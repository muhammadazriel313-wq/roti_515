<?php
include('../database/koneksi.php');

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Ambil status produk saat ini
    $produk = mysqli_query($koneksi, "SELECT status FROM produk WHERE id='$id'");
    $p = mysqli_fetch_assoc($produk);

    // Tentukan status baru
    $new_status = ($p['status'] == 'Aktif') ? 'Mati' : 'Aktif';

    // Update status di database
    mysqli_query($koneksi, "UPDATE produk SET status='$new_status' WHERE id='$id'");

    // Langsung redirect tanpa popup
    header("Location: produk.php");
    exit;
}
?>
