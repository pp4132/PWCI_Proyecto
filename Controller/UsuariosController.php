<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Model/conexion.php';
require_once __DIR__ . '/../Model/UsuarioAdmin.php';

AuthMiddleware::requiereRol(['Administrador']);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rolSesion = AuthMiddleware::obtenerRol();

try {

    $database = new Database();
    $db = $database->conectar();

    $modelo = new UsuarioAdmin($db);

    // =========================================
    // VALIDAR ADMINISTRADOR ACTUAL
    // =========================================

    $sqlAdmin = "
        SELECT
            u.id_usuario,
            u.nombre,
            u.apellidos,
            u.email,
            u.foto,
            u.estatus,
            r.nombre_rol

        FROM usuarios u

        INNER JOIN roles r
            ON r.id_rol = u.id_rol

        WHERE u.id_usuario = :id

        LIMIT 1
    ";

    $stmt = $db->prepare($sqlAdmin);
    $stmt->execute(['id' => $idUsuario]);

    $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$usuarioDB ||
        $usuarioDB['estatus'] !== 'Activo' ||
        $usuarioDB['nombre_rol'] !== 'Administrador' ||
        $rolSesion !== 'Administrador'
    ) {
        http_response_code(403);
        exit('Acceso denegado.');
    }


    // =========================================
    // PROTECCIÓN CSRF
    // =========================================

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['csrf_usuarios'])) {
        $_SESSION['csrf_usuarios'] = bin2hex(
            random_bytes(32)
        );
    }


    // =========================================
    // CAMBIAR ESTATUS
    // =========================================

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $token = $_POST['csrf_token'] ?? '';

        if (
            !is_string($token) ||
            !hash_equals(
                $_SESSION['csrf_usuarios'],
                $token
            )
        ) {
            http_response_code(403);
            exit('Solicitud no válida.');
        }

        $idObjetivo = filter_var(
            $_POST['id_usuario'] ?? null,
            FILTER_VALIDATE_INT
        );

        $nuevoEstatus = $_POST['estatus'] ?? '';

        if (!$idObjetivo || $idObjetivo <= 0) {
            http_response_code(400);
            exit('Usuario no válido.');
        }

        // No permitir modificar la propia cuenta.
        if ($idObjetivo === (int) $idUsuario) {
            http_response_code(403);
            exit('No puedes modificar tu propia cuenta.');
        }

        $resultado = $modelo->cambiarEstatus(
            $idObjetivo,
            $nuevoEstatus
        );

        if (!$resultado) {
            http_response_code(400);
            exit('No se pudo actualizar el estatus.');
        }

        header(
            'Location: ' . url('Controller/UsuariosController.php')
        );

        exit;
    }


    // =========================================
    // INFORMACIÓN DEL ADMINISTRADOR
    // =========================================

    $datos = [
        'usuario' => [
            'id' => (int) $usuarioDB['id_usuario'],
            'nombre' => $usuarioDB['nombre'],
            'apellido' => $usuarioDB['apellidos'],
            'correo' => $usuarioDB['email'],
            'foto' => $usuarioDB['foto'],
            'rol' => 'Administrador'
        ]
    ];


    // =========================================
    // FILTROS
    // =========================================
    $rolFiltro = $_GET['rol'] ?? '';
    $estatusFiltro = $_GET['estatus'] ?? '';
    $buscar = trim($_GET['buscar'] ?? '');

    $rolesValidos = [
        'Estudiante',
        'Instructor',
        'Administrador'
    ];

    $estatusValidos = [
        'Pendiente',
        'Activo',
        'Baneado'
    ];

    if (!in_array($rolFiltro, $rolesValidos, true)) {
        $rolFiltro = '';
    }

    if (!in_array($estatusFiltro, $estatusValidos, true)) {
        $estatusFiltro = '';
    }

    $filtros = [
        'rol' => $rolFiltro,
        'estatus' => $estatusFiltro,
        'buscar' => $buscar
    ];


    // =========================================
    // OBTENER INFORMACIÓN
    // =========================================

    $usuarios = $modelo->listar($filtros);

    // Las estadísticas no dependen de los filtros.
    $estadisticas = $modelo->obtenerEstadisticas();

    // Variables para la vista.
    $totalUsuarios = $estadisticas['total'];
    $usuariosActivos = $estadisticas['activos'];
    $usuariosPendientes = $estadisticas['pendientes'];
    $usuariosBaneados = $estadisticas['baneados'];
    $estudiantes = $estadisticas['estudiantes'];
    $instructores = $estadisticas['instructores'];


    // =========================================
    // REPORTE INDIVIDUAL
    // =========================================

    $reporte = null;
    $cursosReporte = [];

    $idReporte = filter_var(
        $_GET['detalle'] ?? null,
        FILTER_VALIDATE_INT
    );

    if ($idReporte && $idReporte > 0) {

        $sqlRol = "
            SELECT r.nombre_rol
            FROM usuarios u
            INNER JOIN roles r
                ON r.id_rol = u.id_rol
            WHERE u.id_usuario = :id
        ";

        $stmt = $db->prepare($sqlRol);
        $stmt->execute(['id' => $idReporte]);

        $rolReporte = $stmt->fetchColumn();

        if ($rolReporte === 'Instructor') {

            $reporte = $modelo->reporteInstructor(
                $idReporte
            );

            if ($reporte) {
                $cursosReporte = $modelo->cursosInstructor(
                    $idReporte
                );
            }

        } elseif ($rolReporte === 'Estudiante') {

            $reporte = $modelo->reporteEstudiante(
                $idReporte
            );

            if ($reporte) {
                $cursosReporte = $modelo->cursosEstudiante(
                    $idReporte
                );
            }
        }
    }

    require __DIR__ . '/../View/Admin/Usuarios.php';

} catch (Throwable $e) {

    error_log(
        'Error en UsuariosController: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('No se pudo cargar la administración de usuarios.');
}
