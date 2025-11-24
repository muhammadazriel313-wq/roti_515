<?php
include('../database/koneksi.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Ambil data pesanan
    $query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id='$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Tambahkan ke tabel laporan
        $insert = mysqli_query($koneksi, "INSERT INTO laporan 
          (nama_pembeli, email, alamat, total_harga, metode_pembayaran, status, tanggal)
          VALUES (
            '".$data['nama_pembeli']."',
            '".$data['email']."',
            '".$data['alamat']."',
            '".$data['total_harga']."',
            '".$data['metode_pembayaran']."',
            'Selesai',
            NOW()
          )");

        if ($insert) {
            // Update status pesanan jadi selesai (agar dashboard bisa menghitungnya)
            mysqli_query($koneksi, "UPDATE pesanan SET status='Selesai' WHERE id='$id'");

            // Hapus detail dulu biar gak bentrok foreign key
            mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan='$id'");

            // Lalu hapus pesanan dari tabel utama
            mysqli_query($koneksi, "DELETE FROM pesanan WHERE id='$id'");
        }
    }

    // Redirect ke dashboard
    header("Location: dashboard.php?selesai=1");
    exit;
} else {
    echo "ID pesanan tidak ditemukan!";
}
?>
