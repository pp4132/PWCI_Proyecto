<?php

session_start();

require_once __DIR__ . '/../Controller/ApiResenasController.php';

header('Cache-Control: no-store');

$metodo = $_SERVER['REQUEST_METHOD'];
$idCurso = filter_var(
    $_GET['curso'] ?? null,
    FILTER_VALIDATE_INT
);

if ($idCurso === false || $idCurso === null || $idCurso < 1) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Debes proporcionar un ID de curso válido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

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
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'error' => 'Método HTTP no permitido.'
        ], JSON_UNESCAPED_UNICODE);
}
