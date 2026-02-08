<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'camaba') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Cek apakah sudah ada biodata
$query = "SELECT b.*, j.nama_jurusan, j.kode_jurusan 
          FROM biodata_camaba b 
          LEFT JOIN jurusan j ON b.jurusan_id = j.id 
          WHERE b.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$biodata = mysqli_fetch_assoc($result);

// Ambil data jurusan untuk dropdown
$jurusan_query = "SELECT * FROM jurusan WHERE aktif = '1' ORDER BY nama_jurusan";
$jurusan_result = mysqli_query($conn, $jurusan_query);

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $asal_sekolah = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $tahun_lulus = mysqli_real_escape_string($conn, $_POST['tahun_lulus']);
    $nama_ortu = mysqli_real_escape_string($conn, $_POST['nama_ortu']);
    $pekerjaan_ortu = mysqli_real_escape_string($conn, $_POST['pekerjaan_ortu']);
    $jurusan_id = mysqli_real_escape_string($conn, $_POST['jurusan_id']);

    // Validasi
    if (empty($nama_lengkap) || empty($tempat_lahir) || empty($tanggal_lahir) || empty($jenis_kelamin) || empty($alamat) || empty($no_hp) || empty($asal_sekolah) || empty($tahun_lulus) || empty($nama_ortu) || empty($jurusan_id)) {
        $error = "Semua field wajib diisi!";
    } else {
        if ($biodata) {
            // Update biodata yang sudah ada
            $query = "UPDATE biodata_camaba SET 
                      nama_lengkap = '$nama_lengkap',
                      tempat_lahir = '$tempat_lahir',
                      tanggal_lahir = '$tanggal_lahir',
                      jenis_kelamin = '$jenis_kelamin',
                      alamat = '$alamat',
                      no_hp = '$no_hp',
                      asal_sekolah = '$asal_sekolah',
                      tahun_lulus = '$tahun_lulus',
                      nama_ortu = '$nama_ortu',
                      pekerjaan_ortu = '$pekerjaan_ortu',
                      jurusan_id = '$jurusan_id',
                      status_verifikasi = 'pending'
                      WHERE user_id = '$user_id'";
        } else {
            // Insert biodata baru
            $query = "INSERT INTO biodata_camaba (user_id, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_hp, asal_sekolah, tahun_lulus, nama_ortu, pekerjaan_ortu, jurusan_id, status_verifikasi) 
                      VALUES ('$user_id', '$nama_lengkap', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$alamat', '$no_hp', '$asal_sekolah', '$tahun_lulus', '$nama_ortu', '$pekerjaan_ortu', '$jurusan_id', 'pending')";
        }

        if (mysqli_query($conn, $query)) {
            $success = "Biodata berhasil disimpan! Menunggu verifikasi admin.";
            // Refresh data
            $result = mysqli_query($conn, "SELECT b.*, j.nama_jurusan, j.kode_jurusan FROM biodata_camaba b LEFT JOIN jurusan j ON b.jurusan_id = j.id WHERE b.user_id = '$user_id'");
            $biodata = mysqli_fetch_assoc($result);
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
    <title>Biodata - PMB Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .required:after {
            content: " *";
            color: red;
        }
        .jurusan-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .jurusan-card:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        .jurusan-card.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .jurusan-card input[type="radio"] {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'dashboard.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-user-edit"></i> Form Biodata & Pilihan Jurusan</h3>
                    </div>
                    
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <!-- Data Pribadi -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2"><i class="fas fa-user"></i> Data Pribadi</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_lengkap" class="form-label required">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                               value="<?php echo isset($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="tempat_lahir" class="form-label required">Tempat Lahir</label>
                                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                               value="<?php echo isset($biodata['tempat_lahir']) ? $biodata['tempat_lahir'] : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="tanggal_lahir" class="form-label required">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                               value="<?php echo isset($biodata['tanggal_lahir']) ? $biodata['tanggal_lahir'] : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Jenis Kelamin</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L" 
                                                <?php echo (isset($biodata['jenis_kelamin']) && $biodata['jenis_kelamin'] == 'L') ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="laki">Laki-laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P"
                                                <?php echo (isset($biodata['jenis_kelamin']) && $biodata['jenis_kelamin'] == 'P') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="perempuan">Perempuan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="no_hp" class="form-label required">No. HP/WhatsApp</label>
                                        <input type="tel" class="form-control" id="no_hp" name="no_hp" 
                                               value="<?php echo isset($biodata['no_hp']) ? $biodata['no_hp'] : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="alamat" class="form-label required">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo isset($biodata['alamat']) ? $biodata['alamat'] : ''; ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Data Pendidikan -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2"><i class="fas fa-book"></i> Data Pendidikan</h5>
                                
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="asal_sekolah" class="form-label required">Asal Sekolah</label>
                                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" 
                                               value="<?php echo isset($biodata['asal_sekolah']) ? $biodata['asal_sekolah'] : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="tahun_lulus" class="form-label required">Tahun Lulus</label>
                                        <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" 
                                               min="2000" max="2024" 
                                               value="<?php echo isset($biodata['tahun_lulus']) ? $biodata['tahun_lulus'] : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pilihan Jurusan -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2"><i class="fas fa-graduation-cap"></i> Pilihan Jurusan</h5>
                                <p class="text-muted">Pilih salah satu jurusan yang diminati:</p>
                                
                                <div class="row">
                                    <?php while($jurusan = mysqli_fetch_assoc($jurusan_result)): ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="jurusan-card <?php echo (isset($biodata['jurusan_id']) && $biodata['jurusan_id'] == $jurusan['id']) ? 'selected' : ''; ?>">
                                            <input type="radio" name="jurusan_id" value="<?php echo $jurusan['id']; ?>" 
                                                <?php echo (isset($biodata['jurusan_id']) && $biodata['jurusan_id'] == $jurusan['id']) ? 'checked' : ''; ?> required>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo $jurusan['nama_jurusan']; ?></h6>
                                                    <small class="text-muted">Kode: <?php echo $jurusan['kode_jurusan']; ?></small>
                                                    <div class="mt-2">
                                                        <span class="badge bg-info">Kuota: <?php echo $jurusan['kuota']; ?> kursi</span>
                                                        <span class="badge bg-warning ms-1">Passing Grade: 70</span>
                                                    </div>
                                                </div>
                                                <i class="fas fa-check-circle text-success <?php echo (isset($biodata['jurusan_id']) && $biodata['jurusan_id'] == $jurusan['id']) ? '' : 'd-none'; ?>"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            
                            <!-- Data Orang Tua -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2"><i class="fas fa-users"></i> Data Orang Tua</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_ortu" class="form-label required">Nama Orang Tua/Wali</label>
                                        <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" 
                                               value="<?php echo isset($biodata['nama_ortu']) ? $biodata['nama_ortu'] : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pekerjaan_ortu" class="form-label">Pekerjaan Orang Tua</label>
                                        <input type="text" class="form-control" id="pekerjaan_ortu" name="pekerjaan_ortu" 
                                               value="<?php echo isset($biodata['pekerjaan_ortu']) ? $biodata['pekerjaan_ortu'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Biodata
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Info Jurusan -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Jurusan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-laptop-code text-primary"></i> Rekayasa Perangkat Lunak (RPL)</h6>
                                <p class="small">Fokus pada pengembangan aplikasi dan software dengan pendekatan engineering.</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-database text-success"></i> Sistem Informasi (SI)</h6>
                                <p class="small">Mempelajari integrasi sistem informasi dalam bisnis dan organisasi.</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-microchip text-warning"></i> Teknik Informatika (TI)</h6>
                                <p class="small">Kombinasi hardware dan software dengan dasar teknik yang kuat.</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-robot text-danger"></i> Artificial Intelligence (AI)</h6>
                                <p class="small">Spesialisasi dalam kecerdasan buatan, machine learning, dan data science.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pilihan jurusan dengan card selection
        document.querySelectorAll('.jurusan-card').forEach(card => {
            card.addEventListener('click', function() {
                // Hapus seleksi dari semua card
                document.querySelectorAll('.jurusan-card').forEach(c => {
                    c.classList.remove('selected');
                    c.querySelector('.fa-check-circle').classList.add('d-none');
                });
                
                // Tambah seleksi ke card yang diklik
                this.classList.add('selected');
                this.querySelector('.fa-check-circle').classList.remove('d-none');
                
                // Set radio button yang sesuai
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Set max date untuk tanggal lahir (minimal 17 tahun yang lalu)
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 17, today.getMonth(), today.getDate());
        document.getElementById('tanggal_lahir').max = maxDate.toISOString().split('T')[0];
        
        // Set min dan max untuk tahun lulus
        document.getElementById('tahun_lulus').min = today.getFullYear() - 10;
        document.getElementById('tahun_lulus').max = today.getFullYear();
    </script>
</body>
</html>