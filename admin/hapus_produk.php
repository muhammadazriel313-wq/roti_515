<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0){
    die("ID produk tidak valid.");
}

// Hapus gambar produk dari folder
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id='$id'"));
if($data && file_exists("../uploads/" . $data['gambar'])){
    unlink("../uploads/" . $data['gambar']);
}

// Hapus produk
$hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE id='$id'");

if($hapus){
    echo "<script>
        alert('Produk berhasil dihapus!');
        window.location='produk.php';
    </script>";
} else {
    die('Gagal menghapus: ' . mysqli_error($koneksi));
}
?>
