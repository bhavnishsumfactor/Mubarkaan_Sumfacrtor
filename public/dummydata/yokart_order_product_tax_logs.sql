-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:07 AM
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
-- Dumping data for table `yokart_order_product_tax_logs`
--

INSERT INTO `yokart_order_product_tax_logs` (`optl_order_id`, `optl_op_id`, `optl_key`, `optl_value`) VALUES
(10000, 1, 'Tax', '8'),
(10001, 2, 'Tax', '89.9'),
(10002, 3, 'Tax', '3.31'),
(10002, 4, 'Tax', '9.8'),
(10002, 5, 'Tax', '4'),
(10002, 6, 'Tax', '69.9'),
(10003, 7, 'Tax', '5.34'),
(10003, 8, 'Tax', '20'),
(10004, 9, 'Tax', '2'),
(10004, 10, 'Tax', '4.3'),
(10005, 11, 'Tax', '3.5'),
(10005, 12, 'Tax', '26.8'),
(10006, 13, 'Tax', '28.5'),
(10007, 14, 'Tax', '13.5'),
(10007, 15, 'Tax', '14.6'),
(10008, 16, 'Tax', '470'),
(10009, 17, 'Tax', '10'),
(10010, 18, 'Tax', '12.6'),
(10010, 19, 'Tax', '12.9'),
(10010, 20, 'Tax', '3.17'),
(10011, 21, 'Tax', '0'),
(10012, 22, 'Tax', '0'),
(10013, 23, 'Tax', '0'),
(10014, 24, 'Tax', '0'),
(10015, 25, 'Tax', '0'),
(10015, 26, 'Tax', '0'),
(10015, 27, 'Tax', '0'),
(10015, 28, 'Tax', '0'),
(10016, 29, 'Tax', '1120'),
(10017, 30, 'Tax', '18.6'),
(10017, 31, 'Tax', '2.8'),
(10017, 32, 'Tax', '9'),
(10018, 33, 'Tax', '6.5'),
(10018, 34, 'Tax', '79.5'),
(10019, 35, 'Tax', '7'),
(10020, 36, 'Tax', '235'),
(10021, 37, 'Tax', '6.3'),
(10021, 38, 'Tax', '1.9'),
(10022, 39, 'Tax', '8.8'),
(10022, 40, 'Tax', '13.8'),
(10023, 41, 'Tax', '7.6'),
(10024, 42, 'Tax', '5'),
(10024, 43, 'Tax', '4'),
(10025, 44, 'Tax', '4.9'),
(10025, 45, 'Tax', '3'),
(10025, 46, 'Tax', '1.44'),
(10026, 47, 'Tax', '1.2'),
(10026, 48, 'Tax', '11.88'),
(10026, 49, 'Tax', '2.4'),
(10027, 50, 'Tax', '47.5'),
(10027, 51, 'Tax', '39.9');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
