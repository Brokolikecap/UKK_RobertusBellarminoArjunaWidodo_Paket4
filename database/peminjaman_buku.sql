-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 13, 2026 at 06:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int NOT NULL,
  `judul_buku` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun_terbit` int NOT NULL,
  `status` enum('tersedia','dipinjam','tidak tersedia') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `penerbit`, `tahun_terbit`, `status`) VALUES
(1, 'Serial superman', 'PT Brody Saputra', 2022, 'tersedia'),
(2, 'Mie ayam bakso', 'PT Xaverius Pracimantoro', 2025, 'tersedia'),
(3, 'You', 'PT Chiquita Abadi', 2025, 'dipinjam'),
(4, 'Jejak si dia', 'PT Plontos atos', 2023, 'tidak tersedia'),
(5, 'It\'s only me', 'PT Kaleb J', 2023, 'dipinjam'),
(6, 'Katamu aku itu', 'PT Suka ngoding', 2024, 'tidak tersedia'),
(7, 'Sejarah M1 ', 'PT Montoon', 2016, 'tersedia'),
(8, 'Komputer itu', 'PT Bolpen itu', 2019, 'tersedia'),
(9, 'tess', 'tess', 2000, 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_anggota` int NOT NULL,
  `id_buku` int NOT NULL,
  `tgl_pinjam` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tgl_kembali` datetime DEFAULT NULL,
  `status_transaksi` enum('dipinjam','dikembalikan','menunggu','disetujui','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_anggota`, `id_buku`, `tgl_pinjam`, `tgl_kembali`, `status_transaksi`) VALUES
(1, 4, 2, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan'),
(2, 5, 3, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan'),
(3, 5, 5, '2026-02-13 00:00:00', NULL, 'dipinjam'),
(4, 5, 4, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan'),
(5, 5, 3, '2026-02-13 00:00:00', NULL, 'dipinjam'),
(6, 5, 1, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan'),
(7, 4, 4, '2026-02-13 00:00:00', NULL, 'ditolak'),
(8, 2, 1, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan'),
(9, 2, 1, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan'),
(10, 2, 2, '2026-02-13 00:00:00', NULL, 'menunggu'),
(11, 7, 1, '2026-02-13 00:00:00', '2026-02-13 00:00:00', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(3, 'mulyono', 'mulyono', 'admin'),
(4, 'wicaksono', 'wicaksono', 'user'),
(5, 'Alex', 'alex', 'user'),
(6, 'remora', 'remora', 'admin'),
(7, 'user3', 'user3', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
