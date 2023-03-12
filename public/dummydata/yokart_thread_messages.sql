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
-- Dumping data for table `yokart_thread_messages`
--

INSERT INTO `yokart_thread_messages` (`message_id`, `message_thread_id`, `message_from_type`, `message_from_id`, `message_to`, `message_text`, `message_is_unread`, `message_deleted`, `message_created_at`, `message_from_admin`) VALUES
(6, 6, 'App\\Models\\User', 11, 1, 'I am looking to buy this product as a gift and I want to understand what is the return policy?', 0, 0, '2021-03-31 07:19:48', 0),
(7, 6, 'App\\Models\\Admin\\Admin', 1, 11, 'Hi John', 1, 0, '2021-03-31 07:20:19', 1),
(8, 6, 'App\\Models\\Admin\\Admin', 1, 11, 'We offer a 30 day no questions asked return policy', 1, 0, '2021-03-31 07:20:36', 1),
(9, 6, 'App\\Models\\Admin\\Admin', 1, 11, 'I hope this helps', 1, 0, '2021-03-31 07:20:47', 1),
(10, 6, 'App\\Models\\User', 11, 1, 'Yes this surely does!', 0, 0, '2021-03-31 07:21:05', 0),
(11, 6, 'App\\Models\\User', 11, 1, 'Thank you for the information', 0, 0, '2021-03-31 07:21:19', 0),
(12, 7, 'App\\Models\\User', 11, 1, 'I\'m looking for a XS size in the item', 0, 0, '2021-03-31 07:22:54', 0),
(13, 7, 'App\\Models\\Admin\\Admin', 1, 11, 'Hello John', 1, 0, '2021-03-31 07:23:35', 1),
(14, 7, 'App\\Models\\Admin\\Admin', 1, 11, 'We dont have an XS in the item', 1, 0, '2021-03-31 07:23:45', 1),
(15, 7, 'App\\Models\\User', 11, 1, 'That\'s unfortunate! My wife really liked this dress!', 0, 0, '2021-03-31 07:25:49', 0),
(16, 7, 'App\\Models\\User', 11, 1, 'Any how thank you for the help', 0, 0, '2021-03-31 07:25:58', 0),
(17, 7, 'App\\Models\\User', 11, 1, '👍️', 0, 0, '2021-03-31 07:26:10', 0),
(18, 8, 'App\\Models\\User', 9, 1, 'What is the warranty policy for the product', 0, 0, '2021-03-31 07:28:12', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
