<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

if(!isset($_GET['email'])){
    echo "Email tidak ditemukan!";
    exit;
}

$email = $_GET['email'];

// 1. Ambil semua id pesanan milik pelanggan ini
$getPesanan = mysqli_query($koneksi, "SELECT id FROM pesanan WHERE email='$email'");

while($p = mysqli_fetch_assoc($getPesanan)){
    $id_pesanan = $p['id'];

    // 2. Hapus detail pesanan
    mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan='$id_pesanan'");

    // 3. Hapus pesanan utama
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id='$id_pesanan'");
}

// 4. Hapus pelanggan
$delete = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE email='$email'");

if($delete){
    echo "<script>
            alert('Pelanggan & semua pesanan terkait berhasil dihapus permanen!');
            window.location='pelanggan.php';
          </script>";
} else {
    echo "Gagal menghapus pelanggan: " . mysqli_error($koneksi);
}
