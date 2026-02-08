<?php
// Set page title
$page_title = "Data Calon Maba";

// Include header
require_once __DIR__ . '/includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM biodata_camaba WHERE id = '$id'";
    mysqli_query($conn, $query);
    $_SESSION['success'] = "Data berhasil dihapus!";
    header("Location: data_camaba.php");
    exit();
}

// Get all camaba data
$query = "SELECT b.*, u.username, u.email, j.nama_jurusan, j.kode_jurusan
          FROM biodata_camaba b 
          JOIN users u ON b.user_id = u.id 
          LEFT JOIN jurusan j ON b.jurusan_id = j.id
          ORDER BY b.tanggal_daftar DESC";
$camaba = mysqli_query($conn, $query);
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
                    <h4 class="mb-0"><i class="fas fa-users text-primary me-2"></i> Data Calon Maba</h4>
                    <small class="text-muted">Kelola semua data calon mahasiswa baru</small>
                </div>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
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
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Calon Mahasiswa Baru</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Tambah Data
                </button>
            </div>
            
            <div class="card-body">
                <?php if(mysqli_num_rows($camaba) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Jurusan</th>
                                    <th>Asal Sekolah</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while($row = mysqli_fetch_assoc($camaba)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <?php if($row['kode_jurusan']): ?>
                                            <span class="badge bg-info"><?php echo $row['kode_jurusan']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum pilih</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['asal_sekolah']); ?></td>
                                    <td>
                                        <?php 
                                        $status = $row['status_verifikasi'];
                                        $badge_class = '';
                                        switch($status) {
                                            case 'verified': $badge_class = 'success'; break;
                                            case 'pending': $badge_class = 'warning'; break;
                                            case 'rejected': $badge_class = 'danger'; break;
                                            default: $badge_class = 'secondary';
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $badge_class; ?>">
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_daftar'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="verifikasi_camaba.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('Yakin hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>Belum ada data calon mahasiswa</h5>
                        <p class="text-muted">Mulai dengan menambahkan data camaba</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Camaba</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process_add_camaba.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asal Sekolah *</label>
                        <input type="text" class="form-control" name="asal_sekolah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select class="form-control" name="jurusan_id">
                            <option value="">-- Pilih Jurusan --</option>
                            <?php 
                            $jurusan = mysqli_query($conn, "SELECT * FROM jurusan WHERE aktif='1'");
                            while($j = mysqli_fetch_assoc($jurusan)) {
                                echo "<option value='{$j['id']}'>{$j['nama_jurusan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>