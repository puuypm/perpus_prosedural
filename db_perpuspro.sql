-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2024 at 03:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpus`
--

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id` int(25) NOT NULL,
  `nama_level` varchar(45) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id`, `nama_level`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', '2024-07-07', '2024-07-07', NULL),
(2, 'User', '2024-07-07', '2024-07-07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(25) NOT NULL,
  `id_level` int(3) DEFAULT NULL,
  `nama_menu` varchar(48) DEFAULT NULL,
  `url` varchar(60) DEFAULT NULL,
  `icon` varchar(55) DEFAULT NULL,
  `num_columns` int(25) DEFAULT 0,
  `make_table` varchar(5) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `id_level`, `nama_menu`, `url`, `icon`, `num_columns`, `make_table`, `created_at`, `updated_at`, `deleted_at`) VALUES
(14, 1, 'Daftar Pengguna ', '?page=daftarPengguna', 'fas fa-fw fa-wrench', 5, 'yes', '2024-07-07', '2024-07-09', NULL),
(17, 1, 'Jurusan', '?page=JurusanDaftar', 'fas fa-fw fa-folder', 4, 'yes', '2024-07-09', '2024-07-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_column`
--

CREATE TABLE `menu_column` (
  `id` int(25) NOT NULL,
  `id_menu` int(25) DEFAULT NULL,
  `nama_column` varchar(45) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_column`
--

INSERT INTO `menu_column` (`id`, `id_menu`, `nama_column`, `created_at`, `updated_at`, `deleted_at`) VALUES
(106, 14, '1', '2024-07-09', '2024-07-09', NULL),
(107, 14, '2', '2024-07-09', '2024-07-09', NULL),
(108, 14, '5', '2024-07-09', '2024-07-09', NULL),
(109, 14, '4', '2024-07-09', '2024-07-09', NULL),
(110, 14, '3', '2024-07-09', '2024-07-09', NULL),
(114, 17, 'No', '2024-07-09', '2024-07-09', NULL),
(115, 17, 'Nama', '2024-07-09', '2024-07-09', NULL),
(116, 17, 'Alamat', '2024-07-09', '2024-07-09', NULL),
(117, 17, 'NIK', '2024-07-09', '2024-07-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(25) NOT NULL,
  `id_level` int(3) NOT NULL,
  `nama_lengkap` varchar(55) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `id_level`, `nama_lengkap`, `email`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Tri Adhy Yulianto', 'adhy@gmail.com', '$2y$12$xBrrUhtpRmCMRjEpqAGQD.uftYEtih8NhPH.m7Ynajg3AEfXb7UES', '2024-07-07', '2024-07-07', NULL),
(2, 2, 'Dani Sumario', 'dani@gmail.com', '$2y$12$xBrrUhtpRmCMRjEpqAGQD.uftYEtih8NhPH.m7Ynajg3AEfXb7UES', '2024-07-07', '2024-07-07', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_level_menu` (`id_level`);

--
-- Indexes for table `menu_column`
--
ALTER TABLE `menu_column`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_column` (`id_menu`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `menu_column`
--
ALTER TABLE `menu_column`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_id_level_menu` FOREIGN KEY (`id_level`) REFERENCES `level` (`id`);

--
-- Constraints for table `menu_column`
--
ALTER TABLE `menu_column`
  ADD CONSTRAINT `fk_menu_column` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
