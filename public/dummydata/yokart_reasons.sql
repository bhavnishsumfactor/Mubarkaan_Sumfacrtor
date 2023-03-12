-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2021 at 02:54 PM
-- Server version: 5.7.33-0ubuntu0.18.04.1
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restore-setup`
--

--
-- Dumping data for table `yokart_reasons`
--

INSERT INTO `yokart_reasons` (`reason_id`, `reason_type`, `reason_title`, `reason_publish`) VALUES
(1, 2, 'I placed a duplicate order', 1),
(2, 2, 'I ordered the wrong product(s)', 1),
(3, 2, 'The order was not shipped on time', 1),
(4, 2, 'The product(s) i want are no longer in stock', 1),
(5, 2, 'I am not able to contact the seller', 1),
(6, 1, 'Wrong product delivered', 1),
(7, 1, 'Not happy with the product', 1),
(8, 1, 'I changed my mind', 1),
(9, 2, 'I found a better deal at another website', 1),
(10, 1, 'I found a better deal at another website', 1),
(11, 1, 'The product was damaged', 1),
(12, 1, 'The package was opened', 1),
(13, 1, 'The package was missing product(s)', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
