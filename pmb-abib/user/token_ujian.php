<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'camaba') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data biodata dan token
$query = "SELECT b.*, t.token, t.digunakan, t.tanggal_dibuat, t.tanggal_digunakan
          FROM biodata_camaba b 
          LEFT JOIN token_ujian t ON b.id = t.biodata_id 
          WHERE b.user_id = '$user_id' AND b.status_verifikasi = 'verified'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Generate token baru jika belum ada atau sudah digunakan
if (isset($_POST['generate_token']) && $data) {
    $token = substr(md5(uniqid(rand(), true)), 0, 10);
    
    // Hapus token lama jika ada
    mysqli_query($conn, "DELETE FROM token_ujian WHERE biodata_id = '{$data['id']}'");
    
    // Insert token baru
    $insert_query = "INSERT INTO token_ujian (biodata_id, token) VALUES ('{$data['id']}', '$token')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = "Token berhasil digenerate!";
        header("Location: token_ujian.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Ujian - PMB Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'dashboard.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0"><i class="fas fa-key"></i> Token Ujian Online</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($data && $data['status_verifikasi'] == 'verified'): ?>
                            <?php if ($data['token']): ?>
                                <div class="text-center mb-4">
                                    <div class="display-4 fw-bold text-primary mb-3"><?php echo $data['token']; ?></div>
                                    <p class="lead">Gunakan token ini untuk mengakses ujian online</p>
                                    
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Peringatan:</strong> Token hanya dapat digunakan sekali!
                                    </div>
                                    
                                    <div class="row text-start">
                                        <div class="col-md-6">
                                            <p><strong>Tanggal Dibuat:</strong><br>
                                            <?php echo date('d/m/Y H:i', strtotime($data['tanggal_dibuat'])); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong><br>
                                            <span class="badge bg-<?php echo $data['digunakan'] == '0' ? 'success' : 'danger'; ?>">
                                                <?php echo $data['digunakan'] == '0' ? 'BELUM DIGUNAKAN' : 'SUDAH DIGUNAKAN'; ?>
                                            </span></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <?php if ($data['digunakan'] == '0'): ?>
                                        <a href="ujian.php?token=<?php echo $data['token']; ?>" class="btn btn-success btn-lg">
                                            <i class="fas fa-play-circle me-2"></i> Mulai Ujian Sekarang
                                        </a>
                                    <?php else: ?>
                                        <form method="POST" action="">
                                            <button type="submit" name="generate_token" class="btn btn-primary btn-lg w-100">
                                                <i class="fas fa-redo me-2"></i> Generate Token Baru
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-key fa-4x text-muted mb-3"></i>
                                    <h4>Belum Ada Token</h4>
                                    <p class="text-muted mb-4">Generate token untuk mengikuti ujian online.</p>
                                    
                                    <form method="POST" action="" class="d-inline">
                                        <button type="submit" name="generate_token" class="btn btn-primary btn-lg">
                                            <i class="fas fa-plus-circle me-2"></i> Generate Token
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-lock fa-4x text-muted mb-3"></i>
                                <h4>Akses Ditolak</h4>
                                <p class="text-muted mb-3">
                                    Anda belum diverifikasi atau belum melengkapi biodata.
                                </p>
                                <div class="d-grid gap-2 col-md-8 mx-auto">
                                    <a href="biodata.php" class="btn btn-warning">
                                        <i class="fas fa-user-edit me-2"></i> Lengkapi Biodata
                                    </a>
                                    <a href="dashboard.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Instructions -->
                        <div class="mt-4 pt-4 border-top">
                            <h5><i class="fas fa-info-circle text-info me-2"></i> Petunjuk Penggunaan Token:</h5>
                            <ol class="mt-3">
                                <li>Token hanya berlaku untuk 1 kali ujian</li>
                                <li>Pastikan koneksi internet stabil sebelum memulai ujian</li>
                                <li>Waktu ujian tidak dapat dihentikan atau diulang</li>
                                <li>Siapkan dokumen identitas sebelum memulai ujian</li>
                                <li>Hasil ujian akan muncul maksimal 24 jam setelah ujian selesai</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>