<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

// Cek jika user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include header admin jika ada
// include(__DIR__ . '/../../includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: #1a1a2e;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background: #16213e;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <hr>
        <a href="dashboard.php">Dashboard</a>
        <a href="../verifikasi_camaba.php">Verifikasi Camaba</a>
        <a href="../data_camaba.php">Data Camaba</a>
        <a href="../soal_ujian.php">Soal Ujian</a>
        <a href="../generate_token.php">Generate Token</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>This is the admin dashboard.</p>
        <p>Role: <?php echo $_SESSION['role']; ?></p>
        
        <!-- Tambahkan konten dashboard di sini -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Camaba</h5>
                        <?php
                            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='camaba'");
                            $row = $result->fetch_assoc();
                            echo "<p class='card-text' style='font-size: 2rem;'>" . $row['total'] . "</p>";
                            $conn->close();
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Camaba Aktif</h5>
                        <?php
                            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='camaba' AND status='active'");
                            $row = $result->fetch_assoc();
                            echo "<p class='card-text' style='font-size: 2rem;'>" . $row['total'] . "</p>";
                            $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>