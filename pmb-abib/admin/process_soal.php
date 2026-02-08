<?php
session_start();
require_once '../config/database.php';

// Cek apakah user admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'] ?? '';

    if ($action == 'add') {
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
    } elseif ($action == 'edit') {
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

// Untuk delete via GET
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