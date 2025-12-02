<?php
// Tidak perlu koneksi database karena halaman ini statis
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontak - Roti 515</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="satu.css">
  <script defer src="satu.js"></script>
  <style>
    /* Tambahan khusus untuk bot */
    #bot-box {
      display: none;
      background: #fff7f0;
      border-radius: 10px;
      padding: 15px;
      max-width: 400px;
      margin: 20px auto 0;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      font-weight: 600;
      color: #333;
    }
      .navbar img {
      width: 50px;
      height: 50px;
    }

    .logo {
      margin-right: 565px;
      margin-top: 18px;
    }
  </style>
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
  <!-- Kontak Section -->
  <section id="kontak" class="kontak section">
    <h2>Hubungi Kami</h2>
    <p>WhatsApp: 
      <a href="https://wa.me/6285940947728" target="_blank" rel="noopener noreferrer">
        +62 859-4094-7728</a>
    </p>
    <p>Instagram: 
      <a href="https://www.instagram.com/roti_515" target="_blank" rel="noopener noreferrer">
        @roti_515</a>
      <p>Tik Tok:
        <a href="https://www.tiktok.com/@roti_515" target="_blank" rel="noopener noreferrer">
          @roti_515</a>
    </p>

    <!-- Google Map Start -->
    <iframe 
      class="google-map"
      src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3269.987988573515!2d112.05252507500302!3d-7.58769999242689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMzUnMTUuNyJTIDExMsKwMDMnMTguNCJF!5e1!3m2!1sid!2sid!4v1762795949101!5m2!1sid!2sid" 
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <!-- Google Map End -->

    <!-- Form Kritik & Saran -->
    <div class="form-wrapper">
      <h2>Kritik & Saran</h2>
      <p>Kami sangat menghargai masukan kamu agar Roti 515 terus berkembang✨</p>
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
      <button id="kirimBtn">Kirim</button>

      <!-- Respon Otomatis -->
      <div id="bot-box">
        <p id="bot-response"></p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Roti 515. Semua Hak Dilindungi.</p>
  </footer>

  <!-- Tombol Back to Top -->
  <button id="backToTop">⬆</button>

  <div class="float-social">
  <a href="https://wa.me/6285940947728" class="fs-btn">
      <img src="wa.logo.png" alt="WA">
  </a></div>

  <!-- Script Bot -->
  <script>
    document.getElementById('kirimBtn').addEventListener('click', () => {
      const nama = document.getElementById('nama').value.trim();
      const pesan = document.getElementById('pesan').value.trim();
      const botBox = document.getElementById('bot-box');
      const botResponse = document.getElementById('bot-response');

      if (!nama || !pesan) {
        alert('Mohon isi nama dan pesan terlebih dahulu!');
        return;
      }

      // Tampilkan bot-box
      botBox.style.display = 'block';

      // Respon otomatis
      const responses = [
        'Terima kasih atas masukannya!',
        'Pesan kamu sudah kami catat 😊',
        'Kami menghargai kritik & saran kamu!',
        'Masukan kamu akan kami gunakan untuk meningkatkan layanan!',
        'Wow, terima kasih! Kami akan perhatikan hal ini!'
      ];

      // Pilih random response
      const randomIndex = Math.floor(Math.random() * responses.length);
      botResponse.textContent = `Halo ${nama}, ${responses[randomIndex]}`;

      // Kosongkan form setelah submit
      document.getElementById('nama').value = '';
      document.getElementById('pesan').value = '';
    });
  </script>
</body>
</html>
