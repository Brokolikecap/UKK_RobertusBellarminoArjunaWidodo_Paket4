<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../dashboard.php");
    exit;
}

/* ================= SEARCH ================= */
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi, $_GET['keyword']) : '';

/* ================= PAGINATION ================= */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

/* ================= HITUNG TOTAL DATA ================= */
if ($keyword != "") {

    $countQuery = mysqli_query($koneksi, "
        SELECT COUNT(*) as total 
        FROM buku 
        WHERE judul_buku LIKE '%$keyword%'
        AND status IN ('tersedia','dipinjam')
    ");
} else {

    $countQuery = mysqli_query($koneksi, "
        SELECT COUNT(*) as total 
        FROM buku 
        WHERE status IN ('tersedia','dipinjam')
    ");
}

$totalData = mysqli_fetch_assoc($countQuery)['total'];
$totalPage = ceil($totalData / $limit);

/* ================= QUERY DATA ================= */
if ($keyword != "") {

    $data = mysqli_query($koneksi, "
        SELECT * FROM buku 
        WHERE judul_buku LIKE '%$keyword%'
        AND status IN ('tersedia','dipinjam')
        ORDER BY id_buku DESC
        LIMIT $start, $limit
    ");
} else {

    $data = mysqli_query($koneksi, "
        SELECT * FROM buku 
        WHERE status IN ('tersedia','dipinjam')
        ORDER BY id_buku ASC
        LIMIT $start, $limit
    ");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="../css/pinjam.css">
</head>

<body>
    <div class="container">

        <div class="header-section">
            <div class="header-title">
                <h1>Daftar Buku</h1>
                <p class="subtitle">Silakan pilih buku untuk dipinjam</p>
            </div>

            <div class="search-container">
                <form method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input
                            type="text"
                            name="keyword"
                            placeholder="Cari judul buku..."
                            value="<?= $keyword ?>"
                            class="search-input">

                        <button type="submit" class="search-btn">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($data) == 0) { ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 2rem;">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    <?php } ?>

                    <?php
                    $no = $start + 1;

                    while ($b = mysqli_fetch_assoc($data)) {
                        $status = strtolower($b['status']);
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                <span class="book-title">
                                    <?= $b['judul_buku'] ?>
                                </span>
                            </td>

                            <td><?= $b['penerbit'] ?></td>
                            <td><?= $b['tahun_terbit'] ?></td>

                            <td>
                                <?php
                                if ($status == 'tersedia') {
                                    echo '<span class="badge status-tersedia">Tersedia</span>';
                                } elseif ($status == 'dipinjam') {
                                    echo '<span class="badge status-dipinjam">Dipinjam</span>';
                                }
                                ?>
                            </td>

                            <td>
                                <?php if ($status == 'tersedia') { ?>
                                    <a href="../transaksi/pinjam.php?id_buku=<?= $b['id_buku'] ?>" class="btn-action">
                                        Pinjam
                                    </a>
                                <?php } else { ?>
                                    <span class="no-action">-</span>
                                <?php } ?>
                            </td>

                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>

        <!-- ================= PAGINATION ================= -->
        <?php if ($totalPage > 1) { ?>
            <div class="pagination">

                <?php if ($page > 1) { ?>
                    <a href="?page=<?= $page - 1 ?>&keyword=<?= $keyword ?>">Prev</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $totalPage; $i++) { ?>

                    <?php if ($i == $page) { ?>
                        <strong><?= $i ?></strong>
                    <?php } else { ?>
                        <a href="?page=<?= $i ?>&keyword=<?= $keyword ?>"><?= $i ?></a>
                    <?php } ?>

                <?php } ?>

                <?php if ($page < $totalPage) { ?>
                    <a href="?page=<?= $page + 1 ?>&keyword=<?= $keyword ?>">Next</a>
                <?php } ?>

            </div>
        <?php } ?>

        <div class="back-link">
            <a href="../dashboard.php">← Kembali ke Dashboard</a>
        </div>

    </div>
</body>

</html>