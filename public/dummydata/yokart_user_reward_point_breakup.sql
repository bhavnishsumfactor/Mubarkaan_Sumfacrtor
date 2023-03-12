-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:13 AM
-- Server version: 10.3.28-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `singlevendor_rdemo`
--

--
-- Dumping data for table `yokart_user_reward_point_breakup`
--

INSERT INTO `yokart_user_reward_point_breakup` (`urpbreakup_id`, `urpbreakup_urp_id`, `urpbreakup_points`, `urpbreakup_expiry`, `urpbreakup_used`, `urpbreakup_referral_user_id`, `urpbreakup_used_order_id`, `urpbreakup_used_date`) VALUES
(1, 1, 250, '2021-04-04 07:42:24', 1, 0, 10025, '2021-03-31 13:13:42'),
(2, 2, 700, '2021-04-04 07:44:48', 0, 0, 0, NULL),
(3, 3, 260, '2021-04-04 08:50:38', 0, 0, 0, NULL),
(4, 4, 50, '2021-04-04 10:04:19', 1, 0, 10006, '2021-03-31 10:36:31'),
(5, 4, 260, '2021-04-04 10:04:19', 0, 0, 0, NULL),
(6, 6, 290, '2021-04-04 10:38:32', 0, 0, 0, NULL),
(7, 7, 4700, '2021-04-04 10:39:54', 0, 0, 0, NULL),
(8, 9, 290, '2021-04-04 10:55:15', 0, 0, 0, NULL),
(9, 8, 100, '2021-04-30 16:26:11', 0, 8, 0, NULL),
(10, 10, 200, '2021-04-04 10:57:42', 1, 0, 10013, '2021-03-31 11:00:09'),
(11, 11, 100, '2021-04-04 10:59:08', 0, 0, 0, NULL),
(12, 10, 5800, '2022-02-22 10:57:42', 0, 0, 0, NULL),
(13, 12, 200, '2021-04-30 16:30:54', 0, 0, 0, NULL),
(14, 14, 100, '2023-09-17 13:01:51', 1, 0, 10022, '2021-03-31 13:08:49'),
(15, 15, 155, '2023-09-17 13:02:14', 0, 0, 0, NULL),
(16, 16, 370, '2023-09-17 13:05:38', 0, 0, 0, NULL),
(17, 17, 35, '2023-09-17 13:07:08', 0, 0, 0, NULL),
(18, 18, 1175, '2023-09-17 13:07:22', 0, 0, 0, NULL),
(19, 14, 5500, '2023-09-17 13:01:51', 0, 0, 0, NULL),
(20, 20, 45, '2023-09-17 13:11:05', 0, 0, 0, NULL),
(21, 1, 650, '2021-04-04 07:42:24', 0, 0, 0, NULL),
(22, 22, 80, '2023-09-17 13:15:29', 0, 0, 0, NULL),
(23, 23, 440, '2023-09-17 13:17:16', 0, 0, 0, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
