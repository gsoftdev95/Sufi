<?php
session_start();

// Vaciar variables de sesión
$_SESSION = [];

// Eliminar cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Eliminar cookies personalizadas
setcookie('correo', '', time() - 3600, '/');

// Destruir sesión
session_destroy();

// Redirigir
header("Location: index.php");
exit;
