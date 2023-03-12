-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:12 AM
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
-- Dumping data for table `yokart_user_reward_points`
--

INSERT INTO `yokart_user_reward_points` (`urp_id`, `urp_user_id`, `urp_referral_user_id`, `urp_points`, `urp_comments`, `urp_type`, `urp_order_id`, `urp_date_added`, `urp_date_expiry`, `urp_used`) VALUES
(1, 11, 0, 900, 'Earned points via order #10001', 1, 10001, '2021-03-31 07:42:24', '2021-04-04 07:42:24', 0),
(2, 11, 0, 700, 'Earned points via order #10002', 1, 10002, '2021-03-31 07:44:48', '2021-04-04 07:44:48', 0),
(3, 11, 0, 260, 'Earned points via order #10003', 1, 10003, '2021-03-31 08:50:38', '2021-04-04 08:50:38', 0),
(4, 9, 0, 310, 'Earned points via order #10005', 1, 10005, '2021-03-31 10:04:19', '2021-04-04 10:04:19', 0),
(5, 9, 0, -50, 'Redeemed points on order #10006', 4, 10006, '2021-03-31 10:36:31', '0000-00-00 00:00:00', 1),
(6, 9, 0, 290, 'Earned points via order #10007', 1, 10007, '2021-03-31 10:38:32', '2021-04-04 10:38:32', 0),
(7, 9, 0, 4700, 'Earned points via order #10008', 1, 10008, '2021-03-31 10:39:54', '2021-04-04 10:39:54', 0),
(8, 11, 8, 100, 'Earned points via signup (sharjeel@dummyid.com)\r\n', 2, 0, '2021-03-31 16:23:06', '2021-03-31 16:23:06', 0),
(9, 11, 0, 290, 'Earned points via order #10010', 1, 10010, '2021-03-31 10:55:15', '2021-04-04 10:55:15', 0),
(10, 5, 0, 6000, 'Earned points via order #10011', 1, 10011, '2021-03-31 10:57:42', '2021-04-04 10:57:42', 0),
(11, 5, 0, 100, 'Earned points via order #10012', 1, 10012, '2021-03-31 10:59:08', '2021-04-04 10:59:08', 0),
(12, 11, 0, 200, 'Earned birthday points', 3, 0, '2021-03-31 16:30:07', '2023-02-28 16:30:07', 0),
(13, 5, 0, -200, 'Redeemed points on order #10013', 4, 10013, '2021-03-31 11:00:09', '0000-00-00 00:00:00', 1),
(14, 2, 0, 5600, 'Earned points via order #10016', 1, 10016, '2021-03-31 13:01:51', '2023-09-17 13:01:51', 0),
(15, 11, 0, 155, 'Earned points via order #10017', 1, 10017, '2021-03-31 13:02:14', '2023-09-17 13:02:14', 0),
(16, 11, 0, 370, 'Earned points via order #10018', 1, 10018, '2021-03-31 13:05:38', '2023-09-17 13:05:38', 0),
(17, 2, 0, 35, 'Earned points via order #10019', 1, 10019, '2021-03-31 13:07:08', '2023-09-17 13:07:08', 0),
(18, 11, 0, 1175, 'Earned points via order #10020', 1, 10020, '2021-03-31 13:07:22', '2023-09-17 13:07:22', 0),
(19, 2, 0, -100, 'Redeemed points on order #10022', 4, 10022, '2021-03-31 13:08:49', '0000-00-00 00:00:00', 1),
(20, 2, 0, 45, 'Earned points via order #10024', 1, 10024, '2021-03-31 13:11:05', '2023-09-17 13:11:05', 0),
(21, 11, 0, -250, 'Redeemed points on order #10025', 4, 10025, '2021-03-31 13:13:42', '0000-00-00 00:00:00', 1),
(22, 11, 0, 80, 'Earned points via order #10026', 1, 10026, '2021-03-31 13:15:29', '2023-09-17 13:15:29', 0),
(23, 11, 0, 440, 'Earned points via order #10027', 1, 10027, '2021-03-31 13:17:16', '2023-09-17 13:17:16', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
