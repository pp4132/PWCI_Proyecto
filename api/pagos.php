
<?php

session_start();

require_once __DIR__ . '/../Model/ENV.php';
require_once __DIR__ . '/../Model/conexion.php';
require_once __DIR__ . '/../Controller/ApiPagosController.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');

    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

if (empty($_SESSION['usuario_id'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión.'
    ]);
    exit;
}

try {
    $datos = json_decode(
        file_get_contents('php://input'),
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    if (!is_array($datos)) {
        throw new InvalidArgumentException(
            'El cuerpo de la petición no es válido.'
        );
    }

    $idCurso = filter_var(
        $datos['id_curso'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$idCurso || $idCurso <= 0) {
        throw new InvalidArgumentException(
            'El identificador del curso no es válido.'
        );
    }

    $baseDatos = new Database();
    $pdo = $baseDatos->conectar();

    $controller = new ApiPagosController($pdo);

    $resultado = $controller->crearSesion(
        (int) $_SESSION['usuario_id'],
        (int) $idCurso
    );

    echo json_encode($resultado);

} catch (JsonException $e) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'El cuerpo de la petición no contiene JSON válido.'
    ]);

} catch (InvalidArgumentException $e) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

} catch (RuntimeException $e) {
    error_log('Error iniciando pago: ' . $e->getMessage());

    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

} catch (Throwable $e) {
    error_log('Error interno de pagos: ' . $e);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo iniciar el proceso de pago.'
    ]);
}
