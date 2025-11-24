<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include('../database/koneksi.php');

/* =====================================================
   FUNGSI: SELESAIKAN PESANAN (kurangi stok)
===================================================== */
if (isset($_POST['selesai'])) {

    $id_pesanan = (int)$_POST['id'];

    // Ambil semua detail pesanan
    $detail = mysqli_query($koneksi, "SELECT * FROM detail_pesanan WHERE id_pesanan=$id_pesanan");

    if (!$detail) {
        die("❌ ERROR QUERY DETAIL: " . mysqli_error($koneksi));
    }

    while ($d = mysqli_fetch_assoc($detail)) {

        $nama_produk = $d['nama_produk']; 
        $qty = (int)$d['qty'];

        if ($qty <= 0) {
            die("❌ ERROR: qty kosong. qty=$qty");
        }

        // Ambil id_produk berdasarkan nama_produk
        $p = mysqli_query($koneksi, 
            "SELECT id FROM produk WHERE nama_produk='$nama_produk' LIMIT 1"
        );

        if (!$p) {
            die("❌ ERROR QUERY PRODUK: " . mysqli_error($koneksi));
        }

        $prod = mysqli_fetch_assoc($p);

        if (!$prod) {
            die("❌ ERROR: nama_produk '$nama_produk' tidak ditemukan di tabel produk!");
        }

        $id_produk = (int)$prod['id'];

        // Kurangi stok
        $up = mysqli_query($koneksi,
            "UPDATE produk SET stok = stok - $qty WHERE id=$id_produk"
        );

        if (!$up) {
            die("❌ ERROR UPDATE STOK: " . mysqli_error($koneksi));
        }
    }

    // Update status menjadi selesai
    $stat = mysqli_query($koneksi,
        "UPDATE pesanan SET status='Selesai' WHERE id=$id_pesanan"
    );

    if (!$stat) {
        die("❌ ERROR UPDATE STATUS: " . mysqli_error($koneksi));
    }

    echo "<script>
            alert('Pesanan selesai! Stok sudah berkurang otomatis.');
            window.location='dashboard.php';
          </script>";
    exit;
}



/* =====================================================
   FUNGSI: PESANAN DIBAYAR
===================================================== */
if (isset($_POST['dibayar'])) {

    $id_pesanan = (int)$_POST['id'];

    $up = mysqli_query($koneksi,
        "UPDATE pesanan SET status='Sudah Dibayar' WHERE id=$id_pesanan"
    );

    if (!$up) {
        die('❌ ERROR UPDATE STATUS DIBAYAR: ' . mysqli_error($koneksi));
    }

    echo "<script>
            alert('Status pesanan diubah menjadi SUDAH DIBAYAR');
            window.location='detail_pesanan.php?id=$id_pesanan';
          </script>";
    exit;
}



/* =====================================================
   FUNGSI: BATALKAN PESANAN
===================================================== */
if (isset($_POST['batalkan'])) {

    $id_pesanan = (int)$_POST['id'];

    $batal = mysqli_query($koneksi,
        "UPDATE pesanan SET status='Dibatalkan' WHERE id=$id_pesanan"
    );

    if (!$batal) {
        die("❌ ERROR UPDATE STATUS BATAL: " . mysqli_error($koneksi));
    }

    echo "<script>alert('Pesanan dibatalkan.'); window.location='dashboard.php';</script>";
    exit;
}
?>



<?php
// =====================================================
// BAGIAN MENGUBAH STATUS PRODUK (TIDAK DIUBAH)
// =====================================================
include('../database/koneksi.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ambil status produk saat ini
    $query = mysqli_query($koneksi, "SELECT status FROM produk WHERE id='$id'");
    $data = mysqli_fetch_assoc($query);

    // ubah statusnya
    $status_baru = ($data['status'] == 'Aktif') ? 'Mati' : 'Aktif';

    // update database
    $update = mysqli_query($koneksi, "UPDATE produk SET status='$status_baru' WHERE id='$id'");

    if ($update) {
        echo "<script>alert('Status produk berhasil diubah menjadi $status_baru'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah status produk'); window.location='produk.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location='produk.php';</script>";
}
?>
