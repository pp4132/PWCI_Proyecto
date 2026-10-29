<?php
class AuthMiddleware
{
    /**
     * Comprueba que exista una sesión autenticada.
     */
    public static function requiereAutenticacion(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION["usuario_id"])) {
            header("Location: /Capa intermedia/View/Auth/Login.php");
            exit;
        }
    }

    /**
     * Normaliza los nombres de roles.
     */
    private static function normalizarRol(string $rol): string
    {
        return match (strtolower(trim($rol))) {
            "administrador" => "Administrador",
            "instructor" => "Instructor",
            "estudiante" => "Estudiante",
            default => "Desconocido"
        };
    }

    /**
     * Comprueba que el usuario tenga un rol permitido.
     */
    public static function requiereRol(array $rolesPermitidos): void
    {
        self::requiereAutenticacion();

        $rolSesion = $_SESSION["usuario_rol"] ?? "";

        $rolActual = self::normalizarRol($rolSesion);

        $rolesNormalizados = array_map(
            fn($rol) => self::normalizarRol((string) $rol),
            $rolesPermitidos
        );

        if (
            $rolActual === "Desconocido" ||
            !in_array($rolActual, $rolesNormalizados, true)
        ) {
            http_response_code(403);
            exit("403 - No tienes permiso para acceder a esta página.");
        }
    }

    /**
     * Devuelve el ID del usuario autenticado.
     */
    public static function obtenerUsuarioId(): int
    {
        self::requiereAutenticacion();

        return (int) $_SESSION["usuario_id"];
    }

    /**
     * Devuelve el rol normalizado del usuario.
     */
    public static function obtenerRol(): string
    {
        self::requiereAutenticacion();

        return self::normalizarRol(
            (string) ($_SESSION["usuario_rol"] ?? "")
        );
    }
}
