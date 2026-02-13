<?php
include '../koneksi.php';
session_start();

if ($_SESSION['role'] != 'admin') {
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
if ($keyword != '') {

    $countQuery = mysqli_query($koneksi, "
        SELECT COUNT(*) as total FROM buku
        WHERE judul_buku LIKE '%$keyword%'
    ");
} else {

    $countQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
}

$totalData = mysqli_fetch_assoc($countQuery)['total'];
$totalPage = ceil($totalData / $limit);

/* ================= QUERY DATA ================= */
if ($keyword != '') {

    $data = mysqli_query($koneksi, "
        SELECT * FROM buku
        WHERE judul_buku LIKE '%$keyword%'
        ORDER BY id_buku ASC
        LIMIT $start, $limit
    ");
} else {

    $data = mysqli_query($koneksi, "
        SELECT * FROM buku
        ORDER BY id_buku ASC
        LIMIT $start, $limit
    ");
}

if (!$data) {
    die("Query error: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Data Buku</title>
    <link rel="stylesheet" href="../css/buku.css">
</head>

<body>
    <div class="container">

        <div class="header-action">
            <h2>Data Buku</h2>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Buku</a>
        </div>

        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <div class="search-input-wrapper">
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Cari judul buku..."
                        value="<?= htmlspecialchars($keyword) ?>"
                        class="search-input">
                    <button type="submit" class="search-btn">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Judul</th>
                        <th width="20%">Penerbit</th>
                        <th width="10%">Tahun</th>
                        <th width="15%">Status</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = $start + 1;
                    while ($row = mysqli_fetch_assoc($data)) {

                        $statusClass = '';
                        $statusText = $row['status'];

                        if (strtolower($statusText) == 'tersedia') {
                            $statusClass = 'status-tersedia';
                        } elseif (strtolower($statusText) == 'dipinjam') {
                            $statusClass = 'status-dipinjam';
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= $row['judul_buku'] ?></strong></td>
                            <td><?= $row['penerbit'] ?></td>
                            <td><?= $row['tahun_terbit'] ?></td>
                            <td>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $row['id_buku'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="hapus.php?id=<?= $row['id_buku'] ?>"
                                    class="btn btn-sm btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if (mysqli_num_rows($data) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 2rem;">
                                Buku tidak ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPage > 1) { ?>
            <div class="pagination">

                <?php if ($page > 1) { ?>
                    <a href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($keyword) ?>">Prev</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $totalPage; $i++) { ?>

                    <?php if ($i == $page) { ?>
                        <strong><?= $i ?></strong>
                    <?php } else { ?>
                        <a href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>"><?= $i ?></a>
                    <?php } ?>

                <?php } ?>

                <?php if ($page < $totalPage) { ?>
                    <a href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($keyword) ?>">Next</a>
                <?php } ?>

            </div>
        <?php } ?>

        <a href="../dashboard.php" class="btn-back">&larr; Kembali ke Dashboard</a>

    </div>
</body>

</html>