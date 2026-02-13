<?php
include '../koneksi.php';

$id   = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id'");

if (!$data) {
    die("Query error: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {

    $judul    = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun    = $_POST['tahun'];
    $status   = strtolower(trim($_POST['status']));

    $query = "
        UPDATE buku SET
            judul_buku   = '$judul',
            penerbit     = '$penerbit',
            tahun_terbit = '$tahun',
            status       = '$status'
        WHERE id_buku = '$id'
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
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../css/buku.css">
</head>

<body>
    <div class="container">

        <div class="header-action">
            <h2>Edit Buku</h2>
        </div>

        <form method="post">

            <div class="form-group">
                <label>Judul Buku</label>
                <input
                    type="text"
                    name="judul"
                    value="<?= $row['judul_buku']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input
                    type="text"
                    name="penerbit"
                    value="<?= $row['penerbit']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Tahun Terbit</label>
                <input
                    type="number"
                    name="tahun"
                    value="<?= $row['tahun_terbit']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="tersedia"
                        <?= $row['status'] == 'tersedia' ? 'selected' : ''; ?>>
                        Tersedia
                    </option>

                    <option value="dipinjam"
                        <?= $row['status'] == 'dipinjam' ? 'selected' : ''; ?>>
                        Dipinjam
                    </option>

                    <option value="tidak tersedia"
                        <?= $row['status'] == 'tidak tersedia' ? 'selected' : ''; ?>>
                        Tidak Tersedia
                    </option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" name="update" class="btn btn-primary">
                    Update Data
                </button>

                <a
                    href="index.php"
                    class="btn"
                    style="background-color:#94a3b8;color:white;text-decoration:none;">
                    Batal
                </a>
            </div>

        </form>

    </div>
</body>

</html>