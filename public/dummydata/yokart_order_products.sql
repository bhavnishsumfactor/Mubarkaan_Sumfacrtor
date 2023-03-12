-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:05 AM
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
-- Dumping data for table `yokart_order_products`
--

INSERT INTO `yokart_order_products` (`op_id`, `op_order_id`, `op_qty`, `op_product_id`, `op_is_pickup`, `op_product_name`, `op_product_type`, `op_expired_on`, `op_product_price`, `op_pov_code`, `op_return_age`, `op_product_variants`, `op_product_sku`, `op_product_width`, `op_product_height`, `op_product_weight`, `op_prod_cost`, `op_pov_id`) VALUES
(1, 10000, 1, 303, 0, 'Kenneth Cole Beige Solid Handheld Bag', 1, '0000-00-00 00:00:00', 79.99, '0', 30, '[]', 'DSJ32', NULL, NULL, '500', '69.99', NULL),
(2, 10001, 1, 316, 0, 'X Closed Narrow Ring', 1, '0000-00-00 00:00:00', 899.00, '316|75|', 7, '{\"styles\":{\"7\":\"7\"},\"code\":\"316|75|\",\"gift\":{\"message\":{\"from\":\"John\",\"to\":\"Cindy\",\"message\":\"Happy Anniversary !!\",\"id\":\"316|75|\"}}}', 'DS223', NULL, NULL, '120', '299', NULL),
(3, 10002, 1, 56, 1, 'V-Neck Chiffon-Trim Top', 1, '0000-00-00 00:00:00', 33.12, '56|11|210|', 30, '{\"styles\":{\"m\":\"m\",\"iconic blush\":\"iconic blush\"},\"code\":\"56|11|210|\"}', 'JII9', NULL, NULL, '1851', '23', NULL),
(4, 10002, 1, 7, 1, 'High-Rise Stretch Skinny Jean, in Regular & Petite Sizes', 1, '0000-00-00 00:00:00', 98.00, '7|60|', 30, '{\"styles\":{\"6\":\"6\"},\"code\":\"7|60|\"}', '3R5', NULL, NULL, '675', '80', NULL),
(5, 10002, 1, 304, 1, 'ALDO Pink Textured Sling Bag', 1, '0000-00-00 00:00:00', 39.99, '0', 30, '[]', 'EW32', NULL, NULL, '200', '19.99', NULL),
(6, 10002, 1, 311, 1, 'Gen 5E Darci Pavé Gold-Tone Smartwatch', 1, '0000-00-00 00:00:00', 699.00, '311|262|', 7, '{\"styles\":{\"gold\":\"gold\"},\"code\":\"311|262|\",\"coupon\":{\"name\":\"WATCH25\",\"type\":\"coupon\",\"value\":174.75}}', 'SD2332', NULL, NULL, '480', '499', NULL),
(7, 10003, 1, 44, 0, 'Houndstooth Skirt Pants', 1, '0000-00-00 00:00:00', 53.40, '44|11|', 30, '{\"styles\":{\"m\":\"m\"},\"code\":\"44|11|\"}', 'U6TH', NULL, NULL, '155', '29', NULL),
(8, 10003, 2, 278, 0, 'Mon Paris Eau de Parfum Spray, 1.6-oz', 1, '0000-00-00 00:00:00', 100.00, '278|145|', 30, '{\"styles\":{\"1.6 oz\":\"1.6 oz\"},\"code\":\"278|145|\"}', '56TY', NULL, NULL, '250', '35', NULL),
(9, 10004, 1, 268, 0, 'Teyana Taylor Lipstick', 1, '0000-00-00 00:00:00', 20.00, '268|301|', 30, '{\"styles\":{\"good moaninnnn (matte\\/rosy pink)\":\"good moaninnnn (matte\\/rosy pink)\"},\"code\":\"268|301|\",\"coupon\":{\"name\":\"1K5KP14X92\",\"type\":\"coupon\",\"value\":5}}', 'GMO56', NULL, NULL, '150', '6', NULL),
(10, 10004, 1, 257, 0, 'Double Wear Light Soft Matte Hydra Makeup, 1-oz.', 1, '0000-00-00 00:00:00', 43.00, '0', 30, '{\"coupon\":{\"name\":\"1K5KP14X92\",\"type\":\"coupon\",\"value\":5}}', 'PC04B6', NULL, NULL, '120', '20', NULL),
(11, 10005, 1, 159, 0, 'Sunglasses, MU 05SS', 1, '0000-00-00 00:00:00', 35.00, '0', 30, '[]', 'UH76B', NULL, NULL, '240', '35', NULL),
(12, 10005, 2, 163, 0, 'Ralph Polarized Sunglasses , RA4004', 1, '0000-00-00 00:00:00', 134.00, '0', 15, '[]', 'JNJN5B', NULL, NULL, '244', '35', NULL),
(13, 10006, 1, 247, 1, 'Cura Luxe Professional Ionic Hair Dryer with Auto Pause Sensor', 1, '0000-00-00 00:00:00', 285.00, '247|3|', 30, '{\"styles\":{\"white\":\"white\"},\"code\":\"247|3|\"}', 'GF34G', NULL, NULL, '299', '59', NULL),
(14, 10007, 1, 271, 0, 'COCO MADEMOISELLE Eau de Parfum Fragrance Collection', 1, '0000-00-00 00:00:00', 135.00, '271|59|', 30, '{\"styles\":[],\"code\":\"271|59|\"}', 'TFV', NULL, NULL, '90', '20', NULL),
(15, 10007, 2, 262, 0, 'Clé de Peau Beauté Concealer SPF 25', 1, '0000-00-00 00:00:00', 73.00, '262|17|', 45, '{\"styles\":{\"ivory\":\"ivory\"},\"code\":\"262|17|\",\"gift\":{\"message\":{\"from\":\"Misha\",\"to\":\"Jennie\",\"message\":\"Happy Birthday jenny, many happy returns of the day..\",\"id\":\"262|17|\"}}}', 'KJNJKERN34', NULL, NULL, '65', '25', NULL),
(16, 10008, 1, 239, 0, 'Costa Smeralda Emerald (1-5/8 ct. t.w.) & Diamond (5/8 ct. t.w.) Drop Earrings in 14k Rose Gold', 1, '0000-00-00 00:00:00', 4700.00, '0', 15, '[]', 'SDVY656', NULL, NULL, '125', '1500', NULL),
(17, 10009, 1, 230, 1, 'Sterling Silver Cubic Zirconia & Mother-of-Pearl Heart Stud Earrings', 1, '0000-00-00 00:00:00', 100.00, '230|15|', 30, '{\"styles\":{\"silver\":\"silver\"},\"code\":\"230|15|\"}', NULL, NULL, NULL, '225', '52', NULL),
(18, 10010, 1, 294, 1, 'Double Serum Complete Age Control Concentrate, 1.6-oz.', 1, '0000-00-00 00:00:00', 126.00, '0', 15, '[]', 'DFVFR5', NULL, NULL, '150', '30', NULL),
(19, 10010, 1, 166, 1, 'Belted Water Resistant Trench Coat', 1, '0000-00-00 00:00:00', 129.00, '166|9|13|', 52, '{\"styles\":{\"xl\":\"xl\",\"green\":\"green\"},\"code\":\"166|9|13|\"}', '65YH6', NULL, NULL, '499', '50', NULL),
(20, 10010, 1, 46, 1, 'Argyle Pointelle Cotton Sweater', 1, '0000-00-00 00:00:00', 31.73, '46|11|', 30, '{\"styles\":{\"m\":\"m\"},\"code\":\"46|11|\"}', 'U76', NULL, NULL, '342', '20', NULL),
(21, 10011, 1, 223, 0, 'EFFY® Diamond Leaf 20\" Statement Necklace (1-1/10 ct. t.w.) in 14k White Gold', 1, '0000-00-00 00:00:00', 6000.00, '0', 12, '{\"gift\":{\"message\":{\"from\":\"Laura\",\"to\":\"Henny\",\"message\":\"Happy Anniversary\",\"id\":\"223\"}}}', 'PMCR35', NULL, NULL, '165', '1500', NULL),
(22, 10012, 2, 218, 0, 'Cubic Zirconia Open Heart 18\" Pendant Necklace in Sterling Silver', 1, '0000-00-00 00:00:00', 56.00, '0', 15, '[]', 'PIVF566Y', NULL, NULL, '65', '25', NULL),
(23, 10013, 1, 215, 1, 'Purple and Clear Swarovski Zirconia Heart Necklace in Sterling Silver', 1, '0000-00-00 00:00:00', 425.00, '0', 15, '[]', 'BJ8FC4', NULL, NULL, '154', '220', NULL),
(24, 10014, 1, 214, 1, 'EFFY® Mother-of-Pearl & Diamond Accent Pendant Necklace in 14k Rose Gold, 16\" + 2\" extender', 1, '0000-00-00 00:00:00', 2.00, NULL, 45, '', NULL, NULL, NULL, '5698', '954', NULL),
(25, 10015, 1, 184, 0, 'Women\'s Red Silicone Strap Watch 40mm', 1, '0000-00-00 00:00:00', 71.00, NULL, 15, '', NULL, NULL, NULL, '159', '29', NULL),
(26, 10015, 1, 175, 0, 'Military Band Jacket', 1, '0000-00-00 00:00:00', 110.00, '175|1|13|', 30, '{\"styles\":{\"xl\":\"xl\",\"black\":\"black\"},\"code\":\"175|1|13|\"}', NULL, NULL, NULL, '432', '25', NULL),
(27, 10015, 1, 140, 0, 'High-Waisted Shaping Sheers', 1, '0000-00-00 00:00:00', 32.00, '140|1|', 15, '{\"styles\":{\"black\":\"black\"},\"code\":\"140|1|\"}', NULL, NULL, NULL, '109', '10', NULL),
(28, 10015, 1, 146, 0, 'Women\'s Opaque Tights', 1, '0000-00-00 00:00:00', 12.00, '146|61|', 15, '{\"styles\":{\"4\":\"4\"},\"code\":\"146|61|\"}', NULL, NULL, NULL, '154', '5', NULL),
(29, 10016, 1, 307, 1, 'DB Classic emerald-cut diamond studs', 1, '0000-00-00 00:00:00', 11200.00, '307|334|338|340|', 30, '{\"styles\":{\"si 1\":\"si 1\",\"0.71\":\"0.71\",\"i\":\"i\"},\"code\":\"307|334|338|340|\",\"gift\":{\"message\":{\"from\":\"Paula\",\"to\":\"Maria\",\"message\":\"Happy Anniversary\",\"id\":\"307|334|338|340|\"}}}', 'SD23', NULL, NULL, '85', '7500', NULL),
(30, 10017, 1, 162, 0, 'Sunglasses, HC8158', 1, '0000-00-00 00:00:00', 186.00, '0', 15, '[]', 'TF6Y', NULL, NULL, '245', '56', NULL),
(31, 10017, 1, 289, 0, 'Dramatically Different Moisturizing Lotion+ with Pump, 4.2 oz', 1, '0000-00-00 00:00:00', 28.00, '0', 30, '[]', 'XFBHE56', NULL, NULL, '190', '10', NULL),
(32, 10017, 1, 302, 0, 'Calvin Klein Printed Handheld Bag', 1, '0000-00-00 00:00:00', 89.99, '302|3|', 30, '{\"styles\":{\"white\":\"white\"},\"code\":\"302|3|\"}', 'CKWB21', NULL, NULL, '375', '59.99', NULL),
(33, 10018, 1, 70, 0, 'Women\'s Revolution 5 Running Sneakers from Finish Line', 1, '0000-00-00 00:00:00', 65.00, '70|74|280|', 30, '{\"styles\":{\"5\":\"5\",\" psychic pink-dark g\":\" psychic pink-dark g\"},\"code\":\"70|74|280|\"}', 'TRG544TY', NULL, NULL, '144', '26', NULL),
(34, 10018, 1, 21, 0, 'Sequined Lace Gown', 1, '0000-00-00 00:00:00', 795.00, '21|59|', 30, '{\"styles\":{\"8\":\"8\"},\"code\":\"21|59|\"}', 'YHRT54Y', NULL, NULL, '8', '499', NULL),
(35, 10019, 1, 295, 0, 'Benefiance Wrinkle Smoothing Eye Cream, 0.51-oz.', 1, '0000-00-00 00:00:00', 70.00, '0', 30, '[]', 'FB54', NULL, NULL, '100', '25', NULL),
(36, 10020, 1, 225, 1, 'Ocean Bleu by EFFY® Blue Topaz (7-1/10 ct. t.w.) and Diamond (1/8 ct. t.w.) Pendant Necklace in 14k White Gold', 1, '0000-00-00 00:00:00', 2350.00, '0', 30, '[]', 'PK54C', NULL, NULL, '154', '659', NULL),
(37, 10021, 1, 82, 0, 'Glenda Slide Sandals', 1, '0000-00-00 00:00:00', 63.00, '82|87|', 30, '{\"styles\":{\"7m\":\"7m\"},\"code\":\"82|87|\"}', NULL, NULL, NULL, '656', '25', NULL),
(38, 10021, 1, 38, 0, 'Ruffled Floral-Print Skirt', 1, '0000-00-00 00:00:00', 19.04, '38|60|', 30, '{\"styles\":{\"6\":\"6\"},\"code\":\"38|60|\"}', NULL, NULL, NULL, '156', '15', NULL),
(39, 10022, 1, 269, 0, 'Eye Color Quad', 1, '0000-00-00 00:00:00', 88.00, '269|281|', 30, '{\"styles\":{\"honeymoon\":\"honeymoon\"},\"code\":\"269|281|\"}', 'JGH5JG', NULL, NULL, '250', '20', NULL),
(40, 10022, 1, 272, 0, 'Si Passione Eau de Parfum 4-Pc Gift Set', 1, '0000-00-00 00:00:00', 138.00, '0', 15, '[]', 'F54RGF', NULL, NULL, '500', '45', NULL),
(41, 10023, 1, 18, 1, 'Lace Illusion Halter Dress', 1, '0000-00-00 00:00:00', 76.00, '18|52|60|', 30, '{\"styles\":{\"6\":\"6\"},\"code\":\"18|52|60|\"}', NULL, NULL, NULL, '455', '81', NULL),
(42, 10024, 1, 301, 1, 'Burgundy Solid Tote Bag', 1, '0000-00-00 00:00:00', 49.99, '0', 30, '[]', 'DJRK45', NULL, NULL, '450', '39.99', NULL),
(43, 10024, 1, 304, 1, 'ALDO Pink Textured Sling Bag', 1, '0000-00-00 00:00:00', 39.99, '0', 30, '{\"gift\":{\"message\":{\"from\":\"Paula\",\"to\":\"Victoria\",\"message\":\"Birthday gift\",\"id\":\"304\"}}}', 'EW32', NULL, NULL, '200', '19.99', NULL),
(44, 10025, 1, 49, 0, 'Solid Handkerchief Top', 1, '0000-00-00 00:00:00', 49.00, '49|11|66|', 30, '{\"styles\":{\"m\":\"m\",\"baby blue\":\"baby blue\"},\"code\":\"49|11|66|\"}', '4RTF4', NULL, NULL, '153', '18', NULL),
(45, 10025, 1, 54, 0, 'Open-Front Cardigan', 1, '0000-00-00 00:00:00', 29.99, '54|11|', 30, '{\"styles\":{\"m\":\"m\"},\"code\":\"54|11|\"}', 'GGXD', NULL, NULL, '755', '12', NULL),
(46, 10025, 1, 57, 0, 'Cotton V-Neck Sweater', 1, '0000-00-00 00:00:00', 14.39, '57|11|', 30, '{\"styles\":{\"m\":\"m\"},\"code\":\"57|11|\"}', 'T4G5', NULL, NULL, '255', '10', NULL),
(47, 10026, 1, 39, 1, 'High Waist Running Shorts', 1, '0000-00-00 00:00:00', 12.00, '39|11|', 30, '{\"styles\":{\"m\":\"m\"},\"code\":\"39|11|\"}', 'EF61E', NULL, NULL, '184', '19.99', NULL),
(48, 10026, 2, 42, 1, 'Striped Wrap Midi Skirt', 1, '0000-00-00 00:00:00', 59.40, '42|58|', 30, '{\"styles\":{\"10\":\"10\"},\"code\":\"42|58|\"}', '65GT', NULL, NULL, '132', '25', NULL),
(49, 10026, 1, 40, 1, 'Women\'s Ellison Textured Lace Skirt', 1, '0000-00-00 00:00:00', 23.97, '40|1|60|', 30, '{\"styles\":{\"6\":\"6\",\"black\":\"black\"},\"code\":\"40|1|60|\"}', 'E13', NULL, NULL, '225', '39.95', NULL),
(50, 10027, 1, 132, 1, 'Charlie Carryall 40', 1, '0000-00-00 00:00:00', 475.00, '132|119|', 30, '{\"styles\":{\"oxblood\":\"oxblood\"},\"code\":\"132|119|\"}', 'GF5', NULL, NULL, '574', '65', NULL),
(51, 10027, 1, 314, 1, 'Tiffany Victoria® Ring', 1, '0000-00-00 00:00:00', 399.00, '314|61|', 7, '{\"styles\":{\"4\":\"4\"},\"code\":\"314|61|\"}', 'DS23', NULL, NULL, '111', '199', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
