<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

// Ambil tanggal reset terakhir
 $trackerQuery = mysqli_query($koneksi, "SELECT last_reset_date FROM pendapatan_tracker WHERE id = 1");
 $tracker = mysqli_fetch_assoc($trackerQuery);
 $lastResetDate = $tracker['last_reset_date'];

// Buat query untuk menghitung total pendapatan sejak terakhir reset
 $sumQuery = "SELECT SUM(total_harga) AS total_pendapatan FROM laporan";
if ($lastResetDate) {
    $sumQuery .= " WHERE tanggal > '$lastResetDate'";
}

 $totalPendapatanQuery = mysqli_query($koneksi, $sumQuery);
 $totalPendapatan = mysqli_fetch_assoc($totalPendapatanQuery)['total_pendapatan'];

// Jika ada pendapatan, simpan ke riwayat dan update tanggal reset
if ($totalPendapatan > 0) {
    // 1. Simpan total pendapatan saat ini ke tabel riwayat
    mysqli_query($koneksi, "INSERT INTO riwayat_pendapatan (total_pendapatan) VALUES ('$totalPendapatan')");

    // 2. Update tanggal reset menjadi sekarang
    mysqli_query($koneksi, "UPDATE pendapatan_tracker SET last_reset_date = NOW() WHERE id = 1");
}

// Kembali ke halaman laporan
header("Location: laporan.php");
exit;
?>