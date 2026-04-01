<?php
include 'db.php';

// Array kata sifat dan kata benda
$adjectives = ['Cyber', 'Neon', 'Pixel', 'Shadow', 'Quantum', 'Nova', 'Blue', 'Red', 'Silent', 'Rapid', 'Lucky', 'Mighty'];
$nouns = ['Wolf', 'Ninja', 'Tiger', 'Falcon', 'Samurai', 'Dragon', 'Lion', 'Eagle', 'Panther', 'Shark', 'Phoenix', 'Cobra'];

// Ambil semua siswa yang nama_samaran-nya kosong
$result = $conn->query("SELECT id_siswa FROM siswa WHERE nama_samaran = '' OR nama_samaran IS NULL");
while ($row = $result->fetch_assoc()) {
    do {
        $randAdj = $adjectives[array_rand($adjectives)];
        $randNoun = $nouns[array_rand($nouns)];
        $randNum = rand(100,999);
        $nama_samaran = $randAdj . $randNoun . $randNum;
        // Pastikan tidak ada duplikat
        $cek = $conn->query("SELECT 1 FROM siswa WHERE nama_samaran = '$nama_samaran'");
    } while ($cek->num_rows > 0);

    $id = $row['id_siswa'];
    $conn->query("UPDATE siswa SET nama_samaran = '$nama_samaran' WHERE id_siswa = $id");
}
echo "Nama samaran berhasil diisi otomatis!";
?>
