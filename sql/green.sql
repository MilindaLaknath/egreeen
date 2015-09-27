-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 22, 2015 at 02:13 PM
-- Server version: 5.5.27-log
-- PHP Version: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `green`
--

-- --------------------------------------------------------

--
-- Table structure for table `bin_tb`
--

CREATE TABLE IF NOT EXISTS `bin_tb` (
  `idbin_tb` int(11) NOT NULL AUTO_INCREMENT,
  `bin_id` varchar(200) DEFAULT NULL COMMENT 'bin Mob Number',
  `type` int(11) DEFAULT NULL,
  `gcid` int(11) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `collected` tinyint(4) NOT NULL DEFAULT '0',
  `sms` tinyint(4) NOT NULL DEFAULT '0',
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idbin_tb`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `bin_tb`
--

INSERT INTO `bin_tb` (`idbin_tb`, `bin_id`, `type`, `gcid`, `lat`, `lng`, `collected`, `sms`, `date_time`) VALUES
(6, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-13 23:36:48'),
(7, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-13 23:50:36'),
(8, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-14 03:00:53'),
(9, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-14 03:06:03'),
(10, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-14 05:13:38'),
(11, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-14 05:15:44'),
(12, 'tel:B%3C4daplYzRv4VqjRZ5+roQNu2KoCsBSo2r4XW+zPS6AFTxUTyzdkTOahMKWuXp1+QRb', 1, 1, 6.882278, 79.856194, 0, 0, '2015-06-14 05:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `collectables`
--

CREATE TABLE IF NOT EXISTS `collectables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `collectables`
--

INSERT INTO `collectables` (`id`, `type`) VALUES
(1, 'Paper'),
(2, 'Glass'),
(3, 'Plastic'),
(4, 'Metal'),
(5, 'e-Waste');

-- --------------------------------------------------------

--
-- Table structure for table `collector_number`
--

CREATE TABLE IF NOT EXISTS `collector_number` (
  `gcid` int(11) NOT NULL,
  `number` varchar(15) NOT NULL,
  PRIMARY KEY (`gcid`,`number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collector_number`
--

INSERT INTO `collector_number` (`gcid`, `number`) VALUES
(1, '94777114590'),
(2, '94767848343'),
(3, '94767848343'),
(4, '94777999245');

-- --------------------------------------------------------

--
-- Table structure for table `collector_types`
--

CREATE TABLE IF NOT EXISTS `collector_types` (
  `collector_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`collector_id`,`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collector_types`
--

INSERT INTO `collector_types` (`collector_id`, `type_id`) VALUES
(1, 1),
(1, 4),
(1, 5),
(2, 1),
(2, 2),
(3, 1),
(3, 3),
(4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `gbcollector`
--

CREATE TABLE IF NOT EXISTS `gbcollector` (
  `gcid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `username` varchar(15) NOT NULL,
  `organization` varchar(50) NOT NULL,
  `city` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  PRIMARY KEY (`gcid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `gbcollector`
--

INSERT INTO `gbcollector` (`gcid`, `fname`, `lname`, `username`, `organization`, `city`, `password`, `lat`, `lng`) VALUES
(1, 'Thejan', 'Rajapakshe', '0767848343', 'Brightron Solutions', 'Colombo', 'e10adc3949ba59abbe56e057f20f883e', 7.48777, 80.3645),
(2, 'Tilina', 'Perera', '0777146789', 'E-Waste Sol', 'Wellawatta', 'e10adc3949ba59abbe56e057f20f883e', 6.88238, 79.8559),
(3, 'Camal', 'Fdo', '0777146321', 'Paper Waste INC', 'Wellawatta', 'e10adc3949ba59abbe56e057f20f883e', 6.88238, 79.8565),
(4, 'Ceylon', 'Waste', '0777146654', 'Ceylon Waste Management Private Ltd', 'Wellawatta', 'e10adc3949ba59abbe56e057f20f883e', 6.88237, 79.8558);

-- --------------------------------------------------------

--
-- Table structure for table `individual`
--

CREATE TABLE IF NOT EXISTS `individual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(100) NOT NULL,
  `gtype` int(11) NOT NULL,
  `assigned` int(11) NOT NULL DEFAULT '0' COMMENT 'colector id',
  `collected` tinyint(1) DEFAULT '0',
  `pin` int(11) DEFAULT NULL,
  `sms` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `individual`
--

INSERT INTO `individual` (`id`, `sessionid`, `gtype`, `assigned`, `collected`, `pin`, `sms`) VALUES
(1, '716767333', 1, 2, 0, NULL, 0),
(2, '1234', 1, 1, 0, 154, 0),
(3, '123', 1, 1, 0, 35763, 0),
(4, '123', 1, 1, 0, 18410, 0),
(5, '150614031029000', 1, 3, 0, 15324, 0),
(6, '150614040119000', 1, 3, 0, 76822, 0),
(7, '150614052613000', 1, 3, 0, 78619, 0),
(8, '150614053355001', 1, 3, 0, 74972, 0),
(9, '150614055655000', 1, 3, 0, 29069, 0),
(10, '150614061032000', 1, 3, 0, 36611, 0),
(11, '150614061313000', 1, 3, 0, 6210, 0),
(12, '150614061739000', 1, 3, 0, 84425, 0),
(13, '150614064746000', 1, 3, 0, 74586, 0),
(14, '150614075049001', 1, 2, 0, 97007, 0),
(15, '150614081839002', 1, 2, 0, 18020, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sessionsid` varchar(100) NOT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `menu` varchar(50) DEFAULT NULL,
  `pg` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `others` varchar(50) DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  PRIMARY KEY (`sessionsid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Handle the sessions';

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sessionsid`, `tel`, `menu`, `pg`, `created_at`, `others`, `longitude`, `latitude`) VALUES
('150614053355001', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 00:03:57', '', 79.856194, 6.882278),
('150614055655000', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 00:26:57', '', 79.856194, 6.882278),
('150614061032000', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 00:40:34', '', 79.856194, 6.882278),
('150614061313000', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 00:43:15', '', 79.856194, 6.882278),
('150614061739000', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 00:47:41', '', 79.856194, 6.882278),
('150614064746000', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 01:17:49', '', 79.856194, 6.882278),
('150614075049001', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 02:20:51', '', 79.856194, 6.882278),
('150614081839002', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'main', '0', '2015-06-14 02:48:41', '', 79.856194, 6.882278),
('150614114442006', 'tel:AZ110dZo+G+ZuJQKFwEu/NWWUILq4g9HSkVAf6Ezmz2Nvy', 'sub', '0', '2015-06-14 06:14:44', '', 0, 0),
('150614114446000', 'tel:AZ110JmqP0+XHkb7YRtNh51royZADuCRR7UgBYIdfKvVup', 'main', '', '2015-06-14 06:14:48', '', 0, 0),
('150615203047001', 'tel:AZ110VGbOgb4QcvpTW5WuGnr8QaRQOTibFnxlzNFkbfyJx', 'sub', '0', '2015-06-15 15:00:49', '', 0, 0),
('150615203131002', 'tel:AZ110VGbOgb4QcvpTW5WuGnr8QaRQOTibFnxlzNFkbfyJx', 'sub', '0', '2015-06-15 15:01:33', '', 0, 0),
('150630005017002', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'sub', '0', '2015-06-29 19:20:19', '', 80.518827, 8.361347),
('150630101118005', 'tel:AZ110HLLpAHLMKnwYJQS0IfNsF8oIs4b78D1bMKYC1GWdC', 'sub', '0', '2015-06-30 04:41:20', '', 80.518827, 8.361347),
('44090549', 'tel:AZ110hjRMcYRD9CndDfNnb7c/ugg1Qbxez2iU9mwMwilh2', 'sub', '0', '2015-07-11 16:40:17', '', 0, 0),
('44196954', 'tel:B%3C49vKXhx7erXspVmXaFVr0PmJ0Pw0Dvlb4z78WIzamp', 'main', '', '2015-07-12 02:02:24', '', 0, 0),
('56879500', 'tel:B%3C4xC2tBlzSRE9KSyjkdpGG//lld757q31NcLAcP8r/X', 'main', '', '2015-07-26 04:06:17', '', 0, 0),
('56880184', 'tel:B%3C4xC2tBlzSRE9KSyjkdpGG//lld757q31NcLAcP8r/X', 'main', '', '2015-07-26 04:07:05', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_save`
--

CREATE TABLE IF NOT EXISTS `tmp_save` (
  `idtmp_save` int(11) NOT NULL AUTO_INCREMENT,
  `sessid` varchar(100) DEFAULT NULL,
  `countnum` int(2) DEFAULT NULL,
  `gcid` int(11) DEFAULT NULL,
  `type` int(3) DEFAULT NULL,
  PRIMARY KEY (`idtmp_save`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `tmp_save`
--

INSERT INTO `tmp_save` (`idtmp_save`, `sessid`, `countnum`, `gcid`, `type`) VALUES
(2, '150614030416000', 1, 3, 1),
(3, '150614030416000', 2, 2, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
