<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'camaba') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data hasil ujian user
$query = "SELECT h.*, b.nama_lengkap, j.nama_jurusan, j.kode_jurusan, j.passing_grade
          FROM hasil_ujian h
          JOIN biodata_camaba b ON h.biodata_id = b.id
          JOIN jurusan j ON b.jurusan_id = j.id
          WHERE b.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$hasil = mysqli_fetch_assoc($result);

// Hitung status
if ($hasil) {
    $status = $hasil['nilai'] >= $hasil['passing_grade'] ? 'LULUS' : 'TIDAK LULUS';
    $status_class = $status == 'LULUS' ? 'success' : 'danger';
    $status_message = $status == 'LULUS' ? 
        'Selamat! Anda dinyatakan lulus seleksi ujian.' : 
        'Maaf, Anda belum memenuhi passing grade yang ditentukan.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - PMB Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hasil-card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
        }
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto;
            border: 5px solid;
        }
        .score-excellent {
            background: linear-gradient(135deg, #28a745, #20c997);
            border-color: #28a745;
            color: white;
        }
        .score-good {
            background: linear-gradient(135deg, #17a2b8, #0dcaf0);
            border-color: #17a2b8;
            color: white;
        }
        .score-average {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            border-color: #ffc107;
            color: white;
        }
        .score-poor {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            border-color: #dc3545;
            color: white;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'dashboard.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card hasil-card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-award"></i> Hasil Ujian Seleksi Online</h3>
                    </div>
                    
                    <div class="card-body">
                        <?php if ($hasil): ?>
                            <!-- Score Display -->
                            <div class="text-center mb-5">
                                <?php
                                $score_class = 'score-poor';
                                if ($hasil['nilai'] >= 85) $score_class = 'score-excellent';
                                elseif ($hasil['nilai'] >= 70) $score_class = 'score-good';
                                elseif ($hasil['nilai'] >= 50) $score_class = 'score-average';
                                ?>
                                <div class="score-circle <?php echo $score_class; ?> mb-3">
                                    <div class="text-center">
                                        <div class="display-3 fw-bold"><?php echo number_format($hasil['nilai'], 0); ?></div>
                                        <small>Nilai</small>
                                    </div>
                                </div>
                                
                                <h2 class="mb-2 text-<?php echo $status_class; ?>">
                                    <i class="fas fa-<?php echo $status == 'LULUS' ? 'trophy' : 'times-circle'; ?> me-2"></i>
                                    <?php echo $status; ?>
                                </h2>
                                <p class="lead"><?php echo $status_message; ?></p>
                                
                                <span class="badge bg-<?php echo $status_class; ?> p-2 fs-6">
                                    <i class="fas fa-chart-line me-1"></i>
                                    Passing Grade: <?php echo $hasil['passing_grade']; ?>%
                                </span>
                            </div>

                            <!-- Results Grid -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="info-box">
                                        <h5><i class="fas fa-user text-primary me-2"></i> Data Peserta</h5>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Nama Lengkap</strong></td>
                                                <td><?php echo $hasil['nama_lengkap']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jurusan Pilihan</strong></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $hasil['kode_jurusan']; ?></span>
                                                    <?php echo $hasil['nama_jurusan']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Token Ujian</strong></td>
                                                <td><code><?php echo $hasil['token']; ?></code></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <div class="info-box">
                                        <h5><i class="fas fa-chart-bar text-success me-2"></i> Statistik Ujian</h5>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Jumlah Soal</strong></td>
                                                <td><?php echo $hasil['jumlah_soal']; ?> soal</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jawaban Benar</strong></td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo $hasil['jawaban_benar']; ?></span>
                                                    jawaban
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Ujian</strong></td>
                                                <td><?php echo date('d F Y H:i', strtotime($hasil['tanggal_ujian'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Durasi</strong></td>
                                                <td><?php echo floor($hasil['waktu_ujian'] / 60); ?> menit</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <h5><i class="fas fa-tasks text-warning me-2"></i> Presentasi Hasil</h5>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Persentase Nilai</span>
                                    <span><?php echo number_format($hasil['nilai'], 1); ?>%</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar progress-bar-striped bg-<?php echo $status_class; ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $hasil['nilai']; ?>%">
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        <?php echo $hasil['jawaban_benar']; ?> dari <?php echo $hasil['jumlah_soal']; ?> soal dijawab dengan benar
                                    </small>
                                </div>
                            </div>

                            <!-- Detail Jawaban -->
                            <div class="mb-4">
                                <h5><i class="fas fa-list-check text-info me-2"></i> Detail Jawaban</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pertanyaan</th>
                                                <th>Jawaban Anda</th>
                                                <th>Jawaban Benar</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Query untuk mengambil detail jawaban user
                                            $detail_query = "SELECT jc.*, s.pertanyaan, s.jawaban_benar 
                                                            FROM jawaban_camaba jc
                                                            JOIN soal_ujian s ON jc.soal_id = s.id
                                                            WHERE jc.hasil_ujian_id = '{$hasil['id']}'";
                                            $detail_result = mysqli_query($conn, $detail_query);
                                            $detail_no = 1;
                                            while($detail = mysqli_fetch_assoc($detail_result)):
                                                $detail_status = $detail['jawaban'] == $detail['jawaban_benar'] ? 'Benar' : 'Salah';
                                                $detail_class = $detail_status == 'Benar' ? 'success' : 'danger';
                                            ?>
                                            <tr>
                                                <td><?php echo $detail_no++; ?></td>
                                                <td><?php echo substr($detail['pertanyaan'], 0, 60); ?>...</td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo strtoupper($detail['jawaban']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo strtoupper($detail['jawaban_benar']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $detail_class; ?>">
                                                        <i class="fas fa-<?php echo $detail_status == 'Benar' ? 'check' : 'times'; ?> me-1"></i>
                                                        <?php echo $detail_status; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                                </a>
                                <?php if ($status == 'LULUS'): ?>
                                    <a href="daftar_ulang.php" class="btn btn-success">
                                        <i class="fas fa-user-check me-2"></i> Lanjut Daftar Ulang
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reminderModal">
                                        <i class="fas fa-calendar-check me-2"></i> Jadwal Ulang Ujian
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i> Cetak Hasil
                                </button>
                            </div>
                            
                        <?php else: ?>
                            <!-- No Results -->
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                                <h4>Belum Ada Hasil Ujian</h4>
                                <p class="text-muted mb-4">
                                    Anda belum mengikuti ujian seleksi atau hasil ujian belum tersedia.
                                </p>
                                <div class="d-grid gap-2 col-md-6 mx-auto">
                                    <a href="ujian.php" class="btn btn-primary btn-lg">
                                        <i class="fas fa-pencil-alt me-2"></i> Ikuti Ujian Sekarang
                                    </a>
                                    <a href="dashboard.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-home me-2"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reminder Modal -->
    <div class="modal fade" id="reminderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-alt text-warning me-2"></i> Jadwal Ulang Ujian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Untuk mengikuti ujian ulang, silakan:</p>
                    <ol>
                        <li>Hubungi bagian administrasi PMB</li>
                        <li>Minta token ujian baru</li>
                        <li>Jadwalkan ulang ujian minimal 3 hari setelah ujian sebelumnya</li>
                    </ol>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Biaya ujian ulang Rp 50.000
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="token_ujian.php" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i> Minta Token Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Print styles
        function printResult() {
            window.print();
        }
        
        // Auto-refresh jika belum ada hasil
        <?php if (!$hasil): ?>
        setTimeout(function() {
            location.reload();
        }, 10000); // Refresh setiap 10 detik jika belum ada hasil
        <?php endif; ?>
    </script>
</body>
</html>