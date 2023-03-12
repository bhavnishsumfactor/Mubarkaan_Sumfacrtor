-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:04 AM
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
-- Dumping data for table `yokart_blog_post_categories`
--

INSERT INTO `yokart_blog_post_categories` (`bpcat_id`, `bpcat_name`, `bpcat_parent`, `bpcat_publish`, `bpcat_display_order`, `bpcat_created_by_id`, `bpcat_updated_by_id`, `bpcat_created_at`, `bpcat_updated_at`) VALUES
(1, 'E-Commerce', 0, 1, 1, 1, 1, '2020-09-29 13:09:54', '2020-09-29 13:09:54'),
(2, 'Startups', 0, 1, 2, 1, 1, '2020-09-29 14:55:34', '2020-09-29 14:55:34'),
(3, 'Features', 0, 1, 3, 1, 1, '2020-09-29 14:56:24', '2020-09-29 14:56:24');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
