-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 28, 2015 at 01:18 PM
-- Server version: 5.5.38
-- PHP Version: 5.4.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `sessiondb`
--

-- --------------------------------------------------------

--
-- Table structure for table `gbcollector`
--

CREATE TABLE `gbcollector` (
`gcid` int(10) unsigned NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `number` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;



/*!40000 ALTER TABLE `gbcollector` DISABLE KEYS */;
INSERT INTO `gbcollector` (`gcid`,`fname`,`lname`,`city`,`type`,`number`, `password`) VALUES 
 (1,'Chamara','Patirana','Colombo','Paper','789456123', '202cb962ac59075b964b07152d234b70'),
 (2,'Sirisena','Kuree','Mihintale','Compostable','123456789', '202cb962ac59075b964b07152d234b70'),
 (3,'Duminda','Dissanayaka','Matara','Glass','789123456', ''),
 (4,'Gayan','Perera','Gall','Paper','456123789', ''),
 (5,'Sadun','Perera','Kurunagala','Paper','147258369', '');
/*!40000 ALTER TABLE `gbcollector` ENABLE KEYS */;


-- --------------------------------------------------------

--
-- Table structure for table `individual`
--

CREATE TABLE `individual` (
`id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `lan` float NOT NULL,
  `lon` float NOT NULL,
  `gtype` int(11) NOT NULL,
  `assigned` int(11) NOT NULL DEFAULT '0',
  `collected` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `individual`
--

INSERT INTO `individual` (`id`, `number`, `lan`, `lon`, `gtype`, `assigned`, `collected`) VALUES
(1, 716767333, 8.3332, 82.4151, 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sessionsid` varchar(100) NOT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `menu` varchar(50) DEFAULT NULL,
  `pg` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `others` varchar(50) DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Handle the sessions';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gbcollector`
--
ALTER TABLE `gbcollector`
 ADD PRIMARY KEY (`gcid`);

--
-- Indexes for table `individual`
--
ALTER TABLE `individual`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
 ADD PRIMARY KEY (`sessionsid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gbcollector`
--
ALTER TABLE `gbcollector`
MODIFY `gcid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `individual`
--
ALTER TABLE `individual`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;