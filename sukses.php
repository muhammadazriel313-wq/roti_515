<?php
include 'admin/koneksi.php';

if (isset($_POST['kirim'])) {
  $nama = $_POST['nama'];
  $no_hp = $_POST['no_hp'];
  $catatan = $_POST['catatan'];
  $produk_id = $_POST['produk_id'];
  $jumlah = $_POST['jumlah'];

  // Simpan ke tabel pesanan
  mysqli_query($koneksi, "INSERT INTO pesanan (nama_customer, no_hp, catatan, produk_id, jumlah) 
                          VALUES ('$nama', '$no_hp', '$catatan', '$produk_id', '$jumlah')");

  // Kurangi stok produk
  mysqli_query($koneksi, "UPDATE produk SET stok = stok - $jumlah WHERE id = $produk_id");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesanan Berhasil</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="center">
  <h2>🎉 Pesanan kamu berhasil dikirim!</h2>
  <a href="index.php" class="btn">Kembali ke Beranda</a>
</body>
</html>
