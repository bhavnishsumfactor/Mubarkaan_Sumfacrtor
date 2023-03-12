-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:05 AM
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
-- Dumping data for table `yokart_users`
--

INSERT INTO `yokart_users` (`user_id`, `user_facebook_id`, `user_google_id`, `user_instagram_id`, `user_fname`, `user_lname`, `user_dob`, `user_gender`, `user_email`, `user_phone_country_id`, `user_phone`, `user_password`, `user_timezone`, `user_country_id`, `user_birthday_points_expiry`, `user_is_guest`, `user_language_id`, `user_currency_id`, `user_phone_verified`, `user_email_verified`, `user_referral_code`, `user_publish`, `user_created_at`, `user_updated_at`, `user_created_by_id`, `user_updated_by_id`, `user_gdpr_approved`, `user_cookie_preferences`) VALUES
(1, 'NULL', 'NULL', 'NULL', 'Mazel', 'Waugh', '04-08-1987', '2', 'mazel@dummyid.com', 231, '9876543210', '$2y$10$lbS7jaxwPZEi82dV2k7Vx.Cw/a130UDQxs9Ry0gaox3pO/Rqq78ey', 'America/Santiago', 231, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-14 13:18:06', '2020-11-02 14:40:03', 1, 1, 0, ''),
(2, 'NULL', 'NULL', 'NULL', 'Paula', 'Woods', '08-06-2000', '2', 'paula@dummyid.com', 230, '0774847680', '$2y$10$WPmweRj5LEAgeVECuf0OZ.KZCjSoIrFEVs0V8lrbeXCErekzYpXOW', 'Europe/London', 230, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 13:28:49', '2020-11-02 14:39:38', 1, 1, 0, ''),
(3, 'NULL', 'NULL', 'NULL', 'Chris', 'Timberlake', '14-08-1997', '1', 'chris@dummyid.com', 38, '4032232282', '$2y$10$8U5EB3dQeQEP/c26cl7yw.HdY2vHRKdC9S3FOvCFhB3GwyJZWgu0q', 'America/Toronto', 38, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 14:58:20', '2020-11-07 12:35:42', 1, 1, 0, ''),
(4, 'NULL', 'NULL', 'NULL', 'Mariam', 'Iftikhar', '20-11-1991', '2', 'mariam@dummyid.com', 229, '3304444', '$2y$10$sB4CqY1qB7PeYQNvh8bjCeegSm.RqnnZfCGv0KwbLL8qxYNuP91Nq', 'Asia/Dubai', 229, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 15:02:28', '2020-11-02 14:37:46', 1, 1, 0, ''),
(5, 'NULL', 'NULL', 'NULL', 'Laura', 'Wellis', '21-01-1986', '2', 'laura@dummyid.com', 13, '073690005', '$2y$10$ff.IlCbLYcsk86t8ApGfVelFsIpxWuL23.kngT4ESNwQnQlSlADoK', 'Australia/Sydney', 13, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 15:07:29', '2021-03-31 10:53:19', 1, 1, 0, 'a:4:{s:10:\"functional\";i:1;s:9:\"analytics\";i:1;s:12:\"personalized\";i:1;s:11:\"advertising\";i:1;}'),
(6, 'NULL', 'NULL', 'NULL', 'Luis', 'Weber', '26-06-1991', '2', 'luis@dummyid.com', 82, '46361901482', '$2y$10$c09Lh/WtR.IlgyDaEK.2Pe5Hrl5GOAj2FIkccFI3.NPbOPK32PuYG', 'Europe/Berlin', 82, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 15:13:27', '2020-11-02 14:38:27', 1, 1, 0, ''),
(7, 'NULL', 'NULL', 'NULL', 'Claire', 'Corbin', '08-03-1988', '2', 'claire@dummyid.com', 75, '949052089', '$2y$10$Mhwwr.BDXoE2aC0Gfk1S2.8j5ldSBtOVb4WZT0..tKMTiBbiPfbUO', 'Europe/Paris', 75, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 15:19:49', '2020-10-29 14:20:19', 1, 1, 0, ''),
(8, 'NULL', 'NULL', 'NULL', 'Sharjeel', 'Al Masood', '29-02-1996', '1', 'sharjeel@dummyid.com', 191, '026693320', '$2y$10$SDJ0DSUoK9Nin5s87kISl.IQAOnvZRzNkf/v2rbq9MbK12wrlE5bu', 'Asia/Riyadh', 191, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-16 15:30:11', '2020-11-09 10:23:07', 1, 1, 0, ''),
(9, 'NULL', 'NULL', 'NULL', 'Misha', 'Hesson', '17-06-1998', '2', 'misha@dummyid.com', 231, '2702013860', '$2y$10$dWKUG.eJAwZF2F3yXgfYmO4mJNc6PWoa7cepSj3cRz2lq5Ax2UcI2', 'America/Kentucky/Louisville', 101, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-18 16:05:00', '2021-03-31 07:34:53', 1, 1, 0, 'a:4:{s:10:\"functional\";i:1;s:9:\"analytics\";i:1;s:12:\"personalized\";i:1;s:11:\"advertising\";i:1;}'),
(10, 'NULL', '109475806132874271574', 'NULL', 'Pawan', 'Kumar', 'NULL', 'NULL', 'XXXXXX@ablysoft.com', 0, '', 'NULL', 'Asia/Kolkata', 101, NULL, 0, 1, 2, 0, 0, '', 0, '2020-10-07 15:17:27', '2020-11-12 15:43:18', 0, 0, 0, ''),
(11, 'NULL', 'NULL', 'NULL', 'John', 'Doe', '17-06-1998', '1', 'tribe@dummyid.com', 231, '4804568915', '$2y$10$Q5/qCySRpGjg.swklDFjuetEFuperuhAQrRV5aDDaWd5mhmFWd6tO', 'Asia/Kolkata', 101, NULL, 0, 1, 1, 0, 1, '', 1, '2020-09-18 16:05:00', '2021-03-31 06:49:42', 1, 1, 0, 'a:4:{s:10:\"functional\";i:1;s:9:\"analytics\";i:1;s:12:\"personalized\";i:1;s:11:\"advertising\";i:1;}');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
