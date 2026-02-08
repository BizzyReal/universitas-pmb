<?php
// Set page title
$page_title = "Data Mahasiswa";

// Include header
require_once __DIR__ . '/includes/header.php';

// Handle actions
if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $query = "DELETE FROM daftar_ulang WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data mahasiswa berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: data_mahasiswa.php");
    exit();
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $status_akademik = mysqli_real_escape_string($conn, $_POST['status_akademik']);
    
    $query = "UPDATE daftar_ulang SET 
              nim = '$nim',
              status = '$status_akademik'
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data mahasiswa berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: data_mahasiswa.php");
    exit();
}

if (isset($_POST['add_mahasiswa'])) {
    $biodata_id = mysqli_real_escape_string($conn, $_POST['biodata_id']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $status = 'approved';
    
    $query = "INSERT INTO daftar_ulang (biodata_id, nim, status, tanggal_daftar_ulang, tanggal_approved) 
              VALUES ('$biodata_id', '$nim', '$status', NOW(), NOW())";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Mahasiswa berhasil ditambahkan! NIM: $nim";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: data_mahasiswa.php");
    exit();
}

// Get all mahasiswa data (yang sudah approved)
// PERBAIKAN UTAMA: Hapus kolom 'tanggal' atau ganti dengan 'tanggal_daftar_ulang'
$query = "SELECT du.*, 
          b.nama_lengkap, b.asal_sekolah, b.tempat_lahir, b.tanggal_lahir, b.jenis_kelamin, b.no_hp, b.alamat,
          j.nama_jurusan, j.kode_jurusan,
          u.username, u.email,
          h.nilai as nilai_ujian,
          du.tanggal_daftar_ulang as tanggal_daftar  -- TAMBAHKAN ALIAS UNTUK TANGGAL
          FROM daftar_ulang du
          JOIN biodata_camaba b ON du.biodata_id = b.id
          JOIN jurusan j ON b.jurusan_id = j.id
          JOIN users u ON b.user_id = u.id
          LEFT JOIN hasil_ujian h ON b.id = h.biodata_id
          WHERE du.status = 'approved'  -- Hanya tampilkan yang sudah disetujui
          ORDER BY du.nim ASC";

$mahasiswa_result = mysqli_query($conn, $query);

// Debug: Tampilkan error jika ada
if (!$mahasiswa_result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
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
                    <h4 class="mb-0"><i class="fas fa-users text-primary me-2"></i> Data Mahasiswa</h4>
                    <small class="text-muted">Kelola data mahasiswa yang telah terdaftar</small>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
                    <i class="fas fa-plus me-2"></i> Tambah Mahasiswa
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

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Mahasiswa</h6>
                        <?php 
                        $total_query = "SELECT COUNT(*) as total FROM daftar_ulang WHERE status = 'approved'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total = mysqli_fetch_assoc($total_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $total; ?></h2>
                        <small>Mahasiswa Aktif</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Jurusan TI</h6>
                        <?php 
                        $ti_query = "SELECT COUNT(*) as total 
                                    FROM daftar_ulang du 
                                    JOIN biodata_camaba b ON du.biodata_id = b.id 
                                    JOIN jurusan j ON b.jurusan_id = j.id 
                                    WHERE du.status = 'approved' AND j.kode_jurusan LIKE '%TI%'";
                        $ti_result = mysqli_query($conn, $ti_query);
                        $ti = mysqli_fetch_assoc($ti_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $ti; ?></h2>
                        <small>Teknik Informatika</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Jurusan SI</h6>
                        <?php 
                        $si_query = "SELECT COUNT(*) as total 
                                    FROM daftar_ulang du 
                                    JOIN biodata_camaba b ON du.biodata_id = b.id 
                                    JOIN jurusan j ON b.jurusan_id = j.id 
                                    WHERE du.status = 'approved' AND j.kode_jurusan LIKE '%SI%'";
                        $si_result = mysqli_query($conn, $si_query);
                        $si = mysqli_fetch_assoc($si_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $si; ?></h2>
                        <small>Sistem Informasi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Rata-rata Nilai</h6>
                        <?php 
                        $avg_query = "SELECT AVG(h.nilai) as rata_rata 
                                     FROM daftar_ulang du 
                                     JOIN biodata_camaba b ON du.biodata_id = b.id 
                                     JOIN hasil_ujian h ON b.id = h.biodata_id 
                                     WHERE du.status = 'approved'";
                        $avg_result = mysqli_query($conn, $avg_query);
                        $avg = mysqli_fetch_assoc($avg_result)['rata_rata'];
                        ?>
                        <h2 class="mb-0"><?php echo $avg ? number_format($avg, 1) : '0'; ?></h2>
                        <small>Nilai Ujian</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Mahasiswa Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-list text-primary me-2"></i> Daftar Mahasiswa Terdaftar</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jurusan</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Nilai Ujian</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if(mysqli_num_rows($mahasiswa_result) > 0): 
                                while($row = mysqli_fetch_assoc($mahasiswa_result)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo $row['nim']; ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['nama_lengkap']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $row['kode_jurusan']; ?></span>
                                    <div class="small"><?php echo $row['nama_jurusan']; ?></div>
                                </td>
                                <td>
                                    <?php if ($row['jenis_kelamin'] == 'L'): ?>
                                        <span class="badge bg-primary">Laki-laki</span>
                                    <?php else: ?>
                                        <span class="badge bg-pink">Perempuan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($row['tanggal_lahir'])); ?>
                                </td>
                                <td>
                                    <?php if ($row['nilai_ujian']): ?>
                                        <span class="badge bg-<?php echo $row['nilai_ujian'] >= 70 ? 'success' : 'warning'; ?>">
                                            <?php echo number_format($row['nilai_ujian'], 1); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($row['tanggal_daftar'])); ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info" data-bs-toggle="modal" 
                                                data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning" data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Mahasiswa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Data Pribadi</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td width="40%">NIM</td>
                                                            <td><span class="badge bg-primary"><?php echo $row['nim']; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nama Lengkap</td>
                                                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Username</td>
                                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jenis Kelamin</td>
                                                            <td><?php echo $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tempat/Tgl Lahir</td>
                                                            <td><?php echo htmlspecialchars($row['tempat_lahir']); ?>, <?php echo date('d/m/Y', strtotime($row['tanggal_lahir'])); ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Data Akademik & Kontak</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td width="40%">Jurusan</td>
                                                            <td><?php echo $row['nama_jurusan']; ?> (<?php echo $row['kode_jurusan']; ?>)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nilai Ujian</td>
                                                            <td><?php echo $row['nilai_ujian'] ? number_format($row['nilai_ujian'], 1) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Asal Sekolah</td>
                                                            <td><?php echo htmlspecialchars($row['asal_sekolah']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>No HP</td>
                                                            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Alamat</td>
                                                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggal Daftar</td>
                                                            <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_daftar'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggal Disetujui</td>
                                                            <td><?php echo $row['tanggal_approved'] ? date('d/m/Y H:i', strtotime($row['tanggal_approved'])) : '-'; ?></td>
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

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Mahasiswa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">NIM</label>
                                                    <input type="text" class="form-control" name="nim" 
                                                           value="<?php echo $row['nim']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Mahasiswa</label>
                                                    <input type="text" class="form-control" 
                                                           value="<?php echo htmlspecialchars($row['nama_lengkap']); ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jurusan</label>
                                                    <input type="text" class="form-control" 
                                                           value="<?php echo $row['nama_jurusan']; ?> (<?php echo $row['kode_jurusan']; ?>)" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status Akademik</label>
                                                    <select class="form-control" name="status_akademik">
                                                        <option value="approved" <?php echo $row['status'] == 'approved' ? 'selected' : ''; ?>>Aktif</option>
                                                        <option value="nonaktif" <?php echo $row['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                                        <option value="cuti" <?php echo $row['status'] == 'cuti' ? 'selected' : ''; ?>>Cuti</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Data Mahasiswa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <p>Apakah Anda yakin ingin menghapus data mahasiswa ini?</p>
                                                <div class="alert alert-warning">
                                                    <strong>NIM:</strong> <?php echo $row['nim']; ?><br>
                                                    <strong>Nama:</strong> <?php echo htmlspecialchars($row['nama_lengkap']); ?>
                                                </div>
                                                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="delete" class="btn btn-danger">Hapus Data</button>
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
                                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data mahasiswa</p>
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

<!-- Add Mahasiswa Modal -->
<div class="modal fade" id="addMahasiswaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Calon Mahasiswa</label>
                        <select class="form-control" name="biodata_id" required>
                            <option value="">-- Pilih Calon Mahasiswa --</option>
                            <?php 
                            // Get camaba who have passed but haven't been registered
                            $camaba_query = "SELECT b.*, u.username, j.nama_jurusan, h.nilai 
                                            FROM biodata_camaba b
                                            JOIN users u ON b.user_id = u.id
                                            JOIN jurusan j ON b.jurusan_id = j.id
                                            LEFT JOIN hasil_ujian h ON b.id = h.biodata_id
                                            LEFT JOIN daftar_ulang du ON b.id = du.biodata_id
                                            WHERE du.id IS NULL 
                                            AND (h.nilai >= 70 OR h.nilai IS NULL)
                                            ORDER BY b.nama_lengkap";
                            $camaba_result = mysqli_query($conn, $camaba_query);
                            if ($camaba_result && mysqli_num_rows($camaba_result) > 0) {
                                while($camaba = mysqli_fetch_assoc($camaba_result)):
                            ?>
                            <option value="<?php echo $camaba['id']; ?>">
                                <?php echo htmlspecialchars($camaba['nama_lengkap']); ?> 
                                - <?php echo htmlspecialchars($camaba['nama_jurusan']); ?>
                                (Nilai: <?php echo $camaba['nilai'] ? number_format($camaba['nilai'], 1) : 'Belum ujian'; ?>)
                            </option>
                            <?php 
                                endwhile;
                            } else {
                                echo '<option value="">Tidak ada calon mahasiswa yang memenuhi syarat</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" name="nim" required 
                               placeholder="Contoh: 24RPL0001">
                        <small class="text-muted">Format: Tahun + Kode Jurusan + Nomor Urut</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_mahasiswa" class="btn btn-primary">Tambah Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>