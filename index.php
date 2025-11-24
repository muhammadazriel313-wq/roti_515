<?php
include 'database/koneksi.php';
$result = mysqli_query($koneksi, "SELECT * FROM produk");

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MewahSnacks</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>MewahSnacks</h1>
    <p>Makanan ringan kekinian dari UMKM lokal 🍪</p>
  </header>

  <section class="produk">
    <?php while($p = mysqli_fetch_assoc($produk)) { ?>
      <div class="card">
        <img src="images/<?php echo $p['gambar']; ?>" alt="">
        <h3><?php echo $p['nama']; ?></h3>
        <p><?php echo $p['deskripsi']; ?></p>
        <p class="harga">Rp <?php echo number_format($p['harga']); ?></p>
        <p class="stok">Stok: <?php echo $p['stok']; ?></p>
        <a href="pesan.php?id=<?php echo $p['id']; ?>" class="btn">Pesan Sekarang</a>
      </div>
    <?php } ?>
  </section>
</body>
</html>
