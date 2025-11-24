<!DOCTYPE html>
<html>
<head>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    color: #000;
}

h2, h3 {
    text-align: center;
}

form {
    width: 50%;
    margin: 20px auto;
    padding: 10px;
}

input[type="text"], input[type="number"] {
    width: 100%;
    padding: 5px;
    margin: 5px 0;
}

input[type="submit"] {
    padding: 5px 10px;
}

table {
    border-collapse: collapse;
    width: 60%;
    margin: 20px auto;
}

th, td {
    padding: 5px;
    border: 1px solid #000;
    text-align: center;
}

</style>

    <title>Input Nilai Mahasiswa</title>
</head>
<body>
<h2>Input Nilai Mahasiswa</h2>
<form method="post">
    Jumlah Mahasiswa: <input type="number" name="jumlah" min="1" required>
    <input type="submit" name="submitJumlah" value="Submit">
</form>

<?php
function hitungRataRata($nilai) {
    $total = array_sum($nilai);
    $jumlah = count($nilai);
    return $jumlah > 0 ? $total / $jumlah : 0;
}
function tampilkanStatus($nama, $nilai) {
    echo "<h3>Daftar Nilai dan Status</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Nama</th><th>Nilai</th><th>Status</th></tr>";
    for ($i = 0; $i < count($nama); $i++) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($nama[$i]) . "</td>";
        echo "<td>" . $nilai[$i] . "</td>";
        echo "<td>" . ($nilai[$i] >= 60 ? "Lulus" : "Tidak Lulus") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
function totalNilaiRekursif($nilai, $index) {
    if ($index < 0) {
        return 0;
    } else {
        return $nilai[$index] + totalNilaiRekursif($nilai, $index - 1);
    }
}
if (isset($_POST['submitJumlah'])) {
    $jumlah = intval($_POST['jumlah']);
    echo '<form method="post">';
    echo '<input type="hidden" name="jumlah" value="' . $jumlah . '">';
    for ($i = 0; $i < $jumlah; $i++) {
        echo "Nama Mahasiswa ke-" . ($i + 1) . ": <input type='text' name='nama[]' required><br>";
        echo "Nilai Mahasiswa ke-" . ($i + 1) . ": <input type='number' name='nilai[]' min='0' max='100' required><br><br>";
    }
    echo '<input type="submit" name="submitData" value="Proses Nilai">';
    echo '</form>';
}
if (isset($_POST['submitData'])) {
    $nama = $_POST['nama'];
    $nilai = $_POST['nilai'];

    $rata = hitungRataRata($nilai);
    echo "<h3>Nilai Rata-rata Kelas: " . number_format($rata, 2) . "</h3>";

    tampilkanStatus($nama, $nilai);

    $total = totalNilaiRekursif($nilai, count($nilai) - 1);
    echo "<h3>Total Nilai Semua Mahasiswa: " . number_format($total, 2) . "</h3>";
}
?>
</body>
</html>
