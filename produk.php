<?php
include('database/koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk Kami - Roti 515</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="navbar">
    <div class="logo">ROTI<span>515</span></div>
    <nav>
      <ul>
        <li><a href="index.html">Beranda</a></li>
        <li><a href="tentang.php">Tentang</a></li>
        <li><a href="produk.php" class="active">Produk</a></li>
        <li><a href="kontak.php">Kontak</a></li>
        <li class="cart-icon"><a href="keranjang.php">🛒 Keranjang</a></li>
      </ul>
    </nav>
  </header>

  <section class="produk section">
    <h2>Produk Roti 515</h2>
    <div class="produk-container">
      <?php
      $produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE status='Aktif'");
      while($p = mysqli_fetch_assoc($produk)){
        echo "
          <div class='card'>
            <img src='uploads/".$p['gambar']."' alt='".$p['nama_produk']."'>
            <h3>".$p['nama_produk']."</h3>
            <p>".$p['deskripsi']."</p>
          </div>
        ";
      }
      ?>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Roti 515. Semua Hak Dilindungi.</p>
  </footer>
</body>
</html>
