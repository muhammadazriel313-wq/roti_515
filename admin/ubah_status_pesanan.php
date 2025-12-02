<?php
session_start();
include('../database/koneksi.php');

$id = $_POST['id'];
$nama = $_POST['nama_pembeli'];
$telp = $_POST['no_telpon'];

// ==================== FUNGSI: PINDAHKAN KE LAPORAN ====================
function pindahkanKeLaporan($koneksi, $id) {

    // AMBIL DATA PESANAN
    $pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, 
        "SELECT * FROM pesanan WHERE id='$id'"
    ));

    // SIMPAN KE TABEL LAPORAN
    mysqli_query($koneksi, "
        INSERT INTO laporan 
        (id, nama_pembeli, no_telpon, alamat, total_harga, metode_pembayaran, status, tanggal)
        VALUES 
        ('{$pesanan['id']}', '{$pesanan['nama_pembeli']}', '{$pesanan['no_telpon']}', 
        '{$pesanan['alamat']}', '{$pesanan['total_harga']}', '{$pesanan['metode_pembayaran']}', 
        'Selesai', '{$pesanan['tanggal']}')
    ");

    // PINDAHKAN DETAILNYA
    $detail = mysqli_query($koneksi, "SELECT * FROM detail_pesanan WHERE id_pesanan='$id'");
    while ($d = mysqli_fetch_assoc($detail)) {
        mysqli_query($koneksi, "
            INSERT INTO detail_laporan 
            (id_pesanan, id_produk, nama_produk, jumlah, harga, subtotal)
            VALUES 
            ('{$d['id_pesanan']}', '{$d['id_produk']}', '{$d['nama_produk']}', 
            '{$d['qty']}', '{$d['harga']}', '{$d['subtotal']}')
        ");
    }

    // HAPUS DETAIL PESANAN
    mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan='$id'");

    // HAPUS PESANAN
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id='$id'");
}


// ==================== SELESAIKAN ====================
if (isset($_POST['selesai'])) {

    pindahkanKeLaporan($koneksi, $id);

    header("Location: dashboard.php?selesai=1");
    exit;
}


// ==================== BATALKAN ====================
if (isset($_POST['batalkan'])) {

    // UPDATE STATUS PESANAN MENJADI DIBATALKAN
    mysqli_query($koneksi, "UPDATE pesanan SET status='Dibatalkan' WHERE id='$id'");

    // HAPUS PELANGGAN TERKAIT
    mysqli_query($koneksi, "
        DELETE FROM pelanggan 
        WHERE nama='$nama' AND no_telpon='$telp'
    ");

    header("Location: dashboard.php?batal=1");
    exit;
}

?>
