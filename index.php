<?php
include('database/koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Roti 515</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="satu.css" />
  <script defer src="script.js"></script>
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


  <!-- Hero Section dengan Image Slider -->
  <section id="home" class="hero-desktop">
    <div class="image-slider">
  <!-- Wrapper yang akan digeser -->
  <div class="slider-wrapper">
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (1).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (2).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (3).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (4).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (5).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (6).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (7).mp4" autoplay muted loop class="video/mp4">
    </div>
    <div class="image-slide">
      <video src="White Black Refined Y2K Slideshow Video (8).mp4" autoplay muted loop class="video/mp4">
    </div>
        <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
  </div>

  <!-- Tombol Navigasi -->
  <div class="slider-controls">
    <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
  </div>
  <div class="slider-dots">
  <div class="dot active"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>
  </section>
  <section class="hero-mobile">
  <div class="photo-slider">
    <img src="FOTO1.jpg" class="photo-slide">
    <img src="FOTO2.jpg" class="photo-slide">
    <img src="Foto3.jpg" class="photo-slide">
  </div>
</section>



  <!-- About Section -->
<section id="about" class="about section">
  <div class="about-container">
    
    <!-- KIRI: Kartu Rekomendasi Owner -->
    <div class="owner-card">
      <img src="owner 515.png" alt="Owner Roti 515" class="owner-photo">
      <h3>Siswanto</h3>
      <p class="owner-title">Pemilik Roti 515</p>
      <blockquote class="recommendation">
        "Komitmen kami adalah menghadirkan roti kering dengan resep tradisional dan bahan pilihan. Setiap gigitan adalah cerminan dedikasi kami untuk memberikan yang terbaik bagi keluarga Indonesia."
      </blockquote>
    </div>

    <!-- KANAN: Teks Tentang Kami -->
    <div class="about-text">
      <h2>Tentang Kami</h2>
      <h1>ROTI 515</h1>
      <p>Usaha roti kering yang dikelola oleh narasumber diberi nama 515, penamaan ini diambil dari nama pemilik yaitu Siswanto (SIS) sebagai identitas unik (515). UMKM ini memproduksi berbagai produk roti kering, antara lain Roti Susu, Pia Kacang, Bolu Plemben, Onde-Onde Ketawa, Roti Marie Kelapa, Roti Wijen.</p>
      <p>Pemilik menyampaikan bahwa motivasi utama memulai usaha adalah untuk menciptakan lapangan kerja serta tetap dekat dengan keluarga tanpa harus bekerja jauh dari kampung halaman.</p>
    </div>
  </div>
</section>

  <!-- Produk Section -->
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

<!-- Kontak Section -->
  <section id="kontak" class="kontak section">
  <h2>Hubungi Kami</h2>
  <p>WhatsApp: <a href="https://wa.me/6285940947728" target="_blank" rel="noopener noreferrer">+62 859-4094-7728</a></p>
  <p>Instagram: <a href="https://www.instagram.com/roti_515" target="_blank" rel="noopener noreferrer">@roti_515</a></p></section>

  <!-- Google Map Start -->
  <iframe 
  class="google-map"
  src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3269.987988573515!2d112.05252507500302!3d-7.58769999242689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMzUnMTUuNyJTIDExMsKwMDMnMTguNCJF!5e1!3m2!1sid!2sid!4v1762795949101!5m2!1sid!2sid" 
  allowfullscreen=""
  loading="lazy"
  referrerpolicy="no-referrer-when-downgrade">
</iframe>
<!-- Google Map End -->

<!-- Kritik & Saran -->
  <section id="kontak" class="kontak section">
    <h2>Kritik & Saran</h2>
    <p>Kami sangat menghargai masukan kamu agar Roti 515 terus berkembang✨</p>

    <!-- Form Kritik dan Saran -->
    <table class="form-tabel">
      <tr>
        <td><label for="nama">Nama</label></td>
        <td><input type="text" id="nama" placeholder="Masukkan nama kamu..." required></td>
      </tr>
      <tr>
        <td><label for="pesan">Pesan</label></td>
        <td><textarea id="pesan" rows="3" placeholder="Tulis kritik atau saran kamu..." required></textarea></td>
      </tr>
    </table>

    <!-- Tombol Kirim -->
    <button id="kirimBtn">Kirim</button>

    <!-- Respon Otomatis -->
    <div id="bot-box">
      <p id="bot-response"></p>
    </div>
</section>

<div class="admin">
   <a href="admin/login.php" class="btn">Login Admin</a>
</div>
  <footer>
    <p>&copy; 2025 Roti515. Semua Hak Dilindungi.</p>
  </footer>

  <!-- Tombol Back to Top -->
  <button id="backToTop">⬆</button>

  <div class="float-social">
  <a href="https://wa.me/6285940947728" class="fs-btn">
      <img src="wa.logo.png" alt="WA">
  </a></div>
  <script src="satu.js"></script>
</body>
</html>