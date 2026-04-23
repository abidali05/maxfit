-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 03, 2026 at 02:22 AM
-- Server version: 11.4.9-MariaDB-cll-lve-log
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coolhpkn_maxfit`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` int(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `code`, `email`, `phone`, `password`, `image`, `bio`, `percentage`, `address`, `country_id`, `city_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Syed F', NULL, 'sales@skmgwholesale.com', '(281) 968-5103', '$2y$12$86oHMJKZUkDp.BnxtK1Wv.CnFHn8Hfx5q1ZUHV26mE5fWn6gtocSe', 'branches/Lbr8FYJBlZo2ROxjH38tX7evnDY3qXTRSHUZbTnW.jpg', 'huihuiih', 78.00, 'jk', 166, 1, 'active', '2025-08-26 09:44:54', '2025-08-26 09:45:24'),
(2, 'qweqwrt', NULL, 'bracnh6@gmail.com', '78888', '$2y$12$dpqc.teSR9ZX/O7dydgu8OXrkFFR9OJQJ38TvKgkOzjIqQLph4WoW', 'branches/QwInRlXeEDWbz1qkqPDeoBTgMwDSgFWw7Kgsmjzh.png', 'njsdgnjksgsdg', 50.00, 'dsfsdf', 166, 461, 'active', '2025-08-26 15:19:14', '2025-08-26 15:19:36'),
(3, 'Lillith Long', 328627, 'hewewaxeho@mailinator.com', '+1 (577) 692-7811', '$2y$12$o2hZHIU1vs0aYFXdMDtyVuXdvsdkkBrb8RUrza71zTLwZmISBLH3q', 'branches/oS3ntj3gCqeIkY1GD6lKqphwOa4KoTPu07kY0UIG.jpg', 'Iusto animi animi', 30.00, 'Quos quia voluptatem duis sapiente delectus voluptatibus fugiat ad et quo', 133, 460, 'active', '2025-12-11 16:14:30', '2025-12-11 16:14:30'),
(4, 'Emmanuel Luna', 528902, 'rinybaq@mailinator.com', '+1 (617) 914-8342', '$2y$12$u2V21Cp8bG13GqRc3buLL.J2Baw81qcVvoI44BhJ4jF1kjsR1Sogu', NULL, 'Maiores architecto i', 8.00, 'Do ut iure ut laborum Dolorem omnis officia unde anim nulla minus alias quas sapiente eum consequuntur', 120, 425, 'active', '2025-12-11 16:16:03', '2025-12-11 16:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `challenger_id` bigint(20) UNSIGNED NOT NULL,
  `challenge_to_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`id`, `challenger_id`, `challenge_to_id`, `venue_id`, `start_date`, `end_date`, `created_at`, `updated_at`, `start_time`, `end_time`, `status`, `rejection_reason`) VALUES
(24, 156, 157, 1, '2026-01-30', '2026-01-30', '2026-01-29 16:18:03', '2026-01-29 16:19:48', '16:27:00', '16:37:00', 'accepted', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `challenge_exercises`
--

CREATE TABLE `challenge_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `challenge_id` bigint(20) UNSIGNED NOT NULL,
  `set_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `challenge_exercises`
--

INSERT INTO `challenge_exercises` (`id`, `challenge_id`, `set_id`, `exercise_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 5, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(2, 3, 3, 5, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(3, 3, 4, 5, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(4, 3, 5, 5, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(5, 3, 6, 5, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(6, 3, 33, 5, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(7, 3, 8, 6, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(8, 3, 9, 6, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(9, 3, 10, 6, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(10, 3, 12, 6, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(11, 3, 16, 6, '2025-12-10 13:04:39', '2025-12-10 13:04:39'),
(12, 4, 5, 1, '2025-12-11 13:16:21', '2025-12-11 13:16:21'),
(13, 4, 5, 3, '2025-12-11 13:16:21', '2025-12-11 13:16:21'),
(14, 4, 5, 4, '2025-12-11 13:16:21', '2025-12-11 13:16:21'),
(15, 4, 5, 5, '2025-12-11 13:16:21', '2025-12-11 13:16:21'),
(16, 4, 5, 6, '2025-12-11 13:16:21', '2025-12-11 13:16:21'),
(17, 4, 5, 33, '2025-12-11 13:16:21', '2025-12-11 13:16:21'),
(18, 5, 5, 1, '2025-12-11 13:18:45', '2025-12-11 13:18:45'),
(19, 5, 5, 3, '2025-12-11 13:18:45', '2025-12-11 13:18:45'),
(20, 5, 5, 4, '2025-12-11 13:18:45', '2025-12-11 13:18:45'),
(21, 5, 5, 5, '2025-12-11 13:18:45', '2025-12-11 13:18:45'),
(22, 5, 5, 6, '2025-12-11 13:18:45', '2025-12-11 13:18:45'),
(23, 5, 5, 33, '2025-12-11 13:18:45', '2025-12-11 13:18:45'),
(24, 6, 5, 1, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(25, 6, 5, 3, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(26, 6, 5, 4, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(27, 6, 5, 5, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(28, 6, 5, 6, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(29, 6, 5, 33, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(30, 6, 6, 8, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(31, 6, 6, 9, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(32, 6, 6, 10, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(33, 6, 6, 12, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(34, 6, 6, 16, '2025-12-11 13:19:44', '2025-12-11 13:19:44'),
(35, 7, 5, 1, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(36, 7, 5, 3, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(37, 7, 5, 4, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(38, 7, 5, 5, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(39, 7, 5, 6, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(40, 7, 5, 33, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(41, 7, 6, 8, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(42, 7, 6, 9, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(43, 7, 6, 10, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(44, 7, 6, 12, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(45, 7, 6, 16, '2025-12-11 13:21:06', '2025-12-11 13:21:06'),
(46, 8, 5, 1, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(47, 8, 5, 3, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(48, 8, 5, 4, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(49, 8, 5, 5, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(50, 8, 5, 6, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(51, 8, 5, 33, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(52, 8, 6, 8, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(53, 8, 6, 9, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(54, 8, 6, 10, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(55, 8, 6, 12, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(56, 8, 6, 16, '2025-12-11 13:23:30', '2025-12-11 13:23:30'),
(57, 9, 5, 1, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(58, 9, 5, 3, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(59, 9, 5, 4, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(60, 9, 5, 5, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(61, 9, 5, 6, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(62, 9, 5, 33, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(63, 9, 6, 8, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(64, 9, 6, 9, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(65, 9, 6, 10, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(66, 9, 6, 12, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(67, 9, 6, 16, '2025-12-11 13:28:46', '2025-12-11 13:28:46'),
(68, 10, 5, 1, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(69, 10, 5, 3, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(70, 10, 5, 4, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(71, 10, 5, 5, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(72, 10, 5, 6, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(73, 10, 5, 33, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(74, 10, 6, 8, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(75, 10, 6, 9, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(76, 10, 6, 10, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(77, 10, 6, 12, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(78, 10, 6, 16, '2025-12-11 13:31:36', '2025-12-11 13:31:36'),
(79, 11, 5, 1, '2025-12-22 11:58:14', '2025-12-22 11:58:14'),
(80, 11, 5, 3, '2025-12-22 11:58:14', '2025-12-22 11:58:14'),
(81, 11, 5, 4, '2025-12-22 11:58:14', '2025-12-22 11:58:14'),
(82, 11, 5, 5, '2025-12-22 11:58:14', '2025-12-22 11:58:14'),
(83, 11, 5, 6, '2025-12-22 11:58:14', '2025-12-22 11:58:14'),
(84, 11, 5, 33, '2025-12-22 11:58:14', '2025-12-22 11:58:14'),
(85, 12, 5, 1, '2025-12-22 16:12:36', '2025-12-22 16:12:36'),
(86, 12, 6, 8, '2025-12-22 16:12:36', '2025-12-22 16:12:36'),
(87, 12, 7, 18, '2025-12-22 16:12:36', '2025-12-22 16:12:36'),
(88, 12, 8, 24, '2025-12-22 16:12:36', '2025-12-22 16:12:36'),
(89, 12, 9, 20, '2025-12-22 16:12:36', '2025-12-22 16:12:36'),
(90, 13, 5, 1, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(91, 13, 5, 3, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(92, 13, 5, 4, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(93, 13, 5, 5, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(94, 13, 5, 6, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(95, 13, 5, 33, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(96, 13, 6, 10, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(97, 13, 6, 9, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(98, 13, 7, 22, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(99, 13, 7, 27, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(100, 13, 8, 23, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(101, 13, 8, 24, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(102, 13, 9, 20, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(103, 13, 9, 21, '2025-12-25 16:52:22', '2025-12-25 16:52:22'),
(104, 14, 13, 21, '2026-01-19 12:19:41', '2026-01-19 12:19:41'),
(105, 14, 13, 3, '2026-01-19 12:19:41', '2026-01-19 12:19:41'),
(106, 15, 13, 8, '2026-01-25 13:41:38', '2026-01-25 13:41:38'),
(107, 15, 13, 28, '2026-01-25 13:41:38', '2026-01-25 13:41:38'),
(108, 16, 13, 21, '2026-01-26 13:02:16', '2026-01-26 13:02:16'),
(109, 16, 13, 3, '2026-01-26 13:02:16', '2026-01-26 13:02:16'),
(110, 17, 13, 21, '2026-01-26 13:14:34', '2026-01-26 13:14:34'),
(111, 17, 13, 3, '2026-01-26 13:14:34', '2026-01-26 13:14:34'),
(112, 17, 13, 28, '2026-01-26 13:14:34', '2026-01-26 13:14:34'),
(113, 17, 13, 8, '2026-01-26 13:14:34', '2026-01-26 13:14:34'),
(114, 18, 13, 21, '2026-01-27 16:09:31', '2026-01-27 16:09:31'),
(115, 18, 13, 3, '2026-01-27 16:09:31', '2026-01-27 16:09:31'),
(116, 18, 13, 28, '2026-01-27 16:09:31', '2026-01-27 16:09:31'),
(117, 18, 13, 8, '2026-01-27 16:09:31', '2026-01-27 16:09:31'),
(118, 19, 13, 21, '2026-01-27 16:15:20', '2026-01-27 16:15:20'),
(119, 19, 13, 3, '2026-01-27 16:15:20', '2026-01-27 16:15:20'),
(120, 19, 13, 28, '2026-01-27 16:15:20', '2026-01-27 16:15:20'),
(121, 19, 13, 8, '2026-01-27 16:15:20', '2026-01-27 16:15:20'),
(122, 20, 13, 21, '2026-01-27 16:16:59', '2026-01-27 16:16:59'),
(123, 20, 13, 3, '2026-01-27 16:16:59', '2026-01-27 16:16:59'),
(124, 20, 13, 28, '2026-01-27 16:16:59', '2026-01-27 16:16:59'),
(125, 20, 13, 8, '2026-01-27 16:16:59', '2026-01-27 16:16:59'),
(126, 21, 13, 3, '2026-01-27 16:19:53', '2026-01-27 16:19:53'),
(127, 21, 13, 28, '2026-01-27 16:19:53', '2026-01-27 16:19:53'),
(128, 22, 13, 3, '2026-01-27 16:20:16', '2026-01-27 16:20:16'),
(129, 22, 13, 28, '2026-01-27 16:20:16', '2026-01-27 16:20:16'),
(130, 23, 13, 21, '2026-01-28 11:46:51', '2026-01-28 11:46:51'),
(131, 23, 13, 3, '2026-01-28 11:46:51', '2026-01-28 11:46:51'),
(132, 24, 13, 8, '2026-01-29 16:18:03', '2026-01-29 16:18:03'),
(133, 24, 13, 28, '2026-01-29 16:18:03', '2026-01-29 16:18:03'),
(134, 24, 13, 3, '2026-01-29 16:18:03', '2026-01-29 16:18:03');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`, `created_at`, `updated_at`) VALUES
(1, 'Lahore', 1, NULL, NULL),
(2, 'Faisalabad', 1, NULL, NULL),
(3, 'Rawalpindi', 1, NULL, NULL),
(4, 'Gujranwala', 1, NULL, NULL),
(5, 'Multan', 1, NULL, NULL),
(6, 'Sialkot', 1, NULL, NULL),
(7, 'Bahawalpur', 1, NULL, NULL),
(8, 'Sargodha', 1, NULL, NULL),
(9, 'Sheikhupura', 1, NULL, NULL),
(10, 'Mandi Bahauddin', 1, NULL, NULL),
(11, 'Rahim Yar Khan', 1, NULL, NULL),
(12, 'Gujrat', 1, NULL, NULL),
(13, 'Okara', 1, NULL, NULL),
(14, 'Sahiwal', 1, NULL, NULL),
(15, 'Chiniot', 1, NULL, NULL),
(16, 'Khanewal', 1, NULL, NULL),
(17, 'Dera Ghazi Khan', 1, NULL, NULL),
(18, 'Vehari', 1, NULL, NULL),
(19, 'Attock', 1, NULL, NULL),
(20, 'Chakwal', 1, NULL, NULL),
(21, 'Bhakkar', 1, NULL, NULL),
(22, 'Layyah', 1, NULL, NULL),
(23, 'Lodhran', 1, NULL, NULL),
(24, 'Hafizabad', 1, NULL, NULL),
(25, 'Pakpattan', 1, NULL, NULL),
(26, 'Muzaffargarh', 1, NULL, NULL),
(27, 'Rajanpur', 1, NULL, NULL),
(28, 'Toba Tek Singh', 1, NULL, NULL),
(29, 'Khushab', 1, NULL, NULL),
(30, 'Karachi', 2, NULL, NULL),
(31, 'Hyderabad', 2, NULL, NULL),
(32, 'Sukkur', 2, NULL, NULL),
(33, 'Larkana', 2, NULL, NULL),
(34, 'Nawabshah', 2, NULL, NULL),
(35, 'Mirpurkhas', 2, NULL, NULL),
(36, 'Shikarpur', 2, NULL, NULL),
(37, 'Jacobabad', 2, NULL, NULL),
(38, 'Khairpur', 2, NULL, NULL),
(39, 'Badin', 2, NULL, NULL),
(40, 'Thatta', 2, NULL, NULL),
(41, 'Dadu', 2, NULL, NULL),
(42, 'Umerkot', 2, NULL, NULL),
(43, 'Sanghar', 2, NULL, NULL),
(44, 'Ghotki', 2, NULL, NULL),
(45, 'Kashmore', 2, NULL, NULL),
(46, 'Matiari', 2, NULL, NULL),
(47, 'Jamshoro', 2, NULL, NULL),
(48, 'Qambar Shahdadkot', 2, NULL, NULL),
(49, 'Peshawar', 3, NULL, NULL),
(50, 'Mardan', 3, NULL, NULL),
(51, 'Swat', 3, NULL, NULL),
(52, 'Abbottabad', 3, NULL, NULL),
(53, 'Kohat', 3, NULL, NULL),
(54, 'Bannu', 3, NULL, NULL),
(55, 'Dera Ismail Khan', 3, NULL, NULL),
(56, 'Mansehra', 3, NULL, NULL),
(57, 'Charsadda', 3, NULL, NULL),
(58, 'Nowshera', 3, NULL, NULL),
(59, 'Swabi', 3, NULL, NULL),
(60, 'Lakki Marwat', 3, NULL, NULL),
(61, 'Haripur', 3, NULL, NULL),
(62, 'Malakand', 3, NULL, NULL),
(63, 'Karak', 3, NULL, NULL),
(64, 'Tank', 3, NULL, NULL),
(65, 'Lower Dir', 3, NULL, NULL),
(66, 'Upper Dir', 3, NULL, NULL),
(67, 'Buner', 3, NULL, NULL),
(68, 'Quetta', 4, NULL, NULL),
(69, 'Gwadar', 4, NULL, NULL),
(70, 'Turbat', 4, NULL, NULL),
(71, 'Khuzdar', 4, NULL, NULL),
(72, 'Sibi', 4, NULL, NULL),
(73, 'Zhob', 4, NULL, NULL),
(74, 'Hub', 4, NULL, NULL),
(75, 'Dera Murad Jamali', 4, NULL, NULL),
(76, 'Loralai', 4, NULL, NULL),
(77, 'Chaman', 4, NULL, NULL),
(78, 'Panjgur', 4, NULL, NULL),
(79, 'Mastung', 4, NULL, NULL),
(80, 'Kharan', 4, NULL, NULL),
(81, 'Awaran', 4, NULL, NULL),
(82, 'Musakhel', 4, NULL, NULL),
(83, 'Jaffarabad', 4, NULL, NULL),
(84, 'Washuk', 4, NULL, NULL),
(85, 'Islamabad', 5, NULL, NULL),
(86, 'Muzaffarabad', 6, NULL, NULL),
(87, 'Mirpur', 6, NULL, NULL),
(88, 'Kotli', 6, NULL, NULL),
(89, 'Bagh', 6, NULL, NULL),
(90, 'Bhimber', 6, NULL, NULL),
(91, 'Neelum', 6, NULL, NULL),
(92, 'Poonch', 6, NULL, NULL),
(93, 'Hattian Bala', 6, NULL, NULL),
(94, 'Gilgit', 7, NULL, NULL),
(95, 'Skardu', 7, NULL, NULL),
(96, 'Hunza', 7, NULL, NULL),
(97, 'Diamer', 7, NULL, NULL),
(98, 'Ghizer', 7, NULL, NULL),
(99, 'Astore', 7, NULL, NULL),
(100, 'Ghanche', 7, NULL, NULL),
(101, 'Renala Khurd', 1, NULL, NULL),
(102, 'Jhelum\r\n\r\n', 1, NULL, NULL),
(103, 'Kasur', 1, NULL, NULL),
(104, 'Mianwali', 1, NULL, NULL),
(105, 'Wah Cantonment', 1, NULL, NULL),
(106, 'Murree', 1, NULL, NULL),
(107, 'Narowal', 1, NULL, NULL),
(108, 'Jhang', 1, NULL, NULL),
(109, 'Bahawalnagar', 1, NULL, NULL),
(110, 'Rajana', 1, NULL, NULL),
(111, 'Fort Abbas', 1, NULL, NULL),
(112, 'Kot Addu', 1, NULL, NULL),
(113, 'Shakargarh', 1, NULL, NULL),
(114, 'Kabirwala', 1, NULL, NULL),
(115, 'Samundri', 1, NULL, NULL),
(116, 'Kharian', 1, NULL, NULL),
(117, 'Pindi Bhattian', 1, NULL, NULL),
(118, 'Pindi Bhattian', 1, NULL, NULL),
(119, 'Shorkot', 1, NULL, NULL),
(120, 'Ahmedpur East', 1, NULL, NULL),
(121, 'Dina', 1, NULL, NULL),
(122, 'Chishtian', 1, NULL, NULL),
(123, 'Muridke', 1, NULL, NULL),
(124, 'Pattoki', 1, NULL, NULL),
(125, 'Farooqabad', 1, NULL, NULL),
(126, 'Kot Radha Kishan', 1, NULL, NULL),
(127, 'Kanganpur', 1, NULL, NULL),
(128, 'Dipalpur', 1, NULL, NULL),
(129, 'Mailsi', 1, NULL, NULL),
(130, 'Ferozewala', 1, NULL, NULL),
(131, 'Noor Pur Thal', 1, NULL, NULL),
(132, 'Khanpur', 1, NULL, NULL),
(133, 'Shahkot', 1, NULL, NULL),
(134, 'Burewala', 1, NULL, NULL),
(135, 'Alipur', 1, NULL, NULL),
(136, 'Hasilpur', 1, NULL, NULL),
(137, 'Rojhan', 1, NULL, NULL),
(138, 'Raiwind', 1, NULL, NULL),
(139, 'Jaranwala', 1, NULL, NULL),
(140, 'Chunian', 1, NULL, NULL),
(141, 'Jauharabad', 1, NULL, NULL),
(142, 'Safdarabad', 1, NULL, NULL),
(143, 'Daska', 1, NULL, NULL),
(144, 'Tandlianwala', 1, NULL, NULL),
(145, 'Kot Momin', 1, NULL, NULL),
(146, 'Bhalwal', 1, NULL, NULL),
(147, 'Darya Khan', 1, NULL, NULL),
(148, 'Gojra', 1, NULL, NULL),
(149, 'Pir Mahal', 1, NULL, NULL),
(150, 'Kundian', 1, NULL, NULL),
(151, 'Khalabat Township', 1, NULL, NULL),
(152, 'Mitha Tiwana', 1, NULL, NULL),
(153, 'Sarai Alamgir', 1, NULL, NULL),
(154, 'Sohawa', 1, NULL, NULL),
(155, 'Jampur', 1, NULL, NULL),
(156, 'Taunsa', 1, NULL, NULL),
(157, 'Kunjah', 1, NULL, NULL),
(158, 'Talagang', 1, NULL, NULL),
(159, 'Shahpur', 1, NULL, NULL),
(160, 'Harnoli', 1, NULL, NULL),
(161, 'Kala Shah Kaku', 1, NULL, NULL),
(162, 'Mangla', 1, NULL, NULL),
(163, 'Mangowal Gharbi', 1, NULL, NULL),
(164, 'Sillanwali', 1, NULL, NULL),
(165, 'Baghbanpura', 1, NULL, NULL),
(166, 'Ghakhar Mandi', 1, NULL, NULL),
(167, 'Kalur Kot', 1, NULL, NULL),
(168, 'Haroonabad', 1, NULL, NULL),
(169, 'Kotli Loharan', 1, NULL, NULL),
(170, 'Narali', 1, NULL, NULL),
(171, 'Manawan', 1, NULL, NULL),
(172, 'Rakhni', 1, NULL, NULL),
(173, 'Kot Mithan', 1, NULL, NULL),
(174, 'Sodhra', 1, NULL, NULL),
(175, 'Chawinda', 1, NULL, NULL),
(176, 'Nankana Sahib', 1, NULL, NULL),
(177, 'Manga Mandi', 1, NULL, NULL),
(178, 'Sarai Sidhu', 1, NULL, NULL),
(179, 'Sargodha Cantt', 1, NULL, NULL),
(180, 'Jand', 1, NULL, NULL),
(181, 'Wazirabad', 1, NULL, NULL),
(182, 'Kamoke', 1, NULL, NULL),
(183, 'Sadiqabad', 1, NULL, NULL),
(184, 'Kot Abdul Malik', 1, NULL, NULL),
(185, 'Arif Wala', 1, NULL, NULL),
(186, 'Gujranwala Cantonment', 1, NULL, NULL),
(187, 'Jatoi', 1, NULL, NULL),
(188, 'Mian Channu', 1, NULL, NULL),
(189, 'Taxila', 1, NULL, NULL),
(190, 'Haveli Lakha', 1, NULL, NULL),
(191, 'Lalamusa', 1, NULL, NULL),
(192, 'Sambrial', 1, NULL, NULL),
(193, 'Taunsa Sharif', 1, NULL, NULL),
(194, 'Phool Nagar', 1, NULL, NULL),
(195, 'Chichawatni', 1, NULL, NULL),
(196, 'Gujar Khan', 1, NULL, NULL),
(197, 'Pasrur', 1, NULL, NULL),
(198, 'Ludhewala Waraich', 1, NULL, NULL),
(199, 'Mithi', 2, NULL, NULL),
(200, 'Tando Allahyar', 2, NULL, NULL),
(201, 'Tando Adam', 2, NULL, NULL),
(202, 'Kandhkot', 2, NULL, NULL),
(203, 'Moro', 2, NULL, NULL),
(204, 'Sehwan', 2, NULL, NULL),
(205, 'Hala', 2, NULL, NULL),
(206, 'Kotri', 2, NULL, NULL),
(207, 'Johi', 2, NULL, NULL),
(208, 'Tando Muhammad Khan', 2, NULL, NULL),
(209, 'Chachro', 2, NULL, NULL),
(210, 'Thul', 2, NULL, NULL),
(211, 'Sujawal', 2, NULL, NULL),
(212, 'Pano Akil', 2, NULL, NULL),
(213, 'Khipro', 2, NULL, NULL),
(214, 'Ratodero', 2, NULL, NULL),
(215, 'Shahdadkot', 2, NULL, NULL),
(216, 'Kot Diji', 2, NULL, NULL),
(217, 'Gambat', 2, NULL, NULL),
(218, 'Tangwani', 2, NULL, NULL),
(219, 'Mirpur Mathelo', 2, NULL, NULL),
(220, 'Sobho Dero', 2, NULL, NULL),
(221, 'Ranipur', 2, NULL, NULL),
(222, 'Shahdadpur', 2, NULL, NULL),
(223, 'Kandiyaro\r\n', 2, NULL, NULL),
(224, 'Mehar', 2, NULL, NULL),
(225, 'Naudero', 2, NULL, NULL),
(226, 'Padidan', 2, NULL, NULL),
(227, 'Nasirabad', 2, NULL, NULL),
(230, 'Talhar', 2, NULL, NULL),
(231, 'Bulri Shah Karim', 2, NULL, NULL),
(232, 'Gorakh Hill', 2, NULL, NULL),
(233, 'Rohri', 2, NULL, NULL),
(234, 'Dhoronaro', 2, NULL, NULL),
(235, 'Bhiria Road', 2, NULL, NULL),
(236, 'Nara', 2, NULL, NULL),
(237, 'Samaro', 2, NULL, NULL),
(238, 'New Sukkur', 2, NULL, NULL),
(239, 'Karoonjhar', 2, NULL, NULL),
(240, 'Chowdagi\r\n', 2, NULL, NULL),
(241, 'Pir Jo Goth', 2, NULL, NULL),
(242, 'Dighri', 2, NULL, NULL),
(243, 'Mirwah Gorchani', 2, NULL, NULL),
(244, 'Kario Ghanwar', 2, NULL, NULL),
(245, 'Matli', 2, NULL, NULL),
(246, 'Digri', 2, NULL, NULL),
(247, 'Bakrani', 2, NULL, NULL),
(248, 'Dokri', 2, NULL, NULL),
(249, 'Garhi Khairo', 2, NULL, NULL),
(250, 'Hyderabad Cantt', 2, NULL, NULL),
(251, 'Karampur', 2, NULL, NULL),
(252, 'Kandiaro', 2, NULL, NULL),
(253, 'Madeji', 2, NULL, NULL),
(254, 'Manghopir', 2, NULL, NULL),
(255, 'Nooriabad', 2, NULL, NULL),
(256, 'Jhirk', 2, NULL, NULL),
(257, 'Jhirk', 2, NULL, NULL),
(258, 'Keti Bandar', 2, NULL, NULL),
(259, 'Bhit Shah', 2, NULL, NULL),
(260, 'Mirpur Sakro', 2, NULL, NULL),
(261, 'Jam Nawaz Ali', 2, NULL, NULL),
(262, 'Daulatpur', 2, NULL, NULL),
(263, 'Garhi Yasin', 2, NULL, NULL),
(264, 'Lakhi Ghulam Shah', 2, NULL, NULL),
(265, 'Lakhi Ghulam Shah', 2, NULL, NULL),
(266, 'Thari Mirwah', 2, NULL, NULL),
(267, 'Shahpur Chakar', 2, NULL, NULL),
(268, 'Piryaloi', 2, NULL, NULL),
(269, 'Piryaloi', 2, NULL, NULL),
(270, 'Amri', 2, NULL, NULL),
(271, 'Tando Jam', 2, NULL, NULL),
(272, 'Kario', 2, NULL, NULL),
(273, 'Jati', 2, NULL, NULL),
(274, 'Daharki', 2, NULL, NULL),
(275, 'Pithoro', 2, NULL, NULL),
(276, 'Islamkot', 2, NULL, NULL),
(277, 'Sakrand', 2, NULL, NULL),
(279, 'Sijawal Junejo', 2, NULL, NULL),
(280, 'Shah Bandar', 2, NULL, NULL),
(281, 'Mirpur Bathoro', 2, NULL, NULL),
(282, 'Kunri', 2, NULL, NULL),
(283, 'Kot Ghulam Muhammad', 2, NULL, NULL),
(284, 'Gajro', 2, NULL, NULL),
(285, 'Baghban', 2, NULL, NULL),
(286, 'Pingrio', 2, NULL, NULL),
(287, 'Bulhari', 2, NULL, NULL),
(288, 'Barhoon', 2, NULL, NULL),
(289, 'Lohiro', 2, NULL, NULL),
(290, 'Arore', 2, NULL, NULL),
(291, 'Jungshahi', 2, NULL, NULL),
(292, 'Gularchi', 2, NULL, NULL),
(293, 'Gularchi', 2, NULL, NULL),
(294, 'Goth Jan Muhammad', 2, NULL, NULL),
(295, 'Keamari', 2, NULL, NULL),
(296, 'Latifabad', 2, NULL, NULL),
(297, 'Kumb', 2, NULL, NULL),
(298, 'Warah', 2, NULL, NULL),
(299, 'Warah', 2, NULL, NULL),
(300, 'Achar Khosa', 2, NULL, NULL),
(301, 'Chandka', 2, NULL, NULL),
(302, 'Lakha Road', 2, NULL, NULL),
(303, 'Gharo', 2, NULL, NULL),
(304, 'Kaloi', 2, NULL, NULL),
(305, 'Jamsahib', 2, NULL, NULL),
(306, 'Allahabad', 2, NULL, NULL),
(307, 'Kazi Ahmed', 2, NULL, NULL),
(308, 'Darakshan', 2, NULL, NULL),
(309, 'Abdul Hakeem', 2, NULL, NULL),
(310, 'Chotiari Dam', 2, NULL, NULL),
(311, 'Pataro', 2, NULL, NULL),
(312, 'Qadirpur', 2, NULL, NULL),
(313, 'Lakhi', 2, NULL, NULL),
(314, 'Hingorja', 2, NULL, NULL),
(315, 'Bhiria City', 2, NULL, NULL),
(316, 'Kalhora', 2, NULL, NULL),
(317, 'Saeedabad', 2, NULL, NULL),
(318, 'Karondi', 2, NULL, NULL),
(319, 'Dano Dhandal', 2, NULL, NULL),
(320, 'Pir Ali Bux Jalbani', 2, NULL, NULL),
(321, 'Faridabad', 2, NULL, NULL),
(322, 'Khumb', 2, NULL, NULL),
(323, 'Thari', 2, NULL, NULL),
(324, 'Chamber', 2, NULL, NULL),
(325, 'Qasimabad', 2, NULL, NULL),
(326, 'Wagan', 2, NULL, NULL),
(327, 'Batkhela', 3, NULL, NULL),
(328, 'Chitral', 3, NULL, NULL),
(329, 'Dir', 3, NULL, NULL),
(330, 'Parachinar', 3, NULL, NULL),
(331, 'Hangu', 3, NULL, NULL),
(332, 'Shangla', 3, NULL, NULL),
(333, 'Topi', 3, NULL, NULL),
(334, 'Timergara', 3, NULL, NULL),
(335, 'Jamrud', 3, NULL, NULL),
(336, 'Malakand', 3, NULL, NULL),
(337, 'Alpuri', 3, NULL, NULL),
(338, 'Drosh', 3, NULL, NULL),
(339, 'Landi Kotal', 3, NULL, NULL),
(340, 'Gulabad', 3, NULL, NULL),
(341, 'Daggar', 3, NULL, NULL),
(342, 'Kabal', 3, NULL, NULL),
(343, 'Barikot', 3, NULL, NULL),
(344, 'Khwazakhela', 3, NULL, NULL),
(345, 'Charbagh', 3, NULL, NULL),
(346, 'Rustam', 3, NULL, NULL),
(347, 'Gadoon Amazai', 3, NULL, NULL),
(348, 'Thall', 3, NULL, NULL),
(349, 'Ziarat Kaka Sahib', 3, NULL, NULL),
(350, 'Matta', 3, NULL, NULL),
(351, 'Mastuj', 3, NULL, NULL),
(352, 'Shergarh', 3, NULL, NULL),
(353, 'Torghar', 3, NULL, NULL),
(354, 'Baffa', 3, NULL, NULL),
(355, 'Balakot', 3, NULL, NULL),
(356, 'Shewa Adda', 3, NULL, NULL),
(357, 'Khawazakhela', 3, NULL, NULL),
(358, 'Akora Khattak', 3, NULL, NULL),
(359, 'Dargai', 3, NULL, NULL),
(360, 'Shangla Top', 3, NULL, NULL),
(361, 'Tor Ghar', 3, NULL, NULL),
(362, 'Katlang', 3, NULL, NULL),
(363, 'Sherani', 3, NULL, NULL),
(364, 'Kalabagh', 3, NULL, NULL),
(365, 'Pind Kargu Khan', 3, NULL, NULL),
(366, 'Puran', 3, NULL, NULL),
(367, 'Khadezai', 3, NULL, NULL),
(368, 'Gul Bagh', 3, NULL, NULL),
(369, 'Thakot', 3, NULL, NULL),
(370, 'Kulachi', 3, NULL, NULL),
(371, 'Shaidu', 3, NULL, NULL),
(372, 'Darra Adam Khel', 3, NULL, NULL),
(373, 'Ranigat', 3, NULL, NULL),
(374, 'Serai Naurang', 3, NULL, NULL),
(375, 'Shabqadar Bazar', 3, NULL, NULL),
(376, 'Zarobai', 3, NULL, NULL),
(377, 'Khalabat Township', 3, NULL, NULL),
(378, 'Mandian', 3, NULL, NULL),
(379, 'Kheshgi', 3, NULL, NULL),
(380, 'Musa Zai Sharif', 3, NULL, NULL),
(381, 'Yusufabad', 3, NULL, NULL),
(382, 'Doaba', 3, NULL, NULL),
(383, 'Risalpur', 3, NULL, NULL),
(384, 'Shabqadar Fort', 3, NULL, NULL),
(385, 'Nathia Gali', 3, NULL, NULL),
(386, 'Ayubia', 3, NULL, NULL),
(387, 'Battagram', 3, NULL, NULL),
(388, 'Thandiani', 3, NULL, NULL),
(389, 'Oghi', 3, NULL, NULL),
(390, 'Bisham', 3, NULL, NULL),
(391, 'Lachi', 3, NULL, NULL),
(392, 'Kund Park', 3, NULL, NULL),
(393, 'Miran Shah', 3, NULL, NULL),
(394, 'Malikdin Khel', 3, NULL, NULL),
(395, 'Gomal', 3, NULL, NULL),
(396, 'Dara Zinda', 3, NULL, NULL),
(397, 'Serai Saleh', 3, NULL, NULL),
(398, 'Mianwali', 3, NULL, NULL),
(399, 'Krapa', 3, NULL, NULL),
(400, 'Nara Amazai', 3, NULL, NULL),
(401, 'Havelian', 3, NULL, NULL),
(402, 'Munda', 3, NULL, NULL),
(403, 'Landikotal', 3, NULL, NULL),
(404, 'Hindukush Village', 3, NULL, NULL),
(405, 'Dalbandin', 4, NULL, NULL),
(406, 'Hub', 4, NULL, NULL),
(407, 'Lasbela', 4, NULL, NULL),
(408, 'Kalat', 4, NULL, NULL),
(409, 'Nushki', 4, NULL, NULL),
(410, 'Pasni', 4, NULL, NULL),
(411, 'Qila Saifullah', 4, NULL, NULL),
(412, 'Qila Abdullah4', 4, NULL, NULL),
(413, 'Pishin', 4, NULL, NULL),
(414, 'Jiwani', 4, NULL, NULL),
(415, 'Surab', 4, NULL, NULL),
(416, 'Harnai', 4, NULL, NULL),
(417, 'Harnai', 4, NULL, NULL),
(418, 'Kohlu', 4, NULL, NULL),
(419, 'Dera Allah Yar', 4, NULL, NULL),
(420, 'Sohbatpur', 4, NULL, NULL),
(421, 'Mach', 4, NULL, NULL),
(422, 'Usta Muhammad', 4, NULL, NULL),
(423, 'Gaddani', 4, NULL, NULL),
(424, 'Ormara', 4, NULL, NULL),
(425, 'Duki', 4, NULL, NULL),
(426, 'Ziarat', 4, NULL, NULL),
(427, 'Besima', 4, NULL, NULL),
(428, 'Tump', 4, NULL, NULL),
(429, 'Mand', 4, NULL, NULL),
(430, 'Sinjawi', 4, NULL, NULL),
(431, 'Danyore', 7, NULL, NULL),
(432, 'Chalt', 7, NULL, NULL),
(433, 'Nagar', 7, NULL, NULL),
(434, 'Jutial', 7, NULL, NULL),
(435, 'Karimabad', 7, NULL, NULL),
(436, 'Shigar', 7, NULL, NULL),
(437, 'Aliabad', 7, NULL, NULL),
(438, 'Chilas', 7, NULL, NULL),
(439, 'Gakuch', 7, NULL, NULL),
(440, 'Baltistan', 7, NULL, NULL),
(441, 'Skardu', 7, NULL, NULL),
(442, 'Khaplu', 7, NULL, NULL),
(443, 'Chakar', 6, NULL, NULL),
(444, 'Dadyal', 6, NULL, NULL),
(445, 'Leepa', 6, NULL, NULL),
(446, 'Chakswari', 6, NULL, NULL),
(447, 'Rawalakot', 6, NULL, NULL),
(448, 'Dhirkot', 6, NULL, NULL),
(449, 'Halali', 6, NULL, NULL),
(450, 'Abbaspur', 6, NULL, NULL),
(451, 'Tatrinote', 6, NULL, NULL),
(452, 'Miramshah', 6, NULL, NULL),
(453, 'Mandi', 6, NULL, NULL),
(454, 'Sudhnoti District', 6, NULL, NULL),
(455, 'Toli Pir', 6, NULL, NULL),
(456, 'Banjosa', 6, NULL, NULL),
(457, 'Sharda', 6, NULL, NULL),
(458, 'Keran', 6, NULL, NULL),
(459, 'Kel', 6, NULL, NULL),
(460, 'Athmuqam', 6, NULL, NULL),
(461, 'Jhelum Valley District', 6, NULL, NULL),
(462, 'Dhani', 6, NULL, NULL),
(463, 'Chattar Khel', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coaches`
--

CREATE TABLE `coaches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coaches`
--

INSERT INTO `coaches` (`id`, `name`, `email`, `phone`, `password`, `image`, `bio`, `address`, `country_id`, `city_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'usama qadeer', 'usamaqadeer02@gmail.com', '654456789876', '$2y$12$hp0W6e/JGwOinW2cCFpxfe4d/RCMOJ9uhI3GiLeklo0E4ogQjiX/O', 'coaches/AOViCSVJiqSGJVbaeO5ZmNJn7tRMrwUVaEyqO24q.jpg', 'hkjhhkhjkhkjhjkh', 'noor moblie plaza 6 road rawalpindi', 166, 3, 'active', '2025-08-26 09:47:29', '2025-08-26 09:47:29'),
(2, 'anab', 'anabkhanm@gmail.com', '46456363', '$2y$12$VNfuTfmkcqjmsp9oFAjQferXOt2tuCxMUwTAF6J0QHBtmWYGJg67m', 'coaches/mObZbd0cfmwkuUlEagHCFlhe92SmYM2oWXhPSzYa.png', NULL, 'jdsfjskdhfjhk', 166, 1, 'active', '2025-08-26 15:21:53', '2025-08-26 15:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age_group` int(11) DEFAULT NULL,
  `genz` enum('motherfits','fatherfits') DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `time_allowed` int(11) DEFAULT NULL,
  `competition_image` varchar(255) DEFAULT NULL,
  `org_type` int(11) DEFAULT NULL,
  `org` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `has_entry_fee` tinyint(1) DEFAULT 0,
  `entry_fee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competitions`
--

INSERT INTO `competitions` (`id`, `user_id`, `name`, `age_group`, `genz`, `country`, `time_allowed`, `competition_image`, `org_type`, `org`, `status`, `created_at`, `updated_at`, `has_entry_fee`, `entry_fee`) VALUES
(19, NULL, 'MaxFit Challenge', 30, 'fatherfits', 'Pakistan', 20, 'uploads/competitions/1769682054_local gems.png', 9, 10, 'active', '2026-01-29 15:20:54', '2026-01-29 15:20:54', 1, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `competition_appeals`
--

CREATE TABLE `competition_appeals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `competition_video_id` bigint(20) UNSIGNED NOT NULL,
  `appeal_text` longtext NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competition_details`
--

CREATE TABLE `competition_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coach_id` bigint(20) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_details`
--

INSERT INTO `competition_details` (`id`, `competition_id`, `coach_id`, `city`, `start_date`, `end_date`, `start_time`, `end_time`, `image`, `description`, `created_at`, `updated_at`) VALUES
(22, 19, 2, 'Rawalpindi', '2026-01-29', '2026-01-29', '17:20:00', '17:40:00', NULL, 'Encourage teens to stay active, build strength, endurance, and healthy habits through a fun fitness competition.\r\nCompetition Categories / Exercises:\r\nParticipants will compete in a series of exercises to test strength, stamina, and agility:\r\nPush-Ups Challenge – Maximum push-ups in 1 minute.\r\nSquat Challenge – Maximum squats in 1 minute.\r\nPlank Challenge – Hold a plank for maximum time.\r\nBurpee Challenge – Maximum burpees in 1 minute.\r\nSprint / Agility Test – 50-meter sprint or shuttle runs.\r\nRules:\r\n\r\nOpen to 16-year-olds only.\r\nAll exercises must be performed safely with proper form.\r\nParticipants can compete individually.\r\nEach participant will have one attempt per exercise, recorded by a referee or video submission (if online).\r\nScores will be cumulative across all exercises.\r\nScoring:\r\n\r\nEach exercise is scored based on count or duration.\r\nHighest total score wins.\r\nPrizes:\r\n\r\n1st Place: Trophy + Fitness Kit + Certificate\r\n2nd Place: Medal + Fitness Accessories + Certificate\r\n3rd Place: Certificate + Healthy Snack Pack', '2026-01-29 15:20:54', '2026-01-29 15:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `competition_exercises`
--

CREATE TABLE `competition_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exercise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_exercises`
--

INSERT INTO `competition_exercises` (`id`, `competition_id`, `exercise_id`, `created_at`, `updated_at`) VALUES
(440, 19, 1, NULL, NULL),
(441, 19, 3, NULL, NULL),
(442, 19, 4, NULL, NULL),
(443, 19, 5, NULL, NULL),
(444, 19, 6, NULL, NULL),
(445, 19, 8, NULL, NULL),
(446, 19, 9, NULL, NULL),
(447, 19, 10, NULL, NULL),
(448, 19, 12, NULL, NULL),
(449, 19, 14, NULL, NULL),
(450, 19, 16, NULL, NULL),
(451, 19, 18, NULL, NULL),
(452, 19, 19, NULL, NULL),
(453, 19, 20, NULL, NULL),
(454, 19, 21, NULL, NULL),
(455, 19, 22, NULL, NULL),
(456, 19, 23, NULL, NULL),
(457, 19, 24, NULL, NULL),
(458, 19, 26, NULL, NULL),
(459, 19, 27, NULL, NULL),
(460, 19, 28, NULL, NULL),
(461, 19, 29, NULL, NULL),
(462, 19, 30, NULL, NULL),
(463, 19, 31, NULL, NULL),
(464, 19, 32, NULL, NULL),
(465, 19, 33, NULL, NULL),
(466, 19, 36, NULL, NULL),
(467, 19, 37, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `competition_results`
--

CREATE TABLE `competition_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exercise_id` bigint(20) UNSIGNED DEFAULT NULL,
  `score` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_results`
--

INSERT INTO `competition_results` (`id`, `competition_user_id`, `exercise_id`, `score`, `created_at`, `updated_at`) VALUES
(316, 42, 1, 75.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(317, 42, 3, 57.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(318, 42, 4, 69.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(319, 42, 5, 4.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(320, 42, 6, 24.00, '2026-01-29 15:54:39', '2026-02-03 11:52:40'),
(321, 42, 8, 88.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(322, 42, 9, 83.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(323, 42, 10, 89.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(324, 42, 12, 71.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(325, 42, 14, 8.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(326, 42, 16, 38.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(327, 42, 18, 86.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(328, 42, 19, 11.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(329, 42, 20, 19.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(330, 42, 21, 32.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(331, 42, 22, 38.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(332, 42, 23, 43.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(333, 42, 24, 13.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(334, 42, 26, 94.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(335, 42, 27, 40.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(336, 42, 28, 26.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(337, 42, 29, 10.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(338, 42, 30, 21.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(339, 42, 31, 46.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(340, 42, 32, 94.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(341, 42, 33, 88.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(342, 42, 36, 19.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(343, 42, 37, 34.00, '2026-01-29 15:54:39', '2026-01-29 15:54:39'),
(344, 43, 1, 80.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(345, 43, 3, 44.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(346, 43, 4, 95.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(347, 43, 5, 70.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(348, 43, 6, 64.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(349, 43, 8, 73.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(350, 43, 9, 17.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(351, 43, 10, 58.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(352, 43, 12, 72.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(353, 43, 14, 85.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(354, 43, 16, 91.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(355, 43, 18, 59.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(356, 43, 19, 1.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(357, 43, 20, 93.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(358, 43, 21, 12.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(359, 43, 22, 56.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(360, 43, 23, 1.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(361, 43, 24, 10.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(362, 43, 26, 25.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(363, 43, 27, 0.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(364, 43, 28, 96.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(365, 43, 29, 65.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(366, 43, 30, 8.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(367, 43, 31, 84.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(368, 43, 32, 17.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(369, 43, 33, 9.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(370, 43, 36, 93.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(371, 43, 37, 81.00, '2026-01-29 16:23:19', '2026-01-29 16:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `competition_result_videos`
--

CREATE TABLE `competition_result_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_result_id` bigint(20) UNSIGNED NOT NULL,
  `youtube_link` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_result_videos`
--

INSERT INTO `competition_result_videos` (`id`, `competition_result_id`, `youtube_link`, `created_at`, `updated_at`) VALUES
(349, 344, 'https://www.kimiwara.us', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(350, 345, 'https://www.jytyrunuraq.com', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(351, 346, 'https://www.tavihujiryjado.ca', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(352, 347, 'https://www.qetysofyk.net', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(353, 348, 'https://www.lefugedikoxobur.mobi', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(354, 349, 'https://www.lakeburafo.org.au', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(355, 350, 'https://www.vybekohyhavyp.co.uk', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(356, 351, 'https://www.sifep.com.au', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(357, 352, 'https://www.loluvarahemuzy.cc', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(358, 353, 'https://www.woqeqawawodulox.me', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(359, 354, 'https://www.dosyxeqesymiv.co.uk', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(360, 355, 'https://www.wosadyraky.me', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(361, 356, 'https://www.hylanimuman.cm', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(362, 357, 'https://www.huxoqotisojyhe.tv', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(363, 358, 'https://www.nygysymavany.cm', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(364, 359, 'https://www.kesafywewebiw.net', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(365, 360, 'https://www.lalesobupywefec.org.uk', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(366, 361, 'https://www.nifybilu.com', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(367, 362, 'https://www.vulecejaju.cm', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(368, 363, 'https://www.vizovicuk.com.au', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(369, 364, 'https://www.powo.org', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(370, 365, 'https://www.duhozolaq.org', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(371, 366, 'https://www.bolik.biz', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(372, 367, 'https://www.lezaqicug.biz', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(373, 368, 'https://www.pomajo.info', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(374, 369, 'https://www.siziqu.net', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(375, 370, 'https://www.xufyhimananywu.me', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(376, 371, 'https://www.hehy.me', '2026-01-29 16:23:19', '2026-01-29 16:23:19'),
(377, 316, 'https://www.boxulevini.ws', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(378, 317, 'https://www.pusydo.mobi', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(379, 318, 'https://www.rogexeqawolyr.net', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(380, 319, 'https://www.jydudap.info', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(381, 320, 'https://www.vyw.cm', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(382, 321, 'https://www.piduquzawazanar.tv', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(383, 322, 'https://www.siluxu.org', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(384, 323, 'https://www.xewocilexavahe.in', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(385, 324, 'https://www.ryfifu.org.au', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(386, 325, 'https://www.rirylaxe.mobi', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(387, 326, 'https://www.wiwaf.ws', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(388, 327, 'https://www.jawovucolemel.ws', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(389, 328, 'https://www.rydyr.me.uk', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(390, 329, 'https://www.qacanovomu.org', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(391, 330, 'https://www.zogalesowoji.com', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(392, 331, 'https://www.zugozisicyxexuq.ca', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(393, 332, 'https://www.mycyvuvynynina.biz', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(394, 333, 'https://www.xumycofe.mobi', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(395, 334, 'https://www.hugaq.me.uk', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(396, 335, 'https://www.tecosatufuge.com', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(397, 336, 'https://www.nicijenu.us', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(398, 337, 'https://www.kevexob.us', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(399, 338, 'https://www.fafuw.net', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(400, 339, 'https://www.meny.in', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(401, 340, 'https://www.dupokybo.us', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(402, 341, 'https://www.degusebujenuqo.net', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(403, 342, 'https://www.pypyfacyhus.me', '2026-02-03 11:52:40', '2026-02-03 11:52:40'),
(404, 343, 'https://www.cuvevemimij.tv', '2026-02-03 11:52:40', '2026-02-03 11:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `competition_users`
--

CREATE TABLE `competition_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('accepted','rejected','pending') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_users`
--

INSERT INTO `competition_users` (`id`, `competition_detail_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(42, 22, 156, 'accepted', '2026-01-29 15:53:05', '2026-01-29 15:53:05'),
(43, 22, 157, 'accepted', '2026-01-29 16:22:31', '2026-01-29 16:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `competition_user_totals`
--

CREATE TABLE `competition_user_totals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_score` decimal(8,2) DEFAULT NULL,
  `status` enum('passed','failed') DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_user_totals`
--

INSERT INTO `competition_user_totals` (`id`, `competition_user_id`, `total_score`, `status`, `rank`, `created_at`, `updated_at`) VALUES
(17, 42, 1320.00, NULL, 2, '2026-01-29 15:54:39', '2026-02-03 11:52:40'),
(18, 43, 1459.00, NULL, 1, '2026-01-29 16:23:19', '2026-01-30 12:26:31');

-- --------------------------------------------------------

--
-- Table structure for table `competition_videos`
--

CREATE TABLE `competition_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_id` bigint(20) UNSIGNED NOT NULL,
  `video_file` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competition_videos`
--

INSERT INTO `competition_videos` (`id`, `competition_id`, `video_file`, `created_at`, `updated_at`) VALUES
(44, 19, 'https://www.youtube.com/watch?v=WDIpL0pjun0', '2026-01-29 15:20:54', '2026-01-29 15:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phonecode` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `phonecode`, `created_at`, `updated_at`) VALUES
(1, 'AF', 'Afghanistan', 93, NULL, NULL),
(2, 'AL', 'Albania', 355, NULL, NULL),
(3, 'DZ', 'Algeria', 213, NULL, NULL),
(4, 'AS', 'American Samoa', 1684, NULL, NULL),
(5, 'AD', 'Andorra', 376, NULL, NULL),
(6, 'AO', 'Angola', 244, NULL, NULL),
(7, 'AI', 'Anguilla', 1264, NULL, NULL),
(8, 'AQ', 'Antarctica', 0, NULL, NULL),
(9, 'AG', 'Antigua And Barbuda', 1268, NULL, NULL),
(10, 'AR', 'Argentina', 54, NULL, NULL),
(11, 'AM', 'Armenia', 374, NULL, NULL),
(12, 'AW', 'Aruba', 297, NULL, NULL),
(13, 'AU', 'Australia', 61, NULL, NULL),
(14, 'AT', 'Austria', 43, NULL, NULL),
(15, 'AZ', 'Azerbaijan', 994, NULL, NULL),
(16, 'BS', 'Bahamas The', 1242, NULL, NULL),
(17, 'BH', 'Bahrain', 973, NULL, NULL),
(18, 'BD', 'Bangladesh', 880, NULL, NULL),
(19, 'BB', 'Barbados', 1246, NULL, NULL),
(20, 'BY', 'Belarus', 375, NULL, NULL),
(21, 'BE', 'Belgium', 32, NULL, NULL),
(22, 'BZ', 'Belize', 501, NULL, NULL),
(23, 'BJ', 'Benin', 229, NULL, NULL),
(24, 'BM', 'Bermuda', 1441, NULL, NULL),
(25, 'BT', 'Bhutan', 975, NULL, NULL),
(26, 'BO', 'Bolivia', 591, NULL, NULL),
(27, 'BA', 'Bosnia and Herzegovina', 387, NULL, NULL),
(28, 'BW', 'Botswana', 267, NULL, NULL),
(29, 'BV', 'Bouvet Island', 0, NULL, NULL),
(30, 'BR', 'Brazil', 55, NULL, NULL),
(31, 'IO', 'British Indian Ocean Territory', 246, NULL, NULL),
(32, 'BN', 'Brunei', 673, NULL, NULL),
(33, 'BG', 'Bulgaria', 359, NULL, NULL),
(34, 'BF', 'Burkina Faso', 226, NULL, NULL),
(35, 'BI', 'Burundi', 257, NULL, NULL),
(36, 'KH', 'Cambodia', 855, NULL, NULL),
(37, 'CM', 'Cameroon', 237, NULL, NULL),
(38, 'CA', 'Canada', 1, NULL, NULL),
(39, 'CV', 'Cape Verde', 238, NULL, NULL),
(40, 'KY', 'Cayman Islands', 1345, NULL, NULL),
(41, 'CF', 'Central African Republic', 236, NULL, NULL),
(42, 'TD', 'Chad', 235, NULL, NULL),
(43, 'CL', 'Chile', 56, NULL, NULL),
(44, 'CN', 'China', 86, NULL, NULL),
(45, 'CX', 'Christmas Island', 61, NULL, NULL),
(46, 'CC', 'Cocos (Keeling) Islands', 672, NULL, NULL),
(47, 'CO', 'Colombia', 57, NULL, NULL),
(48, 'KM', 'Comoros', 269, NULL, NULL),
(49, 'CG', 'Congo', 242, NULL, NULL),
(50, 'CD', 'Congo The Democratic Republic Of The', 242, NULL, NULL),
(51, 'CK', 'Cook Islands', 682, NULL, NULL),
(52, 'CR', 'Costa Rica', 506, NULL, NULL),
(53, 'CI', 'Cote D Ivoire (Ivory Coast)', 225, NULL, NULL),
(54, 'HR', 'Croatia (Hrvatska)', 385, NULL, NULL),
(55, 'CU', 'Cuba', 53, NULL, NULL),
(56, 'CY', 'Cyprus', 357, NULL, NULL),
(57, 'CZ', 'Czech Republic', 420, NULL, NULL),
(58, 'DK', 'Denmark', 45, NULL, NULL),
(59, 'DJ', 'Djibouti', 253, NULL, NULL),
(60, 'DM', 'Dominica', 1767, NULL, NULL),
(61, 'DO', 'Dominican Republic', 1809, NULL, NULL),
(62, 'TP', 'East Timor', 670, NULL, NULL),
(63, 'EC', 'Ecuador', 593, NULL, NULL),
(64, 'EG', 'Egypt', 20, NULL, NULL),
(65, 'SV', 'El Salvador', 503, NULL, NULL),
(66, 'GQ', 'Equatorial Guinea', 240, NULL, NULL),
(67, 'ER', 'Eritrea', 291, NULL, NULL),
(68, 'EE', 'Estonia', 372, NULL, NULL),
(69, 'ET', 'Ethiopia', 251, NULL, NULL),
(70, 'XA', 'External Territories of Australia', 61, NULL, NULL),
(71, 'FK', 'Falkland Islands', 500, NULL, NULL),
(72, 'FO', 'Faroe Islands', 298, NULL, NULL),
(73, 'FJ', 'Fiji Islands', 679, NULL, NULL),
(74, 'FI', 'Finland', 358, NULL, NULL),
(75, 'FR', 'France', 33, NULL, NULL),
(76, 'GF', 'French Guiana', 594, NULL, NULL),
(77, 'PF', 'French Polynesia', 689, NULL, NULL),
(78, 'TF', 'French Southern Territories', 0, NULL, NULL),
(79, 'GA', 'Gabon', 241, NULL, NULL),
(80, 'GM', 'Gambia The', 220, NULL, NULL),
(81, 'GE', 'Georgia', 995, NULL, NULL),
(82, 'DE', 'Germany', 49, NULL, NULL),
(83, 'GH', 'Ghana', 233, NULL, NULL),
(84, 'GI', 'Gibraltar', 350, NULL, NULL),
(85, 'GR', 'Greece', 30, NULL, NULL),
(86, 'GL', 'Greenland', 299, NULL, NULL),
(87, 'GD', 'Grenada', 1473, NULL, NULL),
(88, 'GP', 'Guadeloupe', 590, NULL, NULL),
(89, 'GU', 'Guam', 1671, NULL, NULL),
(90, 'GT', 'Guatemala', 502, NULL, NULL),
(91, 'XU', 'Guernsey and Alderney', 44, NULL, NULL),
(92, 'GN', 'Guinea', 224, NULL, NULL),
(93, 'GW', 'Guinea-Bissau', 245, NULL, NULL),
(94, 'GY', 'Guyana', 592, NULL, NULL),
(95, 'HT', 'Haiti', 509, NULL, NULL),
(96, 'HM', 'Heard and McDonald Islands', 0, NULL, NULL),
(97, 'HN', 'Honduras', 504, NULL, NULL),
(98, 'HK', 'Hong Kong S.A.R.', 852, NULL, NULL),
(99, 'HU', 'Hungary', 36, NULL, NULL),
(100, 'IS', 'Iceland', 354, NULL, NULL),
(101, 'IN', 'India', 91, NULL, NULL),
(102, 'ID', 'Indonesia', 62, NULL, NULL),
(103, 'IR', 'Iran', 98, NULL, NULL),
(104, 'IQ', 'Iraq', 964, NULL, NULL),
(105, 'IE', 'Ireland', 353, NULL, NULL),
(106, 'IL', 'Israel', 972, NULL, NULL),
(107, 'IT', 'Italy', 39, NULL, NULL),
(108, 'JM', 'Jamaica', 1876, NULL, NULL),
(109, 'JP', 'Japan', 81, NULL, NULL),
(110, 'XJ', 'Jersey', 44, NULL, NULL),
(111, 'JO', 'Jordan', 962, NULL, NULL),
(112, 'KZ', 'Kazakhstan', 7, NULL, NULL),
(113, 'KE', 'Kenya', 254, NULL, NULL),
(114, 'KI', 'Kiribati', 686, NULL, NULL),
(115, 'KP', 'Korea North', 850, NULL, NULL),
(116, 'KR', 'Korea South', 82, NULL, NULL),
(117, 'KW', 'Kuwait', 965, NULL, NULL),
(118, 'KG', 'Kyrgyzstan', 996, NULL, NULL),
(119, 'LA', 'Laos', 856, NULL, NULL),
(120, 'LV', 'Latvia', 371, NULL, NULL),
(121, 'LB', 'Lebanon', 961, NULL, NULL),
(122, 'LS', 'Lesotho', 266, NULL, NULL),
(123, 'LR', 'Liberia', 231, NULL, NULL),
(124, 'LY', 'Libya', 218, NULL, NULL),
(125, 'LI', 'Liechtenstein', 423, NULL, NULL),
(126, 'LT', 'Lithuania', 370, NULL, NULL),
(127, 'LU', 'Luxembourg', 352, NULL, NULL),
(128, 'MO', 'Macau S.A.R.', 853, NULL, NULL),
(129, 'MK', 'Macedonia', 389, NULL, NULL),
(130, 'MG', 'Madagascar', 261, NULL, NULL),
(131, 'MW', 'Malawi', 265, NULL, NULL),
(132, 'MY', 'Malaysia', 60, NULL, NULL),
(133, 'MV', 'Maldives', 960, NULL, NULL),
(134, 'ML', 'Mali', 223, NULL, NULL),
(135, 'MT', 'Malta', 356, NULL, NULL),
(136, 'XM', 'Man (Isle of)', 44, NULL, NULL),
(137, 'MH', 'Marshall Islands', 692, NULL, NULL),
(138, 'MQ', 'Martinique', 596, NULL, NULL),
(139, 'MR', 'Mauritania', 222, NULL, NULL),
(140, 'MU', 'Mauritius', 230, NULL, NULL),
(141, 'YT', 'Mayotte', 269, NULL, NULL),
(142, 'MX', 'Mexico', 52, NULL, NULL),
(143, 'FM', 'Micronesia', 691, NULL, NULL),
(144, 'MD', 'Moldova', 373, NULL, NULL),
(145, 'MC', 'Monaco', 377, NULL, NULL),
(146, 'MN', 'Mongolia', 976, NULL, NULL),
(147, 'MS', 'Montserrat', 1664, NULL, NULL),
(148, 'MA', 'Morocco', 212, NULL, NULL),
(149, 'MZ', 'Mozambique', 258, NULL, NULL),
(150, 'MM', 'Myanmar', 95, NULL, NULL),
(151, 'NA', 'Namibia', 264, NULL, NULL),
(152, 'NR', 'Nauru', 674, NULL, NULL),
(153, 'NP', 'Nepal', 977, NULL, NULL),
(154, 'AN', 'Netherlands Antilles', 599, NULL, NULL),
(155, 'NL', 'Netherlands The', 31, NULL, NULL),
(156, 'NC', 'New Caledonia', 687, NULL, NULL),
(157, 'NZ', 'New Zealand', 64, NULL, NULL),
(158, 'NI', 'Nicaragua', 505, NULL, NULL),
(159, 'NE', 'Niger', 227, NULL, NULL),
(160, 'NG', 'Nigeria', 234, NULL, NULL),
(161, 'NU', 'Niue', 683, NULL, NULL),
(162, 'NF', 'Norfolk Island', 672, NULL, NULL),
(163, 'MP', 'Northern Mariana Islands', 1670, NULL, NULL),
(164, 'NO', 'Norway', 47, NULL, NULL),
(165, 'OM', 'Oman', 968, NULL, NULL),
(166, 'PK', 'Pakistan', 92, NULL, NULL),
(167, 'PW', 'Palau', 680, NULL, NULL),
(168, 'PS', 'Palestinian Territory Occupied', 970, NULL, NULL),
(169, 'PA', 'Panama', 507, NULL, NULL),
(170, 'PG', 'Papua new Guinea', 675, NULL, NULL),
(171, 'PY', 'Paraguay', 595, NULL, NULL),
(172, 'PE', 'Peru', 51, NULL, NULL),
(173, 'PH', 'Philippines', 63, NULL, NULL),
(174, 'PN', 'Pitcairn Island', 0, NULL, NULL),
(175, 'PL', 'Poland', 48, NULL, NULL),
(176, 'PT', 'Portugal', 351, NULL, NULL),
(177, 'PR', 'Puerto Rico', 1787, NULL, NULL),
(178, 'QA', 'Qatar', 974, NULL, NULL),
(179, 'RE', 'Reunion', 262, NULL, NULL),
(180, 'RO', 'Romania', 40, NULL, NULL),
(181, 'RU', 'Russia', 70, NULL, NULL),
(182, 'RW', 'Rwanda', 250, NULL, NULL),
(183, 'SH', 'Saint Helena', 290, NULL, NULL),
(184, 'KN', 'Saint Kitts And Nevis', 1869, NULL, NULL),
(185, 'LC', 'Saint Lucia', 1758, NULL, NULL),
(186, 'PM', 'Saint Pierre and Miquelon', 508, NULL, NULL),
(187, 'VC', 'Saint Vincent And The Grenadines', 1784, NULL, NULL),
(188, 'WS', 'Samoa', 684, NULL, NULL),
(189, 'SM', 'San Marino', 378, NULL, NULL),
(190, 'ST', 'Sao Tome and Principe', 239, NULL, NULL),
(191, 'SA', 'Saudi Arabia', 966, NULL, NULL),
(192, 'SN', 'Senegal', 221, NULL, NULL),
(193, 'RS', 'Serbia', 381, NULL, NULL),
(194, 'SC', 'Seychelles', 248, NULL, NULL),
(195, 'SL', 'Sierra Leone', 232, NULL, NULL),
(196, 'SG', 'Singapore', 65, NULL, NULL),
(197, 'SK', 'Slovakia', 421, NULL, NULL),
(198, 'SI', 'Slovenia', 386, NULL, NULL),
(199, 'XG', 'Smaller Territories of the UK', 44, NULL, NULL),
(200, 'SB', 'Solomon Islands', 677, NULL, NULL),
(201, 'SO', 'Somalia', 252, NULL, NULL),
(202, 'ZA', 'South Africa', 27, NULL, NULL),
(203, 'GS', 'South Georgia', 0, NULL, NULL),
(204, 'SS', 'South Sudan', 211, NULL, NULL),
(205, 'ES', 'Spain', 34, NULL, NULL),
(206, 'LK', 'Sri Lanka', 94, NULL, NULL),
(207, 'SD', 'Sudan', 249, NULL, NULL),
(208, 'SR', 'Suriname', 597, NULL, NULL),
(209, 'SJ', 'Svalbard And Jan Mayen Islands', 47, NULL, NULL),
(210, 'SZ', 'Swaziland', 268, NULL, NULL),
(211, 'SE', 'Sweden', 46, NULL, NULL),
(212, 'CH', 'Switzerland', 41, NULL, NULL),
(213, 'SY', 'Syria', 963, NULL, NULL),
(214, 'TW', 'Taiwan', 886, NULL, NULL),
(215, 'TJ', 'Tajikistan', 992, NULL, NULL),
(216, 'TZ', 'Tanzania', 255, NULL, NULL),
(217, 'TH', 'Thailand', 66, NULL, NULL),
(218, 'TG', 'Togo', 228, NULL, NULL),
(219, 'TK', 'Tokelau', 690, NULL, NULL),
(220, 'TO', 'Tonga', 676, NULL, NULL),
(221, 'TT', 'Trinidad And Tobago', 1868, NULL, NULL),
(222, 'TN', 'Tunisia', 216, NULL, NULL),
(223, 'TR', 'Turkey', 90, NULL, NULL),
(224, 'TM', 'Turkmenistan', 7370, NULL, NULL),
(225, 'TC', 'Turks And Caicos Islands', 1649, NULL, NULL),
(226, 'TV', 'Tuvalu', 688, NULL, NULL),
(227, 'UG', 'Uganda', 256, NULL, NULL),
(228, 'UA', 'Ukraine', 380, NULL, NULL),
(229, 'AE', 'United Arab Emirates', 971, NULL, NULL),
(230, 'GB', 'United Kingdom', 44, NULL, NULL),
(231, 'US', 'United States', 1, NULL, NULL),
(232, 'UM', 'United States Minor Outlying Islands', 1, NULL, NULL),
(233, 'UY', 'Uruguay', 598, NULL, NULL),
(234, 'UZ', 'Uzbekistan', 998, NULL, NULL),
(235, 'VU', 'Vanuatu', 678, NULL, NULL),
(236, 'VA', 'Vatican City State (Holy See)', 39, NULL, NULL),
(237, 'VE', 'Venezuela', 58, NULL, NULL),
(238, 'VN', 'Vietnam', 84, NULL, NULL),
(239, 'VG', 'Virgin Islands (British)', 1284, NULL, NULL),
(240, 'VI', 'Virgin Islands (US)', 1340, NULL, NULL),
(241, 'WF', 'Wallis And Futuna Islands', 681, NULL, NULL),
(242, 'EH', 'Western Sahara', 212, NULL, NULL),
(243, 'YE', 'Yemen', 967, NULL, NULL),
(244, 'YU', 'Yugoslavia', 38, NULL, NULL),
(245, 'ZM', 'Zambia', 260, NULL, NULL),
(246, 'ZW', 'Zimbabwe', 263, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `daily_assessments`
--

CREATE TABLE `daily_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `count` varchar(255) DEFAULT NULL,
  `set_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `daily_assessments`
--

INSERT INTO `daily_assessments` (`id`, `user_id`, `count`, `set_id`, `exercise_id`, `created_at`, `updated_at`) VALUES
(362, 157, '10', 13, 8, '2026-01-29 16:37:27', '2026-01-29 16:37:27'),
(361, 157, '30', 13, 28, '2026-01-29 16:37:27', '2026-01-29 16:37:27'),
(360, 157, '20', 13, 3, '2026-01-29 16:37:27', '2026-01-29 16:37:27'),
(359, 157, '6', 13, 21, '2026-01-29 16:37:27', '2026-01-29 16:37:27'),
(358, 156, '3', 13, 8, '2026-01-29 15:58:11', '2026-01-29 15:58:11'),
(357, 156, '6', 13, 28, '2026-01-29 15:58:11', '2026-01-29 15:58:11'),
(356, 156, '6', 13, 3, '2026-01-29 15:58:11', '2026-01-29 15:58:11'),
(355, 156, '6', 13, 21, '2026-01-29 15:58:11', '2026-01-29 15:58:11'),
(354, 156, '10', 13, 8, '2026-01-29 15:13:31', '2026-01-29 15:13:31'),
(353, 156, '9', 13, 28, '2026-01-29 15:13:31', '2026-01-29 15:13:31'),
(352, 156, '9', 13, 3, '2026-01-29 15:13:31', '2026-01-29 15:13:31'),
(351, 156, '10', 13, 21, '2026-01-29 15:13:31', '2026-01-29 15:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `exercise_type` varchar(1000) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `exercise_category_id` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `genz` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `exercise_type`, `description`, `exercise_category_id`, `youtube_link`, `image`, `genz`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Calf Raises', 'Per Second', 'Calf raises are a strength training exercise that targets the muscles in the lower leg, specifically the calf muscles. The exercise involves lifting the heels off the ground, raising the calf muscles, and then lowering them back down.\r\n\r\n1.1.	Targeted muscles. \r\nCalf raises target the following muscles:\r\n1.1.1.	Gastrocnemius: The largest muscle in the calf, responsible for flexing the foot and ankle.\r\n1.1.2.	Soleus: A smaller muscle in the calf, responsible for flexing the foot and ankle.\r\n\r\n1.2.	Benefits of Calf Raises:\r\n1.2.1.	Stronger Calf Muscles: Calf raises help strengthen the muscles in the lower leg, improving overall leg strength.\r\n1.2.2.	Improved Ankle Mobility: Calf raises help increase ankle mobility and flexibility.\r\n1.2.3.	Reduced Injury Risk: Strengthening the calf muscles can help reduce the risk of ankle and lower leg injuries.\r\n1.2.4.	Improved Athletic Performance: Calf raises can help improve athletic performance in sports that involve running, jumping, and quick changes of direction.\r\n\r\n1.3.	Proper Way of Doing Bodyweight Calf Raises \r\n1.3.1.	Starting Position: Stand on the edge of a step or curb or flat surface with your heels hanging off the edge or flat on the ground.\r\n1.3.2.	Raise Up: Raise up onto your tiptoes, squeezing your calf muscles.\r\n1.3.3.	Lower Down: Lower down to the starting position, stretching your calf muscles.\r\n1.3.4.	Repeat: Repeat the movement as many times within the specified time frame.\r\n\r\n1.4.	Key Points to Focus On\r\n1.4.1.	Use Proper Stand on the edge of a step or curb or flat surface with your heels hanging off the edge or flat on the ground.\r\n1.4.2.	Squeeze Calf Muscles: Squeeze your calf muscles as you raise up onto your tiptoes.\r\n1.4.3.	Controlled Movement: Use a controlled movement when lowering down to the starting position.\r\n1.4.4.	Knees: keep the knees as straight as possible, a slight bend in the knee during the movement is acceptable.\r\n\r\n\r\n1.5.	Counting rules\r\n1.5.1.	One Repetition: One complete movement, including the raise and lower phases, counts as one repetition.\r\n1.5.2.	Time Limit: Count repetitions within the specified time frame.\r\n1.5.3.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the raise and lower phases.\r\n1.5.4.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.', '4', 'https://youtu.be/CtyIVeJH6lI?si=80wVXtiuYfYJ7Gu_', 'uploads/exercises/1756582395.png', 'both', 'active', '2025-08-26 09:54:03', '2026-01-09 12:31:19'),
(2, 'Push Ups on knees', 'Per Second', 'Push-ups on knees, also known as modified push-ups or knee push-ups, are a variation of the traditional push-up exercise. Instead of performing a push-up on your toes, you place your knees on the ground, reducing the amount of weight you need to lift.\r\n\r\n3.1.	Target muscles\r\nPush-ups on knees target the following muscles:\r\n3.1.1.	Chest muscles (Pectoralis major): Responsible for flexing the shoulder joint and extending the arm.\r\n3.1.2.	Anterior deltoids: Assist in flexing the shoulder joint and stabilizing the arm.\r\n3.1.3.	Triceps brachii: Extend the elbow joint and straighten the arm.\r\n3.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain a stable position and generate force.\r\n\r\n3.2.	Benefits of Push-ups on Knees\r\n3.2.1.	Modified exercise for beginners: Easier to perform than traditional push-ups, making it an excellent starting point for those new to strength training.\r\n3.2.2.	Injury rehabilitation: Can be used as a rehabilitation exercise for individuals with shoulder, chest, or tricep injuries.\r\n3.2.3.	Improved upper body strength: Targets multiple muscle groups, helping to build overall upper body strength.\r\n3.2.4.	Enhanced core stability: Engages core muscles, improving stability and overall core strength.\r\n\r\n3.3.	Proper Way of Doing Bodyweight Knee Push-Ups \r\n3.3.1.	Starting Position: Start in a modified plank position with your hands shoulder-width apart and your knees on the ground instead of your toes.\r\n3.3.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n3.3.3.	Lower Your Body: Lower your body until your chest nearly touches the ground, keeping your elbows close to your body.\r\n3.3.4.	Push Back Up: Push back up to the starting position, extending your arms fully.\r\n3.3.5.	Repeat: Count repetitions within the specified time frame.\r\n\r\n3.4.	Key Points to Focus On\r\n3.4.1.	Control the Movement: Avoid bouncing or jerking movements, and focus on slow, controlled movements.\r\n3.4.2.	Keep Body in a Straight Line: Keep your body in a straight line from head to heels, engaging your core muscles to maintain stability.\r\n3.4.3.	Lower Body to Proper Depth: Lower your body until your chest nearly touches the ground to ensure proper range of motion.\r\n\r\n3.5.	Counting rules\r\n3.5.1.	One Repetition: One complete movement, including the lowering and pushing phases, counts as one repetition.\r\n3.5.2.	Only Full Range of Motion Counts: Only count repetitions where the body is lowered to the proper depth and pushed back up to the starting position.\r\n3.5.3.	No Partial Reps: Do not count partial repetitions where the body is not fully lowered or pushed back up.\r\n3.5.4.	Time Limit: Count repetitions within the specified time frame.', '3', 'https://youtu.be/__71lgdtiB8?si=sAjk02oJodnURB4c', 'uploads/exercises/1756582665.png', 'motherfits', 'active', '2025-08-26 15:24:57', '2025-10-07 09:39:05'),
(3, 'Sit ups', 'Per Second', 'Sit-up exercises, also known as crunches or abdominal exercises, are a type of strength training exercise that targets the muscles in the abdomen. The exercise involves lifting the torso off the ground, curling up towards the knees, and then lowering back down.\r\n\r\n4.1.	Targeted muscles\r\nSit-up exercises target the following muscles:\r\n4.1.1.	Rectus Abdominis: The muscle responsible for flexing the spine and lifting the torso.\r\n4.1.2.	Obliques: The muscles responsible for rotating the torso and maintaining posture.\r\n4.1.3.	Transverse Abdominis: The muscle responsible for stabilizing the spine and maintaining core pressure.\r\n\r\n4.2.	Benefits of Sit-up Exercises\r\n4.2.1.	Improved Core Strength: Sit-up exercises help build strong abdominal muscles, which are essential for maintaining good posture, balance, and overall core strength.\r\n4.2.2.	Better Posture: Strengthening the abdominal muscles can help improve posture by pulling the pelvis and spine into alignment.\r\n4.2.3.	Reduced Back Pain: Weak abdominal muscles can contribute to back pain. Sit-up exercises can help strengthen the muscles and reduce the risk of back pain.\r\n4.2.4.	Improved Athletic Performance: Strong core muscles are essential for many sports and activities, such as golf, tennis, and gymnastics.\r\n\r\n4.3.	Proper Way of Doing Bodyweight Sit-Ups\r\n4.3.1.	Starting Position: Lie on your back with your knees bent and feet flat on the ground.\r\n4.3.2.	Hands Behind Head: Place your hands behind your head, but avoid pulling on your neck.\r\n4.3.3.	Lift Torso: Lift your torso up towards your knees, keeping your lower back pressed into the ground.\r\n4.3.4.	Curl Up: Curl up until your elbows touch your knees or your torso is lifted to a 30-40° angle or the elbow touches the knees while holding the ears.\r\n4.3.5.	Lower Back Down: Lower your torso back down to the starting position, keeping control throughout the movement.\r\n4.3.6.	Repeat: Repeat the movement within the given time frame.\r\n\r\n\r\n4.4.	Key Points to Focus On\r\n4.4.1.	Control the Movement: Avoid bouncing or jerking movements, and focus on slow, controlled movements.\r\n4.4.2.	Keep Lower Back Pressed into Ground: Keep your lower back pressed into the ground throughout the exercise to maintain proper form.\r\n4.4.3.	 Lift Torso to Proper Height: Lift your torso to the proper height, keeping your elbows close to your knees.\r\n\r\n4.5.	Counting rules\r\n4.5.1.	One Repetition: One complete movement, including the lifting and lowering phases, counts as one repetition.\r\n4.5.2.	Only Full Range of Motion Counts: Only count repetitions where the torso is lifted to the proper height and lowered back down to the starting position.\r\n4.5.3.	No Partial Reps: Do not count partial repetitions where the torso is not fully lifted or lowered.\r\n4.5.4.	Time Limit: Count repetitions till the given time limit.', '5', 'https://youtu.be/UMaZGY6CbC4?si=xZ2KnCM4p51jQ6CI', 'uploads/exercises/KhHPlClN8lFBWsrw8YCWF92rj7svt5YL9wFzJmsw.png', 'both', 'active', '2025-08-30 23:40:52', '2025-10-07 09:39:02'),
(4, 'Skaters', 'Per Second', 'Skaters exercises, also known as lateral bounds or side-to-side jumps, are a type of plyometric exercise that targets the muscles in the legs, glutes, and core. The exercise involves jumping side-to-side, mimicking the motion of a speed skater.\r\n\r\n5.1.	Targeted muscles\r\nSkaters exercises target the following muscles:\r\n5.1.1.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n5.1.2.	Gluteus Medius: A muscle in the buttocks that helps stabilize the hip joint and assist in abduction.\r\n5.1.3.	Adductor Magnus: A muscle in the thigh that helps stabilize the knee and hip joints.\r\n5.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain balance and stability throughout the exercise.\r\n\r\n5.2.	Benefits of Skaters \r\n5.2.1.	Improved Power and Explosiveness: Skaters exercises help develop power and explosiveness in the legs and glutes.\r\n5.2.2.	Enhanced Agility and Speed: The exercise improves agility and speed by challenging the muscles to rapidly change direction.\r\n5.2.3.	Increased Strength and Endurance: Skaters exercises strengthen the muscles in the legs, glutes, and core, improving overall strength and endurance.\r\n5.2.4.	Better Balance and Coordination: The exercise improves balance and coordination by challenging the muscles to maintain stability throughout the movement.\r\n\r\n5.3.	Proper Way of Doing Bodyweight Skaters\r\n5.3.1.	Starting Position: Stand with your feet together, with your knees slightly bent and your weight evenly distributed on both feet.\r\n5.3.2.	Jump to One Side: Jump to one side, landing on one foot while keeping the other foot lifted off the ground.\r\n5.3.3.	Bring Other Foot to Meet: Bring the other foot to meet the first foot while keeping the foot in the air, mimicking a skating motion.\r\n5.3.4.	Repeat on Other Side: Repeat the movement on the other side, jumping to the other side and bringing the first foot to meet the second foot.\r\n5.3.5.	Continue Alternating: Continue alternating sides for the specified number of repetitions.\r\n5.3.6.	Jump width: the jumps should be outside the shoulder width.\r\n5.4.	Key Points to Focus On\r\n5.4.1.	Explosive Jump: Focus on explosive jumping to quickly switch sides.\r\n5.4.2.	Soft Landing: Land softly on one foot to absorb the impact and maintain balance. \r\n5.4.3.	Quick Turnover: Focus on quick turnover to rapidly switch sides and maintain rhythm.\r\n\r\n5.5.	Counting rules\r\n5.5.1.	One Repetition: One complete movement, including the jump and landing on one foot, counts as one repetition.\r\n5.5.2.	Only Full Movement Counts: Only count repetitions where the movement is completed on both sides.\r\n5.5.3.	No Partial Reps: Do not count partial repetitions where the movement is not completed on both sides.\r\n5.5.4.	Time Limit: Count repetitions till the given time limit.', '6', 'https://youtu.be/9_jLW6VkU8A?si=euzMfhwhIhL4RA3K', 'uploads/exercises/sTEBH2krcIhraVBBThgww1W0W2IGPpVaHYvAF32p.png', 'both', 'active', '2025-08-30 23:46:58', '2025-10-07 09:39:09'),
(5, 'Bird dog', 'Per Second', 'Bird dog exercises, also known as quadruped exercises, are a type of core strengthening exercise that targets the muscles in the core, back, and glutes. The exercise involves starting on your hands and knees, then lifting your arms and legs off the ground, mimicking the posture of a bird dog.\r\n\r\n6.1.	Target muscles\r\nBird dog exercises target the following muscles:\r\n6.1.1.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n6.1.2.	Erector Spinae: A group of muscles in the back that help straighten and stabilize the spine.\r\n6.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n6.1.4.	Latissimus Dorsi: A muscle in the back that helps extend and adduct the shoulder joint.\r\n\r\n6.2.	Benefits of Bird Dog Exercises\r\n6.2.1.	Improved Core Strength: Bird dog exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n6.2.2.	Better Balance and Coordination: The exercise improves balance and coordination by challenging the muscles to maintain stability throughout the movement.\r\n6.2.3.	Reduced Back Pain: Strengthening the muscles in the back and core can help reduce the risk of back pain.\r\n6.2.4.	Improved Posture: Bird dog exercises can help improve posture by strengthening the muscles that support the spine.\r\n\r\n6.3.	Proper Way of Doing Bodyweight Bird Dog\r\n6.3.1.	Starting Position: Start on your hands and knees, with your hands shoulder-width apart and your knees directly under your hips.\r\n6.3.2.	Lift Right Arm and Left Leg: Lift your right arm and left leg off the ground, keeping them straight and almost in line with your body, the hand or the feet cannot touch the ground.\r\n6.3.3.	Hold Position: Hold the position for the specified time, engaging your core muscles to maintain stability and maintaining control.\r\n6.3.4.	Repeat on Other Side: Repeat the movement on the other side, lifting your left arm and right leg off the ground.\r\n\r\n6.4.	Key Points to Focus On\r\n6.4.1.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n6.4.2.	Keep Body in a Straight Line: Keep your body in a straight line from head to heels, avoiding any sagging or arching.\r\n6.4.3.	Lift Limbs to Proper Height: Lift your arms and legs to the proper height, keeping them straight and almost in line with your body.\r\n\r\n6.5.	Counting rules\r\n6.5.1.	Time-Based Test: Hold the same position for as long as possible, up to a maximum time limit (e.g., 60 seconds).\r\n6.5.2.	Record Time: Record the time held in the same position.\r\n6.5.3.	No Repetitions: This test is based on time held in the same position, rather than repetitions.', '7', 'https://youtu.be/QABW99qPiNM?si=7ywKvtt0kfjBdkD7', 'uploads/exercises/SUHp3Xhc6mhN5os4479J2tkfxmbiEOJNYrS1JM05.png', 'both', 'active', '2025-08-30 23:49:47', '2025-10-07 09:39:17'),
(6, 'Single-Leg Glute Bridge', 'Per Second', 'Single leg glute bridge exercises are a type of strength training exercise that targets the muscles in the glutes, hips, and lower back. The exercise involves lying on your back with one leg lifted and the other foot flat on the ground, then lifting your hips up towards the ceiling, squeezing your glutes at the top of the movement.\r\n\r\n24.1.	Targeted muscles\r\nSingle leg glute bridge exercises target the following muscles:\r\n24.1.1.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n24.1.2.	Gluteus Medius: The muscle in the buttocks that helps abduct and rotate the hip joint.\r\n24.1.3.	Hamstrings: The muscles in the back of the thigh that help bend the knee and extend the hip.\r\n24.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n24.2.	Benefits of Single Leg Glute Bridge \r\n24.2.1.	Improved Glute Strength: Single leg glute bridge exercises help build strong glute muscles, which are essential for everyday activities, sports, and overall athletic performance.\r\n24.2.2.	Better Hip Mobility: The exercise improves hip mobility and flexibility, reducing the risk of hip injuries and improving overall athletic performance.\r\n24.2.3.	Increased Core Strength: Single leg glute bridge exercises engage the core muscles, helping to improve overall core strength and stability.\r\n24.2.4.	Reduced Risk of Injury: Strengthening the muscles in the glutes, hips, and lower back can help reduce the risk of injury in sports and everyday activities.\r\n\r\n24.3.	Proper Way of Doing Single Leg Glute Bridge \r\n24.3.1.	Starting Position: Lie on your back with your arms at your sides, lift one leg towards the ceiling (stopping at a point where both the knees align), and keep the other leg bent at a 90-degree angle.\r\n24.3.2.	Lift Hips: Lift your hips off the ground, squeezing your glutes and pushing your heels towards the ceiling.\r\n24.3.3.	Hold Position: Hold the same position, maintaining control and engagement of the core muscles.\r\n\r\n\r\n24.4.	Key Points to Focus On\r\n24.4.1.	Engage Glutes: Focus on engaging your glutes to lift your hips.\r\n24.4.2.	Keep Core Stable: Keep your core muscles stable and engaged throughout the exercise.\r\n24.4.3.	Use Proper Form: Use proper form and technique to avoid straining your lower back.\r\n\r\n24.5.	Counting rules\r\n24.5.1.	Time-Based Test: Hold the same position for as long as possible, up to a maximum time limit (e.g., 60 seconds).\r\n24.5.2.	Record Time: Record the time held in the same position.\r\n24.5.3.	No Repetitions: This test is based on time held in the same position, rather than repetitions.', '5', 'https://youtu.be/AVAXhy6pl7o?si=d-fs1F6WpiPOQJiO', 'uploads/exercises/mR2zBjCW3wDvclymVKLLEp3OhsLIb3NuedJZ0oYR.png', 'fatherfits', 'active', '2025-08-31 00:00:02', '2025-10-07 09:39:11'),
(7, 'Double-Leg Glute Bridge', 'Per Count', 'Double leg glute bridge exercises are a type of strength training exercise that targets the muscles in the glutes, hips, and lower back. The exercise involves lying on your back with your knees bent and feet flat on the ground, then lifting your hips up towards the ceiling, squeezing your glutes at the top of the movement.\r\n\r\n7.1.	Targeted muscle\r\nDouble leg glute bridge exercises target the following muscles:\r\n7.1.1.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n7.1.2.	Gluteus Medius: A muscle in the buttocks that helps stabilize the hip joint and assist in abduction.\r\n7.1.3.	Hamstrings: A group of muscles in the back of the thigh that help bend the knee and extend the hip.\r\n7.1.4.	Erector Spinae: A group of muscles in the lower back that help straighten and stabilize the spine.\r\n\r\n7.2.	Benefits of Double Leg Glute Bridge \r\n7.2.1.	Improved Glute Strength: Double leg glute bridge exercises help build strong glutes, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n7.2.2.	Better Hip Mobility: The exercise improves hip mobility and flexibility, reducing the risk of hip injuries and improving overall athletic performance.\r\n7.2.3.	Reduced Lower Back Pain: Strengthening the muscles in the glutes and lower back can help reduce the risk of lower back pain.\r\n7.2.4.	Improved Core Stability: Double leg glute bridge exercises engage the core muscles, improving core stability and overall athletic performance.\r\n\r\n7.3.	Proper Way of Doing Bodyweight Double Leg Glute Bridge\r\n7.3.1.	Starting Position: Lie on your back with your knees bent and feet flat on the ground, shoulder-width apart.\r\n7.3.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n7.3.3.	Lift Hips: Lift your hips off the ground, squeezing your glutes and pushing your heels towards the ceiling.\r\n\r\n7.3.4.	Hold Position: Hold the position for the specified time, engaging your core muscles to maintain stability and maintaining control.\r\n\r\n7.4.	Key Points to Focus On\r\n7.4.1.	Squeeze Glutes: Focus on squeezing your glutes to lift your hips, rather than relying solely on your lower back.\r\n7.4.2.	Keep Knees in Line with Toes: Keep your knees in line with your toes, avoiding any inward or outward rotation.\r\n7.4.3.	Lift Hips to Proper Height: Lift your hips to the proper height, maintaining control and engagement of the glutes.\r\n\r\n7.5.	Counting Method in Fitness Test\r\n7.5.1.	Time-Based Test: Hold the same position for as long as possible, up to a maximum time limit (e.g., 60 seconds).\r\n7.5.2.	Record Time: Record the time held in the same position.\r\n7.5.3.	No Repetitions: This test is based on time held in the same position, rather than repetitions.', '5', 'https://youtu.be/S09a3ix9l9U?si=rxiKjtXnVA4-r2eA', 'uploads/exercises/YM8BREWejkKeOat6DXXkAw5fPKAyXrPctCTrdPTj.png', 'motherfits', 'active', '2025-08-31 00:02:34', '2025-10-07 09:48:15'),
(8, 'Tricep dips', 'Per Second', 'Tricep dips exercises are a type of strength training exercise that targets the muscles in the back of the upper arm, specifically the triceps. The exercise involves placing your hands on a surface, such as a chair or bench, and lowering your body by bending your elbows until your arms are bent at a 90-degree angle.\r\n\r\n8.1.	Targeted muscles\r\nTricep dips exercises target the following muscles:\r\n8.1.1.	Triceps Brachii: The muscle responsible for extending the elbow joint and straightening the arm.\r\n8.1.2.	Anconaeus: A small muscle in the back of the upper arm that assists in extending the elbow joint.\r\n8.1.3.	Anterior Deltoids: The muscles in the front of the shoulder that assist in stabilizing the arm during the exercise.\r\n\r\n8.2.	Benefits of Tricep Dips \r\n8.2.1.	Improved Tricep Strength: Tricep dips exercises help build strong triceps, which are essential for extending the elbow joint and straightening the arm.\r\n8.2.2.	Better Lockout Strength: The exercise improves lockout strength, which is the ability to fully extend the elbow joint.\r\n8.2.3.	Increased Overall Upper Body Strength: Tricep dips exercises engage multiple muscle groups, including the triceps, shoulders, and chest, helping to improve overall upper body strength.\r\n8.2.4.	Improved Athletic Performance: Strong triceps are essential for many sports and activities, such as tennis, golf, and rowing.\r\n\r\n8.3.	Proper Way of Doing Bodyweight Triceps (Triceps Dips using a Chair or Bench)\r\n8.3.1.	Starting Position: Sit on the edge of a chair or bench with your hands grasping the edge and your feet flat on the floor.\r\n8.3.2.	Lower Your Body: Lower your body by bending your elbows until your upper arms are parallel to the ground.\r\n8.3.3.	Straighten Your Arms: Straighten your arms to return to the starting position. \r\n8.3.4.	Repeat: Repeat the movement for the specified time frame.\r\n\r\n\r\n\r\n8.4.	Key Points to Focus On\r\n8.4.1.	Keep Your Body Straight: Keep your body straight and your core engaged throughout the exercise.\r\n8.4.2.	Lower Your Body Slowly: Lower your body slowly and control the movement as you straighten your arms.\r\n8.4.3.	Avoid Swinging: Avoid swinging or jerking movements, and focus on slow, controlled movements.\r\n\r\n8.5.	Counting rules\r\n8.5.1.	One Repetition: One complete movement, including the lowering and straightening phases, counts as one repetition.\r\n8.5.2.	Only Full Range of Motion Counts: Only count repetitions where the arms are fully lowered and straightened.\r\n8.5.3.	No Partial Reps: Do not count partial repetitions where the arms are not fully lowered or straightened.\r\n8.5.4.	Time Limit: Count repetitions till the given time frame.', '3', 'https://youtu.be/6kALZikXxLc?si=NYe6UCNYVI67JBI9', 'uploads/exercises/1756584348.png', 'both', 'active', '2025-08-31 00:04:48', '2025-10-07 09:39:28'),
(9, 'Squats', 'Per Second', 'Squats exercises are a type of compound strength training exercise that target multiple muscle groups in the legs, glutes, and core. The exercise involves standing with your feet shoulder-width apart, then bending your knees and hips to lower your body down until your thighs are parallel to the ground.\r\n\r\n9.1.	Targeted muscles\r\nSquats exercises target the following muscles:\r\n9.1.1.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n9.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee.\r\n9.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n9.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n9.2.	Benefits of Squats\r\n9.2.1.	Improved Leg Strength: Squats exercises help build strong legs, which are essential for everyday activities, sports, and overall athletic performance.\r\n9.2.2.	Better Balance and Coordination: The exercise improves balance and coordination by challenging the muscles to maintain stability throughout the movement.\r\n9.2.3.	Increased Bone Density: Squats exercises can help increase bone density in the legs, hips, and spine, reducing the risk of osteoporosis and fractures.\r\n9.2.4.	Improved Athletic Performance: Squats exercises engage multiple muscle groups, helping to improve overall athletic performance in sports that involve running, jumping, and quick changes of direction.\r\n\r\n9.3.	Proper Way of Doing Bodyweight Squats\r\n9.3.1.	Starting Position: Stand with your feet shoulder-width apart, toes facing forward or slightly outward.\r\n9.3.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n9.3.3.	Lower Your Body: Lower your body down into a squat, keeping your back straight and your knees behind your toes.\r\n9.3.4.	Depth: Lower down until your thighs are parallel to the ground or your butt is below your knees.\r\n9.3.5.	Push Back Up: Push back up to the starting position, extending your legs and hips.\r\n9.3.6.	Repeat: Repeat the movement for the specified time frame.\r\n9.4.	Key Points to Focus On\r\n9.4.1.	Keep Back Straight: Keep your back straight and your core engaged throughout the exercise.\r\n9.4.2.	Knees Behind Toes: Keep your knees behind your toes, avoiding any inward or outward rotation.\r\n9.4.3.	Proper Depth: Lower down to the proper depth, keeping your thighs parallel to the ground or your butt below your knees.\r\n\r\n9.5.	Counting rules\r\n9.5.1.	One Repetition: One complete movement, including the lowering and pushing phases, counts as one repetition.\r\n9.5.2.	Only Full Range of Motion Counts: Only count repetitions where the body is lowered to the proper depth and pushed back up to the starting position.\r\n9.5.3.	No Partial Reps: Do not count partial repetitions where the body is not fully lowered or pushed back up.\r\n9.5.4.	Time Limit: Count repetitions for specified time frame.', '4', 'https://youtu.be/AWz_7B1cch0?si=WoijV07eHW_xb5X1', 'uploads/exercises/I6wswHrkcRyZ2lZnh3A9XDDYcqZukkXat4XidZQQ.png', 'both', 'active', '2025-08-31 00:07:50', '2025-10-07 09:39:24'),
(10, 'Forearm plank', 'Per Second', 'Forearm plank exercises, also known as forearm planks or plank holds, are a type of isometric exercise that targets the muscles in the core, arms, and shoulders. The exercise involves holding a plank position with your forearms on the ground, engaging your core muscles to maintain stability and support your body.\r\n\r\n10.1.	Targeted muscles\r\nForearm plank exercises target the following muscles:\r\n10.1.1.	Core muscles (Abdominals and Obliques): Engage to maintain stability and support the body.\r\n10.1.2.	Shoulder muscles (Deltoids and Trapezius): Help stabilize the shoulders and maintain proper posture.\r\n10.1.3.	Arm muscles (Biceps and Triceps): Engage to support the body and maintain stability.\r\n10.1.4.	Back muscles (Erector Spinae and Latissimus Dorsi): Help maintain proper posture and stability.\r\n\r\n10.2.	Benefits of Forearm Plank \r\n10.2.1.	Improved Core Strength: Forearm plank exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n10.2.2.	Better Posture: The exercise improves posture by strengthening the muscles that support the spine and maintain proper alignment.\r\n10.2.3.	Increased Endurance: Forearm plank exercises can help improve endurance and reduce fatigue in the muscles.\r\n10.2.4.	Reduced Injury Risk: Strengthening the muscles in the core, arms, and shoulders can help reduce the risk of injury in sports and everyday activities.\r\n\r\n11.3.	Proper Way of Doing Bodyweight Forearm Plank\r\n11.3.1.	Starting Position: Start in a plank position with your forearms on the ground and your elbows directly under your shoulders.\r\n11.3.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n11.3.3.	Keep Body in a Straight Line: Keep your body in a straight line from head to heels (preferably), avoiding any sagging or arching.\r\n11.3.4.	Hold Position: Hold the plank position, maintaining control and engagement of the core muscles.\r\n\r\n11.4.	Key Points to Focus On\r\n11.4.1.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n11.4.2.	Keep Body in a Straight Line: Keep your body in a straight line from head to heels, avoiding any sagging or arching.\r\n11.4.3.	Avoid Letting Hips Sag: Avoid letting your hips sag or your back arch, maintaining a neutral spine position.\r\n\r\n11.5.	Counting Method in Fitness Test\r\n11.5.1.	Time-Based Test: Hold the plank position for as long as possible, up to a maximum time limit (e.g., 60 seconds).\r\n11.5.2.	Record Time: Record the time held in the plank position.\r\n11.5.3.	No Repetitions: This test is based on time held in the plank position, rather than repetitions.', '5', 'https://youtu.be/mH5Sfb_KTGg?si=FAIvudprj2eY6lGK', 'uploads/exercises/AQpRkh0q7ltnWWMndskmPEdLyTarqiujF8l04z9w.png', 'both', 'active', '2025-08-31 00:10:01', '2025-10-07 09:39:32'),
(11, 'Lunges', 'Per Second', 'Lunges exercises are a type of compound strength training exercise that targets multiple muscle groups in the legs, glutes, and core. The exercise involves stepping forward with one foot and lowering your body down until your back knee almost touches the ground, then pushing back up to the starting position.\r\n\r\n12.1.	Targeted muscles\r\nLunges exercises target the following muscles:\r\n12.1.1.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n12.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee.\r\n12.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n12.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n12.2.	Benefits of Lunges \r\n12.2.1.	Improved Leg Strength: Lunges exercises help build strong legs, which are essential for everyday activities, sports, and overall athletic performance.\r\n12.2.2.	Better Balance and Coordination: The exercise improves balance and coordination by challenging the muscles to maintain stability throughout the movement.\r\n12.2.3.	Increased Bone Density: Lunges exercises can help increase bone density in the legs, hips, and spine, reducing the risk of osteoporosis and fractures.\r\n12.2.4.	Improved Athletic Performance: Lunges exercises engage multiple muscle groups, helping to improve overall athletic performance in sports that involve running, jumping, and quick changes of direction.\r\n\r\n12.3.	Proper Way of Doing Bodyweight Lunges \r\n12.3.1.	Starting Position: Stand with your feet together, take a large step forward with one foot.\r\n12.3.2.	Lower Your Body: Lower your body down into a lunge, keeping your front knee behind your toes and your back knee almost touching the ground.\r\n12.3.3.	Keep Back Straight: Keep your back straight and your core engaged throughout the exercise.\r\n12.3.4.	Push Back Up: Push back up to the starting position, extending your front leg and hip.\r\n12.3.5.	Alternate Legs: Alternate legs with each repetition, performing a lunge with one leg and then switching to the other leg.\r\n12.3.6.	Repeat: Repeat the movement for the specified number of repetitions.\r\n\r\n12.4.	Key Points to Focus On\r\n12.4.1.	Keep Front Knee Behind Toes: Keep your front knee behind your toes, avoiding any inward or outward rotation.\r\n12.4.2.	Keep Back Straight: Keep your back straight and your core engaged throughout the exercise.\r\n12.4.3.	Lower Body to Proper Depth: Lower your body down to the proper depth, keeping your back knee almost touching the ground.\r\n\r\n12.5.	Counting rules\r\n12.5.1.	One Repetition: One complete movement, including the lowering and pushing phases on one leg, counts as one repetition.\r\n12.5.2.	Only Full Range of Motion Counts: Only count repetitions where the body is lowered to the proper depth and pushed back up to the starting position.\r\n12.5.3.	No Partial Reps: Do not count partial repetitions where the body is not fully lowered or pushed back up.\r\n12.5.4.	Time Limit: Count repetitions for specified time.', '4', 'https://youtu.be/whwwIax9RRc?si=g0jqIBta3xftjwbJ', 'uploads/exercises/VdpjQ1OdQzUZCyjRW5KWiyx2vFKOAybviVo08SWe.png', 'motherfits', 'active', '2025-08-31 00:12:35', '2025-10-07 09:39:46'),
(12, 'Bulgarian Split Squats', 'Per Second', 'Bulgarian split squats exercises are a type of strength training exercise that targets the muscles in the legs, glutes, and hips. The exercise involves performing a split squat, where one leg is elevated behind you on a bench or platform, while the other leg is in a lunge position.\r\n\r\n25.1.	Targeted muscles\r\nBulgarian split squats exercises target the following muscles:\r\n25.1.1.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n25.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee and extend the hip.\r\n25.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n25.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n25.2.	Benefits of Bulgarian Split Squats \r\n25.2.1.	Improved Leg Strength: Bulgarian split squats exercises help build strong leg muscles, which are essential for everyday activities, sports, and overall athletic performance.\r\n25.2.2.	Better Balance and Coordination: The exercise improves balance and coordination by challenging the muscles to maintain stability and control.\r\n25.2.3.	Increased Hip Mobility: Bulgarian split squats exercises improve hip mobility and flexibility, reducing the risk of hip injuries and improving overall athletic performance.\r\n25.2.4.	Reduced Risk of Injury: Strengthening the muscles in the legs, glutes, and hips can help reduce the risk of injury in sports and everyday activities.\r\n\r\n25.3.	Proper Way of Doing Bodyweight Bulgarian Split Squats \r\n25.3.1.	Starting Position: Stand with your back to a bench or chair, lift one foot behind you and place it on the bench.\r\n25.3.2.	Lower Body Down: Lower your body down into a lunge position, keeping your front knee behind your toes.\r\n25.3.3.	Push Back Up: Push back up to the starting position, using your front leg to lift your body.\r\n25.3.4.	Repeat: Repeat the movement with the same leg for specified time frame.\r\n\r\n25.4.	Key Points to Focus On\r\n25.4.1.	Keep Front Knee Behind Toes: Keep your front knee behind your toes throughout the exercise.\r\n25.4.2.	Keep Back Straight: Keep your back straight and core engaged throughout the exercise.\r\n25.4.3.	Lower Body Down Slowly: Lower your body down slowly and control the movement as you push back up.\r\n\r\n25.5.	Counting rules\r\n25.5.1.	One Repetition: One complete movement, including the lower and push phases, counts as one repetition.\r\n25.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the lower and push phases.\r\n25.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n25.5.4.	Time Limit: Count repetitions for specified time frame.', '4', 'https://youtu.be/QD4P9Di7L20?si=5TKImF2uZgyPye1j', 'uploads/exercises/XpujVbdwDXKKJJsQdxBBpNSFztEOz6SonnSsmhJK.png', 'fatherfits', 'active', '2025-08-31 00:16:32', '2025-10-07 09:39:43'),
(13, 'Assisted russian twist', 'Per Second', 'Russian twist exercises are a type of core strengthening exercise that targets the muscles in the torso, specifically the obliques. The exercise involves rotating your torso from side to side, twisting your spine, and targeting the muscles in your core.\r\n\r\n13.1.	Targeted muscles\r\nRussian twist exercises target the following muscles:\r\n13.1.1.	Obliques (External and Internal): The muscles on the sides of your abdomen that help rotate your torso and maintain posture.\r\n13.1.2.	Rectus Abdominis: The muscle in the front of your abdomen that helps flex your spine and maintain posture.\r\n13.1.3.	Erector Spinae: The muscles in your lower back that help straighten and stabilize your spine.\r\n\r\n13.2.	Benefits of Russian Twist \r\n13.2.1.	Improved Core Strength: Russian twist exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n13.2.2.	Better Posture: The exercise improves posture by strengthening the muscles that support the spine and maintain proper alignment.\r\n13.2.3.	Increased Rotational Power: Russian twist exercises can help improve rotational power, which is essential for sports and activities that involve twisting and turning.\r\n13.2.4.	Reduced Injury Risk: Strengthening the muscles in your core can help reduce the risk of injury in sports and everyday activities.\r\n\r\n13.3.	Proper Way of Doing Bodyweight Russian Twist\r\n13.3.1.	Starting Position: Sit on the floor with your knees bent and feet flat, leaning back slightly and lifting your feet off the ground for non-assisted or anchor your feet under a stable object or have a partner hold them down for assisted Russian twist.\r\n13.3.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n13.3.3.	Twist Your Torso: Twist your torso to left and right, touching your hands to the ground each time.\r\n13.3.4.	Keep Arms Straight: Keep your arms straight and your hands together throughout the exercise.\r\n13.3.5.	Repeat: Repeat the movement for the specified time.\r\n\r\n\r\n13.4.	Key Points to Focus On\r\n13.4.1.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n13.4.2.	Keep Back Straight: Keep your back straight and your torso upright throughout the exercise.\r\n13.4.3.	Twist to Proper Depth: Twist your torso to the proper depth, touching your hands to the ground each time.\r\n\r\n13.5.	Counting rules\r\n13.5.1.	One Repetition: One complete movement, including the twist to left and right, counts as one repetition.\r\n13.5.2.	Only Full Range of Motion Counts: Only count repetitions where the torso is twisted to the proper depth and hands are touched to the ground.\r\n13.5.3.	No Partial Reps: Do not count partial repetitions where the torso is not fully twisted or hands are not touched to the ground.\r\n13.5.4.	Time Limit: Count repetitions within specified time frame.\r\n\r\nNote: \r\nFor assisted Russian twist: Anchor your feet under a stable object or have a partner hold them down.\r\nFor non-assisted Russian twist: No assistance with the feet or otherwise.', '5', 'https://youtu.be/C7xANTiQ_0Q?si=Grv0nbmCeYgr5LrB', 'uploads/exercises/1H3W03uWoLzD8S10Dd3IRXBeo32Vx649i1BUUDae.png', 'motherfits', 'active', '2025-08-31 00:23:38', '2025-10-07 09:39:40'),
(14, 'Russian twist', 'Per Second', 'Russian twist exercises are a type of core strengthening exercise that targets the muscles in the torso, specifically the obliques. The exercise involves rotating your torso from side to side, twisting your spine, and targeting the muscles in your core.\r\n\r\n13.1.	Targeted muscles\r\nRussian twist exercises target the following muscles:\r\n13.1.1.	Obliques (External and Internal): The muscles on the sides of your abdomen that help rotate your torso and maintain posture.\r\n13.1.2.	Rectus Abdominis: The muscle in the front of your abdomen that helps flex your spine and maintain posture.\r\n13.1.3.	Erector Spinae: The muscles in your lower back that help straighten and stabilize your spine.\r\n\r\n13.2.	Benefits of Russian Twist \r\n13.2.1.	Improved Core Strength: Russian twist exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n13.2.2.	Better Posture: The exercise improves posture by strengthening the muscles that support the spine and maintain proper alignment.\r\n13.2.3.	Increased Rotational Power: Russian twist exercises can help improve rotational power, which is essential for sports and activities that involve twisting and turning.\r\n13.2.4.	Reduced Injury Risk: Strengthening the muscles in your core can help reduce the risk of injury in sports and everyday activities.\r\n\r\n13.3.	Proper Way of Doing Bodyweight Russian Twist\r\n13.3.1.	Starting Position: Sit on the floor with your knees bent and feet flat, leaning back slightly and lifting your feet off the ground for non-assisted or anchor your feet under a stable object or have a partner hold them down for assisted Russian twist.\r\n13.3.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n13.3.3.	Twist Your Torso: Twist your torso to left and right, touching your hands to the ground each time.\r\n13.3.4.	Keep Arms Straight: Keep your arms straight and your hands together throughout the exercise.\r\n13.3.5.	Repeat: Repeat the movement for the specified time.\r\n\r\n\r\n13.4.	Key Points to Focus On\r\n13.4.1.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the exercise.\r\n13.4.2.	Keep Back Straight: Keep your back straight and your torso upright throughout the exercise.\r\n13.4.3.	Twist to Proper Depth: Twist your torso to the proper depth, touching your hands to the ground each time.\r\n\r\n13.5.	Counting rules\r\n13.5.1.	One Repetition: One complete movement, including the twist to left and right, counts as one repetition.\r\n13.5.2.	Only Full Range of Motion Counts: Only count repetitions where the torso is twisted to the proper depth and hands are touched to the ground.\r\n13.5.3.	No Partial Reps: Do not count partial repetitions where the torso is not fully twisted or hands are not touched to the ground.\r\n13.5.4.	Time Limit: Count repetitions within specified time frame.\r\n\r\nNote: \r\nFor assisted Russian twist: Anchor your feet under a stable object or have a partner hold them down.\r\nFor non-assisted Russian twist: No assistance with the feet or otherwise.', '5', 'https://youtu.be/DJQGX2J4IVw?si=U00_6eQUzghw4NmZ', 'uploads/exercises/nm26eD2fiOt07cpwIZhUwQYw3G4UNsU1Hf8YUOZH.png', 'fatherfits', 'active', '2025-08-31 00:25:33', '2025-10-07 09:39:38'),
(15, 'Calf jumps', 'Per Second', 'Calf jumps exercises, also known as calf hops or calf explosions, are a type of plyometric exercise that targets the muscles in the lower legs, specifically the calf muscles. The exercise involves explosively jumping up onto your toes, then immediately returning to the starting position.\r\n\r\n14.1.	Targeted muscles\r\nCalf jumps exercises target the following muscles:\r\n14.1.1.	Gastrocnemius: The muscle in the back of the lower leg that helps flex the foot and ankle.\r\n14.1.2.	Soleus: The muscle in the back of the lower leg that helps flex the foot and ankle.\r\n14.1.3.	Achilles Tendon: The tendon that connects the calf muscles to the heel bone.\r\n\r\n14.2.	Benefits of Calf Jumps Exercises\r\n14.2.1.	Improved Calf Strength: Calf jumps exercises help build strong calf muscles, which are essential for athletic performance, balance, and overall lower leg strength.\r\n14.2.2.	Increased Power and Explosiveness: The exercise improves power and explosiveness in the calf muscles, which is essential for sports and activities that involve jumping, hopping, and quick changes of direction.\r\n14.2.3.	Better Balance and Coordination: Calf jumps exercises improve balance and coordination by challenging the muscles to maintain stability throughout the movement.\r\n14.2.4.	Reduced Injury Risk: Strengthening the calf muscles can help reduce the risk of injury in sports and everyday activities.\r\n\r\n14.3.	Proper Way of Doing Bodyweight Calf Jumps\r\n14.3.1.	Starting Position: Stand on the edge of a step or curb or a flat surface with your heels hanging off the edge or flat on the ground.\r\n14.3.2.	Engage Calf Muscles: Engage your calf muscles to raise up onto your tiptoes.\r\n14.3.3.	Jump Up: Jump up, raising your heels as high as possible.\r\n14.3.4.	Land Softly: Land softly on the balls of your feet, immediately repeating the movement.\r\n14.3.5.	Repeat: Repeat the movement for specified time frame. \r\n\r\n14.4.	Key Points to Focus On\r\n14.4.1.	Explosive Jump: Focus on explosive jumping to maximize calf muscle engagement.\r\n14.4.2.	Land Softly: Land softly on the balls of your feet to avoid injury and maintain control.\r\n14.4.3.	Quick Turnover: Focus on quick turnover to rapidly repeat the movement.\r\n14.5.	Counting rules\r\n14.5.1.	One Repetition: One complete movement, including the jump and landing, counts as one repetition.\r\n14.5.2.	Only Full Movement Counts: Only count repetitions where the movement is completed, including the jump and landing.\r\n14.5.3.	No Partial Reps: Do not count partial repetitions where the movement is not completed.\r\n14.5.4.	Time Limit: Count repetitions for specified time frame.', '4', 'https://youtu.be/4z9F0XuGCLI?si=XDfSDBlub38FnAEO', 'uploads/exercises/aXBhHKqrr8eiDyieYmrPMK2PuWCkKsv3uI6fKaNU.png', 'motherfits', 'active', '2025-08-31 00:28:50', '2025-10-07 09:39:35'),
(16, 'Skipping', 'Per Second', 'Skipping exercises, also known as jump rope exercises, are a form of aerobic exercise that involves jumping over a rope swung in a circular motion. Skipping exercises are a great way to improve cardiovascular fitness, burn calories, and enhance coordination and agility.\r\n\r\n34.1.	Targeted Muscles\r\n34.1.1.	Legs (quadriceps, hamstrings, glutes)\r\n34.1.2.	Core muscles (abdominals, obliques)\r\n34.1.3.	Arms (biceps, triceps)\r\n34.1.4.	Shoulders (deltoids)\r\n34.1.5.	Cardiovascular system (heart, lungs)\r\n\r\n34.2.	Benefits\r\n34.2.1.	Improves cardiovascular fitness and endurance\r\n34.2.2.	Burns calories and aids in weight loss\r\n34.2.3.	Enhances coordination, agility, and speed\r\n34.2.4.	Strengthens legs, core, and upper body muscles\r\n34.2.5.	Low-impact, easy on joints\r\n34.2.6.	Improves bone density\r\n34.2.7.	Reduces stress and improves mental well-being\r\n\r\n34.3.	Proper Way of Doing Skipping for Fitness Test\r\n34.3.1.	Stance: Stand with feet shoulder-width apart, toes pointing slightly outward.\r\n34.3.2.	Rope Handling: Hold the rope handles with hands at shoulder height, elbows slightly bent.\r\n34.3.3.	Rope Swing: Swing the rope in a circular motion, keeping it low to the ground.\r\n34.3.4.	Jumping: Jump over the rope with both feet, landing softly on the balls of your feet.\r\n34.3.5.	Rhythm: Maintain a steady rhythm, aiming for 100-150 jumps per minute.\r\n34.3.6.	Duration: Perform skipping for the specified time (e.g., 30-60 seconds).\r\n\r\n34.4.	Counting rules\r\n34.4.1.	One Repetition: One complete jump over the rope counts as one repetition.\r\n34.4.2.	Only Complete Jumps: Only count complete jumps where both feet clear the rope.\r\n34.4.3.	Score: The individual\'s score is the total number of complete jumps within the time limit.\r\n34.4.4.	Time Limit: Count repetitions within the specified time limit (e.g., 30-60 seconds).', '4', 'https://youtu.be/qNGJDb3pdc0?si=bifCSDqu9YPYEczP', 'uploads/exercises/ksIjz5vHnrWa7Ln2Sctp6yfaSHo7dhehZwqFwVVO.png', 'fatherfits', 'active', '2025-08-31 00:31:48', '2025-10-07 09:38:12');
INSERT INTO `exercises` (`id`, `name`, `exercise_type`, `description`, `exercise_category_id`, `youtube_link`, `image`, `genz`, `status`, `created_at`, `updated_at`) VALUES
(17, 'Arm Circles', 'Per Second', 'Arm circles exercises are a type of shoulder exercise that targets the muscles in the shoulders, arms, and upper back. The exercise involves holding your arms straight out to the sides and making small circles with your hands.\r\n\r\n15.1.	Targeted muscles\r\nArm circles exercises target the following muscles:\r\n15.1.1.	Deltoids: The muscles in the shoulders that help lift and rotate the arm.\r\n15.1.2.	Rotator Cuff Muscles (Supraspinatus, Infraspinatus, Teres Minor, and Subscapularis): The muscles in the shoulders that help rotate and stabilize the shoulder joint.\r\n15.1.3.	Trapezius: The muscle in the upper back that helps lift and rotate the scapula.\r\n\r\n15.2.	Benefits of Arm Circles Exercises\r\n15.2.1.	Improved Shoulder Mobility: Arm circles exercises help improve shoulder mobility and flexibility, reducing the risk of shoulder injuries and improving overall athletic performance.\r\n15.2.2.	Increased Shoulder Strength: The exercise helps build strong shoulder muscles, which are essential for everyday activities, sports, and overall upper body strength.\r\n15.2.3.	Better Posture: Arm circles exercises can help improve posture by strengthening the muscles that support the shoulders and upper back.\r\n15.2.4.	Reduced Shoulder Pain: The exercise can help reduce shoulder pain and discomfort by improving shoulder mobility and strengthening the muscles that support the shoulder joint.\r\n\r\n15.3.	Proper Way of Doing Bodyweight Arm Circles\r\n15.3.1.	Starting Position: Stand or sit with your arms extended to the sides at shoulder height.\r\n15.3.2.	Make Small Circles: Make small circles with your hands.\r\n15.3.3.	Keep Arms Straight: Keep your arms straight and your shoulders relaxed throughout the exercise.\r\n15.3.4.	Focus on Shoulder Rotation: Focus on rotating your shoulders to make the circles, rather than just moving your hands.\r\n15.3.5.	Repeat: Repeat the movement as many as possible.\r\n\r\n15.4.	Key Points to Focus On\r\n15.4.1.	Keep Arms Straight: Keep your arms straight and your shoulders relaxed throughout the exercise.\r\n15.4.2.	Focus on Shoulder Rotation: Focus on rotating your shoulders to make the circles, rather than just moving your hands.\r\n15.4.3.	Make Small Circles: Make small circles with your hands to target the shoulder muscles.\r\n\r\n15.5.	Counting rules\r\n15.5.1.	One Repetition: One complete circle with both arms counts as one repetition.\r\n15.5.2.	Only Full Circles Count: Only count repetitions where a full circle is completed with both arms.\r\n15.5.3.	No Partial Reps: Do not count partial repetitions where a full circle is not completed.\r\n15.5.4.	Repeat: Repeat the movement as many as possible.', '3', 'https://youtu.be/xBl8xP1z8WA?si=v1ec0Oa7OzBi8mYC', 'uploads/exercises/1756589276.png', 'motherfits', 'active', '2025-08-31 01:27:01', '2025-10-07 09:38:47'),
(18, 'Inverted push ups', 'Per Second', 'Inverted push-ups, also known as reverse push-ups or inverted press-ups, are a variation of traditional push-ups. This exercise targets the upper body, particularly the chest, shoulders, and triceps.\r\n\r\n32.1.	Targeted muscles\r\nInverted push ups exercises target the following muscles:\r\n32.1.1.	Pectoralis Major: The muscle in the chest that helps extend and adduct the shoulder joint.\r\n32.1.2.	Anterior Deltoids: The muscles in the front of the shoulder that help flex and abduct the shoulder joint.\r\n32.1.3.	Triceps Brachii: The muscle in the back of the upper arm that helps extend the elbow joint.\r\n32.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n32.2.	Benefits of Inverted Push Ups \r\n32.2.1.	Improved Upper Body Strength: Inverted push ups exercises help build strong upper body muscles, which are essential for everyday activities, sports, and overall athletic performance.\r\n32.2.2.	Improves overall chest development\r\n32.2.3.	Increased Muscle Endurance: The exercise improves muscle endurance in the upper body, allowing you to perform daily tasks and sports with more energy and efficiency.\r\n32.2.4.	Better Core Strength: Inverted push ups exercises engage the core muscles, helping to improve overall core strength and stability.\r\n32.2.5.	Reduced Risk of Injury: Strengthening the muscles in the upper body can help reduce the risk of injury in sports and everyday activities.\r\n\r\n32.3.	Proper Way of Doing Bodyweight Inverted Push-ups\r\n32.3.1.	Starting Position: Start in a plank position with your hands shoulder-width apart and your feet hip-width apart, with your body in a straight line from head to heels.\r\n32.3.2.	Invert Position: Walk your feet forward and lift your hips up and back, straightening your arms and keeping your body in a straight line.\r\n32.3.3.	Lower Body: Lower your body down towards the ground, keeping your elbows close to your body, until your upper arms are parallel to the ground.\r\n32.3.4.	Push Back Up: Push back up to the starting position, extending your arms fully.\r\n32.3.5.	Repeat: Repeat the movement for specified time frame.  \r\n32.4.	Key Points to Focus On\r\n32.4.1.	Keep Body Straight: Keep your body in a straight line from head to heels throughout the exercise.\r\n32.4.2.	Use Controlled Movement: Use a controlled movement when lowering and pushing back up.\r\n32.4.3.	Avoid Letting Hips Sag: Avoid letting your hips sag or your back arch.\r\n\r\n32.5.	Counting rules\r\n32.5.1.	One Repetition: One complete movement, including the lower and push back up phases, counts as one repetition.\r\n32.5.2.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n32.5.3.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the lower and push back up phases.\r\n32.5.4.	Time Limit: Count repetitions for specified time frame.', '3', 'https://youtube.com/shorts/vH3iVxRUJyM?si=qhIwH1uXz2ZItbMY', 'uploads/exercises/qPRwBBBHbAKuh8wu65LPa9mulW9tjdFzYg0BCE4I.png', 'fatherfits', 'active', '2025-08-31 01:31:01', '2025-10-07 09:38:15'),
(19, 'Toe raises', 'Per Second', 'Toe raises exercises, also known as toe curls or toe extensions, are a type of exercise that targets the muscles of the foot and ankle. The exercise involves lifting the toes up and down, typically while standing or sitting.\r\n\r\n16.1.	Targeted muscles\r\nThe primary muscle targeted by toe raise exercises is the:\r\n16.1.1.	Gastrocnemius muscle: This muscle is located in the back of the lower leg and is responsible for ankle flexion (pointing the foot downward).\r\n16.1.2.	Tibialis anterior muscle: This muscle is located in the front of the lower leg and is responsible for ankle dorsiflexion (lifting the foot upward).\r\n16.1.3.	Toe flexor muscles: These muscles, including the flexor digitorum longus and flexor hallucis longus, are responsible for curling the toes downward.\r\n\r\n16.2.	Benefits of Toe Raise \r\n16.2.1.	Improves ankle strength and stability: Toe raise exercises can help strengthen the muscles around the ankle, improving stability and reducing the risk of ankle injuries.\r\n16.2.2.	Enhances balance and coordination: The exercise requires balance and coordination, which can help improve overall balance and reduce the risk of falls.\r\n16.2.3.	Relieves plantar fasciitis symptoms: Toe raise exercises can help stretch and strengthen the muscles and tendons in the foot, relieving symptoms of plantar fasciitis.\r\n16.2.4.	Improves foot mechanics: The exercise can help improve foot mechanics, reducing the risk of foot and ankle injuries.\r\n16.2.5.	Enhances athletic performance: Stronger ankles and feet can improve athletic performance in sports that involve running, jumping, and quick changes of direction.\r\n\r\n16.3.	Proper Way of Doing Bodyweight Reverse Calf Raises\r\n16.3.1.	Starting Position: Stand on a flat surface with your back leaning against the wall. \r\n16.3.2.	Raise Toes: Raise your toes up towards your shins, keeping your heels on the ground.\r\n16.3.3.	Lower Toes: Lower your toes back down to the starting position.\r\n16.3.4.	Repeat: Repeat the movement for the specified timeframe.\r\n\r\n16.4.	Key Points to Focus On\r\n16.4.1.	Use Proper Foot Position: Stand on a flat surface with your back leaning against the wall.\r\n16.4.2.	Raise Toes Slowly: Raise your toes up slowly and control the movement as you lower them back down.\r\n16.4.3.	Keep Heels on Ground: Keep your heels on the ground throughout the exercise.\r\n\r\n16.5.	Counting rules\r\n16.5.1.	One Repetition: One complete movement, including the raise and lower phases, counts as one repetition.\r\n16.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the raise and lower phases.\r\n16.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n16.5.4.	Time Limit: Count repetitions for a specified time frame.', '4', 'https://youtube.com/shorts/TcCt9BFP_5Q?si=vheQFOvRSDjoOzWq', 'uploads/exercises/YnT31KjJIbXAxmAv9QrvuSqgo5x85OaR9Kj5TAvx.png', 'both', 'active', '2025-08-31 01:33:28', '2025-10-07 09:38:21'),
(20, 'Leg raises', 'Per Second', 'Leg raises exercises are a type of strength training exercise that targets the muscles in the core, hips, and lower back. The exercise involves lying on your back and lifting your legs straight up towards the ceiling, then lowering them back down to the starting position.\r\n\r\n17.1.	Targeted muscles\r\nLeg raises exercises target the following muscles:\r\n17.1.1.	Rectus Abdominis: The muscle in the front of the abdomen that helps flex the spine and lift the legs.\r\n17.1.2.	Obliques (External and Internal): The muscles on the sides of the abdomen that help rotate the torso and lift the legs.\r\n17.1.3.	Hip Flexors (Iliacus and Psoas Major): The muscles in the front of the hip that help flex the hip joint and lift the legs.\r\n17.1.4.	Lower Back Muscles (Erector Spinae and Latissimus Dorsi): The muscles in the lower back that help stabilize the spine and lift the legs.\r\n\r\n17.2.	Benefits of Leg Raises Exercises\r\n17.2.1.	Improved Core Strength: Leg raises exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n17.2.2.	Better Hip Mobility: The exercise improves hip mobility and flexibility, reducing the risk of hip injuries and improving overall athletic performance.\r\n17.2.3.	Increased Lower Back Strength: Leg raises exercises help build strong lower back muscles, which are essential for maintaining good posture and reducing the risk of lower back injuries.\r\n17.2.4.	Improved Athletic Performance: The exercise can help improve athletic performance in sports that involve running, jumping, and quick changes of direction.\r\n\r\n17.3.	Proper Way of Doing Bodyweight Leg Raises\r\n17.3.1.	Starting Position: Lie on your back with your arms extended overhead and legs straight.\r\n17.3.2.	Lift Legs: Lift your legs off the ground, keeping them straight (as possible), and raise them up towards the ceiling.\r\n17.3.3.	Lower Legs: Lower your legs back down to the starting position without touching the floor, ideally.\r\n17.3.4.	Repeat: Repeat the movement for specified time frame.\r\n\r\n\r\n17.4.	Key Points to Focus On\r\n17.4.1.	Keep Legs Straight: Keep your legs straight throughout the exercise.\r\n17.4.2.	Lift Legs Slowly: Lift your legs slowly and control the movement as you lower them back down.\r\n17.4.3.	Avoid Swinging: Avoid swinging or jerking movements, and focus on slow, controlled movements.\r\n\r\n17.5.	Counting rules\r\n17.5.1.	One Repetition: One complete movement, including the lift and lower phases, counts as one repetition.\r\n17.5.2.	Only Full Range of Motion Counts: Only count repetitions where the legs are lifted up towards the ceiling and lowered back down to the starting position.\r\n17.5.3.	No Partial Reps: Do not count partial repetitions where the legs are not fully lifted or lowered.\r\n17.5.4.	Time Limit: Count repetitions for specified time frame.', '5', 'https://youtube.com/shorts/TcCt9BFP_5Q?si=vheQFOvRSDjoOzWq', 'uploads/exercises/IPvgsWRsIixkLkL82lilVycpGKTxGo3D5FduqlHN.png', 'both', 'active', '2025-08-31 01:36:02', '2025-10-07 09:38:18'),
(21, 'Jumping jacks', 'Per Second', 'Jumping jacks exercises are a type of plyometric exercise that targets the muscles in the legs, glutes, and core. The exercise involves jumping up and spreading your legs apart while raising your arms above your head, then quickly returning to the starting position.\r\n\r\n18.1.	Targeted muscles\r\nJumping jacks exercises target the following muscles:\r\n18.1.1.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n18.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee.\r\n18.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n18.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n18.2.	Benefits of Jumping Jacks \r\n18.2.1.	Improved Cardiovascular Endurance: Jumping jacks exercises provide an excellent cardiovascular workout, improving heart health and increasing endurance.\r\n18.2.2.	Increased Caloric Burn: The exercise is a great way to burn calories and aid in weight loss.\r\n18.2.3.	Improved Coordination and Agility: Jumping jacks exercises improve coordination and agility by challenging the muscles to quickly change direction.\r\n18.2.4.	Improved Bone Density: The exercise can help improve bone density in the legs, hips, and spine, reducing the risk of osteoporosis and fractures.\r\n\r\n18.3.	Proper Way of Doing Bodyweight Jumping Jacks\r\n18.3.1.	Starting Position: Stand with your feet together and your hands by your sides.\r\n18.3.2.	Jump Feet Apart: Jump your feet apart, wider than shoulder-width, while raising your arms above your head.\r\n18.3.3.	Quickly Return: Quickly return your feet to the starting position, while lowering your arms back down to your sides.\r\n18.3.4.	Repeat: Repeat the movement for specified time frame.\r\n\r\n18.4.	Key Points to Focus On\r\n18.4.1.	Explosive Jump: Focus on an explosive jump to quickly move your feet apart and raise your arms.\r\n18.4.2.	Quick Turnover: Focus on quick turnover to rapidly return to the starting position.\r\n18.4.3.	Keep Arms Straight: Keep your arms straight and your hands in a relaxed position throughout the exercise.\r\n\r\n18.5.	Counting rules\r\n18.5.1.	One Repetition: One complete movement, including the jump and return phases, counts as one repetition.\r\n18.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the jump and return phases.\r\n18.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n18.5.4.	Time Limit: Count repetitions for specified time frame.', '6', 'https://youtube.com/shorts/bT2iY8IjEU0?si=vT7SSoXt4rhP8i8z', 'uploads/exercises/IYWaK1WfJAN8XunKZfelh0oIDJyNH1SbWRb5itT5.png', 'both', 'active', '2025-08-31 01:42:02', '2025-10-07 09:38:24'),
(22, 'Superman', 'Per Second', 'Superman exercises are a type of strength training exercise that targets the muscles in the back, glutes, and core. The exercise involves lying on your stomach with your arms extended in front of you, then lifting your arms, shoulders, and legs off the ground, holding for a brief moment, and repeating.\r\n\r\n19.1.	Targeted muscles\r\nSuperman exercises target the following muscles:\r\n19.1.1.	Erector Spinae: The muscles in the lower back that help straighten and stabilize the spine.\r\n19.1.2.	Latissimus Dorsi: The muscles in the middle and upper back that help extend and adduct the shoulder joint.\r\n19.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n19.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n19.2.	Benefits of Superman \r\n19.2.1.	Improved Back Strength: Superman exercises help build strong back muscles, which are essential for maintaining good posture, reducing the risk of back injuries, and improving overall athletic performance.\r\n19.2.2.	Better Posture: The exercise improves posture by strengthening the muscles that support the spine and maintain proper alignment.\r\n19.2.3.	Increased Core Strength: Superman exercises engage the core muscles, helping to improve overall core strength and stability.\r\n19.2.4.	Reduced Lower Back Pain: Strengthening the muscles in the lower back can help reduce the risk of lower back pain and injuries.\r\n\r\n19.3.	Proper Way of Doing Bodyweight Superman \r\n19.3.1.	Starting Position: Lie on your stomach with your arms extended in front of you and legs extended behind you.\r\n19.3.2.	Lift Arms and Legs: Lift your arms and legs off the ground, keeping them straight, and hold.\r\n19.3.3.	Lower Arms and Legs: Lower your arms and legs back down to the starting position.\r\n\r\n\r\n\r\n19.4.	Key Points to Focus On\r\n19.4.1.	Keep Core Engaged: Engage your core muscles to maintain stability and control throughout the exercise.\r\n19.4.2.	Lift Arms and Legs Simultaneously: Lift your arms and legs simultaneously to target the upper and lower back muscles.\r\n\r\n19.5.	Counting rules\r\n19.5.1.	Time-Based Test: Hold the same position for as long as possible, up to a maximum time limit (e.g., 60 seconds).\r\n19.5.2.	Record Time: Record the time held in the same position.\r\n19.5.3.	No Repetitions: This test is based on time held in the same position, rather than repetitions.', '7', 'https://youtu.be/ZNVWTVdJW5s?si=UI20iQAUvg-F156V', 'uploads/exercises/VzoVa5t0UOyKjn3VYDqAeMjpHC66wHWyXXW980A9.png', 'both', 'active', '2025-08-31 01:45:09', '2025-10-07 09:38:50'),
(23, 'Mountain climbers', 'Per Second', 'Mountain climbers exercises are a type of plyometric exercise that targets the muscles in the core, legs, and glutes. The exercise involves starting in a plank position, then bringing one knee up towards your chest and quickly switching to the other knee, mimicking the motion of running.\r\n\r\n20.1.	Targeted muscles\r\nMountain climbers exercises target the following muscles:\r\n21.1.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n21.2.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n21.3.	Hamstrings: The muscles in the back of the thigh that help bend the knee.\r\n21.4.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n\r\n20.2.	Benefits of Mountain Climbers \r\n20.2.1.	Improved Cardiovascular Endurance: Mountain climbers exercises provide an excellent cardiovascular workout, improving heart health and increasing endurance.\r\n20.2.2.	Increased Caloric Burn: The exercise is a great way to burn calories and aid in weight loss.\r\n20.2.3.	Improved Coordination and Agility: Mountain climbers exercises improve coordination and agility by challenging the muscles to quickly change direction.\r\n20.2.4.	Improved Core Strength: The exercise engages the core muscles, helping to improve overall core strength and stability.\r\n\r\n20.3.	Proper Way of Doing Bodyweight Mountain Climbers\r\n20.3.1.	Starting Position: Start in a plank position with your hands shoulder-width apart and your feet hip-width apart.\r\n20.3.2.	Bring One Knee Up: Bring one knee up towards your chest, keeping your core engaged and your body in a straight line.\r\n20.3.3.	Quickly Switch Knees: Quickly switch knees, bringing the other knee up towards your chest.\r\n20.3.4.	Continue Alternating: Continue alternating knees as quickly as possible, mimicking the motion of running.\r\n20.3.5.	Repeat: Repeat the movement for specified time frame.\r\n\r\n\r\n20.4.	Key Points to Focus On\r\n20.4.1.	Keep Core Engaged: Engage your core muscles to maintain stability and control throughout the exercise.\r\n20.4.2.	Keep Body Straight: Keep your body in a straight line from head to heels throughout the exercise.\r\n20.4.3.	Quickly Alternate Knees: Quickly alternate knees to target the cardiovascular system and improve endurance.\r\n\r\n20.5.	Counting rules\r\n20.5.1.	One Repetition: One complete movement, including the lift and switch of knees, counts as one repetition.\r\n20.5.2.	Time Limit: Count repetitions for specified time frame.\r\n20.5.3.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the lift and switch of knees.\r\n20.5.4.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.', '5', 'https://youtu.be/kLh-uczlPLg?si=dI-k8_qdHNDdRl_W', 'uploads/exercises/5oseSSchGcRcmKW8eVsPCoaXhgx5Q99hqTqmdMAx.png', 'both', 'active', '2025-08-31 01:47:39', '2025-10-07 09:38:55'),
(24, 'Reverse flys', 'Per Second', 'Bodyweight reverse flys while lying on the back are an exercise that targets the muscles of the upper back and shoulders. The exercise involves lifting the arms off the ground while lying on the back, squeezing the shoulder blades together, and then lowering the arms back down.\r\n\r\n21.1.	Target muscles\r\nThe primary muscles targeted by bodyweight reverse flys while lying on the back are:\r\n21.1.1.	Trapezius muscle: This muscle is responsible for scapular elevation and upward rotation.\r\n21.1.2.	Rhomboid muscles: These muscles are responsible for scapular retraction and rotation.\r\n21.1.3.	Rear deltoid muscles: These muscles are responsible for shoulder extension and external rotation.\r\n\r\n21.2.	Benefits of Bodyweight Reverse Flys while Lying on the Back\r\n21.2.1.	Improves posture: Strengthening the muscles of the upper back and shoulders can help improve posture and reduce the risk of back and shoulder injuries.\r\n21.2.2.	Enhances shoulder stability: The exercise can help improve shoulder stability and reduce the risk of shoulder injuries.\r\n21.2.3.	Targets hard-to-reach muscles: The exercise targets the muscles of the upper back and shoulders, which can be difficult to target with other exercises.\r\n21.2.4.	Low-impact exercise: The exercise is low-impact and can be modified to suit different fitness levels.\r\n21.2.5.	Improves overall upper body strength: The exercise can help improve overall upper body strength and endurance.\r\n\r\n21.3.	Proper Way of Doing Bodyweight Reverse Flys while Lying on the Back \r\n21.3.1.	Starting Position: Lie on your back with your arms extended to the sides at shoulder height.\r\n21.3.2.	Lift Arms: Lift your arms off the ground, keeping them straight, and squeeze your shoulder blades together.\r\n21.3.3.	Hold for a Moment: Hold the lifted position for a moment.\r\n21.3.4.	Lower Arms: Lower your arms back down to the starting position.\r\n21.3.5.	Repeat: Repeat the movement for specified time frame.\r\n\r\n21.4.	Key Points to Focus On\r\n21.4.1.	Keep Arms Straight: Keep your arms straight throughout the exercise.\r\n21.4.2.	Squeeze Shoulder Blades: Squeeze your shoulder blades together as you lift your arms.\r\n21.4.3.	Use Controlled Movement: Use a controlled movement when lifting and lowering your arms.\r\n\r\n21.5.	Counting rules\r\n21.5.1.	One Repetition: One complete movement, including the lift and lower phases, counts as one repetition.\r\n21.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the lift and lower phases.\r\n21.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n21.5.4.	Time Limit: Count repetitions for specified time frame.', '7', 'https://youtu.be/zlfisQZSxU0?si=GE5zGvqu68w8RntW', 'uploads/exercises/jXsKxpGqA3mYuwWjVkNSp86EDrkmQdPUZsROMlK4.png', 'both', 'active', '2025-08-31 01:51:09', '2025-10-07 09:38:27'),
(25, 'Good Mornings', 'Per Second', 'Good morning exercises are a type of strength training exercise that targets the muscles in the back, glutes, and hamstrings. The exercise involves bending at the hips and knees, then standing up straight, mimicking the motion of getting out of bed in the morning.\r\n\r\n2.1.	Targeted muscles\r\nGood morning exercises target the following muscles:\r\n2.1.1.	Erector Spinae: A group of muscles in the back that help straighten and stabilize the spine.\r\n2.1.2.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n2.1.3.	Hamstrings: A group of muscles in the back of the thigh, responsible for bending the knee and extending the hip.\r\n2.1.4.	Adductor Magnus: A muscle in the thigh that helps stabilize the knee and hip joints.\r\n\r\n2.2.	Benefits of Good Morning Exercises\r\n2.2.1.	Improved Posture: Strengthening the muscles in the back and glutes can help improve posture and reduce the risk of back pain.\r\n2.2.2.	Increased Strength: Good morning exercises can help increase strength in the back, glutes, and hamstrings, which can improve overall athletic performance.\r\n2.2.3.	Reduced Injury Risk: Strengthening the muscles in the back and glutes can help reduce the risk of injury, particularly in the lower back and knees.\r\n2.2.4.	Improved Core Stability: Good morning exercises require engagement of the core muscles, which can help improve core stability and overall athletic performance.\r\n\r\n2.3.	Proper Way of Doing Bodyweight Good Morning exercise\r\n2.3.1.	Starting Position: Stand with your feet shoulder-width apart, toes facing forward or slightly outward.\r\n2.3.2.	Hinge at the Hips: Hinge at the hips, keeping your knees slightly bent, and lower your torso forward.\r\n2.3.3.	Keep Back Straight: Keep your back straight, engaging your core muscles to maintain stability.\r\n2.3.4.	Lower Torso: Lower your torso until your hands are below your knees or your body is parallel to the ground.\r\n2.3.5.	Return to Standing: Return to the standing position, squeezing your glutes and pushing your hips back.\r\n2.3.6.	Repeat: Repeat the movement as many times within the specified time frame.\r\n\r\n2.4.	Key Points to Focus On\r\n2.4.1.	Control the Movement: Avoid bouncing or jerking movements, and focus on slow, controlled movements.\r\n2.4.2.	Keep Knees Slightly Bent: Keep your knees slightly bent to maintain balance and prevent unnecessary strain on your joints.\r\n2.4.3.	Engage Core Muscles: Engage your core muscles to maintain stability and control throughout the movement.\r\n\r\n2.5.	Counting rules\r\n2.5.1.	One Repetition: One complete movement, including the lowering and standing phases, counts as one repetition.\r\n2.5.2.	Only Full Range of Motion Counts: Only count repetitions where the torso is lowered to the specified depth (almost parallel to the ground) and returned to the standing position.\r\n2.5.3.	No Partial Reps: Do not count partial repetitions where the torso is not fully lowered or returned to the standing position.\r\n2.5.4.	Time Limit: Count repetitions within the specified time frame.', '4', 'https://youtu.be/nczH_7m1TnI?si=vsS-SdxHDETeKBUw', 'uploads/exercises/PEnccYsSUaHeQGlytWSFUmfFcFM2mvh1pOZb5HCh.png', 'motherfits', 'active', '2025-08-31 01:54:22', '2025-10-07 09:38:33'),
(26, 'Squat Jumps', 'Per Second', 'Squat jumps exercises are a type of plyometric exercise that targets the muscles in the legs, glutes, and core. The exercise involves performing a squat, then explosively jumping up into the air, landing softly on the balls of your feet.\r\n\r\n23.1.	Targeted muscles\r\nSquat jumps exercises target the following muscles:\r\n23.1.1.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n23.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee.\r\n23.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n23.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n\r\n23.2.	Benefits of Squat Jumps \r\n23.2.1.	Improved Power and Explosiveness: Squat jumps exercises improve power and explosiveness in the legs, glutes, and core.\r\n23.2.2.	Increased Caloric Burn: The exercise is a great way to burn calories and aid in weight loss.\r\n23.2.3.	Improved Athletic Performance: Squat jumps exercises improve athletic performance in sports that involve jumping, hopping, and quick changes of direction.\r\n23.2.4.	Improved Bone Density: The exercise can help improve bone density in the legs, hips, and spine, reducing the risk of osteoporosis and fractures.\r\n\r\n23.3.	Proper Way of Doing Squat Jumps \r\n23.3.1.	Starting Position: Stand with your feet shoulder-width apart, toes facing forward or slightly outward.\r\n23.3.2.	Lower into Squat: Lower your body into a squat position, keeping your back straight, knees behind your toes, and weight in your heels.\r\n23.3.3.	Explosively Jump Up: Explosively jump up from the squat position, extending your hips and knees.\r\n23.3.4.	Land Softly: Land softly on the balls of your feet, immediately lowering into the next squat.\r\n23.3.5.	Repeat: Repeat the movement for specified time frame.\r\n\r\n\r\n\r\n23.4.	Key Points to Focus On\r\n23.4.1.	Proper Squat Form: Maintain proper squat form, keeping your back straight and knees behind your toes.\r\n23.4.2.	Explosive Jump: Focus on an explosive jump, extending your hips and knees.\r\n23.4.3.	Soft Landing: Land softly on the balls of your feet to reduce impact and prepare for the next repetition.\r\n\r\n23.5.	Counting rules\r\n23.5.1.	One Repetition: One complete movement, including the squat and jump phases, counts as one repetition.\r\n23.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the squat and jump phases.\r\n23.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n23.5.4.	Time Limit: Count repetitions for specified time frame.', '4', 'https://youtu.be/BRfxI2Es2lE?si=dr2n--H36FsT4BIi', 'uploads/exercises/i4Wb8xg81Jg6ZrJ4CKMVqzWBIsez27jv3mxKTykM.png', 'both', 'active', '2025-08-31 01:57:51', '2025-10-07 09:38:30'),
(27, 'Burpees', 'Per Second', 'Burpees exercises are a type of full-body exercise that combines a squat, push-up, and jump. The exercise involves starting in a standing position, then dropping down into a squat position, kicking your feet back into a plank position, doing a push-up, quickly returning your feet to the squat position, and finally standing up and jumping up in the air.\r\n\r\n26.1.	Targeted muscles\r\nBurpees exercises target multiple muscle groups, including:\r\n26.1.1.	Chest muscles (Pectoralis Major): Engaged during the push-up portion of the exercise.\r\n26.1.2.	Shoulder muscles (Deltoids and Trapezius): Engaged during the push-up and jump portions of the exercise.\r\n26.1.3.	Back muscles (Latissimus Dorsi and Rhomboids): Engaged during the push-up and squat portions of the exercise.\r\n26.1.4.	Leg muscles (Quadriceps, Hamstrings, and Glutes): Engaged during the squat and jump portions of the exercise.\r\n26.1.5.	Core muscles (Abdominals and Obliques): Engaged throughout the exercise to maintain stability and balance.\r\n\r\n26.2.	Benefits of Burpees \r\n26.2.1.	Improved Cardiovascular Endurance: Burpees exercises provide an excellent cardiovascular workout, improving heart health and increasing endurance.\r\n26.2.2.	Increased Strength and Power: The exercise helps build strength and power in multiple muscle groups, improving overall athletic performance.\r\n26.2.3.	Improved Coordination and Agility: Burpees exercises improve coordination and agility by challenging the muscles to quickly change direction and movement.\r\n26.2.4.	Time-Efficient Workout: Burpees exercises are a great way to get a full-body workout in a short amount of time.\r\n\r\n26.3.	Proper Way of Doing Bodyweight Burpees\r\n26.3.1.	Starting Position: Stand with your feet shoulder-width apart.\r\n26.3.2.	Drop Down: Drop down into a squat position and place your hands on the ground.\r\n26.3.3.	Kick Back: From the squat position, kick your feet back into a plank position.\r\n26.3.4.	Do a Push-Up: Do a push-up by lowering your body down until your chest almost touches the ground.\r\n26.3.5.	Quickly Return Feet: Quickly return your feet to the squat position.\r\n26.3.6.	Stand Up: Stand up from the squat position.\r\n26.3.7.	Jump Up: Jump up in the air, landing softly on the balls of your feet.\r\n26.3.8.	Repeat: Repeat the movement for specified time frame.\r\n\r\n26.4.	Key Points to Focus On\r\n26.4.1.	Keep Core Engaged: Engage your core muscles throughout the exercise.\r\n26.4.2.	Use Proper Form: Use proper form and technique throughout the exercise, including the squat, plank, and push-up positions.\r\n26.4.3.	Move Quickly: Move quickly and smoothly through the exercise, minimizing rest time.\r\n\r\n26.5.	Counting rules\r\n26.5.1.	One Repetition: One complete movement, including the squat, kick back, push-up, and jump phases, counts as one repetition.\r\n26.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including all phases.\r\n26.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n26.5.4.	Time Limit: Count repetitions for specified time frame.', '6', 'https://youtu.be/qLBImHhCXSw?si=WmlBvfeHz1vxWal6', 'uploads/exercises/ULyfCLazH6E3jf5JPzijAmm7RjPoPApFQJbddbsh.png', 'fatherfits', 'active', '2025-08-31 02:01:22', '2025-10-07 09:38:38'),
(28, 'Bicycle crunches', 'Per Second', 'Bicycle crunches exercises are a type of strength training exercise that targets the muscles in the core, specifically the rectus abdominis and obliques. The exercise involves lying on your back with your hands behind your head, then lifting your shoulders off the ground and bringing your elbow to the opposite knee, as if pedaling a bicycle.\r\n\r\n27.1.	Targeted muscles\r\nBicycle crunches exercises target the following muscles:\r\n27.1.1.	Rectus Abdominis: The muscle in the front of the abdomen that helps flex the spine and maintain posture.\r\n27.1.2.	Obliques (External and Internal): The muscles on the sides of the abdomen that help rotate the torso and maintain posture.\r\n27.1.3.	Transverse Abdominis: The muscle in the deep abdominal region that helps stabilize the spine and maintain posture.\r\n\r\n27.2.	Benefits of Bicycle Crunches \r\n27.2.1.	Improved Core Strength: Bicycle crunches exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n27.2.2.	Better Athletic Performance: The exercise improves athletic performance in sports that involve twisting and turning, such as tennis, golf, and basketball.\r\n27.2.3.	Reduced Risk of Injury: Strengthening the muscles in the core can help reduce the risk of injury in sports and everyday activities.\r\n27.2.4.	Improved Posture: Bicycle crunches exercises help improve posture by strengthening the muscles that support the spine and maintain proper alignment.\r\n\r\n27.3.	Proper Way of Doing Bodyweight Bicycle Crunches\r\n27.3.1.	Starting Position: Lie on your back with your hands behind your head, legs lifted and bent at a 90-degree angle.\r\n27.3.2.	Alternate Bringing Elbow to Opposite Knee: Alternate bringing your elbow to the opposite knee, as if pedalling a bicycle.\r\n27.3.3.	Focus on Core Engagement: Focus on engaging your core muscles to lift your shoulders off the ground.\r\n27.3.4.	Continue Alternating: Continue alternating sides for specified time frame.\r\n\r\n\r\n\r\n27.4.	Key Points to Focus On\r\n27.4.1.	Keep Core Engaged: Engage your core muscles throughout the exercise.\r\n27.4.2.	Avoid Straining Neck: Avoid straining your neck by keeping your hands lightly behind your head.\r\n27.4.3.	Use Controlled Movement: Use a controlled movement to alternate sides.\r\n\r\n27.5.	Counting rules\r\n27.5.1.	One Repetition: One complete movement, including the alternate bringing of elbow to opposite knee, counts as one repetition.\r\n27.5.2.	Second Time Limit: Count repetitions for specified time frame.\r\n27.5.3.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the alternate bringing of elbow to opposite knee.\r\n27.5.4.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.', '5', 'https://youtu.be/wpRI3xBhJmo?si=MAABwNdKKgwyCy7H', 'uploads/exercises/lv5WwUOCRQtx75rEx5lglq7uwkKydN6nxOAWb1rW.png', 'fatherfits', 'active', '2025-08-31 02:03:40', '2025-10-07 09:38:43'),
(29, 'Side plank', 'Per Count', 'Side plank exercises are a type of strength training exercise that targets the muscles in the core, specifically the obliques. The exercise involves starting in a side plank position, then lifting your hips off the ground and crunching your elbow to your knee.\r\n\r\n28.1.	Targeted muscles\r\nSide plank exercises target the following muscles:\r\n28.1.1.	Obliques (External and Internal): The muscles on the sides of the abdomen that help rotate the torso and maintain posture.\r\n28.1.2.	Transverse Abdominis: The muscle in the deep abdominal region that helps stabilize the spine and maintain posture.\r\n28.1.3.	Core muscles (Abdominals and Lower Back): Engage to maintain stability and balance throughout the exercise.\r\n\r\n28.2.	Benefits of Side Plank \r\n28.2.1.	Improved Core Strength: Side plank exercises help build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n28.2.2.	Better Athletic Performance: The exercise improves athletic performance in sports that involve twisting and turning, such as tennis, golf, and basketball.\r\n28.2.3.	Reduced Risk of Injury: Strengthening the muscles in the core can help reduce the risk of injury in sports and everyday activities.\r\n28.2.4.	Improved Posture: Side plank exercises help improve posture by strengthening the muscles that support the spine and maintain proper alignment.\r\n\r\n28.3.	Proper Way of Doing Bodyweight Side Plank \r\n28.3.1.	Starting Position: Lie on your side with your feet stacked and your hands under your shoulders.\r\n28.3.2.	Lift Hips Off Ground: Lift your hips off the ground, keeping your body in a straight line from head to heels.\r\n28.3.3.	Engage Core Muscles: Engage your core muscles to maintain stability and control.\r\n28.3.4.	Hold Position: Hold the same position, maintaining control and engagement of the core muscles.\r\n\r\n28.4.	Key Points to Focus On\r\n28.4.1.	Maintain Straight Line: Maintain a straight line from head to heels throughout the exercise.\r\n28.4.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control.\r\n28.4.3.	Avoid Letting Hips Sag: Avoid letting your hips sag or your body twist.\r\n\r\n28.5.	Counting rules\r\n28.5.1.	Time-Based Test: Hold the same position for as long as possible, up to a maximum time limit (e.g., 60 seconds).\r\n28.5.2.	Record Time: Record the time held in the same position.\r\n28.5.3.	No Repetitions: This test is based on time held in the same position, rather than repetitions.', '5', 'https://youtu.be/0Rl5ZQwmS-o?si=5wwSAA3264MsuL4h', 'uploads/exercises/Ir2ZRclXSxpDHgfrgwGcmZ7ixVRQsbwbLsKQ5Or6.png', 'fatherfits', 'active', '2025-08-31 02:05:32', '2025-10-07 09:38:05'),
(30, 'Single-Leg Deadlift', 'Per Count', 'Single-leg deadlift exercises are a type of strength training exercise that targets the muscles in the core, glutes, and legs. The exercise involves standing on one leg, bending at the hips and knees, and lifting the other leg behind you, while crunching your torso and lifting your arms.\r\n\r\n29.1.	Targeted muscles\r\nSingle-leg deadlift exercises target the following muscles:\r\n29.1.1.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n29.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee and extend the hip.\r\n29.1.3.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n29.1.4.	Erector Spinae: The muscles in the lower back that help straighten and stabilize the spine.\r\n\r\n29.2.	Benefits of Single-Leg Deadlift \r\n29.2.1.	Improved Balance and Coordination: Single-leg deadlift exercises improve balance and coordination by challenging the muscles to maintain stability and control.\r\n29.2.2.	Increased Core Strength: The exercise helps build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n29.2.3.	Better Glute Strength: Single-leg deadlift exercises help build strong glute muscles, which are essential for everyday activities, sports, and overall athletic performance.\r\n29.2.4.	Reduced Risk of Injury: Strengthening the muscles in the core, glutes, and legs can help reduce the risk of injury in sports and everyday activities.\r\n\r\n29.3.	Proper Way of Doing Bodyweight Single Leg Deadlift \r\n29.3.1.	Starting Position: Stand on one leg, with the other foot lifted off the ground.\r\n29.3.2.	Bend at Hips: Bend at the hips, keeping your back straight, and lower your torso down towards the ground.\r\n29.3.3.	Keep Leg Straight: Keep the standing leg straight and engage your core muscles.\r\n29.3.4.	Lower Down Slowly: Lower down slowly and control the movement as you lift back up.\r\n29.3.5.	Lift Back Up: Lift back up to the starting position, squeezing your glutes and pushing your hips back.\r\n29.3.6.	Repeat: Repeat the movement with the same leg for specified time frame.\r\n\r\n\r\n29.4.	Key Points to Focus On\r\n29.4.1.	Keep Back Straight: Keep your back straight throughout the exercise.\r\n29.4.2.	Engage Core Muscles: Engage your core muscles to maintain stability and control.\r\n29.4.3.	Use Proper Form: Use proper form and technique throughout the exercise.\r\n\r\n29.5.	Counting rules\r\n29.5.1.	One Repetition: One complete movement, including the lower and lift phases, counts as one repetition.\r\n29.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the lower and lift phases.\r\n29.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n29.5.4.	Time Limit: Count repetitions for specified time frame, one leg.', '4', 'https://youtu.be/HtHxnWmMgzM?si=kmGQoH7nKcMTvad9', 'uploads/exercises/jCaLg5ZPfKyf7NkeUB16ppbmp2ww2MHa6Y8VVqXS.png', 'fatherfits', 'active', '2025-08-31 02:07:57', '2025-10-07 09:38:02'),
(31, 'Renegade rows', 'Per Count', 'Renegade rows exercises are a type of strength training exercise that targets the muscles in the back, core, and arms. The exercise involves starting in a plank position, then lifting one arm and rowing it towards your side, while crunching your torso and lifting your hips.\r\n\r\n30.1.	Targeted muscles\r\nRenegade rows exercises target the following muscles:\r\n30.1.1.	Latissimus Dorsi: The muscles in the middle and upper back that help extend and adduct the shoulder joint.\r\n30.1.2.	Trapezius: The muscle in the upper back that helps shrug the shoulders and rotate the scapula.\r\n30.1.3.	Rhomboids: The muscles in the upper back that help stabilize and rotate the scapula.\r\n30.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n30.1.5.	Biceps Brachii: The muscle in the front of the upper arm that helps flex the elbow joint.\r\n\r\n30.2.	Benefits of Renegade Rows \r\n30.2.1.	Improved Back Strength: Renegade rows exercises help build strong back muscles, which are essential for everyday activities, sports, and overall athletic performance.\r\n30.2.2.	Increased Core Strength: The exercise helps build strong core muscles, which are essential for maintaining good posture, balance, and overall athletic performance.\r\n30.2.3.	Better Arm Strength: Renegade rows exercises help build strong arm muscles, which are essential for everyday activities, sports, and overall athletic performance.\r\n30.2.4.	Reduced Risk of Injury: Strengthening the muscles in the back, core, and arms can help reduce the risk of injury in sports and everyday activities.\r\n\r\n30.3.	Proper Way of Doing Bodyweight Renegade Rows\r\n30.3.1.	Starting Position: Start in a plank position with your hands shoulder-width apart and your feet hip-width apart.\r\n30.3.2.	Lift One Hand: Lift one hand off the ground and row it towards your side, keeping your elbow close to your body.\r\n30.3.3.	Keep Core Engaged: Keep your core muscles engaged to maintain stability and control.\r\n30.3.4.	Lower Hand Back Down: Lower your hand back down to the starting position.\r\n30.3.5.	Repeat with Other Hand: Repeat the movement with the other hand.\r\n30.3.6.	Continue Alternating: Continue alternating hands for specified time frame.\r\n\r\n\r\n30.4.	Key Points to Focus On\r\n30.4.1.	Keep Core Engaged: Engage your core muscles to maintain stability and control.\r\n30.4.2.	Use Proper Rowing Form: Use proper rowing form, keeping your elbow close to your body.\r\n30.4.3.	Avoid Letting Hips Sag: Avoid letting your hips sag or your body twist.\r\n\r\n30.5.	Counting rules\r\n30.5.1.	One Repetition: One complete movement, including the lift and lower phases with one hand, counts as one repetition.\r\n30.5.2.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the lift and lower phases with one hand.\r\n30.5.3.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.\r\n30.5.4.	Time Limit: Count repetitions for specified time frame.', '3', 'https://youtu.be/w2GNGfMk7Nc?si=IaLpVwXrqzZ2ORZb', 'uploads/exercises/gTd9C9Yd8XziVIbLrXINaU3NUQm4PqfgVJyFiSaB.png', 'fatherfits', 'active', '2025-08-31 02:10:10', '2025-10-07 09:37:59'),
(32, 'Box jumps', 'Per Count', 'Box jumps exercises are a type of plyometric exercise that targets the muscles in the legs, glutes, and core. The exercise involves jumping up onto a box or platform, then immediately performing a crunch by lifting your torso and shoulders off the ground.\r\n\r\n31.1.	Targeted muscles\r\nBox jumps exercises target the following muscles:\r\n31.1.1.	Quadriceps: The muscles in the front of the thigh that help straighten the knee.\r\n31.1.2.	Hamstrings: The muscles in the back of the thigh that help bend the knee and extend the hip.\r\n31.1.3.	Gluteus Maximus: The largest muscle in the buttocks, responsible for extending and rotating the hip joint.\r\n31.1.4.	Core muscles (Abdominals and Obliques): Engage to maintain stability and balance throughout the exercise.\r\n31.1.5.	Rectus Abdominis: The muscle in the front of the abdomen that helps flex the spine and maintain posture.\r\n\r\n31.2.	Benefits of Box Jumps \r\n31.2.1.	Improved Power and Explosiveness: Box jumps exercises improve power and explosiveness in the legs, glutes, and core.\r\n31.2.2.	Increased Caloric Burn: The exercise is a great way to burn calories and aid in weight loss.\r\n31.2.3.	Improved Athletic Performance: Box jumps exercises improve athletic performance in sports that involve jumping, hopping, and quick changes of direction.\r\n31.2.4.	Reduced Risk of Injury: Strengthening the muscles in the legs, glutes, and core can help reduce the risk of injury in sports and everyday activities.\r\n\r\n31.3.	Proper Way of Doing Bodyweight Box Jump \r\n31.3.1.	Starting Position: Stand in front of a box or bench with your feet shoulder-width apart.\r\n31.3.2.	Explode Upwards: Explode upwards from the ground, extending your hips and knees.\r\n31.3.3.	Land on Box: Land on the box with both feet, absorbing the impact by bending your knees.\r\n31.3.4.	Step Down: Step down from the box and return to the starting position.\r\n31.3.5.	Repeat: Repeat the movement for specified time frame.\r\n\r\n\r\n\r\n31.4.	Key Points to Focus On\r\n31.4.1.	Proper Take off Technique: Use proper take off technique, exploding upwards from the ground.\r\n31.4.2.	Soft Landing: Land softly on the box, absorbing the impact by bending your knees.\r\n31.4.3.	Controlled Movement: Use a controlled movement when stepping down from the box.\r\n\r\n31.5.	Counting rules\r\n31.5.1.	One Repetition: One complete movement, including the jump and step down phases, counts as one repetition.\r\n31.5.2.	Time Limit: Count repetitions for specified time frame.\r\n31.5.3.	Only Full Movement Counts: Only count repetitions where the full movement is completed, including the jump and step down phases.\r\n31.5.4.	No Partial Reps: Do not count partial repetitions where the full movement is not completed.', '4', 'https://youtu.be/kNIInK_Le8I?si=QdjBwtPLRgUqxc7r', 'uploads/exercises/uryMNp2JyAoGe75TUUkmHcBtYCeWOwXpVSlFyAsv.png', 'fatherfits', 'active', '2025-08-31 02:12:00', '2025-10-07 09:37:56');
INSERT INTO `exercises` (`id`, `name`, `exercise_type`, `description`, `exercise_category_id`, `youtube_link`, `image`, `genz`, `status`, `created_at`, `updated_at`) VALUES
(33, 'Push Ups', 'Per Count', 'Push-ups are a classic bodyweight exercise that targets multiple muscle groups, improving overall upper body strength, endurance, and flexibility.\r\n\r\n33.1.	Targeted Muscles\r\n33.1.1.	Pectoralis major (chest muscle)\r\n33.1.2.	Anterior deltoids (front shoulder muscle)\r\n33.1.3.	Triceps brachii (back arm muscle)\r\n33.1.4.	Serratus anterior (side chest muscle)\r\n33.1.5.	Core muscles (abdominals and lower back)\r\n\r\n33.2.	Benefits\r\n33.2.1.	Builds strength and endurance in the upper body\r\n33.2.2.	Improves overall chest development\r\n33.2.3.	Engages the core muscles, promoting stability and balance\r\n33.2.4.	Can be modified to suit different fitness levels\r\n33.2.5.	Improves posture and reduces the risk of shoulder injuries\r\n\r\n33.3.	Proper Way of Doing Bodyweight Push-ups for Fitness Test\r\n33.3.1.	Starting Position: Begin in a plank position with hands shoulder-width apart and feet hip-width apart.\r\n33.3.2.	Engage Core: Activate core muscles by drawing belly button towards spine.\r\n33.3.3.	Lower Body: Lower body down towards ground, keeping elbows close to body, until chest nearly touches ground.\r\n33.3.4.	Push Back Up: Push back up to starting position, extending arms fully.\r\n33.3.5.	Repeat: Repeat the movement for specified time frame.  \r\n\r\n33.4.	Key Points to Focus On\r\n33.4.1.	Maintain straight line from head to heels.\r\n33.4.2.	Use controlled movement when lowering and pushing back up.\r\n33.4.3.	Avoid letting hips sag or back arch.\r\n\r\n33.5.	Counting rules\r\n33.5.1.	One Repetition: One complete push-up, from starting position to lowest point and back up again, counts as one repetition.\r\n33.5.2.	Only Full Reps: Only count full repetitions where body is lowered down and pushed back up to starting position.\r\n33.5.3.	Score: Individual\'s score is total number of full push-ups within time limit.\r\n33.5.4.	Time Limit: Count repetitions within specified time limit (e.g., 30-60 seconds).', '3', 'https://youtu.be/WDIpL0pjun0?si=lLmm4JPcIkmO0-l_', 'uploads/exercises/c3tRkvMyZlPpIGOSQQQNKZp3hmRLLpOb9IA4GL3T.png', 'fatherfits', 'active', '2025-08-31 02:15:07', '2025-10-07 09:37:53'),
(34, 'client', 'Per Count', 'client', '5', NULL, 'uploads/exercises/U6nrP1VIYO3pvPHlQBrsRoQtVTSBmRFYchgB03I1.png', 'motherfits', 'active', '2025-10-07 12:52:28', '2025-10-07 12:52:28'),
(35, 'test-don', 'Per Count', 'test-don', '6', 'https://www.youtube.com/watch?v=PphkkJIkGFk&list=RDPphkkJIkGFk&start_radio=1', 'uploads/exercises/AmEd57KM5BeECvaxoDLYPkHLQ78an00WrrNTdHjH.png', 'motherfits', 'active', '2025-10-07 12:58:04', '2025-10-07 13:13:45'),
(36, 'testing', NULL, 'juu', '7', 'https://pnac.gov.pk/training-admin', 'uploads/exercises/VAtHE3tEbbonFs8Y7riwkNZXpffr1wGO7v9WHeqm.png', 'fatherfits', 'active', '2025-10-28 15:07:12', '2025-10-28 15:07:12'),
(37, 'Eaton Mccoy', 'Per Second', 'Eum recusandae Cons', '6', 'https://www.xazizitepyhomo.biz', 'uploads/exercises/Ma1R9un9vzP8fmyxUTksO0JB7rbasrGnL79r6VeQ.jpg', 'both', 'active', '2026-01-09 12:27:46', '2026-01-09 12:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_categories`
--

CREATE TABLE `exercise_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `overall_time` varchar(255) DEFAULT NULL,
  `over_kcal` varchar(255) DEFAULT NULL,
  `exerice_lvl` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercise_categories`
--

INSERT INTO `exercise_categories` (`id`, `name`, `tag`, `overall_time`, `over_kcal`, `exerice_lvl`, `description`, `image`, `created_at`, `updated_at`) VALUES
(4, 'Lower Body', 'featured', NULL, NULL, NULL, NULL, NULL, '2025-08-30 23:22:57', '2025-08-30 23:22:57'),
(5, 'Core', 'featured', NULL, NULL, NULL, NULL, NULL, '2025-08-30 23:23:19', '2025-08-30 23:23:19'),
(3, 'Upper Body', 'featured', NULL, NULL, NULL, NULL, NULL, '2025-08-30 23:22:34', '2025-08-30 23:22:34'),
(6, 'Cardio', 'featured', NULL, NULL, NULL, NULL, NULL, '2025-08-30 23:23:34', '2025-08-30 23:23:34'),
(7, 'Back', 'featured', NULL, NULL, NULL, NULL, NULL, '2025-08-30 23:25:08', '2025-08-30 23:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'b388465c-c9b5-49f1-813b-a1086e55d136', 'database', 'default', '{\"uuid\":\"b388465c-c9b5-49f1-813b-a1086e55d136\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"1\\\";}\"},\"createdAt\":1754651378,\"delay\":null}', 'BadMethodCallException: Call to undefined method App\\Models\\CompetitionUser::scores() in C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:67\nStack trace:\n#0 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php(36): Illuminate\\Database\\Eloquent\\Model::throwBadMethodCallException(\'scores\')\n#1 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(2449): Illuminate\\Database\\Eloquent\\Model->forwardCallTo(Object(Illuminate\\Database\\Eloquent\\Builder), \'scores\', Array)\n#2 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(1108): Illuminate\\Database\\Eloquent\\Model->__call(\'scores\', Array)\n#3 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\Concerns\\{closure}()\n#4 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(1107): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints(Object(Closure))\n#5 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(858): Illuminate\\Database\\Eloquent\\Builder->getRelationWithoutConstraints(\'scores\')\n#6 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(987): Illuminate\\Database\\Eloquent\\Builder->withAggregate(Array, \'score\', \'sum\')\n#7 C:\\laragon\\www\\stack-buffers\\maxfit\\app\\Jobs\\ProcessCompetitionResults.php(33): Illuminate\\Database\\Eloquent\\Builder->withSum(\'scores\', \'score\')\n#8 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#9 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#10 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#11 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#12 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#13 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#14 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#15 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#16 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#17 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessCompetitionResults), false)\n#18 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#19 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#20 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#21 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessCompetitionResults))\n#22 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#23 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#24 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#25 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#28 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#29 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#30 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#31 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#32 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#33 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#34 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#35 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\stack-buffers\\maxfit\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#42 {main}', '2025-08-08 06:09:47'),
(2, '96a93307-70ae-47f7-840c-6aee2074158c', 'database', 'default', '{\"uuid\":\"96a93307-70ae-47f7-840c-6aee2074158c\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"1\\\";}\"},\"createdAt\":1754651467,\"delay\":null}', 'BadMethodCallException: Call to undefined method App\\Models\\CompetitionUser::scores() in C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:67\nStack trace:\n#0 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php(36): Illuminate\\Database\\Eloquent\\Model::throwBadMethodCallException(\'scores\')\n#1 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(2449): Illuminate\\Database\\Eloquent\\Model->forwardCallTo(Object(Illuminate\\Database\\Eloquent\\Builder), \'scores\', Array)\n#2 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(1108): Illuminate\\Database\\Eloquent\\Model->__call(\'scores\', Array)\n#3 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\Concerns\\{closure}()\n#4 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(1107): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints(Object(Closure))\n#5 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(858): Illuminate\\Database\\Eloquent\\Builder->getRelationWithoutConstraints(\'scores\')\n#6 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships.php(987): Illuminate\\Database\\Eloquent\\Builder->withAggregate(Array, \'score\', \'sum\')\n#7 C:\\laragon\\www\\stack-buffers\\maxfit\\app\\Jobs\\ProcessCompetitionResults.php(36): Illuminate\\Database\\Eloquent\\Builder->withSum(\'scores\', \'score\')\n#8 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#9 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#10 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#11 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#12 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#13 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#14 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#15 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#16 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#17 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessCompetitionResults), false)\n#18 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#19 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#20 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#21 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessCompetitionResults))\n#22 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#23 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#24 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#25 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#28 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#29 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#30 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#31 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#32 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#33 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#34 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#35 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\stack-buffers\\maxfit\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#42 {main}', '2025-08-08 06:11:11'),
(3, '16ee461e-4814-4824-bacb-e6907e08edec', 'database', 'default', '{\"uuid\":\"16ee461e-4814-4824-bacb-e6907e08edec\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"1\\\";}\"},\"createdAt\":1754651768,\"delay\":null}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'competition_id\' in \'where clause\' in C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:404\nStack trace:\n#0 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(404): PDO->prepare(\'select `competi...\')\n#1 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(809): Illuminate\\Database\\Connection->Illuminate\\Database\\{closure}(\'select `competi...\', Array)\n#2 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(776): Illuminate\\Database\\Connection->runQueryCallback(\'select `competi...\', Array, Object(Closure))\n#3 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(395): Illuminate\\Database\\Connection->run(\'select `competi...\', Array, Object(Closure))\n#4 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3117): Illuminate\\Database\\Connection->select(\'select `competi...\', Array, true)\n#5 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3102): Illuminate\\Database\\Query\\Builder->runSelect()\n#6 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3689): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#7 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3101): Illuminate\\Database\\Query\\Builder->onceWithColumns(Array, Object(Closure))\n#8 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(871): Illuminate\\Database\\Query\\Builder->get(Array)\n#9 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(853): Illuminate\\Database\\Eloquent\\Builder->getModels(Array)\n#10 C:\\laragon\\www\\stack-buffers\\maxfit\\app\\Jobs\\ProcessCompetitionResults.php(35): Illuminate\\Database\\Eloquent\\Builder->get()\n#11 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#12 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#13 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#14 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#15 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#16 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#17 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#18 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#19 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#20 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessCompetitionResults), false)\n#21 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#22 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#23 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessCompetitionResults))\n#25 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#26 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#27 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#31 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#32 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#33 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#34 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#35 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#36 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#37 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#39 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\laragon\\www\\stack-buffers\\maxfit\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#45 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'competition_id\' in \'where clause\' (Connection: mysql, SQL: select `competition_users`.*, (select sum(`competition_results`.`score`) from `competition_results` where `competition_users`.`id` = `competition_results`.`competition_user_id`) as `results_sum_score` from `competition_users` where `competition_id` = 1) in C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:822\nStack trace:\n#0 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(776): Illuminate\\Database\\Connection->runQueryCallback(\'select `competi...\', Array, Object(Closure))\n#1 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(395): Illuminate\\Database\\Connection->run(\'select `competi...\', Array, Object(Closure))\n#2 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3117): Illuminate\\Database\\Connection->select(\'select `competi...\', Array, true)\n#3 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3102): Illuminate\\Database\\Query\\Builder->runSelect()\n#4 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3689): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#5 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3101): Illuminate\\Database\\Query\\Builder->onceWithColumns(Array, Object(Closure))\n#6 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(871): Illuminate\\Database\\Query\\Builder->get(Array)\n#7 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(853): Illuminate\\Database\\Eloquent\\Builder->getModels(Array)\n#8 C:\\laragon\\www\\stack-buffers\\maxfit\\app\\Jobs\\ProcessCompetitionResults.php(35): Illuminate\\Database\\Eloquent\\Builder->get()\n#9 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#10 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#12 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#13 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#14 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#15 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#16 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#17 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessCompetitionResults), false)\n#19 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#20 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#21 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessCompetitionResults))\n#23 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#24 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#29 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#32 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#33 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#34 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#35 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\stack-buffers\\maxfit\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#43 {main}', '2025-08-08 06:16:12');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(4, 'd954ac41-b134-44d9-a850-2f8c49d1d71d', 'database', 'default', '{\"uuid\":\"d954ac41-b134-44d9-a850-2f8c49d1d71d\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"1\\\";}\"},\"createdAt\":1754651874,\"delay\":null}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'competition_id\' in \'where clause\' in C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:404\nStack trace:\n#0 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(404): PDO->prepare(\'select `competi...\')\n#1 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(809): Illuminate\\Database\\Connection->Illuminate\\Database\\{closure}(\'select `competi...\', Array)\n#2 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(776): Illuminate\\Database\\Connection->runQueryCallback(\'select `competi...\', Array, Object(Closure))\n#3 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(395): Illuminate\\Database\\Connection->run(\'select `competi...\', Array, Object(Closure))\n#4 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3117): Illuminate\\Database\\Connection->select(\'select `competi...\', Array, true)\n#5 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3102): Illuminate\\Database\\Query\\Builder->runSelect()\n#6 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3689): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#7 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3101): Illuminate\\Database\\Query\\Builder->onceWithColumns(Array, Object(Closure))\n#8 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(871): Illuminate\\Database\\Query\\Builder->get(Array)\n#9 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(853): Illuminate\\Database\\Eloquent\\Builder->getModels(Array)\n#10 C:\\laragon\\www\\stack-buffers\\maxfit\\app\\Jobs\\ProcessCompetitionResults.php(35): Illuminate\\Database\\Eloquent\\Builder->get()\n#11 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#12 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#13 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#14 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#15 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#16 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#17 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#18 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#19 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#20 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessCompetitionResults), false)\n#21 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#22 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#23 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessCompetitionResults))\n#25 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#26 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#27 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#30 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#31 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#32 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#33 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#34 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#35 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#36 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#37 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#39 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\laragon\\www\\stack-buffers\\maxfit\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#45 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'competition_id\' in \'where clause\' (Connection: mysql, SQL: select `competition_users`.*, (select sum(`competition_results`.`score`) from `competition_results` where `competition_users`.`id` = `competition_results`.`competition_user_id`) as `results_sum_score` from `competition_users` where `competition_id` = 1) in C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:822\nStack trace:\n#0 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(776): Illuminate\\Database\\Connection->runQueryCallback(\'select `competi...\', Array, Object(Closure))\n#1 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(395): Illuminate\\Database\\Connection->run(\'select `competi...\', Array, Object(Closure))\n#2 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3117): Illuminate\\Database\\Connection->select(\'select `competi...\', Array, true)\n#3 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3102): Illuminate\\Database\\Query\\Builder->runSelect()\n#4 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3689): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()\n#5 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3101): Illuminate\\Database\\Query\\Builder->onceWithColumns(Array, Object(Closure))\n#6 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(871): Illuminate\\Database\\Query\\Builder->get(Array)\n#7 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(853): Illuminate\\Database\\Eloquent\\Builder->getModels(Array)\n#8 C:\\laragon\\www\\stack-buffers\\maxfit\\app\\Jobs\\ProcessCompetitionResults.php(35): Illuminate\\Database\\Eloquent\\Builder->get()\n#9 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#10 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#12 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#13 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#14 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#15 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#16 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#17 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\ProcessCompetitionResults), false)\n#19 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#20 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\ProcessCompetitionResults))\n#21 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\ProcessCompetitionResults))\n#23 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#24 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#29 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#32 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#33 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#34 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#35 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\stack-buffers\\maxfit\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\stack-buffers\\maxfit\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#43 {main}', '2025-08-08 06:17:58'),
(5, '211207fc-5a72-4493-af15-f803947e57f7', 'database', 'default', '{\"uuid\":\"211207fc-5a72-4493-af15-f803947e57f7\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"4\\\";}\"},\"createdAt\":1767966811,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\RelationNotFoundException: Call to undefined relationship [physicalAssessment] on model [App\\Models\\User]. in /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/RelationNotFoundException.php:35\nStack trace:\n#0 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(938): Illuminate\\Database\\Eloquent\\RelationNotFoundException::make()\n#1 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\{closure}()\n#2 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(934): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints()\n#3 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(908): Illuminate\\Database\\Eloquent\\Builder->getRelation()\n#4 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#5 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#6 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(212): Illuminate\\Database\\Eloquent\\Builder->get()\n#7 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(175): Illuminate\\Database\\Eloquent\\Relations\\Relation->get()\n#8 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(919): Illuminate\\Database\\Eloquent\\Relations\\Relation->getEager()\n#9 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#10 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#11 /home/coolhpkn/maxfit.cooleats.cool/app/Jobs/ProcessCompetitionResults.php(42): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#13 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#17 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#18 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#21 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then()\n#25 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(391): Illuminate\\Queue\\Worker->process()\n#29 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(177): Illuminate\\Queue\\Worker->runJob()\n#30 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#31 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#37 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#38 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#44 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /home/coolhpkn/maxfit.cooleats.cool/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2026-01-09 18:55:06'),
(6, '456345ad-f908-4422-af7a-6d9e37b83e6e', 'database', 'default', '{\"uuid\":\"456345ad-f908-4422-af7a-6d9e37b83e6e\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"4\\\";}\"},\"createdAt\":1767967434,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\RelationNotFoundException: Call to undefined relationship [physicalAssessment] on model [App\\Models\\User]. in /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/RelationNotFoundException.php:35\nStack trace:\n#0 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(938): Illuminate\\Database\\Eloquent\\RelationNotFoundException::make()\n#1 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\{closure}()\n#2 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(934): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints()\n#3 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(908): Illuminate\\Database\\Eloquent\\Builder->getRelation()\n#4 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#5 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#6 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(212): Illuminate\\Database\\Eloquent\\Builder->get()\n#7 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(175): Illuminate\\Database\\Eloquent\\Relations\\Relation->get()\n#8 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(919): Illuminate\\Database\\Eloquent\\Relations\\Relation->getEager()\n#9 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#10 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#11 /home/coolhpkn/maxfit.cooleats.cool/app/Jobs/ProcessCompetitionResults.php(42): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#13 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#17 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#18 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#21 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then()\n#25 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(391): Illuminate\\Queue\\Worker->process()\n#29 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(177): Illuminate\\Queue\\Worker->runJob()\n#30 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#31 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#37 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#38 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Application.php(163): Symfony\\Component\\Console\\Application->run()\n#44 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(426): Illuminate\\Console\\Application->call()\n#45 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(361): Illuminate\\Foundation\\Console\\Kernel->call()\n#46 /home/coolhpkn/maxfit.cooleats.cool/routes/web.php(171): Illuminate\\Support\\Facades\\Facade::__callStatic()\n#47 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/CallableDispatcher.php(39): Illuminate\\Routing\\RouteFileRegistrar->{closure}()\n#48 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Route.php(243): Illuminate\\Routing\\CallableDispatcher->dispatch()\n#49 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Route.php(214): Illuminate\\Routing\\Route->runCallable()\n#50 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Router.php(808): Illuminate\\Routing\\Route->run()\n#51 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}()\n#52 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php(50): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#53 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Routing\\Middleware\\SubstituteBindings->handle()\n#54 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php(87): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#55 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken->handle()\n#56 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php(48): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#57 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\View\\Middleware\\ShareErrorsFromSession->handle()\n#58 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(120): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#59 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(63): Illuminate\\Session\\Middleware\\StartSession->handleStatefulRequest()\n#60 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Session\\Middleware\\StartSession->handle()\n#61 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php(36): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#62 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle()\n#63 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php(74): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#64 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle()\n#65 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#66 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Router.php(807): Illuminate\\Pipeline\\Pipeline->then()\n#67 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Router.php(786): Illuminate\\Routing\\Router->runRouteWithinStack()\n#68 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Router.php(750): Illuminate\\Routing\\Router->runRoute()\n#69 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Routing/Router.php(739): Illuminate\\Routing\\Router->dispatchToRoute()\n#70 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(200): Illuminate\\Routing\\Router->dispatch()\n#71 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}()\n#72 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#73 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php(31): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle()\n#74 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull->handle()\n#75 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#76 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php(51): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle()\n#77 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Foundation\\Http\\Middleware\\TrimStrings->handle()\n#78 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#79 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Http\\Middleware\\ValidatePostSize->handle()\n#80 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php(109): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#81 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance->handle()\n#82 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php(48): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#83 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Http\\Middleware\\HandleCors->handle()\n#84 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php(58): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#85 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Http\\Middleware\\TrustProxies->handle()\n#86 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php(22): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#87 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Foundation\\Http\\Middleware\\InvokeDeferredCallbacks->handle()\n#88 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php(26): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#89 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(208): Illuminate\\Http\\Middleware\\ValidatePathEncoding->handle()\n#90 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#91 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(175): Illuminate\\Pipeline\\Pipeline->then()\n#92 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(144): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter()\n#93 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1219): Illuminate\\Foundation\\Http\\Kernel->handle()\n#94 /home/coolhpkn/maxfit.cooleats.cool/public/index.php(20): Illuminate\\Foundation\\Application->handleRequest()\n#95 {main}', '2026-01-09 19:04:07');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(7, 'dd51f1bb-e373-4238-9927-3cf88ab22905', 'database', 'default', '{\"uuid\":\"dd51f1bb-e373-4238-9927-3cf88ab22905\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:1:\\\"4\\\";}\"},\"createdAt\":1769149055,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\RelationNotFoundException: Call to undefined relationship [physicalAssessment] on model [App\\Models\\User]. in /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/RelationNotFoundException.php:35\nStack trace:\n#0 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(938): Illuminate\\Database\\Eloquent\\RelationNotFoundException::make()\n#1 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\{closure}()\n#2 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(934): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints()\n#3 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(908): Illuminate\\Database\\Eloquent\\Builder->getRelation()\n#4 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#5 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#6 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(212): Illuminate\\Database\\Eloquent\\Builder->get()\n#7 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(175): Illuminate\\Database\\Eloquent\\Relations\\Relation->get()\n#8 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(919): Illuminate\\Database\\Eloquent\\Relations\\Relation->getEager()\n#9 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#10 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#11 /home/coolhpkn/maxfit.cooleats.cool/app/Jobs/ProcessCompetitionResults.php(42): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#13 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#17 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#18 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#21 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then()\n#25 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(391): Illuminate\\Queue\\Worker->process()\n#29 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(177): Illuminate\\Queue\\Worker->runJob()\n#30 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#31 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#37 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#38 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#44 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /home/coolhpkn/maxfit.cooleats.cool/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2026-01-23 11:20:07'),
(8, 'babdc75b-cb94-4809-863f-6ef02a6ac35b', 'database', 'default', '{\"uuid\":\"babdc75b-cb94-4809-863f-6ef02a6ac35b\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:2:\\\"19\\\";}\"},\"createdAt\":1769685809,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\RelationNotFoundException: Call to undefined relationship [physicalAssessment] on model [App\\Models\\User]. in /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/RelationNotFoundException.php:35\nStack trace:\n#0 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(938): Illuminate\\Database\\Eloquent\\RelationNotFoundException::make()\n#1 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\{closure}()\n#2 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(934): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints()\n#3 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(908): Illuminate\\Database\\Eloquent\\Builder->getRelation()\n#4 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#5 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#6 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(212): Illuminate\\Database\\Eloquent\\Builder->get()\n#7 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(175): Illuminate\\Database\\Eloquent\\Relations\\Relation->get()\n#8 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(919): Illuminate\\Database\\Eloquent\\Relations\\Relation->getEager()\n#9 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#10 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#11 /home/coolhpkn/maxfit.cooleats.cool/app/Jobs/ProcessCompetitionResults.php(42): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#13 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#17 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#18 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#21 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then()\n#25 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(391): Illuminate\\Queue\\Worker->process()\n#29 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(177): Illuminate\\Queue\\Worker->runJob()\n#30 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#31 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#37 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#38 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#44 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /home/coolhpkn/maxfit.cooleats.cool/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2026-01-29 16:25:06'),
(9, 'e6f1d006-5f8b-4fef-93c1-6bdfcf584531', 'database', 'default', '{\"uuid\":\"e6f1d006-5f8b-4fef-93c1-6bdfcf584531\",\"displayName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessCompetitionResults\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\ProcessCompetitionResults\\\":1:{s:13:\\\"competitionId\\\";s:2:\\\"19\\\";}\"},\"createdAt\":1769754971,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\RelationNotFoundException: Call to undefined relationship [physicalAssessment] on model [App\\Models\\User]. in /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/RelationNotFoundException.php:35\nStack trace:\n#0 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(938): Illuminate\\Database\\Eloquent\\RelationNotFoundException::make()\n#1 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(119): Illuminate\\Database\\Eloquent\\Builder->Illuminate\\Database\\Eloquent\\{closure}()\n#2 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(934): Illuminate\\Database\\Eloquent\\Relations\\Relation::noConstraints()\n#3 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(908): Illuminate\\Database\\Eloquent\\Builder->getRelation()\n#4 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#5 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#6 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(212): Illuminate\\Database\\Eloquent\\Builder->get()\n#7 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Relations/Relation.php(175): Illuminate\\Database\\Eloquent\\Relations\\Relation->get()\n#8 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(919): Illuminate\\Database\\Eloquent\\Relations\\Relation->getEager()\n#9 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(888): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelation()\n#10 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(854): Illuminate\\Database\\Eloquent\\Builder->eagerLoadRelations()\n#11 /home/coolhpkn/maxfit.cooleats.cool/app/Jobs/ProcessCompetitionResults.php(42): Illuminate\\Database\\Eloquent\\Builder->get()\n#12 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\ProcessCompetitionResults->handle()\n#13 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#17 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#18 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#21 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then()\n#25 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(391): Illuminate\\Queue\\Worker->process()\n#29 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(177): Illuminate\\Queue\\Worker->runJob()\n#30 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#31 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Container/Container.php(754): Illuminate\\Container\\BoundMethod::call()\n#37 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#38 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /home/coolhpkn/maxfit.cooleats.cool/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#44 /home/coolhpkn/maxfit.cooleats.cool/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /home/coolhpkn/maxfit.cooleats.cool/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2026-01-30 11:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `set_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`days`))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `set_id`, `exercise_id`, `value`, `created_at`, `updated_at`, `start_date`, `end_date`, `days`) VALUES
(32, '157', 13, 8, '5', '2026-01-29 16:35:23', '2026-01-29 16:35:23', '2026-01-29', '2026-02-26', '[\"T\",\"M\",\"W\",\"Th\",\"S\",\"Su\",\"F\"]'),
(31, '157', 13, 28, '3', '2026-01-29 16:35:23', '2026-01-29 16:35:23', '2026-01-29', '2026-02-26', '[\"T\",\"M\",\"W\",\"Th\",\"S\",\"Su\",\"F\"]'),
(30, '157', 13, 3, '4', '2026-01-29 16:35:23', '2026-01-29 16:35:23', '2026-01-29', '2026-02-26', '[\"T\",\"M\",\"W\",\"Th\",\"S\",\"Su\",\"F\"]'),
(29, '157', 13, 21, '10', '2026-01-29 16:35:23', '2026-01-29 16:35:23', '2026-01-29', '2026-02-26', '[\"T\",\"M\",\"W\",\"Th\",\"S\",\"Su\",\"F\"]'),
(28, '156', 13, 8, '8', '2026-01-29 15:09:53', '2026-01-29 15:09:53', '2026-01-29', '2026-02-28', '[\"M\",\"T\",\"W\",\"Th\",\"F\",\"S\",\"Su\"]'),
(27, '156', 13, 28, '5', '2026-01-29 15:09:53', '2026-01-29 15:09:53', '2026-01-29', '2026-02-28', '[\"M\",\"T\",\"W\",\"Th\",\"F\",\"S\",\"Su\"]'),
(26, '156', 13, 3, '8', '2026-01-29 15:09:53', '2026-01-29 15:09:53', '2026-01-29', '2026-02-28', '[\"M\",\"T\",\"W\",\"Th\",\"F\",\"S\",\"Su\"]'),
(25, '156', 13, 21, '5', '2026-01-29 15:09:53', '2026-01-29 15:09:53', '2026-01-29', '2026-02-28', '[\"M\",\"T\",\"W\",\"Th\",\"F\",\"S\",\"Su\"]');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_assessment_answers`
--

CREATE TABLE `medical_assessment_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_assessment_questions`
--

CREATE TABLE `medical_assessment_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assessment_type` varchar(255) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `type` enum('input','textarea','selection') DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  `answer_options` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_assessment_questions`
--

INSERT INTO `medical_assessment_questions` (`id`, `assessment_type`, `question`, `type`, `is_required`, `answer_options`, `created_at`, `updated_at`) VALUES
(1, 'Assessment', 'jhhkjkj', 'input', 0, NULL, '2025-08-26 10:27:46', '2025-08-26 10:28:22'),
(2, 'Medical', 'jiejiej', 'selection', 1, 'dfdsfsdf', '2025-08-26 15:39:26', '2025-08-26 15:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_18_061502_create_personal_access_tokens_table', 2),
(5, '2025_05_08_055743_create_plans_table', 3),
(6, '2025_05_08_090909_add_username_col_to_users_table', 4),
(7, '2025_05_08_093027_add_cnic_name_to_users_table', 5),
(8, '2025_05_09_053454_add_otp_to_users_table', 6),
(10, '2025_05_12_054818_add_video_file_to_exercises_table', 8),
(11, '2025_05_12_061251_add_video_time_to_exercises_table', 9),
(13, '2025_05_07_053455_create_exercise_categories_table', 11),
(14, '2025_05_07_053807_create_exercises_table', 12),
(15, '2025_05_07_055123_create_goals_table', 13),
(16, '2025_05_07_064322_create_plan_questions_table', 14),
(17, '2025_05_07_085502_create_plan_answers_table', 15),
(19, '2025_05_19_064621_create_competition_videos_table', 15),
(20, '2025_05_19_093248_create_competition_users', 15),
(21, '2025_05_19_093624_create_competition_results', 15),
(22, '2025_05_12_053026_add_tag_col_to_exercise_categories_table', 16),
(23, '2025_05_27_062956_create_competition_appeals_table', 17),
(24, '2025_05_28_110046_add_genz_to_competitions_table', 18),
(25, '2025_07_03_055836_create_rules_of_countings_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE `organisations` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisations`
--

INSERT INTO `organisations` (`id`, `type`, `name`, `created_at`, `updated_at`) VALUES
(5, 10, 'Aitchison', '2025-11-19 00:53:56', '2025-11-19 00:53:56'),
(6, 10, 'Beaconhouse', '2025-11-19 00:54:13', '2025-11-19 00:54:13'),
(7, 10, 'Dar-E-Arqam', '2025-11-19 00:54:38', '2025-11-19 00:54:38'),
(8, 11, 'Lahore', '2025-11-19 00:55:02', '2025-11-19 00:55:02'),
(9, 11, 'Kinnard', '2025-11-19 00:55:14', '2025-11-19 00:55:14'),
(10, 9, 'Allied', '2025-11-19 00:55:27', '2025-11-19 00:55:27'),
(11, 10, 'Roots', '2025-11-20 11:43:28', '2025-11-20 11:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `organisation_types`
--

CREATE TABLE `organisation_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisation_types`
--

INSERT INTO `organisation_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(9, 'Bank', '2025-11-19 00:52:09', '2025-11-19 00:52:09'),
(10, 'School', '2025-11-19 00:52:28', '2025-11-19 00:52:28'),
(11, 'College (Women)', '2025-11-19 00:52:45', '2025-11-19 00:52:45');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(188, 'App\\Models\\User', 156, 'sanctum', 'a69d21b91496488394d45b338d7914d37a1bd48a9dbd722cf7bb7129d90b1400', '[\"*\"]', NULL, NULL, '2026-01-29 15:06:08', '2026-01-29 15:06:08'),
(189, 'App\\Models\\User', 156, 'auth:sanctum', '94e29eb22b4edbd02994fc99cd76d48e662b55fbd7b677d51a89215e695e0d8f', '[\"*\"]', '2026-01-29 15:29:25', NULL, '2026-01-29 15:22:51', '2026-01-29 15:29:25'),
(190, 'App\\Models\\User', 157, 'auth:sanctum', '671e162bf71036de8988b5935459330cd3206e0e8a0b6a659d5bf1bf204a9982', '[\"*\"]', '2026-01-30 10:09:54', NULL, '2026-01-29 16:19:35', '2026-01-30 10:09:54'),
(191, 'App\\Models\\User', 158, 'sanctum', 'e218d40ef3bb6e30666c96eb57e9230bee6460647a774f05aa383d8f50ac704a', '[\"*\"]', '2026-01-29 23:09:29', NULL, '2026-01-29 22:59:31', '2026-01-29 23:09:29'),
(192, 'App\\Models\\User', 158, 'sanctum', 'ca5a42e3e6af12634ead6fefcd2dd8745436eb1de7b693d7d42114d648b4ddc7', '[\"*\"]', NULL, NULL, '2026-01-29 22:59:42', '2026-01-29 22:59:42'),
(193, 'App\\Models\\User', 156, 'auth:sanctum', 'c585250783adcfb15e94628939bd1f111894c1b4d67892aeae4a52f8d0f6301a', '[\"*\"]', '2026-01-30 12:27:26', NULL, '2026-01-30 10:27:39', '2026-01-30 12:27:26'),
(194, 'App\\Models\\User', 158, 'auth:sanctum', '81c20bc48963a5b96cfcdb6302539e2a123b23bb2c04a6744ddbbefdf5642511', '[\"*\"]', NULL, NULL, '2026-02-01 01:11:03', '2026-02-01 01:11:03'),
(195, 'App\\Models\\User', 158, 'auth:sanctum', '1f279a89cd896851b5e05566b87f06e06784372113f05d274c74d0f07be946ea', '[\"*\"]', NULL, NULL, '2026-02-01 01:11:24', '2026-02-01 01:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `physical_assessments`
--

CREATE TABLE `physical_assessments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `height_cm` float DEFAULT NULL,
  `weight_kg` float DEFAULT NULL,
  `bmi` float DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `body_shape` varchar(255) DEFAULT NULL,
  `required_body_shape` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exercise_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physical_assessments`
--

INSERT INTO `physical_assessments` (`id`, `user_id`, `height_cm`, `weight_kg`, `bmi`, `gender`, `body_shape`, `required_body_shape`, `created_at`, `updated_at`, `exercise_type`) VALUES
(76, 156, 170, 57, 19.7, 'Male', 'Slim', 'Muscular', '2026-01-29 15:08:46', '2026-01-29 15:08:46', 'Expert'),
(77, 158, 182, 85, 25.7, 'Male', 'Slim', 'Athletic', '2026-01-29 23:03:55', '2026-01-29 23:03:55', 'Expert');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `duration` varchar(255) NOT NULL DEFAULT 'monthly',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `price`, `description`, `features`, `duration`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ferris Logan', 797.00, 'Ullam unde irure cul', '\"[\\\"Et veritatis quod ni\\\",\\\"okay\\\",\\\"helo\\\"]\"', 'monthly', 'active', '2025-05-08 11:31:18', '2025-05-08 11:31:18'),
(2, 'Griffith Beard', 563.00, 'Consectetur distinct', '\"[\\\"Reiciendis tempor po\\\",\\\"second\\\",\\\"third\\\"]\"', 'yearly', 'active', '2025-05-08 11:31:32', '2025-05-08 11:31:32'),
(3, 'Basic', 0.00, 'description for basic', '\"[\\\"basic\\\",\\\"plan\\\",\\\"okay\\\"]\"', 'monthly', 'active', '2025-05-08 11:32:02', '2025-05-08 11:32:02'),
(4, 'cardio plan', 600.00, 'dsfdsf', '[\"new gsdg\"]', 'monthly', 'active', '2025-08-26 10:11:11', '2025-08-26 10:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `plan_answers`
--

CREATE TABLE `plan_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `plan_question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_answers`
--

INSERT INTO `plan_answers` (`id`, `user_id`, `plan_question_id`, `answer`, `created_at`, `updated_at`) VALUES
(1, '33', 1, 'fhfbdfbfbfbcx', '2025-09-05 14:54:01', '2025-09-05 14:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `plan_questions`
--

CREATE TABLE `plan_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `type` enum('input','textarea','selection') NOT NULL DEFAULT 'input',
  `is_required` enum('0','1') NOT NULL DEFAULT '0',
  `answer_options` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_questions`
--

INSERT INTO `plan_questions` (`id`, `question`, `type`, `is_required`, `answer_options`, `created_at`, `updated_at`) VALUES
(1, 'jiejiej', 'textarea', '1', NULL, '2025-08-26 15:40:11', '2025-08-26 15:40:11'),
(2, 'fdjgkljdf', 'input', '0', NULL, '2025-08-26 15:40:28', '2025-08-26 15:40:42');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `created_at`, `updated_at`, `name`, `country_id`) VALUES
(1, NULL, NULL, 'Punjab', 166),
(2, NULL, NULL, 'Sindh', 166),
(3, NULL, NULL, 'Khyber Pakhtunkhwa', 166),
(4, NULL, NULL, 'Balochistan', 166),
(5, NULL, NULL, 'Islamabad', 166),
(6, NULL, NULL, 'Azad Jammu & Kashmir', 166),
(7, NULL, NULL, 'Gilgit-Baltistan', 166);

-- --------------------------------------------------------

--
-- Table structure for table `rules_of_countings`
--

CREATE TABLE `rules_of_countings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `competition_id` bigint(20) UNSIGNED NOT NULL,
  `custom_exercise_name` varchar(255) NOT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `video_file` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ptJ6OUjWyJx8UOs9FR6uigjXAiryJghHS2A1F8Hw', 1, '154.192.19.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMGNiTkxpWXZmcWJWREFxSDBDT3ZRc2lkcmxpQTZhWEM5cVhTc241YiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1NDoiaHR0cHM6Ly9tYXhmaXQuY29vbGVhdHMuY29vbC9jb21wZXRpdGlvbi11c2Vycy80Mi9lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770101561),
('W5yYAjtPxgJMLMF439DnnuwwweBI7qI4BNJRYDS6', 1, '202.142.170.188', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2.1 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoib2hTNXdJdnhRdVZJU1p3eFFmSW81eExUM0xLREJLaDVYSWliRllPcCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwczovL21heGZpdC5jb29sZWF0cy5jb29sL2V4ZXJjaXNlLWFzc2Vzc21lbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1770099734);

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `genz` enum('fatherfits','motherfits') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sets`
--

INSERT INTO `sets` (`id`, `name`, `genz`, `created_at`, `updated_at`) VALUES
(12, 'Berk Schneider', 'motherfits', '2026-01-12 13:29:28', '2026-01-12 13:29:28'),
(13, 'Armand King', 'fatherfits', '2026-01-12 13:29:42', '2026-01-12 13:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `set_exercises`
--

CREATE TABLE `set_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `set_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sequence` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `set_exercises`
--

INSERT INTO `set_exercises` (`id`, `set_id`, `exercise_id`, `created_at`, `updated_at`, `sequence`) VALUES
(82, 12, 4, NULL, NULL, 1),
(83, 12, 10, NULL, NULL, 2),
(84, 12, 19, NULL, NULL, 3),
(85, 12, 8, NULL, NULL, 4),
(86, 13, 8, NULL, NULL, 4),
(87, 13, 3, NULL, NULL, 2),
(88, 13, 28, NULL, NULL, 3),
(89, 13, 21, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans_features`
--

CREATE TABLE `subscription_plans_features` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` text DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `terms_conditions` tinyint(1) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `cnic` bigint(20) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `organisation_type` int(11) DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `class` text DEFAULT NULL,
  `hobbies` text DEFAULT NULL,
  `sports_played` text DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_email` varchar(255) DEFAULT NULL,
  `guardian_number` varchar(255) DEFAULT NULL,
  `guardian_cnic` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state_province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `cnic_name` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  `profile_updated_for_father_fit` tinyint(1) DEFAULT 0,
  `is_age_changed` tinyint(1) DEFAULT 0,
  `assessment` tinyint(1) NOT NULL DEFAULT 0,
  `goal_setting` tinyint(1) DEFAULT 0,
  `profile_step` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `branch_name`, `image`, `username`, `email`, `number`, `password`, `remember_token`, `role`, `terms_conditions`, `name`, `cnic`, `dob`, `gender`, `organisation_type`, `organisation_id`, `class`, `hobbies`, `sports_played`, `guardian_name`, `guardian_email`, `guardian_number`, `guardian_cnic`, `country`, `state_province`, `city`, `created_at`, `updated_at`, `cnic_name`, `otp`, `otp_expires_at`, `is_verified`, `profile_updated_for_father_fit`, `is_age_changed`, `assessment`, `goal_setting`, `profile_step`) VALUES
(1, NULL, NULL, 'profiles/mT1nGBJgKitTzgYA3YR0R1wOq1rgmNd0KJmUoUDh.jpg', NULL, 'admin@gmail.com', '+923415429328', '$2y$12$Nhkr9EFaSoV8PF2Vt1Ii.eCs/ZXwelefw.wg4DIfflospu5UuBlNW', '3f39YvSwr6itS25OYKTFTnb0GtvV3wNmLM6Jt3REVUMwIG526RD3oOLJR4rr', 'admin', NULL, 'admin', NULL, '1988-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-08 11:27:36', '2025-08-26 15:11:38', NULL, NULL, NULL, 0, 0, 0, 0, 0, '0'),
(156, 1, NULL, 'uploads/profile_images/1769681160.jpg', 'usamaqadeer', 'usamaqadeer89@gmail.com', '+923335123730', '$2y$12$aBjgQlO1iIHYwltSJXuQ7OaOvaqv0soV1HP57UNRuw0gMZrzbcwjW', NULL, 'user', 1, 'Muhammad Ali Malik', 5160221223985, '1995-11-10', NULL, 9, 10, '12', 'stamps collection', 'cricket', 'Shah Ahad', 'usamaqadeer02@gmail.com', '+923127847021', '1620293251977', '166', '1', '3', '2026-01-29 10:28:26', '2026-01-29 15:12:49', NULL, NULL, NULL, 1, 0, 0, 1, 1, '5'),
(157, 1, NULL, 'uploads/profile_images/1769681160.jpg', 'alikhan', 'alikhan@gmail.com', '+923335123731', '$2y$12$aBjgQlO1iIHYwltSJXuQ7OaOvaqv0soV1HP57UNRuw0gMZrzbcwjW', NULL, 'user', 1, 'Ali Malik', 5160221223987, '1995-11-10', 'Male', 9, 10, '12', 'stamps collection', 'cricket', 'Shah Ahad', 'usamaqadeer02@gmail.com', '+923127847022', '1620293251979', '166', '1', '3', '2026-01-29 11:35:23', '2026-01-29 16:35:23', NULL, NULL, NULL, 1, 0, 0, 1, 1, '5');

-- --------------------------------------------------------

--
-- Table structure for table `user_exercise_assessment`
--

CREATE TABLE `user_exercise_assessment` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_id` int(10) UNSIGNED NOT NULL,
  `value` int(11) DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_exercise_assessment`
--

INSERT INTO `user_exercise_assessment` (`id`, `user_id`, `exercise_id`, `value`, `created_at`, `updated_at`) VALUES
(1526, 156, 21, 4, '2026-01-29 15:09:10', '2026-01-29 15:09:10'),
(1527, 156, 3, 7, '2026-01-29 15:09:10', '2026-01-29 15:09:10'),
(1528, 156, 28, 4, '2026-01-29 15:09:10', '2026-01-29 15:09:10'),
(1529, 156, 8, 6, '2026-01-29 15:09:10', '2026-01-29 15:09:10'),
(1530, 157, 21, 6, '2026-01-29 16:32:25', '2026-01-29 16:32:25'),
(1531, 157, 3, 1, '2026-01-29 16:32:25', '2026-01-29 16:32:25'),
(1532, 157, 28, 3, '2026-01-29 16:32:25', '2026-01-29 16:32:25'),
(1533, 157, 8, 4, '2026-01-29 16:32:25', '2026-01-29 16:32:25'),
(1534, 158, 21, 3, '2026-01-29 23:05:37', '2026-01-29 23:05:37'),
(1535, 158, 3, 9, '2026-01-29 23:05:37', '2026-01-29 23:05:37'),
(1536, 158, 28, 6, '2026-01-29 23:05:37', '2026-01-29 23:05:37'),
(1537, 158, 8, 64, '2026-01-29 23:05:37', '2026-01-29 23:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `city_id`, `created_at`, `updated_at`) VALUES
(1, 'Natalie Shelton', 3, '2026-01-27 13:51:23', '2026-01-27 13:51:23'),
(2, 'Rhea Valenzuela', 1, '2026-01-27 15:43:16', '2026-01-27 15:43:16'),
(3, 'Test', 3, '2026-01-27 16:03:08', '2026-01-27 16:03:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `branches_country_fk` (`country_id`),
  ADD KEY `branches_city_fk` (`city_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `challenger_id` (`challenger_id`),
  ADD KEY `challenge_to_id` (`challenge_to_id`);

--
-- Indexes for table `challenge_exercises`
--
ALTER TABLE `challenge_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `challenge_id` (`challenge_id`),
  ADD KEY `set_id` (`set_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `coaches_country_fk` (`country_id`),
  ADD KEY `coaches_city_fk` (`city_id`);

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_type` (`org_type`),
  ADD KEY `org` (`org`);

--
-- Indexes for table `competition_appeals`
--
ALTER TABLE `competition_appeals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competition_appeals_competition_video_id_foreign` (`competition_video_id`);

--
-- Indexes for table `competition_details`
--
ALTER TABLE `competition_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `competition_exercises`
--
ALTER TABLE `competition_exercises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_competition_exercise` (`competition_id`,`exercise_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `competition_results`
--
ALTER TABLE `competition_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_result` (`competition_user_id`,`exercise_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `competition_result_videos`
--
ALTER TABLE `competition_result_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competition_result_videos_competition_result_id_foreign` (`competition_result_id`);

--
-- Indexes for table `competition_users`
--
ALTER TABLE `competition_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_competition` (`competition_detail_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `competition_user_totals`
--
ALTER TABLE `competition_user_totals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competition_user_id` (`competition_user_id`);

--
-- Indexes for table `competition_videos`
--
ALTER TABLE `competition_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competition_videos_competition_id_foreign` (`competition_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_assessments`
--
ALTER TABLE `daily_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_daily_assessments_user` (`user_id`),
  ADD KEY `fk_daily_assessments_set` (`set_id`),
  ADD KEY `fk_daily_assessments_exercise` (`exercise_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercise_categories`
--
ALTER TABLE `exercise_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goals_exercise_id_foreign` (`exercise_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_assessment_answers`
--
ALTER TABLE `medical_assessment_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_question_id` (`question_id`);

--
-- Indexes for table `medical_assessment_questions`
--
ALTER TABLE `medical_assessment_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_type` (`type`);

--
-- Indexes for table `organisation_types`
--
ALTER TABLE `organisation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `physical_assessments`
--
ALTER TABLE `physical_assessments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_answers`
--
ALTER TABLE `plan_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_answers_plan_question_id_foreign` (`plan_question_id`);

--
-- Indexes for table `plan_questions`
--
ALTER TABLE `plan_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rules_of_countings`
--
ALTER TABLE `rules_of_countings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `set_exercises`
--
ALTER TABLE `set_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_exercises_set_id_foreign` (`set_id`),
  ADD KEY `set_exercises_exercise_id_foreign` (`exercise_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans_features`
--
ALTER TABLE `subscription_plans_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `fk_users_organisation` (`organisation_id`),
  ADD KEY `fk_users_organisation_type` (`organisation_type`),
  ADD KEY `fk_users_branch_id` (`branch_id`);

--
-- Indexes for table `user_exercise_assessment`
--
ALTER TABLE `user_exercise_assessment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `challenge_exercises`
--
ALTER TABLE `challenge_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=464;

--
-- AUTO_INCREMENT for table `coaches`
--
ALTER TABLE `coaches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `competition_appeals`
--
ALTER TABLE `competition_appeals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `competition_details`
--
ALTER TABLE `competition_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `competition_exercises`
--
ALTER TABLE `competition_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=468;

--
-- AUTO_INCREMENT for table `competition_results`
--
ALTER TABLE `competition_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;

--
-- AUTO_INCREMENT for table `competition_result_videos`
--
ALTER TABLE `competition_result_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=405;

--
-- AUTO_INCREMENT for table `competition_users`
--
ALTER TABLE `competition_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `competition_user_totals`
--
ALTER TABLE `competition_user_totals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `competition_videos`
--
ALTER TABLE `competition_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `daily_assessments`
--
ALTER TABLE `daily_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `exercise_categories`
--
ALTER TABLE `exercise_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `medical_assessment_answers`
--
ALTER TABLE `medical_assessment_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `medical_assessment_questions`
--
ALTER TABLE `medical_assessment_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `organisation_types`
--
ALTER TABLE `organisation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `physical_assessments`
--
ALTER TABLE `physical_assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plan_answers`
--
ALTER TABLE `plan_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plan_questions`
--
ALTER TABLE `plan_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rules_of_countings`
--
ALTER TABLE `rules_of_countings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `set_exercises`
--
ALTER TABLE `set_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans_features`
--
ALTER TABLE `subscription_plans_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `user_exercise_assessment`
--
ALTER TABLE `user_exercise_assessment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1538;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_city_fk` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branches_country_fk` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coaches`
--
ALTER TABLE `coaches`
  ADD CONSTRAINT `coaches_city_fk` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coaches_country_fk` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `competitions`
--
ALTER TABLE `competitions`
  ADD CONSTRAINT `competitions_ibfk_1` FOREIGN KEY (`org_type`) REFERENCES `organisation_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `competitions_ibfk_2` FOREIGN KEY (`org`) REFERENCES `organisations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `competition_details`
--
ALTER TABLE `competition_details`
  ADD CONSTRAINT `competition_details_ibfk_1` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_exercises`
--
ALTER TABLE `competition_exercises`
  ADD CONSTRAINT `competition_exercises_ibfk_1` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competition_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_results`
--
ALTER TABLE `competition_results`
  ADD CONSTRAINT `competition_results_ibfk_1` FOREIGN KEY (`competition_user_id`) REFERENCES `competition_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competition_results_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_result_videos`
--
ALTER TABLE `competition_result_videos`
  ADD CONSTRAINT `competition_result_videos_competition_result_id_foreign` FOREIGN KEY (`competition_result_id`) REFERENCES `competition_results` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_users`
--
ALTER TABLE `competition_users`
  ADD CONSTRAINT `competition_users_ibfk_1` FOREIGN KEY (`competition_detail_id`) REFERENCES `competition_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competition_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_user_totals`
--
ALTER TABLE `competition_user_totals`
  ADD CONSTRAINT `competition_user_totals_ibfk_1` FOREIGN KEY (`competition_user_id`) REFERENCES `competition_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_assessment_answers`
--
ALTER TABLE `medical_assessment_answers`
  ADD CONSTRAINT `fk_question_id` FOREIGN KEY (`question_id`) REFERENCES `medical_assessment_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organisations`
--
ALTER TABLE `organisations`
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`type`) REFERENCES `organisation_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `set_exercises`
--
ALTER TABLE `set_exercises`
  ADD CONSTRAINT `set_exercises_exercise_id_foreign` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `set_exercises_set_id_foreign` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_organisation` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_users_organisation_type` FOREIGN KEY (`organisation_type`) REFERENCES `organisation_types` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
