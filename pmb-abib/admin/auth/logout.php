<?php
// logout.php
session_start();

// Hapus semua session
$_SESSION = array();

// Hapus session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Redirect ke halaman login
if (strpos($_SERVER['PHP_SELF'], 'admin') !== false) {
    header("Location: login.php");
} else {
    header("Location: login.php");
}
exit();
?>