-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2025 at 09:31 AM
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
-- Table structure for table `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_produk`
--

INSERT INTO `kategori_produk` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Bunga Mahal');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat_pengiriman` varchar(255) NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `email_pelanggan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat_pengiriman`, `nomor_telepon`, `email_pelanggan`) VALUES
(1, 'Muhammad Faturrahman Putra', 'Griya Bukit Jaya Blok N8/14, Rt 10/27, Tlajung Udik, Gunung Putri, Kab. Bogor', '087778376988', 'faturrahman86.fr@gmail.com'),
(2, 'Muhammad Faturrahman Putra', 'Griya Bukit Jaya Blok N8/14, Rt 10/27, Tlajung Udik, Gunung Putri, Kab. Bogor', '087778376988', 'faturrahman86.fr@gmail.com'),
(3, 'Kevinta Thabina', 'Sawangan', '087778376988', 'faturrahman86.fr@gmail.com'),
(4, 'Raihan Nurhadi', 'Cijantung', '087778376988', 'faturrahman86.fr@gmail.com'),
(5, 'Rafli Baras', 'Cawang', '087778376988', 'faturrahman86.fr@gmail.com'),
(6, 'Jose Mourinho', 'Portugal', '087778376988', 'faturrahman86.fr@gmail.com'),
(7, 'Laura Shakira Aisyah Putri', 'Pasar Minggu', '087778376988', 'faturrahman86.fr@gmail.com'),
(8, 'Laura Shakira Aisyah Putri', 'Pasar Minggu', '087778376988', 'faturrahman86.fr@gmail.com'),
(9, 'Diego Oliver Hermanto', 'Duren Sawit', '087778376988', 'faturrahman86.fr@gmail.com'),
(10, 'Diego Oliver Hermanto', 'Duren Sawit', '087778376988', 'faturrahman86.fr@gmail.com'),
(11, 'Diego Oliver Hermanto', 'Duren Sawit', '087778376988', 'faturrahman86.fr@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `nomor_pesanan` text NOT NULL,
  `tanggal_pemesanan` datetime NOT NULL DEFAULT current_timestamp(),
  `total_harga` int(11) NOT NULL,
  `status_pemesanan` enum('Dibatalkan','Diproses','Dikirim','Selesai') NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `kurir` enum('-','JNE - Reguler','Sicepat - Reguler','J&T Express - Reguler','Anteraja - Reguler') NOT NULL,
  `nomor_resi` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `nomor_pesanan`, `tanggal_pemesanan`, `total_harga`, `status_pemesanan`, `metode_pembayaran`, `keterangan`, `id_produk`, `kurir`, `nomor_resi`) VALUES
(1, 2, 'V-67837684dbdbd', '2025-01-12 15:02:00', 115000, 'Diproses', 'Midtrans', 'Bunganya harus 4 buah', 1, '-', NULL),
(2, 3, 'V-6783773571a43', '2025-01-12 15:03:01', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(3, 4, 'V-1043663158', '2025-01-12 15:10:21', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(4, 5, 'V-1107791095', '2025-01-12 15:15:32', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(5, 6, 'V-1737817650', '2025-01-12 15:17:22', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(6, 7, 'V-1054764769', '2025-01-12 15:19:37', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(7, 8, 'V-96872991', '2025-01-12 15:24:56', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(8, 9, 'V-1289099708', '2025-01-12 15:26:11', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(9, 10, 'V-89129147', '2025-01-12 15:26:46', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL),
(10, 11, 'V-1100514192', '2025-01-12 15:27:01', 115000, 'Diproses', 'Midtrans', '', 1, '-', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `deskripsi_produk` text NOT NULL,
  `foto_produk` text NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga_produk`, `deskripsi_produk`, `foto_produk`, `id_kategori`) VALUES
(1, 'Bunga Bunga', 100000, 'Ceritanya ini bunga', '67836b5c7b924.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL,
  `username_admin` varchar(15) NOT NULL,
  `nama_admin` text NOT NULL,
  `password` text NOT NULL,
  `role` enum('Superadmin','Admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `username_admin`, `nama_admin`, `password`, `role`) VALUES
(1, 'Senfuri0n', 'Muhammad Faturrahman Putra', '$2y$10$aDtZNh7cXIVA7B8bMdOMCeqtIZzHEw9tPrYlqZF1SpTG9V08JI/2.', 'Superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan_produk`
--

CREATE TABLE `ulasan_produk` (
  `id_ulasan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL,
  `komentar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `id_voucher` int(11) NOT NULL,
  `nama_promo` text NOT NULL,
  `kode_voucher` varchar(20) NOT NULL,
  `diskon` decimal(5,2) NOT NULL,
  `tanggal_kadaluarsa` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`id_voucher`, `nama_promo`, `kode_voucher`, `diskon`, `tanggal_kadaluarsa`) VALUES
(1, 'Promo Tahun Baru 2025', '2025NEWYEAR', 25.00, '2025-01-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_produk` (`id_produk`,`id_pelanggan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id_voucher`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id_voucher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_produk` (`id_kategori`);

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
