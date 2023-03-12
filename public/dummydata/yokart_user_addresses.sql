-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:08 AM
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
-- Dumping data for table `yokart_user_addresses`
--

INSERT INTO `yokart_user_addresses` (`addr_id`, `addr_user_id`, `addr_title`, `addr_apartment`, `addr_email`, `addr_first_name`, `addr_last_name`, `addr_address1`, `addr_address2`, `addr_city`, `addr_country_id`, `addr_state_id`, `addr_phone_country_id`, `addr_postal_code`, `addr_phone`, `addr_billing_default`, `addr_shipping_default`) VALUES
(1, 1, 'Mrs', '', 'mark@dummyid.com', 'Mazel', 'Waugh', '4819', '4819  Hamill Avenue', 'San Diego', 231, 3924, 101, '92111', '6198933057', 1, 1),
(2, 8, 'Mr', '', 'sharjeel@dummyid.com', 'Sharjeel', 'Al Masood', '029', 'Rouwais Dist., P.O.Box: 20621 Postal Code: 21465', 'jeddah', 191, 3146, 191, '21465', '026693320', 1, 1),
(3, 7, 'Mrs', '', 'claire@dummyid.com', 'Claire', 'Corbin', '36', '36  Faubourg Saint Honoré', 'Paris', 75, 1242, 75, '75116', '949052089', 1, 1),
(4, 6, 'Ms', '', 'luis@dummyid.com', 'Luis', 'Weber', '51', 'Augsburger Strasse 51', 'Elben', 82, 1390, 82, '57580', '0463619014', 1, 1),
(5, 5, 'Laura', '', 'laura@dummyid.com', 'Laura', 'Wellis', 'Home', '222 Margaret St, Brisbane', 'Brisbane', 13, 269, 13, '4000', '736900055', 1, 1),
(6, 4, 'Mrs', '', 'mariam@dummyid.com', 'Mariam', 'Iftikhar', 'Box No. 52', 'Box No. 52', 'Dubai', 229, 3798, 229, '52', '3304444', 1, 1),
(7, 3, 'Mr', '', 'chris@dummyid.com', 'Chris', 'Timberlake', '25', '4247  Scotchmere Dr', 'CHatham', 38, 671, 38, '51884', '4032232282', 1, 1),
(8, 2, 'Ms', '', 'paul@dummyid.com', 'Paul', 'Woods', '065', '66  High St', 'Ripon', 230, 3918, 101, '9876', '0774847680', 1, 1),
(9, 9, 'Ms', '', 'mike@dummyid.com', 'Misha', 'Hesson', '1895', '1895  Harper Street', 'Paducah', 231, 3938, 231, '42001', '2702013860', 1, 1),
(10, 11, 'Home', '', '', 'John', 'Doe', 'University Drive', NULL, 'Mumbai', 101, 22, 101, '45684', '7895456525', 0, 0),
(11, 11, 'Office', '', '', 'John', 'Doe', 'Beach Drive', NULL, 'Mumbai', 101, 22, 101, '78956', '4578965987', 0, 0),
(12, 11, 'Wife - Office', '', '', 'Cindy', 'T', 'Broadway Blvd', NULL, 'Mumbai', 101, 22, 101, '78652', '4588965234', 0, 0),
(13, 9, 'Home', '', 'misha@dummyid.com', 'Misha', 'Langdon', '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 231, 3934, 231, '60601', '2454787777', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
