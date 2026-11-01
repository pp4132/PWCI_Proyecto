
<?php

class Resena
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Obtener reseñas activas de un curso.
     */
    public function obtenerPorCurso(
        int $idCurso
    ): array {

        $sql = "SELECT
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
                ORDER BY r.fecha_creacion DESC";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            'curso' => $idCurso
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Obtener estadísticas de reseñas.
     */
    public function obtenerResumen(
        int $idCurso
    ): array {

        $sql = "SELECT
                    COUNT(*) AS total_resenas,

                    COALESCE(
                        ROUND(AVG(calificacion), 2),
                        0
                    ) AS promedio,

                    COALESCE(
                        SUM(calificacion = 5), 0
                    ) AS estrellas_5,

                    COALESCE(
                        SUM(calificacion = 4), 0
                    ) AS estrellas_4,

                    COALESCE(
                        SUM(calificacion = 3), 0
                    ) AS estrellas_3,

                    COALESCE(
                        SUM(calificacion = 2), 0
                    ) AS estrellas_2,

                    COALESCE(
                        SUM(calificacion = 1), 0
                    ) AS estrellas_1

                FROM resenas
                WHERE id_curso = :curso
                  AND estatus = 'Activa'";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            'curso' => $idCurso
        ]);

        return $stmt->fetch();
    }

    /**
     * Registrar o actualizar una reseña.
     *
     * Solo pueden reseñar estudiantes inscritos.
     */
    public function guardar(
        int $idCurso,
        int $idEstudiante,
        int $calificacion,
        ?string $titulo,
        string $resena
    ): array {

        if (
            $idCurso <= 0
            || $idEstudiante <= 0
        ) {
            throw new InvalidArgumentException(
                'Los identificadores no son válidos.'
            );
        }

        if (
            $calificacion < 1
            || $calificacion > 5
        ) {
            throw new InvalidArgumentException(
                'La calificación debe estar entre 1 y 5.'
            );
        }

        if (trim($resena) === '') {
            throw new InvalidArgumentException(
                'La reseña no puede estar vacía.'
            );
        }

        $this->conexion->beginTransaction();

        try {

            // Comprobar que el curso existe y está activo.
            $stmt = $this->conexion->prepare(
                "SELECT id_curso
                 FROM cursos
                 WHERE id_curso = :curso
                   AND estatus = 1
                 LIMIT 1"
            );

            $stmt->execute([
                'curso' => $idCurso
            ]);

            if (!$stmt->fetch()) {
                throw new DomainException(
                    'El curso no existe o no está disponible.'
                );
            }

            // Comprobar que el estudiante está inscrito.
            $stmt = $this->conexion->prepare(
                "SELECT id_inscripcion
                 FROM inscripciones
                 WHERE id_curso = :curso
                   AND id_estudiante = :estudiante
                   AND estatus IN ('Activa', 'Terminada')
                 LIMIT 1"
            );

            $stmt->execute([
                'curso' => $idCurso,
                'estudiante' => $idEstudiante
            ]);

            if (!$stmt->fetch()) {
                throw new DomainException(
                    'Debes estar inscrito en el curso para reseñarlo.'
                );
            }

            // Insertar o actualizar la reseña.
            $sql = "INSERT INTO resenas (
                        id_curso,
                        id_estudiante,
                        calificacion,
                        titulo,
                        resena,
                        estatus
                    )
                    VALUES (
                        :curso,
                        :estudiante,
                        :calificacion,
                        :titulo,
                        :resena,
                        'Activa'
                    )
                    ON DUPLICATE KEY UPDATE
                        calificacion = VALUES(calificacion),
                        titulo = VALUES(titulo),
                        resena = VALUES(resena),
                        estatus = 'Activa'";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                'curso' => $idCurso,
                'estudiante' => $idEstudiante,
                'calificacion' => $calificacion,
                'titulo' => $titulo,
                'resena' => $resena
            ]);

            // Actualizar promedio general del curso.
            $sql = "UPDATE cursos
                    SET promedio_general = (
                        SELECT COALESCE(
                            AVG(calificacion),
                            0
                        )
                        FROM resenas
                        WHERE id_curso = :curso
                          AND estatus = 'Activa'
                    )
                    WHERE id_curso = :curso_actual";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                'curso' => $idCurso,
                'curso_actual' => $idCurso
            ]);

            $this->conexion->commit();

            return $this->obtenerResumen($idCurso);

        } catch (Throwable $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Ocultar una reseña.
     */
    public function ocultar(
        int $idResena
    ): bool {

        $sql = "UPDATE resenas
                SET estatus = 'Oculta'
                WHERE id_resena = :id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            'id' => $idResena
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Eliminar una reseña.
     */
    public function eliminar(
        int $idResena
    ): bool {

        $sql = "DELETE FROM resenas
                WHERE id_resena = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            'id' => $idResena
        ]);
    }
}
