-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2016 at 04:11 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lelux`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `config_name` varchar(50) NOT NULL,
  `config_value` varchar(50) NOT NULL,
  `config_active_date_start` date NOT NULL DEFAULT '1999-01-01',
  `config_active_date_end` date NOT NULL DEFAULT '2999-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`config_name`, `config_value`, `config_active_date_start`, `config_active_date_end`) VALUES
('CONFIG_COMMISSION_RATE', '0.5', '1999-01-01', '2999-12-31'),
('CONFIG_MIN_REQUEST', '60', '1999-01-01', '2999-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `massage_record`
--

DROP TABLE IF EXISTS `massage_record`;
CREATE TABLE `massage_record` (
  `massage_record_id` int(11) NOT NULL,
  `therapist_id` smallint(6) NOT NULL,
  `massage_record_minutes` smallint(6) NOT NULL,
  `massage_record_time_in` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `massage_record_time_out` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `massage_record_requested` tinyint(1) NOT NULL,
  `massage_record_request_reward` decimal(10,2) NOT NULL,
  `massage_record_promotion` tinyint(1) NOT NULL,
  `massage_record_commission` decimal(10,2) NOT NULL,
  `massage_record_cash` decimal(10,2) NOT NULL,
  `massage_record_credit` decimal(10,2) NOT NULL,
  `massage_record_hicaps` decimal(10,2) NOT NULL,
  `massage_record_stamp` smallint(6) NOT NULL,
  `massage_record_voucher` decimal(10,2) NOT NULL,
  `massage_record_date` date NOT NULL,
  `massage_record_create_user` smallint(6) NOT NULL,
  `massage_record_create_datetime` datetime NOT NULL,
  `massage_record_update_user` smallint(6) NOT NULL,
  `massage_record_update_datetime` datetime NOT NULL,
  `massage_record_void_user` smallint(6) NOT NULL,
  `massage_record_void_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request_condition`
--

DROP TABLE IF EXISTS `request_condition`;
CREATE TABLE `request_condition` (
  `request_condition_request` tinyint(1) NOT NULL,
  `request_condition_promotion` tinyint(1) NOT NULL,
  `request_condition_stamp` tinyint(1) NOT NULL,
  `request_condition_amt` decimal(10,2) NOT NULL,
  `request_condition_active_date_start` date NOT NULL DEFAULT '1999-01-01',
  `request_condition_active_date_end` date NOT NULL DEFAULT '2999-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `request_condition`
--

INSERT INTO `request_condition` (`request_condition_request`, `request_condition_promotion`, `request_condition_stamp`, `request_condition_amt`, `request_condition_active_date_start`, `request_condition_active_date_end`) VALUES
(0, 0, 0, '0.00', '1999-01-01', '2015-12-31'),
(0, 0, 0, '0.00', '2016-01-01', '2999-12-31'),
(0, 0, 1, '0.00', '1999-01-01', '2015-12-31'),
(0, 1, 0, '0.00', '1999-01-01', '2015-12-31'),
(0, 1, 0, '0.00', '2016-01-01', '2999-12-31'),
(0, 1, 1, '0.00', '1999-01-01', '2015-12-31'),
(1, 0, 0, '5.00', '1999-01-01', '2015-12-31'),
(1, 0, 0, '5.00', '2016-01-01', '2999-12-31'),
(1, 0, 1, '0.00', '1999-01-01', '2015-12-31'),
(1, 1, 0, '0.00', '1999-01-01', '2015-12-31'),
(1, 1, 0, '0.00', '2016-01-01', '2999-12-31'),
(1, 1, 1, '0.00', '1999-01-01', '2015-12-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `massage_record`
--
ALTER TABLE `massage_record`
  ADD PRIMARY KEY (`massage_record_id`);

--
-- Indexes for table `request_condition`
--
ALTER TABLE `request_condition`
  ADD PRIMARY KEY (`request_condition_request`,`request_condition_promotion`,`request_condition_stamp`,`request_condition_active_date_start`,`request_condition_active_date_end`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `massage_record`
--
ALTER TABLE `massage_record`
  MODIFY `massage_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
