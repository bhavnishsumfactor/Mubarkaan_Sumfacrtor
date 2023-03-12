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
-- Dumping data for table `yokart_blog_posts`
--

INSERT INTO `yokart_blog_posts` (`post_id`, `post_title`, `post_publish`, `post_author_name`, `post_comment_opened`, `post_featured`, `post_view_count`, `post_published_on`, `post_created_by_id`, `post_updated_by_id`, `post_created_at`, `post_updated_at`) VALUES
(1, '8 Things To Know Before Starting An Online eCommerce Marketplace', 1, 'YoKart Chef', 1, 0, 18, '2020-09-29 00:00:00', 0, 1, '2020-09-29 13:11:05', '2020-10-19 15:38:47'),
(2, 'Coronavirus (Covid-19) Impact on the eCommerce Startups & Yo!Kart Offers Help', 1, 'Yo-Kart Chef', 1, 0, 10, '2020-09-29 00:00:00', 0, 1, '2020-09-29 15:43:55', '2020-10-19 14:43:18'),
(3, 'Benefits of Multilingual and Multicurrency Features of YoKart', 1, 'YoKart chef', 1, 0, 8, '2020-09-29 00:00:00', 0, 1, '2020-09-29 15:53:45', '2020-10-19 14:29:41'),
(4, 'A Glimpse Into The Future of Ecommerce Technology', 1, 'YoKart Chef', 1, 0, 6, '2020-09-29 00:00:00', 0, 1, '2020-09-29 15:58:30', '2020-10-19 14:28:51'),
(5, 'Why Startups Fail? Addressing the Top 5 Reasons', 1, 'YoKart Chef', 1, 0, 9, '2020-09-29 00:00:00', 0, 1, '2020-09-29 16:10:48', '2020-10-19 14:43:32'),
(6, 'The Hybrid Theory: Progressive Web Apps And Their Practicality', 1, 'YoKart Chef', 1, 0, 8, '2020-09-29 00:00:00', 0, 0, '2020-09-29 16:59:10', '2020-10-19 14:47:16'),
(7, 'Brand Marketing V/S Direct Marketing: The Early Stage Ecommerce Startup Dilemma', 1, 'YoKart Chef', 1, 0, 8, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:01:17', '2020-10-19 14:31:11'),
(8, '10 Startups Based On Ecommerce Subscription Model', 1, 'YoKart Chef', 1, 0, 4, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:24:44', '2020-10-19 14:30:34'),
(9, 'Yield Maximum Results from B2C E-commerce', 1, 'YoKart Chef', 1, 0, 10, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:26:57', '2020-10-19 14:29:50'),
(10, '5 Niche Ecommerce Businesses You can Start with YoKart', 1, 'YoKart Chef', 1, 0, 8, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:31:14', '2020-10-19 14:30:56'),
(11, 'Subscription Box Marketplace – Business Model and Website Features', 1, 'YoKart Chef', 1, 0, 6, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:37:28', '2020-10-12 13:07:52'),
(12, 'Want Sky-high Conversion Rate for Your Ecommerce Store? Don’t Ignore Clueless Shoppers', 1, 'YoKart Chef', 1, 0, 7, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:42:22', '2020-10-15 10:07:49'),
(13, '5 Features That Make YoKart a Seller-Friendly Ecommerce Platform', 1, 'YoKart Chef', 1, 0, 14, '2020-09-29 00:00:00', 0, 1, '2020-09-29 17:47:20', '2020-10-13 10:21:00'),
(14, 'How to Use Customer Data to Improve Conversion of Your Ecommerce Store', 1, 'YoKart Chef', 1, 0, 5, '2020-09-29 00:00:00', 0, 1, '2020-09-29 18:14:23', '2020-10-05 14:19:05'),
(15, '10 Advantages of E-commerce Over Traditional Commerce', 1, 'YoKart Chef', 1, 0, 10, '2020-09-29 00:00:00', 0, 1, '2020-09-29 18:38:45', '2020-10-19 14:26:40'),
(16, 'Secrets of Creating a Killer Ecommerce Marketplace Logo: Revealed', 1, 'YoKart Chef', 1, 0, 10, '2020-09-29 00:00:00', 0, 1, '2020-09-29 18:41:26', '2020-10-14 10:36:53'),
(17, 'How Dynamic Product Recommendation Can Help Increase Sales of an Ecommerce Marketplace', 1, 'YoKart Chef', 1, 0, 10, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:16:10', '2020-10-19 14:25:23'),
(18, 'Top Chatbots to integrate with your ecommerce store for improved conversion rate', 1, 'YoKart Chef', 1, 0, 11, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:21:16', '2020-10-19 15:37:04'),
(19, 'How Bootstrapped Startups Market Their Brand on a Low Budget & Do well', 1, 'YoKart Chef', 1, 0, 7, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:26:33', '2020-10-19 14:24:05'),
(20, 'How Top eCommerce Marketplaces Are Resuming Operations Amid COVID-19?', 1, 'YoKart Chef', 1, 0, 7, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:29:37', '2020-10-19 14:23:41'),
(21, 'Ecommerce Penetration During Covid-19 in UAE – Popular Brands and Shifting Consumers’ Interests', 1, 'YoKart Chef', 1, 0, 7, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:31:57', '2020-10-19 17:07:55'),
(22, 'Social Commerce Marketplace – Types, Features, And Factors For Growth', 1, 'YoKart Chef', 1, 0, 5, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:37:45', '2020-10-19 14:23:12'),
(23, 'Top Selling eCommerce Products: Shifting Consumer Behaviour And Changing Product Demand Amid COVID-19', 1, 'YoKart Chef', 1, 0, 10, '2020-09-30 00:00:00', 0, 0, '2020-09-30 12:43:01', '2020-10-19 14:19:58'),
(24, '5 Crucial Steps Entrepreneurs Follow To Start A New Ecommerce Business', 1, 'YoKart Chef', 1, 0, 7, '2020-09-30 00:00:00', 0, 0, '2020-09-30 12:44:49', '2020-10-19 14:14:53'),
(25, 'How To Choose The Perfect Domain Name For Ecommerce Website', 1, 'YoKart Chef', 1, 0, 8, '2020-09-30 00:00:00', 0, 0, '2020-09-30 12:46:49', '2020-10-19 14:19:49'),
(26, 'How to Effectively Design Social Media Post for eCommerce Marketplace', 1, 'YoKart Chef', 1, 0, 8, '2020-09-30 00:00:00', 0, 0, '2020-09-30 12:51:19', '2020-10-19 14:19:46'),
(27, 'Blog Integration in Online Store is a great way to generate more traffic & sales', 1, 'YoKart Chef', 1, 0, 10, '2020-09-30 00:00:00', 0, 1, '2020-09-30 12:54:17', '2020-10-19 14:28:27'),
(28, 'Why Vertical Online Marketplace Model Is Gaining More Popularity Over The Horizontal Model?', 1, 'YoKart Chef', 1, 0, 10, '2020-09-30 00:00:00', 0, 0, '2020-09-30 12:57:26', '2020-10-19 15:06:21'),
(29, 'Yo!Kart Partners With EasyEcom For Seamless Multi-channel Selling In Online Marketplaces', 1, 'YoKart Chef', 1, 0, 7, '2020-09-30 00:00:00', 0, 0, '2020-09-30 12:59:16', '2020-10-19 14:10:12'),
(30, 'YoKart Guarantees Scalability & Performance of Your Ecommerce Store', 1, 'YoKart Chef', 1, 0, 19, '2020-09-30 00:00:00', 0, 1, '2020-09-30 13:01:45', '2020-10-19 14:09:10'),
(31, 'Brands That Started As A Single Vendor Store But Became An Online Marketplace', 1, 'YoKart Chef', 1, 0, 9, '2020-09-30 00:00:00', 0, 0, '2020-09-30 13:04:36', '2020-10-19 14:08:38'),
(32, 'Yo!Kart Is Soon Unveiling “Tribe”: An Ecommerce Store Software', 1, 'YoKart Chef', 1, 0, 21, '2020-09-30 00:00:00', 0, 1, '2020-09-30 13:07:27', '2020-10-19 15:16:22');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
