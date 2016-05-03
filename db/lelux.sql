-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2016 at 03:51 PM
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
-- Table structure for table `client`
--

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

CREATE TABLE `condition_type` (
  `condition_type_id` tinyint(4) NOT NULL,
  `condition_type_name` varchar(50) NOT NULL,
  `condition_type_suffix` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `condition_type`
--

INSERT INTO `condition_type` (`condition_type_id`, `condition_type_name`, `condition_type_suffix`) VALUES
(1, 'Stroke', 'Stroke'),
(2, 'Cancer', 'Cancer'),
(3, 'Isomnia', 'Isomnia'),
(4, 'Headache', 'Headache'),
(5, 'Heart Conditions', 'HeartCon'),
(6, 'Pain/Stiffness', 'Pain'),
(7, 'High/Low Blood Pressure', 'BloodPressure'),
(8, 'Allergies/Asthma', 'Allergy'),
(9, 'Broken/Dislocated Bones', 'BrokenBone'),
(10, 'Contagious/Infactious Diseases', 'Disease'),
(11, 'Pregnancy/Breastfeeding', 'Pregnancy'),
(12, 'Sore Back', 'SoreBack');

-- --------------------------------------------------------

--
-- Table structure for table `finding_type`
--

CREATE TABLE `finding_type` (
  `finding_type_id` tinyint(4) NOT NULL,
  `finding_type_name` varchar(50) NOT NULL,
  `finding_type_suffix` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `finding_type`
--

INSERT INTO `finding_type` (`finding_type_id`, `finding_type_name`, `finding_type_suffix`) VALUES
(1, 'True Local', 'TrueLocal'),
(2, 'Google', 'Google'),
(3, 'Passing By', 'Passing'),
(4, 'Word of Mouth', 'Word'),
(5, 'Flyer', 'Flyer'),
(6, 'Facebook', 'Facebook'),
(7, 'Gift Voucher', 'GiftVoucher'),
(8, 'Referred By', 'Referred');

-- --------------------------------------------------------

--
-- Table structure for table `health_fund`
--

CREATE TABLE `health_fund` (
  `health_fund_id` int(11) NOT NULL,
  `health_fund_name` varchar(50) NOT NULL,
  `health_fund_provider_no` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `health_fund`
--

INSERT INTO `health_fund` (`health_fund_id`, `health_fund_name`, `health_fund_provider_no`) VALUES
(0, '----- Non Health Fund -----', ''),
(1, 'ACA Health', 'AW10487R'),
(2, 'AHM Health Insurance', '21146594'),
(3, 'Australian Unity Health Ltd', '21135790'),
(4, 'Budget Direct', ''),
(5, 'Bupa Australia', 'C065957'),
(6, 'CBHS Health Fund Limited', 'AMT1-10'),
(7, 'CUA Health Limited', 'AW10487R'),
(8, 'Defence Health Limited', 'AW10487R'),
(9, 'Frank Health insurance', 'AW10487R'),
(10, 'GMF Health', 'AW10487R'),
(11, 'GMHBA Limited', 'AW10487R'),
(12, 'Grand United Health', 'H2314359'),
(13, 'HBF Health Fund', '1283571W'),
(14, 'Health Care Insurance Ltd', 'AW10487R'),
(15, 'Health Insurance Fund of Australia Ltd (HIF)', 'AW10487R'),
(16, 'Health Partners', 'AW10487R'),
(17, 'Health.com.au', 'AW10487R'),
(18, 'Medibank Private Ltd', '1283571W'),
(19, 'Onemedifund', 'AW10487R'),
(20, 'Navy Health', 'AW10487R'),
(21, 'NIB Health Funds Ltd', 'AMT1-10487'),
(22, 'Peoplecare Health Insurance', 'AW10487R'),
(23, 'Phoenix Health Fund Ltd', 'AW10487R'),
(24, 'Queensland Country Health Fund Ltd', 'AW10487R'),
(25, 'Railway and Transport Health Fund Ltd', 'AW10487R'),
(26, 'Reserve Bank Health Society', ''),
(27, 'St Lukes', 'AW10487R'),
(28, 'The Doctors'' Health Fund', 'AM10487'),
(29, 'Teachers Health Fund', 'AW10487R'),
(30, 'Transport Health Pty Ltd', 'AW10487R'),
(31, 'TUH', ''),
(32, 'Uni Health', ''),
(33, 'Westfund Ltd', ''),
(34, 'HCF', 'AMT1-10487'),
(35, 'Mildura District Hospital Fund Ltd', 'AW10487R'),
(36, 'La Trobe Health Services', 'AW10487R'),
(37, 'Police Health', 'AW10487R');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `membership_no` varchar(10) NOT NULL,
  `membership_patient_id` tinyint(4) NOT NULL,
  `client_id` int(11) NOT NULL,
  `health_fund_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` varchar(23) NOT NULL,
  `report_date` date NOT NULL,
  `report_detail` text NOT NULL,
  `report_recommendation` text NOT NULL,
  `report_hour` decimal(10,2) NOT NULL,
  `therapist_id` int(11) NOT NULL,
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
-- Table structure for table `therapist`
--

CREATE TABLE `therapist` (
  `therapist_id` smallint(6) NOT NULL,
  `therapist_name` varchar(50) NOT NULL,
  `therapist_username` varchar(10) NOT NULL,
  `therapist_password` varchar(50) NOT NULL,
  `therapist_permission` tinyint(4) NOT NULL COMMENT '9 = admin, 1 = staff',
  `therapist_update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `therapist`
--

INSERT INTO `therapist` (`therapist_id`, `therapist_name`, `therapist_username`, `therapist_password`, `therapist_permission`, `therapist_update_datetime`) VALUES
(0, '--- Unknown ---', '', '', 0, '0000-00-00 00:00:00'),
(1, 'Natalie', 'natalie', 'natalie', 9, '2016-04-14 16:33:53'),
(2, 'Sandy', 'sandy', 'sandy', 1, '0000-00-00 00:00:00'),
(3, 'Nicha', 'nicha', 'nicha', 1, '0000-00-00 00:00:00'),
(4, 'Kate', 'kate', 'kate', 1, '0000-00-00 00:00:00'),
(5, 'Patty', 'patty', 'patty', 1, '0000-00-00 00:00:00'),
(6, 'Eve', 'eve', 'eve', 1, '0000-00-00 00:00:00'),
(7, 'Pranee', 'pranee', 'pranee', 1, '0000-00-00 00:00:00'),
(8, 'Sue', 'sue', 'sue', 1, '0000-00-00 00:00:00'),
(9, 'Ice', 'ice', 'ice', 1, '0000-00-00 00:00:00'),
(10, 'Barbie', 'barbie', 'barbie', 1, '0000-00-00 00:00:00'),
(11, 'Noo', 'noona', 'noo1', 1, '0000-00-00 00:00:00'),
(12, 'Ri', 'ri', 'ri', 1, '0000-00-00 00:00:00'),
(13, 'Donna', 'donna', 'donna', 1, '0000-00-00 00:00:00')

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
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_no`,`membership_patient_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);

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
  MODIFY `health_fund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `therapist`
--
ALTER TABLE `therapist`
  MODIFY `therapist_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
