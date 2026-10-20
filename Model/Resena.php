
<?php

require_once __DIR__ . '/conexion.php';

class Resena
{
    private PDO $conexion;

    public function __construct()
    {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->conectar();
    }

    public function obtenerPorCurso(int $idCurso): array
    {
        $sql = "
            SELECT
                r.id_resena,
                r.id_estudiante,
                u.nombre,
                u.apellidos,
                u.foto,
                r.calificacion,
                r.titulo,
                r.resena,
                r.fecha_creacion
            FROM resenas r
            INNER JOIN usuarios u
                ON u.id_usuario = r.id_estudiante
            WHERE r.id_curso = :curso
              AND r.estatus = 'Activa'
            ORDER BY r.fecha_creacion DESC
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['curso' => $idCurso]);

        return $stmt->fetchAll();
    }

    public function obtenerResumen(int $idCurso): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total_resenas,
                COALESCE(ROUND(AVG(calificacion), 2), 0)
                    AS promedio,
                COALESCE(SUM(calificacion = 5), 0) AS estrellas_5,
                COALESCE(SUM(calificacion = 4), 0) AS estrellas_4,
                COALESCE(SUM(calificacion = 3), 0) AS estrellas_3,
                COALESCE(SUM(calificacion = 2), 0) AS estrellas_2,
                COALESCE(SUM(calificacion = 1), 0) AS estrellas_1
            FROM resenas
            WHERE id_curso = :curso
              AND estatus = 'Activa'
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['curso' => $idCurso]);

        return $stmt->fetch();
    }

    public function guardar(
        int $idCurso,
        int $idEstudiante,
        int $calificacion,
        ?string $titulo,
        string $resena
    ): array {
        if ($calificacion < 1 || $calificacion > 5) {
            throw new InvalidArgumentException(
                'La calificación debe estar entre 1 y 5.'
            );
        }

        $this->db->beginTransaction();

        try {
            // Verificar que el curso exista y esté activo.
            $stmt = $this->db->prepare(
                "SELECT id_curso FROM cursos
                 WHERE id_curso = ? AND estatus = 1"
            );
            $stmt->execute([$idCurso]);

            if (!$stmt->fetch()) {
                throw new DomainException('Curso no encontrado.');
            }

            // Solo estudiantes inscritos pueden calificar.
            $stmt = $this->db->prepare(
                "SELECT id_inscripcion
                 FROM inscripciones
                 WHERE id_curso = ?
                   AND id_estudiante = ?
                   AND estatus IN ('Activa', 'Terminada')"
            );
            $stmt->execute([$idCurso, $idEstudiante]);

            if (!$stmt->fetch()) {
                throw new DomainException(
                    'Debes estar inscrito en el curso para calificarlo.'
                );
            }

            // Una reseña por estudiante y curso.
            $sql = "
                INSERT INTO resenas
                    (id_curso, id_estudiante, calificacion,
                     titulo, resena, estatus)
                VALUES (?, ?, ?, ?, ?, 'Activa')
                ON DUPLICATE KEY UPDATE
                    calificacion = VALUES(calificacion),
                    titulo = VALUES(titulo),
                    resena = VALUES(resena),
                    estatus = 'Activa'
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $idCurso,
                $idEstudiante,
                $calificacion,
                $titulo,
                $resena
            ]);

            // Actualizar el promedio general del curso.
            $stmt = $this->db->prepare(
                "UPDATE cursos
                 SET promedio_general = (
                     SELECT COALESCE(AVG(calificacion), 0)
                     FROM resenas
                     WHERE id_curso = ?
                       AND estatus = 'Activa'
                 )
                 WHERE id_curso = ?"
            );

            $stmt->execute([$idCurso, $idCurso]);

            $this->db->commit();

            return $this->obtenerResumen($idCurso);

        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }
}
