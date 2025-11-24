<?php
session_start();
include('../database/koneksi.php');

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
  $cek = mysqli_num_rows($query);

  if ($cek > 0) {
    $_SESSION['admin'] = $username;
    header("Location: dashboard.php");
  } else {
    echo "<script>alert('Username atau password salah!');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin - Roti 515</title>
  <link rel="stylesheet" href="style-admin.css" />
</head>
<body class="login-page">
  <div class="login-container">
    <h1>Login Admin</h1>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="login">Masuk</button>
    </form>
  </div>
</body>
</html>
