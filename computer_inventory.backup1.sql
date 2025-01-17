-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 09:08 AM
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
-- Database: `computer_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE `computers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Laptop','Desktop') NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `mac_address` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `existing_user` varchar(255) NOT NULL,
  `other_details` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`id`, `name`, `type`, `serial_number`, `brand`, `ip_address`, `mac_address`, `location`, `department`, `existing_user`, `other_details`, `date_added`) VALUES
(1, 'testpc', 'Desktop', 'abcdf1234567', 'clone pc', '10.10.1000.111', 'ab:ab:cd:0f', 'JJ', 'IBT', 'Desmond', 'test', '2025-01-16 04:27:14'),
(2, 'IT SUPPORT', 'Laptop', '123456789', 'ASUS', '10.10.10.22', '80-A5-89-12-8F-75', 'HQ', 'IT', 'IT SUPPORT', 'TESTING', '2025-01-16 07:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{"view": true, "edit": false, "add": false}' CHECK (json_valid(`permissions`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `permissions`, `created_at`) VALUES
(1, 'admin', '$2y$10$OmXcEwQhkx4zhfIOR.ebVuxl7s6yVp5gR5WeSy1j128KL5ucoTy1u', 'admin', '{\"view\":true,\"edit\":true,\"add\":true}', '2025-01-16 04:24:43'),
(2, 'desmond', '$2y$10$tZiXmfX4ZjFNZ2k1eMX0xuYRSJDLoHcxaeCZ3.BfY41TFPISDKZNC', 'user', '{\"view\":true,\"edit\":false,\"add\":false}', '2025-01-16 06:11:42'),
(3, 'IT Support', '$2y$10$.TUhQhH434hw5n.euFgwqusGY3BKN69tYLNBw55gib4Gz/cKK0DsW', 'user', '{\"view\":true,\"edit\":true,\"add\":true}', '2025-01-16 07:33:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `computers`
--
ALTER TABLE `computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
