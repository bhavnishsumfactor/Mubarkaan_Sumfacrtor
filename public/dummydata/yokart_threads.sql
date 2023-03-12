-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:30 AM
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
-- Dumping data for table `yokart_threads`
--

INSERT INTO `yokart_threads` (`thread_id`, `thread_started_by`, `thread_product_id`, `thread_product_variant`, `thread_subject`, `thread_created_at`, `thread_product_name`) VALUES
(6, 11, 303, '', 'What is the return policy of the product?', '2021-03-31 07:19:48', 'Kenneth Cole Beige Solid Handheld Bag'),
(7, 11, 30, '', 'Can a XS size be made available?', '2021-03-31 07:22:54', 'Gabby Smocked Floral-Print Mini Dress'),
(8, 9, 310, '', 'Will I get warranty from the company or Tribe?', '2021-03-31 07:28:12', 'PETITE SHEFFIELD');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
