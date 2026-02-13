<?php
session_start();
include "koneksi.php";

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare(
        "SELECT id, username, role 
         FROM users 
         WHERE username = ? AND password = ?"
    );

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $data = $result->fetch_assoc();

        session_regenerate_id(true);

        $_SESSION['login']      = true;
        $_SESSION['id_anggota'] = (int) $data['id'];
        $_SESSION['username']   = $data['username'];
        $_SESSION['role']       = $data['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-container">

        <h2>Selamat Datang</h2>
        <p class="subtitle">Silakan masuk ke akun Anda</p>

        <?php if ($error != "") { ?>
            <div class="alert-error">
                <?= $error ?>
            </div>
        <?php } ?>

        <form method="post">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                    autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required>
            </div>

            <button type="submit" name="login" class="btn-submit">
                Login
            </button>

        </form>

        <p class="register-text">
            Belum punya akun?
            <a href="register.php">Daftar di sini</a>
        </p>

    </div>

</body>

</html>