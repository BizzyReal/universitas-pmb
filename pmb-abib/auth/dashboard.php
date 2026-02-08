 <?php
session_start();

// Mode debug - aktifkan dengan mengatur ke true
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    echo "# DEBUG MODE<br>";
    echo "Session started<br>";
    echo "Current script: " . $_SERVER['PHP_SELF'] . "<br>";
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    if (DEBUG_MODE) {
        echo "Not logged in, redirecting to login page...<br>";
        echo "Redirect path: ../login.php<br>";
    }
    
    // Redirect ke login.php dengan path relatif yang benar
    header("Location: ../login.php");
    exit();
}

// Jika sudah login, tampilkan dashboard
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to Dashboard, <?php echo $_SESSION['username'] ?? 'User'; ?>!</h1>
    <p>You are logged in successfully.</p>
    <a href="logout.php">Logout</a>
</body>
</html>