<?php
include('database/koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk Kami - Roti 515</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="satu.css">
  <script defer src="satu.js"></script>
</head>
<body>
 <!-- Header -->
 <header class="navbar">

  <!-- BAGIAN YANG BISA DIKLIK -->
  <div class="nav-toggle" id="toggleMenu">
      <img src="logo 515.png" id="logoMenu">
      <div class="logo">ROTI<span>515</span></div>
  </div>
   <!-- Hamburger Menu (mobile only) -->
<div class="hamburger" id="hamburger">
  <span></span>
  <span></span>
  <span></span>
</div>


  <!-- MENU NAVBAR -->
  <nav>
    <ul id="menuList">
      <li><a href="index.php" class="nav-link">Beranda</a></li>
      <li><a href="tentang.php" class="nav-link">Tentang</a></li>
      <li><a href="produk.php" class="nav-link">Produk</a></li>
      <li><a href="kontak.php" class="nav-link">Kontak</a></li>
      <li class="cart-icon">
        <a href="keranjang.php" class="nav-link">🛒 Keranjang</a>
      </li>
    </ul>
  </nav>
</header>

  <!-- Kotak menu mobile overlay -->
<div class="mobile-menu" id="mobileMenu">
  <ul>
    <li><a href="index.php">Beranda</a></li>
    <li><a href="tentang.php">Tentang</a></li>
    <li><a href="produk.php">Produk</a></li>
    <li><a href="kontak.php">Kontak</a></li>
    <li><a href="keranjang.php">🛒 Keranjang</a></li>
  </ul>
</div>
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
  <script>
const hamburger = document.getElementById("hamburger");
const menu = document.querySelector("nav ul");

hamburger.addEventListener("click", () => {
  menu.classList.toggle("show");
  hamburger.classList.toggle("active");
});

// Tutup menu saat klik link
menu.querySelectorAll("a").forEach(link => {
  link.addEventListener("click", () => {
    menu.classList.remove("show");
    hamburger.classList.remove("active");
  });
});
  </script>

</body>
</html>
