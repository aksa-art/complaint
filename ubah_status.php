<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_aspirasi'], $_POST['status'])) {
    $id = intval($_POST['id_aspirasi']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $allowed = ['Menunggu','Diproses','Selesai'];
    if (!in_array($status, $allowed)) {
        die('Status tidak valid!');
    }
    $q = mysqli_query($conn, "UPDATE aspirasi SET status='$status' WHERE id_aspirasi=$id");
    if ($q) {
        header('Location: dashboard.php');
        exit;
    } else {
        die('Gagal mengubah status: '.mysqli_error($conn));
    }
} else {
    die('Permintaan tidak valid!');
}
?>