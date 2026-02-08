<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $biodata_id = $_GET['id'];
    
    // Generate token unik
    $token = substr(md5(uniqid(rand(), true)), 0, 10);
    
    // Simpan token ke database
    $query = "INSERT INTO token_ujian (biodata_id, token) VALUES ('$biodata_id', '$token')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Token berhasil digenerate: " . $token;
    } else {
        $_SESSION['error'] = "Gagal generate token";
    }
    
    header("Location: verifikasi_camaba.php?id=" . $biodata_id);
    exit();
}
?>