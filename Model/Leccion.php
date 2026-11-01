<?php

class Leccion
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una nueva lección.
     */
    public function registrar(
        int $idCurso,
        string $titulo,
        ?string $descripcion,
        int $orden
    ): bool {

        if ($orden <= 0) {
            return false;
        }

        $sql = "INSERT INTO lecciones
                (id_curso, titulo, descripcion, orden)
                VALUES
                (:curso, :titulo, :descripcion, :orden)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "curso" => $idCurso,
            "titulo" => $titulo,
            "descripcion" => $descripcion,
            "orden" => $orden
        ]);
    }

    /**
     * Obtener todas las lecciones de un curso.
     */
    public function obtenerPorCurso(
        int $idCurso
    ): array {

        $sql = "SELECT
                    id_leccion,
                    id_curso,
                    titulo,
                    descripcion,
                    orden
                FROM lecciones
                WHERE id_curso = :curso
                ORDER BY orden ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener una lección por su ID.
     */
    public function obtenerPorId(
        int $idLeccion
    ): ?array {

        $sql = "SELECT
                    id_leccion,
                    id_curso,
                    titulo,
                    descripcion,
                    orden
                FROM lecciones
                WHERE id_leccion = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idLeccion
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Actualizar información de una lección.
     */
    public function actualizar(
        int $idLeccion,
        string $titulo,
        ?string $descripcion,
        int $orden
    ): bool {

        if ($orden <= 0) {
            return false;
        }

        $sql = "UPDATE lecciones
                SET titulo = :titulo,
                    descripcion = :descripcion,
                    orden = :orden
                WHERE id_leccion = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "titulo" => $titulo,
            "descripcion" => $descripcion,
            "orden" => $orden,
            "id" => $idLeccion
        ]);
    }

    /**
     * Eliminar una lección.
     */
    public function eliminar(
        int $idLeccion
    ): bool {

        $sql = "DELETE FROM lecciones
                WHERE id_leccion = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idLeccion
        ]);
    }

    /**
     * Cambiar el orden de una lección.
     */
    public function actualizarOrden(
        int $idLeccion,
        int $nuevoOrden
    ): bool {

        if ($nuevoOrden <= 0) {
            return false;
        }

        $sql = "UPDATE lecciones
                SET orden = :orden
                WHERE id_leccion = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "orden" => $nuevoOrden,
            "id" => $idLeccion
        ]);
    }

    /**
     * Contar las lecciones de un curso.
     */
    public function contarPorCurso(
        int $idCurso
    ): int {

        $sql = "SELECT COUNT(*)
                FROM lecciones
                WHERE id_curso = :curso";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return (int) $consulta->fetchColumn();
    }
}
