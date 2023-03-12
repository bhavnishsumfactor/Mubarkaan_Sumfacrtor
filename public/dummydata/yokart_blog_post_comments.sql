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
-- Dumping data for table `yokart_blog_post_comments`
--

INSERT INTO `yokart_blog_post_comments` (`bpcomment_id`, `bpcomment_user_id`, `bpcomment_author_name`, `bpcomment_author_email`, `bpcomment_content`, `bpcomment_approved`, `bpcomment_added_on`, `bpcomment_user_ip`, `bpcomment_user_agent`, `bpcomment_created_by_id`, `bpcomment_updated_by_id`, `bpcomment_created_at`, `bpcomment_updated_at`) VALUES
(1, 9, '', '', 'Great blog very informative loved to read more from you.', 1, '2020-10-01 10:54:59', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 10:59:54'),
(2, 9, '', '', 'Marcados!!!! Eu gosto seu site !', 1, '2020-10-01 11:05:45', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 11:06:33'),
(4, 9, '', '', 'Yo!Kart seems like a good option. Would like to connect. I need it..', 1, '2020-10-01 11:56:46', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 11:56:55'),
(5, 9, '', '', 'Thanks to the insights on marketing. Will surely implement it :)', 1, '2020-10-01 12:01:50', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:03:17'),
(6, 9, '', '', 'Very insightful! Big Thanks!!!!!!!!!', 1, '2020-10-01 12:04:15', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:04:27'),
(7, 9, '', '', 'True that! Couldn\'t agree more..', 1, '2020-10-01 12:05:37', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:09:39'),
(8, 8, '', '', 'Very cool features. Excited!!!', 1, '2020-10-01 12:08:22', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:09:39'),
(9, 8, '', '', 'Very Interesting.. I\'ll make use of these points.', 1, '2020-10-01 12:12:05', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:12:14'),
(10, 8, '', '', 'I am an aspiring entrepreneur. These were very fair bullets..', 1, '2020-10-01 12:15:00', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:16:15'),
(11, 8, '', '', 'Interesting.. You guys are doing a great job! Keep up..', 1, '2020-10-01 12:16:05', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:16:16'),
(12, 8, '', '', 'True that.. many guys starting up ignore this but is important!', 1, '2020-10-01 12:17:29', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:17:35'),
(13, 8, '', '', 'WOW! This was unknown..', 1, '2020-10-01 12:19:09', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:19:16'),
(14, 8, '', '', 'It has been a bad year for e-commerce all around. Hopefully it\'ll get better', 1, '2020-10-01 12:20:52', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:22:36'),
(15, 7, '', '', 'It\'s a booming industry! Very much scope..', 1, '2020-10-01 12:26:48', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:26:57'),
(16, 7, '', '', 'Very Interesting.. mind getting in touch? What other features do you offer? plus admin site', 1, '2020-10-01 12:29:20', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:30:04'),
(17, 7, '', '', 'Hey i\'d like to get in touch with the author. Thanks :)', 1, '2020-10-01 12:44:54', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:45:17'),
(18, 7, '', '', 'This is an amazing hack! Well written too', 1, '2020-10-01 12:45:55', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:46:13'),
(19, 7, '', '', 'Can you please address Analyze Traffic Sources for better customer targeting in more details ,? Thank You!', 1, '2020-10-01 12:47:13', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:47:18'),
(20, 7, '', '', 'It\'s not easy to do so, still i like the tricks you mentioned. Much appreciated!', 1, '2020-10-01 12:48:08', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:48:13'),
(21, 7, '', '', 'Very fresh take and concept. Thanks!', 1, '2020-10-01 12:48:53', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:49:00'),
(22, 7, '', '', 'Tough Times.. but blogs like these makes it easier.', 1, '2020-10-01 12:51:31', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:51:44'),
(23, 6, '', '', 'The fact that it is Economical comparatively is what makes these platforms more accessible. But the competition is fierce. Nicely written !', 1, '2020-10-01 12:55:16', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 12:55:28'),
(24, 6, '', '', 'I plan ventur into PWA soon. I\'d like to get in touch. reach out to me at luis@dummyid.com.', 1, '2020-10-01 13:00:18', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:00:25'),
(25, 6, '', '', 'Future is unpredictable but planning for it is the upmost skill. On point article!', 1, '2020-10-01 13:01:07', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:01:15'),
(26, 6, '', '', 'B2B seems gettable, but if successful, B2C is the real deal! Tho it\'s a long marathon and not a sprint!', 1, '2020-10-01 13:05:12', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:07:40'),
(27, 6, '', '', 'These new age features are sometimes too difficult you gotta do it for staying in the game.. Very well put..', 1, '2020-10-01 13:06:29', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:07:40'),
(28, 6, '', '', 'Können Sie Unterstützung bei der Erstellung von Logos leisten?', 1, '2020-10-01 13:07:34', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:07:41'),
(29, 5, '', '', 'Big thanks! I was looking for innovative ways to target more audience. This helped big time', 1, '2020-10-01 13:10:38', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:12:41'),
(30, 5, '', '', 'Changing Product Demand is very spontaneous these days. I like the writer..', 1, '2020-10-01 13:14:07', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:14:15'),
(31, 5, '', '', 'Useful for beginners. Thankyou!', 1, '2020-10-01 13:15:54', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:16:04'),
(32, 5, '', '', 'Digital is the future. Well put. Kudos', 1, '2020-10-01 13:16:52', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:16:59'),
(33, 5, '', '', 'Let\'s connect and help each other. laura@dummyid.com', 1, '2020-10-01 13:18:05', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:18:13'),
(34, 5, '', '', 'Founders need to introspect and not just ride on the high. Brilliant read', 1, '2020-10-01 13:19:55', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:20:01'),
(35, 5, '', '', 'I was unaware.. Thanks for this', 1, '2020-10-01 13:20:45', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:20:53'),
(36, 4, '', '', 'That\'s a steal! Solid move by both', 1, '2020-10-01 13:23:29', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:23:37'),
(37, 4, '', '', 'That\'s some deep learnings! Keep it up with more of these', 1, '2020-10-01 13:24:20', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:24:26'),
(38, 4, '', '', 'الثاقبة جدا. زيادة المحتوى', 1, '2020-10-01 13:25:43', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:25:48'),
(39, 4, '', '', 'يبدو أنه فريق / منتج جيد للتعاون معه', 1, '2020-10-01 13:27:17', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:27:22'),
(40, 4, '', '', 'This is very important. Great things mentioned', 1, '2020-10-01 13:28:56', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:29:48'),
(41, 4, '', '', 'بين الأشياء هناك. حافظ على كاتب العمل الجيد!', 1, '2020-10-01 13:30:34', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:30:40'),
(42, 4, '', '', 'Very well informed. Need to choose my niche now.', 1, '2020-10-01 13:31:27', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:32:05'),
(43, 3, '', '', 'This itself is a good social media post for a social media post. Very good', 1, '2020-10-01 13:34:26', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:34:35'),
(44, 3, '', '', 'Top notch content. Amazing!', 1, '2020-10-01 13:35:06', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:35:51'),
(45, 3, '', '', 'That\'s nice. very insightful. Need to address the history regarding this.. Very well!', 1, '2020-10-01 13:37:18', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:37:26'),
(46, 3, '', '', 'Logo is very important for UX. Need to focus on it..', 1, '2020-10-01 13:39:16', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:39:34'),
(47, 3, '', '', 'Data is the new currency. Reading it made me realise i have been lagging. Thanks man', 1, '2020-10-01 13:41:00', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:41:08'),
(48, 3, '', '', 'Hey do you guys provide assistance for codded replies or chatbots?', 1, '2020-10-01 13:42:19', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 13:42:24'),
(49, 3, '', '', 'Bootstraped ones provide you with a lot of freedom. freedom is importan to succeed', 1, '2020-10-01 13:44:03', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:40:20'),
(50, 2, '', '', 'Those are some real time features. Blown!', 1, '2020-10-01 14:43:34', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:44:44'),
(51, 2, '', '', 'True. ffiliating our way to heights should be the aim', 1, '2020-10-01 14:44:30', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:44:44'),
(52, 2, '', '', 'Factors mentioned are true, up to date and accurate. Very knowledgeable read', 1, '2020-10-01 14:46:38', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:46:50'),
(53, 2, '', '', 'UAE interests majority. it\'ll blow up pretty soon. Thanks for informing the masses', 1, '2020-10-01 14:47:31', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:48:51'),
(54, 2, '', '', 'Solid list there. It\'s brave but necessary to keep the head high.', 1, '2020-10-01 14:48:13', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:48:51'),
(55, 2, '', '', 'Please share your content on other platforms too. It\'s really encouraging. Also do you have subsciption offers?', 1, '2020-10-01 14:50:26', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:50:34'),
(56, 2, '', '', 'Pleas elaborate more on it. Loking forward to next blogs. Cheers', 1, '2020-10-01 14:51:56', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:59:31'),
(57, 1, '', '', 'Let\'s collab. I see you cvan help me, how can i reach out to you?', 1, '2020-10-01 14:55:13', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:59:31'),
(58, 1, '', '', 'What are the seller side subscription packages?', 1, '2020-10-01 14:56:05', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:59:32'),
(59, 1, '', '', 'Thankyou! i was searching for a good article on future trends. This helps!', 1, '2020-10-01 14:56:41', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:59:32'),
(60, 1, '', '', 'Right approach is necessary. Great read..', 1, '2020-10-01 14:57:09', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:59:33'),
(61, 1, '', '', 'Brand > direct. Good points', 1, '2020-10-01 14:59:17', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 14:59:33'),
(62, 1, '', '', 'Solid list. A lot to learn from this space. Keep adding', 1, '2020-10-01 15:00:06', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 15:01:06'),
(63, 1, '', '', 'Great strategies. Keep up the good work', 1, '2020-10-01 15:01:58', '112.196.26.203', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-01 15:04:00'),
(64, 0, '', '', 'Thankyou Mariam! So kind of you :)', 1, '2020-10-19 12:07:50', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:07:50', '2020-10-19 12:07:50'),
(65, 0, '', '', 'Thanks! Up & above :)', 1, '2020-10-19 12:08:06', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:08:06', '2020-10-19 12:08:06'),
(66, 0, '', '', 'Glad you liked it! Sure..', 1, '2020-10-19 12:08:39', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:08:39', '2020-10-19 12:08:39'),
(67, 0, '', '', 'Word. Thank ;)', 1, '2020-10-19 12:08:56', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:08:56', '2020-10-19 12:08:56'),
(68, 0, '', '', 'Very true Mazel. Thankyou!', 1, '2020-10-19 12:09:18', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:09:18', '2020-10-19 12:09:18'),
(69, 0, '', '', 'Glad we could help! Always there :)', 1, '2020-10-19 12:10:01', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:10:01', '2020-10-19 12:10:01'),
(70, 0, '', '', 'Thanks for your interest Mazel. Our team will get back to you shortly. Meanwhile you can reach out to us at yokartchef@dummyid.com. :)', 1, '2020-10-19 12:11:36', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:11:36', '2020-10-19 12:11:36'),
(71, 0, '', '', 'Thanks for your interest Mazel. Our team will get back to you shortly. Meanwhile you can reach out to us at yokartchef@dummyid.com. :)', 1, '2020-10-19 12:12:10', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:12:10', '2020-10-19 12:12:10'),
(72, 0, '', '', 'Sure Paula! Keep an eye on this page for more updates. You can also subscribe to us for notifications at yokartchef@dummyid.com', 1, '2020-10-19 12:14:28', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:14:28', '2020-10-19 12:14:28'),
(73, 0, '', '', 'Thanks for the suggestion Paula. You can find us at twitter, facebook, instagram & many other platforms with the handle \"yokartchef\" :)\nYes we do have subscription offers, our team will get back to you super soon. Cheers !', 1, '2020-10-19 12:16:52', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:16:52', '2020-10-19 12:16:52'),
(74, 0, '', '', 'Always :)', 1, '2020-10-19 12:17:09', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:17:09', '2020-10-19 12:17:09'),
(75, 0, '', '', 'True that! Thank\'s for the good words!', 1, '2020-10-19 12:18:01', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:18:01', '2020-10-19 12:18:01'),
(76, 0, '', '', 'Glad you liked it Paula!', 1, '2020-10-19 12:18:40', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:18:40', '2020-10-19 12:18:40'),
(77, 0, '', '', 'Up & above!', 1, '2020-10-19 12:19:09', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:19:09', '2020-10-19 12:19:09'),
(78, 0, '', '', 'Thankyou Paula', 1, '2020-10-19 12:20:27', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:20:27', '2020-10-19 12:20:27'),
(79, 0, '', '', 'True that!\nThanks Chris!', 1, '2020-10-19 12:20:59', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:20:59', '2020-10-19 12:20:59'),
(80, 0, '', '', 'Hey Chris! Yes we do. Out team will reach out to you super soon. Alternatively you can contact us at yokartchef@dummyid.com', 1, '2020-10-19 12:22:49', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:22:49', '2020-10-19 12:22:49'),
(81, 0, '', '', 'Always there to help. Glad you liked it!', 1, '2020-10-19 12:25:32', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:25:32', '2020-10-19 12:25:32'),
(82, 0, '', '', 'Yes Chris! Thanks for commenting!', 1, '2020-10-19 12:28:10', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:28:10', '2020-10-19 12:28:10'),
(83, 0, '', '', 'Sure Chris. We will keep updating you. Thanks for reading!', 1, '2020-10-19 12:29:19', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:29:19', '2020-10-19 12:29:19'),
(84, 0, '', '', 'These comments keep us moving. Thanks :D', 1, '2020-10-19 12:31:03', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:31:03', '2020-10-19 12:31:03'),
(85, 0, '', '', 'Inception maybe? Glad we could help :)', 1, '2020-10-19 12:32:10', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:32:10', '2020-10-19 12:32:10'),
(86, 0, '', '', 'Thanks Mariam for the kind words :)', 1, '2020-10-19 12:34:21', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:34:21', '2020-10-19 12:34:21'),
(87, 0, '', '', 'Glad you liked it!', 1, '2020-10-19 12:34:41', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:34:41', '2020-10-19 12:34:41'),
(88, 0, '', '', 'This made our day! Thanks Mariam :)', 1, '2020-10-19 12:35:21', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:35:21', '2020-10-19 12:35:21'),
(89, 0, '', '', 'Thanks Mariam! Sure, we\'re working on that.', 1, '2020-10-19 12:36:58', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:36:58', '2020-10-19 12:36:58'),
(90, 0, '', '', 'Yes Mariam. Thankyou so much !', 1, '2020-10-19 12:39:22', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:39:22', '2020-10-19 12:39:22'),
(91, 0, '', '', 'Thanks for the encouragement Mariam! Yes, We\'re looking forward to it.', 1, '2020-10-19 12:40:53', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:40:53', '2020-10-19 12:40:53'),
(92, 0, '', '', 'Pleasure Laura. Keep up the support!', 1, '2020-10-19 12:42:12', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:42:12', '2020-10-19 12:42:12'),
(93, 0, '', '', 'Yes Laura. Thanks for your sharing your opinion !', 1, '2020-10-19 12:42:59', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:42:59', '2020-10-19 12:42:59'),
(94, 0, '', '', 'Our team will get in touch with you soon Laura. Cheers!', 1, '2020-10-19 12:45:29', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:45:29', '2020-10-19 12:45:29'),
(95, 0, '', '', 'Yes Laura. Thankyou very much. Means alot coming from you.. :)', 1, '2020-10-19 12:46:19', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 12:46:19', '2020-10-19 12:46:19'),
(96, 0, '', '', 'Thanks Laura. Appreciate it!', 1, '2020-10-19 13:08:15', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:08:15', '2020-10-19 13:08:15'),
(97, 0, '', '', 'Thanks Laura. Always good coming from you !', 1, '2020-10-19 13:09:05', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:09:05', '2020-10-19 13:09:05'),
(98, 0, '', '', 'Feels good to be appreciated. Thanks!', 1, '2020-10-19 13:11:19', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:11:19', '2020-10-19 13:11:19'),
(99, 0, '', '', 'Hi Luis! Yes sure we do provide services for that. Our team will get in touch with you soon.', 1, '2020-10-19 13:17:17', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:17:17', '2020-10-19 13:17:17'),
(100, 0, '', '', 'Very true Luis ! We got to keep up with technology and new trends. Thanks for commenting!', 1, '2020-10-19 13:22:57', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:22:57', '2020-10-19 13:22:57'),
(101, 0, '', '', 'Yes Luis, these insights are very useful which you share. Glad to hear it from you every now and then.', 1, '2020-10-19 13:24:47', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:24:47', '2020-10-19 13:24:47'),
(102, 0, '', '', 'Top Comment Luis! That\'s the truth. Keep up the support and do share :)', 1, '2020-10-19 13:25:49', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:25:49', '2020-10-19 13:25:49'),
(103, 0, '', '', 'Sure Luis. Thanks for your interest! Our team will get in touch with you very soon. You can alternatively have a chat with us at yokartchef.dummyid@com.', 1, '2020-10-19 13:27:39', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:27:39', '2020-10-19 13:27:39'),
(104, 0, '', '', 'Very fierce! But what thrill is there in not living on the edge? Yes, the world is accessible now than ever.', 1, '2020-10-19 13:33:49', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:33:49', '2020-10-19 13:33:49'),
(105, 0, '', '', 'Tough times don\'t last, tough people do. Together in this Claire :)', 1, '2020-10-19 13:36:41', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:36:41', '2020-10-19 13:36:41'),
(106, 0, '', '', 'Glad you liked it. Keep sharing!', 1, '2020-10-19 13:37:02', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:37:02', '2020-10-19 13:37:02'),
(107, 0, '', '', 'Indeed Claire, please let us know if we could be of any help.', 1, '2020-10-19 13:37:44', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:37:44', '2020-10-19 13:37:44'),
(108, 0, '', '', 'Sure Claire, Keep an eye on this space.', 1, '2020-10-19 13:38:14', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:38:14', '2020-10-19 13:38:14'),
(109, 0, '', '', 'Thanks Claire! Very much appreciate it.', 1, '2020-10-19 13:38:42', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:38:42', '2020-10-19 13:38:42'),
(110, 0, '', '', 'Hi Claire. You can reach out to us at yokartchef@dummyid.com', 1, '2020-10-19 13:41:11', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:41:11', '2020-10-19 13:41:11'),
(111, 0, '', '', 'We have listed out our features on the website https://www.yo-kart.com/ . You can also choose to get in touch with us at yokartchef@dummyid.com :)', 1, '2020-10-19 13:42:31', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:42:31', '2020-10-19 13:42:31'),
(112, 0, '', '', '100 percent! Eager to see you on this side too :)', 1, '2020-10-19 13:43:06', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:43:06', '2020-10-19 13:43:06'),
(113, 0, '', '', 'Hope is a big thing. We wish you all he best Sharjeel, may you thrive !', 1, '2020-10-19 13:44:02', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:44:02', '2020-10-19 13:44:02'),
(114, 0, '', '', 'Very much appreciate it. Regards!', 1, '2020-10-19 13:44:29', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:44:29', '2020-10-19 13:44:29'),
(115, 0, '', '', 'Yes! Thanks for being here Sharjeel :)', 1, '2020-10-19 13:45:16', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:45:16', '2020-10-19 13:45:16'),
(116, 0, '', '', 'Words of appreciation! We\'re so grateful Sharjeel. Thanks!', 1, '2020-10-19 13:46:14', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:46:14', '2020-10-19 13:46:14'),
(117, 0, '', '', 'Thanks for being here Sharjeel. We wish you nothing but success !', 1, '2020-10-19 13:47:56', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:47:56', '2020-10-19 13:47:56'),
(118, 0, '', '', 'Please checkout more of our articles. Cheers!', 1, '2020-10-19 13:49:16', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:49:16', '2020-10-19 13:49:16'),
(119, 0, '', '', 'Thankyou Sharjeel! :)', 1, '2020-10-19 13:49:44', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:49:44', '2020-10-19 13:49:44'),
(120, 0, '', '', 'Cheers!', 1, '2020-10-19 13:50:18', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:50:18', '2020-10-19 13:50:18'),
(121, 0, '', '', 'Glad to be of help Misha! Big Thanks TO YOU !', 1, '2020-10-19 13:50:50', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:50:50', '2020-10-19 13:50:50'),
(122, 0, '', '', 'Looking forward to it Misha. Way and above!', 1, '2020-10-19 13:51:31', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:51:31', '2020-10-19 13:51:31'),
(123, 0, '', '', 'Thanks for you interest Misha! Our team with get in touch with you soon. Meanwhile you can reach out to us at yokartchef@dummyid.com. cheers!', 1, '2020-10-19 13:54:59', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 13:54:59', '2020-10-19 13:54:59'),
(132, 9, '', '', 'Thanks for sharing this Yokart! Very helpful!', 1, '2020-10-19 13:59:02', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 0, 1, '0000-00-00 00:00:00', '2020-10-19 13:59:50'),
(133, 0, '', '', 'Glad you loved it ! Keep visiting us for more such posts related to ecommerce and startups 🙂 Cheers !', 1, '2020-10-19 14:01:50', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 14:01:50', '2020-10-19 14:01:50'),
(134, 0, '', '', 'Que bom que você fez, amigo .. Cheers!', 1, '2020-10-19 14:02:53', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 14:02:53', '2020-10-19 14:02:53'),
(135, 0, '', '', 'Good to hear that Misha! Keep an eye on this pace. Alots more coming up :)', 1, '2020-10-19 14:07:39', '112.196.26.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36', 1, 1, '2020-10-19 14:07:39', '2020-10-19 14:07:39');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
