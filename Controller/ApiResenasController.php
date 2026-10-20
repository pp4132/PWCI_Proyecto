
<?php

require_once __DIR__ . '/../Model/Resena.php';

class ApiResenasController
{
    private Resena $modelo;

    public function __construct()
    {
        $this->modelo = new Resena();
    }

    private function responder(
        int $status,
        array $datos
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $datos,
            JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }

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
                'curso_id' => $idCurso,
                'resumen' => $resumen,
                'resenas' => $resenas
            ]);
       } catch (Throwable $e) {
    error_log($e->getMessage());

    $this->responder(500, [
        'error' => 'No se pudieron obtener las reseñas.',
        'detalle' => $e->getMessage()
    ]);
}
    }

    public function crear(int $idCurso): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['id_usuario'])) {
            $this->responder(401, [
                'error' => 'Debes iniciar sesión.'
            ]);
            return;
        }

        // El ID se obtiene de la sesión, nunca del JSON recibido.
        $idEstudiante = (int) $_SESSION['id_usuario'];

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

        $resena = trim((string) ($datos['resena'] ?? ''));

        if (
            $calificacion === false ||
            $calificacion < 1 ||
            $calificacion > 5
        ) {
            $this->responder(422, [
                'error' => 'La calificación debe ser un entero del 1 al 5.'
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
                'error' => 'La reseña es obligatoria y no puede superar 5000 caracteres.'
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
                'mensaje' => 'Reseña guardada correctamente.',
                'resumen' => $resumen
            ]);

        } catch (DomainException $e) {
            $status = $e->getMessage() === 'Curso no encontrado.'
                ? 404
                : 403;

            $this->responder($status, [
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
