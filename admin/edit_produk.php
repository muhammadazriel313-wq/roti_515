<?php
include('../database/koneksi.php');

if(!isset($_GET['id'])){
  echo "<script>alert('ID produk tidak ditemukan'); window.location='produk.php';</script>";
  exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
  echo "<script>alert('Produk tidak ditemukan'); window.location='produk.php';</script>";
  exit;
}

// Proses update
if(isset($_POST['update'])){
  $nama_produk = $_POST['nama_produk'];
  $deskripsi = $_POST['deskripsi'];
  $status = $_POST['status'];

  if($_FILES['gambar']['name'] != ''){
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = "../uploads/" . $gambar;
    move_uploaded_file($tmp, $path);
  } else {
    $gambar = $data['gambar'];
  }

  $update = mysqli_query($koneksi, "UPDATE produk 
    SET nama_produk='$nama_produk', deskripsi='$deskripsi', gambar='$gambar', status='$status'
    WHERE id='$id'");

  if($update){
    echo "<script>alert('✅ Produk berhasil diperbarui!'); window.location='produk.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal memperbarui produk!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk - Admin Roti 515</title>
<link rel="stylesheet" href="style.css">
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #0d0d0d;
    color: #f5f5f5;
    margin: 0;
    display: flex;
  }

  .sidebar {
    width: 230px;
    background-color: #1a1a1a;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.3);
  }
  .sidebar h2 {
    color: #fff;
    margin-bottom: 30px;
  }
  .sidebar span {
    color: #ff6600;
  }
  .sidebar ul {
    list-style: none;
    padding: 0;
    width: 100%;
  }
  .sidebar ul li {
    width: 100%;
    text-align: center;
    margin: 10px 0;
  }
  .sidebar ul li a {
    text-decoration: none;
    color: #ccc;
    display: block;
    padding: 10px 0;
    transition: 0.3s;
  }
  .sidebar ul li a.active, 
  .sidebar ul li a:hover {
    background-color: #ff6600;
    color: #fff;
    border-radius: 8px;
  }
  .sidebar ul li a.logout {
    color: #ff4444;
  }

  .main-content {
    margin-left: 250px;
    padding: 40px;
    width: calc(100% - 250px);
  }

  header h1 {
    color: #ff6600;
    margin-bottom: 10px;
  }

  header p {
    color: #aaa;
    margin-bottom: 25px;
  }

  .form-container {
    background-color: #1a1a1a;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(255,255,255,0.05);
    width: 100%;
    max-width: 600px;
  }

  form label {
    display: block;
    margin-top: 15px;
    color: #ddd;
    font-weight: 500;
  }

  form input, form textarea, form select {
    width: 100%;
    background-color: #0d0d0d;
    color: #fff;
    border: 1px solid #333;
    padding: 10px;
    border-radius: 8px;
    margin-top: 5px;
    outline: none;
    transition: 0.2s;
  }
  form input:focus, form textarea:focus, form select:focus {
    border-color: #ff6600;
  }

  .form-container img {
    margin-top: 10px;
    border-radius: 10px;
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 2px solid #333;
  }

  .btn-update, .btn-kembali {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s;
  }

  .btn-update {
    background-color: #ff6600;
    color: #fff;
  }
  .btn-update:hover {
    background-color: #e65c00;
  }

  .btn-kembali {
    background-color: transparent;
    border: 1px solid #ff6600;
    color: #ff6600;
    margin-left: 10px;
  }
  .btn-kembali:hover {
    background-color: #ff6600;
    color: #fff;
  }
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
      <h1>Edit Produk</h1>
      <p>Perbarui detail produk kamu di sini ✏</p>
    </header>

    <div class="form-container">
      <form method="POST" enctype="multipart/form-data">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" value="<?= $data['nama_produk']; ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required><?= $data['deskripsi']; ?></textarea>

        <label>Gambar Produk (kosongkan jika tidak diganti)</label>
        <input type="file" name="gambar" accept="image/*">
        <img src="../uploads/<?= $data['gambar']; ?>" alt="<?= $data['nama_produk']; ?>">

        <label>Status Produk</label>
        <select name="status" required>
          <option value="Proses" <?= $data['status']=='Proses'?'selected':''; ?>>Proses</option>
          <option value="Tersedia" <?= $data['status']=='Tersedia'?'selected':''; ?>>Tersedia</option>
          <option value="Habis" <?= $data['status']=='Habis'?'selected':''; ?>>Habis</option>
        </select>

        <button type="submit" name="update" class="btn-update">💾 Perbarui Produk</button>
        <a href="produk.php" class="btn-kembali">⬅️ Kembali</a>
        <a href="hapus_produk.php?id=<?= $data['id'] ?>" 
          onclick="return confirm('Yakin ingin menghapus produk ini dari daftar produk?')"
          class="btn-kembali"
          style="background:#b30000; color:#fff; border:none; margin-left:10px;">
          🗑 Hapus Produk
        </a>

      </form>
    </div>
  </div>
</body>
</html>
