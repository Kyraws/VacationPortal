-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 03:26 PM
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
-- Database: `vacationportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `reason`, `date_submitted`, `start_date`, `end_date`, `status`) VALUES
(1, 2, 'Vacation', '2025-02-06 14:26:05', '2025-03-30', '2025-04-06', 'pending'),
(2, 2, 'Conference Attendance', '2025-02-06 14:26:05', '2025-03-30', '2025-04-23', 'pending'),
(3, 2, 'Training Program', '2025-02-06 14:26:05', '2025-03-30', '2025-04-14', 'pending'),
(4, 3, 'Evan Black', '2025-02-06 14:26:05', '2025-03-30', '2025-05-06', 'pending'),
(5, 4, 'Fiona Green', '2025-02-06 14:26:05', '2025-03-30', '2025-04-11', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','employee') NOT NULL,
  `employee_code` char(7) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `name`, `email`, `password`, `role`, `employee_code`, `created_at`) VALUES
(1, 'manager', 'Alice Johnson', 'manager@example.com', '$2y$12$7ReD05tqQHYgVe5VNiXr8ei..4AjV.K5quUM52uXmwe0osSNaFao6', 'manager', NULL, '2025-02-06 14:26:04'),
(2, 'emp001', 'Bob Smith', 'bob.smith@example.com', '$2y$12$8SMST9vIxwNMl61LVy0C6OeFtXD0LGUMqV1nkMS6cjjW0uYHSQETO', 'employee', '1234567', '2025-02-06 14:26:04'),
(3, 'emp002', 'Charlie Adams', 'charlie.adams@example.com', '$2y$12$XhqB4diq7hilm0zHnqJNP.5B7Zm4ygMZ8N8xIHBmqndfHBjL2Si/e', 'employee', '2345678', '2025-02-06 14:26:04'),
(4, 'emp003', 'Dana White', 'dana.white@example.com', '$2y$12$EtWryHib97o7mwKI7SYWu.oNbhuYxGxWWBATQddB0Q.XAnngsMqyy', 'employee', '3456789', '2025-02-06 14:26:04'),
(5, 'emp004', 'Evan Black', 'evan.black@example.com', '$2y$12$fenLb6/YNbaBR9kQtNvVQ.zv2oVehSmmNkQy9EXPVo7LXNr7fQdqy', 'employee', '4567890', '2025-02-06 14:26:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `employee_code` (`employee_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
