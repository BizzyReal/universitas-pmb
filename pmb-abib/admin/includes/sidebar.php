<?php
// Current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar" style="width: 260px; background: white; border-right: 1px solid var(--gray-200); position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; transition: transform 0.3s;">
    <div class="sidebar-header" style="padding: 25px 20px; border-bottom: 1px solid var(--gray-200); background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
        <h3 style="margin: 0; font-weight: 700; font-size: 1.5rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-university"></i> PMB Admin
        </h3>
        <p style="margin: 5px 0 0 0; font-size: 0.875rem; opacity: 0.75;">Administrator Panel</p>
    </div>
    
    <nav class="sidebar-nav" style="padding: 20px 0;">
        <div style="margin: 0 15px 5px;">
            <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'dashboard.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="data_camaba.php" class="nav-link <?php echo $current_page == 'data_camaba.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'data_camaba.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Data Calon Maba</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="verifikasi_camaba.php" class="nav-link <?php echo $current_page == 'verifikasi_camaba.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'verifikasi_camaba.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-user-check"></i>
                <span>Verifikasi Camaba</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="jurusan.php" class="nav-link <?php echo $current_page == 'jurusan.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'jurusan.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-graduation-cap"></i>
                <span>Kelola Jurusan</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="mapel.php" class="nav-link <?php echo $current_page == 'mapel.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'mapel.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-book"></i>
                <span>Kelola Mapel</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="soal_ujian.php" class="nav-link <?php echo $current_page == 'soal_ujian.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'soal_ujian.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-question-circle"></i>
                <span>Kelola Soal</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="hasil_ujian.php" class="nav-link <?php echo $current_page == 'hasil_ujian.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'hasil_ujian.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Hasil Ujian</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="daftar_ulang.php" class="nav-link <?php echo $current_page == 'daftar_ulang.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'daftar_ulang.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Daftar Ulang</span>
            </a>
        </div>
        
        <div style="margin: 0 15px 5px;">
            <a href="data_mahasiswa.php" class="nav-link <?php echo $current_page == 'data_mahasiswa.php' ? 'active' : ''; ?>" 
               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--dark); text-decoration: none; border-radius: 8px; transition: all 0.3s; <?php echo $current_page == 'data_mahasiswa.php' ? 'background: var(--gray-100); color: var(--primary); font-weight: 500;' : ''; ?>">
                <i class="fas fa-id-card"></i>
                <span>Data Mahasiswa</span>
            </a>
        </div>
    </nav>
    
    <div class="sidebar-footer" style="padding: 20px; border-top: 1px solid var(--gray-200); position: absolute; bottom: 0; width: 100%; background: white;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <div class="user-avatar" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; margin-right: 12px;">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
            <div>
                <h6 style="margin: 0; font-weight: 600;"><?php echo $_SESSION['username']; ?></h6>
                <small style="color: var(--gray-300);">Administrator</small>
            </div>
        </div>
        <a href="logout.php" style="display: block; width: 100%; background: var(--light); border: 1px solid var(--gray-300); padding: 10px; border-radius: 8px; text-align: center; color: var(--danger); text-decoration: none; transition: all 0.3s;">
            <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i> Logout
        </a>
    </div>
</aside>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-toggle');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                if (sidebar.style.transform === 'translateX(0px)' || sidebar.style.transform === '') {
                    sidebar.style.transform = 'translateX(-100%)';
                } else {
                    sidebar.style.transform = 'translateX(0px)';
                }
            }
        });
    }
});
</script>