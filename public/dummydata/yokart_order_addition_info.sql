-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:01 AM
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
-- Dumping data for table `yokart_order_addition_info`
--

INSERT INTO `yokart_order_addition_info` (`oainfo_order_id`, `oainfo_received_confirmation`, `oainfo_courier_name`, `oainfo_cat_tax_code`, `oainfo_tracking_id`) VALUES
(10005, NULL, 'Fedex', NULL, 'JGGFF35468779'),
(10008, NULL, 'MArex', NULL, 'FVFFDG1548789'),
(10009, NULL, 'REDWALL', NULL, 'FHTFY153867'),
(10019, NULL, 'MAREX', NULL, 'GFF12455878'),
(10018, NULL, 'DHL', NULL, '435436532424'),
(10001, NULL, 'UPS', NULL, '543534546664'),
(10003, NULL, 'USPS', NULL, '34543892189219');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
