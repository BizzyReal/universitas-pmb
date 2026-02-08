<?php
// config/database.php

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'universitas_pmb');

// Fungsi untuk mendapatkan koneksi
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        // Jangan tampilkan error detail untuk publik
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['error'] = "Database connection failed!";
        return false;
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>