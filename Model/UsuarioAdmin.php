<?php
class UsuarioAdmin
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // =================================================
    // LISTAR USUARIOS Y SUS ESTADÍSTICAS
    // =================================================
    public function listar(array $filtros = []): array
    {
        $sql = "
            SELECT
                u.id_usuario,
                u.nombre,
                u.apellidos,
                u.email,
                u.foto,
                u.fecha_registro,
                u.estatus,
                r.nombre_rol

            FROM usuarios u

            INNER JOIN roles r
                ON r.id_rol = u.id_rol

            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($filtros['rol'])) {

            $sql .= " AND r.nombre_rol = :rol";

            $params['rol'] = $filtros['rol'];
        }

        if (!empty($filtros['estatus'])) {

            $sql .= " AND u.estatus = :estatus";

            $params['estatus'] = $filtros['estatus'];
        }

        if (!empty($filtros['buscar'])) {

            $sql .= "
                AND (
                    u.nombre LIKE :buscar_nombre
                    OR u.apellidos LIKE :buscar_apellidos
                    OR u.email LIKE :buscar_email
                    OR CONCAT(u.nombre, ' ', u.apellidos)
                        LIKE :buscar_completo
                )
            ";

            $busqueda = '%' . $filtros['buscar'] . '%';

            $params['buscar_nombre'] = $busqueda;
            $params['buscar_apellidos'] = $busqueda;
            $params['buscar_email'] = $busqueda;
            $params['buscar_completo'] = $busqueda;
        }

        $sql .= "
            ORDER BY
                CASE u.estatus
                    WHEN 'Pendiente' THEN 1
                    WHEN 'Activo' THEN 2
                    WHEN 'Baneado' THEN 3
                END,
                u.fecha_registro DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =================================================
    // ESTADÍSTICAS
    // =================================================

    public function obtenerEstadisticas(): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total,

                SUM(
                    CASE WHEN estatus = 'Activo'
                    THEN 1 ELSE 0 END
                ) AS activos,

                SUM(
                    CASE WHEN estatus = 'Pendiente'
                    THEN 1 ELSE 0 END
                ) AS pendientes,

                SUM(
                    CASE WHEN estatus = 'Baneado'
                    THEN 1 ELSE 0 END
                ) AS baneados

            FROM usuarios
        ";

        $stmt = $this->db->query($sql);

        $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);

        $sqlRoles = "
            SELECT
                r.nombre_rol,
                COUNT(u.id_usuario) AS total

            FROM roles r

            LEFT JOIN usuarios u
                ON u.id_rol = r.id_rol

            GROUP BY r.id_rol, r.nombre_rol
        ";

        $roles = $this->db
            ->query($sqlRoles)
            ->fetchAll(PDO::FETCH_KEY_PAIR);

        return [
            'total' => (int) $estadisticas['total'],
            'activos' => (int) $estadisticas['activos'],
            'pendientes' => (int) $estadisticas['pendientes'],
            'baneados' => (int) $estadisticas['baneados'],
            'estudiantes' => (int) ($roles['Estudiante'] ?? 0),
            'instructores' => (int) ($roles['Instructor'] ?? 0),
            'administradores' => (int) ($roles['Administrador'] ?? 0)
        ];
    }


    // =================================================
    // CAMBIAR ESTATUS
    // =================================================

    public function cambiarEstatus(
        int $idUsuario,
        string $estatus
    ): bool {

        $estatusPermitidos = [
            'Activo',
            'Baneado'
        ];

        if (!in_array($estatus, $estatusPermitidos, true)) {
            return false;
        }

        $sql = "
            UPDATE usuarios
            SET estatus = :estatus
            WHERE id_usuario = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'estatus' => $estatus,
            'id' => $idUsuario
        ]);
    }


    // =================================================
    // REPORTE DETALLADO DE INSTRUCTOR
    // =================================================    
    public function reporteInstructor(int $idUsuario): ?array
    {
        $sql = "
            SELECT
                u.id_usuario,
                u.nombre,
                u.apellidos,
                u.email,
                u.estatus,

                (
                    SELECT COUNT(*)
                    FROM cursos c
                    WHERE c.id_instructor = u.id_usuario
                ) AS total_cursos,

                (
                    SELECT COUNT(DISTINCT i.id_estudiante)
                    FROM inscripciones i
                    INNER JOIN cursos c
                        ON c.id_curso = i.id_curso
                    WHERE c.id_instructor = u.id_usuario
                ) AS total_estudiantes,

                (
                    SELECT COALESCE(ROUND(AVG(c.promedio_general), 2), 0)
                    FROM cursos c
                    WHERE c.id_instructor = u.id_usuario
                ) AS promedio_cursos,

                (
                    SELECT COALESCE(SUM(p.monto), 0)
                    FROM pagos p
                    INNER JOIN cursos c
                        ON c.id_curso = p.id_curso
                    WHERE c.id_instructor = u.id_usuario
                    AND p.estado_pago = 'Completado'
                ) AS ingresos

            FROM usuarios u
            WHERE u.id_usuario = :id

            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // =================================================
    // REPORTE DETALLADO DE ESTUDIANTE
    // =================================================

    public function reporteEstudiante(int $idUsuario): ?array
    {
        $sql = "
            SELECT
                u.id_usuario,
                u.nombre,
                u.apellidos,
                u.email,
                u.estatus,

                (
                    SELECT COUNT(*)
                    FROM inscripciones i
                    WHERE i.id_estudiante = u.id_usuario
                ) AS total_cursos,

                (
                    SELECT COUNT(*)
                    FROM inscripciones i
                    WHERE i.id_estudiante = u.id_usuario
                    AND i.estatus = 'Terminada'
                ) AS cursos_terminados,

                (
                    SELECT COUNT(*)
                    FROM inscripciones i
                    WHERE i.id_estudiante = u.id_usuario
                    AND i.estatus = 'Activa'
                ) AS cursos_activos,

                (
                    SELECT COUNT(*)
                    FROM certificados ce
                    WHERE ce.id_estudiante = u.id_usuario
                ) AS total_certificados

            FROM usuarios u
            WHERE u.id_usuario = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }


    // =================================================
    // CURSOS DE UN INSTRUCTOR
    // =================================================

    public function cursosInstructor(int $idUsuario): array
    {
        $sql = "
            SELECT
                c.id_curso,
                c.nombre_curso,
                c.precio_curso,
                c.promedio_general,
                c.estatus,

                COUNT(DISTINCT i.id_inscripcion)
                    AS total_estudiantes

            FROM cursos c

            LEFT JOIN inscripciones i
                ON i.id_curso = c.id_curso

            WHERE c.id_instructor = :id

            GROUP BY c.id_curso

            ORDER BY c.id_curso DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =================================================
    // CURSOS Y PROGRESO DE UN ESTUDIANTE
    // =================================================

    public function cursosEstudiante(int $idUsuario): array
    {
        $sql = "
            SELECT
                c.id_curso,
                c.nombre_curso,
                u.nombre AS nombre_instructor,
                u.apellidos AS apellidos_instructor,

                i.fecha_inscripcion,
                i.fecha_terminacion,
                i.estatus,
                i.progreso

            FROM inscripciones i

            INNER JOIN cursos c
                ON c.id_curso = i.id_curso

            INNER JOIN usuarios u
                ON u.id_usuario = c.id_instructor

            WHERE i.id_estudiante = :id

            ORDER BY i.fecha_inscripcion DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
