-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:38 AM
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
-- Dumping data for table `yokart_product_categories`
--

INSERT INTO `yokart_product_categories` (`cat_id`, `cat_name`, `cat_parent`, `cat_publish`, `cat_tax_code`, `cat_display_order`, `cat_created_by_id`, `cat_updated_by_id`, `cat_created_at`, `cat_updated_at`) VALUES
(1, 'Clothing', 0, 1, 'NULL', 0, 0, 0, '0000-00-00 00:00:00', '2021-03-31 06:27:58'),
(4, 'Dresses', 1, 1, 'NULL', 1, 0, 1, '0000-00-00 00:00:00', '2021-03-31 05:17:25'),
(5, 'Shorts & Skirts', 1, 1, 'NULL', 0, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:26:51'),
(7, 'Tops & Sweaters', 1, 1, 'NULL', 5, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:16:08'),
(9, 'Jeans, Pants & Leggings', 1, 1, 'NULL', 7, 0, 0, '0000-00-00 00:00:00', '2021-03-25 13:02:00'),
(10, 'Coats, Jackets & Blazers', 1, 1, 'NULL', 8, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:16:44'),
(13, 'Shoes', 0, 1, 'NULL', 3, 0, 0, '0000-00-00 00:00:00', '2021-03-31 06:28:23'),
(14, 'Sports Shoes & Sneakers', 13, 1, 'NULL', 1, 0, 0, '0000-00-00 00:00:00', '2021-03-25 13:15:02'),
(16, 'Heels & Pumps', 13, 1, 'NULL', 3, 0, 0, '0000-00-00 00:00:00', '2021-03-25 08:30:18'),
(17, 'Sandals & Flipflops', 13, 1, 'NULL', 4, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:34:58'),
(18, 'Mules, Slippers & Slides', 13, 1, 'NULL', 5, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:30:24'),
(21, 'Handbags & Accessories', 0, 1, 'NULL', 5, 0, 0, '0000-00-00 00:00:00', '2021-03-31 06:30:27'),
(22, 'Handbags & Wallets', 21, 1, 'NULL', 1, 0, 0, '0000-00-00 00:00:00', '2021-03-25 08:30:55'),
(23, 'Tights, Socks, & Hosiery', 21, 1, 'NULL', 2, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:38:08'),
(24, 'Sunglasses', 21, 1, 'NULL', 3, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:20:31'),
(25, 'Jewelry & Watches', 0, 1, 'NULL', 6, 0, 0, '0000-00-00 00:00:00', '2021-03-31 06:31:25'),
(26, 'Watches', 25, 1, 'NULL', 1, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:21:11'),
(27, 'Rings', 25, 1, 'NULL', 2, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:19:13'),
(28, 'Necklaces', 25, 1, 'NULL', 3, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:40:15'),
(31, 'Earrings', 25, 1, 'NULL', 6, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:18:00'),
(32, 'Beauty', 0, 1, 'NULL', 4, 0, 0, '0000-00-00 00:00:00', '2021-03-31 06:29:25'),
(33, 'Hair Care', 32, 1, 'NULL', 1, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:37:27'),
(34, 'Makeup', 32, 1, 'NULL', 3, 0, 1, '0000-00-00 00:00:00', '2021-03-31 05:18:43'),
(35, 'Perfume', 32, 1, 'NULL', 2, 0, 0, '0000-00-00 00:00:00', '2021-03-25 08:28:06'),
(36, 'Skin Care', 32, 1, 'NULL', 4, 0, 0, '0000-00-00 00:00:00', '2021-03-31 05:21:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
