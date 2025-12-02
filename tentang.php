<?php
// tidak perlu koneksi database karena halaman ini statis
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Kami - Roti 515</title>
  <link rel="stylesheet" href="style.css" />
  <script defer src="script.js"></script>
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
  <!-- Konten Tentang Kami -->
  <section class="about-page section">
    <div class="about-container">
      <div class="about-image">
        <img id="aboutImage" src="11.jpg" alt="Tentang Kami">
      </div>
      <div class="about-text">
        <h2>TENTANG KAMI</h2>
        <h1>ROTI 515</h1>
        <p id="text" style="text-indent:25px;">
            ROTI 515 merupakan usaha roti kering yang lahir dari semangat menghadirkan cita rasa khas rumahan dengan kualitas premium. Nama 515 sendiri diambil dari singkatan nama pemilik (SIS) yang kemudian dikreasikan menjadi identitas unik dan mudah diingat sebagai ciri khas usaha ini. 
            <span id="moreText">
            Terinspirasi dari resep turun-temurun keluarga, setiap produk yang dihasilkan mengusung cita rasa otentik yang terus dijaga dari generasi ke generasi. Dengan dedikasi tinggi terhadap mutu dan kepuasan pelanggan, ROTI 515 hanya menggunakan bahan-bahan pilihan yang aman, higienis, dan berkualitas. 
            Proses pembuatan dilakukan dengan penuh ketelitian, mulai dari pemilihan bahan dasar, pengolahan adonan, hingga teknik pemanggangan yang memastikan setiap produk memiliki tekstur yang renyah dan rasa yang lezat.
            Kami memproduksi berbagai jenis roti kering yang banyak digemari, seperti pia susu dengan isian lembut yang manis, pia kacang yang kaya rasa, bolu klemben dengan tekstur khas yang empuk-renyah, serta onde-onde ketawa yang terkenal dengan bentuknya yang mengembang dan membawa sensasi gurih manis di setiap gigitan.
            Semua produk ini dirancang agar dapat dinikmati kapan saja, cocok sebagai camilan keluarga, suguhan untuk tamu, hingga pilihan oleh-oleh yang memikat. 
            ROTI 515 terus berinovasi dalam menciptakan varian rasa dan menjaga kualitas produksi agar selalu konsisten. 
            Dengan harapan dapat memperluas pasar, ROTI 515 berkomitmen untuk selalu memberikan produk terbaik yang tidak hanya memanjakan lidah, tetapi juga menciptakan pengalaman kuliner yang menghangatkan hati, sebagaimana cita rasa rumahan yang menjadi dasar dari perjalanan usaha ini.
  </span>
        </p>
        <button id="readMoreBtn" onclick="readMore()">Selengkapnya</button>
      </div>
    </div>
  </section>

  <script>
  // Fitur Selengkapnya
function readMore() {
  let moreText = document.getElementById("moreText");
  let button = document.getElementById("readMoreBtn");

  if (moreText.style.display === "none") {
    moreText.style.display = "inline";
    button.innerText = "Sembunyikan";
  } else {
    moreText.style.display = "none";
    button.innerText = "Selengkapnya";
  }
}
</script>
  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Roti 515. Semua Hak Dilindungi.</p>
  </footer>

  <!-- Tombol Back to Top -->
  <button id="backToTop">⬆</button>
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
