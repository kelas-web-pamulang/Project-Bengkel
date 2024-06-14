-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 11:44 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_bengkel`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Oli Motor'),
(2, 'Oli Mobil');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `id_category` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `stock` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `id_category`, `id_supplier`, `stock`, `created_at`, `updated_at`, `deleted_at`) VALUES
(20, 'tmo 372', 100000, 2, 1, '46', '2024-06-03 10:35:28', '2024-06-11 08:26:27', NULL),
(22, 'castrol 102', 75000, 1, 1, '22', '2024-06-03 10:54:43', '2024-06-11 08:34:50', NULL),
(23, 'motul 3550', 100000, 1, 2, '19', '2024-06-03 10:58:11', '2024-06-03 11:14:08', NULL),
(24, 'shell 123', 20000, 2, 2, '20', '2024-06-03 11:01:53', '2024-06-03 11:04:54', NULL),
(25, 'tesw', 200000, 1, 2, '20', '2024-06-03 11:05:13', '2024-06-11 16:40:08', '2024-06-11 16:40:08'),
(26, 'castrol 102', 10000, 1, 1, '194', '2024-06-03 11:23:03', '2024-06-03 13:19:44', NULL),
(32, 'fastron platinum', 150000, 2, 1, '96', '2024-06-03 13:16:06', '2024-06-03 13:21:03', NULL),
(33, 'Bardhal', 350000, 2, 1, '325', '2024-06-03 13:37:58', '2024-06-11 15:54:58', '2024-06-11 15:54:58'),
(34, 'radiator', 30000, 2, 1, '25', '2024-06-11 08:51:10', '2024-06-11 08:51:45', '2024-06-11 08:51:45'),
(35, 'radiator', 30000, 1, 2, '25', '2024-06-11 08:55:20', NULL, NULL),
(36, 'motul123', 35000, 2, 2, '31', '2024-06-11 09:07:53', '2024-06-11 14:46:27', '2024-06-11 14:46:27'),
(37, 'Oli Transmisi', 45000, 2, 1, '49', '2024-06-11 14:46:52', NULL, NULL),
(38, '&lt;script&gt;alert(test)&lt;/script&gt;', 30000, 2, 2, '30', '2024-06-11 15:21:04', '2024-06-11 15:22:42', '2024-06-11 15:22:42'),
(39, '&lt;script&gt;alert(test)&lt;/script&gt;', 28000, 1, 1, '23', '2024-06-11 15:23:18', '2024-06-11 16:40:13', '2024-06-11 16:40:13'),
(40, '&lt;script&gt;alert(test)&lt;/script&gt;', 67000, 2, 1, '24', '2024-06-11 16:03:56', NULL, NULL),
(41, 'Fastron Gold 5w-30', 75000, 1, 2, '48', '2024-06-11 16:40:55', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`) VALUES
(1, 'Rudi'),
(2, 'Samson');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `tgl_buat` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `email`, `password`, `full_name`, `role`, `tgl_buat`) VALUES
(2, 'digmadani90@gmail.com', '$2y$10$a34O4uOn1j2/6s9xwHi.m.yDCvEKWQzoNn8DWv1HvdUQTCJ69FiWK', 'digma dani', 'admin', '2024-06-10 09:29:29'),
(3, 'faren@gmail.com', '$2y$10$Xj1xF1.13F8H8Wg2ECUCV.zF1/6oPtagKy2qDgfpxk0jNZMQ4Vshy', 'faren', 'admin', '2024-06-10 09:48:10'),
(4, 'digmadani2018@gmail.com', '$2y$10$.qDhxJu//5ju0kCrr9HkeepaoYOIT64Tp1RqSG4utnXlK.vBtZXz2', 'digma dani', 'admin', '2024-06-10 10:57:45'),
(5, 'digmadani2018@gmail.com', '$2y$10$LOheGoV/Rem78xuIyxMRTOnqpygJzJ5BpUU.//PmcKHISrohmrX26', 'digma dani', 'admin', '2024-06-10 10:59:04'),
(6, 'digmadani2018@gmail.com', '$2y$10$G3TD.29RztTzN415nXgRG.o4i5F8E1TTS8cho03VZMgCiwWA.jUya', 'digma dani', 'admin', '2024-06-10 11:02:11'),
(7, 'digma@gmail.com', '$2y$10$.vhfUmjklcZhhnNy8yo.medayPQEASQSDF/CxRUvSPOWVH3QhrgOq', 'dani', 'admin', '2024-06-11 03:18:25'),
(8, 'digmadani87@gmail.com', '$2y$10$hGWeZrOY5GpgP6JWkzelW.NXZ44tg9J58KbJ9SQwH1n.qclb/OJNq', 'Dani', 'admin', '2024-06-11 11:42:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `fk_supplier` (`id_supplier`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_category` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
