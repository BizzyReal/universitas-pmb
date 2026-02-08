<?php
// Set page title
$page_title = "Verifikasi Camaba";

// Include header
require_once 'includes/header.php';

// Handle Verification
if (isset($_GET['verify'])) {
    $id = mysqli_real_escape_string($conn, $_GET['verify']);
    $query = "UPDATE biodata_camaba SET status_verifikasi = 'verified' WHERE id = '$id'";
    mysqli_query($conn, $query);
    
    // Generate token otomatis
    $token = substr(md5(uniqid(rand(), true)), 0, 10);
    $token_query = "INSERT INTO token_ujian (biodata_id, token) VALUES ('$id', '$token')";
    mysqli_query($conn, $token_query);
    
    $_SESSION['success'] = "Camaba berhasil diverifikasi! Token: $token";
    header("Location: verifikasi_camaba.php");
    exit();
}

// Handle Rejection
if (isset($_GET['reject'])) {
    $id = mysqli_real_escape_string($conn, $_GET['reject']);
    $query = "UPDATE biodata_camaba SET status_verifikasi = 'rejected' WHERE id = '$id'";
    mysqli_query($conn, $query);
    $_SESSION['success'] = "Camaba berhasil ditolak!";
    header("Location: verifikasi_camaba.php");
    exit();
}

// Ambil data pending verification
$query = "SELECT b.*, u.username, u.email 
          FROM biodata_camaba b 
          JOIN users u ON b.user_id = u.id 
          WHERE b.status_verifikasi = 'pending'
          ORDER BY b.tanggal_daftar DESC";
$pending_camaba = mysqli_query($conn, $query);
?>

<div class="main-content">
    <!-- Topbar -->
    <header class="topbar border-bottom bg-white sticky-top">
        <div class="container-fluid py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-3 d-lg-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h4 class="mb-0"><i class="fas fa-user-check text-primary me-2"></i> Verifikasi Camaba</h4>
                        <small class="text-muted">Verifikasi data dan generate token ujian</small>
                    </div>
                </div>
                <div>
                    <a href="dashboard.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="container-fluid py-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock text-warning me-2"></i> Menunggu Verifikasi</h5>
            </div>
            
            <div class="card-body">
                <?php if(mysqli_num_rows($pending_camaba) > 0): ?>
                    <div class="row">
                        <?php while($row = mysqli_fetch_assoc($pending_camaba)): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <h6 class="card-title text-truncate"><?php echo $row['nama_lengkap']; ?></h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-user me-1"></i> <?php echo $row['username']; ?>
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-envelope me-1"></i> <?php echo $row['email']; ?>
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-school me-1"></i> <?php echo $row['asal_sekolah']; ?>
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-calendar me-1"></i> <?php echo date('d/m/Y H:i', strtotime($row['tanggal_daftar'])); ?>
                                        </small>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="?verify=<?php echo $row['id']; ?>" 
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Verifikasi camaba ini?')">
                                            <i class="fas fa-check me-1"></i> Verifikasi
                                        </a>
                                        <div class="btn-group">
                                            <a href="?reject=<?php echo $row['id']; ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Tolak camaba ini?')">
                                                <i class="fas fa-times me-1"></i> Tolak
                                            </a>
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5>Tidak ada data menunggu verifikasi</h5>
                        <p class="text-muted">Semua camaba sudah diverifikasi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once 'includes/footer.php';
?>