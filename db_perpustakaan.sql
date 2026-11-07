-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2026 at 05:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

CREATE DATABASE IF NOT EXISTS `db_perpustakaan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_perpustakaan`;

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
CREATE TABLE `buku` (
  `id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(150) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `tahun_terbit` smallint(5) UNSIGNED NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `sampul` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul`, `kategori`, `penulis`, `tahun_terbit`, `stok`, `sampul`, `created_at`) VALUES
(1, 'Laskar Pelangi', 'Novel', 'Andrea Hirata', 2005, 3, '6aee977613400.jpg', '2026-11-06 04:28:26'),
(2, 'Pemrograman Web dengan PHP', 'Teknologi', 'Budi Raharjo', 2019, 2, '', '2026-11-06 04:28:26'),
(3, 'Sistem Basis Data', 'Teknologi', 'Abdul Kadir', 2014, 5, '', '2026-11-06 04:28:26'),
(4, 'Bumi Manusia', 'Sejarah/Novel', 'Pramoedya Ananta Toer', 1980, 0, '', '2026-11-06 04:30:30'),
(5, 'Filosofi Teras', 'Pengembangan Diri', 'Henry Manampiring', 2018, 6, '', '2026-11-06 04:30:30'),
(6, 'Algoritma dan Pemrograman', 'Pendidikan', 'Rinaldi Munir', 2017, 0, '', '2026-11-06 04:30:30'),
(7, 'Negeri 5 Menara', 'Novel', 'A. Fuadi', 2009, 5, '6aee8b252c002.jpg', '2026-11-06 04:30:30'),
(8, 'Matematika Diskrit', 'Pendidikan', 'Informatika Edi', 2016, 3, '6aed9803000e6.jpg', '2026-11-06 04:30:30'),
(9, 'hujan', 'Novel', 'tere liye', 2025, 1, '6aee8ec1ec9e8.jpg', '2026-11-07 02:36:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$pJTGbE3fjPG9.1FBBDZuI.NLZP0yJwYE0juNS1ADqKm7YiJlCoC5a', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
