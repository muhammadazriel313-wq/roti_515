<?php
session_start(); // wajib untuk pakai session
include('../database/koneksi.php');

if(isset($_POST['tambah_stok'])){
    $id_produk = (int)$_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];

    // Update stok
    mysqli_query($koneksi, "
        UPDATE produk 
        SET stok = stok + $jumlah 
        WHERE id = $id_produk
    ");

    // Set flash message
    $_SESSION['flash_stok'] = "Stok berhasil ditambahkan!";

    // Redirect ke produk.php tanpa GET parameter
    header("Location: produk.php");
    exit;
}
?>
