
<?php

class StripeService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct(
        string $secretKey,
        string $baseUrl
    ) {
        if ($secretKey === '') {
            throw new RuntimeException(
                'La clave secreta de Stripe no está configurada.'
            );
        }

        if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(
                'La URL base no es válida.'
            );
        }

        $this->secretKey = $secretKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function crearSesion(
        int $idCurso,
        int $idEstudiante,
        string $nombreCurso,
        float $precio,
        string $email = '',
        ?string $idempotencyKey = null
    ): array {

        if ($idCurso <= 0 || $idEstudiante <= 0) {
            throw new InvalidArgumentException(
                'Los identificadores no son válidos.'
            );
        }

        if (
            trim($nombreCurso) === ''
            || $precio <= 0
            || !is_finite($precio)
        ) {
            throw new InvalidArgumentException(
                'Los datos del curso no son válidos.'
            );
        }

        if (!extension_loaded('curl')) {
            throw new RuntimeException(
                'La extensión cURL no está habilitada.'
            );
        }

        $importe = (int) round($precio * 100);

        if ($importe <= 0) {
            throw new InvalidArgumentException(
                'El importe no es válido.'
            );
        }

        if (
            $idempotencyKey !== null
            && (
                strlen($idempotencyKey) > 255
                || trim($idempotencyKey) === ''
            )
        ) {
            throw new InvalidArgumentException(
                'La clave de idempotencia no es válida.'
            );
        }

        $datos = [
            'mode' => 'payment',

            'client_reference_id' =>
                (string) $idEstudiante,

            'line_items[0][price_data][currency]' => 'mxn',

            'line_items[0][price_data][unit_amount]' =>
                $importe,

            'line_items[0][price_data][product_data][name]' =>
                $nombreCurso,

            'line_items[0][quantity]' => 1,

            'metadata[id_curso]' => (string) $idCurso,

            'metadata[id_estudiante]' =>
                (string) $idEstudiante,

            'payment_intent_data[metadata][id_curso]' =>
                (string) $idCurso,

            'payment_intent_data[metadata][id_estudiante]' =>
                (string) $idEstudiante,

            'success_url' =>
                $this->baseUrl
                . '/View/Estudiante/Cursos.php?pago=procesando'
                . '&session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' =>
                $this->baseUrl
                . '/View/Public/CursoPreambulo.php?id='
                . $idCurso
                . '&pago=cancelado',
        ];

        if (
            $email !== ''
            && filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            $datos['customer_email'] = $email;
        }

        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];

        if ($idempotencyKey !== null) {
            $headers[] =
                'Idempotency-Key: ' . $idempotencyKey;
        }

        $ch = curl_init(
            'https://api.stripe.com/v1/checkout/sessions'
        );

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($datos),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->secretKey . ':',
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $respuesta = curl_exec($ch);

        $codigo = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        $error = curl_error($ch);

        curl_close($ch);

        if ($respuesta === false) {
            error_log('Error cURL Stripe: ' . $error);

            throw new RuntimeException(
                'No fue posible conectar con Stripe.'
            );
        }

        $resultado = json_decode($respuesta, true);

        if ($codigo < 200 || $codigo >= 300) {
            error_log(
                'Stripe HTTP ' . $codigo . ': ' . $respuesta
            );

            throw new RuntimeException(
                'Stripe no pudo crear la sesión de pago.'
            );
        }

        if (
            !is_array($resultado)
            || empty($resultado['id'])
            || empty($resultado['url'])
        ) {
            throw new RuntimeException(
                'Stripe devolvió una sesión inválida.'
            );
        }

        return $resultado;
    }
}
