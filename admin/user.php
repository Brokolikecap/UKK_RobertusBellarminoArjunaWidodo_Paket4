<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

/* ================= PAGINATION ================= */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

/* ================= HITUNG TOTAL USER ================= */
$countQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
$totalData = mysqli_fetch_assoc($countQuery)['total'];
$totalPage = ceil($totalData / $limit);

/* ================= QUERY USER ================= */
$query = mysqli_query($koneksi, "
    SELECT * FROM users
    ORDER BY id ASC
    LIMIT $start, $limit
");

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/kelola_user.css">
</head>

<body>

    <div class="container-admin">

        <div class="header-action">
            <h1>Kelola User</h1>
            <a href="user_tambah.php" class="btn btn-primary">
                + Tambah User
            </a>
        </div>

        <div class="table-wrapper">
            <table>

                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Username</th>
                        <th width="20%">Role</th>
                        <th width="35%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = $start + 1;

                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <td><?= htmlspecialchars($row['username']) ?></td>

                                <td>
                                    <span class="badge <?= ($row['role'] == 'admin') ? 'role-admin' : 'role-user' ?>">
                                        <?= ucfirst(htmlspecialchars($row['role'])) ?>
                                    </span>
                                </td>

                                <td class="action-cell">
                                    <a
                                        href="user_edit.php?id=<?= $row['id'] ?>"
                                        class="btn btn-sm btn-edit">
                                        Edit
                                    </a>

                                    <?php if ($row['role'] != 'admin') { ?>
                                        <a
                                            href="user_hapus.php?id=<?= $row['id'] ?>"
                                            class="btn btn-sm btn-delete"
                                            onclick="return confirm('Yakin hapus user ini?')">
                                            Hapus
                                        </a>
                                    <?php } else { ?>
                                        <span class="no-action">-</span>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '
                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Tidak ada data user.
                            </td>
                        </tr>
                    ';
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPage > 1) { ?>
            <div class="pagination">

                <?php if ($page > 1) { ?>
                    <a href="?page=<?= $page - 1 ?>">Prev</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $totalPage; $i++) { ?>

                    <?php if ($i == $page) { ?>
                        <strong><?= $i ?></strong>
                    <?php } else { ?>
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    <?php } ?>

                <?php } ?>

                <?php if ($page < $totalPage) { ?>
                    <a href="?page=<?= $page + 1 ?>">Next</a>
                <?php } ?>

            </div>
        <?php } ?>

        <a href="../dashboard.php" class="btn-back">
            &larr; Kembali ke Dashboard
        </a>

    </div>

</body>

</html>