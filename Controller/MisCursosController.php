
<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';

// =====================================================
// 1. VALIDAR ACCESO
// =====================================================

AuthMiddleware::requiereRol([
    'Instructor'
]);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rolSesion = AuthMiddleware::obtenerRol();

try {

    $database = new Database();
    $db = $database->conectar();

    // =====================================================
    // 2. OBTENER INFORMACIÓN DEL INSTRUCTOR
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

    $stmtUsuario = $db->prepare($sqlUsuario);

    $stmtUsuario->execute([
        'id' => $idUsuario
    ]);

    $usuarioDB = $stmtUsuario->fetch();

    if (
        !$usuarioDB ||
        $usuarioDB['estatus'] !== 'Activo'
    ) {

        http_response_code(403);

        exit('La cuenta no está activa o no existe.');

    }

    // =====================================================
    // 3. VALIDAR ROL
    // =====================================================

    $rolesPermitidos = [
        'instructor' => 'Instructor'
    ];

    $claveRol = strtolower(
        trim($usuarioDB['nombre_rol'])
    );

    $rolBD = $rolesPermitidos[$claveRol] ?? null;

    if (
        $rolBD === null ||
        $rolBD !== $rolSesion
    ) {

        http_response_code(403);

        exit('La sesión no coincide con el rol actual.');

    }

    // =====================================================
    // 4. OBTENER CURSOS DEL INSTRUCTOR
    // =====================================================

    $sqlCursos = "

        SELECT

            c.id_curso AS id,

            c.nombre_curso AS titulo,

            c.descripcion,

            c.precio_curso AS precio,

            c.promedio_general,

            c.estatus,

            c.imagen_url,

            c.nivel,

            cat.id_categoria,

            cat.nombre_categoria AS categoria,

            COALESCE(
                inscripciones.total_estudiantes,
                0
            ) AS estudiantes,

            COALESCE(
                lecciones.total_lecciones,
                0
            ) AS total_lecciones,

            COALESCE(
                modulos.total_modulos,
                0
            ) AS total_modulos

        FROM cursos c

        INNER JOIN categorias cat

            ON cat.id_categoria = c.id_categoria

        LEFT JOIN (

            SELECT

                id_curso,

                COUNT(DISTINCT id_estudiante)
                    AS total_estudiantes

            FROM inscripciones

            WHERE estatus = 'Activa'

            GROUP BY id_curso

        ) inscripciones

            ON inscripciones.id_curso = c.id_curso

        LEFT JOIN (

            SELECT

                id_curso,

                COUNT(*) AS total_lecciones

            FROM lecciones

            GROUP BY id_curso

        ) lecciones

            ON lecciones.id_curso = c.id_curso

        LEFT JOIN (

            SELECT

                id_curso,

                COUNT(*) AS total_modulos

            FROM modulos

            GROUP BY id_curso

        ) modulos

            ON modulos.id_curso = c.id_curso

        WHERE c.id_instructor = :id_instructor

        ORDER BY

            c.estatus DESC,

            c.id_curso DESC

    ";

    $stmtCursos = $db->prepare($sqlCursos);

    $stmtCursos->execute([
        'id_instructor' => $idUsuario
    ]);

    $cursosDB = $stmtCursos->fetchAll();

    // =====================================================
    // 5. PREPARAR CURSOS PARA LA VISTA
    // =====================================================

    $cursos = [];

    foreach ($cursosDB as $curso) {

        $publicado = (bool) $curso['estatus'];

        $cursos[] = [

            'id' => (int) $curso['id'],

            'titulo' => $curso['titulo'],

            'descripcion' => $curso['descripcion'],

            'categoria' => $curso['categoria'],

            'estudiantes' => (int) $curso['estudiantes'],

            'precio' => (float) $curso['precio'],

            'estado' => $publicado
                ? 'Publicado'
                : 'Borrador',

            'imagen_url' => $curso['imagen_url'],

            'nivel' => $curso['nivel'],

            'promedio' => (float) $curso['promedio_general'],

            'lecciones' => (int) $curso['total_lecciones'],

            'modulos' => (int) $curso['total_modulos'],

            'color' => 'purple'

        ];

    }

    // =====================================================
    // 6. CALCULAR ESTADÍSTICAS
    // =====================================================

    $totalCursos = count($cursos);

    $cursosPublicados = 0;

    $cursosBorrador = 0;

    $totalEstudiantes = 0;

    foreach ($cursos as $curso) {

        $totalEstudiantes += $curso['estudiantes'];

        if ($curso['estado'] === 'Publicado') {

            $cursosPublicados++;

        } else {

            $cursosBorrador++;

        }

    }

    // =====================================================
    // 7. PREPARAR DATOS PARA LA VISTA
    // =====================================================

    $datos = [

        'usuario' => [

            'id' => (int) $usuarioDB['id_usuario'],

            'nombre' => $usuarioDB['nombre'],

            'apellido' => $usuarioDB['apellidos'],

            'correo' => $usuarioDB['email'],

            'foto' => $usuarioDB['foto'],

            'telefono' => '',

            'rol' => $rolBD

        ],

        'cursos' => $cursos,

        'estadisticas' => [

            'total_cursos' => $totalCursos,

            'publicados' => $cursosPublicados,

            'borradores' => $cursosBorrador,

            'estudiantes' => $totalEstudiantes

        ]

    ];

    // =====================================================
    // 8. CARGAR VISTA
    // =====================================================

    require __DIR__ . '/../View/Instructor/MisCursos.php';

} catch (Throwable $e) {

    error_log(
        'Error en MisCursosController: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('No se pudieron cargar los cursos del instructor.');

}
