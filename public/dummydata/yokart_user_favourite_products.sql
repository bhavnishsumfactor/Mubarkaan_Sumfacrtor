-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:20 AM
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
-- Dumping data for table `yokart_user_favourite_products`
--

INSERT INTO `yokart_user_favourite_products` (`ufp_id`, `ufp_user_id`, `ufp_prod_id`, `ufp_pov_code`, `ufp_created_at`) VALUES
(1, 11, 50, '50|11|206|', '0000-00-00 00:00:00'),
(2, 11, 317, '317|74|', '0000-00-00 00:00:00'),
(3, 11, 314, '314|61|', '0000-00-00 00:00:00'),
(4, 11, 301, NULL, '0000-00-00 00:00:00'),
(5, 11, 17, '17|11|', '0000-00-00 00:00:00'),
(6, 11, 19, '19|11|', '0000-00-00 00:00:00'),
(7, 11, 47, '47|11|203|', '0000-00-00 00:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
