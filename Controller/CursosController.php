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

    $sql = "
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

    $stmt = $db->prepare($sql);
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
        'estudiante' => 'Estudiante'
    ];

    $claveRol = strtolower(
        trim($usuarioDB['nombre_rol'])
    );

    $rolBD = $rolesPermitidos[$claveRol] ?? null;

    if ($rolBD === null || $rolBD !== $rolSesion) {
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
            'rol' => $rolBD
        ]
    ];

    require __DIR__ . '/../View/Estudiante/Cursos.php';

} catch (Throwable $e) {
    error_log('Error en CursosController: ' . $e->getMessage());

    http_response_code(500);
    exit('No se pudo cargar los cursos.');
}
