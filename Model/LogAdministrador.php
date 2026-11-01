
<?php

class LogAdministrador
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una acción administrativa.
     */
    public function registrar(
        int $idAdministrador,
        ?int $idUsuarioAfectado,
        string $accion,
        ?string $descripcion
    ): bool {

        $sql = "INSERT INTO log_administrador
                (
                    id_administrador,
                    id_usuario_afectado,
                    accion,
                    descripcion
                )
                VALUES
                (
                    :administrador,
                    :afectado,
                    :accion,
                    :descripcion
                )";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "administrador" => $idAdministrador,
            "afectado" => $idUsuarioAfectado,
            "accion" => $accion,
            "descripcion" => $descripcion
        ]);
    }

    /**
     * Obtener todos los registros administrativos.
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT
                    l.id_log,
                    l.id_administrador,
                    l.id_usuario_afectado,
                    l.accion,
                    l.descripcion,
                    l.fecha,
                    a.nombre AS nombre_administrador,
                    a.apellidos AS apellidos_administrador,
                    u.nombre AS nombre_afectado,
                    u.apellidos AS apellidos_afectado
                FROM log_administrador l
                INNER JOIN usuarios a
                    ON l.id_administrador = a.id_usuario
                LEFT JOIN usuarios u
                    ON l.id_usuario_afectado = u.id_usuario
                ORDER BY l.fecha DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    /**
     * Obtener acciones de un administrador.
     */
    public function obtenerPorAdministrador(
        int $idAdministrador
    ): array {

        $sql = "SELECT
                    id_log,
                    id_administrador,
                    id_usuario_afectado,
                    accion,
                    descripcion,
                    fecha
                FROM log_administrador
                WHERE id_administrador = :administrador
                ORDER BY fecha DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "administrador" => $idAdministrador
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener historial de un usuario afectado.
     */
    public function obtenerPorUsuarioAfectado(
        int $idUsuario
    ): array {

        $sql = "SELECT
                    l.id_log,
                    l.id_administrador,
                    l.id_usuario_afectado,
                    l.accion,
                    l.descripcion,
                    l.fecha,
                    a.nombre AS nombre_administrador,
                    a.apellidos AS apellidos_administrador
                FROM log_administrador l
                INNER JOIN usuarios a
                    ON l.id_administrador = a.id_usuario
                WHERE l.id_usuario_afectado = :usuario
                ORDER BY l.fecha DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "usuario" => $idUsuario
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener un registro específico.
     */
    public function obtenerPorId(
        int $idLog
    ): ?array {

        $sql = "SELECT
                    l.*,
                    a.nombre AS nombre_administrador,
                    a.apellidos AS apellidos_administrador,
                    u.nombre AS nombre_afectado,
                    u.apellidos AS apellidos_afectado
                FROM log_administrador l
                INNER JOIN usuarios a
                    ON l.id_administrador = a.id_usuario
                LEFT JOIN usuarios u
                    ON l.id_usuario_afectado = u.id_usuario
                WHERE l.id_log = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idLog
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Filtrar registros por tipo de acción.
     */
    public function obtenerPorAccion(
        string $accion
    ): array {

        $sql = "SELECT
                    l.id_log,
                    l.id_administrador,
                    l.id_usuario_afectado,
                    l.accion,
                    l.descripcion,
                    l.fecha,
                    a.nombre AS nombre_administrador,
                    a.apellidos AS apellidos_administrador
                FROM log_administrador l
                INNER JOIN usuarios a
                    ON l.id_administrador = a.id_usuario
                WHERE l.accion = :accion
                ORDER BY l.fecha DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "accion" => $accion
        ]);

        return $consulta->fetchAll();
    }
}
