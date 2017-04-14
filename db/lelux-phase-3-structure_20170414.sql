-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2017 at 04:11 AM
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
CREATE DATABASE IF NOT EXISTS `lelux` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `lelux`;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `client_id` varchar(23) NOT NULL,
  `health_fund_id` int(11) NOT NULL,
  `client_membership_no` varchar(10) NOT NULL,
  `client_patient_id` tinyint(4) NOT NULL,
  `client_first_name` varchar(50) NOT NULL,
  `client_last_name` varchar(50) NOT NULL,
  `client_gender` bit(1) NOT NULL,
  `client_address` varchar(250) NOT NULL,
  `client_postcode` varchar(4) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `client_contact_no` varchar(12) NOT NULL,
  `client_birthday` date NOT NULL,
  `client_occupation` varchar(50) NOT NULL,
  `client_sports` varchar(50) NOT NULL,
  `client_other_conditions` varchar(50) NOT NULL,
  `client_emergency_contact_name` varchar(50) NOT NULL,
  `client_emergency_contact_no` varchar(12) NOT NULL,
  `client_create_datetime` datetime NOT NULL,
  `client_create_user` tinyint(4) NOT NULL,
  `client_update_datetime` datetime NOT NULL,
  `client_update_user` tinyint(4) NOT NULL,
  `client_void_datetime` datetime NOT NULL,
  `client_void_user` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client_condition`
--

DROP TABLE IF EXISTS `client_condition`;
CREATE TABLE `client_condition` (
  `client_id` varchar(23) NOT NULL,
  `condition_type_id` tinyint(4) NOT NULL,
  `client_condition_remark` varchar(50) NOT NULL,
  `client_condition_checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client_finding`
--

DROP TABLE IF EXISTS `client_finding`;
CREATE TABLE `client_finding` (
  `client_id` varchar(23) NOT NULL,
  `finding_type_id` tinyint(4) NOT NULL,
  `client_finding_remark` varchar(50) NOT NULL,
  `client_finding_checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `condition_type`
--

DROP TABLE IF EXISTS `condition_type`;
CREATE TABLE `condition_type` (
  `condition_type_id` tinyint(4) NOT NULL,
  `condition_type_name` varchar(50) NOT NULL,
  `condition_type_suffix` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `finding_type`
--

DROP TABLE IF EXISTS `finding_type`;
CREATE TABLE `finding_type` (
  `finding_type_id` tinyint(4) NOT NULL,
  `finding_type_name` varchar(50) NOT NULL,
  `finding_type_suffix` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `health_fund`
--

DROP TABLE IF EXISTS `health_fund`;
CREATE TABLE `health_fund` (
  `health_fund_id` int(11) NOT NULL,
  `health_fund_name` varchar(50) NOT NULL,
  `health_fund_provider_no` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `massage_record`
--

DROP TABLE IF EXISTS `massage_record`;
CREATE TABLE `massage_record` (
  `massage_record_id` int(11) NOT NULL,
  `therapist_id` smallint(6) NOT NULL,
  `massage_type_id` int(11) NOT NULL,
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
-- Table structure for table `massage_type`
--

DROP TABLE IF EXISTS `massage_type`;
CREATE TABLE `massage_type` (
  `massage_type_id` int(11) NOT NULL,
  `massage_type_name` varchar(30) NOT NULL,
  `massage_type_commission` int(11) NOT NULL,
  `massage_type_active` tinyint(1) NOT NULL,
  `massage_type_update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

DROP TABLE IF EXISTS `membership`;
CREATE TABLE `membership` (
  `membership_no` varchar(10) NOT NULL,
  `membership_patient_id` tinyint(4) NOT NULL,
  `client_id` int(11) NOT NULL,
  `health_fund_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_price_changeable` tinyint(1) NOT NULL,
  `product_stock_amt` int(11) NOT NULL,
  `product_img` varchar(50) NOT NULL,
  `product_active` tinyint(1) NOT NULL,
  `product_update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

DROP TABLE IF EXISTS `provider`;
CREATE TABLE `provider` (
  `provider_id` int(11) NOT NULL,
  `provider_no` varchar(20) NOT NULL,
  `provider_name` varchar(50) NOT NULL,
  `provider_update_datetime` datetime NOT NULL,
  `provider_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reception_record`
--

DROP TABLE IF EXISTS `reception_record`;
CREATE TABLE `reception_record` (
  `reception_record_id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `reception_record_date` date NOT NULL,
  `reception_record_late_night` tinyint(1) NOT NULL,
  `reception_record_whole_day` tinyint(1) NOT NULL,
  `reception_record_hour` tinyint(4) NOT NULL,
  `reception_record_shop_income` decimal(10,2) NOT NULL,
  `reception_record_std_com` decimal(10,2) NOT NULL,
  `reception_record_extra_com` decimal(10,2) NOT NULL,
  `reception_record_total_com` decimal(10,2) NOT NULL,
  `reception_record_create_user` int(11) NOT NULL,
  `reception_record_create_datetime` datetime NOT NULL,
  `reception_record_update_user` int(11) NOT NULL,
  `reception_record_update_datetime` datetime NOT NULL,
  `reception_record_void_user` int(11) NOT NULL,
  `reception_record_void_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `report_id` varchar(23) NOT NULL,
  `report_date` date NOT NULL,
  `report_detail` text NOT NULL,
  `report_recommendation` text NOT NULL,
  `report_hour` decimal(10,2) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `membership_no` int(11) NOT NULL,
  `pateint_id` int(11) NOT NULL,
  `report_create_datetime` datetime NOT NULL,
  `report_create_user` tinyint(4) NOT NULL,
  `report_update_datetime` datetime NOT NULL,
  `report_update_user` tinyint(4) NOT NULL,
  `report_void_datetime` datetime NOT NULL,
  `report_void_user` tinyint(4) NOT NULL,
  `client_id` varchar(23) NOT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

DROP TABLE IF EXISTS `sale`;
CREATE TABLE `sale` (
  `sale_id` int(11) NOT NULL,
  `sale_uid` varchar(23) NOT NULL,
  `sale_date` date NOT NULL,
  `sale_time` time NOT NULL,
  `sale_total` decimal(10,2) NOT NULL,
  `sale_cash` decimal(10,2) NOT NULL,
  `sale_credit` decimal(10,2) NOT NULL,
  `sale_create_user` smallint(6) NOT NULL,
  `sale_create_datetime` datetime NOT NULL,
  `sale_update_user` smallint(6) NOT NULL,
  `sale_update_datetime` datetime NOT NULL,
  `sale_void_user` smallint(6) NOT NULL,
  `sale_void_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sale_item`
--

DROP TABLE IF EXISTS `sale_item`;
CREATE TABLE `sale_item` (
  `sale_item_id` int(11) NOT NULL,
  `sale_uid` varchar(23) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sale_item_amount` smallint(6) NOT NULL,
  `sale_item_price` decimal(10,2) NOT NULL,
  `sale_item_total` decimal(10,2) NOT NULL,
  `sale_item_create_user` smallint(6) NOT NULL,
  `sale_item_create_datetime` datetime NOT NULL,
  `sale_item_update_user` smallint(6) NOT NULL,
  `sale_item_update_datetime` datetime NOT NULL,
  `sale_item_void_user` smallint(6) NOT NULL,
  `sale_item_void_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `therapist`
--

DROP TABLE IF EXISTS `therapist`;
CREATE TABLE `therapist` (
  `therapist_id` smallint(6) NOT NULL,
  `therapist_name` varchar(50) NOT NULL,
  `therapist_username` varchar(10) NOT NULL,
  `therapist_password` varchar(50) NOT NULL,
  `therapist_permission` tinyint(4) NOT NULL COMMENT '9 = admin, 1 = staff',
  `therapist_update_datetime` datetime NOT NULL,
  `therapist_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_condition`
--
ALTER TABLE `client_condition`
  ADD PRIMARY KEY (`condition_type_id`,`client_id`);

--
-- Indexes for table `client_finding`
--
ALTER TABLE `client_finding`
  ADD PRIMARY KEY (`finding_type_id`,`client_id`);

--
-- Indexes for table `condition_type`
--
ALTER TABLE `condition_type`
  ADD PRIMARY KEY (`condition_type_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `finding_type`
--
ALTER TABLE `finding_type`
  ADD PRIMARY KEY (`finding_type_id`);

--
-- Indexes for table `health_fund`
--
ALTER TABLE `health_fund`
  ADD PRIMARY KEY (`health_fund_id`);

--
-- Indexes for table `massage_record`
--
ALTER TABLE `massage_record`
  ADD PRIMARY KEY (`massage_record_id`);

--
-- Indexes for table `massage_type`
--
ALTER TABLE `massage_type`
  ADD PRIMARY KEY (`massage_type_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_no`,`membership_patient_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`provider_id`);

--
-- Indexes for table `reception_record`
--
ALTER TABLE `reception_record`
  ADD PRIMARY KEY (`reception_record_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `request_condition`
--
ALTER TABLE `request_condition`
  ADD PRIMARY KEY (`request_condition_request`,`request_condition_promotion`,`request_condition_stamp`,`request_condition_active_date_start`,`request_condition_active_date_end`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`sale_id`);

--
-- Indexes for table `sale_item`
--
ALTER TABLE `sale_item`
  ADD PRIMARY KEY (`sale_item_id`);

--
-- Indexes for table `therapist`
--
ALTER TABLE `therapist`
  ADD PRIMARY KEY (`therapist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `health_fund`
--
ALTER TABLE `health_fund`
  MODIFY `health_fund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `massage_record`
--
ALTER TABLE `massage_record`
  MODIFY `massage_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6895;
--
-- AUTO_INCREMENT for table `massage_type`
--
ALTER TABLE `massage_type`
  MODIFY `massage_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `reception_record`
--
ALTER TABLE `reception_record`
  MODIFY `reception_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `sale_item`
--
ALTER TABLE `sale_item`
  MODIFY `sale_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `therapist`
--
ALTER TABLE `therapist`
  MODIFY `therapist_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
