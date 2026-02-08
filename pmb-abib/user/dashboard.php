<?php
// Set page title
$page_title = "Dashboard Camaba";

// Include header
require_once __DIR__ . '/includes/header.php';

// Include sidebar
require_once __DIR__ . '/includes/sidebar.php';

// Query data lengkap camaba
$query = "SELECT b.*, j.nama_jurusan, j.kode_jurusan,
                 u.username, u.email,
                 h.nilai as nilai_ujian, h.status as status_ujian,
                 du.nim, du.status as status_daftar_ulang, du.tanggal_approved
          FROM biodata_camaba b
          LEFT JOIN jurusan j ON b.jurusan_id = j.id
          LEFT JOIN users u ON b.user_id = u.id
          LEFT JOIN hasil_ujian h ON b.id = h.biodata_id
          LEFT JOIN daftar_ulang du ON b.id = du.biodata_id
          WHERE b.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<div class="main-content" id="mainContent">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle me-3" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt text-primary me-2"></i> Dashboard</h4>
                    <small class="text-muted">Selamat datang di portal PMB</small>
                </div>
            </div>
            <div>
                <span class="badge bg-primary">
                    <i class="fas fa-calendar me-1"></i>
                    <?php echo date('d F Y'); ?>
                </span>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Welcome Banner -->
        <div class="alert alert-info border-0">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle fa-2x"></i>
                </div>
                <div class="ms-3">
                    <h5 class="alert-heading mb-1">Halo, <?php echo htmlspecialchars($data['nama_lengkap']); ?>!</h5>
                    <p class="mb-0">Selamat datang di sistem Penerimaan Mahasiswa Baru. Lengkapi semua tahapan untuk menjadi mahasiswa.</p>
                </div>
            </div>
        </div>

        <!-- Progress Status -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-tasks text-primary me-2"></i> Status Pendaftaran</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="mb-3">
                                <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="fas fa-check text-white fa-2x"></i>
                                </div>
                            </div>
                            <h6>Registrasi</h6>
                            <span class="badge bg-success">Selesai</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="mb-3">
                                <div class="rounded-circle <?php echo $data['jurusan_id'] ? 'bg-success' : 'bg-secondary'; ?> d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="fas fa-university text-white fa-2x"></i>
                                </div>
                            </div>
                            <h6>Pilih Jurusan</h6>
                            <span class="badge bg-<?php echo $data['jurusan_id'] ? 'success' : 'secondary'; ?>">
                                <?php echo $data['jurusan_id'] ? 'Selesai' : 'Belum'; ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="mb-3">
                                <div class="rounded-circle <?php echo $data['nilai_ujian'] ? 'bg-success' : 'bg-secondary'; ?> d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="fas fa-file-alt text-white fa-2x"></i>
                                </div>
                            </div>
                            <h6>Ujian</h6>
                            <span class="badge bg-<?php echo $data['nilai_ujian'] ? 'success' : 'secondary'; ?>">
                                <?php echo $data['nilai_ujian'] ? 'Selesai' : 'Belum'; ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="mb-3">
                                <div class="rounded-circle <?php echo $data['status_daftar_ulang'] == 'approved' ? 'bg-success' : 'bg-secondary'; ?> d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="fas fa-graduation-cap text-white fa-2x"></i>
                                </div>
                            </div>
                            <h6>Daftar Ulang</h6>
                            <span class="badge bg-<?php echo $data['status_daftar_ulang'] == 'approved' ? 'success' : ($data['status_daftar_ulang'] ? 'warning' : 'secondary'); ?>">
                                <?php echo $data['status_daftar_ulang'] == 'approved' ? 'Selesai' : ($data['status_daftar_ulang'] ? 'Proses' : 'Belum'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Jurusan</h6>
                                <h3 class="mb-0"><?php echo $data['nama_jurusan'] ?? 'Belum dipilih'; ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-university fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Nilai Ujian</h6>
                                <h3 class="mb-0"><?php echo $data['nilai_ujian'] ? number_format($data['nilai_ujian'], 1) : '0'; ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Status</h6>
                                <h3 class="mb-0">
                                    <?php if($data['status_daftar_ulang'] == 'approved'): ?>
                                        Diterima
                                    <?php elseif($data['nilai_ujian']): ?>
                                        Lulus Ujian
                                    <?php else: ?>
                                        Proses
                                    <?php endif; ?>
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">NIM</h6>
                                <h3 class="mb-0"><?php echo $data['nim'] ?? '-'; ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-id-card fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-bolt text-primary me-2"></i> Akses Cepat</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="biodata.php" class="card card-hover text-decoration-none h-100">
                                    <div class="card-body text-center">
                                        <div class="text-primary mb-3">
                                            <i class="fas fa-user fa-3x"></i>
                                        </div>
                                        <h6 class="card-title">Biodata</h6>
                                        <p class="card-text text-muted small">Lengkapi/Edit data pribadi</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="ujian.php" class="card card-hover text-decoration-none h-100">
                                    <div class="card-body text-center">
                                        <div class="text-success mb-3">
                                            <i class="fas fa-file-alt fa-3x"></i>
                                        </div>
                                        <h6 class="card-title">Ujian</h6>
                                        <p class="card-text text-muted small">Ikuti ujian online</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="hasil_ujian.php" class="card card-hover text-decoration-none h-100">
                                    <div class="card-body text-center">
                                        <div class="text-info mb-3">
                                            <i class="fas fa-chart-bar fa-3x"></i>
                                        </div>
                                        <h6 class="card-title">Hasil Ujian</h6>
                                        <p class="card-text text-muted small">Lihat nilai ujian</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="daftar_ulang.php" class="card card-hover text-decoration-none h-100">
                                    <div class="card-body text-center">
                                        <div class="text-warning mb-3">
                                            <i class="fas fa-clipboard-check fa-3x"></i>
                                        </div>
                                        <h6 class="card-title">Daftar Ulang</h6>
                                        <p class="card-text text-muted small">Daftar ulang jika lulus</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Scripts -->
<script>
// Toggle sidebar di mobile
document.getElementById('mobileToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('show');
});

document.querySelector('.close-sidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.remove('show');
});

// Close sidebar saat klik di luar pada mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('mobileToggle');
    
    if (window.innerWidth <= 992 && 
        !sidebar.contains(event.target) && 
        !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('show');
    }
});
</script>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>