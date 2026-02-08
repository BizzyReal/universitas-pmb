<?php
// Query untuk mengambil data camaba
$query = "SELECT b.*, j.nama_jurusan 
          FROM biodata_camaba b 
          LEFT JOIN jurusan j ON b.jurusan_id = j.id 
          WHERE b.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$camaba = mysqli_fetch_assoc($result);

// Query untuk status daftar ulang
$query_daftar = "SELECT * FROM daftar_ulang WHERE biodata_id = '".$camaba['id']."'";
$result_daftar = mysqli_query($conn, $query_daftar);
$daftar_ulang = mysqli_fetch_assoc($result_daftar);
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fas fa-user-graduate"></i> PMB Camaba</h4>
            <small class="text-white-50">Portal Mahasiswa Baru</small>
        </div>
        <button class="btn btn-sm btn-outline-light close-sidebar d-lg-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-content">
        <!-- User Profile -->
        <div class="text-center py-4">
            <div class="avatar mb-3">
                <i class="fas fa-user-circle fa-4x text-white-50"></i>
            </div>
            <h6 class="mb-1"><?php echo htmlspecialchars($camaba['nama_lengkap'] ?? 'User'); ?></h6>
            <small class="text-white-50">
                <?php echo htmlspecialchars($camaba['nama_jurusan'] ?? 'Belum pilih jurusan'); ?>
            </small>
            <div class="mt-2">
                <?php if (isset($daftar_ulang['nim']) && $daftar_ulang['nim']): ?>
                    <span class="badge bg-success">NIM: <?php echo $daftar_ulang['nim']; ?></span>
                <?php else: ?>
                    <span class="badge bg-warning">Calon Mahasiswa</span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Menu -->
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="biodata.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'biodata.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span>Biodata Saya</span>
                </a>
            </li>
            <li>
                <a href="ujian.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ujian.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Ujian Online</span>
                </a>
            </li>
            <li>
                <a href="hasil_ujian.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'hasil_ujian.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Hasil Ujian</span>
                </a>
            </li>
            <li>
                <a href="daftar_ulang.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'daftar_ulang.php' ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Daftar Ulang</span>
                </a>
            </li>
            <li>
                <a href="pengumuman.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pengumuman.php' ? 'active' : ''; ?>">
                    <i class="fas fa-bullhorn"></i>
                    <span>Pengumuman</span>
                </a>
            </li>
            <li>
                <a href="profil.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan Akun</span>
                </a>
            </li>
            <li>
                <a href="../auth/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>