<?php
$koneksi = mysqli_connect("localhost", "root", "", "roti515");

if (!$koneksi) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
