
<?php

class Multimedia
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar un recurso multimedia.
     */
    public function registrar(
        int $idCurso,
        string $linkMultimedia,
        string $tipo
    ): bool {

        $sql = "INSERT INTO multimedia
                (id_curso, link_multimedia, tipo)
                VALUES
                (:curso, :link, :tipo)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "curso" => $idCurso,
            "link" => $linkMultimedia,
            "tipo" => $tipo
        ]);
    }

    /**
     * Obtener todos los recursos de un curso.
     */
    public function obtenerPorCurso(
        int $idCurso
    ): array {

        $sql = "SELECT
                    id_multimedia,
                    id_curso,
                    link_multimedia,
                    tipo
                FROM multimedia
                WHERE id_curso = :curso
                ORDER BY id_multimedia DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener un recurso multimedia por su ID.
     */
    public function obtenerPorId(
        int $idMultimedia
    ): ?array {

        $sql = "SELECT
                    id_multimedia,
                    id_curso,
                    link_multimedia,
                    tipo
                FROM multimedia
                WHERE id_multimedia = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idMultimedia
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Actualizar un recurso multimedia.
     */
    public function actualizar(
        int $idMultimedia,
        string $linkMultimedia,
        string $tipo
    ): bool {

        $sql = "UPDATE multimedia
                SET link_multimedia = :link,
                    tipo = :tipo
                WHERE id_multimedia = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "link" => $linkMultimedia,
            "tipo" => $tipo,
            "id" => $idMultimedia
        ]);
    }

    /**
     * Eliminar un recurso multimedia.
     */
    public function eliminar(
        int $idMultimedia
    ): bool {

        $sql = "DELETE FROM multimedia
                WHERE id_multimedia = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idMultimedia
        ]);
    }

    /**
     * Obtener recursos de un curso por tipo.
     */
    public function obtenerPorTipo(
        int $idCurso,
        string $tipo
    ): array {

        $sql = "SELECT
                    id_multimedia,
                    id_curso,
                    link_multimedia,
                    tipo
                FROM multimedia
                WHERE id_curso = :curso
                  AND tipo = :tipo
                ORDER BY id_multimedia DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso,
            "tipo" => $tipo
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Contar recursos multimedia de un curso.
     */
    public function contarPorCurso(
        int $idCurso
    ): int {

        $sql = "SELECT COUNT(*)
                FROM multimedia
                WHERE id_curso = :curso";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return (int) $consulta->fetchColumn();
    }
}
