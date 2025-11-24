<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

$id = $_GET['id'];

// HAPUS DETAIL PESANAN DULU (WAJIB)
mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan='$id'");

// Baru hapus pesanan induknya
mysqli_query($koneksi, "DELETE FROM pesanan WHERE id='$id'");

echo "<script>
alert('Pesanan berhasil dihapus permanen!');
window.location='dashboard.php';
</script>";
?>
