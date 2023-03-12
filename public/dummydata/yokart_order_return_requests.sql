-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:10 AM
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
-- Dumping data for table `yokart_order_return_requests`
--

INSERT INTO `yokart_order_return_requests` (`orrequest_id`, `orrequest_user_id`, `orrequest_op_id`, `orrequest_order_id`, `orrequest_type`, `orrequest_qty`, `orrequest_date`, `orrequest_status`, `orrequest_is_shipping`, `orrequest_order_status`, `orrequest_omsg_id`, `orrequest_txn_gateway_transaction_id`, `orrequest_reason`, `orrequest_comment`) VALUES
(1, 5, 24, 10014, 2, 1, '2021-03-31 12:16:32', 0, 0, 1, 0, '', 'I placed a duplicate order', 'Placed duplicate order'),
(2, 5, 21, 10011, 2, 1, '2021-03-31 12:17:30', 3, 0, 1, 0, 'ch_1Ib1k2L1bMNoOfFv7hiQpsU3', 'I placed a duplicate order', NULL),
(3, 9, 16, 10008, 1, 1, '2021-03-31 12:22:09', 2, 0, 1, 0, 'ch_1Ib1SnL1bMNoOfFvjM7DqlMp', 'Wrong product delivered', 'Wrong item delivered'),
(4, 2, 42, 10024, 2, 1, '2021-03-31 13:11:34', 3, 0, 1, 0, 'ch_1Ib3p6L1bMNoOfFv2mi3Ase4', 'I placed a duplicate order', NULL),
(5, 2, 43, 10024, 2, 1, '2021-03-31 13:11:34', 2, 0, 1, 0, 'ch_1Ib3p6L1bMNoOfFv2mi3Ase4', 'I am not able to contact the seller', NULL),
(6, 2, 35, 10019, 1, 1, '2021-03-31 13:13:43', 0, 0, 1, 0, 'ch_1Ib3lHL1bMNoOfFvDguUK880', 'The package was opened', 'Package was opened and seems  not fresh'),
(7, 11, 8, 10003, 1, 1, '2021-03-31 13:24:38', 2, 0, 1, 0, 'ch_1Iazl3L1bMNoOfFvu8YsOntY', 'I changed my mind', 'Just need 1 quantity on the product'),
(8, 11, 18, 10010, 2, 1, '2021-03-31 13:26:37', 0, 0, 0, 0, '', 'I found a better deal at another website', 'Amazon has this cheaper.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
