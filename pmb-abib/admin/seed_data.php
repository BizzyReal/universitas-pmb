<?php
// HANYA UNTUK DEVELOPMENT - JANGAN DI PRODUCTION
require_once '../config/database.php';

echo "<h3>Seeding Data Testing...</h3>";

// Tambahkan beberapa soal dummy
$soal_dummy = [
    [
        'pertanyaan' => 'Apa ibukota Indonesia?',
        'pilihan_a' => 'Jakarta',
        'pilihan_b' => 'Surabaya',
        'pilihan_c' => 'Bandung',
        'pilihan_d' => 'Medan',
        'jawaban_benar' => 'a'
    ],
    [
        'pertanyaan' => '2 + 2 = ?',
        'pilihan_a' => '3',
        'pilihan_b' => '4',
        'pilihan_c' => '5',
        'pilihan_d' => '6',
        'jawaban_benar' => 'b'
    ],
    // Tambahkan lebih banyak soal...
];

foreach ($soal_dummy as $soal) {
    mysqli_query($conn, "INSERT INTO soal_ujian (pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar) VALUES (
        '{$soal['pertanyaan']}', '{$soal['pilihan_a']}', '{$soal['pilihan_b']}', '{$soal['pilihan_c']}', '{$soal['pilihan_d']}', '{$soal['jawaban_benar']}'
    )");
    echo "Soal ditambahkan: {$soal['pertanyaan']}<br>";
}

echo "<h3>Seeding selesai!</h3>";
echo "<a href='dashboard.php'>Kembali ke Dashboard</a>";
?>