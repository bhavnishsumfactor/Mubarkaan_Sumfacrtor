-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2021 at 10:45 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tr1beyok4rt_demo`
--

--
-- Dumping data for table `yokart_store_timings`
--

INSERT INTO `yokart_store_timings` (`st_saddr_id`, `st_day`, `st_from`, `st_to`) VALUES
(1, 1, '09:00', '21:00'),
(1, 2, '09:00', '21:00'),
(1, 3, '09:00', '21:00'),
(1, 4, '09:00', '21:00'),
(1, 5, '09:00', '21:00'),
(1, 6, '09:00', '21:00'),
(1, 0, '09:00', '21:00'),
(2, 1, '09:00', '18:00'),
(2, 3, '09:00', '18:00'),
(2, 5, '09:00', '18:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;