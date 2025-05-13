-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 08:17 AM
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
-- Database: `laravel_apr`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_reports`
--

CREATE TABLE `task_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_words` int(11) DEFAULT NULL,
  `tasks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `task_date` date NOT NULL,
  `is_draft` tinyint(1) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `is_hidden_from_writer` tinyint(1) NOT NULL DEFAULT 0,
  `version` int(11) NOT NULL DEFAULT 1,
  `parent_id` bigint(11) DEFAULT NULL,
  `edit_request_status` enum('pending','approved','rejected','archived') DEFAULT NULL,
  `edit_reason` varchar(55) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_reports`
--

INSERT INTO `task_reports` (`id`, `user_id`, `total_words`, `tasks`, `task_date`, `is_draft`, `submitted_at`, `is_hidden_from_writer`, `version`, `parent_id`, `edit_request_status`, `edit_reason`, `created_at`, `updated_at`) VALUES
(5, 8423, 700, '[{\"order_code\":\"UKS39640\",\"nature\":\"New\",\"word_count\":600,\"comments\":\"md\",\"timestamp\":\"2025-05-10 06:17:29\"},{\"order_code\":\"UKS38156\",\"nature\":\"Feedback\",\"word_count\":0,\"comments\":\"fb md\",\"timestamp\":\"2025-05-10 06:17:51\"},{\"order_code\":\"UKS38156\",\"nature\":\"Additional Words\",\"word_count\":100,\"comments\":\"add md\",\"timestamp\":\"2025-05-10 06:18:15\"}]', '2025-05-10', 0, '2025-05-10 00:48:18', 0, 1, NULL, NULL, NULL, '2025-05-10 00:47:29', '2025-05-10 00:48:18'),
(8, 8422, 300, '[{\"order_code\":\"UKS39037\",\"nature\":\"New\",\"word_count\":100,\"comments\":\"first entry\",\"timestamp\":\"2025-05-10 09:08:32\"},{\"order_code\":\"UKS38156\",\"nature\":\"New\",\"word_count\":200,\"comments\":\"second entry\",\"timestamp\":\"2025-05-10 10:56:45\"}]', '2025-05-10', 0, '2025-05-12 07:13:18', 0, 1, NULL, NULL, NULL, '2025-05-10 03:38:32', '2025-05-12 07:13:18'),
(24, 8422, 6100, '[{\"order_code\":\"UKS35664\",\"nature\":\"New\",\"word_count\":100,\"comments\":\"validate\",\"timestamp\":\"2025-05-12 10:21:56\"},{\"order_code\":\"UKS38156\",\"nature\":\"New\",\"word_count\":5000,\"comments\":\"on top\",\"timestamp\":\"2025-05-12 10:37:49\"},{\"order_code\":\"UKS35783\",\"nature\":\"New\",\"word_count\":600,\"comments\":\"tr\",\"timestamp\":\"2025-05-12 10:47:58\"},{\"order_code\":\"UKS38156\",\"nature\":\"New\",\"word_count\":400,\"comments\":\"hello ghj\",\"timestamp\":\"2025-05-12 12:14:01\"}]', '2025-05-12', 0, '2025-05-12 06:51:12', 1, 1, NULL, 'archived', 'need to add the task', '2025-05-12 04:51:56', '2025-05-12 06:52:14'),
(25, 8422, 5000, '[{\"order_code\":\"UKS38156\",\"nature\":\"New\",\"word_count\":5000,\"comments\":\"on top\",\"timestamp\":\"2025-05-12 10:37:49\"}]', '2025-05-12', 1, NULL, 0, 2, 24, 'approved', '', '2025-05-12 06:52:14', '2025-05-12 21:48:52'),
(26, 8422, 1000, '[{\"order_code\":\"UKS38835\",\"nature\":\"New\",\"word_count\":1000,\"comments\":\"hello\",\"timestamp\":\"2025-05-13 03:05:30\"}]', '2025-05-13', 0, '2025-05-12 22:01:58', 1, 1, NULL, 'archived', 'need to edit ', '2025-05-12 21:35:30', '2025-05-12 23:11:43'),
(27, 8422, 100, '[{\"order_code\":\"UKS38835\",\"nature\":\"New\",\"word_count\":100,\"comments\":\"copy\",\"timestamp\":\"2025-05-13 03:05:30\"}]', '2025-05-13', 0, '2025-05-13 00:35:00', 0, 2, 26, 'rejected', 'need to add more task', '2025-05-12 23:11:43', '2025-05-13 00:35:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task_reports`
--
ALTER TABLE `task_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task_reports`
--
ALTER TABLE `task_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
