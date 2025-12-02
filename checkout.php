<?php
include('database/koneksi.php');

/* === FUNGSI KIRIM WA === */
function kirim_wa($target, $pesan, $token) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'target' => $target,
            'message' => $pesan,
        ],
        CURLOPT_HTTPHEADER => [
            "Authorization: $token"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    file_put_contents(
        "log_wa_checkout.txt",
        date("Y-m-d H:i:s") . " | Target: $target | Response: $response\n",
        FILE_APPEND
    );

    return $response;
}

/* === PROSES CHECKOUT === */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $metode = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
    $total = mysqli_real_escape_string($koneksi, $_POST['total_harga']);
    $daftar_item = $_POST['daftar_item'];
    $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis_customer']); // ★ JENIS PELANGGAN

    $bukti_file = '';
    // Status awal 'Belum Dibayar' agar konsisten dengan logika di codingan 1
    $status = 'Belum Dibayar';

    /* === UPLOAD BUKTI === */
    if ($metode === 'transfer' && isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $bukti_file = 'bukti_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['bukti']['tmp_name'], 'uploads/' . $bukti_file);
        // Jika ada bukti transfer, statusnya berubah
        $status = 'Sudah Dibayar';
    } else if ($metode === 'cod') {
        // Jika COD, statusnya tetap 'Belum Dibayar' sampai admin konfirmasi
        $status = 'Belum Dibayar';
    }

    /* === SIMPAN PESANAN === */
    $sql = "INSERT INTO pesanan (nama_pembeli, no_telpon, alamat, total_harga, tanggal, metode_pembayaran, bukti_pembayaran, status)
            VALUES ('$nama', '$no_telp', '$alamat', '$total', NOW(), '$metode', '$bukti_file', '$status')";

    if (mysqli_query($koneksi, $sql)) {

        $id_pesanan = mysqli_insert_id($koneksi);

        /* === SIMPAN / UPDATE PELANGGAN === */
        $cek = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE no_telpon='$no_telp'");

        if(mysqli_num_rows($cek) == 0){
            // pelanggan baru
            mysqli_query($koneksi, "
                INSERT INTO pelanggan (no_telpon, nama, jenis, status)
                VALUES ('$no_telp', '$nama', '$jenis', 'Aktif')
            ");
        } else {
            // pelanggan lama → update jenis & status
            mysqli_query($koneksi, "
                UPDATE pelanggan 
                SET nama='$nama', jenis='$jenis', status='Aktif'
                WHERE no_telpon='$no_telp'
            ");
        }

        /* === DETAIL PESANAN === */
        $items = explode(",", $daftar_item);

        foreach ($items as $it) {
            if (trim($it) === '') continue;

            list($nama_produk, $qty, $harga) = explode("|", $it);
            $qty = (int)$qty;
            $harga = (int)$harga;
            $subtotal = $qty * $harga;

            mysqli_query($koneksi, "
                INSERT INTO detail_pesanan (id_pesanan, nama_produk, qty, harga, subtotal)
                VALUES ('$id_pesanan', 
                        '" . mysqli_real_escape_string($koneksi, $nama_produk) . "',
                        '$qty',
                        '$harga',
                        '$subtotal')
            ");

            /* ==========================================
               === [TAMBAHAN DARI CODINGAN 1] UPDATE STOK ===
               ========================================== */

            // Ambil id produk berdasarkan nama
            $qProduk = mysqli_query($koneksi, "
                SELECT id FROM produk WHERE nama_produk='" . mysqli_real_escape_string($koneksi, $nama_produk) . "'
            ");
            $p = mysqli_fetch_assoc($qProduk);
            $id_produk = $p['id'];

            if ($id_produk) {
                // Kurangi stok
                mysqli_query($koneksi, "
                    UPDATE produk 
                    SET stok = stok - $qty
                    WHERE id = '$id_produk'
                ");
            }
        }

        /* ==================================================
           === [TAMBAHAN DARI CODINGAN 1] SIMPAN KE LAPORAN ===
           ================================================== */
        
        // Masukkan data ke tabel laporan
        mysqli_query($koneksi, "
            INSERT INTO laporan (nama_pembeli, no_telpon, alamat, total_harga, tanggal, metode_pembayaran, status)
            VALUES ('$nama', '$no_telp', '$alamat', '$total', NOW(), '$metode', '$status')
        ");
        
        $id_laporan = mysqli_insert_id($koneksi);
        
        // Masukkan detail produk terjual ke detail_laporan
        foreach ($items as $it) {
            if (trim($it) === '') continue;
            
            list($nama_produk, $qty, $harga) = explode("|", $it);
            $qty = (int)$qty;
            
            // Ambil id produk berdasarkan nama
            $qProduk = mysqli_query($koneksi, "
                SELECT id FROM produk WHERE nama_produk='" . mysqli_real_escape_string($koneksi, $nama_produk) . "'
            ");
            $p = mysqli_fetch_assoc($qProduk);
            $id_produk = $p['id'];
            
            if ($id_produk) {
                // Masukkan ke detail_laporan dengan id_laporan yang benar
                mysqli_query($koneksi, "
                    INSERT INTO detail_laporan (id_laporan, id_produk, jumlah)
                    VALUES ('$id_laporan', '$id_produk', '$qty')
                ");
            }
        }

        /* === FORMAT WA === */
        $teks_item = "";
        foreach ($items as $it) {
            if (trim($it) === '') continue;
            list($nama_produk, $qty, $harga) = explode("|", $it);

            $teks_item .= "- $nama_produk ($qty x Rp" . number_format($harga,0,',','.') . ")\n";
        }

        /* === KIRIM WA === */
        $token = "K9asMQwnsqqzJyF85tj7";

        if (substr($no_telp, 0, 1) === "0") {
            $no_telp = "62" . substr($no_telp, 1);
        }

        $pesan = 
"Hallo $nama 👋

Terima kasih sudah memesan di Roti 515 🍞✨

🧾 ID Pesanan: #$id_pesanan
📦 Detail Pesanan:
 $teks_item
💰 Total: Rp" . number_format($total,0,',','.') . "

📍 Alamat: $alamat
👥 Jenis Pelanggan: $jenis
💳 Metode Pembayaran: $metode

Pesanan kamu sudah kami terima dan sedang diproses ✔
Terima kasih 🙏🙂

*_Note : Kirim Bukti Pembayaran di nomer ini (jika transfer)_*";

        kirim_wa($no_telp, $pesan, $token);

        echo 'success';

    } else {
        echo 'error: ' . mysqli_error($koneksi);
    }
}
?>