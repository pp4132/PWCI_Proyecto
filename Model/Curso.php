<?php

class Curso
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerParaCompra(int $idCurso): ?array
    {
        $sql = "SELECT id_curso, nombre_curso, precio_curso
                FROM cursos
                WHERE id_curso = :id
                  AND estatus = 1
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id' => $idCurso]);

        $curso = $stmt->fetch(PDO::FETCH_ASSOC);

        return $curso ?: null;
    }

    public function estudianteInscrito(
        int $idEstudiante,
        int $idCurso
    ): bool {
        $sql = "SELECT 1
                FROM inscripciones
                WHERE id_estudiante = :estudiante
                  AND id_curso = :curso
                  AND estatus IN ('Activa', 'Terminada')
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            'estudiante' => $idEstudiante,
            'curso' => $idCurso
        ]);

        return $stmt->fetchColumn() !== false;
    }
}
