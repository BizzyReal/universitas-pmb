<?php
// Set page title
$page_title = "Distribusi Soal";

// Include header
require_once 'includes/header.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jurusan_id = mysqli_real_escape_string($conn, $_POST['jurusan_id']);
    
    // Hapus semua mapel untuk jurusan ini
    mysqli_query($conn, "DELETE FROM jurusan_mapel WHERE jurusan_id = '$jurusan_id'");
    
    // Tambah mapel yang dipilih
    if (isset($_POST['mapel_ids'])) {
        foreach ($_POST['mapel_ids'] as $mapel_id) {
            $jumlah = mysqli_real_escape_string($conn, $_POST["jumlah_$mapel_id"]);
            mysqli_query($conn, "INSERT INTO jurusan_mapel (jurusan_id, mapel_id, jumlah_soal) 
                                VALUES ('$jurusan_id', '$mapel_id', '$jumlah')");
        }
    }
    
    $_SESSION['success'] = "Distribusi soal berhasil diperbarui!";
    header("Location: jurusan_mapel.php");
    exit();
}

// Get all jurusan
$jurusan = mysqli_query($conn, "SELECT * FROM jurusan WHERE aktif='1'");
?>

<!-- Include sidebar -->
<?php require_once 'includes/sidebar.php'; ?>
<!-- ... kode selanjutnya untuk form distribusi ... -->