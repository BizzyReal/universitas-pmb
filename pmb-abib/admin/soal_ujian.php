<?php
// Set page title
$page_title = "Kelola Soal Ujian";

// Include header
require_once __DIR__ . '/includes/header.php';

// Handle actions
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM soal_ujian WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Soal berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: soal_ujian.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $mapel_id = mysqli_real_escape_string($conn, $_POST['mapel_id']);
        $jurusan_id = mysqli_real_escape_string($conn, $_POST['jurusan_id']);
        $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
        $pilihan_a = mysqli_real_escape_string($conn, $_POST['pilihan_a']);
        $pilihan_b = mysqli_real_escape_string($conn, $_POST['pilihan_b']);
        $pilihan_c = mysqli_real_escape_string($conn, $_POST['pilihan_c']);
        $pilihan_d = mysqli_real_escape_string($conn, $_POST['pilihan_d']);
        $jawaban_benar = mysqli_real_escape_string($conn, $_POST['jawaban_benar']);
        $aktif = isset($_POST['aktif']) ? '1' : '0';
        
        $query = "INSERT INTO soal_ujian (mapel_id, jurusan_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar, aktif) 
                  VALUES ('$mapel_id', '$jurusan_id', '$pertanyaan', '$pilihan_a', '$pilihan_b', '$pilihan_c', '$pilihan_d', '$jawaban_benar', '$aktif')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Soal berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
    elseif (isset($_POST['edit'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $mapel_id = mysqli_real_escape_string($conn, $_POST['mapel_id']);
        $jurusan_id = mysqli_real_escape_string($conn, $_POST['jurusan_id']);
        $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
        $pilihan_a = mysqli_real_escape_string($conn, $_POST['pilihan_a']);
        $pilihan_b = mysqli_real_escape_string($conn, $_POST['pilihan_b']);
        $pilihan_c = mysqli_real_escape_string($conn, $_POST['pilihan_c']);
        $pilihan_d = mysqli_real_escape_string($conn, $_POST['pilihan_d']);
        $jawaban_benar = mysqli_real_escape_string($conn, $_POST['jawaban_benar']);
        $aktif = isset($_POST['aktif']) ? '1' : '0';
        
        $query = "UPDATE soal_ujian SET 
                  mapel_id = '$mapel_id',
                  jurusan_id = '$jurusan_id',
                  pertanyaan = '$pertanyaan',
                  pilihan_a = '$pilihan_a',
                  pilihan_b = '$pilihan_b',
                  pilihan_c = '$pilihan_c',
                  pilihan_d = '$pilihan_d',
                  jawaban_benar = '$jawaban_benar',
                  aktif = '$aktif'
                  WHERE id = '$id'";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Soal berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
    header("Location: soal_ujian.php");
    exit();
}

// Get all soal ujian with mapel and jurusan info
$soal_query = "SELECT s.*, m.nama_mapel, m.kode_mapel, j.nama_jurusan, j.kode_jurusan 
               FROM soal_ujian s 
               JOIN mapel m ON s.mapel_id = m.id 
               JOIN jurusan j ON s.jurusan_id = j.id 
               ORDER BY s.created_at DESC";
$soal_result = mysqli_query($conn, $soal_query);
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
                    <h4 class="mb-0"><i class="fas fa-question-circle text-primary me-2"></i> Kelola Soal Ujian</h4>
                    <small class="text-muted">Tambah, edit, dan hapus soal ujian</small>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Tambah Soal
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
                                <th>Pertanyaan</th>
                                <th>Mapel</th>
                                <th>Jurusan</th>
                                <th>Jawaban Benar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if(mysqli_num_rows($soal_result) > 0): 
                                while($row = mysqli_fetch_assoc($soal_result)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo substr($row['pertanyaan'], 0, 60); ?>...</div>
                                    <small class="text-muted">
                                        A: <?php echo substr($row['pilihan_a'], 0, 30); ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $row['kode_mapel']; ?></span>
                                    <div class="small"><?php echo $row['nama_mapel']; ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?php echo $row['kode_jurusan']; ?></span>
                                    <div class="small"><?php echo $row['nama_jurusan']; ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?php echo strtoupper($row['jawaban_benar']); ?></span>
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
                                           onclick="return confirm('Hapus soal ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Soal Ujian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Mapel</label>
                                                        <select class="form-control" name="mapel_id" required>
                                                            <?php 
                                                            $mapel = mysqli_query($conn, "SELECT * FROM mapel WHERE aktif='1'");
                                                            while($m = mysqli_fetch_assoc($mapel)):
                                                            ?>
                                                            <option value="<?php echo $m['id']; ?>" <?php echo $row['mapel_id'] == $m['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $m['nama_mapel']; ?> (<?php echo $m['kode_mapel']; ?>)
                                                            </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Jurusan</label>
                                                        <select class="form-control" name="jurusan_id" required>
                                                            <?php 
                                                            $jurusan = mysqli_query($conn, "SELECT * FROM jurusan WHERE aktif='1'");
                                                            while($j = mysqli_fetch_assoc($jurusan)):
                                                            ?>
                                                            <option value="<?php echo $j['id']; ?>" <?php echo $row['jurusan_id'] == $j['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $j['nama_jurusan']; ?> (<?php echo $j['kode_jurusan']; ?>)
                                                            </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Pertanyaan</label>
                                                    <textarea class="form-control" name="pertanyaan" rows="3" required><?php echo $row['pertanyaan']; ?></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Pilihan A</label>
                                                        <input type="text" class="form-control" name="pilihan_a" 
                                                               value="<?php echo $row['pilihan_a']; ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Pilihan B</label>
                                                        <input type="text" class="form-control" name="pilihan_b" 
                                                               value="<?php echo $row['pilihan_b']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Pilihan C</label>
                                                        <input type="text" class="form-control" name="pilihan_c" 
                                                               value="<?php echo $row['pilihan_c']; ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Pilihan D</label>
                                                        <input type="text" class="form-control" name="pilihan_d" 
                                                               value="<?php echo $row['pilihan_d']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jawaban Benar</label>
                                                    <select class="form-control" name="jawaban_benar" required>
                                                        <option value="a" <?php echo $row['jawaban_benar'] == 'a' ? 'selected' : ''; ?>>A</option>
                                                        <option value="b" <?php echo $row['jawaban_benar'] == 'b' ? 'selected' : ''; ?>>B</option>
                                                        <option value="c" <?php echo $row['jawaban_benar'] == 'c' ? 'selected' : ''; ?>>C</option>
                                                        <option value="d" <?php echo $row['jawaban_benar'] == 'd' ? 'selected' : ''; ?>>D</option>
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
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-question-circle fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data soal ujian</p>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Soal Ujian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mapel</label>
                            <select class="form-control" name="mapel_id" required>
                                <option value="">-- Pilih Mapel --</option>
                                <?php 
                                $mapel = mysqli_query($conn, "SELECT * FROM mapel WHERE aktif='1'");
                                while($m = mysqli_fetch_assoc($mapel)):
                                ?>
                                <option value="<?php echo $m['id']; ?>"><?php echo $m['nama_mapel']; ?> (<?php echo $m['kode_mapel']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan</label>
                            <select class="form-control" name="jurusan_id" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <?php 
                                $jurusan = mysqli_query($conn, "SELECT * FROM jurusan WHERE aktif='1'");
                                while($j = mysqli_fetch_assoc($jurusan)):
                                ?>
                                <option value="<?php echo $j['id']; ?>"><?php echo $j['nama_jurusan']; ?> (<?php echo $j['kode_jurusan']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <textarea class="form-control" name="pertanyaan" rows="3" required placeholder="Masukkan pertanyaan..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan A</label>
                            <input type="text" class="form-control" name="pilihan_a" required placeholder="Pilihan A">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan B</label>
                            <input type="text" class="form-control" name="pilihan_b" required placeholder="Pilihan B">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan C</label>
                            <input type="text" class="form-control" name="pilihan_c" required placeholder="Pilihan C">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan D</label>
                            <input type="text" class="form-control" name="pilihan_d" required placeholder="Pilihan D">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jawaban Benar</label>
                        <select class="form-control" name="jawaban_benar" required>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="aktif" id="aktif" checked>
                        <label class="form-check-label" for="aktif">
                            Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add" class="btn btn-primary">Tambah Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>