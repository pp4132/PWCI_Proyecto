<?php
require_once __DIR__ . '/../Model/conexion.php';
require_once __DIR__ . '/../Model/Resena.php';

class ApiResenasController
{
    private Resena $modelo;

    public function __construct()
    {
        $database = new Database();

        $conexion = $database->conectar();

        $this->modelo = new Resena($conexion);
    }

    /**
     * Responder en formato JSON.
     */
    private function responder(
        int $status,
        array $datos
    ): void {

        http_response_code($status);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $datos,
            JSON_UNESCAPED_UNICODE |
            JSON_INVALID_UTF8_SUBSTITUTE
        );
    }

    /**
     * Consultar reseñas de un curso.
     */
    public function listar(int $idCurso): void
    {
        if ($idCurso < 1) {
            $this->responder(400, [
                'error' => 'ID de curso inválido.'
            ]);
            return;
        }

        try {

            $resumen = $this->modelo->obtenerResumen($idCurso);

            $resenas = $this->modelo->obtenerPorCurso($idCurso);

            $this->responder(200, [
                'success' => true,
                'curso_id' => $idCurso,
                'resumen' => $resumen,
                'resenas' => $resenas
            ]);

        } catch (Throwable $e) {

            error_log($e->getMessage());

            $this->responder(500, [
                'success' => false,
                'error' => 'No se pudieron obtener las reseñas.'
            ]);
        }
    }

    /**
     * Registrar o actualizar una reseña.
     */
    public function crear(int $idCurso): void
    {
        if ($idCurso < 1) {
            $this->responder(400, [
                'error' => 'ID de curso inválido.'
            ]);
            return;
        }

        // Verificar sesión.

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['usuario_id'])) {
            $this->responder(401, [
                'success' => false,
                'error' => 'Debes iniciar sesión.'
            ]);
            return;
        }

        // Obtener el ID del estudiante desde la sesión.
        $idEstudiante = (int) $_SESSION['usuario_id'];

        // Recibir JSON.
        $datos = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($datos)) {
            $this->responder(400, [
                'error' => 'El cuerpo debe ser JSON válido.'
            ]);
            return;
        }

        $calificacion = filter_var(
            $datos['calificacion'] ?? null,
            FILTER_VALIDATE_INT
        );

        $titulo = isset($datos['titulo'])
            ? trim((string) $datos['titulo'])
            : null;

        $resena = trim(
            (string) ($datos['resena'] ?? '')
        );

        // Validaciones.

        if (
            $calificacion === false ||
            $calificacion < 1 ||
            $calificacion > 5
        ) {
            $this->responder(422, [
                'error' => 'Selecciona una calificación del 1 al 5.'
            ]);
            return;
        }

        if ($titulo !== null && mb_strlen($titulo) > 200) {
            $this->responder(422, [
                'error' => 'El título no puede superar 200 caracteres.'
            ]);
            return;
        }

        if ($resena === '' || mb_strlen($resena) > 5000) {
            $this->responder(422, [
                'error' => 'Escribe una reseña de máximo 5000 caracteres.'
            ]);
            return;
        }

        try {

            $resumen = $this->modelo->guardar(
                $idCurso,
                $idEstudiante,
                $calificacion,
                $titulo,
                $resena
            );

            $this->responder(200, [
                'success' => true,
                'mensaje' => 'Reseña guardada correctamente.',
                'resumen' => $resumen
            ]);

        } catch (DomainException $e) {

            $mensaje = $e->getMessage();

            $status = str_contains(
                mb_strtolower($mensaje),
                'curso no existe'
            ) ? 404 : 403;

            $this->responder($status, [
                'error' => $mensaje
            ]);

        } catch (InvalidArgumentException $e) {

            $this->responder(422, [
                'error' => $e->getMessage()
            ]);

        } catch (Throwable $e) {

            error_log($e->getMessage());

            $this->responder(500, [
                'error' => 'No se pudo guardar la reseña.'
            ]);
        }
    }
}