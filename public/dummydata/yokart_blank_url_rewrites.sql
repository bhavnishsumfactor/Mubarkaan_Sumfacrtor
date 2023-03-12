-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2021 at 10:38 AM
-- Server version: 5.7.33-0ubuntu0.18.04.1
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restore-setup`
--

--
-- Dumping data for table `yokart_url_rewrites`
--

INSERT INTO `yokart_url_rewrites` (`urlrewrite_id`, `urlrewrite_type`, `urlrewrite_record_id`, `urlrewrite_original`, `urlrewrite_custom`) VALUES
(1, 2, 1, 'page/1', 'home'),
(2, 2, 2, 'page/2', 'about-us'),
(3, 2, 3, 'page/3', 'terms'),
(4, 2, 4, 'page/4', 'contact-us'),
(5, 2, 7, 'page/7', 'privacy-policy'),
(6, 2, 8, 'page/8', 'faqs');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
