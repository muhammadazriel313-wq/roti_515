<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

// ----------------------
if(isset($_POST['ubah_status']) && isset($_POST['telp'])){
    $telp = $_POST['telp'];
    $status_baru = $_POST['status'];

    mysqli_query($koneksi, "UPDATE pelanggan SET status='$status_baru' WHERE email='$telp'");
    // Redirect supaya tidak submit ulang saat refresh
    header("Location: pelanggan.php");
    exit;
}

// Ambil filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Semua';

// Query dasar ambil data pelanggan unik dengan IFNULL supaya Perorangan default
$query_str = "
    SELECT pesanan.nama_pembeli, pesanan.email AS no_telp, 
           COUNT(*) AS total_transaksi, MAX(pesanan.tanggal) AS terakhir_pesan, 
           IFNULL(pelanggan.jenis, 'Perorangan') AS jenis,
           IFNULL(pelanggan.status,'Aktif') AS status
    FROM pesanan
    LEFT JOIN pelanggan ON pesanan.email = pelanggan.email
    GROUP BY pesanan.nama_pembeli, pesanan.email
";

// Tambahkan filter Perorangan/Mitra
if ($filter == 'Mitra') {
    $query_str .= " HAVING jenis = 'Mitra'";
} elseif ($filter == 'Perorangan') {
    $query_str .= " HAVING jenis = 'Perorangan'";
}

$result = mysqli_query($koneksi, $query_str);

// Hitung statistik hanya pelanggan aktif
$total_pelanggan = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT DISTINCT pesanan.email 
    FROM pesanan 
    LEFT JOIN pelanggan ON pesanan.email = pelanggan.email AND (pelanggan.status='Aktif' OR pelanggan.status IS NULL)
"));

$mitra = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT DISTINCT pesanan.email 
    FROM pesanan 
    LEFT JOIN pelanggan ON pesanan.email = pelanggan.email 
    WHERE pelanggan.jenis='Mitra' AND pelanggan.status='Aktif'
"));

$perorangan = $total_pelanggan - $mitra;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pelanggan - Roti 515</title>
  <link rel="stylesheet" href="iya.css">
  <style>
    /* Tombol aktif / nonaktif */
    .btn-status {
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      color: #fff;
      cursor: pointer;
      font-size: 14px;
    }
    .aktif {
      background-color: #28a745; /* hijau */
    }
    .nonaktif {
      background-color: #dc3545; /* merah */
    }
    .btn-detail {
      background-color: #007bff;
      color: #fff;
      padding: 5px 10px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
    }
  </style>
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
      <p>Rekap seluruh pelanggan yang pernah memesan 🧾</p>
    </header>

    <!-- Statistik -->
    <section class="cards">
      <div class="card">
        <h3>Total Pelanggan Aktif</h3>
        <p><?= $total_pelanggan ?> Orang</p>
      </div>
      <div class="card">
        <h3>Pelanggan Mitra Aktif</h3>
        <p><?= $mitra ?> Orang</p>
      </div>
      <div class="card">
        <h3>Pelanggan Perorangan Aktif</h3>
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
      <h2>Daftar Pelanggan Aktif / Nonaktif</h2>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>No. Telp</th>
            <th>Jenis Konsumen</th>
            <th>Total Transaksi</th>
            <th>Terakhir Pesan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if(mysqli_num_rows($result) > 0){
          while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>".$row['nama_pembeli']."</td>";
            echo "<td>".$row['no_telp']."</td>";
            echo "<td>".$row['jenis']."</td>";
            echo "<td>".$row['total_transaksi']."</td>";
            echo "<td>".$row['terakhir_pesan']."</td>";
            echo "<td>".$row['status']."</td>";
            echo "<td>
                    <a href='detail_pelanggan.php?telp=".$row['no_telp']."' class='btn-detail'>Detail</a> 
                    <form action='' method='POST' style='display:inline; margin-left:5px;'>
                        <input type='hidden' name='telp' value='".$row['no_telp']."'>
                        <input type='hidden' name='status' value='".($row['status']=='Aktif'?'Nonaktif':'Aktif')."'>
                    </form>
                  </td>";
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
