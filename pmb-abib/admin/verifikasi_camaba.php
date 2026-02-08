<?php
// Set page title
$page_title = "Verifikasi Camaba";

// Include header dengan path yang benar
require_once __DIR__ . '/includes/header.php';

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
$query = "SELECT b.*, u.username, u.email, j.nama_jurusan, j.kode_jurusan
          FROM biodata_camaba b 
          JOIN users u ON b.user_id = u.id 
          LEFT JOIN jurusan j ON b.jurusan_id = j.id
          WHERE b.status_verifikasi = 'pending'
          ORDER BY b.tanggal_daftar DESC";
$pending_camaba = mysqli_query($conn, $query);
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
                    <h4 class="mb-0"><i class="fas fa-user-check text-primary me-2"></i> Verifikasi Camaba</h4>
                    <small class="text-muted">Verifikasi data dan generate token ujian</small>
                </div>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock text-warning me-2"></i> Data Menunggu Verifikasi</h5>
            </div>
            
            <div class="card-body">
                <?php if(mysqli_num_rows($pending_camaba) > 0): ?>
                    <div class="row">
                        <?php while($row = mysqli_fetch_assoc($pending_camaba)): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><?php echo $row['nama_lengkap']; ?></h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><small class="text-muted">Username</small></td>
                                                <td><small><?php echo $row['username']; ?></small></td>
                                            </tr>
                                            <tr>
                                                <td><small class="text-muted">Email</small></td>
                                                <td><small><?php echo $row['email']; ?></small></td>
                                            </tr>
                                            <tr>
                                                <td><small class="text-muted">Jurusan</small></td>
                                                <td>
                                                    <?php if($row['kode_jurusan']): ?>
                                                        <span class="badge bg-info"><?php echo $row['kode_jurusan']; ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Belum pilih</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><small class="text-muted">Asal Sekolah</small></td>
                                                <td><small><?php echo $row['asal_sekolah']; ?></small></td>
                                            </tr>
                                            <tr>
                                                <td><small class="text-muted">Tanggal Daftar</small></td>
                                                <td><small><?php echo date('d/m/Y H:i', strtotime($row['tanggal_daftar'])); ?></small></td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="?verify=<?php echo $row['id']; ?>" 
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Verifikasi camaba ini?')">
                                            <i class="fas fa-check me-1"></i> Verifikasi
                                        </a>
                                        <a href="?reject=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Tolak camaba ini?')">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </a>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Biodata - <?php echo $row['nama_lengkap']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Nama Lengkap</strong></td>
                                                        <td><?php echo $row['nama_lengkap']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tempat Lahir</strong></td>
                                                        <td><?php echo $row['tempat_lahir']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Lahir</strong></td>
                                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_lahir'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jenis Kelamin</strong></td>
                                                        <td><?php echo $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alamat</strong></td>
                                                        <td><?php echo $row['alamat']; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>No HP</strong></td>
                                                        <td><?php echo $row['no_hp']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Asal Sekolah</strong></td>
                                                        <td><?php echo $row['asal_sekolah']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tahun Lulus</strong></td>
                                                        <td><?php echo $row['tahun_lulus']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Nama Orang Tua</strong></td>
                                                        <td><?php echo $row['nama_ortu']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Pekerjaan Orang Tua</strong></td>
                                                        <td><?php echo $row['pekerjaan_ortu']; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                        <a href="dashboard.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>