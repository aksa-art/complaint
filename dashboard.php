<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/dashboard_admin.js"></script>
</head>
<body>
  <header class="admin-dashboard-header">
    <div>
      <div class="admin-dashboard-title">ADMIN DASHBOARD</div>
      <div class="admin-dashboard-subtitle">System Control Panel</div>
    </div>
    <a href="logout.php" class="admin-dashboard-btn">↩ Logout</a>
  </header>
  <main class="admin-dashboard-main">
    <?php
    // Hitung status komplain untuk admin (semua siswa)
    include_once 'db.php';
    $pending = mysqli_query($conn, "SELECT COUNT(*) as jml FROM aspirasi WHERE status='Menunggu'");
    $inprogress = mysqli_query($conn, "SELECT COUNT(*) as jml FROM aspirasi WHERE status='Diproses'");
    $completed = mysqli_query($conn, "SELECT COUNT(*) as jml FROM aspirasi WHERE status='Selesai'");
    $pending = mysqli_fetch_assoc($pending)['jml'];
    $inprogress = mysqli_fetch_assoc($inprogress)['jml'];
    $completed = mysqli_fetch_assoc($completed)['jml'];
    ?>
    <div class="admin-dashboard-row">
      <div class="admin-dashboard-card pending">
        <div class="card-title admin-title-pending">
          <svg fill="none" stroke="#00e6ff" stroke-width="2" width="22" height="22" viewBox="0 0 24 24" style="vertical-align:middle;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          Pending
        </div>
        <div class="card-value"><?php echo $pending; ?></div>
        <div class="card-desc">Waiting for review</div>
      </div>
      <div class="admin-dashboard-card inprogress">
        <div class="card-title admin-title-inprogress">
          <svg fill="none" stroke="#00e6ff" stroke-width="2" width="22" height="22" viewBox="0 0 24 24" style="vertical-align:middle;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 2"/></svg>
          In Progress
        </div>
        <div class="card-value"><?php echo $inprogress; ?></div>
        <div class="card-desc">Being processed</div>
      </div>
      <div class="admin-dashboard-card completed">
        <div class="card-title admin-title-completed">
          <svg fill="none" stroke="#00ffb3" stroke-width="2" width="22" height="22" viewBox="0 0 24 24" style="vertical-align:middle;"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-5"/></svg>
          Completed
        </div>
        <div class="card-value"><?php echo $completed; ?></div>
        <div class="card-desc">Finished</div>
      </div>
    </div>
    <div class="admin-dashboard-row">
      <section class="admin-dashboard-section" style="flex:2;">
        <div class="admin-dashboard-section-title">FILTERS</div>
        <form method="get" id="filterForm">
        <div class="admin-dashboard-filters">
          <div class="admin-dashboard-filter-group">
            <label for="filter-category">Category</label>
            <select id="filter-category" name="category" class="admin-dashboard-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">All Categories</option>
                <?php
                include_once 'db.php';
                $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                while($row = mysqli_fetch_assoc($kategori)) {
                  $selected = (isset($_GET['category']) && $_GET['category'] == $row['id_kategori']) ? 'selected' : '';
                  echo '<option value="'.$row['id_kategori'].'" '.$selected.'>'.htmlspecialchars($row['ket_kategori']).'</option>';
                }
                ?>
            </select>
          </div>
          <div class="admin-dashboard-filter-group">
            <label for="filter-status">Status</label>
            <select id="filter-status" name="status" class="admin-dashboard-select" onchange="document.getElementById('filterForm').submit()">
              <option value="">All Status</option>
              <option value="Menunggu" <?php if(isset($_GET['status']) && $_GET['status']==='Menunggu') echo 'selected'; ?>>Menunggu</option>
              <option value="Diproses" <?php if(isset($_GET['status']) && $_GET['status']==='Diproses') echo 'selected'; ?>>Diproses</option>
              <option value="Selesai" <?php if(isset($_GET['status']) && $_GET['status']==='Selesai') echo 'selected'; ?>>Selesai</option>
              <option value="Ditolak" <?php if(isset($_GET['status']) && $_GET['status']==='Ditolak') echo 'selected'; ?>>Ditolak</option>
            </select>
          </div>
          <div class="admin-dashboard-filter-group"></div>
        </div>
        </form>
      </section>
      <section class="admin-dashboard-section admin-dashboard-analytics" style="flex:1;min-width:320px;">
        <div class="admin-dashboard-section-title">KELUHAN PER KATEGORI</div>
        <canvas id="category-bar-chart" style="max-width:100%;min-width:220px;height:260px;"></canvas>
        <?php
        // Query jumlah keluhan per kategori
        $kategoriBar = [];
        $jumlahBar = [];
        $totalBar = 0;
        $qBar = mysqli_query($conn, "SELECT k.ket_kategori, COUNT(a.id_aspirasi) as jumlah FROM kategori k LEFT JOIN aspirasi a ON a.id_kategori = k.id_kategori GROUP BY k.id_kategori ORDER BY jumlah DESC, k.ket_kategori ASC");
        while($rowBar = mysqli_fetch_assoc($qBar)) {
          $kategoriBar[] = $rowBar['ket_kategori'];
          $jumlahBar[] = (int)$rowBar['jumlah'];
          $totalBar += (int)$rowBar['jumlah'];
        }
        ?>
        <script>
        window.categoryBarLabels = <?php echo json_encode($kategoriBar); ?>;
        window.categoryBarData = <?php echo json_encode($jumlahBar); ?>;
        window.categoryBarTotal = <?php echo $totalBar; ?>;
        </script>
      </section>
    </div>
    <section class="admin-dashboard-section admin-dashboard-complaints">
      <div class="admin-dashboard-section-title">COMPLAINTS</div>
      <div style="overflow-x:auto;">
      <table class="admin-dashboard-table" style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:#2d0a3a;color:#fff;">
            <th style="padding:8px 12px;">Nama Samaran</th>
            <th style="padding:8px 12px;">Deskripsi</th>
            <th style="padding:8px 12px;">Kelas</th>
            <th style="padding:8px 12px;">Kategori</th>
            <th style="padding:8px 12px;">Tanggal</th>
            <th style="padding:8px 12px;">Like</th>
            <th style="padding:8px 12px;">Status</th>
            <th style="padding:8px 12px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        include_once 'db.php';
        $where = [];
        if(isset($_GET['category']) && $_GET['category']!=='') {
          $where[] = "a.id_kategori='".mysqli_real_escape_string($conn,$_GET['category'])."'";
        }
        if(isset($_GET['status']) && $_GET['status']!=='') {
          $where[] = "a.status='".mysqli_real_escape_string($conn,$_GET['status'])."'";
        }
        $whereSql = count($where) ? ('WHERE '.implode(' AND ',$where)) : '';
        $q = mysqli_query($conn, "SELECT a.*, s.nisn, s.nama_samaran, k.ket_kategori FROM aspirasi a LEFT JOIN siswa s ON a.id_siswa=s.id_siswa LEFT JOIN kategori k ON a.id_kategori=k.id_kategori $whereSql ORDER BY a.data_created DESC");
        if(mysqli_num_rows($q)>0){
          while($row=mysqli_fetch_assoc($q)){
            $desk = isset($row['deskiripsi']) && trim($row['deskiripsi'])!=='' ? $row['deskiripsi'] : (isset($row['deskripsi']) && trim($row['deskripsi'])!=='' ? $row['deskripsi'] : '-');
            echo '<tr style="background:#1a0033;color:#fff;">';
            echo '<td style="padding:8px 12px;">'.htmlspecialchars($row['nama_samaran']).'</td>';
            echo '<td style="padding:8px 12px;max-width:220px;">'.htmlspecialchars($desk).'</td>';
            echo '<td style="padding:8px 12px;">'.htmlspecialchars($row['kelas']).'</td>';
            echo '<td style="padding:8px 12px;">'.htmlspecialchars($row['ket_kategori']).'</td>';
            echo '<td style="padding:8px 12px;">'.date('d-m-Y',strtotime($row['data_created'])).'</td>';
            echo '<td style="padding:8px 12px;text-align:center;">'.(isset($row['like'])?$row['like']:0).'</td>';
            echo '<td style="padding:8px 12px;">'.htmlspecialchars($row['status']).'</td>';
            echo '<td style="padding:8px 12px;">';
            echo '<form method="post" action="ubah_status.php" style="display:inline;">';
            echo '<input type="hidden" name="id_aspirasi" value="'.htmlspecialchars($row['id_aspirasi']).'">';
            echo '<select name="status" style="padding:2px 8px;">';
            $statuses = ['Menunggu','Diproses','Selesai'];
            foreach($statuses as $st){
              $sel = ($row['status']==$st)?'selected':'';
              echo '<option value="'.$st.'" '.$sel.'>'.$st.'</option>';
            }
            echo '</select> ';
            echo '<button type="submit" style="padding:2px 8px;">Ubah</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
          }
        }else{
          echo '<tr><td colspan="9" style="text-align:center;color:#d1a4f7;opacity:0.7;padding:32px 0;">Belum ada pengaduan.</td></tr>';
        }
        ?>
        </tbody>
      </table>
      </div>
    </section>
  </main>
  