-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2021 at 07:41 AM
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
-- Dumping data for table `yokart_product_to_categories`
--

INSERT INTO `yokart_product_to_categories` (`ptc_prod_id`, `ptc_cat_id`) VALUES
(1, 9),
(2, 9),
(3, 9),
(4, 9),
(5, 9),
(6, 9),
(7, 9),
(8, 9),
(9, 9),
(10, 9),
(11, 9),
(12, 9),
(13, 9),
(14, 9),
(15, 9),
(16, 4),
(17, 4),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 4),
(24, 4),
(25, 4),
(26, 4),
(27, 4),
(28, 4),
(29, 4),
(30, 4),
(31, 5),
(32, 5),
(33, 5),
(34, 5),
(35, 5),
(36, 5),
(37, 5),
(38, 5),
(39, 5),
(40, 5),
(41, 5),
(42, 5),
(43, 5),
(44, 5),
(45, 5),
(46, 7),
(47, 7),
(48, 7),
(49, 7),
(50, 7),
(51, 7),
(52, 7),
(53, 7),
(54, 7),
(55, 7),
(56, 7),
(57, 7),
(58, 7),
(59, 7),
(60, 7),
(61, 14),
(62, 14),
(63, 14),
(64, 14),
(65, 14),
(66, 14),
(67, 14),
(68, 14),
(69, 14),
(70, 14),
(71, 14),
(72, 14),
(73, 14),
(74, 14),
(75, 14),
(76, 16),
(77, 16),
(78, 16),
(79, 16),
(80, 16),
(81, 16),
(82, 16),
(83, 16),
(84, 16),
(85, 16),
(86, 16),
(87, 16),
(88, 16),
(89, 16),
(90, 16),
(91, 17),
(92, 17),
(93, 17),
(94, 17),
(95, 17),
(96, 17),
(97, 17),
(98, 17),
(99, 17),
(100, 17),
(101, 17),
(102, 17),
(103, 17),
(104, 17),
(105, 17),
(106, 18),
(107, 18),
(108, 18),
(109, 18),
(110, 18),
(111, 18),
(112, 18),
(113, 18),
(114, 18),
(115, 18),
(116, 18),
(117, 18),
(118, 18),
(119, 18),
(120, 18),
(121, 22),
(122, 22),
(123, 22),
(124, 22),
(125, 22),
(126, 22),
(127, 22),
(128, 22),
(129, 22),
(130, 22),
(131, 22),
(132, 22),
(133, 22),
(134, 22),
(135, 22),
(136, 23),
(137, 23),
(138, 23),
(139, 23),
(140, 23),
(141, 23),
(142, 23),
(143, 23),
(144, 23),
(145, 23),
(146, 23),
(147, 23),
(148, 23),
(149, 23),
(150, 23),
(151, 24),
(152, 24),
(153, 24),
(154, 24),
(155, 24),
(156, 24),
(157, 24),
(158, 24),
(159, 24),
(160, 24),
(161, 24),
(162, 24),
(163, 24),
(164, 24),
(165, 24),
(166, 10),
(167, 10),
(168, 10),
(169, 10),
(170, 10),
(171, 10),
(172, 10),
(173, 10),
(174, 10),
(175, 10),
(176, 10),
(177, 10),
(178, 10),
(179, 10),
(180, 10),
(181, 26),
(182, 26),
(183, 26),
(184, 26),
(185, 26),
(186, 26),
(187, 26),
(188, 26),
(189, 26),
(190, 26),
(191, 26),
(192, 26),
(193, 26),
(194, 26),
(195, 26),
(196, 27),
(197, 27),
(198, 27),
(199, 27),
(200, 27),
(201, 27),
(202, 27),
(203, 27),
(204, 27),
(205, 27),
(206, 27),
(207, 27),
(208, 27),
(209, 27),
(210, 27),
(211, 28),
(212, 28),
(213, 28),
(214, 28),
(215, 28),
(216, 28),
(217, 28),
(218, 28),
(219, 28),
(220, 28),
(221, 28),
(222, 28),
(223, 28),
(224, 28),
(225, 28),
(226, 31),
(227, 31),
(228, 31),
(229, 31),
(230, 31),
(231, 31),
(232, 31),
(233, 31),
(234, 31),
(235, 31),
(236, 31),
(237, 31),
(238, 31),
(239, 31),
(240, 31),
(241, 33),
(242, 33),
(243, 33),
(244, 33),
(245, 33),
(246, 33),
(247, 33),
(248, 33),
(249, 33),
(250, 33),
(251, 33),
(252, 33),
(253, 33),
(254, 33),
(255, 33),
(256, 34),
(257, 34),
(258, 34),
(259, 34),
(260, 34),
(261, 34),
(262, 34),
(263, 34),
(264, 34),
(265, 34),
(266, 34),
(267, 34),
(268, 34),
(269, 34),
(270, 34),
(271, 35),
(272, 35),
(273, 35),
(274, 35),
(275, 35),
(276, 35),
(277, 35),
(278, 35),
(279, 35),
(280, 35),
(281, 35),
(282, 35),
(283, 35),
(284, 35),
(285, 35),
(286, 36),
(287, 36),
(288, 36),
(289, 36),
(290, 36),
(291, 36),
(292, 36),
(293, 36),
(294, 36),
(295, 36),
(296, 36),
(297, 36),
(298, 36),
(299, 36),
(300, 36),
(301, 22),
(302, 22),
(303, 22),
(304, 22),
(305, 31),
(306, 31),
(307, 31),
(308, 31),
(309, 26),
(310, 26),
(311, 26),
(312, 26),
(314, 27),
(315, 27),
(316, 27),
(317, 27);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
