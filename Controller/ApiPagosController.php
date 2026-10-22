
<?php

require_once __DIR__ . '/../Model/Curso.php';
require_once __DIR__ . '/../Model/Pago.php';
require_once __DIR__ . '/../Model/StripeService.php';

class ApiPagosController
{
    private Curso $cursoModel;
    private Pago $pagoModel;
    private StripeService $stripe;

    public function __construct(PDO $conexion)
    {
        $this->cursoModel = new Curso($conexion);
        $this->pagoModel = new Pago($conexion);

        // conexion.php carga el .env al conectar, pero
        // cargamos explícitamente la configuración por claridad.
        Env::cargar(__DIR__ . '/../.env');

        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $baseUrl = $_ENV['APP_BASE_URL'] ?? '';

        if ($baseUrl === '') {
            throw new RuntimeException(
                'Falta configurar APP_BASE_URL.'
            );
        }

        $this->stripe = new StripeService(
            $secretKey,
            $baseUrl
        );
    }

    public function crearSesion(
        int $idEstudiante,
        int $idCurso
    ): array {
        if ($idEstudiante <= 0 || $idCurso <= 0) {
            throw new InvalidArgumentException(
                'Los identificadores no son válidos.'
            );
        }

        $curso = $this->cursoModel->obtenerParaCompra($idCurso);

        if (!$curso) {
            throw new RuntimeException(
                'El curso no existe o no está disponible.'
            );
        }

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

        $precio = (float) $curso['precio_curso'];

        if ($precio <= 0) {
            throw new RuntimeException(
                'Este endpoint solo permite cursos de pago.'
            );
        }

        // Crear Checkout con el precio obtenido de MySQL.
        $sesion = $this->stripe->crearSesion(
            $idCurso,
            $idEstudiante,
            $curso['nombre_curso'],
            $precio
        );

        // Registrar la sesión antes de enviar su URL al navegador.
        $this->pagoModel->crearPendiente(
            $idEstudiante,
            $idCurso,
            $precio,
            $sesion['id']
        );

        return [
            'success' => true,
            'checkout_url' => $sesion['url'],
            'session_id' => $sesion['id']
        ];
    }

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

    public function marcarFallido(string $sessionId): void
    {
        $this->pagoModel->marcarFallido($sessionId);
    }
}
