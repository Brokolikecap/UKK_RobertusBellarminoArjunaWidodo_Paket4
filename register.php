<?php
session_start();
include "koneksi.php";

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error   = "";
$success = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = "user";

    if (empty($username) || empty($password)) {

        $error = "Username dan Password wajib diisi!";
    } else {

        $cek = $koneksi->prepare(
            "SELECT id 
             FROM users 
             WHERE username = ?"
        );

        $cek->bind_param("s", $username);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {

            $error = "Username sudah digunakan!";
        } else {

            $stmt = $koneksi->prepare(
                "INSERT INTO users (username, password, role) 
                 VALUES (?, ?, ?)"
            );

            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Registrasi gagal! Terjadi kesalahan sistem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Akun</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

    <div class="login-container">

        <h2>Daftar Akun</h2>
        <p style="color: #777; font-size: 14px; margin-bottom: 20px;">
            Lengkapi formulir di bawah ini
        </p>

        <?php if ($error) { ?>
            <div class="alert-error">
                <?= $error ?>
            </div>
        <?php } ?>

        <?php if ($success) { ?>
            <div class="alert-success">
                <?= $success ?>
            </div>
        <?php } ?>

        <form method="post">

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    class="form-control"
                    required
                    autocomplete="off">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required>
            </div>

            <button type="submit" name="register" class="btn-submit">
                Register
            </button>

        </form>

        <p class="text-center">
            Sudah punya akun?
            <a href="login.php">Login</a>
        </p>

    </div>

</body>

</html>