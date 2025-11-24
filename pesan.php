<?php
include 'admin/koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id=$id");
$p = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesan Produk - ROTI515</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-box">
    <h2>Pesan Produk</h2>
    <form method="POST" action="sukses.php">
      <input type="hidden" name="produk_id" value="<?php echo $p['id']; ?>">
      <p><b>Produk:</b> <?php echo $p['nama']; ?></p>
      <label>Nama</label>
      <input type="text" name="nama" required>

      <label>No HP</label>
      <input type="text" name="no_hp" required>

      <label>Jumlah</label>
      <input type="number" name="jumlah" min="1" max="<?php echo $p['stok']; ?>" required>

      <label>Catatan</label>
      <textarea name="catatan" placeholder="Contoh: tanpa pedas, bungkus kecil, dll"></textarea>

      <button type="submit" name="kirim">Kirim Pesanan</button>
    </form>
  </div>
</body>
</html>
