-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2021 at 02:56 PM
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
-- Dumping data for table `yokart_related_products`
--

INSERT INTO `yokart_related_products` (`related_product_id`, `related_recommend_product_id`, `related_created_at`) VALUES
(300, 291, '2021-03-05 07:18:27'),
(300, 294, '2021-03-05 07:18:27'),
(300, 286, '2021-03-05 07:18:27'),
(299, 292, '2021-03-05 07:18:27'),
(299, 298, '2021-03-05 07:18:27'),
(299, 296, '2021-03-05 07:18:27'),
(298, 290, '2021-03-05 07:18:27'),
(298, 287, '2021-03-05 07:18:27'),
(298, 292, '2021-03-05 07:18:27'),
(297, 294, '2021-03-05 07:18:27'),
(297, 291, '2021-03-05 07:18:27'),
(297, 286, '2021-03-05 07:18:27'),
(296, 293, '2021-03-05 07:18:27'),
(296, 300, '2021-03-05 07:18:27'),
(296, 289, '2021-03-05 07:18:27'),
(295, 299, '2021-03-05 07:18:27'),
(295, 288, '2021-03-05 07:18:27'),
(295, 286, '2021-03-05 07:18:27'),
(294, 300, '2021-03-05 07:18:27'),
(294, 286, '2021-03-05 07:18:27'),
(294, 297, '2021-03-05 07:18:27'),
(293, 287, '2021-03-05 07:18:27'),
(293, 289, '2021-03-05 07:18:27'),
(293, 288, '2021-03-05 07:18:27'),
(292, 299, '2021-03-05 07:18:27'),
(292, 287, '2021-03-05 07:18:27'),
(292, 290, '2021-03-05 07:18:27'),
(291, 286, '2021-03-05 07:18:27'),
(291, 297, '2021-03-05 07:18:27'),
(291, 300, '2021-03-05 07:18:27'),
(290, 292, '2021-03-05 07:18:27'),
(290, 298, '2021-03-05 07:18:27'),
(290, 299, '2021-03-05 07:18:27'),
(289, 287, '2021-03-05 07:18:27'),
(289, 292, '2021-03-05 07:18:27'),
(289, 296, '2021-03-05 07:18:27'),
(288, 293, '2021-03-05 07:18:27'),
(288, 298, '2021-03-05 07:18:27'),
(288, 292, '2021-03-05 07:18:27'),
(287, 299, '2021-03-05 07:18:27'),
(287, 292, '2021-03-05 07:18:27'),
(287, 296, '2021-03-05 07:18:27'),
(286, 291, '2021-03-05 07:18:27'),
(286, 295, '2021-03-05 07:18:27'),
(286, 297, '2021-03-05 07:18:27'),
(285, 283, '2021-03-05 07:18:27'),
(285, 273, '2021-03-05 07:18:27'),
(285, 279, '2021-03-05 07:18:27'),
(284, 281, '2021-03-05 07:18:27'),
(284, 271, '2021-03-05 07:18:27'),
(284, 274, '2021-03-05 07:18:27'),
(283, 280, '2021-03-05 07:18:27'),
(283, 272, '2021-03-05 07:18:27'),
(283, 277, '2021-03-05 07:18:27'),
(282, 281, '2021-03-05 07:18:27'),
(282, 285, '2021-03-05 07:18:27'),
(282, 276, '2021-03-05 07:18:27'),
(281, 275, '2021-03-05 07:18:27'),
(281, 272, '2021-03-05 07:18:27'),
(281, 279, '2021-03-05 07:18:27'),
(280, 272, '2021-03-05 07:18:27'),
(280, 277, '2021-03-05 07:18:27'),
(279, 271, '2021-03-05 07:18:27'),
(279, 282, '2021-03-05 07:18:27'),
(279, 284, '2021-03-05 07:18:27'),
(278, 276, '2021-03-05 07:18:27'),
(278, 273, '2021-03-05 07:18:27'),
(278, 274, '2021-03-05 07:18:27'),
(277, 280, '2021-03-05 07:18:27'),
(277, 272, '2021-03-05 07:18:27'),
(277, 283, '2021-03-05 07:18:27'),
(276, 274, '2021-03-05 07:18:27'),
(276, 271, '2021-03-05 07:18:27'),
(276, 280, '2021-03-05 07:18:27'),
(275, 273, '2021-03-05 07:18:27'),
(275, 285, '2021-03-05 07:18:27'),
(275, 282, '2021-03-05 07:18:27'),
(274, 278, '2021-03-05 07:18:27'),
(274, 284, '2021-03-05 07:18:27'),
(274, 281, '2021-03-05 07:18:27'),
(273, 272, '2021-03-05 07:18:27'),
(273, 278, '2021-03-05 07:18:27'),
(273, 279, '2021-03-05 07:18:27'),
(272, 275, '2021-03-05 07:18:27'),
(272, 274, '2021-03-05 07:18:27'),
(272, 283, '2021-03-05 07:18:27'),
(271, 275, '2021-03-05 07:18:27'),
(271, 274, '2021-03-05 07:18:27'),
(271, 284, '2021-03-05 07:18:27'),
(270, 261, '2021-03-05 07:18:27'),
(270, 260, '2021-03-05 07:18:27'),
(270, 269, '2021-03-05 07:18:27'),
(268, 267, '2021-03-05 07:18:27'),
(268, 264, '2021-03-05 07:18:27'),
(268, 266, '2021-03-05 07:18:27'),
(269, 270, '2021-03-05 07:18:27'),
(269, 260, '2021-03-05 07:18:27'),
(269, 259, '2021-03-05 07:18:27'),
(267, 268, '2021-03-05 07:18:27'),
(267, 266, '2021-03-05 07:18:27'),
(267, 262, '2021-03-05 07:18:27'),
(266, 262, '2021-03-05 07:18:27'),
(266, 268, '2021-03-05 07:18:27'),
(266, 267, '2021-03-05 07:18:27'),
(265, 262, '2021-03-05 07:18:27'),
(265, 258, '2021-03-05 07:18:27'),
(265, 256, '2021-03-05 07:18:27'),
(264, 267, '2021-03-05 07:18:27'),
(264, 257, '2021-03-05 07:18:27'),
(264, 260, '2021-03-05 07:18:27'),
(263, 262, '2021-03-05 07:18:27'),
(263, 256, '2021-03-05 07:18:27'),
(263, 257, '2021-03-05 07:18:27'),
(262, 265, '2021-03-05 07:18:27'),
(262, 261, '2021-03-05 07:18:27'),
(262, 258, '2021-03-05 07:18:27'),
(261, 260, '2021-03-05 07:18:27'),
(261, 259, '2021-03-05 07:18:27'),
(261, 269, '2021-03-05 07:18:27'),
(260, 259, '2021-03-05 07:18:27'),
(260, 270, '2021-03-05 07:18:27'),
(260, 261, '2021-03-05 07:18:27'),
(259, 260, '2021-03-05 07:18:27'),
(259, 258, '2021-03-05 07:18:27'),
(259, 261, '2021-03-05 07:18:27'),
(258, 256, '2021-03-05 07:18:27'),
(258, 263, '2021-03-05 07:18:27'),
(258, 262, '2021-03-05 07:18:27'),
(257, 256, '2021-03-05 07:18:27'),
(257, 263, '2021-03-05 07:18:27'),
(257, 266, '2021-03-05 07:18:27'),
(256, 257, '2021-03-05 07:18:27'),
(256, 262, '2021-03-05 07:18:27'),
(256, 263, '2021-03-05 07:18:27'),
(255, 254, '2021-03-05 07:18:27'),
(255, 243, '2021-03-05 07:18:27'),
(255, 249, '2021-03-05 07:18:27'),
(254, 250, '2021-03-05 07:18:27'),
(254, 246, '2021-03-05 07:18:27'),
(254, 243, '2021-03-05 07:18:27'),
(253, 252, '2021-03-05 07:18:27'),
(253, 251, '2021-03-05 07:18:27'),
(253, 249, '2021-03-05 07:18:27'),
(252, 251, '2021-03-05 07:18:27'),
(252, 253, '2021-03-05 07:18:27'),
(252, 248, '2021-03-05 07:18:27'),
(251, 252, '2021-03-05 07:18:27'),
(251, 253, '2021-03-05 07:18:27'),
(251, 249, '2021-03-05 07:18:27'),
(250, 247, '2021-03-05 07:18:27'),
(250, 246, '2021-03-05 07:18:27'),
(250, 254, '2021-03-05 07:18:27'),
(249, 247, '2021-03-05 07:18:27'),
(249, 251, '2021-03-05 07:18:27'),
(249, 253, '2021-03-05 07:18:27'),
(248, 247, '2021-03-05 07:18:27'),
(248, 249, '2021-03-05 07:18:27'),
(248, 242, '2021-03-05 07:18:27'),
(246, 243, '2021-03-05 07:18:27'),
(246, 241, '2021-03-05 07:18:27'),
(246, 250, '2021-03-05 07:18:27'),
(245, 248, '2021-03-05 07:18:27'),
(245, 251, '2021-03-05 07:18:27'),
(245, 242, '2021-03-05 07:18:27'),
(244, 242, '2021-03-05 07:18:27'),
(244, 248, '2021-03-05 07:18:27'),
(244, 249, '2021-03-05 07:18:27'),
(243, 241, '2021-03-05 07:18:27'),
(243, 254, '2021-03-05 07:18:27'),
(243, 246, '2021-03-05 07:18:27'),
(242, 245, '2021-03-05 07:18:27'),
(242, 248, '2021-03-05 07:18:27'),
(242, 247, '2021-03-05 07:18:27'),
(241, 250, '2021-03-05 07:18:27'),
(241, 243, '2021-03-05 07:18:27'),
(241, 246, '2021-03-05 07:18:27'),
(240, 233, '2021-03-05 07:18:27'),
(240, 228, '2021-03-05 07:18:27'),
(240, 227, '2021-03-05 07:18:27'),
(239, 236, '2021-03-05 07:18:27'),
(239, 234, '2021-03-05 07:18:27'),
(239, 229, '2021-03-05 07:18:27'),
(238, 232, '2021-03-05 07:18:27'),
(238, 227, '2021-03-05 07:18:27'),
(238, 235, '2021-03-05 07:18:27'),
(237, 231, '2021-03-05 07:18:27'),
(237, 229, '2021-03-05 07:18:27'),
(237, 226, '2021-03-05 07:18:27'),
(236, 235, '2021-03-05 07:18:27'),
(236, 227, '2021-03-05 07:18:27'),
(236, 229, '2021-03-05 07:18:27'),
(235, 238, '2021-03-05 07:18:27'),
(235, 232, '2021-03-05 07:18:27'),
(235, 230, '2021-03-05 07:18:27'),
(234, 231, '2021-03-05 07:18:27'),
(234, 227, '2021-03-05 07:18:27'),
(234, 239, '2021-03-05 07:18:27'),
(233, 240, '2021-03-05 07:18:27'),
(233, 230, '2021-03-05 07:18:27'),
(233, 228, '2021-03-05 07:18:27'),
(232, 238, '2021-03-05 07:18:27'),
(232, 227, '2021-03-05 07:18:27'),
(232, 235, '2021-03-05 07:18:27'),
(231, 237, '2021-03-05 07:18:27'),
(231, 229, '2021-03-05 07:18:27'),
(231, 226, '2021-03-05 07:18:27'),
(230, 228, '2021-03-05 07:18:27'),
(230, 227, '2021-03-05 07:18:27'),
(230, 233, '2021-03-05 07:18:27'),
(229, 226, '2021-03-05 07:18:27'),
(229, 234, '2021-03-05 07:18:27'),
(229, 237, '2021-03-05 07:18:27'),
(228, 230, '2021-03-05 07:18:27'),
(228, 233, '2021-03-05 07:18:27'),
(228, 240, '2021-03-05 07:18:27'),
(227, 229, '2021-03-05 07:18:27'),
(227, 239, '2021-03-05 07:18:27'),
(227, 234, '2021-03-05 07:18:27'),
(226, 237, '2021-03-05 07:18:27'),
(226, 234, '2021-03-05 07:18:27'),
(226, 239, '2021-03-05 07:18:27'),
(224, 222, '2021-03-05 07:18:27'),
(224, 214, '2021-03-05 07:18:27'),
(224, 217, '2021-03-05 07:18:27'),
(223, 221, '2021-03-05 07:18:27'),
(223, 213, '2021-03-05 07:18:27'),
(223, 216, '2021-03-05 07:18:27'),
(222, 224, '2021-03-05 07:18:27'),
(222, 214, '2021-03-05 07:18:27'),
(222, 217, '2021-03-05 07:18:27'),
(221, 218, '2021-03-05 07:18:27'),
(221, 219, '2021-03-05 07:18:27'),
(221, 212, '2021-03-05 07:18:27'),
(220, 223, '2021-03-05 07:18:27'),
(220, 225, '2021-03-05 07:18:27'),
(220, 212, '2021-03-05 07:18:27'),
(219, 221, '2021-03-05 07:18:27'),
(219, 220, '2021-03-05 07:18:27'),
(219, 212, '2021-03-05 07:18:27'),
(218, 221, '2021-03-05 07:18:27'),
(218, 216, '2021-03-05 07:18:27'),
(218, 213, '2021-03-05 07:18:27'),
(217, 214, '2021-03-05 07:18:27'),
(217, 211, '2021-03-05 07:18:27'),
(217, 225, '2021-03-05 07:18:27'),
(216, 218, '2021-03-05 07:18:27'),
(216, 212, '2021-03-05 07:18:27'),
(216, 219, '2021-03-05 07:18:27'),
(215, 214, '2021-03-05 07:18:27'),
(215, 220, '2021-03-05 07:18:27'),
(215, 223, '2021-03-05 07:18:27'),
(214, 211, '2021-03-05 07:18:27'),
(214, 217, '2021-03-05 07:18:27'),
(214, 220, '2021-03-05 07:18:27'),
(213, 220, '2021-03-05 07:18:27'),
(213, 212, '2021-03-05 07:18:27'),
(213, 223, '2021-03-05 07:18:27'),
(212, 220, '2021-03-05 07:18:27'),
(212, 224, '2021-03-05 07:18:27'),
(212, 223, '2021-03-05 07:18:27'),
(211, 215, '2021-03-05 07:18:27'),
(211, 217, '2021-03-05 07:18:27'),
(211, 225, '2021-03-05 07:18:27'),
(210, 206, '2021-03-05 07:18:27'),
(210, 198, '2021-03-05 07:18:27'),
(210, 202, '2021-03-05 07:18:27'),
(209, 205, '2021-03-05 07:18:27'),
(209, 200, '2021-03-05 07:18:27'),
(209, 207, '2021-03-05 07:18:27'),
(208, 205, '2021-03-05 07:18:27'),
(208, 199, '2021-03-05 07:18:27'),
(208, 207, '2021-03-05 07:18:27'),
(207, 202, '2021-03-05 07:18:27'),
(207, 201, '2021-03-05 07:18:27'),
(207, 203, '2021-03-05 07:18:27'),
(206, 210, '2021-03-05 07:18:27'),
(206, 204, '2021-03-05 07:18:27'),
(206, 198, '2021-03-05 07:18:27'),
(205, 209, '2021-03-05 07:18:27'),
(205, 207, '2021-03-05 07:18:27'),
(205, 199, '2021-03-05 07:18:27'),
(204, 206, '2021-03-05 07:18:27'),
(204, 210, '2021-03-05 07:18:27'),
(204, 196, '2021-03-05 07:18:27'),
(203, 201, '2021-03-05 07:18:27'),
(203, 200, '2021-03-05 07:18:27'),
(203, 197, '2021-03-05 07:18:27'),
(202, 204, '2021-03-05 07:18:27'),
(202, 206, '2021-03-05 07:18:27'),
(202, 210, '2021-03-05 07:18:27'),
(201, 203, '2021-03-05 07:18:27'),
(201, 199, '2021-03-05 07:18:27'),
(201, 200, '2021-03-05 07:18:27'),
(200, 199, '2021-03-05 07:18:27'),
(200, 197, '2021-03-05 07:18:27'),
(200, 201, '2021-03-05 07:18:27'),
(199, 203, '2021-03-05 07:18:27'),
(199, 208, '2021-03-05 07:18:27'),
(199, 197, '2021-03-05 07:18:27'),
(198, 196, '2021-03-05 07:18:27'),
(198, 204, '2021-03-05 07:18:27'),
(198, 206, '2021-03-05 07:18:27'),
(197, 200, '2021-03-05 07:18:27'),
(197, 199, '2021-03-05 07:18:27'),
(197, 203, '2021-03-05 07:18:27'),
(196, 198, '2021-03-05 07:18:27'),
(196, 206, '2021-03-05 07:18:27'),
(196, 210, '2021-03-05 07:18:27'),
(195, 189, '2021-03-05 07:18:27'),
(195, 187, '2021-03-05 07:18:27'),
(195, 181, '2021-03-05 07:18:27'),
(194, 185, '2021-03-05 07:18:27'),
(194, 192, '2021-03-05 07:18:27'),
(194, 195, '2021-03-05 07:18:27'),
(193, 191, '2021-03-05 07:18:27'),
(193, 190, '2021-03-05 07:18:27'),
(193, 183, '2021-03-05 07:18:27'),
(192, 195, '2021-03-05 07:18:27'),
(192, 189, '2021-03-05 07:18:27'),
(192, 187, '2021-03-05 07:18:27'),
(191, 193, '2021-03-05 07:18:27'),
(191, 190, '2021-03-05 07:18:27'),
(191, 186, '2021-03-05 07:18:27'),
(190, 184, '2021-03-05 07:18:27'),
(190, 183, '2021-03-05 07:18:27'),
(190, 186, '2021-03-05 07:18:27'),
(189, 194, '2021-03-05 07:18:27'),
(189, 195, '2021-03-05 07:18:27'),
(189, 192, '2021-03-05 07:18:27'),
(188, 183, '2021-03-05 07:18:27'),
(188, 182, '2021-03-05 07:18:27'),
(188, 184, '2021-03-05 07:18:27'),
(187, 189, '2021-03-05 07:18:27'),
(187, 181, '2021-03-05 07:18:27'),
(187, 192, '2021-03-05 07:18:27'),
(186, 188, '2021-03-05 07:18:27'),
(186, 185, '2021-03-05 07:18:27'),
(186, 190, '2021-03-05 07:18:27'),
(185, 181, '2021-03-05 07:18:27'),
(185, 194, '2021-03-05 07:18:27'),
(185, 195, '2021-03-05 07:18:27'),
(184, 182, '2021-03-05 07:18:27'),
(184, 190, '2021-03-05 07:18:27'),
(184, 191, '2021-03-05 07:18:27'),
(183, 181, '2021-03-05 07:18:27'),
(183, 193, '2021-03-05 07:18:27'),
(183, 182, '2021-03-05 07:18:27'),
(182, 184, '2021-03-05 07:18:27'),
(182, 188, '2021-03-05 07:18:27'),
(182, 191, '2021-03-05 07:18:27'),
(181, 187, '2021-03-05 07:18:27'),
(181, 189, '2021-03-05 07:18:27'),
(181, 192, '2021-03-05 07:18:27'),
(154, 165, '2021-03-05 07:18:27'),
(154, 151, '2021-03-05 07:18:27'),
(154, 164, '2021-03-05 07:18:27'),
(164, 154, '2021-03-05 07:18:27'),
(164, 163, '2021-03-05 07:18:27'),
(164, 162, '2021-03-05 07:18:27'),
(163, 161, '2021-03-05 07:18:27'),
(163, 159, '2021-03-05 07:18:27'),
(163, 153, '2021-03-05 07:18:27'),
(162, 160, '2021-03-05 07:18:27'),
(162, 159, '2021-03-05 07:18:27'),
(162, 156, '2021-03-05 07:18:27'),
(161, 154, '2021-03-05 07:18:27'),
(161, 159, '2021-03-05 07:18:27'),
(161, 155, '2021-03-05 07:18:27'),
(160, 159, '2021-03-05 07:18:27'),
(160, 156, '2021-03-05 07:18:27'),
(160, 162, '2021-03-05 07:18:27'),
(159, 160, '2021-03-05 07:18:27'),
(159, 162, '2021-03-05 07:18:27'),
(159, 161, '2021-03-05 07:18:27'),
(158, 152, '2021-03-05 07:18:27'),
(158, 164, '2021-03-05 07:18:27'),
(158, 153, '2021-03-05 07:18:27'),
(157, 156, '2021-03-05 07:18:27'),
(157, 155, '2021-03-05 07:18:27'),
(157, 160, '2021-03-05 07:18:27'),
(156, 155, '2021-03-05 07:18:27'),
(156, 152, '2021-03-05 07:18:27'),
(156, 162, '2021-03-05 07:18:27'),
(155, 152, '2021-03-05 07:18:27'),
(155, 157, '2021-03-05 07:18:27'),
(155, 160, '2021-03-05 07:18:27'),
(165, 163, '2021-03-05 07:18:27'),
(165, 161, '2021-03-05 07:18:27'),
(165, 164, '2021-03-05 07:18:27'),
(153, 165, '2021-03-05 07:18:27'),
(153, 158, '2021-03-05 07:18:27'),
(153, 164, '2021-03-05 07:18:27'),
(151, 152, '2021-03-05 07:18:27'),
(151, 155, '2021-03-05 07:18:27'),
(151, 161, '2021-03-05 07:18:27'),
(120, 110, '2021-03-05 07:18:27'),
(120, 106, '2021-03-05 07:18:27'),
(120, 109, '2021-03-05 07:18:27'),
(119, 107, '2021-03-05 07:18:27'),
(119, 118, '2021-03-05 07:18:27'),
(119, 104, '2021-03-05 07:18:27'),
(118, 111, '2021-03-05 07:18:27'),
(118, 109, '2021-03-05 07:18:27'),
(118, 103, '2021-03-05 07:18:27'),
(117, 114, '2021-03-05 07:18:27'),
(117, 113, '2021-03-05 07:18:27'),
(117, 109, '2021-03-05 07:18:27'),
(116, 112, '2021-03-05 07:18:27'),
(116, 115, '2021-03-05 07:18:27'),
(116, 111, '2021-03-05 07:18:27'),
(114, 117, '2021-03-05 07:18:27'),
(114, 113, '2021-03-05 07:18:27'),
(114, 109, '2021-03-05 07:18:27'),
(113, 114, '2021-03-05 07:18:27'),
(113, 117, '2021-03-05 07:18:27'),
(113, 109, '2021-03-05 07:18:27'),
(111, 118, '2021-03-05 07:18:27'),
(111, 109, '2021-03-05 07:18:27'),
(111, 106, '2021-03-05 07:18:27'),
(110, 102, '2021-03-05 07:18:27'),
(110, 120, '2021-03-05 07:18:27'),
(110, 115, '2021-03-05 07:18:27'),
(109, 105, '2021-03-05 07:18:27'),
(109, 117, '2021-03-05 07:18:27'),
(109, 113, '2021-03-05 07:18:27'),
(108, 86, '2021-03-05 07:18:27'),
(108, 81, '2021-03-05 07:18:27'),
(108, 85, '2021-03-05 07:18:27'),
(107, 105, '2021-03-05 07:18:27'),
(107, 119, '2021-03-05 07:18:27'),
(107, 102, '2021-03-05 07:18:27'),
(106, 89, '2021-03-05 07:18:27'),
(106, 109, '2021-03-05 07:18:27'),
(106, 112, '2021-03-05 07:18:27'),
(105, 117, '2021-03-05 07:18:27'),
(105, 103, '2021-03-05 07:18:27'),
(105, 104, '2021-03-05 07:18:27'),
(103, 102, '2021-03-05 07:18:27'),
(103, 96, '2021-03-05 07:18:27'),
(103, 91, '2021-03-05 07:18:27'),
(102, 98, '2021-03-05 07:18:27'),
(102, 94, '2021-03-05 07:18:27'),
(102, 93, '2021-03-05 07:18:27'),
(101, 91, '2021-03-05 07:18:27'),
(101, 100, '2021-03-05 07:18:27'),
(101, 103, '2021-03-05 07:18:27'),
(100, 96, '2021-03-05 07:18:27'),
(100, 99, '2021-03-05 07:18:27'),
(100, 103, '2021-03-05 07:18:27'),
(99, 94, '2021-03-05 07:18:27'),
(99, 92, '2021-03-05 07:18:27'),
(99, 98, '2021-03-05 07:18:27'),
(98, 93, '2021-03-05 07:18:27'),
(98, 92, '2021-03-05 07:18:27'),
(98, 104, '2021-03-05 07:18:27'),
(104, 103, '2021-03-05 07:18:27'),
(104, 102, '2021-03-05 07:18:27'),
(104, 93, '2021-03-05 07:18:27'),
(97, 95, '2021-03-05 07:18:27'),
(97, 93, '2021-03-05 07:18:27'),
(97, 98, '2021-03-05 07:18:27'),
(95, 98, '2021-03-05 07:18:27'),
(95, 105, '2021-03-05 07:18:27'),
(95, 93, '2021-03-05 07:18:27'),
(94, 92, '2021-03-05 07:18:27'),
(94, 105, '2021-03-05 07:18:27'),
(94, 104, '2021-03-05 07:18:27'),
(93, 97, '2021-03-05 07:18:27'),
(93, 95, '2021-03-05 07:18:27'),
(93, 96, '2021-03-05 07:18:27'),
(92, 94, '2021-03-05 07:18:27'),
(92, 91, '2021-03-05 07:18:27'),
(92, 97, '2021-03-05 07:18:27'),
(91, 92, '2021-03-05 07:18:27'),
(91, 100, '2021-03-05 07:18:27'),
(91, 103, '2021-03-05 07:18:27'),
(90, 87, '2021-03-05 07:18:27'),
(90, 78, '2021-03-05 07:18:27'),
(90, 76, '2021-03-05 07:18:27'),
(89, 84, '2021-03-05 07:18:27'),
(89, 82, '2021-03-05 07:18:27'),
(89, 83, '2021-03-05 07:18:27'),
(88, 83, '2021-03-05 07:18:27'),
(88, 80, '2021-03-05 07:18:27'),
(88, 76, '2021-03-05 07:18:27'),
(87, 85, '2021-03-05 07:18:27'),
(87, 81, '2021-03-05 07:18:27'),
(87, 78, '2021-03-05 07:18:27'),
(86, 78, '2021-03-05 07:18:27'),
(86, 76, '2021-03-05 07:18:27'),
(86, 79, '2021-03-05 07:18:27'),
(85, 87, '2021-03-05 07:18:27'),
(85, 83, '2021-03-05 07:18:27'),
(85, 78, '2021-03-05 07:18:27'),
(84, 82, '2021-03-05 07:18:27'),
(84, 77, '2021-03-05 07:18:27'),
(84, 80, '2021-03-05 07:18:27'),
(83, 82, '2021-03-05 07:18:27'),
(83, 77, '2021-03-05 07:18:27'),
(83, 84, '2021-03-05 07:18:27'),
(82, 83, '2021-03-05 07:18:27'),
(82, 77, '2021-03-05 07:18:27'),
(82, 76, '2021-03-05 07:18:27'),
(81, 88, '2021-03-05 07:18:27'),
(81, 80, '2021-03-05 07:18:27'),
(81, 79, '2021-03-05 07:18:27'),
(80, 78, '2021-03-05 07:18:27'),
(80, 76, '2021-03-05 07:18:27'),
(80, 88, '2021-03-05 07:18:27'),
(79, 90, '2021-03-05 07:18:27'),
(79, 86, '2021-03-05 07:18:27'),
(79, 88, '2021-03-05 07:18:27'),
(78, 76, '2021-03-05 07:18:27'),
(78, 80, '2021-03-05 07:18:27'),
(78, 89, '2021-03-05 07:18:27'),
(77, 89, '2021-03-05 07:18:27'),
(77, 84, '2021-03-05 07:18:27'),
(77, 82, '2021-03-05 07:18:27'),
(76, 87, '2021-03-05 07:18:27'),
(76, 90, '2021-03-05 07:18:27'),
(76, 85, '2021-03-05 07:18:27'),
(75, 68, '2021-03-05 07:18:27'),
(75, 69, '2021-03-05 07:18:27'),
(75, 73, '2021-03-05 07:18:27'),
(74, 67, '2021-03-05 07:18:27'),
(74, 63, '2021-03-05 07:18:27'),
(74, 66, '2021-03-05 07:18:27'),
(73, 72, '2021-03-05 07:18:27'),
(73, 64, '2021-03-05 07:18:27'),
(73, 62, '2021-03-05 07:18:27'),
(72, 61, '2021-03-05 07:18:27'),
(72, 70, '2021-03-05 07:18:27'),
(72, 71, '2021-03-05 07:18:27'),
(71, 62, '2021-03-05 07:18:27'),
(71, 67, '2021-03-05 07:18:27'),
(71, 68, '2021-03-05 07:18:27'),
(70, 61, '2021-03-05 07:18:27'),
(70, 69, '2021-03-05 07:18:27'),
(70, 73, '2021-03-05 07:18:27'),
(69, 66, '2021-03-05 07:18:27'),
(69, 65, '2021-03-05 07:18:27'),
(69, 68, '2021-03-05 07:18:27'),
(68, 65, '2021-03-05 07:18:27'),
(68, 72, '2021-03-05 07:18:27'),
(68, 66, '2021-03-05 07:18:27'),
(67, 64, '2021-03-05 07:18:27'),
(67, 74, '2021-03-05 07:18:27'),
(67, 75, '2021-03-05 07:18:27'),
(66, 65, '2021-03-05 07:18:27'),
(66, 71, '2021-03-05 07:18:27'),
(66, 62, '2021-03-05 07:18:27'),
(65, 62, '2021-03-05 07:18:27'),
(65, 68, '2021-03-05 07:18:27'),
(65, 70, '2021-03-05 07:18:27'),
(64, 62, '2021-03-05 07:18:27'),
(64, 72, '2021-03-05 07:18:27'),
(64, 73, '2021-03-05 07:18:27'),
(63, 61, '2021-03-05 07:18:27'),
(63, 70, '2021-03-05 07:18:27'),
(63, 75, '2021-03-05 07:18:27'),
(62, 64, '2021-03-05 07:18:27'),
(62, 73, '2021-03-05 07:18:27'),
(62, 72, '2021-03-05 07:18:27'),
(61, 66, '2021-03-05 07:18:27'),
(61, 71, '2021-03-05 07:18:27'),
(61, 73, '2021-03-05 07:18:27'),
(60, 55, '2021-03-05 07:18:27'),
(60, 49, '2021-03-05 07:18:27'),
(60, 46, '2021-03-05 07:18:27'),
(59, 51, '2021-03-05 07:18:27'),
(59, 47, '2021-03-05 07:18:27'),
(59, 58, '2021-03-05 07:18:27'),
(58, 57, '2021-03-05 07:18:27'),
(58, 52, '2021-03-05 07:18:27'),
(58, 48, '2021-03-05 07:18:27'),
(57, 50, '2021-03-05 07:18:27'),
(57, 48, '2021-03-05 07:18:27'),
(57, 51, '2021-03-05 07:18:27'),
(56, 60, '2021-03-05 07:18:27'),
(56, 53, '2021-03-05 07:18:27'),
(56, 51, '2021-03-05 07:18:27'),
(55, 59, '2021-03-05 07:18:27'),
(55, 47, '2021-03-05 07:18:27'),
(55, 50, '2021-03-05 07:18:27'),
(54, 46, '2021-03-05 07:18:27'),
(54, 50, '2021-03-05 07:18:27'),
(54, 47, '2021-03-05 07:18:27'),
(53, 59, '2021-03-05 07:18:27'),
(53, 47, '2021-03-05 07:18:27'),
(53, 46, '2021-03-05 07:18:27'),
(52, 57, '2021-03-05 07:18:27'),
(52, 58, '2021-03-05 07:18:27'),
(52, 48, '2021-03-05 07:18:27'),
(51, 56, '2021-03-05 07:18:27'),
(51, 53, '2021-03-05 07:18:27'),
(51, 47, '2021-03-05 07:18:27'),
(50, 46, '2021-03-05 07:18:27'),
(50, 48, '2021-03-05 07:18:27'),
(50, 58, '2021-03-05 07:18:27'),
(49, 47, '2021-03-05 07:18:27'),
(49, 60, '2021-03-05 07:18:27'),
(49, 56, '2021-03-05 07:18:27'),
(48, 50, '2021-03-05 07:18:27'),
(48, 52, '2021-03-05 07:18:27'),
(48, 57, '2021-03-05 07:18:27'),
(47, 53, '2021-03-05 07:18:27'),
(47, 60, '2021-03-05 07:18:27'),
(47, 56, '2021-03-05 07:18:27'),
(46, 50, '2021-03-05 07:18:27'),
(46, 48, '2021-03-05 07:18:27'),
(46, 57, '2021-03-05 07:18:27'),
(45, 37, '2021-03-05 07:18:27'),
(45, 31, '2021-03-05 07:18:27'),
(45, 33, '2021-03-05 07:18:27'),
(44, 40, '2021-03-05 07:18:27'),
(44, 36, '2021-03-05 07:18:27'),
(44, 32, '2021-03-05 07:18:27'),
(43, 35, '2021-03-05 07:18:27'),
(43, 33, '2021-03-05 07:18:27'),
(43, 41, '2021-03-05 07:18:27'),
(42, 38, '2021-03-05 07:18:27'),
(42, 32, '2021-03-05 07:18:27'),
(42, 34, '2021-03-05 07:18:27'),
(41, 43, '2021-03-05 07:18:27'),
(41, 35, '2021-03-05 07:18:27'),
(41, 33, '2021-03-05 07:18:27'),
(40, 36, '2021-03-05 07:18:27'),
(40, 34, '2021-03-05 07:18:27'),
(40, 32, '2021-03-05 07:18:27'),
(39, 37, '2021-03-05 07:18:27'),
(39, 31, '2021-03-05 07:18:27'),
(39, 45, '2021-03-05 07:18:27'),
(38, 32, '2021-03-05 07:18:27'),
(38, 42, '2021-03-05 07:18:27'),
(38, 44, '2021-03-05 07:18:27'),
(37, 39, '2021-03-05 07:18:27'),
(37, 31, '2021-03-05 07:18:27'),
(37, 45, '2021-03-05 07:18:27'),
(36, 40, '2021-03-05 07:18:27'),
(36, 44, '2021-03-05 07:18:27'),
(36, 42, '2021-03-05 07:18:27'),
(35, 33, '2021-03-05 07:18:27'),
(35, 43, '2021-03-05 07:18:27'),
(35, 41, '2021-03-05 07:18:27'),
(34, 36, '2021-03-05 07:18:27'),
(34, 40, '2021-03-05 07:18:27'),
(34, 44, '2021-03-05 07:18:27'),
(33, 35, '2021-03-05 07:18:27'),
(33, 41, '2021-03-05 07:18:27'),
(33, 43, '2021-03-05 07:18:27'),
(32, 38, '2021-03-05 07:18:27'),
(32, 34, '2021-03-05 07:18:27'),
(32, 40, '2021-03-05 07:18:27'),
(31, 37, '2021-03-05 07:18:27'),
(31, 39, '2021-03-05 07:18:27'),
(31, 41, '2021-03-05 07:18:27'),
(30, 27, '2021-03-05 07:18:27'),
(30, 24, '2021-03-05 07:18:27'),
(30, 17, '2021-03-05 07:18:27'),
(29, 22, '2021-03-05 07:18:27'),
(29, 18, '2021-03-05 07:18:27'),
(29, 16, '2021-03-05 07:18:27'),
(28, 26, '2021-03-05 07:18:27'),
(28, 20, '2021-03-05 07:18:27'),
(28, 25, '2021-03-05 07:18:27'),
(27, 30, '2021-03-05 07:18:27'),
(27, 24, '2021-03-05 07:18:27'),
(27, 22, '2021-03-05 07:18:27'),
(26, 25, '2021-03-05 07:18:27'),
(26, 22, '2021-03-05 07:18:27'),
(26, 29, '2021-03-05 07:18:27'),
(25, 22, '2021-03-05 07:18:27'),
(25, 27, '2021-03-05 07:18:27'),
(25, 20, '2021-03-05 07:18:27'),
(24, 27, '2021-03-05 07:18:27'),
(24, 22, '2021-03-05 07:18:27'),
(24, 30, '2021-03-05 07:18:27'),
(23, 21, '2021-03-05 07:18:27'),
(23, 20, '2021-03-05 07:18:27'),
(23, 19, '2021-03-05 07:18:27'),
(22, 27, '2021-03-05 07:18:27'),
(22, 17, '2021-03-05 07:18:27'),
(22, 16, '2021-03-05 07:18:27'),
(21, 23, '2021-03-05 07:18:27'),
(21, 19, '2021-03-05 07:18:27'),
(21, 17, '2021-03-05 07:18:27'),
(20, 18, '2021-03-05 07:18:27'),
(20, 16, '2021-03-05 07:18:27'),
(20, 23, '2021-03-05 07:18:27'),
(19, 17, '2021-03-05 07:18:27'),
(19, 21, '2021-03-05 07:18:27'),
(19, 27, '2021-03-05 07:18:27'),
(18, 16, '2021-03-05 07:18:27'),
(18, 19, '2021-03-05 07:18:27'),
(18, 29, '2021-03-05 07:18:27'),
(17, 30, '2021-03-05 07:18:27'),
(17, 24, '2021-03-05 07:18:27'),
(17, 22, '2021-03-05 07:18:27'),
(16, 18, '2021-03-05 07:18:27'),
(16, 19, '2021-03-05 07:18:27'),
(16, 29, '2021-03-05 07:18:27'),
(15, 11, '2021-03-05 07:18:27'),
(15, 9, '2021-03-05 07:18:27'),
(15, 3, '2021-03-05 07:18:27'),
(14, 4, '2021-03-05 07:18:27'),
(14, 1, '2021-03-05 07:18:27'),
(14, 10, '2021-03-05 07:18:27'),
(13, 9, '2021-03-05 07:18:27'),
(13, 3, '2021-03-05 07:18:27'),
(13, 6, '2021-03-05 07:18:27'),
(12, 7, '2021-03-05 07:18:27'),
(12, 2, '2021-03-05 07:18:27'),
(12, 8, '2021-03-05 07:18:27'),
(11, 6, '2021-03-05 07:18:27'),
(11, 5, '2021-03-05 07:18:27'),
(11, 3, '2021-03-05 07:18:27'),
(10, 4, '2021-03-05 07:18:27'),
(10, 1, '2021-03-05 07:18:27'),
(10, 14, '2021-03-05 07:18:27'),
(9, 6, '2021-03-05 07:18:27'),
(9, 3, '2021-03-05 07:18:27'),
(9, 13, '2021-03-05 07:18:27'),
(6, 3, '2021-03-05 07:18:27'),
(6, 9, '2021-03-05 07:18:27'),
(6, 13, '2021-03-05 07:18:27'),
(5, 8, '2021-03-05 07:18:27'),
(5, 12, '2021-03-05 07:18:27'),
(5, 11, '2021-03-05 07:18:27'),
(4, 1, '2021-03-05 07:18:27'),
(4, 10, '2021-03-05 07:18:27'),
(4, 14, '2021-03-05 07:18:27'),
(3, 6, '2021-03-05 07:18:27'),
(3, 9, '2021-03-05 07:18:27'),
(3, 13, '2021-03-05 07:18:27'),
(1, 4, '2021-03-05 07:18:27'),
(1, 10, '2021-03-05 07:18:27'),
(1, 14, '2021-03-05 07:18:27'),
(150, 149, '2021-03-05 07:18:27'),
(150, 145, '2021-03-05 07:18:27'),
(150, 141, '2021-03-05 07:18:27'),
(149, 150, '2021-03-05 07:18:27'),
(149, 145, '2021-03-05 07:18:27'),
(149, 141, '2021-03-05 07:18:27'),
(148, 146, '2021-03-05 07:18:27'),
(148, 138, '2021-03-05 07:18:27'),
(148, 136, '2021-03-05 07:18:27'),
(147, 150, '2021-03-05 07:18:27'),
(147, 145, '2021-03-05 07:18:27'),
(147, 149, '2021-03-05 07:18:27'),
(146, 148, '2021-03-05 07:18:27'),
(146, 144, '2021-03-05 07:18:27'),
(146, 142, '2021-03-05 07:18:27'),
(145, 149, '2021-03-05 07:18:27'),
(145, 147, '2021-03-05 07:18:27'),
(145, 137, '2021-03-05 07:18:27'),
(144, 140, '2021-03-05 07:18:27'),
(144, 142, '2021-03-05 07:18:27'),
(144, 146, '2021-03-05 07:18:27'),
(142, 146, '2021-03-05 07:18:27'),
(142, 148, '2021-03-05 07:18:27'),
(142, 138, '2021-03-05 07:18:27'),
(141, 149, '2021-03-05 07:18:27'),
(141, 139, '2021-03-05 07:18:27'),
(141, 137, '2021-03-05 07:18:27'),
(140, 144, '2021-03-05 07:18:27'),
(140, 142, '2021-03-05 07:18:27'),
(140, 148, '2021-03-05 07:18:27'),
(138, 136, '2021-03-05 07:18:27'),
(138, 148, '2021-03-05 07:18:27'),
(138, 146, '2021-03-05 07:18:27'),
(137, 139, '2021-03-05 07:18:27'),
(137, 145, '2021-03-05 07:18:27'),
(137, 147, '2021-03-05 07:18:27'),
(135, 134, '2021-03-05 07:18:27'),
(135, 125, '2021-03-05 07:18:27'),
(135, 122, '2021-03-05 07:18:27'),
(134, 135, '2021-03-05 07:18:27'),
(134, 125, '2021-03-05 07:18:27'),
(134, 122, '2021-03-05 07:18:27'),
(133, 132, '2021-03-05 07:18:27'),
(133, 129, '2021-03-05 07:18:27'),
(133, 127, '2021-03-05 07:18:27'),
(132, 127, '2021-03-05 07:18:27'),
(132, 129, '2021-03-05 07:18:27'),
(132, 122, '2021-03-05 07:18:27'),
(131, 133, '2021-03-05 07:18:27'),
(131, 124, '2021-03-05 07:18:27'),
(131, 122, '2021-03-05 07:18:27'),
(130, 121, '2021-03-05 07:18:27'),
(130, 128, '2021-03-05 07:18:27'),
(130, 131, '2021-03-05 07:18:27'),
(129, 127, '2021-03-05 07:18:27'),
(129, 129, '2021-03-05 07:18:27'),
(129, 122, '2021-03-05 07:18:27'),
(128, 130, '2021-03-05 07:18:27'),
(128, 121, '2021-03-05 07:18:27'),
(128, 133, '2021-03-05 07:18:27'),
(127, 129, '2021-03-05 07:18:27'),
(127, 121, '2021-03-05 07:18:27'),
(127, 130, '2021-03-05 07:18:27'),
(126, 123, '2021-03-05 07:18:27'),
(126, 133, '2021-03-05 07:18:27'),
(126, 129, '2021-03-05 07:18:27'),
(125, 135, '2021-03-05 07:18:27'),
(125, 134, '2021-03-05 07:18:27'),
(125, 132, '2021-03-05 07:18:27'),
(122, 124, '2021-03-05 07:18:27'),
(122, 129, '2021-03-05 07:18:27'),
(122, 133, '2021-03-05 07:18:27'),
(121, 128, '2021-03-05 07:18:27'),
(121, 130, '2021-03-05 07:18:27'),
(121, 135, '2021-03-05 07:18:27'),
(179, 175, '2021-03-05 07:18:27'),
(179, 167, '2021-03-05 07:18:27'),
(179, 170, '2021-03-05 07:18:27'),
(174, 178, '2021-03-05 07:18:27'),
(174, 168, '2021-03-05 07:18:27'),
(174, 166, '2021-03-05 07:18:27'),
(177, 169, '2021-03-05 07:18:27'),
(177, 167, '2021-03-05 07:18:27'),
(177, 170, '2021-03-05 07:18:27'),
(176, 167, '2021-03-05 07:18:27'),
(176, 170, '2021-03-05 07:18:27'),
(176, 169, '2021-03-05 07:18:27'),
(175, 179, '2021-03-05 07:18:27'),
(175, 176, '2021-03-05 07:18:27'),
(175, 171, '2021-03-05 07:18:27'),
(178, 174, '2021-03-05 07:18:27'),
(178, 172, '2021-03-05 07:18:27'),
(178, 168, '2021-03-05 07:18:27'),
(173, 172, '2021-03-05 07:18:27'),
(173, 166, '2021-03-05 07:18:27'),
(173, 168, '2021-03-05 07:18:27'),
(172, 178, '2021-03-05 07:18:27'),
(172, 168, '2021-03-05 07:18:27'),
(172, 166, '2021-03-05 07:18:27'),
(171, 180, '2021-03-05 07:18:27'),
(171, 174, '2021-03-05 07:18:27'),
(171, 166, '2021-03-05 07:18:27'),
(170, 167, '2021-03-05 07:18:27'),
(170, 176, '2021-03-05 07:18:27'),
(170, 175, '2021-03-05 07:18:27'),
(168, 166, '2021-03-05 07:18:27'),
(168, 172, '2021-03-05 07:18:27'),
(168, 180, '2021-03-05 07:18:27'),
(167, 170, '2021-03-05 07:18:27'),
(167, 175, '2021-03-05 07:18:27'),
(167, 179, '2021-03-05 07:18:27'),
(166, 174, '2021-03-05 07:18:27'),
(166, 180, '2021-03-05 07:18:27'),
(166, 173, '2021-03-05 07:18:27'),
(7, 35, '2021-03-05 07:18:27'),
(7, 53, '2021-03-05 07:18:27'),
(7, 67, '2021-03-05 07:18:27'),
(8, 37, '2021-03-05 07:18:27'),
(8, 62, '2021-03-05 07:18:27'),
(8, 145, '2021-03-05 07:18:27'),
(96, 19, '2021-03-05 07:18:27'),
(96, 43, '2021-03-05 07:18:27'),
(96, 47, '2021-03-05 07:18:27'),
(112, 124, '2021-03-05 07:18:27'),
(112, 141, '2021-03-05 07:18:27'),
(112, 13, '2021-03-05 07:18:27'),
(115, 6, '2021-03-05 07:18:27'),
(115, 28, '2021-03-05 07:18:27'),
(115, 137, '2021-03-05 07:18:27'),
(123, 104, '2021-03-05 07:18:27'),
(123, 161, '2021-03-05 07:18:27'),
(123, 171, '2021-03-05 07:18:27'),
(124, 73, '2021-03-05 07:18:27'),
(124, 54, '2021-03-05 07:18:27'),
(124, 147, '2021-03-05 07:18:27'),
(136, 139, '2021-03-05 07:18:27'),
(136, 177, '2021-03-05 07:18:27'),
(136, 31, '2021-03-05 07:18:27'),
(139, 136, '2021-03-05 07:18:27'),
(139, 148, '2021-03-05 07:18:27'),
(139, 77, '2021-03-05 07:18:27'),
(143, 141, '2021-03-05 07:18:27'),
(143, 31, '2021-03-05 07:18:27'),
(143, 94, '2021-03-05 07:18:27'),
(152, 173, '2021-03-05 07:18:27'),
(152, 129, '2021-03-05 07:18:27'),
(152, 113, '2021-03-05 07:18:27'),
(169, 8, '2021-03-05 07:18:27'),
(169, 39, '2021-03-05 07:18:27'),
(169, 72, '2021-03-05 07:18:27'),
(180, 194, '2021-03-05 07:18:27'),
(180, 135, '2021-03-05 07:18:27'),
(180, 72, '2021-03-05 07:18:27'),
(225, 228, '2021-03-05 07:18:27'),
(225, 209, '2021-03-05 07:18:27'),
(225, 185, '2021-03-05 07:18:27'),
(247, 244, '2021-03-05 07:18:27'),
(247, 242, '2021-03-05 07:18:27'),
(247, 250, '2021-03-05 07:18:27'),
(2, 5, '2021-03-05 07:18:27'),
(2, 12, '2021-03-05 07:18:27'),
(2, 7, '2021-03-05 07:18:27');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
