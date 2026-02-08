<?php
// Set page title
$page_title = "Kelola Mata Pelajaran";

// Include header
require_once __DIR__ . '/includes/header.php';

// Handle actions
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM mapel WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Mata pelajaran berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: mapel.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $kode = mysqli_real_escape_string($conn, $_POST['kode_mapel']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_mapel']);
        $bobot = mysqli_real_escape_string($conn, $_POST['bobot_nilai']);
        
        $query = "INSERT INTO mapel (kode_mapel, nama_mapel, bobot_nilai) 
                  VALUES ('$kode', '$nama', '$bobot')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Mata pelajaran berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
    elseif (isset($_POST['edit'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $kode = mysqli_real_escape_string($conn, $_POST['kode_mapel']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_mapel']);
        $bobot = mysqli_real_escape_string($conn, $_POST['bobot_nilai']);
        $aktif = isset($_POST['aktif']) ? '1' : '0';
        
        $query = "UPDATE mapel SET 
                  kode_mapel = '$kode',
                  nama_mapel = '$nama',
                  bobot_nilai = '$bobot',
                  aktif = '$aktif'
                  WHERE id = '$id'";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Mata pelajaran berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
    header("Location: mapel.php");
    exit();
}

// Get all mapel
$mapel_query = "SELECT * FROM mapel ORDER BY kode_mapel";
$mapel_result = mysqli_query($conn, $mapel_query);
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
                    <h4 class="mb-0"><i class="fas fa-book text-primary me-2"></i> Kelola Mata Pelajaran</h4>
                    <small class="text-muted">Kelola mata pelajaran untuk ujian</small>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Tambah Mapel
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
                                <th>Nama Mata Pelajaran</th>
                                <th>Bobot Nilai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if(mysqli_num_rows($mapel_result) > 0): 
                                while($row = mysqli_fetch_assoc($mapel_result)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge bg-info"><?php echo $row['kode_mapel']; ?></span></td>
                                <td><?php echo $row['nama_mapel']; ?></td>
                                <td>
                                    <span class="badge bg-warning">
                                        <?php echo $row['bobot_nilai']; ?>x
                                    </span>
                                </td>
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
                                           onclick="return confirm('Hapus mata pelajaran ini?')">
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
                                            <h5 class="modal-title">Edit Mata Pelajaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Kode Mapel</label>
                                                    <input type="text" class="form-control" name="kode_mapel" 
                                                           value="<?php echo $row['kode_mapel']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Mata Pelajaran</label>
                                                    <input type="text" class="form-control" name="nama_mapel" 
                                                           value="<?php echo $row['nama_mapel']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Bobot Nilai</label>
                                                    <select class="form-control" name="bobot_nilai" required>
                                                        <option value="1" <?php echo $row['bobot_nilai'] == 1 ? 'selected' : ''; ?>>1x (Rendah)</option>
                                                        <option value="2" <?php echo $row['bobot_nilai'] == 2 ? 'selected' : ''; ?>>2x (Sedang)</option>
                                                        <option value="3" <?php echo $row['bobot_nilai'] == 3 ? 'selected' : ''; ?>>3x (Tinggi)</option>
                                                        <option value="4" <?php echo $row['bobot_nilai'] == 4 ? 'selected' : ''; ?>>4x (Sangat Tinggi)</option>
                                                        <option value="5" <?php echo $row['bobot_nilai'] == 5 ? 'selected' : ''; ?>>5x (Kritis)</option>
                                                    </select>
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
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-book fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data mata pelajaran</p>
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
                <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Mapel</label>
                        <input type="text" class="form-control" name="kode_mapel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" name="nama_mapel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bobot Nilai</label>
                        <select class="form-control" name="bobot_nilai" required>
                            <option value="1">1x (Rendah)</option>
                            <option value="2" selected>2x (Sedang)</option>
                            <option value="3">3x (Tinggi)</option>
                            <option value="4">4x (Sangat Tinggi)</option>
                            <option value="5">5x (Kritis)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add" class="btn btn-primary">Tambah Mapel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>