-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 09, 2021 at 06:04 AM
-- Server version: 10.3.31-MariaDB-log
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `singlevendor_devdemo`
--

--
-- Dumping data for table `yokart_app_pages`
--

INSERT INTO `yokart_app_pages` (`page_id`, `page_title`, `page_slug`, `page_type`) VALUES
(1, 'Home', 'home-screen', 1),
(2, 'Privacy Screen', 'privacy', 3),
(3, 'Terms Screen', 'terms', 3),
(4, 'Contact Us', 'contact-us', 3),
(5, 'Faq', 'faqs', 3),
(6, 'Blogs', 'blogs', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
