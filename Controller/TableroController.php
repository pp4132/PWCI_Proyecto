<?php

require_once __DIR__ . "/../Middleware/AuthMiddleware.php";
require_once __DIR__ . "/../Model/conexion.php";
require_once __DIR__ . "/../Model/Tablero.php";

AuthMiddleware::requiereRol([
    "Administrador",
    "Instructor",
    "Estudiante"
]);

$idUsuario = AuthMiddleware::obtenerUsuarioId();
$rol = AuthMiddleware::obtenerRol();

try {
    $database = new Database();
    $db = $database->conectar();

    $sql = "
        SELECT
            u.id_usuario,
            u.nombre,
            u.apellidos,
            u.email,
            u.foto,
            u.estatus,
            r.nombre_rol
        FROM usuarios u
        INNER JOIN roles r ON r.id_rol = u.id_rol
        WHERE u.id_usuario = :id
        LIMIT 1
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute(["id" => $idUsuario]);

    $usuarioDB = $stmt->fetch();

    if (
        !$usuarioDB ||
        $usuarioDB["estatus"] !== "Activo"
    ) {
        http_response_code(403);
        exit("La cuenta no está activa o no existe.");
    }

    // Verificar también el rol actual registrado en MySQL.
    $rolBD = strtolower(trim($usuarioDB["nombre_rol"]));

    $rolBD = match ($rolBD) {
        "administrador" => "Administrador",
        "instructor" => "Instructor",
        "estudiante" => "Estudiante",
        default => "Desconocido"
    };

    if ($rolBD === "Desconocido" || $rolBD !== $rol) {
        http_response_code(403);
        exit("La sesión no coincide con el rol actual de la cuenta.");
    }

    $tablero = new Tablero();

    $datos = [
        "usuario" => [
            "id" => (int) $usuarioDB["id_usuario"],
            "nombre" => $usuarioDB["nombre"],
            "apellido" => $usuarioDB["apellidos"],
            "email" => $usuarioDB["email"],
            "foto" => $usuarioDB["foto"],
            "rol" => $rol
        ],
        "estadisticas" => $tablero->obtenerEstadisticas(
            $idUsuario,
            $rol
        ),
        "cursos" => $tablero->obtenerCursos(
            $idUsuario,
            $rol
        ),
        "actividad" => $tablero->obtenerActividad(
            $idUsuario,
            $rol
        )
    ];

    require __DIR__ . "/../View/Compartidas/Tablero.php";

} catch (Throwable $e) {
    error_log("Error en TableroController: " . $e->getMessage());

    http_response_code(500);
    exit("No se pudo cargar el tablero.");
}
