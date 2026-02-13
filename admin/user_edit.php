<?php session_start();
include '../koneksi.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'"));
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        mysqli_query($koneksi, "UPDATE users SET username='$username', password='$password', role='$role' WHERE id='$id'");
    } else {
        mysqli_query($koneksi, "UPDATE users SET username='$username', role='$role' WHERE id='$id'");
    }
    header("Location: user.php");
} ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght+700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/kelola_user.css">
</head>

<body>
    <div class="container-admin">
        <h1 class="page-title">Edit User</h1>
        <form method="post" class="form-container">
            <div class="form-group"> <label for="username">Username</label> <input type="text" id="username" name="username" value="<?= $data['username'] ?>" required> </div>
            <div class="form-group"> <label for="password">Password Baru</label> <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak diubah"> <small class="form-helper">Biarkan kosong jika tidak ingin mengubah password.</small> </div>
            <div class="form-group"> <label for="role">Role</label> <select name="role" id="role">
                    <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= $data['role'] == 'user' ? 'selected' : '' ?>>User</option>
                </select> </div>
            <div class="form-actions"> <button type="submit" name="update" class="btn btn-primary">Update Data</button> <a href="user.php" class="btn btn-back">Batal</a> </div>
        </form>
    </div>
</body>

</html>