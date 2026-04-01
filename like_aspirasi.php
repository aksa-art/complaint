<?php
include 'db.php';
session_start();
if (!isset($_SESSION['s_global'])) {
    echo 'ERR'; exit;
}
$id_siswa = $_SESSION['s_global']->id_siswa;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_aspirasi'])) {
    $id = intval($_POST['id_aspirasi']);
    // Cek apakah user sudah like
    $cek = mysqli_query($conn, "SELECT 1 FROM aspirasi_like WHERE id_aspirasi=$id AND id_siswa=$id_siswa");
    if (mysqli_num_rows($cek) > 0) {
        // Sudah like, maka unlike
        mysqli_query($conn, "DELETE FROM aspirasi_like WHERE id_aspirasi=$id AND id_siswa=$id_siswa");
        mysqli_query($conn, "UPDATE aspirasi SET `like` = IF(`like`>0, `like`-1, 0) WHERE id_aspirasi=$id");
    } else {
        // Belum like, maka like
        mysqli_query($conn, "INSERT INTO aspirasi_like (id_aspirasi, id_siswa) VALUES ($id, $id_siswa)");
        mysqli_query($conn, "UPDATE aspirasi SET `like` = IFNULL(`like`,0) + 1 WHERE id_aspirasi=$id");
    }
    // Ambil jumlah like terbaru
    $result = mysqli_query($conn, "SELECT `like` FROM aspirasi WHERE id_aspirasi = $id");
    $row = mysqli_fetch_assoc($result);
    echo $row['like'];
} else {
    echo 'ERR';
}
?>