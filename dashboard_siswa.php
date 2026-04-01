<?php
include 'db.php';
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true || !isset($_SESSION['s_global'])) {
    header('Location: login.php');
    exit;
}
// Ambil NISN dan id_siswa dari session siswa
$nisn = isset($_SESSION['s_global']->nisn) ? $_SESSION['s_global']->nisn : '';
$id_siswa = isset($_SESSION['s_global']->id_siswa) ? $_SESSION['s_global']->id_siswa : '';

// Handler insert komplain
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    echo '<pre>';
    echo "SESSION GLOBAL:\n";
    var_dump($_SESSION['s_global']);
    echo "\nPOST:\n";
    var_dump($_POST);
    echo "\nid_siswa: ".$id_siswa."\n";
    echo '</pre>';
    $id_kategori = mysqli_real_escape_string($conn, $_POST['category']);
    $kelas = mysqli_real_escape_string($conn, $_POST['class']);
    $deskiripsi = mysqli_real_escape_string($conn, $_POST['description']);
    $status = 'Menunggu';
    $date = date('Y-m-d H:i:s');
    $query = "INSERT INTO aspirasi (id_siswa, id_kategori, kelas, deskiripsi, status, data_created)
              VALUES ('$id_siswa', '$id_kategori', '$kelas', '$deskiripsi', '$status', '$date')";
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Komplain berhasil dikirim!");window.location.href="dashboard_siswa.php";</script>';
        exit;
    } else {
        die("Gagal mengirim komplain: ".mysqli_error($conn)."\nID SISWA: $id_siswa");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="stylesheet" href="css/siswa.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    
</head>
<body>
    <!-- Modal Tambah Pengaduan -->
    <div id="complaint-modal" class="student-portal-container" style="display:none; position:fixed; inset:0; min-height:100vh; width:100vw; background:rgba(24,30,42,0.92); z-index:999; align-items:center; justify-content:center; overflow-y:auto;">
        <form class="student-portal-form complaint-modal-form" autocomplete="off" method="post">
            <h2 class="complaint-modal-title">SUBMIT NEW COMPLAINT</h2>
            <label class="student-portal-label" for="category">Category *</label>
            <select id="category" name="category" class="student-portal-input" required>
                <option value="">Select Category</option>
                <?php
                $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                while($row = mysqli_fetch_assoc($kategori)) {
                    echo '<option value="'.$row['id_kategori'].'">'.htmlspecialchars($row['ket_kategori']).'</option>';
                }
                ?>
            </select>
            <label class="student-portal-label" for="nisn">NISN *</label>
            <input type="text" id="nisn" name="nisn" class="student-portal-input" value="<?= $nisn ?>" readonly required>
            <label class="student-portal-label" for="class">Class *</label>
            <input type="text" id="class" name="class" class="student-portal-input" placeholder="e.g., XII IPA 1" required>
            <label class="student-portal-label" for="date">Date *</label>
            <input type="date" id="date" name="date" class="student-portal-input" value="<?= date('Y-m-d') ?>" required>
            <label class="student-portal-label" for="description">Complaint Description *</label>
            <textarea id="description" name="description" class="student-portal-input" rows="4" placeholder="Describe your complaint in detail..." required></textarea>
            <div class="complaint-modal-btns">
                <button type="button" id="cancel-complaint" class="dashboard-header-btn dashboard-header-btn-outline complaint-btn-cancel">Cancel</button>
                <button type="submit" class="dashboard-header-btn dashboard-header-btn-primary complaint-btn-gradient">Submit Complaint</button>
            </div>
        </form>
    </div>
    <header class="dashboard-header">
        <div class="dashboard-header-left">
            <div class="dashboard-header-title">STUDENT PORTAL</div>
            <div class="dashboard-header-nisn">NISN: <span> <?= $nisn ?> </span></div>
        </div>
        <div class="dashboard-header-actions">
            <button id="btn-new-complaint" class="dashboard-header-btn dashboard-header-btn-primary">+ New Complaint</button>
            <a href="logout.php" class="dashboard-header-btn dashboard-header-btn-outline">↩ Logout</a>
        </div>
    </header>
    <div class="dashboard-wrapper">
        <main class="main-container">
            <!-- STATUS CARDS -->
            <?php
            // Hitung status komplain untuk siswa ini saja
            $pending = mysqli_query($conn, "SELECT COUNT(*) as jml FROM aspirasi WHERE status='Menunggu' AND id_siswa='$id_siswa'");
            $inprogress = mysqli_query($conn, "SELECT COUNT(*) as jml FROM aspirasi WHERE status='Diproses' AND id_siswa='$id_siswa'");
            $completed = mysqli_query($conn, "SELECT COUNT(*) as jml FROM aspirasi WHERE status='Selesai' AND id_siswa='$id_siswa'");
            $pending = mysqli_fetch_assoc($pending)['jml'];
            $inprogress = mysqli_fetch_assoc($inprogress)['jml'];
            $completed = mysqli_fetch_assoc($completed)['jml'];
            ?>
            <div class="dashboard-row dashboard-row-status">
                <div class="dashboard-card">
                    <div class="card-title"><svg fill="none" stroke="#ffe066" stroke-width="2" width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Pending</div>
                    <div class="card-value"><?php echo $pending; ?></div>
                    <div class="card-desc">Waiting for review</div>
                </div>
                <div class="dashboard-card">
                    <div class="card-title inprogress"><svg fill="none" stroke="#00e6ff" stroke-width="2" width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg> In Progress</div>
                    <div class="card-value"><?php echo $inprogress; ?></div>
                    <div class="card-desc">Being processed</div>
                </div>
                <div class="dashboard-card">
                    <div class="card-title completed"><svg fill="none" stroke="#00ffb3" stroke-width="2" width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2l4-4"/></svg> Completed</div>
                    <div class="card-value"><?php echo $completed; ?></div>
                    <div class="card-desc">Finished</div>
                </div>
            </div>
            <!-- TAB COMPLAINTS -->
            <div class="complaints-tab-container">
                <div class="complaints-tabs">
                    <div class="complaints-tab active" id="tab-my" onclick="showTab('my')">MY COMPLAINTS</div>
                    <div class="complaints-tab" id="tab-other" onclick="showTab('other')">OTHER COMPLAINTS</div>
                </div>
                <div class="complaints-tab-underline">
                    <div class="complaints-tab-underline-bar" id="tab-underline"></div>
                </div>
            </div>
            <div class="complaints-content" id="complaints-my">
                <!-- History complaints user sendiri -->
                <?php
                // Ambil data pengaduan siswa sendiri
                $query_aspirasi = mysqli_query($conn, "SELECT a.*, k.ket_kategori, s.nama_samaran FROM aspirasi a LEFT JOIN kategori k ON a.id_kategori = k.id_kategori LEFT JOIN siswa s ON a.id_siswa = s.id_siswa WHERE a.id_siswa = '$id_siswa' ORDER BY a.data_created DESC");
                if (mysqli_num_rows($query_aspirasi) > 0) {
                    echo '<div class="dashboard-row dashboard-row-scroll">';
                    while($asp = mysqli_fetch_assoc($query_aspirasi)) {
                        $nama_samaran = $asp['nama_samaran'] ? $asp['nama_samaran'] : $nisn;
                        $like = isset($asp['like']) ? $asp['like'] : 0;
                        // Ambil deskripsi dari field yang ada
                        $desk = isset($asp['deskiripsi']) && trim($asp['deskiripsi']) !== '' ? $asp['deskiripsi'] : (isset($asp['deskripsi']) && trim($asp['deskripsi']) !== '' ? $asp['deskripsi'] : '-');
                        $kelas = isset($asp['kelas']) ? $asp['kelas'] : '-';
                        $kategori = isset($asp['ket_kategori']) ? $asp['ket_kategori'] : '-';
                        $tanggal = isset($asp['data_created']) ? date('d-m-Y', strtotime($asp['data_created'])) : '-';
                        $id_aspirasi = $asp['id_aspirasi'];
                        $status = isset($asp['status']) ? $asp['status'] : '-';
                        echo '<div class="dashboard-card dashboard-card-complaint">';
                        echo '<div class="card-title">'.htmlspecialchars($nama_samaran).'</div>';
                        // Tambahkan status komplain
                        echo '<div class="card-status" style="margin-bottom:4px;font-size:13px;color:#888;font-weight:600;">Status: <span style="color:';
                        if ($status == 'Menunggu') echo '#ffe066';
                        elseif ($status == 'Diproses') echo '#00e6ff';
                        elseif ($status == 'Selesai') echo '#00ffb3';
                        else echo '#888';
                        echo '">'.htmlspecialchars($status).'</span></div>';
                        echo '<div class="card-desc">'.htmlspecialchars($desk).'</div>';
                        echo '<div class="card-meta-row">';
                        echo '<div class="card-meta">Kelas: <b>'.htmlspecialchars($kelas).'</b> | Kategori: <b>'.htmlspecialchars($kategori).'</b> | Tanggal: <b>'.$tanggal.'</b></div>';
                        // Cek apakah user sudah like aspirasi ini
                        $cek_like = mysqli_query($conn, "SELECT 1 FROM aspirasi_like WHERE id_aspirasi='$id_aspirasi' AND id_siswa='$id_siswa'");
                        $liked = mysqli_num_rows($cek_like) > 0;
                        echo '<div class="card-like" style="margin-left:auto;display:flex;align-items:center;gap:4px;">';
                        echo '<span class="like-count" id="like-count-'.$id_aspirasi.'">'.$like.'</span>';
                        echo '<svg class="love-icon'.($liked ? ' liked' : '').'" data-id="'.$id_aspirasi.'" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;"><path d="M20.8 5.6c-1.4-1.4-3.7-1.4-5.1 0l-1.1 1.1-1.1-1.1c-1.4-1.4-3.7-1.4-5.1 0-1.4 1.4-1.4 3.7 0 5.1l1.1 1.1 5.1 5.1 5.1-5.1 1.1-1.1c1.4-1.4 1.4-3.7 0-5.1z"></path></svg>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="complaints-history-empty">Belum ada pengaduan.</div>';
                }
                ?>
            </div>
            <div class="complaints-content" id="complaints-other" style="display:none;">
                <?php
                // Feed: tampilkan semua komplain siswa lain (bukan milik sendiri)
                $query_other = mysqli_query($conn, "SELECT a.*, k.ket_kategori, s.nama_samaran FROM aspirasi a LEFT JOIN kategori k ON a.id_kategori = k.id_kategori LEFT JOIN siswa s ON a.id_siswa = s.id_siswa WHERE a.id_siswa != '".$id_siswa."' ORDER BY a.data_created DESC");
                if (mysqli_num_rows($query_other) > 0) {
                    echo '<div class="dashboard-row dashboard-row-scroll">';
                    while($asp = mysqli_fetch_assoc($query_other)) {
                        $nama_samaran = $asp['nama_samaran'] ? $asp['nama_samaran'] : '-';
                        $like = isset($asp['like']) ? $asp['like'] : 0;
                        $desk = isset($asp['deskiripsi']) && trim($asp['deskiripsi']) !== '' ? $asp['deskiripsi'] : (isset($asp['deskripsi']) && trim($asp['deskripsi']) !== '' ? $asp['deskripsi'] : '-');
                        $kelas = isset($asp['kelas']) ? $asp['kelas'] : '-';
                        $kategori = isset($asp['ket_kategori']) ? $asp['ket_kategori'] : '-';
                        $tanggal = isset($asp['data_created']) ? date('d-m-Y', strtotime($asp['data_created'])) : '-';
                        $id_aspirasi = $asp['id_aspirasi'];
                        $status = isset($asp['status']) ? $asp['status'] : '-';
                        echo '<div class="dashboard-card dashboard-card-complaint">';
                        echo '<div class="card-title">'.htmlspecialchars($nama_samaran).'</div>';
                        echo '<div class="card-status" style="margin-bottom:4px;font-size:13px;color:#888;font-weight:600;">Status: <span style="color:';
                        if ($status == 'Menunggu') echo '#ffe066';
                        elseif ($status == 'Diproses') echo '#00e6ff';
                        elseif ($status == 'Selesai') echo '#00ffb3';
                        else echo '#888';
                        echo '\">'.htmlspecialchars($status).'</span></div>';
                        echo '<div class="card-desc">'.htmlspecialchars($desk).'</div>';
                        echo '<div class="card-meta-row">';
                        echo '<div class="card-meta">Kelas: <b>'.htmlspecialchars($kelas).'</b> | Kategori: <b>'.htmlspecialchars($kategori).'</b> | Tanggal: <b>'.$tanggal.'</b></div>';
                        // Cek apakah user sudah like aspirasi ini
                        $cek_like = mysqli_query($conn, "SELECT 1 FROM aspirasi_like WHERE id_aspirasi='$id_aspirasi' AND id_siswa='$id_siswa'");
                        $liked = mysqli_num_rows($cek_like) > 0;
                        echo '<div class="card-like" style="margin-left:auto;display:flex;align-items:center;gap:4px;">';
                        echo '<span class="like-count" id="like-count-'.$id_aspirasi.'">'.$like.'</span>';
                        echo '<svg class="love-icon'.($liked ? ' liked' : '').'" data-id="'.$id_aspirasi.'" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;"><path d="M20.8 5.6c-1.4-1.4-3.7-1.4-5.1 0l-1.1 1.1-1.1-1.1c-1.4-1.4-3.7-1.4-5.1 0-1.4 1.4-1.4 3.7 0 5.1l1.1 1.1 5.1 5.1 5.1-5.1 1.1-1.1c1.4-1.4 1.4-3.7 0-5.1z"></path></svg>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="complaints-other-empty">Belum ada pengaduan lain.</div>';
                }
                ?>
            </div>
        </main>
    </div>
    <script src="js/dashboard_siswa.js"></script>
    </script>
</body>
</html>
