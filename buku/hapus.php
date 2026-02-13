<?php
include '../koneksi.php';

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $id = mysqli_real_escape_string($koneksi, $id);

    $query = "
        DELETE FROM buku
        WHERE id_buku = '$id'
    ";

    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
}

header("Location: index.php");
exit;
