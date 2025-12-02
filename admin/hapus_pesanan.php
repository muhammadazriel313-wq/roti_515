<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

if(isset($_POST['id'])){
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    
    // Hapus detail pesanan terlebih dahulu jika ada tabel detail
    mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan = '$id'");
    
    // Hapus pesanan utama
    $hapus = mysqli_query($koneksi, "DELETE FROM pesanan WHERE id = '$id'");
    
    if($hapus){
        header("Location: dashboard.php?hapus=success");
    } else {
        header("Location: dashboard.php?hapus=error");
    }
} else {
    header("Location: dashboard.php");
}
?>