
<?php

class Comentario
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar un comentario principal o una respuesta.
     */
    public function registrar(
        int $idCurso,
        int $idUsuario,
        string $comentario,
        ?int $idComentarioPadre = null
    ): bool {

        $sql = "INSERT INTO comentarios
                (
                    id_curso,
                    id_usuario,
                    id_comentario_padre,
                    comentario
                )
                VALUES
                (
                    :curso,
                    :usuario,
                    :padre,
                    :comentario
                )";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "curso" => $idCurso,
            "usuario" => $idUsuario,
            "padre" => $idComentarioPadre,
            "comentario" => $comentario
        ]);
    }

    /**
     * Obtener los comentarios principales de un curso.
     */
    public function obtenerPorCurso(
        int $idCurso
    ): array {

        $sql = "SELECT
                    c.id_comentario,
                    c.id_curso,
                    c.id_usuario,
                    c.id_comentario_padre,
                    c.comentario,
                    c.fecha_mensaje,
                    u.nombre,
                    u.apellidos,
                    u.foto,
                    (
                        SELECT COUNT(*)
                        FROM comentarios respuestas
                        WHERE respuestas.id_comentario_padre =
                              c.id_comentario
                    ) AS total_respuestas
                FROM comentarios c
                INNER JOIN usuarios u
                    ON c.id_usuario = u.id_usuario
                WHERE c.id_curso = :curso
                  AND c.id_comentario_padre IS NULL
                ORDER BY c.fecha_mensaje DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener las respuestas de un comentario.
     */
    public function obtenerRespuestas(
        int $idComentarioPadre
    ): array {

        $sql = "SELECT
                    c.id_comentario,
                    c.id_curso,
                    c.id_usuario,
                    c.id_comentario_padre,
                    c.comentario,
                    c.fecha_mensaje,
                    u.nombre,
                    u.apellidos,
                    u.foto
                FROM comentarios c
                INNER JOIN usuarios u
                    ON c.id_usuario = u.id_usuario
                WHERE c.id_comentario_padre = :padre
                ORDER BY c.fecha_mensaje ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "padre" => $idComentarioPadre
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener un comentario por su ID.
     */
    public function obtenerPorId(
        int $idComentario
    ): ?array {

        $sql = "SELECT
                    c.*,
                    u.nombre,
                    u.apellidos,
                    u.foto
                FROM comentarios c
                INNER JOIN usuarios u
                    ON c.id_usuario = u.id_usuario
                WHERE c.id_comentario = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idComentario
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Editar un comentario propio.
     */
    public function actualizar(
        int $idComentario,
        int $idUsuario,
        string $comentario
    ): bool {

        $sql = "UPDATE comentarios
                SET comentario = :comentario
                WHERE id_comentario = :id
                  AND id_usuario = :usuario";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "comentario" => $comentario,
            "id" => $idComentario,
            "usuario" => $idUsuario
        ]);
    }

    /**
     * Eliminar un comentario propio.
     */
    public function eliminar(
        int $idComentario,
        int $idUsuario
    ): bool {

        $sql = "DELETE FROM comentarios
                WHERE id_comentario = :id
                  AND id_usuario = :usuario";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idComentario,
            "usuario" => $idUsuario
        ]);
    }

    /**
     * Contar comentarios principales de un curso.
     */
    public function contarPorCurso(
        int $idCurso
    ): int {

        $sql = "SELECT COUNT(*)
                FROM comentarios
                WHERE id_curso = :curso
                  AND id_comentario_padre IS NULL";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return (int) $consulta->fetchColumn();
    }
}
