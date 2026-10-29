
<?php

require_once __DIR__ . "/conexion.php";

class Tablero
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    /**
     * Normaliza el nombre del rol.
     */
    private function normalizarRol(string $rol): string
    {
        $rol = strtolower(trim($rol));

        return match ($rol) {
            "admin", "administrador" => "Admin",
            "instructor" => "Instructor",
            "estudiante" => "Estudiante",
            default => "Desconocido"
        };
    }

    /**
     * Estadísticas según el rol.
     */
    public function obtenerEstadisticas(
        int $idUsuario,
        string $rol
    ): array {
        $rol = $this->normalizarRol($rol);

        switch ($rol) {
            case "Estudiante":
                $sql = "
                    SELECT
                        COUNT(*) AS total_cursos,
                        COALESCE(
                            SUM(estatus = 'Terminada'), 0
                        ) AS cursos_terminados,
                        COALESCE(
                            ROUND(AVG(
                                CASE
                                    WHEN estatus = 'Activa'
                                    THEN progreso
                                END
                            ), 1), 0
                        ) AS progreso_promedio
                    FROM inscripciones
                    WHERE id_estudiante = :id
                ";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(["id" => $idUsuario]);
                $r = $stmt->fetch();

                return [
                    [
                        "nombre" => "Mis cursos",
                        "valor" => (int) $r["total_cursos"],
                        "icono" => "▣",
                        "color" => "purple"
                    ],
                    [
                        "nombre" => "Cursos terminados",
                        "valor" => (int) $r["cursos_terminados"],
                        "icono" => "✓",
                        "color" => "green"
                    ],
                    [
                        "nombre" => "Progreso promedio",
                        "valor" => $r["progreso_promedio"] . "%",
                        "icono" => "↗",
                        "color" => "blue"
                    ]
                ];

            case "Instructor":
                $sql = "
                    SELECT
                        COUNT(DISTINCT c.id_curso) AS total_cursos,
                        COUNT(DISTINCT CASE
                            WHEN i.estatus = 'Activa'
                            THEN i.id_estudiante
                        END) AS total_estudiantes
                    FROM cursos c
                    LEFT JOIN inscripciones i
                        ON i.id_curso = c.id_curso
                    WHERE c.id_instructor = :id
                ";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(["id" => $idUsuario]);
                $r = $stmt->fetch();

                $sqlVentas = "
                    SELECT COALESCE(SUM(p.monto), 0)
                    FROM pagos p
                    INNER JOIN cursos c
                        ON c.id_curso = p.id_curso
                    WHERE c.id_instructor = :id
                      AND p.estado_pago = 'Completado'
                ";

                $stmt = $this->db->prepare($sqlVentas);
                $stmt->execute(["id" => $idUsuario]);
                $ventas = (float) $stmt->fetchColumn();

                return [
                    [
                        "nombre" => "Mis cursos",
                        "valor" => (int) $r["total_cursos"],
                        "icono" => "▣",
                        "color" => "purple"
                    ],
                    [
                        "nombre" => "Estudiantes activos",
                        "valor" => (int) $r["total_estudiantes"],
                        "icono" => "♙",
                        "color" => "blue"
                    ],
                    [
                        "nombre" => "Pagos completados",
                        "valor" => "$" . number_format(
                            $ventas, 2
                        ),
                        "icono" => "$",
                        "color" => "green"
                    ]
                ];

            case "Admin":
                $consultas = [
                    [
                        "Usuarios registrados",
                        "SELECT COUNT(*) FROM usuarios"
                    ],
                    [
                        "Cursos publicados",
                        "SELECT COUNT(*) FROM cursos
                         WHERE estatus = 1"
                    ],
                    [
                        "Inscripciones activas",
                        "SELECT COUNT(*) FROM inscripciones
                         WHERE estatus = 'Activa'"
                    ],
                    [
                        "Ingresos registrados",
                        "SELECT COALESCE(SUM(monto), 0)
                         FROM pagos
                         WHERE estado_pago = 'Completado'"
                    ]
                ];

                $estadisticas = [];

                foreach ($consultas as $i => [$nombre, $sql]) {
                    $valor = $this->db
                        ->query($sql)
                        ->fetchColumn();

                    if ($i === 3) {
                        $valor = "$" . number_format(
                            (float) $valor, 2
                        );
                    } else {
                        $valor = (int) $valor;
                    }

                    $estadisticas[] = [
                        "nombre" => $nombre,
                        "valor" => $valor,
                        "icono" => ["♙", "▣", "✓", "$"][$i],
                        "color" => [
                            "purple", "blue", "green", "orange"
                        ][$i]
                    ];
                }

                return $estadisticas;

            default:
                return [];
        }
    }

    /**
     * Cursos correspondientes al usuario.
     */
    public function obtenerCursos(
        int $idUsuario,
        string $rol
    ): array {
        $rol = $this->normalizarRol($rol);

        if ($rol === "Estudiante") {
            $sql = "
                SELECT
                    c.id_curso AS id,
                    c.nombre_curso AS titulo,
                    cat.nombre_categoria AS categoria,
                    CONCAT(u.nombre, ' ', u.apellidos)
                        AS instructor,
                    i.progreso
                FROM inscripciones i
                INNER JOIN cursos c
                    ON c.id_curso = i.id_curso
                INNER JOIN categorias cat
                    ON cat.id_categoria = c.id_categoria
                INNER JOIN usuarios u
                    ON u.id_usuario = c.id_instructor
                WHERE i.id_estudiante = :id
                  AND i.estatus = 'Activa'
                  AND c.estatus = 1
                ORDER BY i.fecha_inscripcion DESC
            ";

        } elseif ($rol === "Instructor") {
            $sql = "
                SELECT
                    c.id_curso AS id,
                    c.nombre_curso AS titulo,
                    cat.nombre_categoria AS categoria,
                    COUNT(DISTINCT CASE
                        WHEN i.estatus = 'Activa'
                        THEN i.id_estudiante
                    END) AS estudiantes
                FROM cursos c
                INNER JOIN categorias cat
                    ON cat.id_categoria = c.id_categoria
                LEFT JOIN inscripciones i
                    ON i.id_curso = c.id_curso
                WHERE c.id_instructor = :id
                GROUP BY
                    c.id_curso,
                    c.nombre_curso,
                    cat.nombre_categoria
                ORDER BY c.id_curso DESC
            ";

        } else {
            return [];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute(["id" => $idUsuario]);

        $cursos = $stmt->fetchAll();

        foreach ($cursos as &$curso) {
            $curso["color"] = "purple";

            if ($rol === "Estudiante") {
                $curso["progreso"] = (float) $curso["progreso"];
            }

            if ($rol === "Instructor") {
                $curso["estudiantes"] = (int) $curso["estudiantes"];
            }
        }
        unset($curso);

        return $cursos;
    }

    /**
     * Actividad reciente para cada rol.
     */
    public function obtenerActividad(
        int $idUsuario,
        string $rol
    ): array {
        $rol = $this->normalizarRol($rol);

        if ($rol === "Admin") {
            $sql = "
                SELECT
                    l.accion AS tipo,
                    l.accion AS titulo,
                    COALESCE(
                        l.descripcion, 'Sin descripción'
                    ) AS descripcion,
                    DATE_FORMAT(
                        l.fecha, '%d/%m/%Y %H:%i'
                    ) AS fecha
                FROM log_administrador l
                ORDER BY l.fecha DESC
                LIMIT 8
            ";

            $stmt = $this->db->query($sql);

        } elseif ($rol === "Estudiante") {
            $sql = "
                SELECT
                    'Curso' AS tipo,
                    CONCAT(
                        'Inscripción: ', c.nombre_curso
                    ) AS titulo,
                    CONCAT(
                        'Estado: ', i.estatus,
                        ' · Progreso: ', i.progreso, '%'
                    ) AS descripcion,
                    DATE_FORMAT(
                        i.fecha_inscripcion, '%d/%m/%Y %H:%i'
                    ) AS fecha
                FROM inscripciones i
                INNER JOIN cursos c
                    ON c.id_curso = i.id_curso
                WHERE i.id_estudiante = :id
                ORDER BY i.fecha_inscripcion DESC
                LIMIT 8
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(["id" => $idUsuario]);

        } elseif ($rol === "Instructor") {
            $sql = "
                SELECT
                    'Curso' AS tipo,
                    c.nombre_curso AS titulo,
                    CONCAT(
                        'Estado: ',
                        IF(c.estatus = 1, 'Publicado', 'Inactivo')
                    ) AS descripcion,
                    'Curso registrado' AS fecha
                FROM cursos c
                WHERE c.id_instructor = :id
                ORDER BY c.id_curso DESC
                LIMIT 8
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(["id" => $idUsuario]);

        } else {
            return [];
        }

        $actividad = $stmt->fetchAll();

        foreach ($actividad as &$item) {
            $item["color"] = "purple";
            $item["icono"] = "•";
        }
        unset($item);

        return $actividad;
    }
}
