
<?php

require_once __DIR__ . '/../Model/ENV.php';
require_once __DIR__ . '/../Model/conexion.php';
require_once __DIR__ . '/../Controller/ApiPagosController.php';

header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $datos): never
{
    http_response_code($codigo);
    echo json_encode($datos);
    exit;
}

function verificarFirmaStripe(
    string $payload,
    string $firmaHeader,
    string $secreto
): bool {
    if ($payload === '' || $firmaHeader === '' || $secreto === '') {
        return false;
    }

    $timestamp = null;
    $firmas = [];

    foreach (explode(',', $firmaHeader) as $parte) {
        $elementos = explode('=', trim($parte), 2);

        if (count($elementos) !== 2) {
            continue;
        }

        [$clave, $valor] = $elementos;

        if ($clave === 't') {
            $timestamp = $valor;
        } elseif ($clave === 'v1') {
            $firmas[] = $valor;
        }
    }

    if (
        $timestamp === null
        || !ctype_digit($timestamp)
        || count($firmas) === 0
    ) {
        return false;
    }

    // Rechazar eventos demasiado antiguos o con fecha futura
    // fuera de la tolerancia.
    if (abs(time() - (int) $timestamp) > 300) {
        return false;
    }

    $firmaEsperada = hash_hmac(
        'sha256',
        $timestamp . '.' . $payload,
        $secreto
    );

    foreach ($firmas as $firma) {
        if (
            preg_match('/^[a-f0-9]{64}$/i', $firma)
            && hash_equals($firmaEsperada, $firma)
        ) {
            return true;
        }
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');

    responder(405, [
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
}

try {
    Env::cargar(__DIR__ . '/../.env');

    $secreto = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '';

    $payload = file_get_contents('php://input');
    $firma = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

    if (!verificarFirmaStripe($payload, $firma, $secreto)) {
        responder(400, [
            'success' => false,
            'message' => 'Firma de Stripe inválida.'
        ]);
    }

    $evento = json_decode(
        $payload,
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    $tipo = $evento['type'] ?? '';
    $sesion = $evento['data']['object'] ?? [];

    if (!is_array($sesion)) {
        responder(400, [
            'success' => false,
            'message' => 'Evento inválido.'
        ]);
    }

    // Ignorar eventos que no corresponden a este flujo.
    if (!in_array($tipo, [
        'checkout.session.completed',
        'checkout.session.async_payment_succeeded',
        'checkout.session.async_payment_failed'
    ], true)) {
        responder(200, [
            'success' => true,
            'message' => 'Evento ignorado.'
        ]);
    }

    $sessionId = $sesion['id'] ?? '';

    if ($sessionId === '') {
        responder(400, [
            'success' => false,
            'message' => 'La sesión no tiene identificador.'
        ]);
    }

    $baseDatos = new Database();
    $pdo = $baseDatos->conectar();

    $controller = new ApiPagosController($pdo);

    if ($tipo === 'checkout.session.async_payment_failed') {
        $controller->marcarFallido($sessionId);

        responder(200, [
            'success' => true,
            'message' => 'Pago fallido registrado.'
        ]);
    }

    // checkout.session.completed no significa necesariamente
    // que todos los métodos de pago estén liquidados.
    if (($sesion['payment_status'] ?? '') !== 'paid') {
        // Se deja pendiente hasta recibir confirmación.
        responder(200, [
            'success' => true,
            'message' => 'El pago todavía no está confirmado.'
        ]);
    }

    $metadata = $sesion['metadata'] ?? [];

    $idEstudiante = filter_var(
        $metadata['id_estudiante'] ?? null,
        FILTER_VALIDATE_INT
    );

    $idCurso = filter_var(
        $metadata['id_curso'] ?? null,
        FILTER_VALIDATE_INT
    );

    $referenciaEstudiante = filter_var(
        $sesion['client_reference_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (
        !$idEstudiante
        || !$idCurso
        || $idEstudiante !== $referenciaEstudiante
    ) {
        error_log('Webhook con metadatos inválidos: ' . $sessionId);

        responder(400, [
            'success' => false,
            'message' => 'Los datos del pago no son válidos.'
        ]);
    }

    $importeCentavos = filter_var(
        $sesion['amount_total'] ?? null,
        FILTER_VALIDATE_INT
    );

    $moneda = $sesion['currency'] ?? '';

    if (!$importeCentavos || $importeCentavos <= 0) {
        responder(400, [
            'success' => false,
            'message' => 'El importe confirmado no es válido.'
        ]);
    }

    $controller->confirmarPago(
        $sessionId,
        $idEstudiante,
        $idCurso,
        $importeCentavos,
        $moneda
    );

    responder(200, [
        'success' => true,
        'message' => 'Pago confirmado correctamente.'
    ]);

} catch (JsonException $e) {
    responder(400, [
        'success' => false,
        'message' => 'El evento no contiene JSON válido.'
    ]);

} catch (Throwable $e) {
    error_log('Error procesando webhook Stripe: ' . $e);

    // HTTP 500 permite a Stripe reintentar el evento.
    responder(500, [
        'success' => false,
        'message' => 'No fue posible procesar el evento.'
    ]);
}
