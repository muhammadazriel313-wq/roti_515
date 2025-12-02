<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

// --- pastikan koneksi ada
if (!isset($koneksi) || !$koneksi) {
    die("Koneksi database gagal. Periksa file koneksi.");
}

// ===================== HAPUS PELANGGAN =====================
if(isset($_POST['hapus_pelanggan']) && isset($_POST['id_pelanggan'])){
    $id = (int) $_POST['id_pelanggan'];

    // Hapus pelanggan
    $del = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan='$id'");

    if(!$del){
        // Log error atau tampilkan (sementara)
        die("Gagal menghapus pelanggan: " . mysqli_error($koneksi));
    }

    header("Location: pelanggan.php");
    exit;
}


// ===================== UBAH STATUS =====================
if(isset($_POST['ubah_status']) && isset($_POST['id_pelanggan'])){
    $id = (int) $_POST['id_pelanggan'];
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);

    mysqli_query($koneksi, "UPDATE pelanggan SET status='$status_baru' WHERE id_pelanggan='$id'");

    header("Location: pelanggan.php");
    exit;
}


// ===================== FILTER =====================
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Semua';

// ===================== QUERY PELANGGAN (PASTIKAN $result TERDEFINISI) =====================
$query = "SELECT * FROM pelanggan";

if ($filter === "Mitra") {
    $query .= " WHERE jenis='Mitra'";
} elseif ($filter === "Perorangan") {
    $query .= " WHERE jenis='Perorangan'";
}

$query .= " ORDER BY tanggal_daftar DESC";

$result = mysqli_query($koneksi, $query);

// cek hasil query
if ($result === false) {
    die("Query ke tabel pelanggan gagal: " . mysqli_error($koneksi));
}


// ===================== HITUNG STATISTIK =====================
$total_pelanggan_q = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM pelanggan");
$total_pelanggan_row = $total_pelanggan_q ? mysqli_fetch_assoc($total_pelanggan_q) : ['cnt'=>0];
$total_pelanggan = (int)$total_pelanggan_row['cnt'];

$mitra_q = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM pelanggan WHERE jenis='Mitra'");
$mitra_row = $mitra_q ? mysqli_fetch_assoc($mitra_q) : ['cnt'=>0];
$mitra = (int)$mitra_row['cnt'];

$perorangan_q = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM pelanggan WHERE jenis='Perorangan'");
$perorangan_row = $perorangan_q ? mysqli_fetch_assoc($perorangan_q) : ['cnt'=>0];
$perorangan = (int)$perorangan_row['cnt'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pelanggan - Roti 515</title>
  <link rel="stylesheet" href="iya.css">
  <style>
    .btn-status { padding: 5px 10px; border: none; border-radius: 5px; color: #fff; cursor: pointer; font-size: 14px; }
    .btn-detail { background-color: #007bff; color: #fff; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 14px; }
    table { width: 100%; border-collapse: collapse; }
    table thead th { text-align: left; padding: 12px; border-bottom:1px solid rgba(255,255,255,0.06); color: #ff8a00; }
    table tbody td { padding: 12px; border-bottom:1px solid rgba(255,255,255,0.03); color:#ddd; }
    .badge-active { background:#28a745;color:#fff;padding:4px 8px;border-radius:6px;font-size:13px; }
    .badge-inactive { background:#dc3545;color:#fff;padding:4px 8px;border-radius:6px;font-size:13px; }
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
  <div class="sidebar">
    <h2>ROTI<span>515</span></h2>
    <ul>
      <li><a href="dashboard.php">📦 Pesanan</a></li>
      <li><a href="produk.php">🍞 Produk</a></li>
      <li><a href="pelanggan.php" class="active">👤 Pelanggan</a></li>
      <li><a href="laporan.php">📊 Laporan</a></li>
      <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h1>Data Pelanggan</h1>
      <p>Rekap seluruh pelanggan yang pernah mendaftar</p>
    </header>

    <!-- Statistik -->
    <section class="cards">
      <div class="card">
        <h3>Total Pelanggan</h3>
        <p><?= $total_pelanggan ?> Orang</p>
      </div>
      <div class="card">
        <h3>Pelanggan Mitra</h3>
        <p><?= $mitra ?> Orang</p>
      </div>
      <div class="card">
        <h3>Perorangan</h3>
        <p><?= $perorangan ?> Orang</p>
      </div>
    </section>

    <!-- Filter -->
    <div style="margin:15px 0;">
      <form method="GET" style="display:inline-block;">
        <select name="filter" onchange="this.form.submit()" style="padding:7px;border-radius:8px;">
          <option value="Semua" <?= $filter=='Semua'?'selected':'' ?>>Semua</option>
          <option value="Mitra" <?= $filter=='Mitra'?'selected':'' ?>>Mitra</option>
          <option value="Perorangan" <?= $filter=='Perorangan'?'selected':'' ?>>Perorangan</option>
        </select>
      </form>
    </div>

    <!-- Tabel -->
    <section class="table-section">
      <h2>Daftar Pelanggan</h2>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>No. Telp</th>
            <th>Jenis Pelanggan</th>
            <th>Total Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        // Pastikan $result valid (sudah dicek di atas)
        if(mysqli_num_rows($result) > 0){
          while($row = mysqli_fetch_assoc($result)){
              // ambil statistik per pelanggan dari tabel pesanan (opsional)
              $no_telpon = mysqli_real_escape_string($koneksi, $row['no_telpon']);

              // AMBIL NO TELP
$no_telpon = mysqli_real_escape_string($koneksi, $row['no_telpon']);

// HITUNG TOTAL HARGA DARI SEMUA PESANAN
$q_total = mysqli_query($koneksi, "
    SELECT SUM(total_harga) AS total_harga_all, 
           MAX(tanggal) AS last_order
    FROM pesanan 
    WHERE no_telpon='$no_telpon'
");

$d_total = mysqli_fetch_assoc($q_total);

$total_transaksi = $d_total['total_harga_all'] ?? 0; // total belanja
$terakhir_pesan = $d_total['last_order'] ?? '-';      // tanggal terakhir


              // status badge
              $status_html = ($row['status'] == 'Aktif') ? "<span class='badge-active'>Aktif</span>" : "<span class='badge-inactive'>Nonaktif</span>";

              echo "<tr>";
              echo "<td>".htmlspecialchars($row['nama'])."</td>";
              echo "<td>".htmlspecialchars($row['no_telpon'])."</td>";
              echo "<td>".htmlspecialchars($row['jenis'])."</td>";
              echo "<td>Rp " . number_format($total_transaksi, 0, ',', '.') . "</td>";
              echo "<td>".$terakhir_pesan."</td>";
              if ($row['jenis'] == 'Mitra') {
    echo "<td>".$status_html."</td>";
} else {
    echo "<td>-</td>"; // untuk perorangan kosong
}

              echo "<td style='text-align:center;'>";

              // tombol ubah status (toggle Aktif/Nonaktif)
              if ($row['jenis'] == 'Mitra') {
    // tombol ubah status hanya untuk mitra
    $new_status = ($row['status'] == 'Aktif') ? 'Nonaktif' : 'Aktif';
    
    // Tentukan ikon dan warna berdasarkan status saat ini
    if ($row['status'] == 'Aktif') {
        $icon = 'fas fa-toggle-on'; // Ikon untuk status Aktif
        $color = '#28a745'; // Warna hijau untuk Aktif
    } else {
        $icon = 'fas fa-toggle-off'; // Ikon untuk status Nonaktif
        $color = '#dc3545'; // Warna merah untuk Nonaktif
    }

    echo "<form method='POST' style='display:inline-block;margin-right:6px;'>
            <input type='hidden' name='id_pelanggan' value='".$row['id_pelanggan']."'>
            <input type='hidden' name='status' value='".$new_status."'>
            <button type='submit' name='ubah_status' class='btn-status' style='background:none;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;'>
              <i class='{$icon}' style='color:{$color}; font-size: 24px;'></i>
            </button>
          </form>";
}


              // tombol hapus
              echo "<form action='' method='POST' style='display:inline-block;'>
                      <input type='hidden' name='id_pelanggan' value='".$row['id_pelanggan']."'>
                      <button type='submit' name='hapus_pelanggan' onclick=\"return confirm('Yakin hapus pelanggan ini? Semua data yang terkait tidak akan bisa dipulihkan!')\" style='background:#dc3545;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;'>
                        <i class='fas fa-trash' style='color:white;'></i>
                      </button>
                    </form>";

              echo "</td>";
              echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data pelanggan</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </section>
  </div>
</body>
</html>
