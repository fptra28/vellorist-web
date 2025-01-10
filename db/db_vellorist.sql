-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 05:54 AM
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
-- Database: `db_vellorist`
--

-- --------------------------------------------------------

--
-- Table structure for table `ulasan_produk`
--

CREATE TABLE `ulasan_produk` (
  `id_ulasan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL,
  `komentar` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ulasan_produk`
--

INSERT INTO `ulasan_produk` (`id_ulasan`, `id_produk`, `id_pelanggan`, `rating`, `komentar`, `created_at`) VALUES
(1, 1, 1, 5, 'Sangat Bagus', '2025-01-04 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_produk` (`id_produk`,`id_pelanggan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD CONSTRAINT `ulasan_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `ulasan_produk_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
