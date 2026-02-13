<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

$id_transaksi = $_GET['id'];
$aksi         = $_GET['aksi'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT id_buku 
         FROM transaksi 
         WHERE id_transaksi = '$id_transaksi'"
    )
);

mysqli_begin_transaction($koneksi);

try {

    if ($aksi == 'setuju') {

        mysqli_query(
            $koneksi,
            "UPDATE transaksi 
             SET status_transaksi = 'dipinjam' 
             WHERE id_transaksi = '$id_transaksi'"
        );

        mysqli_query(
            $koneksi,
            "UPDATE buku 
             SET status = 'dipinjam' 
             WHERE id_buku = '{$data['id_buku']}'"
        );
    } elseif ($aksi == 'tolak') {

        mysqli_query(
            $koneksi,
            "UPDATE transaksi 
             SET status_transaksi = 'ditolak' 
             WHERE id_transaksi = '$id_transaksi'"
        );
    }

    mysqli_commit($koneksi);
    header("Location: transaksi.php");
    exit;
} catch (Exception $e) {

    mysqli_rollback($koneksi);
    echo "Gagal memproses";
}
