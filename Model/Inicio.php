<?php
require_once __DIR__ . "/conexion.php";

class Inicio
{
    private PDO $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }

    /**
     * Obtener las categorías disponibles.
     */
    public function obtenerCategorias(): array
    {
        $sql = "SELECT
                    id_categoria,
                    nombre_categoria,
                    descripcion
                FROM categorias
                ORDER BY nombre_categoria ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtener los cursos destacados.
     */
    public function obtenerCursosDestacados(int $limite = 4): array
    {
        $sql = "SELECT
                    c.id_curso,
                    c.nombre_curso,
                    c.descripcion,
                    c.precio_curso,
                    c.promedio_general,
                    c.imagen_url,
                    c.nivel,
                    c.id_categoria,
                    cat.nombre_categoria,
                    u.nombre AS nombre_instructor,
                    u.apellidos AS apellidos_instructor

                FROM cursos c

                INNER JOIN categorias cat
                    ON c.id_categoria = cat.id_categoria

                INNER JOIN usuarios u
                    ON c.id_instructor = u.id_usuario

                WHERE c.estatus = 1
                  AND u.estatus = 'Activo'

                ORDER BY
                    c.promedio_general DESC,
                    c.id_curso DESC

                LIMIT :limite";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ':limite',
            $limite,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }
}