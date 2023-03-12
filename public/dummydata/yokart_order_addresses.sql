-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2021 at 01:04 AM
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
-- Dumping data for table `yokart_order_addresses`
--

INSERT INTO `yokart_order_addresses` (`oaddr_order_id`, `oaddr_name`, `oaddr_email`, `oaddr_type`, `oaddr_address1`, `oaddr_address2`, `oaddr_city`, `oaddr_state`, `oaddr_country`, `oaddr_country_code`, `oaddr_postal_code`, `oaddr_phone_country_id`, `oaddr_phone`) VALUES
(10000, 'John Doe', '', 2, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10000, 'John Doe', '', 1, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10001, 'John Doe', '', 2, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10001, 'John Doe', '', 1, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10002, 'Tribe Store 1 ', '', 3, 'Plot no. 268, Sector-82, JLPL Industrial Area', '', 'Sahibzada Ajit Singh Nagar', 'Punjab', 'India', 'IN', '140308', 101, '09779200800'),
(10002, 'John Doe', '', 1, 'Beach Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78956', 101, '4578965987'),
(10003, 'John Doe', '', 2, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10003, 'John Doe', '', 1, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10004, 'Misha Langdon', 'misha@dummyid.com', 2, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10004, 'Misha Langdon', 'misha@dummyid.com', 1, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10005, 'Misha Hesson', 'mike@dummyid.com', 2, '1895', '1895  Harper Street', 'Paducah', 'Kentucky', 'United States', 'US', '42001', 231, '2702013860'),
(10005, 'Misha Langdon', 'misha@dummyid.com', 1, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10006, 'Tribe Store 2 ', '', 3, 'Unit No. A-712, Tower A, 7th Floor,\n              Bestech Business Towers, Sector-66', '', 'Mohali', 'Punjab', 'India', 'IN', '160066', 101, '08559008860'),
(10006, 'Misha Langdon', 'misha@dummyid.com', 1, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10007, 'Misha Langdon', 'misha@dummyid.com', 2, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10007, 'Misha Langdon', 'misha@dummyid.com', 1, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10008, 'Misha Langdon', 'misha@dummyid.com', 2, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10008, 'Misha Langdon', 'misha@dummyid.com', 1, '58 E.', 'LAKE STREET CHICAGO', 'Chicago', 'Illinois', 'United States', 'US', '60601', 231, '2454787777'),
(10009, 'Misha Hesson', 'mike@dummyid.com', 1, '1895', '1895  Harper Street', 'Paducah', 'Kentucky', 'United States', 'US', '42001', 231, '2702013860'),
(10009, 'Misha Hesson', 'mike@dummyid.com', 2, '1895', '1895  Harper Street', 'Paducah', 'Kentucky', 'United States', 'US', '42001', 231, '2702013860'),
(10010, 'Tribe Store 1 ', '', 3, 'Plot no. 268, Sector-82, JLPL Industrial Area', '', 'Sahibzada Ajit Singh Nagar', 'Punjab', 'India', 'IN', '140308', 101, '09779200800'),
(10010, 'John Doe', '', 1, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10011, 'Laura Wellis', 'laura@dummyid.com', 2, 'Home', '222 Margaret St, Brisbane', 'Brisbane', 'Queensland', 'Australia', 'AU', '4000', 13, '736900055'),
(10011, 'Laura Wellis', 'laura@dummyid.com', 1, 'Home', '222 Margaret St, Brisbane', 'Brisbane', 'Queensland', 'Australia', 'AU', '4000', 13, '736900055'),
(10012, 'Laura Wellis', 'laura@dummyid.com', 2, 'Home', '222 Margaret St, Brisbane', 'Brisbane', 'Queensland', 'Australia', 'AU', '4000', 13, '736900055'),
(10012, 'Laura Wellis', 'laura@dummyid.com', 1, 'Home', '222 Margaret St, Brisbane', 'Brisbane', 'Queensland', 'Australia', 'AU', '4000', 13, '736900055'),
(10013, 'Tribe Store 2 ', '', 3, 'Unit No. A-712, Tower A, 7th Floor,\n              Bestech Business Towers, Sector-66', '', 'Mohali', 'Punjab', 'India', 'IN', '160066', 101, '08559008860'),
(10013, 'Laura Wellis', 'laura@dummyid.com', 1, 'Home', '222 Margaret St, Brisbane', 'Brisbane', 'Queensland', 'Australia', 'AU', '4000', 13, '736900055'),
(10015, 'Laura Wellis', 'laura@dummyid.com', 1, 'Home', '222 Margaret St, Brisbane', 'Brisbane', 'Queensland', 'Australia', 'AU', '4000', 13, '736900055'),
(10015, 'Tribe Store 2 ', '', 3, 'Unit No. A-712, Tower A, 7th Floor,\n              Bestech Business Towers, Sector-66', '', 'Mohali', 'Punjab', 'India', '', '160066', 101, '08559008860'),
(10016, 'Tribe Store 2 ', '', 3, 'Unit No. A-712, Tower A, 7th Floor,\n              Bestech Business Towers, Sector-66', '', 'Mohali', 'Punjab', 'India', 'IN', '160066', 101, '08559008860'),
(10016, 'Paul Woods', 'paul@dummyid.com', 1, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10017, 'John Doe', '', 2, 'Beach Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78956', 101, '4578965987'),
(10017, 'John Doe', '', 1, 'Beach Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78956', 101, '4578965987'),
(10018, 'Cindy T', '', 2, 'Broadway Blvd', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78652', 101, '4588965234'),
(10018, 'Cindy T', '', 1, 'Broadway Blvd', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78652', 101, '4588965234'),
(10019, 'Paul Woods', 'paul@dummyid.com', 2, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10019, 'Paul Woods', 'paul@dummyid.com', 1, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10020, 'Tribe Store 1 ', '', 3, 'Plot no. 268, Sector-82, JLPL Industrial Area', '', 'Sahibzada Ajit Singh Nagar', 'Punjab', 'India', 'IN', '140308', 101, '09779200800'),
(10020, 'John Doe', '', 1, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10021, 'Paul Woods', 'paul@dummyid.com', 1, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10021, 'Tribe Store 1 ', '', 3, 'Plot no. 268, Sector-82, JLPL Industrial Area', '', 'Sahibzada Ajit Singh Nagar', 'Punjab', 'India', '', '140308', 101, '09779200800'),
(10022, 'Paul Woods', 'paul@dummyid.com', 2, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10022, 'Paul Woods', 'paul@dummyid.com', 1, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10023, 'Paul Woods', 'paul@dummyid.com', 1, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10023, 'Paul Woods', 'paul@dummyid.com', 2, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10024, 'Tribe Store 2 ', '', 3, 'Unit No. A-712, Tower A, 7th Floor,\n              Bestech Business Towers, Sector-66', '', 'Mohali', 'Punjab', 'India', 'IN', '160066', 101, '08559008860'),
(10024, 'Paul Woods', 'paul@dummyid.com', 1, '065', '535 8TH AVE NEW YORK', 'New York', 'New York', 'United States', 'US', '10018', 231, '7748476808'),
(10025, 'John Doe', '', 2, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10025, 'John Doe', '', 1, 'University Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '45684', 101, '7895456525'),
(10026, 'Tribe Store 1 ', '', 3, 'Plot no. 268, Sector-82, JLPL Industrial Area', '', 'Sahibzada Ajit Singh Nagar', 'Punjab', 'India', 'IN', '140308', 101, '09779200800'),
(10026, 'John Doe', '', 1, 'Beach Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78956', 101, '4578965987'),
(10027, 'Tribe Store 2 ', '', 3, 'Unit No. A-712, Tower A, 7th Floor,\n              Bestech Business Towers, Sector-66', '', 'Mohali', 'Punjab', 'India', 'IN', '160066', 101, '08559008860'),
(10027, 'John Doe', '', 1, 'Beach Drive', NULL, 'Mumbai', 'Maharashtra', 'India', 'IN', '78956', 101, '4578965987');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
