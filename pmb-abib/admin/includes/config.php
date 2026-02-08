<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Set default page title
$page_title = isset($page_title) ? $page_title : 'Admin Panel';
?>