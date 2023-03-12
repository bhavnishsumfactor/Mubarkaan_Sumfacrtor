-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:08 AM
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
-- Dumping data for table `yokart_order_return_amounts`
--

INSERT INTO `yokart_order_return_amounts` (`oramount_id`, `oramount_orrequest_id`, `oramount_op_id`, `oramount_tax`, `oramount_shipping`, `oramount_discount`, `oramount_giftwrap_price`, `oramount_reward_price`, `oramount_payment_status`) VALUES
(1, 1, 24, '0', '10.00', '0', '0', '0', 0),
(2, 2, 21, '0', '10.00', '0', '5', '0', 0),
(3, 3, 16, '235', '15.00', '0', '0', '0', 1),
(4, 4, 42, '2.4995', '0', '0', '0', '0', 0),
(5, 5, 43, '1.9995', '0', '0', '5', '0', 1),
(6, 6, 35, '3.5', '15.00', '0', '0', '0', 0),
(7, 7, 8, '10', '0', '0', '0', '0', 1),
(8, 8, 18, '12.6', '0', '0', '0', '0', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
