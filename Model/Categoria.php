
<?php

class Categoria
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una categoría.
     */
    public function registrar(
        string $nombreCategoria,
        ?string $descripcion,
        int $idUsuarioCreacion
    ): bool {

        $sql = "INSERT INTO categorias
                (
                    nombre_categoria,
                    descripcion,
                    id_usuario_creacion
                )
                VALUES
                (
                    :nombre,
                    :descripcion,
                    :usuario
                )";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "nombre" => $nombreCategoria,
            "descripcion" => $descripcion,
            "usuario" => $idUsuarioCreacion
        ]);
    }

    /**
     * Obtener todas las categorías.
     */
    public function obtenerTodas(): array
    {
        $sql = "SELECT
                    c.id_categoria,
                    c.nombre_categoria,
                    c.descripcion,
                    c.id_usuario_creacion,
                    c.fecha_creacion,
                    u.nombre AS nombre_creador,
                    u.apellidos AS apellidos_creador,
                    COUNT(cu.id_curso) AS total_cursos
                FROM categorias c
                INNER JOIN usuarios u
                    ON c.id_usuario_creacion = u.id_usuario
                LEFT JOIN cursos cu
                    ON c.id_categoria = cu.id_categoria
                GROUP BY
                    c.id_categoria,
                    c.nombre_categoria,
                    c.descripcion,
                    c.id_usuario_creacion,
                    c.fecha_creacion,
                    u.nombre,
                    u.apellidos
                ORDER BY c.nombre_categoria ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /**
     * Obtener una categoría por su ID.
     */
    public function obtenerPorId(
        int $idCategoria
    ): ?array {

        $sql = "SELECT
                    id_categoria,
                    nombre_categoria,
                    descripcion,
                    id_usuario_creacion,
                    fecha_creacion
                FROM categorias
                WHERE id_categoria = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idCategoria
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Actualizar una categoría.
     */
    public function actualizar(
        int $idCategoria,
        string $nombreCategoria,
        ?string $descripcion
    ): bool {

        $sql = "UPDATE categorias
                SET nombre_categoria = :nombre,
                    descripcion = :descripcion
                WHERE id_categoria = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "nombre" => $nombreCategoria,
            "descripcion" => $descripcion,
            "id" => $idCategoria
        ]);
    }

    /**
     * Eliminar una categoría.
     */
    public function eliminar(
        int $idCategoria
    ): bool {

        $sql = "DELETE FROM categorias
                WHERE id_categoria = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idCategoria
        ]);
    }

    /**
     * Obtener cursos pertenecientes a una categoría.
     */
    public function obtenerCursos(
        int $idCategoria
    ): array {

        $sql = "SELECT
                    id_curso,
                    id_categoria,
                    id_instructor,
                    nombre_curso,
                    descripcion,
                    precio_curso,
                    promedio_general,
                    estatus
                FROM cursos
                WHERE id_categoria = :categoria
                  AND estatus = 1
                ORDER BY nombre_curso ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "categoria" => $idCategoria
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Contar cursos de una categoría.
     */
    public function contarCursos(
        int $idCategoria
    ): int {

        $sql = "SELECT COUNT(*)
                FROM cursos
                WHERE id_categoria = :categoria";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "categoria" => $idCategoria
        ]);

        return (int) $consulta->fetchColumn();
    }
}
