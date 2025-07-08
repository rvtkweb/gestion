<?php
// session_secure.php
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerar ID de sesión cada 5 minutos
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 300) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
    
    // Timeout de sesión (1 hora)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
        session_unset();
        session_destroy();
        return false;
    }
    $_SESSION['last_activity'] = time();
    
    return true;
}
?>