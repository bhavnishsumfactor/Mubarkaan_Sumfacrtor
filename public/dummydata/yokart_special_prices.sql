-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:14 AM
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
-- Dumping data for table `yokart_special_prices`
--

INSERT INTO `yokart_special_prices` (`splprice_id`, `splprice_name`, `splprice_type`, `splprice_amount`, `splprice_startdate`, `splprice_enddate`, `splprice_include`, `splprice_publish`, `splprice_created_by_id`, `splprice_updated_by_id`, `splprice_created_at`, `splprice_updated_at`) VALUES
(1, 'Shoes Special Dicount', 2, '10', '2020-08-10 00:00:00', '2021-07-31 00:00:00', 2, 1, 0, 0, '2020-08-27 05:19:04', '2020-08-27 05:19:04'),
(2, 'Mega Discount Season on Clothing', 2, '25', '2020-08-28 00:00:00', '2022-03-25 00:00:00', 2, 1, 0, 0, '2020-08-27 05:32:08', '2020-08-27 05:32:08'),
(3, 'Flat Off', 1, '15', '2020-08-28 00:00:00', '2022-08-12 00:00:00', 3, 1, 0, 0, '2020-08-27 05:22:00', '2020-08-27 05:22:00'),
(4, 'Limited Period Discount Offer', 2, '10', '2020-08-29 00:00:00', '2021-11-23 00:00:00', 2, 1, 0, 0, '2020-08-27 05:23:24', '2020-08-27 05:23:24'),
(5, 'Off on Cosmetics', 1, '5', '2020-08-27 00:00:00', '2021-04-19 00:00:00', 2, 1, 0, 0, '2020-08-27 05:24:07', '2020-08-27 05:24:07'),
(6, 'Luxury Discount on Jewellery & Watches', 2, '20', '2020-08-28 00:00:00', '2020-11-04 00:00:00', 2, 1, 0, 0, '2020-08-27 05:24:42', '2020-08-27 05:24:42'),
(7, 'Seasonal Offer on Accesories', 2, '40', '2020-08-27 00:00:00', '2021-09-16 00:00:00', 2, 1, 0, 0, '2020-08-27 05:31:43', '2020-08-27 05:31:43'),
(8, 'Heavy Discount on Footwear', 2, '20', '2020-08-27 00:00:00', '2021-05-07 00:00:00', 2, 1, 0, 1, '2020-08-27 05:31:28', '2020-09-16 12:13:26'),
(9, 'Super Sale on variety of items', 2, '30', '2020-08-28 00:00:00', '2022-02-25 00:00:00', 2, 1, 0, 0, '2020-08-27 05:33:08', '2020-08-27 05:33:08'),
(10, 'Flat off on Sneakers & Sports Shoes', 1, '35', '2020-08-27 00:00:00', '2024-09-18 00:00:00', 2, 1, 0, 0, '2020-08-27 05:34:07', '2020-08-27 05:34:07'),
(11, 'Winter Mega Offering', 2, '25', '2020-08-27 00:00:00', '2021-12-14 00:00:00', 2, 1, 0, 1, '2020-08-27 05:34:54', '2020-09-16 12:12:51'),
(12, 'Accessories Mega Sale', 2, '35', '2020-08-27 00:00:00', '2022-10-18 00:00:00', 2, 1, 0, 0, '2020-08-27 05:43:56', '2020-08-27 05:43:56'),
(13, 'Special Price, Limited Period', 1, '14', '2020-08-27 00:00:00', '2021-04-20 00:00:00', 2, 1, 0, 0, '2020-08-27 05:44:52', '2020-08-27 05:44:52'),
(14, 'Off on Ethnic Season & Jewellery', 2, '10', '2020-08-27 00:00:00', '2021-07-15 00:00:00', 2, 1, 0, 0, '2020-08-27 05:47:11', '2020-08-27 05:47:11'),
(15, 'Clothes Sale!', 1, '5', '2020-08-27 00:00:00', '2021-08-11 00:00:00', 2, 1, 0, 0, '2020-08-27 05:48:17', '2020-08-27 05:48:17');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
