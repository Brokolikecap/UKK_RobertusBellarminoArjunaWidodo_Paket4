<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    die("Akses ditolak");
}

$id_anggota = $_SESSION['id_anggota'];

/* ===== SEARCH ===== */
$keyword = isset($_GET['keyword'])
    ? mysqli_real_escape_string($koneksi, $_GET['keyword'])
    : "";

/* ===== PAGINATION ===== */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;


/* ===== HITUNG TOTAL DATA ===== */
$countQuery = "
SELECT COUNT(*) as total
FROM transaksi
JOIN buku ON transaksi.id_buku = buku.id_buku
WHERE transaksi.id_anggota = '$id_anggota'
";

if ($keyword != "") {
    $countQuery .= " AND buku.judul_buku LIKE '%$keyword%'";
}

$countResult = mysqli_query($koneksi, $countQuery);
$totalData = mysqli_fetch_assoc($countResult)['total'];
$totalPage = ceil($totalData / $limit);


/* ===== QUERY DATA ===== */
$query = "
SELECT 
    transaksi.id_transaksi,
    transaksi.tgl_pinjam,
    transaksi.tgl_kembali,
    transaksi.status_transaksi,
    buku.judul_buku
FROM transaksi
JOIN buku ON transaksi.id_buku = buku.id_buku
WHERE transaksi.id_anggota = '$id_anggota'
";

if ($keyword != "") {
    $query .= " AND buku.judul_buku LIKE '%$keyword%'";
}

$query .= " 
ORDER BY transaksi.id_transaksi ASC
LIMIT $start, $limit
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <link rel="stylesheet" href="../css/riwayat.css">
</head>

<body>
    <div class="container">

        <header class="header-title">
            <h1>Riwayat Peminjaman</h1>
            <p class="subtitle">Daftar buku yang pernah Anda pinjam</p>
        </header>


        <!-- ===== SEARCH ===== -->
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <div class="search-input-wrapper">

                    <input
                        type="text"
                        name="keyword"
                        placeholder="Cari judul buku..."
                        value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
                        class="search-input">

                    <button type="submit" class="search-btn">
                        Cari
                    </button>

                </div>
            </form>
        </div>


        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = $start + 1;

                    while ($r = mysqli_fetch_assoc($result)) {

                        if ($r['status_transaksi'] == 'dipinjam') {
                            $statusClass = 'status-active';
                        } elseif ($r['status_transaksi'] == 'dikembalikan') {
                            $statusClass = 'status-returned';
                        } else {
                            $statusClass = 'status-other';
                        }

                        $tgl_pinjam = date('d/m/Y', strtotime($r['tgl_pinjam']));

                        if ($r['tgl_kembali'] == NULL || $r['tgl_kembali'] == '0000-00-00 00:00:00') {
                            $tgl_kembali = '-';
                        } else {
                            $tgl_kembali = date('d/m/Y', strtotime($r['tgl_kembali']));
                        }
                    ?>

                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="book-title"><?= $r['judul_buku']; ?></td>
                            <td><?= $tgl_pinjam; ?></td>
                            <td><?= $tgl_kembali; ?></td>

                            <td>
                                <span class="badge <?= $statusClass; ?>">
                                    <?= $r['status_transaksi']; ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($r['status_transaksi'] == 'dipinjam') { ?>
                                    <a href="kembalikan.php?id_transaksi=<?= $r['id_transaksi']; ?>" class="btn-action">
                                        Kembalikan
                                    </a>
                                <?php } else { ?>
                                    <span class="no-action">-</span>
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>


                    <?php if (mysqli_num_rows($result) == 0) { ?>
                        <tr>
                            <td colspan="6" style="text-align:center;padding:20px;">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>


        <!-- ===== PAGINATION ===== -->
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
            <a href="../dashboard.php">&larr; Kembali ke Dashboard</a>
        </div>

    </div>
</body>

</html>