<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

/* ================= SEARCH ================= */
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi, $_GET['keyword']) : '';

/* ================= PAGINATION ================= */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

/* ================= HITUNG TOTAL DATA ================= */
$countQuery = "
SELECT COUNT(*) as total
FROM transaksi 
JOIN users ON transaksi.id_anggota = users.id 
JOIN buku ON transaksi.id_buku = buku.id_buku
";

if (!empty($keyword)) {
    $countQuery .= " WHERE 
        users.username LIKE '%$keyword%' 
        OR transaksi.tgl_pinjam LIKE '%$keyword%'
        OR transaksi.tgl_kembali LIKE '%$keyword%'
    ";
}

$countResult = mysqli_query($koneksi, $countQuery);
$totalData = mysqli_fetch_assoc($countResult)['total'];
$totalPage = ceil($totalData / $limit);

/* ================= QUERY DATA ================= */
$query = "
SELECT 
    transaksi.id_transaksi, 
    users.username, 
    buku.judul_buku, 
    transaksi.tgl_pinjam, 
    transaksi.tgl_kembali, 
    transaksi.status_transaksi 
FROM transaksi 
JOIN users ON transaksi.id_anggota = users.id 
JOIN buku ON transaksi.id_buku = buku.id_buku
";

if (!empty($keyword)) {
    $query .= " WHERE 
        users.username LIKE '%$keyword%' 
        OR transaksi.tgl_pinjam LIKE '%$keyword%'
        OR transaksi.tgl_kembali LIKE '%$keyword%'
    ";
}

/* ================= URUTKAN DATA PALING LAMA DULU ================= */
$query .= " ORDER BY transaksi.id_transaksi ASC LIMIT $start, $limit";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi Admin</title>
    <link rel="stylesheet" href="../css/transaksi_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container-admin">

        <header class="header-admin">
            <div class="header-content">
                <h1>Semua Transaksi Peminjaman</h1>
                <p class="subtitle">Pantau aktivitas peminjaman seluruh anggota</p>
            </div>
        </header>

        <!-- SEARCH -->
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <div class="search-input-wrapper">
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Cari user atau tanggal..."
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
                        <th style="width: 50px;">No</th>
                        <th>User</th>
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

                        $statusClass = '';
                        if ($r['status_transaksi'] == 'dipinjam') $statusClass = 'status-borrowed';
                        elseif ($r['status_transaksi'] == 'dikembalikan') $statusClass = 'status-returned';
                        else $statusClass = 'status-pending';
                    ?>

                        <tr>
                            <td><?= $no++; ?></td>

                            <td class="user-name">
                                <span class="user-icon">@</span> <?= $r['username']; ?>
                            </td>

                            <td class="book-title"><?= $r['judul_buku']; ?></td>

                            <td><?= date('Y-m-d', strtotime($r['tgl_pinjam'])); ?></td>

                            <td>
                                <?= $r['tgl_kembali']
                                    ? date('Y-m-d', strtotime($r['tgl_kembali']))
                                    : '-'
                                ?>
                            </td>

                            <td>
                                <span class="badge <?= $statusClass; ?>">
                                    <?= $r['status_transaksi']; ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($r['status_transaksi'] == 'menunggu') { ?>
                                    <div class="action-buttons">
                                        <a href="approve.php?id=<?= $r['id_transaksi']; ?>&aksi=setuju" class="btn-action btn-approve">Setujui</a>
                                        <a href="approve.php?id=<?= $r['id_transaksi']; ?>&aksi=tolak" class="btn-action btn-reject">Tolak</a>
                                    </div>
                                <?php } else { ?>
                                    -
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
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

        <div class="back-link-wrapper">
            <a href="../dashboard.php" class="btn-back">
                <span class="arrow">&larr;</span> Kembali ke Dashboard
            </a>
        </div>

    </div>
</body>

</html>