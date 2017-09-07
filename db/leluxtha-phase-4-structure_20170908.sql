-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2017 at 01:04 AM
-- Server version: 10.0.31-MariaDB-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `leluxtha_support`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `booking_id` varchar(23) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time_in` datetime NOT NULL,
  `booking_time_out` datetime NOT NULL,
  `booking_name` varchar(30) NOT NULL,
  `booking_tel` varchar(15) NOT NULL,
  `booking_client` tinyint(4) NOT NULL,
  `booking_create_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `booking_update_datetime` datetime NOT NULL,
  `booking_status_id` tinyint(4) NOT NULL COMMENT '1 = waiting, 2 = came',
  `booking_remark` varchar(100) NOT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `booking_item`
--

DROP TABLE IF EXISTS `booking_item`;
CREATE TABLE IF NOT EXISTS `booking_item` (
  `booking_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(23) NOT NULL,
  `therapist_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '0 = Any',
  `massage_type_id` tinyint(4) NOT NULL,
  `booking_item_status` tinyint(4) NOT NULL COMMENT '1 = coming, 2 = came',
  PRIMARY KEY (`booking_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=870 ;

-- --------------------------------------------------------

--
-- Table structure for table `booking_room`
--

DROP TABLE IF EXISTS `booking_room`;
CREATE TABLE IF NOT EXISTS `booking_room` (
  `booking_id` varchar(23) NOT NULL,
  `room_type_id` tinyint(4) NOT NULL,
  `booking_room_amount` tinyint(4) NOT NULL,
  PRIMARY KEY (`booking_id`,`room_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
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
  `client_void_user` tinyint(4) NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client_condition`
--

DROP TABLE IF EXISTS `client_condition`;
CREATE TABLE IF NOT EXISTS `client_condition` (
  `client_id` varchar(23) NOT NULL,
  `condition_type_id` tinyint(4) NOT NULL,
  `client_condition_remark` varchar(50) NOT NULL,
  `client_condition_checked` tinyint(1) NOT NULL,
  PRIMARY KEY (`condition_type_id`,`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client_finding`
--

DROP TABLE IF EXISTS `client_finding`;
CREATE TABLE IF NOT EXISTS `client_finding` (
  `client_id` varchar(23) NOT NULL,
  `finding_type_id` tinyint(4) NOT NULL,
  `client_finding_remark` varchar(50) NOT NULL,
  `client_finding_checked` tinyint(1) NOT NULL,
  PRIMARY KEY (`finding_type_id`,`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `condition_type`
--

DROP TABLE IF EXISTS `condition_type`;
CREATE TABLE IF NOT EXISTS `condition_type` (
  `condition_type_id` tinyint(4) NOT NULL,
  `condition_type_name` varchar(50) NOT NULL,
  `condition_type_suffix` varchar(20) NOT NULL,
  PRIMARY KEY (`condition_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `config_name` varchar(50) NOT NULL,
  `config_value` varchar(50) NOT NULL,
  `config_active_date_start` date NOT NULL DEFAULT '1999-01-01',
  `config_active_date_end` date NOT NULL DEFAULT '2999-12-31',
  PRIMARY KEY (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `finding_type`
--

DROP TABLE IF EXISTS `finding_type`;
CREATE TABLE IF NOT EXISTS `finding_type` (
  `finding_type_id` tinyint(4) NOT NULL,
  `finding_type_name` varchar(50) NOT NULL,
  `finding_type_suffix` varchar(20) NOT NULL,
  PRIMARY KEY (`finding_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `health_fund`
--

DROP TABLE IF EXISTS `health_fund`;
CREATE TABLE IF NOT EXISTS `health_fund` (
  `health_fund_id` int(11) NOT NULL AUTO_INCREMENT,
  `health_fund_name` varchar(50) NOT NULL,
  `health_fund_provider_no` varchar(14) NOT NULL,
  PRIMARY KEY (`health_fund_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `massage_record`
--

DROP TABLE IF EXISTS `massage_record`;
CREATE TABLE IF NOT EXISTS `massage_record` (
  `massage_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `therapist_id` smallint(6) NOT NULL,
  `massage_type_id` int(11) NOT NULL,
  `room_no` float NOT NULL,
  `booking_item_id` int(11) NOT NULL,
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
  `massage_record_void_datetime` datetime NOT NULL,
  PRIMARY KEY (`massage_record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13056 ;

-- --------------------------------------------------------

--
-- Table structure for table `massage_type`
--

DROP TABLE IF EXISTS `massage_type`;
CREATE TABLE IF NOT EXISTS `massage_type` (
  `massage_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `massage_type_name` varchar(30) NOT NULL,
  `massage_type_commission` int(11) NOT NULL,
  `massage_type_active` tinyint(1) NOT NULL,
  `massage_type_update_datetime` datetime NOT NULL,
  PRIMARY KEY (`massage_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

DROP TABLE IF EXISTS `membership`;
CREATE TABLE IF NOT EXISTS `membership` (
  `membership_no` varchar(10) NOT NULL,
  `membership_patient_id` tinyint(4) NOT NULL,
  `client_id` int(11) NOT NULL,
  `health_fund_id` int(11) NOT NULL,
  PRIMARY KEY (`membership_no`,`membership_patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(30) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_price_changeable` tinyint(1) NOT NULL,
  `product_stock_amt` int(11) NOT NULL,
  `product_img` varchar(50) NOT NULL,
  `product_active` tinyint(1) NOT NULL,
  `product_update_datetime` datetime NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

DROP TABLE IF EXISTS `provider`;
CREATE TABLE IF NOT EXISTS `provider` (
  `provider_id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_no` varchar(20) NOT NULL,
  `provider_name` varchar(50) NOT NULL,
  `provider_update_datetime` datetime NOT NULL,
  `provider_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`provider_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `reception_record`
--

DROP TABLE IF EXISTS `reception_record`;
CREATE TABLE IF NOT EXISTS `reception_record` (
  `reception_record_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `reception_record_void_datetime` datetime NOT NULL,
  PRIMARY KEY (`reception_record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
CREATE TABLE IF NOT EXISTS `report` (
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
  `client_id` varchar(23) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request_condition`
--

DROP TABLE IF EXISTS `request_condition`;
CREATE TABLE IF NOT EXISTS `request_condition` (
  `request_condition_request` tinyint(1) NOT NULL,
  `request_condition_promotion` tinyint(1) NOT NULL,
  `request_condition_stamp` tinyint(1) NOT NULL,
  `request_condition_amt` decimal(10,2) NOT NULL,
  `request_condition_active_date_start` date NOT NULL DEFAULT '1999-01-01',
  `request_condition_active_date_end` date NOT NULL DEFAULT '2999-12-31',
  PRIMARY KEY (`request_condition_request`,`request_condition_promotion`,`request_condition_stamp`,`request_condition_active_date_start`,`request_condition_active_date_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `room_no` float NOT NULL,
  `room_remark` varchar(20) NOT NULL,
  PRIMARY KEY (`room_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room_double`
--

DROP TABLE IF EXISTS `room_double`;
CREATE TABLE IF NOT EXISTS `room_double` (
  `room_double_no` tinyint(4) NOT NULL,
  `room_no_1` float NOT NULL,
  `room_no_2` float NOT NULL,
  PRIMARY KEY (`room_double_no`,`room_no_1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

DROP TABLE IF EXISTS `room_type`;
CREATE TABLE IF NOT EXISTS `room_type` (
  `room_type_id` tinyint(4) NOT NULL,
  `room_type_name` varchar(20) NOT NULL,
  `room_type_capacity` tinyint(4) NOT NULL,
  PRIMARY KEY (`room_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

DROP TABLE IF EXISTS `sale`;
CREATE TABLE IF NOT EXISTS `sale` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sale_void_datetime` datetime NOT NULL,
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=161 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_item`
--

DROP TABLE IF EXISTS `sale_item`;
CREATE TABLE IF NOT EXISTS `sale_item` (
  `sale_item_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sale_item_void_datetime` datetime NOT NULL,
  PRIMARY KEY (`sale_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=173 ;

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

DROP TABLE IF EXISTS `shift`;
CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_date` date NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `shift_type_id` int(11) NOT NULL,
  `shift_working` tinyint(1) NOT NULL,
  `shift_create_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1006 ;

-- --------------------------------------------------------

--
-- Table structure for table `shift_type`
--

DROP TABLE IF EXISTS `shift_type`;
CREATE TABLE IF NOT EXISTS `shift_type` (
  `shift_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_type_name` varchar(20) NOT NULL,
  `shift_type_rate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`shift_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `therapist`
--

DROP TABLE IF EXISTS `therapist`;
CREATE TABLE IF NOT EXISTS `therapist` (
  `therapist_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `therapist_name` varchar(50) NOT NULL,
  `therapist_guarantee` decimal(10,2) NOT NULL,
  `therapist_username` varchar(10) NOT NULL,
  `therapist_password` varchar(50) NOT NULL,
  `therapist_permission` tinyint(4) NOT NULL COMMENT '9 = admin, 1 = staff',
  `therapist_update_datetime` datetime NOT NULL,
  `therapist_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`therapist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
