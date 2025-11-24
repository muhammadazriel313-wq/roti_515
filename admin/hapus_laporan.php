<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Hapus laporan utama
    mysqli_query($koneksi, "DELETE FROM laporan WHERE id = '$id'");

    // Hapus detail laporan (produk-produk yang terjual)
    mysqli_query($koneksi, "DELETE FROM detail_laporan WHERE id_laporan = '$id'");
}

header("Location: laporan.php");
exit;

if(!isset($_GET['id'])){
    echo "<script>alert('ID laporan tidak ditemukan!'); window.location='laporan.php';</script>";
    exit;
}

$id = (int) $_GET['id'];

// Hapus laporan utama
$del = mysqli_query($koneksi, "DELETE FROM laporan WHERE id=$id");

if($del){

    // Hapus detail laporan
    mysqli_query($koneksi, "DELETE FROM detail_laporan WHERE id_laporan=$id");

    echo "<script>alert('Laporan berhasil dihapus.'); window.location='laporan.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus laporan'); window.location='laporan.php';</script>";
}
?>
