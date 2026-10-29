<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Vaciar las variables de sesión.
$_SESSION = [];

// Eliminar la cookie de sesión del navegador.
if (ini_get("session.use_cookies")) {
    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $parametros["path"],
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"]
    );
}

// Destruir la sesión en el servidor.
session_destroy();

// Redirigir a la página de inicio.
header("Location: ../View/Public/Index.php");
exit;
