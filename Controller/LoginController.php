<?php

require_once __DIR__ . "/../Model/Usuario.php";

class LoginController
{
    private Usuario $modeloUsuario;

    public function __construct()
    {
        $this->modeloUsuario = new Usuario();
    }

    public function iniciarSesion(
        string $email,
        string $password
    ): ?string {

        // Comprobamos las credenciales mediante el modelo.
        $usuario = $this->modeloUsuario->autenticar(
            trim($email),
            $password
        );

        if ($usuario === null) {
            return "Correo o contraseña incorrectos, o cuenta no activa.";
        }

        // Iniciamos la sesión después de validar las credenciales.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Evita reutilizar el identificador anterior de sesión.
        session_regenerate_id(true);

        // Datos que necesitará el middleware.
        $_SESSION["usuario_id"] = (int) $usuario["id_usuario"];
        $_SESSION["usuario_rol_id"] = (int) $usuario["id_rol"];
        $_SESSION["usuario_rol"] = $usuario["nombre_rol"];
        $_SESSION["usuario_nombre"] = $usuario["nombre"];

        // Redirección al tablero compartido.
        header("Location: ../Compartidas/Tablero.php");
        exit;
    }
}