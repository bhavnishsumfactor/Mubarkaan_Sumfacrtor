-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:06 AM
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
-- Dumping data for table `yokart_order_product_charges`
--

INSERT INTO `yokart_order_product_charges` (`opc_op_id`, `opc_type`, `opc_value`) VALUES
(1, 'tax', '7.999'),
(2, 'tax', '89.9'),
(2, 'rewardpoints', '900'),
(3, 'tax', '3.312'),
(3, 'rewardpoints', '40'),
(4, 'tax', '9.8'),
(4, 'rewardpoints', '100'),
(5, 'tax', '3.999'),
(5, 'rewardpoints', '40'),
(6, 'tax', '69.9'),
(6, 'rewardpoints', '530'),
(7, 'tax', '5.34'),
(7, 'rewardpoints', '60'),
(8, 'tax', '10'),
(8, 'rewardpoints', '200'),
(9, 'tax', '1'),
(10, 'tax', '2.15'),
(11, 'tax', '1.75'),
(11, 'rewardpoints', '40'),
(12, 'tax', '6.7'),
(12, 'rewardpoints', '270'),
(13, 'tax', '14.25'),
(13, 'rewardpoints', '290'),
(14, 'tax', '6.75'),
(14, 'rewardpoints', '140'),
(15, 'tax', '3.65'),
(15, 'rewardpoints', '150'),
(16, 'tax', '235'),
(16, 'rewardpoints', '4700'),
(17, 'tax', '5'),
(18, 'tax', '12.6'),
(18, 'rewardpoints', '130'),
(19, 'tax', '12.9'),
(19, 'rewardpoints', '130'),
(20, 'tax', '3.173'),
(20, 'rewardpoints', '40'),
(21, 'rewardpoints', '6000'),
(22, 'rewardpoints', '100'),
(23, 'rewardpoints', '430'),
(29, 'tax', '560'),
(29, 'rewardpoints', '5600'),
(30, 'tax', '18.6'),
(30, 'rewardpoints', '95'),
(31, 'tax', '2.8'),
(31, 'rewardpoints', '15'),
(32, 'tax', '8.999'),
(32, 'rewardpoints', '45'),
(33, 'tax', '6.5'),
(33, 'rewardpoints', '30'),
(34, 'tax', '79.5'),
(34, 'rewardpoints', '340'),
(35, 'tax', '3.5'),
(35, 'rewardpoints', '35'),
(36, 'tax', '235'),
(36, 'rewardpoints', '1175'),
(37, 'tax', '3.15'),
(38, 'tax', '0.9519'),
(39, 'tax', '4.4'),
(39, 'rewardpoints', '40'),
(40, 'tax', '6.9'),
(40, 'rewardpoints', '60'),
(41, 'tax', '3.8'),
(42, 'tax', '2.4995'),
(42, 'rewardpoints', '25'),
(43, 'tax', '1.9995'),
(43, 'rewardpoints', '20'),
(44, 'tax', '4.9'),
(44, 'rewardpoints', '25'),
(45, 'tax', '2.999'),
(45, 'rewardpoints', '15'),
(46, 'tax', '1.439'),
(46, 'rewardpoints', '10'),
(47, 'tax', '1.2'),
(47, 'rewardpoints', '10'),
(48, 'tax', '5.94'),
(48, 'rewardpoints', '60'),
(49, 'tax', '2.397'),
(49, 'rewardpoints', '15'),
(50, 'tax', '47.5'),
(50, 'rewardpoints', '240'),
(51, 'tax', '39.9'),
(51, 'rewardpoints', '200');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
