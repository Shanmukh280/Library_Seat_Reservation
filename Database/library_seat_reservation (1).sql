-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 12:28 PM
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
-- Database: `library_seat_reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_records`
--

CREATE TABLE `login_records` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_records`
--

INSERT INTO `login_records` (`id`, `student_id`, `login_time`) VALUES
(4, 'N210215', '2025-02-05 15:22:29'),
(5, 'N210215', '2025-02-05 15:25:37'),
(6, 'N210215', '2025-02-05 16:27:33'),
(7, 'N200215', '2025-02-05 18:09:39'),
(8, 'N200215', '2025-02-05 18:10:41'),
(9, 'N210210', '2025-02-05 19:11:58'),
(10, 'N210210', '2025-02-05 19:16:13'),
(11, 'N210210', '2025-02-05 19:17:14'),
(12, 'N210210', '2025-02-05 20:04:22'),
(13, 'N210210', '2025-02-05 20:46:14'),
(14, 'N210210', '2025-02-05 21:56:10'),
(15, 'N210210', '2025-02-05 22:28:18'),
(16, 'N210210', '2025-02-05 23:15:52'),
(17, 'N210210', '2025-02-05 23:17:37'),
(18, 'N210210', '2025-02-05 23:35:23'),
(19, 'N210219', '2025-02-06 01:09:05'),
(20, 'N210211', '2025-02-06 01:44:25'),
(21, 'N210211', '2025-02-06 01:50:04'),
(22, 'N210211', '2025-02-06 01:52:46'),
(23, 'N210211', '2025-02-06 02:08:33'),
(24, 'N210211', '2025-02-06 02:22:08'),
(25, 'N210111', '2025-02-06 02:38:13'),
(26, 'N210111', '2025-02-06 03:21:51'),
(27, 'N210111', '2025-02-06 03:24:06'),
(28, 'N210111', '2025-02-06 03:33:17'),
(29, 'N210210', '2025-02-06 03:36:43'),
(30, 'N210111', '2025-02-06 03:44:22'),
(31, 'N210111', '2025-02-06 03:44:58'),
(32, 'N210111', '2025-02-06 11:48:21'),
(33, 'N210111', '2025-02-06 12:05:02'),
(34, 'N210111', '2025-02-07 12:10:03'),
(35, 'N210111', '2025-02-07 12:11:44'),
(36, 'N210111', '2025-02-07 12:11:55'),
(37, 'N210258', '2025-02-07 14:14:42'),
(38, 'N210143', '2025-02-07 14:20:43'),
(39, 'N210143', '2025-02-07 14:22:40'),
(40, 'N210258', '2025-02-07 14:34:15'),
(41, 'N210343', '2025-02-07 14:40:14'),
(42, 'N210111', '2025-02-07 14:44:28'),
(43, 'N200215', '2025-02-07 14:47:01'),
(44, 'N210211', '2025-02-07 14:58:01'),
(45, 'N210343', '2025-02-07 15:03:54'),
(46, 'N140014', '2025-02-07 18:04:42'),
(47, 'N140014', '2025-02-07 18:07:34'),
(48, 'N150150', '2025-02-07 20:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat_reservations`
--

CREATE TABLE `seat_reservations` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `reservation_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) NOT NULL DEFAULT 'assets/uploads/about.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`id`, `student_id`, `name`, `email`, `gender`, `password`, `profile_pic`) VALUES
(7, 'N210215', 'Srinu', 'srinu@gmail.com', 'Male', '$2y$10$Y2zUMekXvO2IYON4yIet2uoSt3GMSiTtWrQLlYJa9RNnLS3eqtqHe', 'assets/uploads/about.jpg'),
(8, 'N200215', 'Bogineni Veeravenkata Ramana', 'ramana@gmail.com', 'Male', '$2y$10$mrQxPftWXFPdUW73W9L1R.St0EIdvreorHp8w3bAkbh3XIzxND2hW', 'photo_2024-12-16_15-55-28.jpg'),
(9, 'N210210', 'Shanmukh', 'shanmuk@gmail.com', 'Male', '$2y$10$QZBlMUoXMnCC6vXI79RjXuCcBuI5CMm6xIb..WJf0xJu6KH7NymWu', 'assets/uploads/about.jpg'),
(10, 'N210219', 'Veera Raghava', 'ragava@gmail.com', 'Male', '$2y$10$xwY5uvsQ/r1Kj3Sm0GNrzeNiVA5UF9U9W5Z6YlPBEnGW8siRChBmK', 'assets/uploads/about.jpg'),
(17, 'N210343', 'Seetha Rama raju', 'n21034312@gmail.com', 'Male', '$2y$10$Pq6/oFmK54q7r6EtHkavAux.xABNngzWsYcVgiE8VdGoN.0/G8Y/2', 'Seetharamaraju.jpg'),
(18, 'N140014', 'Kajal', 'n@gmail.com', 'Female', '$2y$10$R0pEi5WSCDfxbIeeJyIs9.g/MEnaw5fJmIDBBK9NOhZ1OpaBe.HIi', 'kajal.jpg'),
(19, 'N150150', 'Keerthi', 'keerthana@gmail.com', 'Female', '$2y$10$dh7.TA7AIPzEEWh7.Hh/duuzrG4Z4THFzWuHyBxRy8ti0FpuFypF.', 'profile.jfif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_records`
--
ALTER TABLE `login_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD UNIQUE KEY `seat_number` (`seat_number`);

--
-- Indexes for table `seat_reservations`
--
ALTER TABLE `seat_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Indexes for table `student_details`
--
ALTER TABLE `student_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_records`
--
ALTER TABLE `login_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_reservations`
--
ALTER TABLE `seat_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_details`
--
ALTER TABLE `student_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_records`
--
ALTER TABLE `login_records`
  ADD CONSTRAINT `login_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`student_id`);

--
-- Constraints for table `seat_reservations`
--
ALTER TABLE `seat_reservations`
  ADD CONSTRAINT `seat_reservations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_details` (`student_id`),
  ADD CONSTRAINT `seat_reservations_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
