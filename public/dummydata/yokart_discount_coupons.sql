-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:15 AM
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
-- Dumping data for table `yokart_discount_coupons`
--

INSERT INTO `yokart_discount_coupons` (`discountcpn_id`, `discountcpn_code`, `discountcpn_in_percent`, `discountcpn_type`, `discountcpn_total_uses`, `discountcpn_uses_per_user`, `discountcpn_maxamt_limit`, `discountcpn_minorderamt`, `discountcpn_amount`, `discountcpn_operator`, `discountcpn_for_guest`, `discountcpn_startdate`, `discountcpn_enddate`, `discountcpn_publish`, `discountcpn_created_by_id`, `discountcpn_updated_by_id`, `discountcpn_created_at`, `discountcpn_updated_at`) VALUES
(1, 'WATCH25', 1, 0, 25, 1, '500', '150', '25', 0, 0, '2020-08-26 06:30:00', '2025-11-19 06:30:00', 1, 0, 0, '2020-09-11 14:34:59', '2020-09-11 14:34:59'),
(2, '7XYX9DLUCV', 0, 0, 50, 1, '50', '100', '20', 0, 0, '2020-09-10 06:30:00', '2025-10-24 06:30:00', 1, 0, 1, '2020-09-11 13:39:51', '2021-03-31 08:54:27'),
(4, '1K5KP14X92', 0, 0, 1000, 1, '30', '50', '10', 0, 1, '2020-09-16 06:30:00', '2024-09-17 06:30:00', 1, 0, 1, '2020-09-11 14:33:29', '2021-03-31 08:54:21'),
(5, 'AE1THAM4Q7', 1, 0, 999, 1, '100', '500', '20', 0, 1, '2020-09-10 06:30:00', '2023-09-17 06:30:00', 1, 0, 1, '2020-09-11 14:40:58', '2021-03-31 08:54:05'),
(6, 'C49NGHUUPV', 1, 0, 1, 15, '50', '150', '15', 0, 1, '2020-09-10 06:30:00', '2025-09-15 06:30:00', 1, 0, 0, '2020-09-11 14:43:14', '2020-09-11 14:43:14'),
(9, 'HU1H0RKG9A', 0, 0, 100, 1, '55', '200', '20', 0, 1, '2020-09-10 06:30:00', '2025-11-09 06:30:00', 1, 0, 1, '2020-09-11 14:52:52', '2021-03-31 08:54:03'),
(10, '8GMA71WJ1P', 1, 0, 199, 1, '25', '99', '33', 0, 1, '2020-09-10 06:30:00', '2023-03-07 06:30:00', 1, 0, 1, '2020-09-11 14:57:43', '2021-03-31 08:53:33'),
(11, 'RLMF0YI186', 0, 0, 150, 10, '20', '100', '15', 0, 1, '2020-09-10 06:30:00', '2024-05-14 06:30:00', 1, 0, 1, '2021-03-31 10:47:15', '2021-03-31 10:47:15'),
(12, 'U7OPR2BH8A', 1, 0, 699, 1, '35', '99', '20', 0, 1, '2020-09-10 06:30:00', '2024-02-05 06:30:00', 1, 0, 1, '2020-09-11 15:02:51', '2021-03-31 08:54:23'),
(16, 'TRIBENEW', 1, 0, 5000, 100, '500', '50', '15', 0, 0, '2021-03-30 18:30:00', '2033-03-30 18:30:00', 1, 1, 1, '2021-03-31 10:57:19', '2021-03-31 10:57:19');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
