<?php
session_start();
include '../koneksi.php';

$id_transaksi = $_GET['id_transaksi'];
$tgl_kembali  = date('Y-m-d');

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

    mysqli_query(
        $koneksi,
        "UPDATE transaksi 
         SET 
            tgl_kembali      = '$tgl_kembali',
            status_transaksi = 'dikembalikan'
         WHERE id_transaksi = '$id_transaksi'"
    );

    mysqli_query(
        $koneksi,
        "UPDATE buku 
         SET status = 'tersedia' 
         WHERE id_buku = '{$data['id_buku']}'"
    );

    mysqli_commit($koneksi);
    header("Location: riwayat.php");
    exit;
} catch (Exception $e) {

    mysqli_rollback($koneksi);
    echo "Gagal mengembalikan buku";
}
