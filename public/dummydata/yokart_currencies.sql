-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2021 at 12:46 PM
-- Server version: 5.7.32-0ubuntu0.18.04.1
-- PHP Version: 7.3.25-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rdemobackup`
--

--
-- Dumping data for table `yokart_currencies`
--

INSERT INTO `yokart_currencies` (`currency_id`, `currency_name`, `currency_code`, `currency_symbol`, `currency_position`, `currency_value`, `currency_publish`, `currency_default`, `currency_view_default`, `currency_updated_at`) VALUES
(1, 'United States Dollar', 'USD', '$', 0, '1.00000000', 1, 1, 1, '2020-06-01 10:44:39'),
(2, 'Indian Rupee', 'INR', '₹', 0, '1.00000000', 1, 0, 0, '2020-06-01 10:44:39'),
(5, 'Euro', 'EUR', '€', 0, '1.00000000', 1, 0, 0, '2020-06-01 10:44:39'),
(8, 'Australian Dollar', 'AUD', '$', 0, '1.00000000', 1, 0, 0, '2020-06-01 10:44:39'),
(11, 'UAE Dirham', 'AED', 't', 0, '1.00000000', 1, 0, 0, '2020-06-01 10:44:39'),
(12, 'British Pound', 'GBP', '£', 0, '0.73018000', 1, 0, 0, '2021-03-25 06:42:05'),
(13, 'Canadian Dollar', 'CAD', '$', 0, '1.25723500', 1, 0, 0, '2021-03-25 06:42:39');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
