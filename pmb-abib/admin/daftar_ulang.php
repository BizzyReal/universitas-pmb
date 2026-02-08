<?php
// Set page title
$page_title = "Daftar Ulang Mahasiswa";

// Include header
require_once __DIR__ . '/includes/header.php';

// Handle actions
if (isset($_POST['approve'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $status = 'approved';
    
    // Generate NIM otomatis jika kosong
    if (empty($nim)) {
        $tahun = date('y');
        $jurusan_kode = mysqli_real_escape_string($conn, $_POST['jurusan_kode']);
        $counter_query = "SELECT COUNT(*) as total FROM daftar_ulang WHERE YEAR(tanggal_daftar_ulang) = YEAR(NOW())";
        $counter_result = mysqli_query($conn, $counter_query);
        $counter = mysqli_fetch_assoc($counter_result)['total'] + 1;
        $nim = $tahun . $jurusan_kode . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }
    
    $query = "UPDATE daftar_ulang SET 
              nim = '$nim',
              status = '$status',
              tanggal_approved = NOW()
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Daftar ulang berhasil disetujui! NIM: $nim";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: daftar_ulang.php");
    exit();
}

if (isset($_POST['reject'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    $status = 'rejected';
    
    $query = "UPDATE daftar_ulang SET 
              status = '$status'
              WHERE id = '$id'";
    // Note: Tabel tidak memiliki kolom 'catatan', jadi hapus bagian catatan
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Daftar ulang ditolak!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: daftar_ulang.php");
    exit();
}

if (isset($_POST['add_manual'])) {
    $biodata_id = mysqli_real_escape_string($conn, $_POST['biodata_id']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    
    $query = "INSERT INTO daftar_ulang (biodata_id, nim, status, tanggal_daftar_ulang, tanggal_approved) 
              VALUES ('$biodata_id', '$nim', 'approved', NOW(), NOW())";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Mahasiswa berhasil ditambahkan secara manual! NIM: $nim";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: daftar_ulang.php");
    exit();
}

// Get all daftar ulang data
// PERBAIKAN UTAMA: Tambahkan alias untuk tanggal_daftar_ulang sebagai tanggal
$query = "SELECT du.*, 
          b.nama_lengkap, b.asal_sekolah, b.no_hp,
          j.nama_jurusan, j.kode_jurusan,
          u.username, u.email,
          h.nilai as nilai_ujian,
          du.tanggal_daftar_ulang as tanggal  -- TAMBAHKAN INI
          FROM daftar_ulang du
          JOIN biodata_camaba b ON du.biodata_id = b.id
          JOIN jurusan j ON b.jurusan_id = j.id
          JOIN users u ON b.user_id = u.id
          LEFT JOIN hasil_ujian h ON b.id = h.biodata_id
          ORDER BY du.tanggal_daftar_ulang DESC";  // Gunakan nama kolom yang benar

$daftar_ulang_result = mysqli_query($conn, $query);

// Debug: Tampilkan error jika ada
if (!$daftar_ulang_result) {
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
                    <h4 class="mb-0"><i class="fas fa-user-graduate text-primary me-2"></i> Daftar Ulang Mahasiswa</h4>
                    <small class="text-muted">Kelola pendaftaran ulang mahasiswa baru</small>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addManualModal">
                    <i class="fas fa-plus me-2"></i> Tambah Manual
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
                        <h6 class="card-title">Total Pendaftar</h6>
                        <?php 
                        $total_query = "SELECT COUNT(*) as total FROM daftar_ulang";
                        $total_result = mysqli_query($conn, $total_query);
                        $total = mysqli_fetch_assoc($total_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $total; ?></h2>
                        <small>Mahasiswa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Disetujui</h6>
                        <?php 
                        $approved_query = "SELECT COUNT(*) as total FROM daftar_ulang WHERE status = 'approved'";
                        $approved_result = mysqli_query($conn, $approved_query);
                        $approved = mysqli_fetch_assoc($approved_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $approved; ?></h2>
                        <small>Mahasiswa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Menunggu</h6>
                        <?php 
                        $pending_query = "SELECT COUNT(*) as total FROM daftar_ulang WHERE status = 'pending'";
                        $pending_result = mysqli_query($conn, $pending_query);
                        $pending = mysqli_fetch_assoc($pending_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $pending; ?></h2>
                        <small>Mahasiswa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Ditolak</h6>
                        <?php 
                        $rejected_query = "SELECT COUNT(*) as total FROM daftar_ulang WHERE status = 'rejected'";
                        $rejected_result = mysqli_query($conn, $rejected_query);
                        $rejected = mysqli_fetch_assoc($rejected_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $rejected; ?></h2>
                        <small>Mahasiswa</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Ulang Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-list text-primary me-2"></i> Daftar Pendaftaran Ulang</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jurusan</th>
                                <th>Nilai Ujian</th>
                                <th>Tanggal Daftar</th>
                                <th>NIM</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if(mysqli_num_rows($daftar_ulang_result) > 0): 
                                while($row = mysqli_fetch_assoc($daftar_ulang_result)): 
                                    $status = $row['status'];
                                    $status_badge = '';
                                    switch($status) {
                                        case 'approved': $status_badge = 'success'; break;
                                        case 'pending': $status_badge = 'warning'; break;
                                        case 'rejected': $status_badge = 'danger'; break;
                                        default: $status_badge = 'secondary';
                                    }
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['nama_lengkap']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['asal_sekolah']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $row['kode_jurusan']; ?></span>
                                    <div class="small"><?php echo $row['nama_jurusan']; ?></div>
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
                                    <?php echo date('d/m/Y', strtotime($row['tanggal'])); ?>
                                    <div class="small text-muted"><?php echo date('H:i', strtotime($row['tanggal'])); ?></div>
                                </td>
                                <td>
                                    <?php if ($row['nim']): ?>
                                        <span class="badge bg-primary"><?php echo $row['nim']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $status_badge; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info" data-bs-toggle="modal" 
                                                data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($status == 'pending'): ?>
                                            <button class="btn btn-success" data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger" data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Pendaftaran Ulang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Data Mahasiswa</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td width="40%">Nama Lengkap</td>
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
                                                            <td>Asal Sekolah</td>
                                                            <td><?php echo htmlspecialchars($row['asal_sekolah']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>No HP</td>
                                                            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Data Akademik</h6>
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
                                                            <td>NIM</td>
                                                            <td>
                                                                <?php if ($row['nim']): ?>
                                                                    <span class="badge bg-primary"><?php echo $row['nim']; ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">Belum ada</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <td>
                                                                <span class="badge bg-<?php echo $status_badge; ?>">
                                                                    <?php echo ucfirst($status); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggal Daftar</td>
                                                            <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                                        </tr>
                                                        <?php if ($row['tanggal_approved']): ?>
                                                        <tr>
                                                            <td>Tanggal Disetujui</td>
                                                            <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_approved'])); ?></td>
                                                        </tr>
                                                        <?php endif; ?>
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

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Setujui Daftar Ulang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="jurusan_kode" value="<?php echo $row['kode_jurusan']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Mahasiswa</label>
                                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['nama_lengkap']); ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jurusan</label>
                                                    <input type="text" class="form-control" value="<?php echo $row['nama_jurusan']; ?> (<?php echo $row['kode_jurusan']; ?>)" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">NIM</label>
                                                    <input type="text" class="form-control" name="nim" 
                                                           placeholder="Kosongkan untuk generate otomatis"
                                                           value="<?php echo $row['nim']; ?>">
                                                    <small class="text-muted">Format: Tahun + Kode Jurusan + Nomor Urut (contoh: 24RPL0001)</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="approve" class="btn btn-success">Setujui & Beri NIM</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Daftar Ulang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Mahasiswa</label>
                                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['nama_lengkap']); ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Penolakan</label>
                                                    <textarea class="form-control" name="alasan" rows="3" 
                                                              placeholder="Masukkan alasan penolakan..."></textarea>
                                                    <small class="text-muted">Catatan: Alasan tidak akan disimpan di database karena tabel tidak memiliki kolom catatan</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="reject" class="btn btn-danger">Tolak Pendaftaran</button>
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
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data daftar ulang</p>
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

<!-- Add Manual Modal -->
<div class="modal fade" id="addManualModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Mahasiswa</label>
                        <select class="form-control" name="biodata_id" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php 
                            // Get camaba who passed the exam but haven't registered yet
                            // Modifikasi query: hapus kondisi passing_grade karena mungkin tidak ada di tabel jurusan
                            $camaba_query = "SELECT b.*, u.username, j.nama_jurusan, h.nilai 
                                            FROM biodata_camaba b
                                            JOIN users u ON b.user_id = u.id
                                            JOIN jurusan j ON b.jurusan_id = j.id
                                            LEFT JOIN hasil_ujian h ON b.id = h.biodata_id
                                            LEFT JOIN daftar_ulang du ON b.id = du.biodata_id
                                            WHERE du.id IS NULL 
                                            AND (h.nilai >= 70 OR h.nilai IS NULL)  -- Default nilai kelulusan 70
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
                                echo '<option value="">Tidak ada data mahasiswa yang memenuhi syarat</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" name="nim" required 
                               placeholder="Contoh: 24RPL0001">
                        <small class="text-muted">Pastikan NIM unik dan sesuai format</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_manual" class="btn btn-primary">Tambah Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>