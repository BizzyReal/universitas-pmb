<?php
// Set page title
$page_title = "Kelola Jurusan";

// Include header dengan path yang benar
require_once __DIR__ . '/includes/header.php';

// Handle actions
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM biodata_camaba WHERE jurusan_id = '$id'");
    $result = mysqli_fetch_assoc($check);
    
    if ($result['total'] == 0) {
        mysqli_query($conn, "DELETE FROM jurusan WHERE id = '$id'");
        $_SESSION['success'] = "Jurusan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Jurusan tidak dapat dihapus karena sudah digunakan!";
    }
    header("Location: jurusan.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $kode = mysqli_real_escape_string($conn, $_POST['kode_jurusan']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_jurusan']);
        $kuota = mysqli_real_escape_string($conn, $_POST['kuota']);
        $jumlah_soal = mysqli_real_escape_string($conn, $_POST['jumlah_soal']);
        $waktu_ujian = mysqli_real_escape_string($conn, $_POST['waktu_ujian']);
        $passing_grade = mysqli_real_escape_string($conn, $_POST['passing_grade']);
        
        $query = "INSERT INTO jurusan (kode_jurusan, nama_jurusan, kuota, jumlah_soal, waktu_ujian, passing_grade) 
                  VALUES ('$kode', '$nama', '$kuota', '$jumlah_soal', '$waktu_ujian', '$passing_grade')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Jurusan berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
    elseif (isset($_POST['edit'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $kode = mysqli_real_escape_string($conn, $_POST['kode_jurusan']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_jurusan']);
        $kuota = mysqli_real_escape_string($conn, $_POST['kuota']);
        $jumlah_soal = mysqli_real_escape_string($conn, $_POST['jumlah_soal']);
        $waktu_ujian = mysqli_real_escape_string($conn, $_POST['waktu_ujian']);
        $passing_grade = mysqli_real_escape_string($conn, $_POST['passing_grade']);
        $aktif = isset($_POST['aktif']) ? '1' : '0';
        
        $query = "UPDATE jurusan SET 
                  kode_jurusan = '$kode',
                  nama_jurusan = '$nama',
                  kuota = '$kuota',
                  jumlah_soal = '$jumlah_soal',
                  waktu_ujian = '$waktu_ujian',
                  passing_grade = '$passing_grade',
                  aktif = '$aktif'
                  WHERE id = '$id'";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Jurusan berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
    header("Location: jurusan.php");
    exit();
}

// Get all jurusan
$jurusan_query = "SELECT * FROM jurusan ORDER BY kode_jurusan";
$jurusan_result = mysqli_query($conn, $jurusan_query);
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
                    <h4 class="mb-0"><i class="fas fa-graduation-cap text-primary me-2"></i> Kelola Jurusan</h4>
                    <small class="text-muted">Kelola data jurusan dan pengaturan ujian</small>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Tambah Jurusan
                </button>
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
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Jurusan</th>
                                <th>Kuota</th>
                                <th>Jumlah Soal</th>
                                <th>Waktu Ujian</th>
                                <th>Passing Grade</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if(mysqli_num_rows($jurusan_result) > 0): 
                                while($row = mysqli_fetch_assoc($jurusan_result)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge bg-primary"><?php echo $row['kode_jurusan']; ?></span></td>
                                <td><?php echo $row['nama_jurusan']; ?></td>
                                <td><?php echo $row['kuota']; ?> kursi</td>
                                <td><?php echo $row['jumlah_soal']; ?> soal</td>
                                <td><?php echo $row['waktu_ujian']; ?> menit</td>
                                <td><?php echo $row['passing_grade']; ?>%</td>
                                <td>
                                    <span class="badge bg-<?php echo $row['aktif'] == '1' ? 'success' : 'danger'; ?>">
                                        <?php echo $row['aktif'] == '1' ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-warning" data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Hapus jurusan ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Jurusan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Kode Jurusan</label>
                                                    <input type="text" class="form-control" name="kode_jurusan" 
                                                           value="<?php echo $row['kode_jurusan']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Jurusan</label>
                                                    <input type="text" class="form-control" name="nama_jurusan" 
                                                           value="<?php echo $row['nama_jurusan']; ?>" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kuota</label>
                                                        <input type="number" class="form-control" name="kuota" 
                                                               value="<?php echo $row['kuota']; ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Jumlah Soal</label>
                                                        <input type="number" class="form-control" name="jumlah_soal" 
                                                               value="<?php echo $row['jumlah_soal']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Waktu Ujian (menit)</label>
                                                        <input type="number" class="form-control" name="waktu_ujian" 
                                                               value="<?php echo $row['waktu_ujian']; ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Passing Grade (%)</label>
                                                        <input type="number" class="form-control" name="passing_grade" 
                                                               value="<?php echo $row['passing_grade']; ?>" required min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="aktif" 
                                                           id="aktif<?php echo $row['id']; ?>" 
                                                           <?php echo $row['aktif'] == '1' ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="aktif<?php echo $row['id']; ?>">
                                                        Aktif
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                endwhile;
                            else: 
                            ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-graduation-cap fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data jurusan</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jurusan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" class="form-control" name="kode_jurusan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" class="form-control" name="nama_jurusan" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kuota</label>
                            <input type="number" class="form-control" name="kuota" value="50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Soal</label>
                            <input type="number" class="form-control" name="jumlah_soal" value="20" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Ujian (menit)</label>
                            <input type="number" class="form-control" name="waktu_ujian" value="120" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passing Grade (%)</label>
                            <input type="number" class="form-control" name="passing_grade" value="70" required min="0" max="100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add" class="btn btn-primary">Tambah Jurusan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>