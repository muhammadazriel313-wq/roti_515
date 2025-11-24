<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

if(!isset($_GET['telp'])){
    echo "No. Telp pelanggan tidak ditemukan!";
    exit;
}

 $telp = $_GET['telp'];

// Ambil data pelanggan dari tabel pelanggan
 $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE email='$telp'");
 $pelanggan = mysqli_fetch_assoc($query);

// Jika pelanggan tidak ada di tabel pelanggan, ambil dari pesanan dan insert ke tabel pelanggan
if(!$pelanggan){
    $query2 = mysqli_query($koneksi, "SELECT nama_pembeli AS nama, email, 'Perorangan' AS jenis, CURRENT_TIMESTAMP() AS tanggal_daftar, 'Aktif' AS status FROM pesanan WHERE email='$telp' LIMIT 1");
    $data_pesanan = mysqli_fetch_assoc($query2);

    if(!$data_pesanan){
        echo "Pelanggan tidak ditemukan di tabel pelanggan maupun pesanan!";
        exit;
    }

    // Insert ke tabel pelanggan
    $insert = mysqli_query($koneksi, "INSERT INTO pelanggan (nama, email, jenis, tanggal_daftar, status) VALUES 
        ('{$data_pesanan['nama']}', '{$data_pesanan['email']}', '{$data_pesanan['jenis']}', '{$data_pesanan['tanggal_daftar']}', '{$data_pesanan['status']}')");

    if(!$insert){
        die("Gagal menambahkan pelanggan baru: " . mysqli_error($koneksi));
    }

    // Ambil data pelanggan yang baru di-insert
    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE email='$telp'");
    $pelanggan = mysqli_fetch_assoc($query);
}

// Ubah status pelanggan jika tombol diklik
if(isset($_POST['ubah_status'])){
    $status_baru = $_POST['status'];
    $update = mysqli_query($koneksi, "UPDATE pelanggan SET status='$status_baru' WHERE email='$telp'");
    if($update){
        header("Location: detail_pelanggan.php?telp=$telp");
        exit;
    } else {
        echo "Gagal mengubah status: " . mysqli_error($koneksi);
    }
}

// Hapus pelanggan jika tombol hapus diklik
if(isset($_POST['hapus_pelanggan'])){
    // Hapus dari tabel pelanggan
    $delete = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE email='$telp'");
    
    if($delete){
        header("Location: pelanggan.php");
        exit;
    } else {
        echo "Gagal menghapus pelanggan: " . mysqli_error($koneksi);
    }
}

// Tentukan tampilan tombol
 $status_sekarang = $pelanggan['status'] ?? 'Aktif';
 $status_baru = ($status_sekarang == 'Aktif') ? 'Nonaktif' : 'Aktif';
 $btn_class = ($status_sekarang == 'Aktif') ? 'nonaktif' : 'aktif';
 $btn_text  = ($status_sekarang == 'Aktif') ? 'Nonaktifkan' : 'Aktifkan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pelanggan - <?= htmlspecialchars($pelanggan['nama']) ?></title>
<style>
body { 
    font-family:sans-serif; 
    margin:20px; 
    background-color: #b3781fff; /* Background sederhana */
}
.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 0 auto;
}
.btn-status { 
    padding:7px 12px; 
    border:none; 
    border-radius:5px; 
    color:#fff; 
    cursor:pointer; 
    font-size:14px; 
    margin-right: 10px;
}
.aktif { background:#28a745; }
.nonaktif { background:#dc3545; }
.hapus { background:#6c757d; } /* Warna untuk tombol hapus */
.button-group {
    margin-top: 20px;
}
</style>
</head>
<body>

<div class="container">
    <h1>Detail Pelanggan</h1>

    <p><strong>Nama:</strong> <?= htmlspecialchars($pelanggan['nama']) ?></p>
    <p><strong>No. Telp:</strong> <?= htmlspecialchars($pelanggan['email']) ?></p>
    <p><strong>Jenis:</strong> <?= htmlspecialchars($pelanggan['jenis']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($status_sekarang) ?></p>
    <p><strong>Tanggal Daftar:</strong> <?= htmlspecialchars($pelanggan['tanggal_daftar']) ?></p>

    <!-- Tombol Aktif / Nonaktif dan Hapus -->
    <div class="button-group">
        <form method="POST" style="display: inline-block;">
            <input type="hidden" name="status" value="<?= $status_baru ?>">
            <button type="submit" name="ubah_status" class="btn-status <?= $btn_class ?>">
                <?= $btn_text ?>
            </button>
        </form>
        
        <a href="hapus_pelanggan.php?email=<?= $pelanggan['email'] ?>" 
            onclick="return confirm('Yakin ingin menghapus pelanggan ini?')"
            class="btn-status hapus">
         Hapus
        </a>

    </div>

    <p style="margin-top: 20px;"><a href="pelanggan.php">Kembali ke daftar pelanggan</a></p>
</div>

</body>
</html>