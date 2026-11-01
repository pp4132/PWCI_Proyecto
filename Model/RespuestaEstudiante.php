
<?php

class RespuestaEstudiante
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una respuesta seleccionada.
     * La respuesta correcta se determina desde MySQL,
     * nunca desde los datos enviados por el estudiante.
     */
    public function registrar(
        int $idEstudiante,
        int $idPregunta,
        int $idRespuesta
    ): bool {

        $sql = "INSERT INTO respuestas_estudiante
                (
                    id_estudiante,
                    id_pregunta,
                    id_respuesta,
                    correcta
                )
                SELECT
                    :estudiante,
                    r.id_pregunta,
                    r.id_respuesta,
                    r.es_correcta
                FROM respuestas r
                WHERE r.id_respuesta = :respuesta
                  AND r.id_pregunta = :pregunta";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "respuesta" => $idRespuesta,
            "pregunta" => $idPregunta
        ]);

        return $consulta->rowCount() > 0;
    }

    /**
     * Obtener respuestas de un estudiante.
     */
    public function obtenerPorEstudiante(
        int $idEstudiante
    ): array {

        $sql = "SELECT
                    re.id_respuesta_estudiante,
                    re.id_estudiante,
                    re.id_pregunta,
                    re.id_respuesta,
                    re.correcta,
                    re.fecha,
                    p.pregunta,
                    r.respuesta
                FROM respuestas_estudiante re
                INNER JOIN preguntas p
                    ON re.id_pregunta = p.id_pregunta
                INNER JOIN respuestas r
                    ON re.id_respuesta = r.id_respuesta
                WHERE re.id_estudiante = :estudiante
                ORDER BY re.fecha DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener respuestas registradas para una pregunta.
     */
    public function obtenerPorPregunta(
        int $idEstudiante,
        int $idPregunta
    ): array {

        $sql = "SELECT
                    re.id_respuesta_estudiante,
                    re.id_estudiante,
                    re.id_pregunta,
                    re.id_respuesta,
                    re.correcta,
                    re.fecha,
                    r.respuesta
                FROM respuestas_estudiante re
                INNER JOIN respuestas r
                    ON re.id_respuesta = r.id_respuesta
                WHERE re.id_estudiante = :estudiante
                  AND re.id_pregunta = :pregunta
                ORDER BY re.fecha DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "pregunta" => $idPregunta
        ]);

        return $consulta->fetchAll();
    }

    /**
     * Obtener el resultado general de una evaluación.
     */
    public function obtenerResultadosPorLeccion(
        int $idEstudiante,
        int $idLeccion
    ): array {

        $sql = "SELECT
                    COUNT(re.id_respuesta_estudiante) AS total_respuestas,
                    SUM(
                        CASE
                            WHEN re.correcta = 1 THEN 1
                            ELSE 0
                        END
                    ) AS correctas,
                    SUM(
                        CASE
                            WHEN re.correcta = 0 THEN 1
                            ELSE 0
                        END
                    ) AS incorrectas
                FROM preguntas p
                LEFT JOIN respuestas_estudiante re
                    ON p.id_pregunta = re.id_pregunta
                    AND re.id_estudiante = :estudiante
                WHERE p.id_leccion = :leccion";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);

        return $consulta->fetch();
    }

    /**
     * Contar respuestas correctas de un estudiante.
     */
    public function contarCorrectas(
        int $idEstudiante,
        int $idLeccion
    ): int {

        $sql = "SELECT COUNT(*)
                FROM respuestas_estudiante re
                INNER JOIN preguntas p
                    ON re.id_pregunta = p.id_pregunta
                WHERE re.id_estudiante = :estudiante
                  AND p.id_leccion = :leccion
                  AND re.correcta = 1";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);

        return (int) $consulta->fetchColumn();
    }

    /**
     * Contar respuestas incorrectas de un estudiante.
     */
    public function contarIncorrectas(
        int $idEstudiante,
        int $idLeccion
    ): int {

        $sql = "SELECT COUNT(*)
                FROM respuestas_estudiante re
                INNER JOIN preguntas p
                    ON re.id_pregunta = p.id_pregunta
                WHERE re.id_estudiante = :estudiante
                  AND p.id_leccion = :leccion
                  AND re.correcta = 0";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            "estudiante" => $idEstudiante,
            "leccion" => $idLeccion
        ]);

        return (int) $consulta->fetchColumn();
    }
}
