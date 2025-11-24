<?php
include('../database/koneksi.php');

if (isset($_POST['submit'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $harga       = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $status = 'Proses'; // Status default

    $namaFile = $_FILES['gambar']['name'];
    $tmpFile  = $_FILES['gambar']['tmp_name'];
    $folderTujuan = "../uploads/" . basename($namaFile);

    if (move_uploaded_file($tmpFile, $folderTujuan)) {
        $query = "INSERT INTO produk (nama_produk, deskripsi, harga, gambar, status) 
                  VALUES ('$nama_produk', '$deskripsi', '$harga', '$namaFile', '$status')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('✅ Produk berhasil ditambahkan!');window.location='produk.php';</script>";
        } else {
            echo "❌ Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    } else {
        echo "⚠️ Upload gambar gagal!";
    }
}
?>
