
<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';

AuthMiddleware::requiereRol([
    'Administrador'
]);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rolSesion = AuthMiddleware::obtenerRol();

try {

    $database = new Database();
    $db = $database->conectar();

    // =====================================================
    // 1. VALIDAR ADMINISTRADOR
    // =====================================================

    $sqlUsuario = "
        SELECT
            u.id_usuario,
            u.nombre,
            u.apellidos,
            u.email,
            u.foto,
            u.estatus,
            r.nombre_rol
        FROM usuarios u
        INNER JOIN roles r
            ON r.id_rol = u.id_rol
        WHERE u.id_usuario = :id
        LIMIT 1
    ";

    $stmt = $db->prepare($sqlUsuario);
    $stmt->execute(['id' => $idUsuario]);

    $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$usuarioDB ||
        $usuarioDB['estatus'] !== 'Activo'
    ) {
        http_response_code(403);
        exit('La cuenta no está activa o no existe.');
    }

    $rolesPermitidos = [
        'administrador' => 'Administrador'
    ];

    $claveRol = strtolower(
        trim($usuarioDB['nombre_rol'])
    );

    $rolBD = $rolesPermitidos[$claveRol] ?? null;

    if ($rolBD === null || $rolBD !== $rolSesion) {
        http_response_code(403);
        exit('La sesión no coincide con el rol actual.');
    }

    // =====================================================
    // 2. DATOS DEL ADMINISTRADOR
    // =====================================================

    $usuario = [
        'id' => (int) $usuarioDB['id_usuario'],
        'nombre' => $usuarioDB['nombre'],
        'apellido' => $usuarioDB['apellidos'],
        'correo' => $usuarioDB['email'],
        'foto' => $usuarioDB['foto'],
        'telefono' => '',
        'rol' => $rolBD
    ];

    // =====================================================
    // 3. FILTROS DE FECHAS
    // =====================================================

    $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
    $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

    $fechaValida = function ($fecha) {
        $d = DateTime::createFromFormat('!Y-m-d', $fecha);

        return $d && $d->format('Y-m-d') === $fecha;
    };

    if (
        !$fechaValida($fechaInicio) ||
        !$fechaValida($fechaFin) ||
        $fechaInicio > $fechaFin
    ) {
        throw new InvalidArgumentException(
            'El período seleccionado no es válido.'
        );
    }

    // Se utiliza un límite exclusivo para incluir
    // todo el día de la fecha final.

    $fechaFinExclusiva = (new DateTime($fechaFin))
        ->modify('+1 day')
        ->format('Y-m-d');

    $parametros = [
        ':inicio' => $fechaInicio,
        ':fin' => $fechaFinExclusiva
    ];

    // =====================================================
    // 4. ESTADÍSTICAS GENERALES
    // =====================================================

    // Usuarios registrados en el período.

    $sqlUsuarios = "
        SELECT COUNT(*)
        FROM usuarios
        WHERE fecha_registro >= :inicio
          AND fecha_registro < :fin
    ";

    $stmt = $db->prepare($sqlUsuarios);
    $stmt->execute($parametros);

    $totalUsuarios = (int) $stmt->fetchColumn();

    // Cursos publicados.
    // La tabla cursos no tiene fecha de creación.

    $sqlCursos = "
        SELECT COUNT(*)
        FROM cursos
        WHERE estatus = 1
    ";

    $totalCursos = (int) $db
        ->query($sqlCursos)
        ->fetchColumn();

    // Ventas completadas durante el período.

    $sqlVentas = "
        SELECT COUNT(*)
        FROM pagos
        WHERE estado_pago = 'Completado'
          AND fecha_pago >= :inicio
          AND fecha_pago < :fin
    ";

    $stmt = $db->prepare($sqlVentas);
    $stmt->execute($parametros);

    $totalVentas = (int) $stmt->fetchColumn();

    // Ingresos generados durante el período.

    $sqlIngresos = "
        SELECT COALESCE(SUM(monto), 0)
        FROM pagos
        WHERE estado_pago = 'Completado'
          AND fecha_pago >= :inicio
          AND fecha_pago < :fin
    ";

    $stmt = $db->prepare($sqlIngresos);
    $stmt->execute($parametros);

    $totalIngresos = (float) $stmt->fetchColumn();

    // =====================================================
    // 5. ESTADÍSTICAS PARA LA VISTA
    // =====================================================

    $estadisticas = [

        [
            'nombre' => 'Usuarios registrados',
            'valor' => number_format($totalUsuarios),
            'icono' => '◉',
            'color' => 'purple'
        ],

        [
            'nombre' => 'Cursos publicados',
            'valor' => number_format($totalCursos),
            'icono' => '▣',
            'color' => 'blue'
        ],

        [
            'nombre' => 'Cursos vendidos',
            'valor' => number_format($totalVentas),
            'icono' => '✓',
            'color' => 'green'
        ],

        [
            'nombre' => 'Ingresos generados',
            'valor' => '$' . number_format(
                $totalIngresos,
                2
            ),
            'icono' => '$',
            'color' => 'orange'
        ]

    ];

    // =====================================================
    // 6. REPORTES DISPONIBLES
    // =====================================================

    // Reporte de ventas.

    $sqlReporteVentas = "
        SELECT COUNT(*)
        FROM pagos
        WHERE estado_pago = 'Completado'
          AND fecha_pago >= :inicio
          AND fecha_pago < :fin
    ";

    $stmt = $db->prepare($sqlReporteVentas);
    $stmt->execute($parametros);

    $cantidadVentas = (int) $stmt->fetchColumn();

    // Reporte de usuarios por rol.

    $sqlReporteUsuarios = "
        SELECT
            r.nombre_rol,
            COUNT(u.id_usuario) AS cantidad
        FROM roles r
        LEFT JOIN usuarios u
            ON u.id_rol = r.id_rol
            AND u.fecha_registro >= :inicio
            AND u.fecha_registro < :fin
        GROUP BY r.id_rol, r.nombre_rol
        ORDER BY cantidad DESC
    ";

    $stmt = $db->prepare($sqlReporteUsuarios);
    $stmt->execute($parametros);

    $usuariosPorRol = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Reporte de cursos por categoría.

    $sqlReporteCursos = "
        SELECT
            cat.nombre_categoria,
            COUNT(c.id_curso) AS cantidad
        FROM categorias cat
        LEFT JOIN cursos c
            ON c.id_categoria = cat.id_categoria
            AND c.estatus = 1
        GROUP BY cat.id_categoria, cat.nombre_categoria
        ORDER BY cantidad DESC
    ";

    $stmt = $db->query($sqlReporteCursos);

    $cursosPorCategoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Reporte de actividad del período.

    $sqlReporteActividad = "
        SELECT
            (
                SELECT COUNT(*)
                FROM usuarios
                WHERE fecha_registro >= :inicio1
                  AND fecha_registro < :fin1
            )
            +
            (
                SELECT COUNT(*)
                FROM inscripciones
                WHERE fecha_inscripcion >= :inicio2
                  AND fecha_inscripcion < :fin2
            )
            +
            (
                SELECT COUNT(*)
                FROM pagos
                WHERE fecha_pago >= :inicio3
                  AND fecha_pago < :fin3
            )
            AS cantidad
    ";

    $stmt = $db->prepare($sqlReporteActividad);

    $stmt->execute([
        ':inicio1' => $fechaInicio,
        ':fin1' => $fechaFinExclusiva,
        ':inicio2' => $fechaInicio,
        ':fin2' => $fechaFinExclusiva,
        ':inicio3' => $fechaInicio,
        ':fin3' => $fechaFinExclusiva
    ]);

    $cantidadActividad = (int) $stmt->fetchColumn();

    $periodo = date('d/m/Y', strtotime($fechaInicio))
        . ' - ' .
        date('d/m/Y', strtotime($fechaFin));

    $reportes = [

        [
            'id' => 1,
            'tipo' => 'Ventas',
            'titulo' => 'Ventas de cursos',
            'descripcion' => 'Resumen de las transacciones completadas durante el período.',
            'cantidad' => number_format($cantidadVentas),
            'periodo' => $periodo,
            'estado' => 'Disponible'
        ],

        [
            'id' => 2,
            'tipo' => 'Usuarios',
            'titulo' => 'Registro de usuarios',
            'descripcion' => 'Usuarios registrados durante el período, distribuidos por rol.',
            'cantidad' => number_format($totalUsuarios),
            'periodo' => $periodo,
            'estado' => 'Disponible'
        ],

        [
            'id' => 3,
            'tipo' => 'Cursos',
            'titulo' => 'Cursos publicados',
            'descripcion' => 'Cursos publicados actualmente, distribuidos por categoría.',
            'cantidad' => number_format($totalCursos),
            'periodo' => 'Actualidad',
            'estado' => 'Disponible'
        ],

        [
            'id' => 4,
            'tipo' => 'Actividad',
            'titulo' => 'Actividad de la plataforma',
            'descripcion' => 'Registro de usuarios, inscripciones y pagos realizados.',
            'cantidad' => number_format($cantidadActividad),
            'periodo' => $periodo,
            'estado' => 'Disponible'
        ]

    ];

    // =====================================================
    // 7. ACTIVIDAD RECIENTE
    // =====================================================

    $sqlActividad = "

        SELECT *
        FROM (

            SELECT
                'Nuevo usuario' AS tipo,
                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellidos,
                    ' se registró'
                ) AS titulo,
                CONCAT(
                    'Nuevo usuario con rol ',
                    r.nombre_rol
                ) AS descripcion,
                u.fecha_registro AS fecha,
                '+' AS icono,
                'blue' AS color
            FROM usuarios u
            INNER JOIN roles r
                ON r.id_rol = u.id_rol

            UNION ALL

            SELECT
                'Nueva inscripción' AS tipo,
                CONCAT(
                    'Inscripción al curso: ',
                    c.nombre_curso
                ) AS titulo,
                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellidos,
                    ' se inscribió en un curso.'
                ) AS descripcion,
                i.fecha_inscripcion AS fecha,
                '✓' AS icono,
                'purple' AS color
            FROM inscripciones i
            INNER JOIN usuarios u
                ON u.id_usuario = i.id_estudiante
            INNER JOIN cursos c
                ON c.id_curso = i.id_curso

            UNION ALL

            SELECT
                'Venta' AS tipo,
                CONCAT(
                    'Nueva venta: ',
                    c.nombre_curso
                ) AS titulo,
                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellidos,
                    ' realizó una compra.'
                ) AS descripcion,
                p.fecha_pago AS fecha,
                '$' AS icono,
                'green' AS color
            FROM pagos p
            INNER JOIN usuarios u
                ON u.id_usuario = p.id_estudiante
            INNER JOIN cursos c
                ON c.id_curso = p.id_curso
            WHERE p.estado_pago = 'Completado'

        ) AS movimientos

        ORDER BY fecha DESC
        LIMIT 10
    ";

    $stmt = $db->query($sqlActividad);

    $actividad = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =====================================================
    // 8. FORMATEAR FECHAS DE ACTIVIDAD
    // =====================================================

    foreach ($actividad as &$item) {

        $fecha = new DateTime($item['fecha']);
        $ahora = new DateTime();

        $diferencia = $fecha->diff($ahora);

        if ($diferencia->y > 0) {

            $item['fecha'] = 'Hace '
                . $diferencia->y
                . ' años';

        } elseif ($diferencia->m > 0) {

            $item['fecha'] = 'Hace '
                . $diferencia->m
                . ' meses';

        } elseif ($diferencia->d > 0) {

            $item['fecha'] = 'Hace '
                . $diferencia->d
                . ' días';

        } elseif ($diferencia->h > 0) {

            $item['fecha'] = 'Hace '
                . $diferencia->h
                . ' horas';

        } elseif ($diferencia->i > 0) {

            $item['fecha'] = 'Hace '
                . $diferencia->i
                . ' minutos';

        } else {

            $item['fecha'] = 'Hace unos segundos';

        }
    }

    unset($item);

    // =====================================================
    // 9. ENVIAR DATOS A LA VISTA
    // =====================================================

    $datos = [

        'usuario' => $usuario,

        'estadisticas' => $estadisticas,

        'reportes' => $reportes,

        'actividad' => $actividad,

        'usuariosPorRol' => $usuariosPorRol,

        'cursosPorCategoria' => $cursosPorCategoria,

        'filtros' => [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]

    ];

    require __DIR__ . '/../View/Admin/ConsultaReportes.php';

} catch (Throwable $e) {

    error_log(
        'Error en ConsultaRepController: '
        . $e->getMessage()
    );

    http_response_code(500);

    exit('No se pudo cargar los reportes.');
}
