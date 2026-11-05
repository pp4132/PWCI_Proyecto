<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';

// =====================================================
// SEGURIDAD
// =====================================================

AuthMiddleware::requiereRol(['Instructor']);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rolSesion = AuthMiddleware::obtenerRol();

try {

    $database = new Database();
    $db = $database->conectar();

    // =====================================================
    // 1. DATOS DEL INSTRUCTOR
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

    $usuarioDB = $stmt->fetch();

    if (
        !$usuarioDB ||
        $usuarioDB['estatus'] !== 'Activo'
    ) {
        http_response_code(403);
        exit('La cuenta no está activa o no existe.');
    }

    $claveRol = strtolower(trim($usuarioDB['nombre_rol']));

    if (
        $claveRol !== 'instructor' ||
        $rolSesion !== 'Instructor'
    ) {
        http_response_code(403);
        exit('La sesión no coincide con el rol actual.');
    }

    $datos = [
        'usuario' => [
            'id' => (int) $usuarioDB['id_usuario'],
            'nombre' => $usuarioDB['nombre'],
            'apellido' => $usuarioDB['apellidos'],
            'correo' => $usuarioDB['email'],
            'foto' => $usuarioDB['foto'],
            'telefono' => '',
            'rol' => $claveRol === 'instructor'
                ? 'Instructor'
                : ''
        ]
    ];

    // =====================================================
    // 2. RESUMEN DE VENTAS
    // =====================================================

    $sqlResumen = "
        SELECT

            COUNT(p.id_pago) AS total_ventas,

            COALESCE(
                SUM(p.monto),
                0
            ) AS ingresos_totales,

            COALESCE(
                SUM(
                    CASE
                        WHEN YEAR(p.fecha_pago) = YEAR(CURDATE())
                        AND MONTH(p.fecha_pago) = MONTH(CURDATE())
                        THEN p.monto
                        ELSE 0
                    END
                ),
                0
            ) AS ingresos_mes,

            COUNT(
                CASE
                    WHEN YEAR(p.fecha_pago) = YEAR(CURDATE())
                    AND MONTH(p.fecha_pago) = MONTH(CURDATE())
                    THEN p.id_pago
                END
            ) AS ventas_mes,

            COALESCE(
                SUM(
                    CASE
                        WHEN p.fecha_pago >= DATE_FORMAT(
                            DATE_SUB(CURDATE(), INTERVAL 1 MONTH),
                            '%Y-%m-01'
                        )
                        AND p.fecha_pago < DATE_FORMAT(
                            CURDATE(),
                            '%Y-%m-01'
                        )
                        THEN p.monto
                        ELSE 0
                    END
                ),
                0
            ) AS ingresos_mes_anterior

        FROM pagos p

        INNER JOIN cursos c
            ON c.id_curso = p.id_curso

        WHERE c.id_instructor = :idInstructor
        AND p.estado_pago = 'Completado'
    ";

    $stmt = $db->prepare($sqlResumen);

    $stmt->execute([
        'idInstructor' => $idUsuario
    ]);

    $resumenDB = $stmt->fetch();

    $ingresosTotales = (float) $resumenDB['ingresos_totales'];

    $totalVentas = (int) $resumenDB['total_ventas'];

    $ingresosMes = (float) $resumenDB['ingresos_mes'];

    $ingresosMesAnterior = (float) $resumenDB['ingresos_mes_anterior'];

    $ticketPromedio = $totalVentas > 0
        ? $ingresosTotales / $totalVentas
        : 0;

    $variacionMes = 0;

    if ($ingresosMesAnterior > 0) {

        $variacionMes = (
            ($ingresosMes - $ingresosMesAnterior)
            / $ingresosMesAnterior
        ) * 100;

    } elseif ($ingresosMes > 0) {

        $variacionMes = 100;

    }

    // =====================================================
    // 3. RESUMEN PARA LA VISTA
    // =====================================================

    $datos['resumen'] = [

        [
            'titulo' => 'Ingresos totales',
            'valor' => '$' . number_format($ingresosTotales, 2),
            'descripcion' => 'Ingresos acumulados',
            'icono' => '$',
            'color' => 'green'
        ],

        [
            'titulo' => 'Ventas',
            'valor' => number_format($totalVentas),
            'descripcion' => 'Pagos completados',
            'icono' => '↗',
            'color' => 'blue'
        ],

        [
            'titulo' => 'Ingresos este mes',
            'valor' => '$' . number_format($ingresosMes, 2),
            'descripcion' => sprintf(
                '%+.1f%% respecto al mes anterior',
                $variacionMes
            ),
            'icono' => '◷',
            'color' => 'purple'
        ],

        [
            'titulo' => 'Ticket promedio',
            'valor' => '$' . number_format($ticketPromedio, 2),
            'descripcion' => 'Por venta realizada',
            'icono' => '◎',
            'color' => 'orange'
        ]

    ];

    // =====================================================
    // 4. VENTAS RECIENTES
    // =====================================================

    $sqlVentas = "
        SELECT
            p.id_pago,
            p.monto,
            p.fecha_pago,
            p.estado_pago,
            p.metodo_pago,

            c.id_curso,
            c.nombre_curso,

            u.id_usuario,
            u.nombre,
            u.apellidos

        FROM pagos p

        INNER JOIN cursos c
            ON c.id_curso = p.id_curso

        INNER JOIN usuarios u
            ON u.id_usuario = p.id_estudiante

        WHERE c.id_instructor = :idInstructor

        ORDER BY p.fecha_pago DESC, p.id_pago DESC

        LIMIT 10
    ";

    $stmt = $db->prepare($sqlVentas);

    $stmt->execute([
        'idInstructor' => $idUsuario
    ]);

    $ventasDB = $stmt->fetchAll();

    $datos['ventas'] = [];

    foreach ($ventasDB as $venta) {

        $nombreEstudiante = trim(
            $venta['nombre'] . ' ' . $venta['apellidos']
        );

        $datos['ventas'][] = [

            'id' => (int) $venta['id_pago'],

            'curso' => $venta['nombre_curso'],

            'id_curso' => (int) $venta['id_curso'],

            'estudiante' => $nombreEstudiante,

            'fecha' => date(
                'd/m/Y H:i',
                strtotime($venta['fecha_pago'])
            ),

            'importe' => (float) $venta['monto'],

            'estado' => $venta['estado_pago'],

            'metodo' => $venta['metodo_pago'],

            'inicial' => strtoupper(
                substr($nombreEstudiante, 0, 1)
            )

        ];

    }

    // =====================================================
    // 5. CURSOS CON MÁS VENTAS
    // =====================================================

    $sqlRanking = "
        SELECT

            c.id_curso,
            c.nombre_curso,
            c.imagen_url,

            COUNT(p.id_pago) AS total_ventas,

            COALESCE(
                SUM(p.monto),
                0
            ) AS ingresos

        FROM cursos c

        INNER JOIN pagos p
            ON p.id_curso = c.id_curso

        WHERE c.id_instructor = :idInstructor
        AND p.estado_pago = 'Completado'

        GROUP BY
            c.id_curso,
            c.nombre_curso,
            c.imagen_url

        ORDER BY total_ventas DESC, ingresos DESC

        LIMIT 5
    ";

    $stmt = $db->prepare($sqlRanking);

    $stmt->execute([
        'idInstructor' => $idUsuario
    ]);

    $rankingDB = $stmt->fetchAll();

    $colores = [
        'purple',
        'blue',
        'green',
        'orange',
        'pink'
    ];

    $datos['rankingCursos'] = [];

    foreach ($rankingDB as $index => $curso) {

        $datos['rankingCursos'][] = [

            'posicion' => $index + 1,

            'id' => (int) $curso['id_curso'],

            'titulo' => $curso['nombre_curso'],

            'ventas' => (int) $curso['total_ventas'],

            'ingresos' => (float) $curso['ingresos'],

            'imagen_url' => $curso['imagen_url'],

            'color' => $colores[$index % count($colores)]

        ];

    }

    // =====================================================
    // 6. INGRESOS DE LOS ÚLTIMOS 12 MESES
    // =====================================================

    $sqlGrafica = "
        SELECT

            DATE_FORMAT(
                p.fecha_pago,
                '%Y-%m'
            ) AS periodo,

            COALESCE(
                SUM(p.monto),
                0
            ) AS ingresos

        FROM pagos p

        INNER JOIN cursos c
            ON c.id_curso = p.id_curso

        WHERE c.id_instructor = :idInstructor

        AND p.estado_pago = 'Completado'

        AND p.fecha_pago >= DATE_FORMAT(
            DATE_SUB(CURDATE(), INTERVAL 11 MONTH),
            '%Y-%m-01'
        )

        AND p.fecha_pago < DATE_FORMAT(
            DATE_ADD(CURDATE(), INTERVAL 1 MONTH),
            '%Y-%m-01'
        )

        GROUP BY DATE_FORMAT(
            p.fecha_pago,
            '%Y-%m'
        )

        ORDER BY periodo ASC
    ";

    $stmt = $db->prepare($sqlGrafica);

    $stmt->execute([
        'idInstructor' => $idUsuario
    ]);

    $graficaDB = $stmt->fetchAll();

    $ingresosPorMes = [];

    foreach ($graficaDB as $fila) {

        $ingresosPorMes[$fila['periodo']] =
            (float) $fila['ingresos'];

    }

    $meses = [
        1 => 'Ene',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Abr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Ago',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dic'
    ];

    $datos['grafica'] = [];

    $fechaInicio = new DateTimeImmutable(
        date('Y-m-01')
    );

    $fechaInicio = $fechaInicio->modify('-11 months');

    for ($i = 0; $i < 12; $i++) {

        $fecha = $fechaInicio->modify("+{$i} months");

        $periodo = $fecha->format('Y-m');

        $datos['grafica'][] = [

            'mes' => $meses[(int) $fecha->format('n')],

            'periodo' => $periodo,

            'ingresos' => $ingresosPorMes[$periodo] ?? 0

        ];

    }

    // =====================================================
    // 7. CARGAR VISTA
    // =====================================================

    require __DIR__ . '/../View/Instructor/Ventas.php';

} catch (Throwable $e) {

    error_log(
        'Error en VentasController: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('No se pudo cargar la información de ventas.');

}
