-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:24 AM
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
-- Dumping data for table `yokart_share_earn_records`
--

INSERT INTO `yokart_share_earn_records` (`ser_id`, `ser_type`, `ser_user_id`, `ser_user_email`, `ser_code`, `ser_created_at`, `ser_expiry`, `ser_publish`, `ser_user_phone_code`, `ser_user_phone`) VALUES
(1, 2, 11, NULL, 'fQCsv8L0XxtU', '2021-03-31 06:46:46', '2021-04-10 06:46:00', 0, NULL, NULL),
(2, 1, 11, 'jimmy@dummyid.com', '08QF7cZuS4v2', '2021-04-01 04:46:51', '2021-05-21 04:46:00', 0, NULL, NULL),
(3, 1, 11, 'tina@dummyid.com', '08QF7cZuS4v2', '2021-04-01 04:46:51', '2021-05-21 04:46:00', 0, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
