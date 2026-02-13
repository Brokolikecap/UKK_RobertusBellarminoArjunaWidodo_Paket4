<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

if (isset($_POST['simpan'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    mysqli_query(
        $koneksi,
        "INSERT INTO users (username, password, role)
         VALUES ('$username', '$password', '$role')"
    );

    header("Location: user.php");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/kelola_user.css">
</head>

<body>

    <div class="container-admin">

        <h1 class="page-title">Tambah User Baru</h1>

        <form method="post" class="form-container">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    placeholder="Masukkan username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Masukkan password">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-actions">
                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-primary">
                    Simpan Data
                </button>

                <a href="user.php" class="btn btn-back">
                    Batal
                </a>
            </div>

        </form>

    </div>

</body>

</html>