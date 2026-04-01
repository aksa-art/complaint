<?php
// Proses pembuatan akun siswa baru
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn'] ?? '');
    $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
    $confirm = mysqli_real_escape_string($conn, $_POST['confirm_password'] ?? '');
    if ($nisn === '' || $password === '' || $confirm === '') {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        // Cek apakah NISN sudah terdaftar
        $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nisn='$nisn'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'NISN sudah terdaftar!';
        } else {
            // Generate nama samaran unik
            $adjectives = ['Cyber', 'Neon', 'Pixel', 'Shadow', 'Quantum', 'Nova', 'Blue', 'Red', 'Silent', 'Rapid', 'Lucky', 'Mighty'];
            $nouns = ['Wolf', 'Ninja', 'Tiger', 'Falcon', 'Samurai', 'Dragon', 'Lion', 'Eagle', 'Panther', 'Shark', 'Phoenix', 'Cobra'];
            do {
                $randAdj = $adjectives[array_rand($adjectives)];
                $randNoun = $nouns[array_rand($nouns)];
                $randNum = rand(100,999);
                $nama_samaran = $randAdj . $randNoun . $randNum;
                $cek_nama = mysqli_query($conn, "SELECT 1 FROM siswa WHERE nama_samaran = '$nama_samaran'");
            } while(mysqli_num_rows($cek_nama) > 0);
            $pass_hash = md5($password);
            $query = "INSERT INTO siswa (nisn, password, nama_samaran) VALUES ('$nisn', '$pass_hash', '$nama_samaran')";
            if (mysqli_query($conn, $query)) {
                $success = 'Akun berhasil dibuat! Silakan login.';
            } else {
                $error = 'Gagal membuat akun: '.mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buat Akun Siswa</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body id="bg-login">
    <div class="student-portal-container" style="margin-top:60px;">
        <div class="student-portal-header">
            <div class="student-portal-icon">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="#d946ef" stroke-width="4"/>
                    <path d="M30 24a6 6 0 100-12 6 6 0 000 12zm0 4c-5.52 0-10 4.48-10 10v2a2 2 0 002 2h16a2 2 0 002-2v-2c0-5.52-4.48-10-10-10z" stroke="#d946ef" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="student-portal-title">BUAT AKUN SISWA</h1>
            <p class="student-portal-subtitle">Isi data di bawah untuk mendaftar</p>
        </div>
        <?php if (!empty($error)) echo '<div style="color:#ff4d6d;text-align:center;margin-bottom:10px;font-weight:600;">'.htmlspecialchars($error).'</div>'; ?>
        <?php if (!empty($success)) echo '<div style="color:#00ffb3;text-align:center;margin-bottom:10px;font-weight:600;">'.htmlspecialchars($success).'</div>'; ?>
        <form class="student-portal-form" action="" method="POST">
            <label class="student-portal-label" for="nisn">NISN</label>
            <div class="student-portal-input-wrapper">
                <span class="student-portal-input-icon">
                    <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="7" width="16" height="8" rx="2" stroke="#d946ef" stroke-width="1.5"/><path d="M7 11h6" stroke="#d946ef" stroke-width="1.5"/></svg>
                </span>
                <input type="text" id="nisn" name="nisn" placeholder="Enter your NISN" class="student-portal-input" autocomplete="off" required>
            </div>
            <label class="student-portal-label" for="password">PASSWORD</label>
            <div class="student-portal-input-wrapper">
                <span class="student-portal-input-icon">
                    <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 10V8a6 6 0 10-12 0v2M5 10h10v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z" stroke="#d946ef" stroke-width="1.5"/></svg>
                </span>
                <input type="password" id="password" name="password" placeholder="Enter password" class="student-portal-input" required>
            </div>
            <label class="student-portal-label" for="confirm_password">CONFIRM PASSWORD</label>
            <div class="student-portal-input-wrapper">
                <span class="student-portal-input-icon">
                    <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 10V8a6 6 0 10-12 0v2M5 10h10v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z" stroke="#d946ef" stroke-width="1.5"/></svg>
                </span>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" class="student-portal-input" required>
            </div>
            <button type="submit" class="student-portal-btn">DAFTAR</button>
            <div style="text-align:center;margin-top:16px;">
                <a href="login.php#student" style="color:#d946ef;text-decoration:underline;">Sudah punya akun? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
