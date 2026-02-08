<?php
// Set page title
$page_title = "Hasil Ujian";

// Include header
require_once __DIR__ . '/includes/header.php';

// Get filter parameters
$jurusan_filter = isset($_GET['jurusan_id']) ? mysqli_real_escape_string($conn, $_GET['jurusan_id']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Build query with filters
$query = "SELECT h.*, b.nama_lengkap, b.asal_sekolah, j.nama_jurusan, j.kode_jurusan, j.passing_grade
          FROM hasil_ujian h
          JOIN biodata_camaba b ON h.biodata_id = b.id
          JOIN jurusan j ON b.jurusan_id = j.id
          WHERE 1=1";

if ($jurusan_filter != '') {
    $query .= " AND b.jurusan_id = '$jurusan_filter'";
}
if ($status_filter != '') {
    if ($status_filter == 'lulus') {
        $query .= " AND h.nilai >= j.passing_grade";
    } elseif ($status_filter == 'tidak_lulus') {
        $query .= " AND h.nilai < j.passing_grade";
    }
}

// PERBAIKAN: ganti ORDER BY h.tanggal_ujian dengan ORDER BY h.waktu_mu
$query .= " ORDER BY h.waktu_mulai DESC";
$hasil_result = mysqli_query($conn, $query);

// Cek error query
if (!$hasil_result) {
    die("Query error: " . mysqli_error($conn));
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
                    <h4 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i> Hasil Ujian</h4>
                    <small class="text-muted">Lihat hasil ujian semua peserta</small>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-filter text-primary me-2"></i> Filter Hasil Ujian</h5>
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Jurusan</label>
                        <select class="form-control" name="jurusan_id">
                            <option value="">Semua Jurusan</option>
                            <?php 
                            $jurusan = mysqli_query($conn, "SELECT * FROM jurusan WHERE aktif='1'");
                            while($j = mysqli_fetch_assoc($jurusan)):
                            ?>
                            <option value="<?php echo $j['id']; ?>" <?php echo $jurusan_filter == $j['id'] ? 'selected' : ''; ?>>
                                <?php echo $j['nama_jurusan']; ?> (<?php echo $j['kode_jurusan']; ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Status Kelulusan</label>
                        <select class="form-control" name="status">
                            <option value="">Semua Status</option>
                            <option value="lulus" <?php echo $status_filter == 'lulus' ? 'selected' : ''; ?>>Lulus</option>
                            <option value="tidak_lulus" <?php echo $status_filter == 'tidak_lulus' ? 'selected' : ''; ?>>Tidak Lulus</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Peserta</h6>
                        <?php 
                        $total_query = "SELECT COUNT(*) as total FROM hasil_ujian";
                        $total_result = mysqli_query($conn, $total_query);
                        $total = mysqli_fetch_assoc($total_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $total; ?></h2>
                        <small>Orang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Lulus</h6>
                        <?php 
                        $lulus_query = "SELECT COUNT(*) as total FROM hasil_ujian h 
                                       JOIN biodata_camaba b ON h.biodata_id = b.id 
                                       JOIN jurusan j ON b.jurusan_id = j.id 
                                       WHERE h.nilai >= j.passing_grade";
                        $lulus_result = mysqli_query($conn, $lulus_query);
                        $lulus = mysqli_fetch_assoc($lulus_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $lulus; ?></h2>
                        <small>Orang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Tidak Lulus</h6>
                        <?php 
                        $tidak_lulus_query = "SELECT COUNT(*) as total FROM hasil_ujian h 
                                             JOIN biodata_camaba b ON h.biodata_id = b.id 
                                             JOIN jurusan j ON b.jurusan_id = j.id 
                                             WHERE h.nilai < j.passing_grade";
                        $tidak_lulus_result = mysqli_query($conn, $tidak_lulus_query);
                        $tidak_lulus = mysqli_fetch_assoc($tidak_lulus_result)['total'];
                        ?>
                        <h2 class="mb-0"><?php echo $tidak_lulus; ?></h2>
                        <small>Orang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Rata-rata Nilai</h6>
                        <?php 
                        $avg_query = "SELECT AVG(nilai) as rata FROM hasil_ujian";
                        $avg_result = mysqli_query($conn, $avg_query);
                        $rata = mysqli_fetch_assoc($avg_result)['rata'];
                        $rata = $rata ? number_format($rata, 1) : '0.0';
                        ?>
                        <h2 class="mb-0"><?php echo $rata; ?></h2>
                        <small>Dari 100</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-list text-primary me-2"></i> Daftar Hasil Ujian</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peserta</th>
                                <th>Asal Sekolah</th>
                                <th>Jurusan</th>
                                <th>Jumlah Soal</th>
                                <th>Benar</th>
                                <th>Nilai</th>
                                <th>Status</th>
                                <th>Waktu Ujian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if(mysqli_num_rows($hasil_result) > 0): 
                                while($row = mysqli_fetch_assoc($hasil_result)): 
                                    // Periksa kolom yang tersedia
                                    $jumlah_soal = isset($row['jumlah_s']) ? $row['jumlah_s'] : (isset($row['jumlah_soal']) ? $row['jumlah_soal'] : 0);
                                    $jawaban_benar = isset($row['jawaban_be']) ? $row['jawaban_be'] : (isset($row['jawaban_benar']) ? $row['jawaban_benar'] : 0);
                                    $nilai = isset($row['nilai']) ? $row['nilai'] : 0;
                                    $waktu_mulai = isset($row['waktu_mu']) ? $row['waktu_mu'] : (isset($row['waktu_mulai']) ? $row['waktu_mulai'] : '');
                                    $token = isset($row['token']) ? $row['token'] : '';
                                    
                                    $status = $nilai >= $row['passing_grade'] ? 'Lulus' : 'Tidak Lulus';
                                    $status_class = $status == 'Lulus' ? 'success' : 'danger';
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['nama_lengkap']); ?></div>
                                    <small class="text-muted">Token: <?php echo htmlspecialchars($token); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['asal_sekolah']); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo $row['kode_jurusan']; ?></span>
                                    <div class="small"><?php echo $row['nama_jurusan']; ?></div>
                                </td>
                                <td><?php echo $jumlah_soal; ?></td>
                                <td><?php echo $jawaban_benar; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $nilai >= 70 ? 'success' : ($nilai >= 50 ? 'warning' : 'danger'); ?>">
                                        <?php echo number_format($nilai, 1); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $status_class; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($waktu_mulai): ?>
                                        <?php echo date('d/m/Y', strtotime($waktu_mulai)); ?>
                                        <div class="small text-muted"><?php echo date('H:i', strtotime($waktu_mulai)); ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Hasil Ujian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6>Data Peserta</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td width="40%">Nama Lengkap</td>
                                                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Asal Sekolah</td>
                                                            <td><?php echo htmlspecialchars($row['asal_sekolah']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jurusan</td>
                                                            <td><?php echo $row['nama_jurusan']; ?> (<?php echo $row['kode_jurusan']; ?>)</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Hasil Ujian</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td width="40%">Token Ujian</td>
                                                            <td><?php echo htmlspecialchars($token); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jumlah Soal</td>
                                                            <td><?php echo $jumlah_soal; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jawaban Benar</td>
                                                            <td><?php echo $jawaban_benar; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nilai</td>
                                                            <td>
                                                                <span class="badge bg-<?php echo $status_class; ?>">
                                                                    <?php echo number_format($nilai, 1); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <td>
                                                                <span class="badge bg-<?php echo $status_class; ?>">
                                                                    <?php echo $status; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Passing Grade</td>
                                                            <td><?php echo $row['passing_grade']; ?>%</td>
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
                            <?php 
                                endwhile;
                            else: 
                            ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-chart-bar fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data hasil ujian</p>
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

<?php
// Include footer
require_once __DIR__ . '/includes/footer.php';
?>