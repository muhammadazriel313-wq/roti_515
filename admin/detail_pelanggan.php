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
$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE no_telpon='$telp'");
$pelanggan = mysqli_fetch_assoc($query);

// Jika pelanggan tidak ada di tabel pelanggan, ambil dari pesanan dan insert ke tabel pelanggan
if(!$pelanggan){
    $query2 = mysqli_query($koneksi, "SELECT nama_pembeli AS nama, no_telpon, 'Perorangan' AS jenis, CURRENT_TIMESTAMP() AS tanggal_daftar, 'Aktif' AS status FROM pesanan WHERE no_telpon='$telp' LIMIT 1");
    $data_pesanan = mysqli_fetch_assoc($query2);

    if(!$data_pesanan){
        echo "Pelanggan tidak ditemukan di tabel pelanggan maupun pesanan!";
        exit;
    }

    // Insert ke tabel pelanggan
    $insert = mysqli_query($koneksi, "INSERT INTO pelanggan (nama, no_telpon, jenis, tanggal_daftar, status) VALUES 
        ('{$data_pesanan['nama']}', '{$data_pesanan['no_telpon']}', '{$data_pesanan['jenis']}', '{$data_pesanan['tanggal_daftar']}', '{$data_pesanan['status']}')");

    if(!$insert){
        die("Gagal menambahkan pelanggan baru: " . mysqli_error($koneksi));
    }

    // Ambil data pelanggan yang baru di-insert
    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE no_telpon='$telp'");
    $pelanggan = mysqli_fetch_assoc($query);
}

// Ubah status pelanggan jika tombol diklik
if(isset($_POST['ubah_status'])){
    $status_baru = $_POST['status'];
    $update = mysqli_query($koneksi, "UPDATE pelanggan SET status='$status_baru' WHERE no_telpon='$telp'");
    if($update){
        header("Location: detail_pelanggan.php?telp=$telp");
        exit;
    } else {
        echo "Gagal mengubah status: " . mysqli_error($koneksi);
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
body { font-family:sans-serif; margin:20px; }
.btn-status { padding:7px 12px; border:none; border-radius:5px; color:#fff; cursor:pointer; font-size:14px; }
.aktif { background:#28a745; }
.nonaktif { background:#dc3545; }
</style>
</head>
<body>

<h1>Detail Pelanggan</h1>

<p><strong>Nama:</strong> <?= htmlspecialchars($pelanggan['nama']) ?></p>
<p><strong>No. Telp:</strong> <?= htmlspecialchars($pelanggan['no_telpon']) ?></p>
<p><strong>Jenis:</strong> <?= htmlspecialchars($pelanggan['jenis']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($status_sekarang) ?></p>
<p><strong>Tanggal Daftar:</strong> <?= htmlspecialchars($pelanggan['tanggal_daftar']) ?></p>

<!-- Tombol Aktif / Nonaktif -->
<form method="POST">
    <input type="hidden" name="status" value="<?= $status_baru ?>">
    <button type="submit" name="ubah_status" class="btn-status <?= $btn_class ?>">
        <?= $btn_text ?>
    </button>
</form>

<p><a href="pelanggan.php">Kembali ke daftar pelanggan</a></p>

</body>
</html>
