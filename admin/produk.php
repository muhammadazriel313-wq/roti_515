<?php
session_start();
if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit;
}
include('../database/koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Produk - Admin Roti 515</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #0d0d0d; color: #f5f5f5; margin: 0; display: flex; }
    .sidebar { width: 230px; background-color: #1a1a1a; height: 100vh; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; align-items: center; padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.3); }
    .sidebar h2 { color: #fff; margin-bottom: 30px; }
    .sidebar span { color: #ff6600; }
    .sidebar ul { list-style: none; padding: 0; width: 100%; }
    .sidebar ul li { width: 100%; text-align: center; margin: 10px 0; }
    .sidebar ul li a { text-decoration: none; color: #ccc; display: block; padding: 10px 0; transition: 0.3s; }
    .sidebar ul li a.active, .sidebar ul li a:hover { background-color: #ff6600; color: #fff; border-radius: 8px; }
    .sidebar ul li a.logout { color: #ff4444; }
    .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
    header h1 { color: #ff6600; margin-bottom: 5px; }
    header p { color: #aaa; margin-bottom: 25px; }
    .cards { background-color: #1a1a1a; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(255,255,255,0.05); margin-bottom: 30px; }
    form label { display: block; margin-top: 10px; color: #ddd; }
    form input, form textarea, form select { width: 100%; background-color: #0d0d0d; color: #fff; border: 1px solid #333; padding: 10px; border-radius: 8px; margin-top: 5px; outline: none; transition: 0.2s; }
    form input:focus, form textarea:focus, form select:focus { border-color: #ff6600; }
    button.btn-detail { background-color: #ff6600; border: none; color: #fff; padding: 10px 15px; border-radius: 8px; cursor: pointer; margin-top: 15px; transition: 0.3s; }
    button.btn-detail:hover { background-color: #e65c00; }
    .table-section { background-color: #1a1a1a; padding: 20px; border-radius: 10px; }
    table { width: 100%; border-collapse: collapse; color: #ddd; }
    table th, table td { padding: 12px 10px; border-bottom: 1px solid #333; text-align: left; }
    table th { background-color: #ff6600; color: #fff; }
    table img { border-radius: 10px; width: 70px; height: 70px; object-fit: cover; }
    .status.Proses { color: #ffaa00; font-weight: bold; }
    .status.Aktif { color: #00ff88; font-weight: bold; }
    .status.Mati { color: #ff4444; font-weight: bold; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>ROTI<span>515</span></h2>
    <ul>
      <li><a href="dashboard.php">📦 Pesanan</a></li>
      <li><a href="produk.php" class="active">🍞 Produk</a></li>
      <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h1>Kelola Produk</h1>
      <p>Tambah, lihat, dan kelola produk 🍞</p>
    </header>

    <!-- Form Tambah Produk -->
    <section class="cards">
      <h3>Tambah Produk Baru</h3>
      <form action="tambah_produk.php" method="POST" enctype="multipart/form-data">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required></textarea>

        <label>Harga</label>
        <input type="number" name="harga" required>

        <label>Gambar Produk</label>
        <input type="file" name="gambar" accept="image/*" required>

        <button type="submit" name="submit" class="btn-detail">Tambah Produk</button>
      </form>
    </section>

    <!-- Form Tambah Stok -->
    <section class="cards">
      <h3>Tambah Stok Produk</h3>
      <form method="post">
        <label>Produk:</label>
        <select name="id_produk">
          <?php
          $produkQuery = mysqli_query($koneksi, "SELECT id, nama_produk FROM produk");
          while($p = mysqli_fetch_assoc($produkQuery)){
              echo "<option value='{$p['id']}'>{$p['nama_produk']}</option>";
          }
          ?>
        </select>

        <label>Jumlah Stok:</label>
        <input type="number" name="jumlah" required>

        <button type="submit" name="tambah_stok" class="btn-detail">Tambah Stok</button>
      </form>

      <?php
      if(isset($_POST['tambah_stok'])){
          $id_produk = (int)$_POST['id_produk'];
          $jumlah = (int)$_POST['jumlah'];

          mysqli_query($koneksi, "UPDATE produk SET stok = stok + $jumlah WHERE id = $id_produk");
          echo "<p style='color:#00ff88;margin-top:10px;'>Stok berhasil ditambahkan!</p>";
      }
      ?>
    </section>

    <!-- Daftar Produk dengan Stok -->
    <section class="table-section">
      <h2>Daftar Produk</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Gambar</th>
            <th>Aksi</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $produk = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
          while($p = mysqli_fetch_assoc($produk)){
              echo "<tr>";
              echo "<td>".$p['id']."</td>";
              echo "<td>".$p['nama_produk']."</td>";
              echo "<td>".$p['stok']."</td>";
              echo "<td class='status ".urlencode($p['status'])."'>".$p['status']."</td>";
              echo "<td><img src='../uploads/".$p['gambar']."' alt='".$p['nama_produk']."'></td>";

              echo "<td><a href='ubah_status.php?id=".$p['id']."' class='btn-detail'>";
              echo ($p['status'] == 'Aktif') ? "Matikan ❌" : "Aktifkan ✅";
              echo "</a></td>";

              echo "<td><a href='edit_produk.php?id=".urlencode($p['id'])."' class='btn-detail'>Edit</a></td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </div>
</body>
</html>