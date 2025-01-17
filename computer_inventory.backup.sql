-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 08:04 AM
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
  `processor` varchar(255) NOT NULL,
  `ram` varchar(50) NOT NULL,
  `hard_disk` varchar(50) NOT NULL,
  `os` varchar(255) NOT NULL,
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

INSERT INTO `computers` (`id`, `name`, `type`, `serial_number`, `processor`, `ram`, `hard_disk`, `os`, `brand`, `ip_address`, `mac_address`, `location`, `department`, `existing_user`, `other_details`, `date_added`) VALUES
(1, 'testpc', 'Desktop', 'abcdf1234567', 'Intel i5 4440', '4 GB', '1TB SSD', 'windows 10', 'clone pc', '10.10.1000.111', 'ab:ab:cd:0f', 'JJ', 'IBT', 'Desmond', 'test', '2025-01-16 04:27:14'),
(4, 'computer2', 'Laptop', 'xxyz888uuhzx1', 'intel i3', '8 GB', '512GB', 'windows 11', 'ACER', '10.10.10.99', 'AA-CC-09-F1', 'JJ', 'Account', 'Mr. X', 'test', '2025-01-17 00:08:18'),
(5, 'Dell Laptop', 'Laptop', 'SN123', 'Intel i5', '8GB', '512GB SSD', 'Windows 10', 'Dell', '192.168.1.2', '00:1A:2B:3C:4D:5E', 'Office A', 'IT', 'John Doe', 'Test Details', '2025-01-17 00:19:05'),
(6, 'HP Desktop', 'Desktop', 'SN124', 'AMD Ryzen 7', '16GB', '1TB HDD', 'Windows 11', 'HP', '192.168.1.3', '00:1A:2B:3C:4D:5F', 'Office B', 'HR', 'Jane Smith', 'Test Details', '2025-01-17 00:19:05'),
(7, 'Ong_PC', 'Laptop', 'A86SD77ZN6', 'intel i3 Gen10', '12 GB', '1TB SSD', 'Windows 11', 'Lenovo', '10.10.10.11', 'A8:FF:03:E4', 'Segamat', 'Office', 'Nurul', 'Admin PC', '2025-01-17 01:19:34');

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
(2, 'desmond', '$2y$10$s3tExbp1RuF7W1cxBBQOvuM6GBe48nzfhgGj0Mkh2PQuWlcAWd5j2', 'user', '{\"view\":true,\"edit\":false,\"add\":false}', '2025-01-16 06:11:42'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
