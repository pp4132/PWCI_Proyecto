<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../Controller/ApiResenasController.php';

header('Cache-Control: no-store');

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$idCurso = filter_var(
    $_GET['curso'] ?? null,
    FILTER_VALIDATE_INT
);

header('Content-Type: application/json; charset=utf-8');

if (
    $idCurso === false ||
    $idCurso === null ||
    $idCurso < 1
) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Debes proporcionar un ID de curso válido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    $controller = new ApiResenasController();

    switch ($metodo) {

        case 'GET':

            $controller->listar($idCurso);
            break;

        case 'POST':

            $controller->crear($idCurso);
            break;

        default:

            header('Allow: GET, POST');

            http_response_code(405);

            echo json_encode([
                'success' => false,
                'error' => 'Método HTTP no permitido.'
            ], JSON_UNESCAPED_UNICODE);

            break;
    }

} catch (Throwable $e) {

    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Error interno al procesar las reseñas.'
    ], JSON_UNESCAPED_UNICODE);
}