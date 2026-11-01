
<?php

class Pregunta
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una pregunta.
     */
    public function registrar(
        int $idLeccion,
        string $pregunta
    ): bool {

        $sql = "INSERT INTO preguntas
                (id_leccion, pregunta)
                VALUES
                (:leccion, :pregunta)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "leccion" => $idLeccion,
            "pregunta" => $pregunta
        ]);
    }

    /**
     * Obtener preguntas de una lección.
     */
    public function obtenerPorLeccion(
        int $idLeccion
    ): array {

        $sql = "SELECT
                    id_pregunta,
                    id_leccion,
                    pregunta
                FROM preguntas
                WHERE id_leccion = :leccion
                ORDER BY id_pregunta ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "leccion" => $idLeccion
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener una pregunta específica.
     */
    public function obtenerPorId(
        int $idPregunta
    ): ?array {

        $sql = "SELECT
                    id_pregunta,
                    id_leccion,
                    pregunta
                FROM preguntas
                WHERE id_pregunta = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idPregunta
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Actualizar una pregunta.
     */
    public function actualizar(
        int $idPregunta,
        string $pregunta
    ): bool {

        $sql = "UPDATE preguntas
                SET pregunta = :pregunta
                WHERE id_pregunta = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "pregunta" => $pregunta,
            "id" => $idPregunta
        ]);
    }

    /**
     * Eliminar una pregunta.
     */
    public function eliminar(
        int $idPregunta
    ): bool {

        $sql = "DELETE FROM preguntas
                WHERE id_pregunta = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idPregunta
        ]);
    }

    /**
     * Contar preguntas de una lección.
     */
    public function contarPorLeccion(
        int $idLeccion
    ): int {

        $sql = "SELECT COUNT(*)
                FROM preguntas
                WHERE id_leccion = :leccion";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "leccion" => $idLeccion
        ]);

        return (int) $consulta->fetchColumn();
    }
}
