<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

/* =======================
   QUERY DATA DASHBOARD
======================= */

$qUser       = mysqli_query($koneksi, "SELECT COUNT(*) AS total_user FROM users");
$totalUser   = mysqli_fetch_assoc($qUser)['total_user'];

$qBuku       = mysqli_query($koneksi, "SELECT COUNT(*) AS total_buku FROM buku");
$totalBuku   = mysqli_fetch_assoc($qBuku)['total_buku'];

$qPinjam     = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS dipinjam 
     FROM transaksi 
     WHERE status_transaksi = 'dipinjam'"
);
$totalDipinjam = mysqli_fetch_assoc($qPinjam)['dipinjam'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar">

        <a href="" class="navbar-brand">
            Perpus<span>Broxy</span>
        </a>

        <ul class="navbar-menu">
            <li><a href="" class="active">Dashboard</a></li>

            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li><a href="admin/user.php">Kelola User</a></li>
                <li><a href="buku/index.php">Kelola Buku</a></li>
                <li><a href="admin/transaksi.php">Data Transaksi</a></li>
            <?php } else { ?>
                <li><a href="user/buku.php">Pinjam Buku</a></li>
                <li><a href="transaksi/riwayat.php">Riwayat Peminjaman</a></li>
            <?php } ?>
        </ul>

        <div class="navbar-user">
            <div class="user-info-text">
                <span class="username">
                    <?php echo $_SESSION['username']; ?>
                </span>
                <span class="userrole">
                    <?php echo ucfirst($_SESSION['role']); ?>
                </span>
            </div>

            <a href="logout.php" class="btn-logout">Logout</a>
        </div>

    </nav>

    <main class="main-content">

        <?php if ($_SESSION['role'] == 'admin') { ?>

            <div class="grid-placeholder">

                <div class="mini-card">
                    <h3>Total User</h3>
                    <p>
                        <strong><?php echo $totalUser; ?></strong>
                        pengguna terdaftar
                    </p>
                </div>

                <div class="mini-card">
                    <h3>Total Buku</h3>
                    <p>
                        <strong><?php echo $totalBuku; ?></strong>
                        buku tersedia
                    </p>
                </div>

                <div class="mini-card">
                    <h3>Buku Dipinjam</h3>
                    <p>
                        <strong><?php echo $totalDipinjam; ?></strong>
                        sedang dipinjam
                    </p>
                </div>

            </div>

        <?php } else { ?>

            <div class="welcome-card">
                <h1>Hi, <?php echo $_SESSION['username']; ?></h1>
                <p class="desc-text">
                    Selamat datang di sistem peminjaman buku.
                    Silakan pilih menu <strong>Pinjam Buku</strong>
                    untuk mulai meminjam.
                </p>
            </div>

        <?php } ?>

    </main>

</body>

</html>