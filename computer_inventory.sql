CREATE DATABASE computer_inventory;

USE computer_inventory;

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

