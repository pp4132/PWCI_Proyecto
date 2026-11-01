
<?php

class Certificado
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Generar un certificado.
     */
    public function registrar(
        int $idEstudiante,
        int $idCurso,
        int $idInstructor,
        string $fechaTerminacion
    ): bool {

        $sql = "INSERT INTO certificados
                (
                    id_estudiante,
                    id_curso,
                    id_instructor,
                    fecha_terminacion
                )
                VALUES
                (
                    :estudiante,
                    :curso,
                    :instructor,
                    :fecha
                )";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "estudiante" => $idEstudiante,
            "curso" => $idCurso,
            "instructor" => $idInstructor,
            "fecha" => $fechaTerminacion
        ]);
    }

    /**
     * Obtener certificados de un estudiante.
     */
    public function obtenerPorEstudiante(
        int $idEstudiante
    ): array {

        $sql = "SELECT
                    ce.id_certificado,
                    ce.id_estudiante,
                    ce.id_curso,
                    ce.id_instructor,
                    ce.fecha_terminacion,
                    c.nombre_curso,
                    u.nombre AS nombre_instructor,
                    u.apellidos AS apellidos_instructor
                FROM certificados ce
                INNER JOIN cursos c
                    ON ce.id_curso = c.id_curso
                INNER JOIN usuarios u
                    ON ce.id_instructor = u.id_usuario
                WHERE ce.id_estudiante = :estudiante
                ORDER BY ce.fecha_terminacion DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener certificados de un curso.
     */
    public function obtenerPorCurso(
        int $idCurso
    ): array {

        $sql = "SELECT
                    ce.id_certificado,
                    ce.id_estudiante,
                    ce.id_curso,
                    ce.id_instructor,
                    ce.fecha_terminacion,
                    u.nombre,
                    u.apellidos,
                    u.email,
                    u.foto
                FROM certificados ce
                INNER JOIN usuarios u
                    ON ce.id_estudiante = u.id_usuario
                WHERE ce.id_curso = :curso
                ORDER BY ce.fecha_terminacion DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "curso" => $idCurso
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener un certificado específico.
     */
    public function obtenerPorId(
        int $idCertificado
    ): ?array {

        $sql = "SELECT
                    ce.id_certificado,
                    ce.id_estudiante,
                    ce.id_curso,
                    ce.id_instructor,
                    ce.fecha_terminacion,
                    c.nombre_curso,
                    e.nombre AS nombre_estudiante,
                    e.apellidos AS apellidos_estudiante,
                    i.nombre AS nombre_instructor,
                    i.apellidos AS apellidos_instructor
                FROM certificados ce
                INNER JOIN cursos c
                    ON ce.id_curso = c.id_curso
                INNER JOIN usuarios e
                    ON ce.id_estudiante = e.id_usuario
                INNER JOIN usuarios i
                    ON ce.id_instructor = i.id_usuario
                WHERE ce.id_certificado = :id
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "id" => $idCertificado
        ]);

        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    /**
     * Comprobar si existe un certificado.
     */
    public function existeCertificado(
        int $idEstudiante,
        int $idCurso
    ): bool {

        $sql = "SELECT id_certificado
                FROM certificados
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
     * Obtener todos los certificados.
     */
    public function obtenerTodos(): array
    {
        $sql = "SELECT
                    ce.id_certificado,
                    ce.fecha_terminacion,
                    c.nombre_curso,
                    e.nombre AS nombre_estudiante,
                    e.apellidos AS apellidos_estudiante,
                    i.nombre AS nombre_instructor,
                    i.apellidos AS apellidos_instructor
                FROM certificados ce
                INNER JOIN cursos c
                    ON ce.id_curso = c.id_curso
                INNER JOIN usuarios e
                    ON ce.id_estudiante = e.id_usuario
                INNER JOIN usuarios i
                    ON ce.id_instructor = i.id_usuario
                ORDER BY ce.fecha_terminacion DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }
}
