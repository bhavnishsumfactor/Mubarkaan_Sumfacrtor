-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2021 at 02:59 PM
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
-- Dumping data for table `yokart_shipping_rates`
--

INSERT INTO `yokart_shipping_rates` (`srate_id`, `srate_spzone_id`, `srate_type`, `srate_name`, `srate_cost`, `srate_min_value`, `srate_max_value`) VALUES
(1, 1, 0, 'Standard Shipping', '0', 'NULL', 'NULL'),
(2, 1, 0, 'Priority Shipping', '10', 'NULL', 'NULL'),
(3, 2, 0, 'Standard Shipping', '0', 'NULL', 'NULL'),
(4, 3, 0, 'Standard Shipping', '15', 'NULL', 'NULL'),
(5, 4, 0, 'Standard Shipping', '20', 'NULL', 'NULL'),
(6, 5, 0, 'Standard Shipping', '10', 'NULL', 'NULL'),
(7, 5, 2, 'Priority Shipping', '30', '0', '50');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
