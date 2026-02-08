<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

// Jika sudah login, redirect ke dashboard user
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'camaba') {
    header("Location: ../../profile/index.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Koneksi database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Query untuk cek camaba
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'camaba' AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: ../../profile/index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Camaba - PMB Abib</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
        input[type="submit"] { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
        .error { color: red; text-align: center; }
        .links { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login Calon Mahasiswa</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div>
                <input type="text" name="username" placeholder="Username/Email" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
        
        <div class="links">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            <p><a href="../../index.php">Kembali ke Beranda</a></p>
        </div>
    </div>
</body>
</html>