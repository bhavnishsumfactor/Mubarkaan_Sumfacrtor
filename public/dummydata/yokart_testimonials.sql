-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:12 AM
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
-- Dumping data for table `yokart_testimonials`
--

INSERT INTO `yokart_testimonials` (`testimonial_id`, `testimonial_user_name`, `testimonial_designation`, `testimonial_title`, `testimonial_description`, `testimonial_publish`, `testimonial_created_by_id`, `testimonial_updated_by_id`, `testimonial_created_at`, `testimonial_updated_at`) VALUES
(1, 'Jessica Nicole', NULL, 'Fashion Done Right!', 'Shopping at Tribe has completely elevated my skill set, my creativity, and my understanding of how fabric works on the body- their beautiful, high-quality textiles have both challenged and inspired me, and I wouldn’t be the maker I am today without them!', 1, 1, 1, '2021-03-25 08:03:26', '2021-03-25 08:03:26'),
(2, 'Alex Parker', NULL, 'Best Online Experience !', 'Tribe Store has rapidly become my favorite place to order online. I can shop safe in the knowledge that the clothes will be top quality and true to the thorough online description. Fast shipping plus something to suit every project from everyday basics to a ball gown!', 1, 1, 1, '2021-03-25 08:06:03', '2021-03-25 08:06:03'),
(3, 'Olivia B', NULL, 'I Love Tribe!', '\"I just love the Tribe store so much - I can check-in, get great service, great clothes, and leave feeling brilliant.\"', 1, 1, 1, '2021-03-25 08:08:03', '2021-03-25 08:08:03'),
(4, 'Natalie S', NULL, 'Must have accessories!', '\"Love the jewelry. Awesome, amazing, beautiful, light, pretty, peaceful, cheap! Love Jesscia.\"', 1, 1, 1, '2021-03-25 08:11:49', '2021-03-25 08:11:49');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
