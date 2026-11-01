
<?php

class MensajeChat
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Enviar un mensaje a un curso.
     */
    public function registrar(
        int $idCurso,
        int $idUsuario,
        string $mensaje
    ): bool {

        $sql = "INSERT INTO mensajes_chat
                (id_curso, id_usuario, mensaje)
                VALUES
                (:curso, :usuario, :mensaje)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "curso" => $idCurso,
            "usuario" => $idUsuario,
            "mensaje" => $mensaje
        ]);
    }

    /**
     * Obtener los últimos mensajes de un curso.
     *
     * Se muestran primero los más recientes.
     */
    public function obtenerPorCurso(
        int $idCurso,
        int $limite = 100
    ): array {

        if ($limite < 1 || $limite > 500) {
            $limite = 100;
        }

        $sql = "SELECT
                    m.id_mensaje,
                    m.id_curso,
                    m.id_usuario,
                    m.mensaje,
                    m.fecha_mensaje,
                    u.nombre,
                    u.apellidos,
                    u.foto,
                    r.nombre_rol
                FROM mensajes_chat m
                INNER JOIN usuarios u
                    ON m.id_usuario = u.id_usuario
                INNER JOIN roles r
                    ON u.id_rol = r.id_rol
                WHERE m.id_curso = :curso
                ORDER BY m.id_mensaje DESC
                LIMIT :limite";

        $consulta = $this->conexion->prepare($sql);

        $consulta->bindValue(
            ":curso",
            $idCurso,
            PDO::PARAM_INT
        );

        $consulta->bindValue(
            ":limite",
            $limite,
            PDO::PARAM_INT
        );

        $consulta->execute();

        return $consulta->fetchAll();
    }

    /**
     * Obtener mensajes posteriores a un ID.
     * Útil para actualizar el chat periódicamente.
     */
    public function obtenerNuevos(
        int $idCurso,
        int $ultimoIdMensaje
    ): array {

        $sql = "SELECT
                    m.id_mensaje,
                    m.id_curso,
                    m.id_usuario,
                    m.mensaje,
                    m.fecha_mensaje,
                    u.nombre,
                    u.apellidos,
                    u.foto,
                    r.nombre_rol
                FROM mensajes_chat m
                INNER JOIN usuarios u
                    ON m.id_usuario = u.id_usuario
                INNER JOIN roles r
                    ON u.id_rol = r.id_rol
                WHERE m.id_curso = :curso
                  AND m.id_mensaje > :ultimo
                ORDER BY m.id_mensaje ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso,
            "ultimo" => $ultimoIdMensaje
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener un mensaje específico.
     */
    public function obtenerPorId(
        int $idMensaje
    ): ?array {

        $sql = "SELECT
                    m.id_mensaje,
                    m.id_curso,
                    m.id_usuario,
                    m.mensaje,
                    m.fecha_mensaje,
                    u.nombre,
                    u.apellidos,
                    u.foto
                FROM mensajes_chat m
                INNER JOIN usuarios u
                    ON m.id_usuario = u.id_usuario
                WHERE m.id_mensaje = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idMensaje
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Obtener mensajes enviados por un usuario.
     */
    public function obtenerPorUsuario(
        int $idUsuario,
        int $idCurso
    ): array {

        $sql = "SELECT
                    id_mensaje,
                    id_curso,
                    id_usuario,
                    mensaje,
                    fecha_mensaje
                FROM mensajes_chat
                WHERE id_usuario = :usuario
                  AND id_curso = :curso
                ORDER BY fecha_mensaje DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "usuario" => $idUsuario,
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Eliminar un mensaje propio.
     */
    public function eliminar(
        int $idMensaje,
        int $idUsuario
    ): bool {

        $sql = "DELETE FROM mensajes_chat
                WHERE id_mensaje = :id
                  AND id_usuario = :usuario";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "id" => $idMensaje,
            "usuario" => $idUsuario
        ]);
    }

    /**
     * Contar mensajes de un curso.
     */
    public function contarPorCurso(
        int $idCurso
    ): int {

        $sql = "SELECT COUNT(*)
                FROM mensajes_chat
                WHERE id_curso = :curso";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return (int) $consulta->fetchColumn();
    }
}
