
<?php

class Respuesta
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una respuesta.
     */
    public function registrar(
        int $idPregunta,
        string $respuesta,
        bool $esCorrecta = false
    ): bool {

        $sql = "INSERT INTO respuestas
                (id_pregunta, respuesta, es_correcta)
                VALUES
                (:pregunta, :respuesta, :correcta)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "pregunta" => $idPregunta,
            "respuesta" => $respuesta,
            "correcta" => $esCorrecta ? 1 : 0
        ]);
    }

    /**
     * Obtener todas las respuestas de una pregunta.
     */
    public function obtenerPorPregunta(
        int $idPregunta
    ): array {

        $sql = "SELECT
                    id_respuesta,
                    id_pregunta,
                    respuesta,
                    es_correcta
                FROM respuestas
                WHERE id_pregunta = :pregunta
                ORDER BY id_respuesta ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "pregunta" => $idPregunta
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener una respuesta específica.
     */
    public function obtenerPorId(
        int $idRespuesta
    ): ?array {

        $sql = "SELECT
                    id_respuesta,
                    id_pregunta,
                    respuesta,
                    es_correcta
                FROM respuestas
                WHERE id_respuesta = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idRespuesta
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Actualizar una respuesta.
     */
    public function actualizar(
        int $idRespuesta,
        string $respuesta,
        bool $esCorrecta
    ): bool {

        $sql = "UPDATE respuestas
                SET respuesta = :respuesta,
                    es_correcta = :correcta
                WHERE id_respuesta = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "respuesta" => $respuesta,
            "correcta" => $esCorrecta ? 1 : 0,
            "id" => $idRespuesta
        ]);
    }

    /**
     * Eliminar una respuesta.
     */
    public function eliminar(
        int $idRespuesta
    ): bool {

        $sql = "DELETE FROM respuestas
                WHERE id_respuesta = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idRespuesta
        ]);
    }

    /**
     * Comprobar si una respuesta es correcta.
     */
    public function esCorrecta(
        int $idRespuesta
    ): bool {

        $sql = "SELECT es_correcta
                FROM respuestas
                WHERE id_respuesta = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idRespuesta
        ]);

        $resultado = $consulta->fetchColumn();

        return $resultado !== false && (bool) $resultado;
    }

    /**
     * Obtener la cantidad de respuestas de una pregunta.
     */
    public function contarPorPregunta(
        int $idPregunta
    ): int {

        $sql = "SELECT COUNT(*)
                FROM respuestas
                WHERE id_pregunta = :pregunta";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "pregunta" => $idPregunta
        ]);

        return (int) $consulta->fetchColumn();
    }
}
