
<?php

class AuthMiddleware
{
    /**
     * Verifica si el usuario ha iniciado sesión.
     */
    public static function requiereAutenticacion(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['usuario_id'])) {
            header('Location: /View/Auth/Login.php');
            exit;
        }
    }

    /**
     * Verifica si el usuario tiene uno de los roles permitidos.
     */
    public static function requiereRol(array $rolesPermitidos): void
    {
        self::requiereAutenticacion();

        $rol = $_SESSION['usuario_rol'] ?? null;

        if (!in_array($rol, $rolesPermitidos, true)) {
            http_response_code(403);
            exit('403 - No tienes permiso para acceder a esta página.');
        }
    }
}
