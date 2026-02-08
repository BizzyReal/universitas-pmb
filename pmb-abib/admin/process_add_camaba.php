<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $asal_sekolah = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    
    // Insert into users table
    $user_query = "INSERT INTO users (username, email, password, role) 
                   VALUES ('$username', '$email', '$password', 'camaba')";
    
    if (mysqli_query($conn, $user_query)) {
        $user_id = mysqli_insert_id($conn);
        
        // Insert into biodata table
        $biodata_query = "INSERT INTO biodata_camaba (user_id, nama_lengkap, asal_sekolah) 
                          VALUES ('$user_id', '$nama_lengkap', '$asal_sekolah')";
        
        if (mysqli_query($conn, $biodata_query)) {
            $_SESSION['success'] = "Data camaba berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Error menambahkan biodata: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Error menambahkan user: " . mysqli_error($conn);
    }
    
    header("Location: data_camaba.php");
    exit();
}
?>