<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión completamente
if (session_id() != '') {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_destroy();

// Redirigir al login con ruta absoluta
header("Location: /Formato/login.php", true, 302);
exit();
?>