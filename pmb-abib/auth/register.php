<?php
// C:\xampp\htdocs\pmb-abib\auth\register.php
ob_start(); // Start output buffering

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../user/dashboard.php");
    }
    exit();
}

// Include database config
require_once __DIR__ . '/../config/database.php';

// Check database connection
if (!isset($conn) || !$conn) {
    die("Database connection failed. Please check config/database.php");
}

$error = '';
$success = '';

// Get jurusan data
$jurusan_query = "SELECT * FROM jurusan";
$jurusan_result = mysqli_query($conn, $jurusan_query);

if (!$jurusan_result) {
    $error = "Error loading jurusan data: " . mysqli_error($conn);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $jurusan_id = isset($_POST['jurusan_id']) ? $_POST['jurusan_id'] : '';
    
    // Validation
    $errors = [];
    
    if (empty($nama_lengkap)) $errors[] = "Nama lengkap harus diisi";
    if (empty($username)) $errors[] = "Username harus diisi";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (empty($password)) $errors[] = "Password harus diisi";
    if (empty($jurusan_id)) $errors[] = "Jurusan harus dipilih";
    
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }
    
    if (!empty($password) && !empty($confirm_password) && $password !== $confirm_password) {
        $errors[] = "Password tidak cocok";
    }
    
    if (empty($errors)) {
        // Check if username/email already exists
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            // Insert user
            $insert_user_sql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, 'camaba')";
            $insert_user_stmt = mysqli_prepare($conn, $insert_user_sql);
            mysqli_stmt_bind_param($insert_user_stmt, "sss", $username, $email, $password);
            
            if (mysqli_stmt_execute($insert_user_stmt)) {
                $user_id = mysqli_insert_id($conn);
                
                // Insert biodata
                $insert_biodata_sql = "INSERT INTO biodata_camaba (user_id, nama_lengkap, jurusan_id) VALUES (?, ?, ?)";
                $insert_biodata_stmt = mysqli_prepare($conn, $insert_biodata_sql);
                mysqli_stmt_bind_param($insert_biodata_stmt, "isi", $user_id, $nama_lengkap, $jurusan_id);
                
                if (mysqli_stmt_execute($insert_biodata_stmt)) {
                    $success = "Pendaftaran berhasil! Silakan login.";
                    // Clear form
                    $nama_lengkap = $username = $email = $jurusan_id = '';
                } else {
                    $error = "Gagal menyimpan biodata: " . mysqli_error($conn);
                }
            } else {
                $error = "Gagal membuat akun: " . mysqli_error($conn);
            }
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

ob_end_flush(); // Flush output buffer
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PMB Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,.1);
        }
        .card-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-user-plus"></i> Pendaftaran Calon Mahasiswa</h3>
                        <p class="mb-0">Isi formulir berikut untuk mendaftar</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $success; ?>
                                <div class="mt-2">
                                    <a href="login.php" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-sign-in-alt me-1"></i> Login Sekarang
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" name="nama_lengkap" required 
                                           value="<?php echo htmlspecialchars($nama_lengkap ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jurusan Pilihan *</label>
                                    <select class="form-control" name="jurusan_id" required>
                                        <option value="">-- Pilih Jurusan --</option>
                                        <?php
                                        if ($jurusan_result && mysqli_num_rows($jurusan_result) > 0):
                                            while ($jurusan = mysqli_fetch_assoc($jurusan_result)):
                                                $selected = (isset($jurusan_id) && $jurusan_id == $jurusan['id']) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $jurusan['id']; ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($jurusan['nama_jurusan'] . ' (' . $jurusan['kode_jurusan'] . ')'); ?>
                                        </option>
                                        <?php endwhile; else: ?>
                                        <option value="1">Teknik Informatika (TI)</option>
                                        <option value="2">Sistem Informasi (SI)</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username *</label>
                                    <input type="text" class="form-control" name="username" required 
                                           value="<?php echo htmlspecialchars($username ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required 
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password *</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya menyetujui syarat dan ketentuan
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                            </button>
                            
                            <div class="text-center mt-3">
                                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                                <p><a href="../index.php"><i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Close connection
if (isset($conn)) {
    mysqli_close($conn);
}
?>