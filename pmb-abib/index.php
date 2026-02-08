<?php
// /pmb-abib/index.php - Landing Page
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMB Universitas - Penerimaan Mahasiswa Baru</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #6c757d;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.9), rgba(58, 86, 212, 0.9)),
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 100px 0;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .btn-hero {
            background-color: white;
            color: var(--primary);
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .btn-hero:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        /* Features Section */
        .section-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 50px;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background-color: var(--primary);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .feature-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s;
            padding: 40px 25px;
            text-align: center;
            height: 100%;
            background: white;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .feature-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary), #3a56d4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .feature-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        /* Jurusan Section */
        .jurusan-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
            background: white;
        }
        
        .jurusan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .jurusan-header {
            background: linear-gradient(135deg, var(--primary), #3a56d4);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .jurusan-body {
            padding: 30px;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            color: white;
            padding: 80px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        /* Timeline/Alur Pendaftaran */
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: var(--primary);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item:nth-child(odd) {
            left: 0;
        }
        
        .timeline-item:nth-child(even) {
            left: 50%;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            background-color: white;
            border: 4px solid var(--primary);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-item:nth-child(odd)::after {
            right: -12.5px;
        }
        
        .timeline-item:nth-child(even)::after {
            left: -12.5px;
        }
        
        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Footer */
        .footer {
            background-color: #1a1a2e;
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: var(--primary);
        }
        
        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
            transition: all 0.3s;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary) !important;
        }
        
        .navbar.scrolled {
            background-color: white;
            padding: 10px 0;
        }
        
        /* Kontak Form */
        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item:nth-child(even) {
                left: 0;
            }
            
            .timeline-item::after {
                left: 18px;
                right: auto;
            }
            
            .timeline-item:nth-child(odd)::after,
            .timeline-item:nth-child(even)::after {
                left: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-graduate me-2"></i>PMB UNIV
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jurusan">Jurusan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#alur">Alur Pendaftaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-primary" href="user/auth/login.php">Masuk</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary" href="user/auth/register.php">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center hero-content">
                    <h1 class="hero-title animate__animated animate__fadeInDown">
                        Penerimaan Mahasiswa Baru
                    </h1>
                    <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                        Selamat datang di portal penerimaan mahasiswa baru universitas terkemuka.
                        Bergabunglah dengan komunitas akademik terbaik untuk menggapai masa depan gemilang.
                        Tahun Akademik 2024/2025 telah dibuka!
                    </p>
                    <div class="animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="auth/register.php" class="btn btn-hero me-3">
                            Daftar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="#jurusan" class="btn btn-outline-light btn-hero-outline">
                            Lihat Jurusan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Mengapa Memilih Kami?</h2>
                    <p class="text-muted">Keunggulan yang membuat kami berbeda</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card animate__animated animate__fadeInUp">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4>Akreditasi A</h4>
                        <p class="text-muted">Universitas kami memiliki akreditasi A dari BAN-PT dengan fasilitas pembelajaran yang lengkap dan modern.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>Dosen Berpengalaman</h4>
                        <p class="text-muted">Dosen-dosen profesional dengan pengalaman di bidangnya masing-masing siap membimbing Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="feature-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4>Kerjasama Industri</h4>
                        <p class="text-muted">Kerjasama dengan berbagai perusahaan terkemuka untuk peluang magang dan karir setelah lulus.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jurusan Section -->
    <section id="jurusan" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Program Studi Unggulan</h2>
                    <p class="text-muted">Pilih program studi yang sesuai dengan passion Anda</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                // Koneksi database untuk mengambil data jurusan
                $host = 'localhost';
                $username = 'root';
                $password = '';
                $database = 'universitas_pmb';
                
                $conn = mysqli_connect($host, $username, $password, $database);
                
                if ($conn) {
                    // PERBAIKAN: Hapus WHERE status = 'aktif' karena kolom status mungkin tidak ada
                    // atau ganti dengan kolom yang sesuai
                    $query = "SELECT * FROM jurusan LIMIT 6"; // Hapus kondisi WHERE
                    $result = mysqli_query($conn, $query);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        $colors = ['#4361ee', '#3a56d4', '#7209b7', '#f72585', '#4cc9f0', '#4895ef'];
                        $i = 0;
                        while ($jurusan = mysqli_fetch_assoc($result)) {
                            $color = $colors[$i % count($colors)];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="jurusan-card">
                        <div class="jurusan-header" style="background: linear-gradient(135deg, <?php echo $color; ?>, <?php echo $color; ?>dd);">
                            <h3 class="mb-0"><?php echo $jurusan['kode_jurusan']; ?></h3>
                            <p class="mb-0 mt-2"><?php echo $jurusan['nama_jurusan']; ?></p>
                        </div>
                        <div class="jurusan-body">
                            <?php if(isset($jurusan['kuota'])): ?>
                                <p><strong>Kuota:</strong> <?php echo $jurusan['kuota']; ?> mahasiswa</p>
                            <?php endif; ?>
                            <?php if(isset($jurusan['passing_grade'])): ?>
                                <p><strong>Passing Grade:</strong> <?php echo $jurusan['passing_grade']; ?></p>
                            <?php endif; ?>
                            <p><?php echo isset($jurusan['deskripsi']) ? substr($jurusan['deskripsi'], 0, 120) . '...' : 'Program studi terbaik dengan kurikulum terkini dan dosen berpengalaman.'; ?></p>
                            <a href="user/auth/register.php" class="btn btn-outline-primary w-100">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
                <?php
                            $i++;
                        }
                    } else {
                        // Data dummy jika database kosong atau error
                        $jurusan_dummy = [
                            ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'warna' => '#4361ee'],
                            ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'warna' => '#3a56d4'],
                            ['kode' => 'MI', 'nama' => 'Manajemen Informatika', 'warna' => '#7209b7'],
                            ['kode' => 'TE', 'nama' => 'Teknik Elektro', 'warna' => '#f72585'],
                            ['kode' => 'TM', 'nama' => 'Teknik Mesin', 'warna' => '#4cc9f0'],
                            ['kode' => 'TS', 'nama' => 'Teknik Sipil', 'warna' => '#4895ef']
                        ];
                        
                        foreach ($jurusan_dummy as $index => $jurusan) {
                            $color = $jurusan['warna'];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="jurusan-card">
                        <div class="jurusan-header" style="background: linear-gradient(135deg, <?php echo $color; ?>, <?php echo $color; ?>dd);">
                            <h3 class="mb-0"><?php echo $jurusan['kode']; ?></h3>
                            <p class="mb-0 mt-2"><?php echo $jurusan['nama']; ?></p>
                        </div>
                        <div class="jurusan-body">
                            <p><strong>Kuota:</strong> 100 mahasiswa</p>
                            <p><strong>Passing Grade:</strong> 65</p>
                            <p>Program studi terbaik dengan kurikulum terkini dan dosen berpengalaman di bidangnya.</p>
                            <a href="user/auth/register.php" class="btn btn-outline-primary w-100">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                    if ($conn) mysqli_close($conn);
                } else {
                    // Jika koneksi gagal, tampilkan data dummy
                    $jurusan_dummy = [
                        ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'warna' => '#4361ee'],
                        ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'warna' => '#3a56d4'],
                        ['kode' => 'MI', 'nama' => 'Manajemen Informatika', 'warna' => '#7209b7'],
                        ['kode' => 'TE', 'nama' => 'Teknik Elektro', 'warna' => '#f72585'],
                        ['kode' => 'TM', 'nama' => 'Teknik Mesin', 'warna' => '#4cc9f0'],
                        ['kode' => 'TS', 'nama' => 'Teknik Sipil', 'warna' => '#4895ef']
                    ];
                    
                    foreach ($jurusan_dummy as $index => $jurusan) {
                        $color = $jurusan['warna'];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="jurusan-card">
                        <div class="jurusan-header" style="background: linear-gradient(135deg, <?php echo $color; ?>, <?php echo $color; ?>dd);">
                            <h3 class="mb-0"><?php echo $jurusan['kode']; ?></h3>
                            <p class="mb-0 mt-2"><?php echo $jurusan['nama']; ?></p>
                        </div>
                        <div class="jurusan-body">
                            <p><strong>Kuota:</strong> 100 mahasiswa</p>
                            <p><strong>Passing Grade:</strong> 65</p>
                            <p>Program studi terbaik dengan kurikulum terkini dan dosen berpengalaman di bidangnya.</p>
                            <a href="user/auth/register.php" class="btn btn-outline-primary w-100">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <a href="user/auth/register.php" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-list me-2"></i> Lihat Semua Jurusan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">5,000+</div>
                        <p>Mahasiswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">250+</div>
                        <p>Dosen Berpengalaman</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <p>Program Studi</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">100+</div>
                        <p>Kerjasama Industri</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran -->
    <section id="alur" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Alur Pendaftaran</h2>
                    <p class="text-muted">Ikuti langkah-langkah berikut untuk menjadi mahasiswa</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="timeline">
                        <!-- Langkah 1 -->
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon-circle bg-primary me-3">
                                        <span class="timeline-step">1</span>
                                    </div>
                                    <h4 class="mb-0">Registrasi Akun</h4>
                                </div>
                                <p>Daftarkan diri Anda dengan mengisi formulir pendaftaran online. Siapkan data pribadi seperti KTP, ijazah, dan pas foto.</p>
                                <ul>
                                    <li>Buka halaman pendaftaran</li>
                                    <li>Isi data pribadi dengan lengkap</li>
                                    <li>Verifikasi email dan aktivasi akun</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Langkah 2 -->
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon-circle bg-success me-3">
                                        <span class="timeline-step">2</span>
                                    </div>
                                    <h4 class="mb-0">Pilih Jurusan</h4>
                                </div>
                                <p>Pilih program studi yang sesuai dengan minat dan bakat Anda. Setiap jurusan memiliki kuota dan persyaratan khusus.</p>
                                <ul>
                                    <li>Telusuri informasi jurusan</li>
                                    <li>Pilih jurusan pertama dan alternatif</li>
                                    <li>Pastikan memenuhi persyaratan</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Langkah 3 -->
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon-circle bg-info me-3">
                                        <span class="timeline-step">3</span>
                                    </div>
                                    <h4 class="mb-0">Ujian Online</h4>
                                </div>
                                <p>Ikuti ujian seleksi secara online yang terdiri dari tes potensi akademik dan tes bidang studi sesuai jurusan pilihan.</p>
                                <ul>
                                    <li>Login sesuai jadwal yang ditentukan</li>
                                    <li>Selesaikan ujian dalam waktu yang diberikan</li>
                                    <li>Pastikan koneksi internet stabil</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Langkah 4 -->
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon-circle bg-warning me-3">
                                        <span class="timeline-step">4</span>
                                    </div>
                                    <h4 class="mb-0">Pengumuman Hasil</h4>
                                </div>
                                <p>Tunggu pengumuman hasil seleksi yang akan diumumkan melalui website dan email. Hasil biasanya keluar 1-2 minggu setelah ujian.</p>
                                <ul>
                                    <li>Cek email secara berkala</li>
                                    <li>Login ke dashboard pribadi</li>
                                    <li>Unduh surat keputusan jika diterima</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Langkah 5 -->
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon-circle bg-danger me-3">
                                        <span class="timeline-step">5</span>
                                    </div>
                                    <h4 class="mb-0">Daftar Ulang</h4>
                                </div>
                                <p>Lakukan daftar ulang dengan melengkapi dokumen dan pembayaran biaya pendidikan sesuai ketentuan yang berlaku.</p>
                                <ul>
                                    <li>Lengkapi dokumen persyaratan</li>
                                    <li>Lakukan pembayaran biaya pendidikan</li>
                                    <li>Upload bukti pembayaran</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p class="text-muted">Mengenal lebih dekat PMB Universitas</p>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                         alt="Tentang Kami" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-4">Visi Misi PMB Universitas</h3>
                    <div class="mb-4">
                        <h5 class="text-primary">Visi</h5>
                        <p>Menjadi pusat penerimaan mahasiswa baru yang terdepan dalam pelayanan, transparan, dan berkualitas untuk mencetak generasi pemimpin masa depan yang berkompeten dan berkarakter.</p>
                    </div>
                    <div class="mb-4">
                        <h5 class="text-primary">Misi</h5>
                        <ul>
                            <li>Menyelenggarakan sistem penerimaan mahasiswa baru yang adil, transparan, dan akuntabel</li>
                            <li>Menyediakan layanan informasi yang cepat, akurat, dan mudah diakses</li>
                            <li>Mengembangkan sistem seleksi yang komprehensif untuk mendapatkan calon mahasiswa terbaik</li>
                            <li>Membangun kerjasama dengan berbagai pihak untuk meningkatkan kualitas input mahasiswa</li>
                            <li>Menerapkan teknologi informasi terkini dalam proses penerimaan mahasiswa baru</li>
                        </ul>
                    </div>
                    <div class="mb-4">
                        <h5 class="text-primary">Filosofi Pendidikan</h5>
                        <p>Kami percaya bahwa setiap individu memiliki potensi unik yang dapat dikembangkan melalui pendidikan berkualitas. Sistem penerimaan kami dirancang untuk mengenali dan mengembangkan potensi tersebut, menciptakan lingkungan belajar yang inklusif dan mendukung.</p>
                    </div>
                </div>
            </div>
            
            <!-- Timeline Sejarah -->
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="text-center mb-4">Sejarah Perkembangan</h4>
                    <div class="history-timeline">
                        <div class="row text-center">
                            <div class="col-md-3 mb-4">
                                <div class="history-card p-3">
                                    <div class="history-year bg-primary">2010</div>
                                    <h5 class="mt-3">Pendirian</h5>
                                    <p>PMB Universitas didirikan dengan sistem konvensional</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="history-card p-3">
                                    <div class="history-year bg-success">2015</div>
                                    <h5 class="mt-3">Digitalisasi Awal</h5>
                                    <p>Memulai transformasi digital dengan sistem online pertama</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="history-card p-3">
                                    <div class="history-year bg-info">2020</div>
                                    <h5 class="mt-3">Era Modern</h5>
                                    <p>Meluncurkan sistem PMB terintegrasi lengkap</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="history-card p-3">
                                    <div class="history-year bg-warning">2024</div>
                                    <h5 class="mt-3">Inovasi Baru</h5>
                                    <p>Sistem AI-powered untuk seleksi yang lebih akurat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Hubungi Kami</h2>
                    <p class="text-muted">Kami siap membantu Anda dalam proses pendaftaran</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon bg-primary mb-3">
                                <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                            </div>
                            <h5 class="card-title">Alamat Kantor</h5>
                            <p class="card-text">
                                Gedung Rektorat Lt. 3<br>
                                Jl. Pendidikan No. 123<br>
                                Kota Akademik 12345<br>
                                Indonesia
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon bg-success mb-3">
                                <i class="fas fa-phone fa-2x text-white"></i>
                            </div>
                            <h5 class="card-title">Telepon & WhatsApp</h5>
                            <p class="card-text">
                                <strong>Informasi PMB:</strong><br>
                                (021) 1234-5678<br>
                                0812-3456-7890 (WA)<br>
                                <br>
                                <strong>Customer Service:</strong><br>
                                08:00 - 16:00 WIB
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon bg-info mb-3">
                                <i class="fas fa-envelope fa-2x text-white"></i>
                            </div>
                            <h5 class="card-title">Email & Media Sosial</h5>
                            <p class="card-text">
                                <strong>Email:</strong><br>
                                info@pmbuniv.ac.id<br>
                                pendaftaran@pmbuniv.ac.id<br>
                                <br>
                                <strong>Sosial Media:</strong><br>
                                @pmb_univ (Instagram)<br>
                                PMB Universitas (Facebook)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Kontak -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-form">
                        <h4 class="text-center mb-4">Kirim Pesan kepada Kami</h4>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek *</label>
                                <input type="text" class="form-control" id="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan *</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Pertanyaan yang Sering Ditanyakan</h2>
                    <p class="text-muted">Temukan jawaban untuk pertanyaan umum tentang PMB</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ Item 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Kapan pendaftaran PMB dibuka?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pendaftaran PMB dibuka setiap tahun pada bulan Januari untuk gelombang pertama, Maret untuk gelombang kedua, dan Juni untuk gelombang ketiga. Periode pendaftaran berlangsung selama 1 bulan untuk setiap gelombang.
                                </div>
                            </div>
                        </div>
                        
                        <!-- FAQ Item 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Apa saja persyaratan pendaftaran?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Persyaratan umum meliputi: Fotokopi ijazah dan transkrip nilai, fotokopi KTP, pas foto 3x4, dan surat rekomendasi (opsional). Persyaratan lengkap dapat dilihat di halaman panduan pendaftaran.
                                </div>
                            </div>
                        </div>
                        
                        <!-- FAQ Item 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Bagaimana sistem ujian online dilakukan?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ujian dilakukan secara online melalui platform khusus. Anda akan mendapatkan akses pada tanggal dan waktu yang telah ditentukan. Ujian terdiri dari tes potensi akademik dan tes bidang studi dengan durasi total 120 menit.
                                </div>
                            </div>
                        </div>
                        
                        <!-- FAQ Item 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Apakah ada beasiswa yang tersedia?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, terdapat berbagai jenis beasiswa seperti beasiswa prestasi, beasiswa kurang mampu, dan beasiswa khusus. Informasi detail dapat dilihat di halaman beasiswa setelah Anda login ke dashboard.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-4"><i class="fas fa-user-graduate me-2"></i>PMB UNIV</h4>
                    <p>Portal Penerimaan Mahasiswa Baru Universitas Terkemuka. Pendidikan berkualitas untuk masa depan gemilang.</p>
                    <div class="social-icons mt-4">
                        <a href="#" class="social-icon me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h5 class="mb-4">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home">Beranda</a></li>
                        <li class="mb-2"><a href="#jurusan">Jurusan</a></li>
                        <li class="mb-2"><a href="#alur">Alur Pendaftaran</a></li>
                        <li class="mb-2"><a href="#tentang">Tentang Kami</a></li>
                        <li class="mb-2"><a href="#kontak">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <h5 class="mb-4">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="user/auth/login.php">Masuk</a></li>
                        <li class="mb-2"><a href="user/auth/register.php">Daftar</a></li>
                        <li class="mb-2"><a href="#">Panduan Pendaftaran</a></li>
                        <li class="mb-2"><a href="#">FAQ</a></li>
                        <li class="mb-2"><a href="#">Beasiswa</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5 class="mb-4">Kontak Kami</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123, Kota Akademik</p>
                    <p><i class="fas fa-phone me-2"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@pmbuniv.ac.id</p>
                    <p><i class="fas fa-clock me-2"></i> Senin - Jumat: 08:00 - 16:00</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> PMB UNIV. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk pendidikan Indonesia</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll untuk anchor link
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if(this.getAttribute('href') === '#') return;
                
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Update active nav link
                    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
        
        // Form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Terima kasih! Pesan Anda telah dikirim. Kami akan menghubungi Anda dalam 1x24 jam.');
            this.reset();
        });
        
        // Update active nav link based on scroll position
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const scrollPos = window.scrollY + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
        
        // Add some inline styles for new elements
        const style = document.createElement('style');
        style.textContent = `
            .timeline-icon-circle {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.2rem;
            }
            
            .timeline-step {
                font-size: 1.2rem;
            }
            
            .history-year {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.3rem;
                margin: 0 auto;
            }
            
            .history-card {
                background: white;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                transition: transform 0.3s;
            }
            
            .history-card:hover {
                transform: translateY(-5px);
            }
            
            .contact-icon {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
            }
            
            .social-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: rgba(255,255,255,0.1);
                border-radius: 50%;
                transition: all 0.3s;
            }
            
            .social-icon:hover {
                background: var(--primary);
                transform: translateY(-3px);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>