<?php
// logout.php - Cerrar sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpiar todas las variables de sesión
if (isset($_SESSION)) {
    $_SESSION = array();
    
    // Eliminar cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir sesión
    session_destroy();
}

// Redirigir a página de login
header('Location: index.php');
exit;
?>