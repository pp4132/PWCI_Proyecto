
<?php

class ProgresoLeccion
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar el progreso inicial de una lección.
     */
    public function registrar(
        int $idEstudiante,
        int $idLeccion
    ): bool {

        $sql = "INSERT INTO progreso_leccion
                (id_estudiante, id_leccion)
                VALUES
                (:estudiante, :leccion)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);
    }

    /**
     * Marcar una lección como completada.
     */
    public function completar(
        int $idEstudiante,
        int $idLeccion
    ): bool {

        $sql = "UPDATE progreso_leccion
                SET completada = TRUE,
                    fecha_completada = NOW()
                WHERE id_estudiante = :estudiante
                  AND id_leccion = :leccion";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);
    }

    /**
     * Consultar el progreso de una lección.
     */
    public function obtenerPorLeccion(
        int $idEstudiante,
        int $idLeccion
    ): ?array {

        $sql = "SELECT
                    id_progreso,
                    id_estudiante,
                    id_leccion,
                    completada,
                    fecha_completada
                FROM progreso_leccion
                WHERE id_estudiante = :estudiante
                  AND id_leccion = :leccion
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Obtener todas las lecciones y su progreso
     * dentro de un curso.
     */
    public function obtenerPorCurso(
        int $idEstudiante,
        int $idCurso
    ): array {

        $sql = "SELECT
                    l.id_leccion,
                    l.titulo,
                    l.descripcion,
                    l.orden,
                    COALESCE(p.completada, 0) AS completada,
                    p.fecha_completada
                FROM lecciones l
                LEFT JOIN progreso_leccion p
                    ON l.id_leccion = p.id_leccion
                    AND p.id_estudiante = :estudiante
                WHERE l.id_curso = :curso
                ORDER BY l.orden ASC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Comprobar si una lección está completada.
     */
    public function estaCompletada(
        int $idEstudiante,
        int $idLeccion
    ): bool {

        $sql = "SELECT completada
                FROM progreso_leccion
                WHERE id_estudiante = :estudiante
                  AND id_leccion = :leccion
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);

        return (bool) $consulta->fetchColumn();
    }

    /**
     * Obtener el porcentaje de progreso de un curso.
     */
    public function calcularProgreso(
        int $idEstudiante,
        int $idCurso
    ): float {

        $sql = "SELECT
                    COUNT(l.id_leccion) AS total_lecciones,
                    SUM(
                        CASE
                            WHEN p.completada = 1 THEN 1
                            ELSE 0
                        END
                    ) AS completadas
                FROM lecciones l
                LEFT JOIN progreso_leccion p
                    ON l.id_leccion = p.id_leccion
                    AND p.id_estudiante = :estudiante
                WHERE l.id_curso = :curso";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "curso" => $idCurso
        ]);

        $resultado = $consulta->fetch();

        $total = (int) $resultado["total_lecciones"];
        $completadas = (int) $resultado["completadas"];

        if ($total === 0) {
            return 0.0;
        }

        return round(
            ($completadas / $total) * 100,
            2
        );
    }

    /**
     * Reiniciar el progreso de una lección.
     */
    public function reiniciar(
        int $idEstudiante,
        int $idLeccion
    ): bool {

        $sql = "UPDATE progreso_leccion
                SET completada = FALSE,
                    fecha_completada = NULL
                WHERE id_estudiante = :estudiante
                  AND id_leccion = :leccion";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);
    }
}
