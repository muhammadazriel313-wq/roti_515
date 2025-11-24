<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
a
include('../database/koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Roti 515</title>
  <link rel="stylesheet" href="iya.css">
</head>
<body>
  <div class="sidebar">
    <h2>ROTI<span>515</span></h2>
    <ul>
      <li><a href="dashboard.php" class="active">📦 Pesanan</a></li>
      <li><a href="produk.php">🍞 Produk</a></li>
      <li><a href="pelanggan.php">👤 Pelanggan</a></li>
      <li><a href="laporan.php">📊 Laporan</a></li>
      <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h1>Dashboard Admin</h1>
      <p>Selamat datang, <strong><?= $_SESSION['admin'] ?></strong> 👋</p>
    </header>
    <?php if (isset($_GET['selesai'])): ?>
<div style="background:#00c853;color:white;padding:10px 15px;border-radius:8px;margin-bottom:20px;box-shadow:0 3px 10px rgba(0,0,0,0.3);">
  ✅ Pesanan telah diselesaikan dan dipindahkan ke laporan.
</div>
<?php endif; ?>

    <section class="cards">
  <div class="card">
    <h3>Total Pesanan</h3>
    <?php
      $total = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pesanan"));
      echo "<p>$total Pesanan</p>";
    ?>
  </div>

  <div class="card">
    <h3>Pesanan Selesai</h3>
    <?php
      $selesai1 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pesanan WHERE status='Selesai'"));
      $selesai2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM laporan WHERE status='Selesai'"));
      $selesai_total = $selesai1 + $selesai2;
      echo "<p>$selesai_total</p>";
    ?>
  </div>

  <div class="card">
    <h3>Pesanan Dibatalkan</h3>
    <?php
      $batal1 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pesanan WHERE status='Dibatalkan'"));
      $batal2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM laporan WHERE status='Dibatalkan'"));
      $batal_total = $batal1 + $batal2;
      echo "<p>$batal_total</p>";
    ?>
  </div>
</section>

    <section class="table-section">
      <h2>Daftar Pesanan</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No.Telpon</th>
            <th>Alamat</th>
            <th>Total Harga</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Detail</th>
          </tr>
        </thead>
        <tbody>
        <?php
$query = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY tanggal DESC");

while ($pesanan = mysqli_fetch_assoc($query)) {

    echo "<tr>";
    echo "<td>".$pesanan['id']."</td>";
    echo "<td>".$pesanan['nama_pembeli']."</td>";
    echo "<td>".$pesanan['email']."</td>";
    echo "<td>".$pesanan['alamat']."</td>";
    echo "<td>Rp".number_format($pesanan['total_harga'],0,',','.')."</td>";
    echo "<td>".$pesanan['metode_pembayaran']."</td>";
    echo "<td class='status ".$pesanan['status']."'>".$pesanan['status']."</td>";
    echo "<td>".$pesanan['tanggal']."</td>";

    echo "<td>";
    $status = trim($pesanan['status']); 

    if (strcasecmp($status, 'Selesai') == 0) {
        echo "<span style='font-size:22px; color:#00ff88;'>✔</span>";

    } elseif ($status == 'Pending') {
        ?>

        <form action="ubah_status.php" method="POST" style="display:inline-block;margin-bottom:5px;">
            <input type="hidden" name="id" value="<?= $pesanan['id'] ?>">
            <input type="hidden" name="nama_pembeli" value="<?= $pesanan['nama_pembeli'] ?>">
            <input type="hidden" name="email" value="<?= $pesanan['email'] ?>">
            <button type="submit" name="selesai" class="btn-detail" style="background:#00c853;">Selesai</button>
        </form>

        <form action="ubah_status.php" method="POST" style="display:inline-block;">
            <input type="hidden" name="id" value="<?= $pesanan['id'] ?>">
            <input type="hidden" name="nama_pembeli" value="<?= $pesanan['nama_pembeli'] ?>">
            <input type="hidden" name="email" value="<?= $pesanan['email'] ?>">
            <button type="submit" name="batalkan" class="btn-detail" style="background:#e53935;">Batalkan</button>
        </form>

        <?php

    } else {
        echo "<a href='detail_pesanan.php?id=".$pesanan['id']."' class='btn-detail'>Lihat</a>";
    }

    echo "</td>";
    echo "</tr>";

}

?>

        </tbody>
      </table>
    </section>
  </div>
</body>
</html>
