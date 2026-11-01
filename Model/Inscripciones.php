
<?php

class Inscripcion
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una inscripción.
     */
    public function registrar(
        int $idEstudiante,
        int $idCurso
    ): bool {

        $sql = "INSERT INTO inscripciones
                (id_estudiante, id_curso)
                VALUES
                (:estudiante, :curso)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "estudiante" => $idEstudiante,
            "curso" => $idCurso
        ]);
    }

    /**
     * Verificar si un estudiante está inscrito.
     */
    public function existeInscripcion(
        int $idEstudiante,
        int $idCurso
    ): bool {

        $sql = "SELECT id_inscripcion
                FROM inscripciones
                WHERE id_estudiante = :estudiante
                  AND id_curso = :curso
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "curso" => $idCurso
        ]);

        return $consulta->fetch() !== false;
    }

    /**
     * Obtener las inscripciones de un estudiante.
     */
    public function obtenerPorEstudiante(
        int $idEstudiante
    ): array {

        $sql = "SELECT
                    i.id_inscripcion,
                    i.id_estudiante,
                    i.id_curso,
                    i.fecha_inscripcion,
                    i.fecha_terminacion,
                    i.estatus,
                    i.progreso,
                    c.nombre_curso,
                    c.descripcion,
                    c.precio_curso,
                    c.promedio_general,
                    c.id_instructor,
                    u.nombre AS nombre_instructor,
                    u.apellidos AS apellidos_instructor,
                    u.foto AS foto_instructor
                FROM inscripciones i
                INNER JOIN cursos c
                    ON i.id_curso = c.id_curso
                INNER JOIN usuarios u
                    ON c.id_instructor = u.id_usuario
                WHERE i.id_estudiante = :estudiante
                ORDER BY i.fecha_inscripcion DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener estudiantes inscritos en un curso.
     */
    public function obtenerPorCurso(
        int $idCurso
    ): array {

        $sql = "SELECT
                    i.id_inscripcion,
                    i.id_estudiante,
                    i.fecha_inscripcion,
                    i.estatus,
                    i.progreso,
                    u.nombre,
                    u.apellidos,
                    u.email,
                    u.foto
                FROM inscripciones i
                INNER JOIN usuarios u
                    ON i.id_estudiante = u.id_usuario
                WHERE i.id_curso = :curso
                ORDER BY i.fecha_inscripcion DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener una inscripción específica.
     */
    public function obtenerPorId(
        int $idInscripcion
    ): ?array {

        $sql = "SELECT *
                FROM inscripciones
                WHERE id_inscripcion = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idInscripcion
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Actualizar el progreso de una inscripción.
     */
    public function actualizarProgreso(
        int $idInscripcion,
        float $progreso
    ): bool {

        if ($progreso < 0 || $progreso > 100) {
            return false;
        }

        $sql = "UPDATE inscripciones
                SET progreso = :progreso,
                    estatus = CASE
                        WHEN :progreso_estado >= 100
                        THEN 'Terminada'
                        ELSE 'Activa'
                    END,
                    fecha_terminacion = CASE
                        WHEN :progreso_fecha >= 100
                        THEN COALESCE(fecha_terminacion, NOW())
                        ELSE NULL
                    END
                WHERE id_inscripcion = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "progreso" => $progreso,
            "progreso_estado" => $progreso,
            "progreso_fecha" => $progreso,
            "id" => $idInscripcion
        ]);
    }

    /**
     * Actualizar el estado de una inscripción.
     */
    public function actualizarEstatus(
        int $idInscripcion,
        string $estatus
    ): bool {

        $estadosPermitidos = [
            "Activa",
            "Terminada",
            "Cancelada"
        ];

        if (!in_array($estatus, $estadosPermitidos, true)) {
            return false;
        }

        $sql = "UPDATE inscripciones
                SET estatus = :estatus
                WHERE id_inscripcion = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "estatus" => $estatus,
            "id" => $idInscripcion
        ]);
    }

    /**
     * Contar estudiantes inscritos en un curso.
     */
    public function contarPorCurso(
        int $idCurso
    ): int {

        $sql = "SELECT COUNT(*)
                FROM inscripciones
                WHERE id_curso = :curso
                  AND estatus IN ('Activa', 'Terminada')";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return (int) $consulta->fetchColumn();
    }
}
