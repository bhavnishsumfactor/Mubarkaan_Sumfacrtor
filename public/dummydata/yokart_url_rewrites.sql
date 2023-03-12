-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 08, 2021 at 03:50 AM
-- Server version: 10.0.38-MariaDB
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
-- Database: `tr1beyok4rt_demo`
--

--
-- Dumping data for table `yokart_url_rewrites`
--

INSERT INTO `yokart_url_rewrites` (`urlrewrite_id`, `urlrewrite_type`, `urlrewrite_record_id`, `urlrewrite_original`, `urlrewrite_custom`) VALUES
(1, 3, 1, 'category/1', 'clothing'),
(4, 3, 4, 'category/4', 'dresses'),
(5, 3, 5, 'category/5', 'shorts-skirts'),
(7, 3, 7, 'category/7', 'tops-sweaters'),
(9, 3, 9, 'category/9', 'jeans-pants-leggings'),
(10, 3, 10, 'category/10', 'coats-jackets-blazers'),
(13, 3, 13, 'category/13', 'shoes'),
(14, 3, 14, 'category/14', 'sports-shoes-sneakers'),
(16, 3, 16, 'category/16', 'heels-pumps'),
(17, 3, 17, 'category/17', 'sandals-flipflops'),
(18, 3, 18, 'category/18', 'mules-slippers--slides'),
(21, 3, 21, 'category/21', 'handbags-accessories'),
(22, 3, 22, 'category/22', 'handbags-wallets'),
(23, 3, 23, 'category/23', 'tights-socks-hosiery'),
(24, 3, 24, 'category/24', 'sunglasses'),
(25, 3, 25, 'category/25', 'jewelry-watches'),
(26, 3, 26, 'category/26', 'watches'),
(27, 3, 27, 'category/27', 'rings'),
(28, 3, 28, 'category/28', 'necklaces'),
(31, 3, 31, 'category/31', 'earrings'),
(32, 3, 32, 'category/32', 'beauty'),
(33, 3, 33, 'category/33', 'hair-care'),
(34, 3, 34, 'category/34', 'makeup'),
(35, 3, 35, 'category/35', 'perfume'),
(36, 3, 36, 'category/36', 'skin-care'),
(37, 1, 1, 'product/1', 'womens-720-highrise-superskinny-jeans'),
(38, 1, 2, 'product/2', 'womens-one-logo-leggings'),
(39, 1, 3, 'product/3', 'capri-pullon-pants'),
(40, 1, 4, 'product/4', 'waverly-skinny-jeans'),
(41, 1, 5, 'product/5', 'womens-tiro-climacool-soccer-pants'),
(42, 1, 6, 'product/6', 'cropped-cargo-pants'),
(43, 1, 7, 'product/7', 'highrise-stretch-skinny-jean-in-regular-&-petite-sizes'),
(44, 1, 8, 'product/8', 'womens-pearl-essence-tiro-climacool-soccer-pants'),
(45, 1, 9, 'product/9', 'wideleg-pullon-pants'),
(46, 1, 10, 'product/10', 'trendy-plus-size-415-classic-bootcut-jeans'),
(47, 1, 11, 'product/11', 'tribeca-plaid-knit-pants'),
(48, 1, 12, 'product/12', 'logo-tropical-mesh-leggings'),
(49, 1, 13, 'product/13', 'cotton-palazzo-pants'),
(50, 1, 14, 'product/14', 'highrise-skinny-ankle-jeans'),
(51, 1, 15, 'product/15', 'sutton-bootleg-trousers'),
(52, 1, 16, 'product/16', 'juniors-cowlneck-geoprint-metallic-dress'),
(53, 1, 17, 'product/17', 'printed-jersey-bell-sleeve-aline-dress'),
(54, 1, 18, 'product/18', 'lace-illusion-halter-dress'),
(55, 1, 19, 'product/19', 'ruffle-vneck-chiffon-dress'),
(56, 1, 20, 'product/20', 'crochet-long-dress'),
(57, 1, 21, 'product/21', 'sequined-lace-gown'),
(58, 1, 22, 'product/22', 'belted-cotton-shirtdress'),
(59, 1, 23, 'product/23', 'oneshoulder-longsleeve-gown'),
(60, 1, 24, 'product/24', 'short-sleeve-floral-fit-&-flare-dress'),
(61, 1, 25, 'product/25', 'longsleeve-fitted-denim-dress'),
(62, 1, 26, 'product/26', 'detail-button-dress'),
(63, 1, 27, 'product/27', 'cotton-mixedprint-shirtdress'),
(64, 1, 28, 'product/28', 'womens-jacquard-bodycon-long-sleeve-midi-dress'),
(65, 1, 29, 'product/29', 'sleeveless-fit-&-flare-shirtdress'),
(66, 1, 30, 'product/30', 'gabby-smocked-floralprint-mini-dress'),
(67, 1, 31, 'product/31', 'sport-stripedlogo-highwaist-bike-shorts'),
(68, 1, 32, 'product/32', 'faded-denim-skirt'),
(69, 1, 33, 'product/33', 'hollywood-shorts'),
(70, 1, 34, 'product/34', 'belted-chambray-skort'),
(71, 1, 35, 'product/35', 'highrise-distressed-denim-shorts'),
(72, 1, 36, 'product/36', 'ribbed-graphic-pencil-skirt'),
(73, 1, 37, 'product/37', 'womens-drifit-tempo-running-shorts'),
(74, 1, 38, 'product/38', 'ruffled-floralprint-skirt'),
(75, 1, 39, 'product/39', 'high-waist-running-shorts'),
(76, 1, 40, 'product/40', 'womens-ellison-textured-lace-skirt'),
(77, 1, 41, 'product/41', 'gingham-shorts'),
(78, 1, 42, 'product/42', 'striped-wrap-midi-skirt'),
(79, 1, 43, 'product/43', 'claudia-highrise-frayed-denim-shorts'),
(80, 1, 44, 'product/44', 'houndstooth-skirt-pants'),
(81, 1, 45, 'product/45', 'womens-pacer-here-to-create-printed-shorts'),
(82, 1, 46, 'product/46', 'argyle-pointelle-cotton-sweater'),
(83, 1, 47, 'product/47', 'laceyoke-handkerchiefhem-top'),
(84, 1, 48, 'product/48', 'ribbed-vneck-cotton-sweater'),
(85, 1, 49, 'product/49', 'solid-handkerchief-top'),
(86, 1, 50, 'product/50', 'mixedmedia-highlow-sweater'),
(87, 1, 51, 'product/51', 'vneck-rollsleeve-blouse'),
(88, 1, 52, 'product/52', 'cotton-layeredlook-sweater'),
(89, 1, 53, 'product/53', 'short-sleeve-henley-top'),
(90, 1, 54, 'product/54', 'openfront-cardigan'),
(91, 1, 55, 'product/55', 'printed-tiecuff-top'),
(92, 1, 56, 'product/56', 'vneck-chiffontrim-top'),
(93, 1, 57, 'product/57', 'cotton-vneck-sweater'),
(94, 1, 58, 'product/58', 'metal-thread-sweater'),
(95, 1, 59, 'product/59', 'floralprint-elbowsleeve-boatneck-top'),
(96, 1, 60, 'product/60', 'cropped-puffsleeve-top'),
(97, 1, 61, 'product/61', 'womens-disruptor-3-casual-sneakers-from-finish-line'),
(98, 1, 62, 'product/62', 'womens-air-max-270-casual-sneakers-from-finish-line'),
(99, 1, 63, 'product/63', 'chuck-taylor-ox-casual-sneakers-from-finish-line'),
(100, 1, 64, 'product/64', 'womens-fuelcore-nergize-walking-sneakers-from-finish-line'),
(101, 1, 65, 'product/65', 'womens-swift-run-casual-sneakers-from-finish-line'),
(102, 1, 66, 'product/66', 'ashly-sneakers'),
(103, 1, 67, 'product/67', 'keaton-slipon-logo-sneakers'),
(104, 1, 68, 'product/68', 'womens-lightz-laceup-fashion-sneakers'),
(105, 1, 69, 'product/69', 'womens-tea-sneakers'),
(106, 1, 70, 'product/70', 'womens-revolution-5-running-sneakers-from-finish-line'),
(107, 1, 71, 'product/71', 'womens-myles-knit-chunky-sneakers'),
(108, 1, 72, 'product/72', 'felix-bubble-trainer-sneakers'),
(109, 1, 73, 'product/73', 'marcel-sneakers'),
(110, 1, 74, 'product/74', 'womens-belux-daylights-casual-walking-sneakers-from-finish-line'),
(111, 1, 75, 'product/75', 'collection-womens-cloudsteppers-step-allena-bay-sneakers'),
(112, 1, 76, 'product/76', 'womens-riley-85-pumps'),
(113, 1, 77, 'product/77', 'womens-camille-vinyl-sandals'),
(114, 1, 78, 'product/78', 'dorothy-flex-pumps'),
(115, 1, 79, 'product/79', 'womens-gayle-pumps'),
(116, 1, 80, 'product/80', 'collection-womens-adriel-viola-pumps'),
(117, 1, 81, 'product/81', 'womens-mari-ankletie-pumps'),
(118, 1, 82, 'product/82', 'glenda-slide-sandals'),
(119, 1, 83, 'product/83', 'collection-womens-adriel-cove-dress-sandals'),
(120, 1, 84, 'product/84', 'collection-womens-valarie-rally-pumps'),
(121, 1, 85, 'product/85', 'gladiss-slingback-pumps'),
(122, 1, 86, 'product/86', 'daisie-pumps'),
(123, 1, 87, 'product/87', 'henaya-slingback-pumps'),
(124, 1, 88, 'product/88', 'meaggann-pumps'),
(125, 1, 89, 'product/89', 'womens-alanna-western-mules'),
(126, 1, 90, 'product/90', 'riley-85-pumps'),
(127, 1, 91, 'product/91', 'sarraly-eva-logo-wedge-sandals'),
(128, 1, 92, 'product/92', 'haloe2-wedge-thong-sandals'),
(129, 1, 93, 'product/93', 'womens-palm-sandals'),
(130, 1, 94, 'product/94', 'womens-cloudsteppers-brinkley-jazz-sandals'),
(131, 1, 95, 'product/95', 'womens-travel-rock-stud-flat-sandals'),
(132, 1, 96, 'product/96', 'easten-slide-sandals'),
(133, 1, 97, 'product/97', 'womens-jeri-leather-sandals'),
(134, 1, 98, 'product/98', 'mk-plate-flat-thong-sandals'),
(135, 1, 99, 'product/99', 'tutu-bow-flip-flops'),
(136, 1, 100, 'product/100', 'womens-fine-glass-wedge-sandals'),
(137, 1, 101, 'product/101', 'womens-card-wedges'),
(138, 1, 102, 'product/102', 'collection-womens-leisa-janna-flat-sandals'),
(139, 1, 103, 'product/103', 'brenda-flatform-sport-sandals'),
(140, 1, 104, 'product/104', 'memory-foam-rivver-sandals'),
(141, 1, 105, 'product/105', 'daz-flat-slide-sandals'),
(142, 1, 106, 'product/106', 'maisie-stitch-mules'),
(143, 1, 107, 'product/107', 'womens-fuzz-slippers'),
(144, 1, 108, 'product/108', 'dacey-mule-pump'),
(145, 1, 109, 'product/109', 'grandie-slipon-tailored-mules'),
(146, 1, 110, 'product/110', 'collection-womens-delana-amber-clogs'),
(147, 1, 111, 'product/111', 'raquel-womens-jr-trapper-slipper'),
(148, 1, 112, 'product/112', 'ricki-espadrille-flatform-mules'),
(149, 1, 113, 'product/113', 'womens-moise-margaret-mules'),
(150, 1, 114, 'product/114', 'womens-wallice-mules'),
(151, 1, 115, 'product/115', 'cloudstepper-womens-step-flow-low-clogs'),
(152, 1, 116, 'product/116', 'womens-gills-slipon-sneakers'),
(153, 1, 117, 'product/117', 'ali-mule-flats'),
(154, 1, 118, 'product/118', 'ramona-womens-bootie-slipper'),
(155, 1, 119, 'product/119', 'womens-oh-yeah-slides'),
(156, 1, 120, 'product/120', 'collection-womens-leisa-clover-mules'),
(157, 1, 121, 'product/121', 'julia-camo-phone-crossbody'),
(158, 1, 122, 'product/122', 'boho-studded-strap-hobo'),
(159, 1, 123, 'product/123', 'signature-card-holder'),
(160, 1, 124, 'product/124', 'daily-diaper-bag'),
(161, 1, 125, 'product/125', 'amelia-nylon-flap-backpack'),
(162, 1, 126, 'product/126', 'softy-leather-all-in-one-wallet'),
(163, 1, 127, 'product/127', 'lynne-convertible-crossbody'),
(164, 1, 128, 'product/128', 'elissa-graffiti-logo-pebble-leather-charm-crossbody'),
(165, 1, 129, 'product/129', 'tabby-leather-shoulder-bag-26'),
(166, 1, 130, 'product/130', 'college-green-medium-phone-crossbody'),
(167, 1, 131, 'product/131', 'pish-posh-tote'),
(168, 1, 132, 'product/132', 'charlie-carryall-40'),
(169, 1, 133, 'product/133', 'maxine-small-messenger'),
(170, 1, 134, 'product/134', 'camilla-convertible-large-leather-backpack'),
(171, 1, 135, 'product/135', 'elaine-flap-backpack'),
(172, 1, 136, 'product/136', 'hold-&-stretch-stirrup-tight'),
(173, 1, 137, 'product/137', 'disposable-sock'),
(174, 1, 138, 'product/138', 'studio-basics-fishnet-seamless-tight'),
(175, 1, 139, 'product/139', '3pk.-cushioned-noshow-womens-socks'),
(176, 1, 140, 'product/140', 'highwaisted-shaping-sheers'),
(177, 1, 141, 'product/141', 'womens-hidden-cotton-no-show-4-pack-socks'),
(178, 1, 142, 'product/142', 'satin-touch-20-tights'),
(179, 1, 143, 'product/143', 'higher-power-panties-also-available-in-extended-sizes'),
(180, 1, 144, 'product/144', 'tuxedostripe-fauxleather-leggings'),
(181, 1, 145, 'product/145', 'womens-3pk.-soft-microfiber-demi-crew-socks'),
(182, 1, 146, 'product/146', 'womens-opaque-tights'),
(183, 1, 147, 'product/147', 'womens-thumbs-up-crew-socks'),
(184, 1, 148, 'product/148', 'womens-opaque-control-top-tights'),
(185, 1, 149, 'product/149', 'womens-2pk.-microterry-embroidered-ballerina-slippers'),
(186, 1, 150, 'product/150', '6pk.-trefoil-superlite-noshow-womens-socks'),
(187, 1, 151, 'product/151', 'eyewear-sunglasses-vo4166s-49'),
(188, 1, 152, 'product/152', 'polarized-sunglasses-rb2132-new-wayfarer'),
(189, 1, 153, 'product/153', 'chelsea-sunglasses-mk5004'),
(190, 1, 154, 'product/154', 'womens-sunglasses'),
(191, 1, 155, 'product/155', 'polarized-holbrook-prizm-black-iridium-sunglasses--oo9102'),
(192, 1, 156, 'product/156', 'bulgari-womens-sunglasses'),
(193, 1, 157, 'product/157', 'sunglasses-ve4384b-54'),
(194, 1, 158, 'product/158', 'sunglasses-ty6066-58'),
(195, 1, 159, 'product/159', 'sunglasses-mu-05ss'),
(196, 1, 160, 'product/160', 'sunglasses-va4065-53'),
(197, 1, 161, 'product/161', 'sunglasses-pr-60xs-59'),
(198, 1, 162, 'product/162', 'sunglasses-hc8158'),
(199, 1, 163, 'product/163', 'ralph-polarized-sunglasses--ra4004'),
(200, 1, 164, 'product/164', 'sunglasses-gg0062s'),
(201, 1, 165, 'product/165', 'womens-sunglasses'),
(202, 1, 166, 'product/166', 'belted-water-resistant-trench-coat'),
(203, 1, 167, 'product/167', 'tiedye-hooded-packable-puffer-jacket'),
(204, 1, 168, 'product/168', 'hooded-fauxleathertrim-waterresistant-doublebreasted-trench-coat'),
(205, 1, 169, 'product/169', 'womens-sportswear-windrunner-windbreaker'),
(206, 1, 170, 'product/170', 'zipfront-piped-piqu-jacket'),
(207, 1, 171, 'product/171', 'doublebreasted-blazer'),
(208, 1, 172, 'product/172', 'hooded-waterresistant-raincoat'),
(209, 1, 173, 'product/173', 'womens-original-sherpa-trucker'),
(210, 1, 174, 'product/174', 'hooded-belted-waterresistant-raincoat'),
(211, 1, 175, 'product/175', 'military-band-jacket'),
(212, 1, 176, 'product/176', 'womens-original-denim-trucker-jacket'),
(213, 1, 177, 'product/177', 'womens-air-waterrepellent-running-jacket'),
(214, 1, 178, 'product/178', 'hooded-belted-waterresistant-raincoat'),
(215, 1, 179, 'product/179', 'leather-biker-jacket'),
(216, 1, 180, 'product/180', 'doublebreasted-hooded-waterresistant-trench-coat'),
(217, 1, 181, 'product/181', 'womens-swiss-palazzo-empire-greca-gold-ionplated-stainless-steel-mesh-bracelet-watch-37mm'),
(218, 1, 182, 'product/182', 'womens-swiss-gtimeless-pink-blooms-canvas-strap-watch-38mm'),
(219, 1, 183, 'product/183', 'tech-gen-5-julianna-hr-nude-leather-strap-smart-watch-44mm-powered-by-wear-os-by-google'),
(220, 1, 184, 'product/184', 'womens-red-silicone-strap-watch-40mm'),
(221, 1, 185, 'product/185', 'womens-blue-stainless-steel-mesh-bracelet-watch-32mm'),
(222, 1, 186, 'product/186', 'womens-jacqueline-mint-leather-strap-watch-36mm'),
(223, 1, 187, 'product/187', 'goldtone-stainless-steel-chain-bracelet-watch-39x47mm'),
(224, 1, 188, 'product/188', 'womens-swiss-virtus-pink-leather-strap-watch-36mm'),
(225, 1, 189, 'product/189', 'womens-runway-mercer-twotone-stainless-steel-bracelet-watch-38mm'),
(226, 1, 190, 'product/190', 'womens-chronograph-white-silicone-strap-watch-38mm'),
(227, 1, 191, 'product/191', 'unisex-swiss-gtimeless-mystic-white-leather-strap-watch-38mm'),
(228, 1, 192, 'product/192', 'womens-swiss-aquaracer-stainless-steel-&-18k-yellow-gold-bracelet-watch-27mm'),
(229, 1, 193, 'product/193', 'womens-swiss-tlady-lovely-pink-leather-strap-watch-20mm'),
(230, 1, 194, 'product/194', 'womens-moon-twotone-stainless-steel-mesh-bracelet-watch-35mm'),
(231, 1, 195, 'product/195', 'womens-rose-goldtone-stainless-steel-bracelet-watch-32mm'),
(232, 1, 196, 'product/196', 'rhodiumplated-roundcut-clear-crystal-ring'),
(233, 1, 197, 'product/197', 'effy-diamond-statement-ring-(910-ct.-t.w.)-in-14k-rose-gold'),
(234, 1, 198, 'product/198', 'diamond-twostone-engagement-ring-(115-ct.-t.w.)-in-14k-white-gold'),
(235, 1, 199, 'product/199', 'nude-diamonds-statement-ring-(13ct.-t.w.)-in-14k-rose-gold'),
(236, 1, 200, 'product/200', 'effy-diamond-pav-openwork-statement-ring-(34-ct.-t.w.)-in-14k-gold'),
(237, 1, 201, 'product/201', 'womens-kors-love-cz-pav-heart-sterling-silver-ring'),
(238, 1, 202, 'product/202', 'seaside-by-effy-diamond-octopus-ring-(113-ct.-t.w.)-in-14k-white-gold'),
(239, 1, 203, 'product/203', 'chocolate-and-white-diamond-circle-cluster-ring-in-14k-rose-gold-(78-ct.-t.w.)'),
(240, 1, 204, 'product/204', 'diamond-square-halo-cluster-ring-(3-ct.-t.w.)-in-14k-white-gold'),
(241, 1, 205, 'product/205', 'effy-emerald-(215-ct.-t.w.)-&-diamond-(110-ct.-t.w.)-statement-ring-in-14k-gold'),
(242, 1, 206, 'product/206', 'diamond-threestone-engagement-ring-(3-ct.-t.w.)-in-14k-white-gold'),
(243, 1, 207, 'product/207', 'multisapphire-(1910-ct.-t.w.)-&-diamond-(110-ct.-t.w.)-ring-in-14k-rose-gold'),
(244, 1, 208, 'product/208', 'effy-aquamarine-(112-ct.-t.w.)-&-diamond-(14-ct.-t.w.)-halo-ring-in-14k-rose-gold'),
(245, 1, 209, 'product/209', 'chocolatier-blue-topaz-(4-ct.-t.w.)-diamond-(38-ct.-t.w.)-ring-in-14k-rose-gold-(also-in-rhodolite-garnet)'),
(246, 1, 210, 'product/210', '14k-white-gold-ring-swarovski-zirconia-wedding-ring-(234-ct.-t.w.)'),
(247, 1, 211, 'product/211', 'effy-green-onyx-(11-x-9mm)-&-diamond-(110-ct.-t.w.)-18-pendant-necklace'),
(248, 1, 212, 'product/212', 'diamond-pendant-necklace-(12-ct.-t.w.)-in-14k-white-gold'),
(249, 1, 213, 'product/213', 'diamond-pendant-(12-ct.-t.w.)-18-necklace-in-14k-white-gold'),
(250, 1, 214, 'product/214', 'effy-motherofpearl-&-diamond-accent-pendant-necklace-in-14k-rose-gold-16--2-extender'),
(251, 1, 215, 'product/215', 'purple-and-clear-swarovski-zirconia-heart-necklace-in-sterling-silver'),
(252, 1, 216, 'product/216', '4pc.-set-cubic-zirconia-pendant-necklaces-&-stud-earrings-in-sterling-silver'),
(253, 1, 217, 'product/217', 'effy-certified-ruby-(1-ct.-t.w.)-&-diamond-(18-ct.-t.w.)-18-pendant-necklace-in-14k-gold'),
(254, 1, 218, 'product/218', 'cubic-zirconia-open-heart-18-pendant-necklace-in-sterling-silver'),
(255, 1, 219, 'product/219', 'cultured-freshwater-pearl-(8mm)-and-swarovski-zirconia-17-pendant-necklace-in-sterling-silver'),
(256, 1, 220, 'product/220', 'effy-diamond-solitaire-18-pendant-necklace-(12-ct.-t.w.)-in-14k-white-gold'),
(257, 1, 221, 'product/221', 'cubic-zirconia-halo-pendant-necklace-in-sterling-silver-16--2-extender'),
(258, 1, 222, 'product/222', 'collection-multigemstone-silk-cord-pendant-necklace-(614-ct.-t.w.)-in-14k-rose-gold'),
(259, 1, 223, 'product/223', 'effy-diamond-leaf-20-statement-necklace-(1110-ct.-t.w.)-in-14k-white-gold'),
(260, 1, 224, 'product/224', 'rose-goldtone-sterling-silver-pav-zodiac-pendant-necklace-16--2-extender'),
(261, 1, 225, 'product/225', 'ocean-bleu-by-effy-blue-topaz-(7110-ct.-t.w.)-and-diamond-(18-ct.-t.w.)-pendant-necklace-in-14k-white-gold'),
(262, 1, 226, 'product/226', 'bridal-cultured-freshwater-pearl-(7mm)-and-swarovski-zirconia-(2-ct.-t.w.)-drop-earrings-in-sterling-silver'),
(263, 1, 227, 'product/227', 'diamond-halo-drop-earrings-(78-ct.-t.w.)-in-14k-white-gold'),
(264, 1, 228, 'product/228', 'effy-london-blue-topaz-(815-ct.-t.w.)-&-diamond-(13-ct.-t.w.)-stud-earrings-in-14k-white-gold'),
(265, 1, 229, 'product/229', 'mint-julep-quartz-(538-ct.-t.w.)-&-diamond-(34-ct.-t.w.)-drop-earrings-in-14k-rose-gold'),
(266, 1, 230, 'product/230', 'sterling-silver-cubic-zirconia-&-motherofpearl-heart-stud-earrings'),
(267, 1, 231, 'product/231', 'effy-cultured-freshwater-pearl-(9mm)-drop-earrings-in-14k-gold-white-gold-&-rose-gold'),
(268, 1, 232, 'product/232', 'strawberry-layer-cake-multigemstone-ombr-hoop-earrings-(415-ct.-t.w.)-in-14k-rose-gold'),
(269, 1, 233, 'product/233', 'labcreated-diamond-halo-stud-earrings-(12-ct.-t.w.)-in-sterling-silver'),
(270, 1, 234, 'product/234', 'effy-blue-topaz-and-sapphire-(458-ct.-t.w.)-and-diamond-(13-ct.-t.w.)-drop-earrings-in-14k-white-gold'),
(271, 1, 235, 'product/235', '14k-goldplated-sterling-silver-mercer-medium-link-door-knocker-earrings-1.88'),
(272, 1, 236, 'product/236', 'diamond-butterfly-drop-earrings-(178-ct.-t.w.)-in-14k-rose-gold'),
(273, 1, 237, 'product/237', 'signature-by-effy-diamond-(18-ct.-t.w.)-&-tsavorite-accent-panther-door-knocker-drop-earrings-in-sterling-silver'),
(274, 1, 238, 'product/238', 'sterling-silver-pav-frontfacing-hoop-earrings'),
(275, 1, 239, 'product/239', 'costa-smeralda-emerald-(158-ct.-t.w.)-&-diamond-(58-ct.-t.w.)-drop-earrings-in-14k-rose-gold'),
(276, 1, 240, 'product/240', 'effy-diamond-sunburst-stud-earrings-(113-ct.-t.w.)-in-14k-white-gold'),
(277, 1, 241, 'product/241', 'amino-acid-shampoo-33.8-fl.-oz.'),
(278, 1, 242, 'product/242', 'the-new-classic-flat-iron-112-from-purebeauty-salon-&-spa'),
(279, 1, 243, 'product/243', '3pc.-system-4-set-from-purebeauty-salon-&-spa'),
(280, 1, 244, 'product/244', 'prima-3000-nano-titanium-flat-iron-114-from-purebeauty-salon-&-spa'),
(281, 1, 245, 'product/245', 'double-shot-dryer-brush'),
(282, 1, 246, 'product/246', 'tea-tree-special-shampoo-33.8oz.-from-purebeauty-salon-&-spa'),
(283, 1, 247, 'product/247', 'cura-luxe-professional-ionic-hair-dryer-with-auto-pause-sensor'),
(284, 1, 248, 'product/248', 'handy-bristle-hair-brush'),
(285, 1, 249, 'product/249', 'whirl-trio-interchangeable-styling-wand-set-tapered-1-1.5'),
(286, 1, 250, 'product/250', 'volume-spray-25-10oz.-from-purebeauty-salon-&-spa'),
(287, 1, 251, 'product/251', 'hair-straightening-styling-comb'),
(288, 1, 252, 'product/252', 'laserband-82'),
(289, 1, 253, 'product/253', 'lasercomb-ultima-12'),
(290, 1, 254, 'product/254', 'healthy-color-trio'),
(291, 1, 255, 'product/255', 'the-luster-natural-hair-elixir'),
(292, 1, 256, 'product/256', 'heavenly-luxe-complexion-perfection-makeup-brush-#7'),
(293, 1, 257, 'product/257', 'double-wear-light-soft-matte-hydra-makeup-1oz.'),
(294, 1, 258, 'product/258', '224s-tapered-blending-brush'),
(295, 1, 259, 'product/259', 'wispies-5pk.'),
(296, 1, 260, 'product/260', 'definicils-lengthening-and-defining-lengthening-and-volume-mascara-0.21-oz.'),
(297, 1, 261, 'product/261', 'theyre-real!-lengthening-mascara'),
(298, 1, 262, 'product/262', 'cl-de-peau-beaut-concealer-spf-25'),
(299, 1, 263, 'product/263', 'reset-liquid-matte-foundation'),
(300, 1, 264, 'product/264', 'sculpting-pencil'),
(301, 1, 265, 'product/265', 'all--nothing-concealer'),
(302, 1, 266, 'product/266', 'addict-lip-maximizer-plumping-gloss'),
(303, 1, 267, 'product/267', 'lip-pencil'),
(304, 1, 268, 'product/268', 'teyana-taylor-lipstick'),
(305, 1, 269, 'product/269', 'eye-color-quad'),
(306, 1, 270, 'product/270', 'naked-ultraviolet-eyeshadow-palette'),
(307, 1, 271, 'product/271', 'coco-mademoiselle-eau-de-parfum-fragrance-collection'),
(308, 1, 272, 'product/272', 'si-passione-eau-de-parfum-4pc-gift-set'),
(309, 1, 273, 'product/273', 'bloom-ambrosia-di-fiori-eau-de-parfum-intense-1.6oz.'),
(310, 1, 274, 'product/274', 'miss-dior-absolutely-blooming-eau-de-parfum-rollerpearl-0.67oz.'),
(311, 1, 275, 'product/275', 'women-eau-de-parfum-spray-1.7oz.'),
(312, 1, 276, 'product/276', 'cashmere-mist-fragrance-collection'),
(313, 1, 277, 'product/277', '3pc.-rever-de-moi-dream-eau-de-parfum-gift-set'),
(314, 1, 278, 'product/278', 'mon-paris-eau-de-parfum-spray-1.6oz'),
(315, 1, 279, 'product/279', 'sexy-blossom-eau-de-parfum-spray-3.4-oz.'),
(316, 1, 280, 'product/280', '3pc.-jadore-eau-de-parfum-gift-set'),
(317, 1, 281, 'product/281', 'eau-de-parfum-spray-3.3-oz.'),
(318, 1, 282, 'product/282', 'candy-gloss-eau-de-toilette-spray-1oz.'),
(319, 1, 283, 'product/283', '3pc.-flowerbomb-mothers-day-gift-set'),
(320, 1, 284, 'product/284', 'good-girl-eau-de-parfum-spray-1-oz.'),
(321, 1, 285, 'product/285', 'euphoria-rollerball-.33-oz'),
(322, 1, 286, 'product/286', '3pc.-power-starters-antiwrinkle-set'),
(323, 1, 287, 'product/287', 'essentials-perfect-cleansing-oil-10.1-oz'),
(324, 1, 288, 'product/288', 'absolue-premium-bx-hand-spf-15-sunscreen-3.4-oz'),
(325, 1, 289, 'product/289', 'dramatically-different-moisturizing-lotion-with-pump-4.2-oz'),
(326, 1, 290, 'product/290', 'checks-and-balances-frothy-face-wash-5oz.'),
(327, 1, 291, 'product/291', 'mini-facial-toning-device'),
(328, 1, 292, 'product/292', 'clarifying-lotion--skin-type-2-13.5-oz'),
(329, 1, 293, 'product/293', 'bienfait-multivital-spf-30-day-cream-moisturizer-and-sunblock-1.7-oz.'),
(330, 1, 294, 'product/294', 'double-serum-complete-age-control-concentrate-1.6oz.'),
(331, 1, 295, 'product/295', 'benefiance-wrinkle-smoothing-eye-cream-0.51oz.'),
(332, 1, 296, 'product/296', 'vitamin-enriched-face-base-priming-moisturizer-1.7oz.'),
(333, 1, 297, 'product/297', 'tl-advanced-tightening-neck-cream-plus-1.7oz.'),
(334, 1, 298, 'product/298', 'acne-facial-cleanser-6oz.'),
(335, 1, 299, 'product/299', 'greek-yoghurt-foaming-cream-cleanser-5.07-fl-oz.'),
(336, 1, 300, 'product/300', 'luna-2'),
(337, 4, 1, 'blog/1', '8-things-to-know-before-starting-an-online-ecommerce-marketplace'),
(338, 4, 2, 'blog/2', 'coronavirus-(covid19)-impact-on-the-ecommerce-startups-&-yo!kart-offers-help'),
(339, 4, 3, 'blog/3', 'benefits-of-multilingual-and-multicurrency-features-of-yokart'),
(340, 4, 4, 'blog/4', 'a-glimpse-into-the-future-of-ecommerce-technology'),
(341, 4, 5, 'blog/5', 'why-startups-fail-addressing-the-top-5-reasons'),
(342, 4, 6, 'blog/6', 'the-hybrid-theory-progressive-web-apps-and-their-practicality'),
(343, 4, 7, 'blog/7', 'brand-marketing-vs-direct-marketing-the-early-stage-ecommerce-startup-dilemma'),
(344, 4, 8, 'blog/8', '10-startups-based-on-ecommerce-subscription-model'),
(345, 4, 9, 'blog/9', 'yield-maximum-results-from-b2c-ecommerce'),
(346, 4, 10, 'blog/10', '5-niche-ecommerce-businesses-you-can-start-with-yokart'),
(347, 4, 11, 'blog/11', 'subscription-box-marketplace--business-model-and-website-features'),
(348, 4, 12, 'blog/12', 'want-skyhigh-conversion-rate-for-your-ecommerce-store-dont-ignore-clueless-shoppers'),
(349, 4, 13, 'blog/13', '5-features-that-make-yokart-a-sellerfriendly-ecommerce-platform'),
(350, 4, 14, 'blog/14', 'how-to-use-customer-data-to-improve-conversion-of-your-ecommerce-store'),
(351, 4, 15, 'blog/15', '10-advantages-of-ecommerce-over-traditional-commerce'),
(352, 4, 16, 'blog/16', 'secrets-of-creating-a-killer-ecommerce-marketplace-logo-revealed'),
(353, 4, 17, 'blog/17', 'how-dynamic-product-recommendation-can-help-increase-sales-of-an-ecommerce-marketplace'),
(354, 4, 18, 'blog/18', 'top-chatbots-to-integrate-with-your-ecommerce-store-for-improved-conversion-rate'),
(355, 4, 19, 'blog/19', 'how-bootstrapped-startups-market-their-brand-on-a-low-budget-&-do-well'),
(356, 4, 20, 'blog/20', 'how-top-ecommerce-marketplaces-are-resuming-operations-amid-covid19'),
(357, 4, 21, 'blog/21', 'ecommerce-penetration-during-covid19-in-uae--popular-brands-and-shifting-consumers-interests'),
(358, 4, 22, 'blog/22', 'social-commerce-marketplace--types-features-and-factors-for-growth'),
(359, 4, 23, 'blog/23', 'top-selling-ecommerce-products-shifting-consumer-behaviour-and-changing-product-demand-amid-covid19'),
(360, 4, 24, 'blog/24', '5-crucial-steps-entrepreneurs-follow-to-start-a-new-ecommerce-business'),
(361, 4, 25, 'blog/25', 'how-to-choose-the-perfect-domain-name-for-ecommerce-website'),
(362, 4, 26, 'blog/26', 'how-to-effectively-design-social-media-post-for-ecommerce-marketplace'),
(363, 4, 27, 'blog/27', 'blog-integration-in-online-store-is-a-great-way-to-generate-more-traffic-&-sales'),
(364, 4, 28, 'blog/28', 'why-vertical-online-marketplace-model-is-gaining-more-popularity-over-the-horizontal-model'),
(365, 4, 29, 'blog/29', 'yo!kart-partners-with-easyecom-for-seamless-multichannel-selling-in-online-marketplaces'),
(366, 4, 30, 'blog/30', 'yokart-guarantees-scalability-&-performance-of-your-ecommerce-store'),
(367, 4, 31, 'blog/31', 'brands-that-started-as-a-single-vendor-store-but-became-an-online-marketplace'),
(368, 4, 32, 'blog/32', 'yo!kart-is-soon-unveiling-tribe-an-ecommerce-store-software'),
(369, 2, 1, 'page/1', 'home'),
(370, 2, 2, 'page/2', 'about-us'),
(371, 2, 3, 'page/3', 'terms'),
(372, 2, 4, 'page/4', 'contact-us'),
(373, 2, 7, 'page/7', 'privacy-policy'),
(374, 2, 8, 'page/8', 'faqs'),
(375, 5, 1, 'brand/1', 'dkny'),
(376, 5, 2, 'brand/2', 'levis'),
(377, 5, 3, 'brand/3', 'nike'),
(378, 5, 4, 'brand/4', 'karen-scott'),
(379, 5, 5, 'brand/5', 'tommy-hilfiger'),
(380, 5, 6, 'brand/6', 'adidas'),
(381, 5, 7, 'brand/7', 'michael-kors'),
(382, 5, 8, 'brand/8', 'calvin-klein'),
(383, 5, 9, 'brand/9', 'guess'),
(384, 5, 10, 'brand/10', 'mango'),
(385, 5, 11, 'brand/11', 'bebe'),
(386, 5, 12, 'brand/12', 'mac-duggal'),
(387, 5, 13, 'brand/13', 'superdry'),
(388, 5, 14, 'brand/14', 'style-&-co'),
(389, 5, 15, 'brand/15', 'cotton-on'),
(390, 5, 16, 'brand/16', 'lauren-ralph-lauren'),
(391, 5, 17, 'brand/17', 'danielle-bernstein'),
(392, 5, 18, 'brand/18', 'fila'),
(393, 5, 19, 'brand/19', 'converse'),
(394, 5, 20, 'brand/20', 'new-balance'),
(395, 5, 21, 'brand/21', 'steve-madden'),
(396, 5, 22, 'brand/22', 'skechers'),
(397, 5, 23, 'brand/23', 'clarks'),
(398, 5, 24, 'brand/24', 'kenneth-cole'),
(399, 5, 25, 'brand/25', 'anne-klein'),
(400, 5, 26, 'brand/26', 'sun--stone'),
(401, 5, 27, 'brand/27', 'coach'),
(402, 5, 28, 'brand/28', 'giani-bernini'),
(403, 5, 29, 'brand/29', 'hush-puppies'),
(404, 5, 30, 'brand/30', 'ugg'),
(405, 5, 31, 'brand/31', 'kate-spade-new-york'),
(406, 5, 32, 'brand/32', 'kipling'),
(407, 5, 33, 'brand/33', 'radley-london'),
(408, 5, 34, 'brand/34', 'fossil'),
(409, 5, 35, 'brand/35', 'capezio'),
(410, 5, 36, 'brand/36', 'spanx'),
(411, 5, 37, 'brand/37', 'hue'),
(412, 5, 38, 'brand/38', 'wolford'),
(413, 5, 39, 'brand/39', 'hot-sox'),
(414, 5, 40, 'brand/40', 'isotoner-signature'),
(415, 5, 41, 'brand/41', 'vogue'),
(416, 5, 42, 'brand/42', 'rayban'),
(417, 5, 43, 'brand/43', 'dolce-&-gabbana'),
(418, 5, 44, 'brand/44', 'oakley'),
(419, 5, 45, 'brand/45', 'bvlgari'),
(420, 5, 46, 'brand/46', 'versace'),
(421, 5, 47, 'brand/47', 'tory-burch'),
(422, 5, 48, 'brand/48', 'miu-miu'),
(423, 5, 49, 'brand/49', 'valentino'),
(424, 5, 50, 'brand/50', 'prada'),
(425, 5, 51, 'brand/51', 'ralph-lauren'),
(426, 5, 52, 'brand/52', 'gucci'),
(427, 5, 53, 'brand/53', 'giorgio-armani'),
(428, 5, 54, 'brand/54', 'nautica'),
(429, 5, 55, 'brand/55', 'tag-heuer'),
(430, 5, 56, 'brand/56', 'tissot'),
(431, 5, 57, 'brand/57', 'lacoste'),
(432, 5, 58, 'brand/58', 'emporio-armani'),
(433, 5, 59, 'brand/59', 'swarovski'),
(434, 5, 60, 'brand/60', 'effy-collection'),
(435, 5, 61, 'brand/61', 'two-souls-one-love'),
(436, 5, 62, 'brand/62', 'le-vian'),
(437, 5, 63, 'brand/63', 'trumiracle'),
(438, 5, 64, 'brand/64', 'arabella'),
(439, 5, 65, 'brand/65', 'forever-grown-diamonds'),
(440, 5, 66, 'brand/66', 'kiehls-since-1851'),
(441, 5, 67, 'brand/67', 'croc'),
(442, 5, 68, 'brand/68', 'nioxin'),
(443, 5, 69, 'brand/69', 'babyliss'),
(444, 5, 70, 'brand/70', 'drybar'),
(445, 5, 71, 'brand/71', 'paul-mitchell'),
(446, 5, 72, 'brand/72', 't3'),
(447, 5, 73, 'brand/73', 'mason-pearson'),
(448, 5, 74, 'brand/74', 'kenra-professional'),
(449, 5, 75, 'brand/75', 'purecode'),
(450, 5, 76, 'brand/76', 'hairmax'),
(451, 5, 77, 'brand/77', 'evolvh'),
(452, 5, 78, 'brand/78', 'addicted-beauty'),
(453, 5, 79, 'brand/79', 'it-cosmetics'),
(454, 5, 80, 'brand/80', 'este-lauder'),
(455, 5, 81, 'brand/81', 'mac'),
(456, 5, 82, 'brand/82', 'ardell'),
(457, 5, 83, 'brand/83', 'lancme'),
(458, 5, 84, 'brand/84', 'benefit-cosmetics'),
(459, 5, 85, 'brand/85', 'cle-de-peau'),
(460, 5, 86, 'brand/86', 'haleys-beauty'),
(461, 5, 87, 'brand/87', 'nudestix'),
(462, 5, 88, 'brand/88', 'pyt-beauty'),
(463, 5, 89, 'brand/89', 'dior'),
(464, 5, 90, 'brand/90', 'tom-ford'),
(465, 5, 91, 'brand/91', 'urban-decay'),
(466, 5, 92, 'brand/92', 'chanel'),
(467, 5, 93, 'brand/93', 'donna-karan'),
(468, 5, 94, 'brand/94', 'catherine-malandrino'),
(469, 5, 95, 'brand/95', 'yves-saint-laurent'),
(470, 5, 96, 'brand/96', 'jimmy-choo'),
(471, 5, 97, 'brand/97', 'viktor-&-rolf'),
(472, 5, 98, 'brand/98', 'carolina-herrera'),
(473, 5, 99, 'brand/99', 'strivectin'),
(474, 5, 100, 'brand/100', 'shiseido'),
(475, 5, 101, 'brand/101', 'clinique'),
(476, 5, 102, 'brand/102', 'origins'),
(477, 5, 103, 'brand/103', 'nuface'),
(478, 5, 104, 'brand/104', 'clarins'),
(479, 5, 105, 'brand/105', 'bobbi-brown'),
(480, 5, 106, 'brand/106', 'mario-badescu'),
(481, 5, 107, 'brand/107', 'korres'),
(482, 5, 108, 'brand/108', 'foreo'),
(483, 5, 109, 'brand/109', '1.state'),
(484, 5, 110, 'brand/110', 'bcbgeneration'),
(485, 5, 111, 'brand/111', 'madden-girl'),
(486, 1, 301, 'product/301', 'burgundy-solid-tote-bag'),
(487, 1, 302, 'product/302', 'calvin-klein-printed-handheld-bag'),
(488, 5, 112, 'brand/112', 'aldo'),
(489, 1, 303, 'product/303', 'kenneth-cole-beige-solid-handheld-bag'),
(490, 1, 304, 'product/304', 'aldo-pink-textured-sling-bag'),
(491, 5, 113, 'brand/113', 'de-beers'),
(492, 1, 305, 'product/305', 'aura-round-brilliant-diamond-studs'),
(493, 1, 306, 'product/306', 'enchanted-lotus-sleepers-in-rose-gold'),
(494, 1, 307, 'product/307', 'db-classic-emeraldcut-diamond-studs'),
(495, 1, 308, 'product/308', 'dewdrop-ear-cuff-in-white-gold'),
(496, 5, 114, 'brand/114', 'daniel-wellington'),
(497, 1, 309, 'product/309', 'petite-melrose'),
(498, 1, 310, 'product/310', 'petite-sheffield'),
(499, 1, 311, 'product/311', 'gen-5e-darci-pav-goldtone-smartwatch'),
(500, 1, 312, 'product/312', 'park-watch-34mm'),
(502, 5, 115, 'brand/115', 'tiffany-co.'),
(503, 1, 314, 'product/314', 'tiffany-victoria-ring'),
(504, 1, 315, 'product/315', 'sixteen-stone-ring'),
(505, 1, 316, 'product/316', 'x-closed-narrow-ring'),
(506, 1, 317, 'product/317', 'palomas-studio-hexagon-ring');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
