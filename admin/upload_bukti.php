<?php
include('../database/koneksi.php');

if (isset($_POST['upload'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $namaFile = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];
    $folder = "../uploads/bukti/" . $namaFile;

    if (move_uploaded_file($tmp, $folder)) {
        // Simpan nama file ke database
        mysqli_query($koneksi, "UPDATE pesanan SET bukti_pembayaran='$namaFile' WHERE id='$id_pesanan'");
        echo "<script>alert('Bukti pembayaran berhasil dikirim!'); window.location='riwayat_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal mengunggah bukti pembayaran!');</script>";
    }
}
?>
