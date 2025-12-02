<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

// Pastikan id ada dan merupakan angka yang valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID laporan tidak valid.";
    header("Location: laporan.php");
    exit;
}

 $id_laporan = (int)$_GET['id'];

// Mulai transaksi untuk memastikan semua query berhasil atau gagal bersama
mysqli_begin_transaction($koneksi);

try {
    // 1. Ambil semua detail produk yang terkait laporan ini untuk mengembalikan stok
    $stmt_detail = mysqli_prepare($koneksi, "SELECT id_produk, jumlah FROM detail_laporan WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt_detail, "i", $id_laporan);
    mysqli_stmt_execute($stmt_detail);
    $result_detail = mysqli_stmt_get_result($stmt_detail);

    while ($d = mysqli_fetch_assoc($result_detail)) {
        $id_produk = $d['id_produk'];
        $jumlah = $d['jumlah'];

        // 2. Kembalikan stok produk
        $stmt_stok = mysqli_prepare($koneksi, "UPDATE produk SET stok = stok + ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_stok, "ii", $jumlah, $id_produk);
        mysqli_stmt_execute($stmt_stok);

        // PERBAIKAN: Query untuk kolom 'terjual' dihapus karena kolom tersebut tidak ada di tabel Anda.
        // Jika Anda menambahkan kolom 'terjual' di masa mendatang, Anda dapat mengaktifkan kembali query ini:
        /*
        $stmt_terjual = mysqli_prepare($koneksi, "UPDATE produk SET terjual = terjual - ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_terjual, "ii", $jumlah, $id_produk);
        mysqli_stmt_execute($stmt_terjual);
        */
    }
    mysqli_stmt_close($stmt_detail);

    // 3. Hapus semua detail laporan
    $stmt_hapus_detail = mysqli_prepare($koneksi, "DELETE FROM detail_laporan WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt_hapus_detail, "i", $id_laporan);
    mysqli_stmt_execute($stmt_hapus_detail);
    mysqli_stmt_close($stmt_hapus_detail);

    // 4. Hapus laporan utamanya
    $stmt_hapus_laporan = mysqli_prepare($koneksi, "DELETE FROM laporan WHERE id = ?");
    mysqli_stmt_bind_param($stmt_hapus_laporan, "i", $id_laporan);
    mysqli_stmt_execute($stmt_hapus_laporan);
    mysqli_stmt_close($stmt_hapus_laporan);

    // Jika semua query berhasil, commit transaksi
    mysqli_commit($koneksi);

    // Set pesan sukses ke session
    $_SESSION['success'] = "Laporan berhasil dihapus dan stok telah dikembalikan.";
    header("Location: laporan.php");
    exit;

} catch (Exception $e) {
    // Jika ada error, rollback transaksi
    mysqli_rollback($koneksi);

    // Log error untuk debugging
    error_log("Gagal menghapus laporan ID $id_laporan: " . $e->getMessage());

    // Set pesan error ke session
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus laporan. Silakan coba lagi.";
    header("Location: laporan.php");
    exit;
}
?>