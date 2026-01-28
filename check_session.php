<?php
// check_session.php
// Incluir este archivo al inicio de cada página protegida

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Formato/login.php", true, 302);
    exit();
}

// Opcional: verificar tiempo de inactividad (30 minutos)
$timeout = 1800; // 30 minutos en segundos

if (isset($_SESSION['ultimo_acceso'])) {
    $inactivo = time() - $_SESSION['ultimo_acceso'];
    
    if ($inactivo > $timeout) {
        session_unset();
        session_destroy();
        header("Location: /Formato/login.php?timeout=1", true, 302);
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time();
?>