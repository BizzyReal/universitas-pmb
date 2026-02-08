<?php
session_start();

// Debug mode
$debug_mode = true;

if ($debug_mode) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Cek config
$config_path = __DIR__ . '/../../config/database.php';
if (!file_exists($config_path)) {
    die("Error: Database configuration file not found at $config_path");
}

require_once($config_path);

// Cek jika sudah login sebagai admin, redirect ke dashboard
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Jika login sebagai user, logout dulu
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'camaba') {
    session_destroy();
    session_start();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        // Koneksi database
        $conn = getConnection();
        
        if (!$conn) {
            $error = "Koneksi database gagal!";
        } else {
            // Query untuk cek admin
            $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND role = 'admin' AND status = 'active'";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("ss", $username, $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($user = $result->fetch_assoc()) {
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Password salah!";
                    }
                } else {
                    $error = "Username/email tidak ditemukan atau bukan admin!";
                }
                
                $stmt->close();
            } else {
                $error = "Terjadi kesalahan pada query!";
            }
            
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PMB ABIB</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a1a2e;
            --secondary: #16213e;
        }
        
        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .admin-login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        
        .admin-login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .admin-login-header h2 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .admin-login-header i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .btn-admin-login {
            background: var(--primary);
            color: white;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-admin-login:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-header">
            <i class="fas fa-user-shield"></i>
            <h2>Admin Login</h2>
            <p class="text-muted">Panel Administrasi PMB ABIB</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username atau Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan username admin" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Masukkan password admin" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-admin-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login sebagai Admin
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="../../index.php" class="text-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>