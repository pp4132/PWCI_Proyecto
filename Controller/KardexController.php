<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';

// =====================================================
// 1. VALIDAR ACCESO
// =====================================================
AuthMiddleware::requiereRol([
    'Estudiante'
]);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rolSesion = AuthMiddleware::obtenerRol();

try {

    $database = new Database();
    $db = $database->conectar();

    // =====================================================
    // 2. OBTENER INFORMACIÓN DEL ESTUDIANTE
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
    // 4. OBTENER HISTORIAL ACADÉMICO
    // =====================================================

    $sqlKardex = "

        SELECT

            c.id_curso AS id,

            c.nombre_curso AS curso,

            cat.nombre_categoria AS categoria,

            CONCAT(
                instructor.nombre,
                ' ',
                instructor.apellidos
            ) AS instructor,

            i.fecha_inscripcion,

            i.estatus AS estado_inscripcion,

            COALESCE(
                lecciones.total_lecciones,
                0
            ) AS total_lecciones,

            COALESCE(
                lecciones.lecciones_completadas,
                0
            ) AS lecciones_completadas,

            CASE

                WHEN i.estatus = 'Terminada'
                    THEN 100

                WHEN COALESCE(
                    lecciones.total_lecciones,
                    0
                ) = 0
                    THEN 0

                ELSE ROUND(
                    (
                        lecciones.lecciones_completadas /
                        lecciones.total_lecciones
                    ) * 100,
                    0
                )

            END AS progreso,

            CASE

                WHEN i.estatus = 'Terminada'
                    THEN 'Completado'

                WHEN i.estatus = 'Cancelada'
                    THEN 'Cancelada'

                WHEN COALESCE(
                    lecciones.total_lecciones,
                    0
                ) > 0

                AND lecciones.lecciones_completadas >=
                    lecciones.total_lecciones

                    THEN 'Completado'

                ELSE 'En progreso'

            END AS estado

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

                        WHEN pl.completada = 1
                            THEN 1

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

        WHERE i.id_estudiante = :id_estudiante

        ORDER BY

            CASE

                WHEN i.estatus = 'Cancelada'
                    THEN 2

                WHEN i.estatus = 'Terminada'
                    THEN 1

                ELSE 0

            END ASC,

            i.fecha_inscripcion DESC

    ";

    $stmtKardex = $db->prepare($sqlKardex);

    $stmtKardex->execute([
        'id_progreso' => $idUsuario,
        'id_estudiante' => $idUsuario
    ]);

    $kardexDB = $stmtKardex->fetchAll();

    // =====================================================
    // 5. PREPARAR HISTORIAL PARA LA VISTA
    // =====================================================

    $kardex = [];

    foreach ($kardexDB as $curso) {

        $kardex[] = [

            'id' => (int) $curso['id'],

            'curso' => $curso['curso'],

            'categoria' => $curso['categoria'],

            'instructor' => $curso['instructor'],

            'fecha' => date(
                'd/m/Y',
                strtotime($curso['fecha_inscripcion'])
            ),

            'progreso' => (int) $curso['progreso'],

            'estado' => $curso['estado'],

            'estado_inscripcion' => $curso['estado_inscripcion'],

            'lecciones' => (int) $curso['total_lecciones'],

            'completadas' => (int) $curso['lecciones_completadas']

        ];

    }

    // =====================================================
    // 6. OBTENER CERTIFICADOS DEL ESTUDIANTE
    // =====================================================

    $sqlCertificados = "

        SELECT

            cert.id_certificado,

            cert.fecha_terminacion,

            c.id_curso,

            c.nombre_curso AS curso,

            CONCAT(
                instructor.nombre,
                ' ',
                instructor.apellidos
            ) AS instructor

        FROM certificados cert

        INNER JOIN cursos c
            ON c.id_curso = cert.id_curso

        INNER JOIN usuarios instructor
            ON instructor.id_usuario = cert.id_instructor

        WHERE cert.id_estudiante = :id_estudiante

        ORDER BY cert.fecha_terminacion DESC

    ";

    $stmtCertificados = $db->prepare($sqlCertificados);

    $stmtCertificados->execute([
        'id_estudiante' => $idUsuario
    ]);

    $certificadosDB = $stmtCertificados->fetchAll();

    // =====================================================
    // 7. PREPARAR CERTIFICADOS
    // =====================================================

    $certificados = [];

    foreach ($certificadosDB as $certificado) {

        $certificados[] = [

            'id' => (int) $certificado['id_certificado'],

            'curso' => $certificado['curso'],

            'instructor' => $certificado['instructor'],

            'fecha' => date(
                'd/m/Y',
                strtotime($certificado['fecha_terminacion'])
            ),

            'codigo' => 'CERT-' .
                $certificado['id_certificado']

        ];

    }

    // =====================================================
    // 8. PREPARAR INFORMACIÓN DEL USUARIO
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

        'kardex' => $kardex,

        'certificados' => $certificados

    ];

    // =====================================================
    // 9. CARGAR VISTA
    // =====================================================

    require __DIR__ . '/../View/Estudiante/Kardex.php';

} catch (Throwable $e) {

    error_log(
        'Error en KardexController: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('No se pudo cargar el Kardex.');

}
