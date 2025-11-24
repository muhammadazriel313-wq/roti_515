<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');     

// Ambil nomor telepon dari URL
$telp = isset($_GET['telp']) ? mysqli_real_escape_string($koneksi, $_GET['telp']) : '';

echo "<h2>DEBUG INFO</h2>";

// Cek telp
var_dump($_GET['telp']);
echo "<hr>";

// Kalau telp kosong
if(empty($telp)){
    die("No. Telp pelanggan tidak dikirim lewat URL!");
}

// Query ke tabel pelanggan
$query = "SELECT * FROM pelanggan WHERE email='$telp'";
$result = mysqli_query($koneksi, $query);
$pelanggan = mysqli_fetch_assoc($result);

// Debug query dan hasil
echo "<strong>Query ke pelanggan:</strong> $query<br>";
echo "<strong>Hasil query pelanggan:</strong><br>";
var_dump($pelanggan);
echo "<hr>";

// Kalau tidak ditemukan di pelanggan, ambil dari pesanan
if(!$pelanggan){
    $query2 = "SELECT nama_pembeli AS nama, email, 'Perorangan' AS jenis, CURRENT_TIMESTAMP() AS tanggal_daftar, 'Aktif' AS status 
               FROM pesanan WHERE email='$telp' LIMIT 1";
    $result2 = mysqli_query($koneksi, $query2);
    $pelanggan = mysqli_fetch_assoc($result2);

    echo "<strong>Query ke pesanan:</strong> $query2<br>";
    echo "<strong>Hasil query pesanan:</strong><br>";
    var_dump($pelanggan);
    echo "<hr>";
}

// Kalau tetap tidak ada
if(!$pelanggan){
    echo "<strong>Data pelanggan dengan No. Telp $telp tidak ditemukan di kedua tabel!</strong>";
    exit;
}

echo "<strong>Data pelanggan yang berhasil diambil:</strong><br>";
var_dump($pelanggan);
?>
