<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'camaba') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data biodata dan hasil ujian
$query = "SELECT b.*, h.nilai, h.status as status_ujian, j.nama_jurusan, j.kode_jurusan 
          FROM biodata_camaba b 
          LEFT JOIN hasil_ujian h ON b.id = h.biodata_id 
          LEFT JOIN jurusan j ON b.jurusan_id = j.id 
          WHERE b.user_id = '$user_id'";
$data = mysqli_fetch_assoc(mysqli_query($conn, $query));

// Cek apakah sudah daftar ulang
$daftar_ulang_query = "SELECT * FROM daftar_ulang WHERE biodata_id = '{$data['id']}'";
$daftar_ulang_result = mysqli_query($conn, $daftar_ulang_query);
$daftar_ulang = mysqli_fetch_assoc($daftar_ulang_result);

$error = '';
$success = '';

// Proses form daftar ulang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$daftar_ulang) {
    // Validasi: hanya yang lulus ujian bisa daftar ulang
    if ($data['status_ujian'] != 'lulus') {
        $error = "Anda tidak lulus ujian seleksi.";
    } else {
        // Generate NIM (contoh: tahun + kode jurusan + nomor urut)
        $tahun = date('y');
        $kode_jurusan = $data['kode_jurusan'];
        $nomor_urut = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $nim = $tahun . $kode_jurusan . $nomor_urut;

        // Simpan data daftar ulang
        $query = "INSERT INTO daftar_ulang (biodata_id, nim, status) 
                  VALUES ('{$data['id']}', '$nim', 'pending')";
        if (mysqli_query($conn, $query)) {
            $success = "Pendaftaran ulang berhasil! NIM sementara: $nim. Menunggu verifikasi admin.";
            // Refresh data
            $daftar_ulang_result = mysqli_query($conn, $daftar_ulang_query);
            $daftar_ulang = mysqli_fetch_assoc($daftar_ulang_result);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang - PMB Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'dashboard.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-user-check"></i> Pendaftaran Ulang</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if (!$data): ?>
                            <div class="alert alert-warning">
                                Anda belum melengkapi biodata.
                            </div>
                        <?php elseif ($data['status_verifikasi'] != 'verified'): ?>
                            <div class="alert alert-warning">
                                Biodata Anda belum diverifikasi.
                            </div>
                        <?php elseif (!$data['nilai']): ?>
                            <div class="alert alert-warning">
                                Anda belum mengikuti ujian seleksi.
                            </div>
                        <?php elseif ($data['status_ujian'] != 'lulus'): ?>
                            <div class="alert alert-danger">
                                Maaf, Anda tidak lulus ujian seleksi. Nilai Anda: <?php echo $data['nilai']; ?>
                            </div>
                        <?php else: ?>
                            <!-- Tampilkan data diri -->
                            <div class="mb-4">
                                <h5>Data Diri:</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Nama Lengkap</th>
                                        <td><?php echo $data['nama_lengkap']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jurusan</th>
                                        <td><?php echo $data['nama_jurusan']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nilai Ujian</th>
                                        <td><span class="badge bg-success"><?php echo $data['nilai']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <th>Status Ujian</th>
                                        <td><span class="badge bg-success">LULUS</span></td>
                                    </tr>
                                </table>
                            </div>

                            <?php if ($daftar_ulang): ?>
                                <!-- Sudah daftar ulang -->
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-check-circle"></i> Anda sudah mendaftar ulang</h5>
                                    <p>Status: 
                                        <?php if ($daftar_ulang['status'] == 'approved'): ?>
                                            <span class="badge bg-success">DISETUJUI</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">MENUNGGU</span>
                                        <?php endif; ?>
                                    </p>
                                    <p>NIM: <strong><?php echo $daftar_ulang['nim']; ?></strong></p>
                                    <p>Tanggal Daftar Ulang: <?php echo date('d/m/Y H:i', strtotime($daftar_ulang['tanggal_daftar_ulang'])); ?></p>
                                </div>
                            <?php else: ?>
                                <!-- Form daftar ulang -->
                                <div class="alert alert-success">
                                    <h5><i class="fas fa-graduation-cap"></i> Selamat! Anda berhak mendaftar ulang.</h5>
                                    <p>Silakan klik tombol di bawah untuk melakukan pendaftaran ulang.</p>
                                </div>

                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Konfirmasi Data</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" required>
                                            <label class="form-check-label">
                                                Saya menyatakan bahwa data yang saya berikan adalah benar.
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" required>
                                            <label class="form-check-label">
                                                Saya bersedia mengikuti seluruh proses pendidikan.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane"></i> Kirim Pendaftaran Ulang
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="mt-3">
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>