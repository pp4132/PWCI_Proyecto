<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';

AuthMiddleware::requiereRol([
    'Estudiante'
]);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rolSesion = AuthMiddleware::obtenerRol();

try {

    $database = new Database();
    $db = $database->conectar();

    // =====================================================
    // 1. OBTENER INFORMACIÓN DEL ESTUDIANTE
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
    // 2. VALIDAR ROL
    // =====================================================

    $rolesPermitidos = [
        'estudiante' => 'Estudiante'
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
    // 3. OBTENER CURSOS DEL ESTUDIANTE
    // =====================================================

    $sqlCursos = "

        SELECT

            c.id_curso AS id,

            c.nombre_curso AS titulo,

            c.descripcion,

            c.imagen_url,

            cat.nombre_categoria AS categoria,

            CONCAT(
                instructor.nombre,
                ' ',
                instructor.apellidos
            ) AS instructor,

            COALESCE(lecciones.total_lecciones, 0)
                AS lecciones,

            COALESCE(lecciones.lecciones_completadas, 0)
                AS completadas,

            CASE

                WHEN COALESCE(lecciones.total_lecciones, 0) > 0

                THEN ROUND(
                    (
                        lecciones.lecciones_completadas /
                        lecciones.total_lecciones
                    ) * 100,
                    0
                )

                ELSE 0

            END AS progreso,

            i.estatus AS estado_inscripcion,

            i.fecha_inscripcion,

            CASE

                WHEN i.estatus = 'Terminada'
                    OR (
                        COALESCE(lecciones.total_lecciones, 0) > 0
                        AND lecciones.lecciones_completadas >=
                            lecciones.total_lecciones
                    )

                THEN 1

                ELSE 0

            END AS completado

        FROM inscripciones i

        INNER JOIN cursos c
            ON c.id_curso = i.id_curso

        INNER JOIN categorias cat
            ON cat.id_categoria = c.id_categoria

        INNER JOIN usuarios instructor
            ON instructor.id_usuario = c.id_instructor

        LEFT JOIN (

            SELECT

                l.id_curso,

                COUNT(l.id_leccion) AS total_lecciones,

                SUM(
                    CASE

                        WHEN pl.completada = 1 THEN 1

                        ELSE 0

                    END
                ) AS lecciones_completadas

            FROM lecciones l

            LEFT JOIN progreso_leccion pl

                ON pl.id_leccion = l.id_leccion

                AND pl.id_estudiante = :id_progreso

            GROUP BY l.id_curso

        ) lecciones

            ON lecciones.id_curso = c.id_curso

        WHERE
            i.id_estudiante = :id_estudiante

            AND i.estatus IN ('Activa', 'Terminada')

        ORDER BY

            completado ASC,

            CASE
                WHEN i.estatus = 'Activa' THEN 0
                ELSE 1
            END ASC,

            i.fecha_inscripcion DESC

    ";

    $stmtCursos = $db->prepare($sqlCursos);

    $stmtCursos->execute([
        'id_progreso' => $idUsuario,
        'id_estudiante' => $idUsuario
    ]);

    $cursosDB = $stmtCursos->fetchAll();

    // =====================================================
    // 4. PREPARAR DATOS PARA LA VISTA
    // =====================================================

    $cursos = [];

    foreach ($cursosDB as $curso) {

        $completado = (bool) $curso['completado'];

        $cursos[] = [

            'id' => (int) $curso['id'],

            'titulo' => $curso['titulo'],

            'categoria' => $curso['categoria'],

            'instructor' => $curso['instructor'],

            'progreso' => (int) $curso['progreso'],

            'lecciones' => (int) $curso['lecciones'],

            'completadas' => (int) $curso['completadas'],

            'estado' => $completado
                ? 'Completado'
                : 'En progreso',

            'imagen_url' => $curso['imagen_url'],

            'fecha_inscripcion' => $curso['fecha_inscripcion'],

            'color' => 'purple'

        ];

    }

    // =====================================================
    // 5. ENVIAR INFORMACIÓN A LA VISTA
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

        'cursos' => $cursos

    ];

    require __DIR__ . '/../View/Estudiante/Cursos.php';

} catch (Throwable $e) {

    error_log(
        'Error en CursosController: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('No se pudo cargar los cursos.');

}
