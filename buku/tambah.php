<?php
include '../koneksi.php';

if (isset($_POST['simpan'])) {

    $judul    = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $tahun    = $_POST['tahun'];
    $status   = $_POST['status'];

    $judul    = mysqli_real_escape_string($koneksi, $judul);
    $penerbit = mysqli_real_escape_string($koneksi, $penerbit);
    $status   = mysqli_real_escape_string($koneksi, $status);

    $query = "
        INSERT INTO buku (
            judul_buku,
            penerbit,
            tahun_terbit,
            status
        ) VALUES (
            '$judul',
            '$penerbit',
            '$tahun',
            '$status'
        )
    ";

    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../css/buku.css">
</head>

<body>
    <div class="container">

        <div class="header-action">
            <h2>Tambah Buku Baru</h2>
        </div>

        <form method="post">

            <div class="form-group">
                <label>Judul Buku</label>
                <input
                    type="text"
                    name="judul"
                    placeholder="Masukkan judul buku..."
                    required>
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input
                    type="text"
                    name="penerbit"
                    placeholder="Nama penerbit..."
                    required>
            </div>

            <div class="form-group">
                <label>Tahun Terbit</label>
                <input
                    type="number"
                    name="tahun"
                    placeholder="Contoh: 2023"
                    required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="" disabled selected>
                        -- Pilih Status --
                    </option>
                    <option value="tersedia">Tersedia</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="tidak tersedia">Tidak Tersedia</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" name="simpan" class="btn btn-primary">
                    Simpan Data
                </button>

                <a
                    href="index.php"
                    class="btn"
                    style="background-color: #94a3b8; color: white; text-decoration:none;">
                    Batal
                </a>
            </div>

        </form>

    </div>
</body>

</html>