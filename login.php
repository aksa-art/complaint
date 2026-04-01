
<?php
// Selalu panggil session_start() di baris paling atas sebelum output apapun
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Reset session agar tidak tertukar antar akun
session_unset();
session_destroy();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body id="bg-login">
    <div class="admin-access-container" id="admin-login" style="display:none;">
      <div class="admin-access-header">
        <div class="admin-access-icon">
          <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="30" cy="30" r="28" stroke="#00fff7" stroke-width="4"/>
            <path d="M30 18C34.4183 18 38 21.5817 38 26V30C38 34.4183 34.4183 38 30 38C25.5817 38 22 34.4183 22 30V26C22 21.5817 25.5817 18 30 18Z" stroke="#00fff7" stroke-width="2"/>
          </svg>
        </div>
        <h1 class="admin-access-title">ADMIN ACCESS</h1>
        <p class="admin-access-subtitle">Enter your credentials</p>
      </div>
      <form class="admin-access-form" action="" method="POST">
        <label class="admin-access-label" for="username">USERNAME</label>
        <div class="admin-access-input-wrapper">
          <span class="admin-access-input-icon">
            <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 11a4 4 0 100-8 4 4 0 000 8zm0 2c-3.31 0-6 2.69-6 6v1a1 1 0 001 1h10a1 1 0 001-1v-1c0-3.31-2.69-6-6-6z" stroke="#00fff7" stroke-width="1.5"/></svg>
          </span>
          <input type="text" id="username" name="username" placeholder="admin" class="admin-access-input" autocomplete="off">
        </div>
        <label class="admin-access-label" for="password">PASSWORD</label>
        <div class="admin-access-input-wrapper">
          <span class="admin-access-input-icon">
            <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 10V8a6 6 0 10-12 0v2M5 10h10v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z" stroke="#00fff7" stroke-width="1.5"/></svg>
          </span>
          <input type="password" id="password" name="password" placeholder="Enter password" class="admin-access-input">
        </div>
        <button type="submit" name="submit" class="admin-access-btn">LOGIN</button>
      </form>
      <!-- Demo credentials removed -->
    </div>
    <div class="student-portal-container" id="student-login" style="display:none;">
      <div class="student-portal-header">
        <div class="student-portal-icon">
          <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="30" cy="30" r="28" stroke="#d946ef" stroke-width="4"/>
            <path d="M30 24a6 6 0 100-12 6 6 0 000 12zm0 4c-5.52 0-10 4.48-10 10v2a2 2 0 002 2h16a2 2 0 002-2v-2c0-5.52-4.48-10-10-10z" stroke="#d946ef" stroke-width="2"/>
          </svg>
        </div>
        <h1 class="student-portal-title">STUDENT PORTAL</h1>
        <p class="student-portal-subtitle">Enter your credentials</p>
      </div>
      <form class="student-portal-form" action="" method="POST">
        <label class="student-portal-label" for="nisn">NISN</label>
        <div class="student-portal-input-wrapper">
          <span class="student-portal-input-icon">
            <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="7" width="16" height="8" rx="2" stroke="#d946ef" stroke-width="1.5"/><path d="M7 11h6" stroke="#d946ef" stroke-width="1.5"/></svg>
          </span>
          <input type="text" id="nisn" name="nisn" placeholder="Enter your NISN" class="student-portal-input" autocomplete="off">
        </div>
        <label class="student-portal-label" for="password_nisn">PASSWORD</label>
        <div class="student-portal-input-wrapper">
          <span class="student-portal-input-icon">
            <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 10V8a6 6 0 10-12 0v2M5 10h10v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z" stroke="#d946ef" stroke-width="1.5"/></svg>
          </span>
          <input type="password" id="password_nisn" name="password_nisn" placeholder="Enter password" class="student-portal-input">
        </div>
        <button type="submit" name="submit_nisn" class="student-portal-btn">LOGIN</button>
      </form>
      <div style="text-align:center;margin-top:16px;">
        <a href="register_siswa.php" style="color:#d946ef;text-decoration:underline;">Buat akun siswa</a>
      </div>
      <!-- Demo credentials removed -->
    </div>
    <script src="js/login.js"></script>

      <?php
      if(isset($_POST['submit'])) {
        include 'db.php';
        $user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
        $pass = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
        $cek = mysqli_query($conn, "SELECT * FROM admin WHERE role = '".$user."' AND password = '".MD5($pass)."' ");
        if(mysqli_num_rows($cek) > 0){
          $d = mysqli_fetch_object($cek);
          $_SESSION['status_login'] = true;
          $_SESSION['a_global'] = $d;
          $_SESSION['id'] = $d->admin_id;
          $_SESSION['is_admin'] = true;
          echo '<script>window.location="dashboard.php"</script>';
        }else{
          echo '<script>alert("Login Gagal: Cek username dan password anda!")</script>';
        }
      }
      ?>


        <?php
           if(isset($_POST['submit_nisn'])) {
            include 'db.php';
            $user = mysqli_real_escape_string($conn, $_POST['nisn'] ?? '');
            $pass = mysqli_real_escape_string($conn, $_POST['password_nisn'] ?? '');
            $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nisn = '".$user."' AND password = '".MD5($pass)."' ");
            if(mysqli_num_rows($cek) > 0){
                $d = mysqli_fetch_object($cek);
                $_SESSION['status_login'] = true;
                $_SESSION['s_global'] = $d;
                $_SESSION['id'] = $d->siswa_id;
                echo '<script>window.location="dashboard_siswa.php"</script>';
            }else{
                echo '<script>alert("Login Gagal: Cek NISN dan password anda!")</script>';
            }
           }
        ?>
        ?>
          
      
</body>
</html>