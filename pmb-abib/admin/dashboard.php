<?php
// Set page title
$page_title = "Dashboard";

// Include header
require_once __DIR__ . '/includes/header.php';

// Get statistics
$stats = [];
$stats['total_camaba'] = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM biodata_camaba"))['total'] ?? 0;
$stats['verified'] = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM biodata_camaba WHERE status_verifikasi='verified'"))['total'] ?? 0;
$stats['lulus'] = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM hasil_ujian WHERE status='lulus'"))['total'] ?? 0;
$stats['daftar_ulang'] = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM daftar_ulang WHERE status='approved'"))['total'] ?? 0;
?>

<!-- Include sidebar -->
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle me-3 d-lg-none">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt text-primary me-2"></i> Dashboard</h4>
                    <small class="text-muted">Monitor sistem penerimaan mahasiswa baru</small>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <small class="text-muted d-block">Login sebagai:</small>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Welcome Banner -->
        <div class="alert alert-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Selamat Datang, <?php echo $_SESSION['username']; ?>!</h5>
                    <p class="mb-0">Selamat mengelola sistem Penerimaan Mahasiswa Baru.</p>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-50"></i>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo $stats['total_camaba']; ?></h2>
                                <small class="text-muted">Total Calon Maba</small>
                            </div>
                            <div class="bg-primary text-white rounded p-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo $stats['verified']; ?></h2>
                                <small class="text-muted">Terverifikasi</small>
                            </div>
                            <div class="bg-success text-white rounded p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo $stats['lulus']; ?></h2>
                                <small class="text-muted">Lulus Ujian</small>
                            </div>
                            <div class="bg-info text-white rounded p-3">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo $stats['daftar_ulang']; ?></h2>
                                <small class="text-muted">Daftar Ulang</small>
                            </div>
                            <div class="bg-warning text-white rounded p-3">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="verifikasi_camaba.php" class="btn btn-outline-primary w-100 h-100 py-4">
                                    <i class="fas fa-user-check fa-2x mb-2"></i>
                                    <div>Verifikasi Camaba</div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="soal_ujian.php" class="btn btn-outline-success w-100 h-100 py-4">
                                    <i class="fas fa-question-circle fa-2x mb-2"></i>
                                    <div>Kelola Soal</div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="daftar_ulang.php" class="btn btn-outline-info w-100 h-100 py-4">
                                    <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                    <div>Daftar Ulang</div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="data_mahasiswa.php" class="btn btn-outline-warning w-100 h-100 py-4">
                                    <i class="fas fa-id-card fa-2x mb-2"></i>
                                    <div>Data Mahasiswa</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>