<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user, biodata, dan jurusan
$query = "SELECT b.*, j.nama_jurusan, j.kode_jurusan, j.jumlah_soal, j.waktu_ujian, 
                 t.token 
          FROM biodata_camaba b 
          LEFT JOIN jurusan j ON b.jurusan_id = j.id 
          LEFT JOIN token_ujian t ON b.id = t.biodata_id 
          WHERE b.user_id = '$user_id' AND b.status_verifikasi = 'verified' 
          AND t.digunakan = '0'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result); // Perbaikan: sebelumnya $data = mysqli_fetch_assoc($data);

if (!$data) {
    header("Location: biodata.php");
    exit();
}

// Ambil soal berdasarkan jurusan dan mapel yang diujikan
$jurusan_id = $data['jurusan_id'];
$jumlah_soal = $data['jumlah_soal'];

// Query untuk mengambil soal berdasarkan jurusan
$soal_query = "SELECT s.*, m.nama_mapel, m.kode_mapel 
               FROM soal_ujian s 
               JOIN mapel m ON s.mapel_id = m.id 
               WHERE s.jurusan_id = '$jurusan_id' 
               AND s.aktif = '1' 
               ORDER BY RAND() 
               LIMIT $jumlah_soal";
$soal_result = mysqli_query($conn, $soal_query);

// Hitung jumlah soal per mapel untuk informasi
$mapel_count = [];
while($soal = mysqli_fetch_assoc($soal_result)) {
    $mapel_id = $soal['mapel_id'];
    if (!isset($mapel_count[$mapel_id])) {
        $mapel_count[$mapel_id] = [
            'nama' => $soal['nama_mapel'],
            'kode' => $soal['kode_mapel'],
            'count' => 0
        ];
    }
    $mapel_count[$mapel_id]['count']++;
}

// Reset pointer hasil query
mysqli_data_seek($soal_result, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Online - PMB Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            z-index: 1000;
        }
        .soal-container {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="timer" id="timer">00:30:00</div>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Ujian Seleksi Online</h4>
                        <small>Token: <?php echo $data['token']; ?></small>
                    </div>
                    <div class="card-body">
                        <form id="formUjian" method="POST" action="proses_ujian.php">
                            <?php $no = 1; while ($soal = mysqli_fetch_assoc($soal_result)): ?>
                            <div class="soal-container">
                                <h5>Soal <?php echo $no; ?></h5>
                                <p class="fw-bold"><?php echo $soal['pertanyaan']; ?></p>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="soal_<?php echo $soal['id']; ?>" id="soal_<?php echo $soal['id']; ?>_a" value="a">
                                    <label class="form-check-label" for="soal_<?php echo $soal['id']; ?>_a">
                                        A. <?php echo $soal['pilihan_a']; ?>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="soal_<?php echo $soal['id']; ?>" id="soal_<?php echo $soal['id']; ?>_b" value="b">
                                    <label class="form-check-label" for="soal_<?php echo $soal['id']; ?>_b">
                                        B. <?php echo $soal['pilihan_b']; ?>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="soal_<?php echo $soal['id']; ?>" id="soal_<?php echo $soal['id']; ?>_c" value="c">
                                    <label class="form-check-label" for="soal_<?php echo $soal['id']; ?>_c">
                                        C. <?php echo $soal['pilihan_c']; ?>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="soal_<?php echo $soal['id']; ?>" id="soal_<?php echo $soal['id']; ?>_d" value="d">
                                    <label class="form-check-label" for="soal_<?php echo $soal['id']; ?>_d">
                                        D. <?php echo $soal['pilihan_d']; ?>
                                    </label>
                                </div>
                            </div>
                            <?php $no++; endwhile; ?>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan jawaban?')">
                                    Selesai & Kumpulkan Jawaban
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Timer 30 menit
        let timeLeft = 30 * 60; // 30 menit dalam detik
        const timerElement = document.getElementById('timer');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                document.getElementById('formUjian').submit();
            } else {
                timeLeft--;
            }
        }
        
        setInterval(updateTimer, 1000);
        updateTimer();
        
        // Mencegah refresh/navigate keluar
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });
    </script>
</body>
</html>