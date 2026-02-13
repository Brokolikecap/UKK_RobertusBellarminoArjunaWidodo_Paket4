<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    die("Akses ditolak");
}

$id_anggota = $_SESSION['id_anggota'];
$id_buku    = $_GET['id_buku'];
$tgl_pinjam = date('Y-m-d');

/* Cek apakah masih ada transaksi aktif */
$cekTransaksi = mysqli_query(
    $koneksi,
    "SELECT id_transaksi 
     FROM transaksi 
     WHERE id_buku = '$id_buku' 
     AND status_transaksi IN ('menunggu', 'dipinjam', 'disetujui')"
);

if (mysqli_num_rows($cekTransaksi) > 0) {
    die("Buku sedang dalam proses atau sudah dipinjam");
}

/* Insert sebagai request */
mysqli_query(
    $koneksi,
    "INSERT INTO transaksi (
        id_anggota,
        id_buku,
        tgl_pinjam,
        status_transaksi
    ) VALUES (
        '$id_anggota',
        '$id_buku',
        '$tgl_pinjam',
        'menunggu'
    )"
);

header("Location: riwayat.php");
exit;
