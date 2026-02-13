<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT role FROM users WHERE id='$id'")
);

if ($data['role'] == 'admin') {
    die("Admin tidak boleh dihapus");
}

mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

header("Location: user.php");
