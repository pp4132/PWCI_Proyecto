
<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';

AuthMiddleware::requiereRol([
    'Administrador'
]);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Validar token CSRF
$tokenSesion = $_SESSION['csrf_token'] ?? '';
$tokenFormulario = $_POST['csrf_token'] ?? '';

if (
    $tokenSesion === '' ||
    !hash_equals($tokenSesion, $tokenFormulario)
) {
    http_response_code(403);
    exit('Solicitud no válida.');
}

$idUsuario = filter_input(
    INPUT_POST,
    'id_usuario',
    FILTER_VALIDATE_INT
);

if (!$idUsuario || $idUsuario <= 0) {
    http_response_code(400);
    exit('ID de usuario inválido.');
}

try {

    $database = new Database();
    $db = $database->conectar();

    $sql = "
        UPDATE usuarios
        SET estatus = 'Activo'
        WHERE id_usuario = :id
          AND estatus = 'Pendiente'
    ";

    $stmt = $db->prepare($sql);

    $stmt->execute([
        'id' => $idUsuario
    ]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['mensaje_admin'] =
            'El usuario no existe o ya no está pendiente.';
    } else {
        $_SESSION['mensaje_admin'] =
            'La cuenta fue activada correctamente.';
    }

    header(
        'Location: ' . url('Controller/ConsultaRepController.php')
    );
    exit;

} catch (Throwable $e) {

    error_log(
        'Error al activar usuario: ' . $e->getMessage()
    );

    http_response_code(500);
    exit('No se pudo actualizar el estado del usuario.');
}
