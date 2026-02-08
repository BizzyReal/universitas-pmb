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

// Cek jika sudah login, redirect sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../../admin/auth/dashboard.php");
    } else {
        header("Location: ../../profile/index.php");
    }
    exit();
}

$error = '';
$success = '';
$username = $email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    
    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Username hanya boleh mengandung huruf, angka, dan underscore!";
    } else {
        // Koneksi database
        $conn = getConnection();
        
        if (!$conn) {
            $error = "Koneksi database gagal!";
        } else {
            // Cek apakah username atau email sudah terdaftar
            $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
            $check_stmt = $conn->prepare($check_sql);
            
            if ($check_stmt) {
                $check_stmt->bind_param("ss", $username, $email);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    $error = "Username atau email sudah terdaftar!";
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert user baru
                    $insert_sql = "INSERT INTO users (username, email, password, role, status, created_at) 
                                   VALUES (?, ?, ?, 'camaba', 'active', NOW())";
                    $insert_stmt = $conn->prepare($insert_sql);
                    
                    if ($insert_stmt) {
                        $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
                        
                        if ($insert_stmt->execute()) {
                            // Auto create camaba record if fullname provided
                            if (!empty($fullname)) {
                                $user_id = $conn->insert_id;
                                $camaba_sql = "INSERT INTO camaba (user_id, nama_lengkap, tanggal_daftar) 
                                              VALUES (?, ?, NOW())";
                                $camaba_stmt = $conn->prepare($camaba_sql);
                                if ($camaba_stmt) {
                                    $camaba_stmt->bind_param("is", $user_id, $fullname);
                                    $camaba_stmt->execute();
                                    $camaba_stmt->close();
                                }
                            }
                            
                            $success = "Pendaftaran berhasil! Silakan login.";
                            // Clear form
                            $username = $email = $fullname = '';
                        } else {
                            $error = "Gagal mendaftar: " . $conn->error;
                        }
                        
                        $insert_stmt->close();
                    } else {
                        $error = "Terjadi kesalahan pada query insert!";
                    }
                }
                
                $check_stmt->close();
            } else {
                $error = "Terjadi kesalahan pada query check!";
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
    <title>Register - PMB ABIB</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #6c757d;
            --success: #28a745;
        }
        
        body {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.9), rgba(58, 86, 212, 0.9)),
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h2 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .register-header p {
            color: var(--secondary);
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-register {
            background: var(--success);
            color: white;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .error-message {
            background: #ffe6e6;
            border: 1px solid #ffcccc;
            color: #d9534f;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .success-message {
            background: #e6ffe6;
            border: 1px solid #b3ffb3;
            color: var(--success);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .password-strength {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2><i class="fas fa-user-plus me-2"></i>Pendaftaran Akun Baru</h2>
            <p>Daftar sebagai calon mahasiswa PMB ABIB</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                <div class="mt-2">
                    <a href="login.php" class="btn btn-sm btn-primary">Login Sekarang</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($success)): ?>
        <form method="POST" action="" id="registerForm">
            <div class="mb-3">
                <label for="fullname" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="fullname" name="fullname" 
                           placeholder="Masukkan nama lengkap"
                           value="<?php echo htmlspecialchars($fullname); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Contoh: budi123" required
                           value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <small class="text-muted">Username hanya boleh huruf, angka, dan underscore</small>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="contoh@email.com" required
                           value="<?php echo htmlspecialchars($email); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Minimal 6 karakter" required>
                </div>
                <div id="passwordStrength" class="password-strength"></div>
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Ketik ulang password" required>
                </div>
                <div id="passwordMatch" class="password-strength"></div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="showPasswords">
                <label class="form-check-label" for="showPasswords">Tampilkan Password</label>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                <label class="form-check-label" for="agreeTerms">
                    Saya menyetujui <a href="#" class="text-primary">Syarat dan Ketentuan</a>
                </label>
            </div>
            
            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </button>
        </form>
        <?php endif; ?>
        
        <div class="links">
            <p class="mb-2">Sudah punya akun? <a href="login.php" class="text-primary">Login di sini</a></p>
            <p class="mb-0"><a href="../../index.php" class="text-secondary"><i class="fas fa-home me-1"></i>Kembali ke Beranda</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle show passwords
        document.getElementById('showPasswords').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmField = document.getElementById('confirm_password');
            
            if (this.checked) {
                passwordField.type = 'text';
                confirmField.type = 'text';
            } else {
                passwordField.type = 'password';
                confirmField.type = 'password';
            }
        });
        
        // Check password strength
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            let strength = 0;
            let message = '';
            let className = '';
            
            if (password.length === 0) {
                message = '';
            } else if (password.length < 6) {
                message = 'Password terlalu pendek (minimal 6 karakter)';
                className = 'strength-weak';
            } else {
                // Check for character types
                const hasLower = /[a-z]/.test(password);
                const hasUpper = /[A-Z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                
                strength = hasLower + hasUpper + hasNumbers + hasSpecial;
                
                switch(strength) {
                    case 1:
                        message = 'Password lemah';
                        className = 'strength-weak';
                        break;
                    case 2:
                        message = 'Password sedang';
                        className = 'strength-medium';
                        break;
                    case 3:
                        message = 'Password kuat';
                        className = 'strength-strong';
                        break;
                    case 4:
                        message = 'Password sangat kuat';
                        className = 'strength-strong';
                        break;
                }
            }
            
            strengthDiv.textContent = message;
            strengthDiv.className = 'password-strength ' + className;
        });
        
        // Check password match
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirm.length === 0) {
                matchDiv.textContent = '';
            } else if (password === confirm) {
                matchDiv.textContent = '✓ Password cocok';
                matchDiv.className = 'password-strength strength-strong';
            } else {
                matchDiv.textContent = '✗ Password tidak cocok';
                matchDiv.className = 'password-strength strength-weak';
            }
        });
        
        // Form validation
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            // Validate username
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                e.preventDefault();
                alert('Username hanya boleh mengandung huruf, angka, dan underscore!');
                return false;
            }
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return false;
            }
            
            // Validate password
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (!agreeTerms) {
                e.preventDefault();
                alert('Anda harus menyetujui Syarat dan Ketentuan!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>