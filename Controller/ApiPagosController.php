
<?php

require_once __DIR__ . '/../Model/ENV.php';
require_once __DIR__ . '/../Model/Curso.php';
require_once __DIR__ . '/../Model/Pago.php';
require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../Model/StripeService.php';

class ApiPagosController
{
    private Curso $cursoModel;
    private Pago $pagoModel;
    private Usuario $usuarioModel;
    private StripeService $stripe;

    public function __construct(PDO $conexion)
    {
        $this->cursoModel = new Curso($conexion);
        $this->pagoModel = new Pago($conexion);
        $this->usuarioModel = new Usuario($conexion);

        Env::cargar(__DIR__ . '/../.env');

        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $baseUrl = $_ENV['APP_BASE_URL'] ?? '';

        if ($secretKey === '' || $baseUrl === '') {
            throw new RuntimeException(
                'La configuración de Stripe está incompleta.'
            );
        }

        $this->stripe = new StripeService(
            $secretKey,
            $baseUrl
        );
    }

    /**
     * Crear una sesión de pago para un estudiante.
     */
    public function crearSesion(
        int $idEstudiante,
        int $idCurso
    ): array {

        if (
            $idEstudiante <= 0
            || $idCurso <= 0
        ) {
            throw new InvalidArgumentException(
                'Los identificadores no son válidos.'
            );
        }

        // 1. Obtener información del estudiante.
        $usuario = $this->usuarioModel->obtenerPorId(
            $idEstudiante
        );

        if (!$usuario || $usuario['estatus'] !== 'Activo') {
            throw new RuntimeException(
                'El usuario no está disponible para realizar compras.'
            );
        }

        // 2. Obtener información del curso.
        $curso = $this->cursoModel->obtenerParaCompra(
            $idCurso
        );

        if (!$curso) {
            throw new RuntimeException(
                'El curso no existe o no está disponible.'
            );
        }

        // 3. Comprobar si ya está inscrito.
        if (
            $this->cursoModel->estudianteInscrito(
                $idEstudiante,
                $idCurso
            )
        ) {
            throw new RuntimeException(
                'Ya estás inscrito en este curso.'
            );
        }

        // 4. Obtener el precio real desde MySQL.
        $precio = (float) $curso['precio_curso'];

        if (
            $precio <= 0
            || !is_finite($precio)
        ) {
            throw new RuntimeException(
                'El precio del curso no es válido.'
            );
        }

        // 5. Crear la sesión de Stripe.
        $sesion = $this->stripe->crearSesion(
            $idCurso,
            $idEstudiante,
            $curso['nombre_curso'],
            $precio,
            $usuario['email']
        );

        // 6. Registrar el pago pendiente.
        try {

            $this->pagoModel->crearPendiente(
                $idEstudiante,
                $idCurso,
                $precio,
                $sesion['id']
            );

        } catch (Throwable $e) {

            error_log(
                'No se pudo registrar el pago '
                . $sesion['id']
                . ': '
                . $e->getMessage()
            );

            throw new RuntimeException(
                'No se pudo registrar la operación de pago.'
            );
        }

        // 7. Devolver información al navegador.
        return [
            'success' => true,
            'checkout_url' => $sesion['url'],
            'session_id' => $sesion['id']
        ];
    }

    /**
     * Confirmar un pago desde el webhook validado.
     */
    public function confirmarPago(
        string $sessionId,
        int $idEstudiante,
        int $idCurso,
        int $importeCentavos,
        string $moneda
    ): void {

        $this->pagoModel->confirmarPago(
            $sessionId,
            $idEstudiante,
            $idCurso,
            $importeCentavos,
            $moneda
        );
    }

    /**
     * Marcar un pago como fallido.
     */
    public function marcarFallido(
        string $sessionId
    ): void {

        if (trim($sessionId) === '') {
            throw new InvalidArgumentException(
                'El identificador de sesión no es válido.'
            );
        }

        $this->pagoModel->marcarFallido(
            $sessionId
        );
    }
}
