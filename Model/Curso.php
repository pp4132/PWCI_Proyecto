
<?php

class Curso
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    // ==========================================
    // OBTENER CURSO PARA COMPRA

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

    // ==========================================
    // VERIFICAR INSCRIPCIÓN
    // ==========================================

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

    // ==========================================
    // OBTENER CATEGORÍAS
    // ==========================================

    public function obtenerCategorias(): array
    {
        $sql = "SELECT id_categoria, nombre_categoria
                FROM categorias
                ORDER BY nombre_categoria ASC";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // CREAR CURSO CON MÓDULOS Y LECCIONES
    // ==========================================

    public function crearCurso(
        array $datos,
        int $idInstructor
    ): int {

        try {

            $this->conexion->beginTransaction();

            // 1. Insertar curso

            $sqlCurso = "
            INSERT INTO cursos (
                id_categoria,
                id_instructor,
                nombre_curso,
                descripcion,
                precio_curso,
                nivel,
                video_url,
                estatus
            ) VALUES (
                :categoria,
                :instructor,
                :nombre,
                :descripcion,
                :precio,
                :nivel,
                :video,
                1
            )
        ";

            $stmtCurso = $this->conexion->prepare($sqlCurso);

           $stmtCurso->execute([
                'categoria' => $datos['categoria'],
                'instructor' => $idInstructor,
                'nombre' => $datos['titulo'],
                'descripcion' => $datos['descripcion'],
                'precio' => $datos['precio'],
                'nivel' => $datos['nivel'],
                'video' => $datos['video']
            ]);

            $idCurso = (int) $this->conexion->lastInsertId();

            // 2. Preparar inserción de módulos

            $sqlModulo = "
                INSERT INTO modulos (
                    id_curso,
                    titulo,
                    orden
                ) VALUES (
                    :curso,
                    :titulo,
                    :orden
                )
            ";

            $stmtModulo = $this->conexion->prepare($sqlModulo);

            // 3. Preparar inserción de lecciones

            $sqlLeccion = "
                INSERT INTO lecciones (
                    id_curso,
                    id_modulo,
                    titulo,
                    orden
                ) VALUES (
                    :curso,
                    :modulo,
                    :titulo,
                    :orden
                )
            ";

            $stmtLeccion = $this->conexion->prepare($sqlLeccion);

            // 4. Recorrer módulos

            foreach ($datos['modulos'] as $indice => $modulo) {

                $stmtModulo->execute([
                    'curso' => $idCurso,
                    'titulo' => $modulo['titulo'],
                    'orden' => $indice + 1
                ]);

                $idModulo = (int) $this->conexion->lastInsertId();

                // 5. Recorrer lecciones del módulo

                foreach ($modulo['lecciones'] as $orden => $leccion) {

                    $stmtLeccion->execute([
                        'curso' => $idCurso,
                        'modulo' => $idModulo,
                        'titulo' => $leccion,
                        'orden' => $orden + 1
                    ]);
                }
            }

            // 6. Confirmar transacción

            $this->conexion->commit();

            return $idCurso;

        } catch (Throwable $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }
}
