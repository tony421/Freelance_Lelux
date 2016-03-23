-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2016 at 03:26 PM
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
  `client_create_user` varchar(50) NOT NULL,
  `client_update_datetime` datetime NOT NULL,
  `client_update_user` varchar(50) NOT NULL,
  `client_void_datetime` datetime NOT NULL,
  `client_void_user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_id`, `health_fund_id`, `client_membership_no`, `client_patient_id`, `client_first_name`, `client_last_name`, `client_gender`, `client_address`, `client_postcode`, `client_email`, `client_contact_no`, `client_birthday`, `client_occupation`, `client_sports`, `client_other_conditions`, `client_emergency_contact_name`, `client_emergency_contact_no`, `client_create_datetime`, `client_create_user`, `client_update_datetime`, `client_update_user`, `client_void_datetime`, `client_void_user`) VALUES
('56f17a5231e5c', 32, '9001', 0, 'Mark', 'Macro', b'0', '19 Moo st.', '3078', 'mark@gmail.com', '0984128921', '1970-01-01', 'Lawer', 'Soccer', '', 'Miky', '0891112222', '2016-03-23 04:01:06', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', ''),
('56f17c3331c74', 9, '70091', 1, 'Frank', 'Scoot', b'0', '203 King st.', '3000', 'frankscoot@hotmail.com', '0454449999', '1989-05-20', 'Student', 'Footy', '', '', '', '2016-03-23 04:09:07', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', ''),
('56f17d4063ec9', 18, '345678', 0, 'Maggie', 'Gyllenhaal', b'1', '54 Church street', '3045', 'maggy@gmail.com', '0897776666', '1984-09-04', 'Accoutant', 'Yoga', '', 'Mom', '0459990000', '2016-03-23 04:13:36', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', ''),
('56f1d4493e2f3', 18, 'HF90001', 0, 'Thawatchai', 'Jidsodsai', b'1', '509/565 Flinders street, Melbourne, VIC', '3000', 'thawat@gmail.com', '0982229999', '1980-02-02', 'S.PG', 'Soccer, swimming', 'nothing1', 'Mala', '0936669999', '2016-03-23 10:24:57', 'default', '2016-03-23 20:21:09', 'default', '0000-00-00 00:00:00', ''),
('56f1f456ec772', 1, '', 0, '', '', b'0', '', '', '', '', '0000-00-00', '', '', '', '', '', '2016-03-23 12:41:42', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', ''),
('56f1fae137be4', 1, '', 1, '', '', b'0', '', '', '', '', '0000-00-00', '', '', '', '', '', '2016-03-23 13:09:37', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', ''),
('56f2164068469', 1, '9999999999', 1, '', '', b'0', '', '', '', '', '0000-00-00', '', '', '', '', '', '2016-03-23 15:06:24', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '');

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

--
-- Dumping data for table `client_condition`
--

INSERT INTO `client_condition` (`client_id`, `condition_type_id`, `client_condition_remark`, `client_condition_checked`) VALUES
('56f17a5231e5c', 1, '', 0),
('56f17c3331c74', 1, '', 0),
('56f17d4063ec9', 1, '', 0),
('56f1d4493e2f3', 1, 'xxx', 1),
('56f1f456ec772', 1, '', 0),
('56f1fae137be4', 1, '', 0),
('56f2164068469', 1, '', 0),
('56f17a5231e5c', 2, '', 0),
('56f17c3331c74', 2, '', 0),
('56f17d4063ec9', 2, '', 0),
('56f1d4493e2f3', 2, '', 0),
('56f1f456ec772', 2, '', 0),
('56f1fae137be4', 2, '', 0),
('56f2164068469', 2, '', 0),
('56f17a5231e5c', 3, '', 0),
('56f17c3331c74', 3, '', 0),
('56f17d4063ec9', 3, '', 0),
('56f1d4493e2f3', 3, 'f', 1),
('56f1f456ec772', 3, '', 0),
('56f1fae137be4', 3, '', 0),
('56f2164068469', 3, '', 0),
('56f17a5231e5c', 4, '', 0),
('56f17c3331c74', 4, '', 0),
('56f17d4063ec9', 4, '', 0),
('56f1d4493e2f3', 4, '', 1),
('56f1f456ec772', 4, '', 0),
('56f1fae137be4', 4, '', 0),
('56f2164068469', 4, '', 0),
('56f17a5231e5c', 5, '', 0),
('56f17c3331c74', 5, '', 0),
('56f17d4063ec9', 5, '', 0),
('56f1d4493e2f3', 5, '', 0),
('56f1f456ec772', 5, '', 0),
('56f1fae137be4', 5, '', 0),
('56f2164068469', 5, '', 0),
('56f17a5231e5c', 6, '', 1),
('56f17c3331c74', 6, '', 1),
('56f17d4063ec9', 6, '', 0),
('56f1d4493e2f3', 6, '', 0),
('56f1f456ec772', 6, '', 0),
('56f1fae137be4', 6, '', 0),
('56f2164068469', 6, '', 0),
('56f17a5231e5c', 7, '', 0),
('56f17c3331c74', 7, '', 0),
('56f17d4063ec9', 7, '', 0),
('56f1d4493e2f3', 7, '', 0),
('56f1f456ec772', 7, '', 0),
('56f1fae137be4', 7, '', 0),
('56f2164068469', 7, '', 0),
('56f17a5231e5c', 8, '', 0),
('56f17c3331c74', 8, '', 0),
('56f17d4063ec9', 8, '', 0),
('56f1d4493e2f3', 8, '', 0),
('56f1f456ec772', 8, '', 0),
('56f1fae137be4', 8, '', 0),
('56f2164068469', 8, '', 0),
('56f17a5231e5c', 9, '', 0),
('56f17c3331c74', 9, '', 0),
('56f17d4063ec9', 9, '', 0),
('56f1d4493e2f3', 9, '', 0),
('56f1f456ec772', 9, '', 0),
('56f1fae137be4', 9, '', 0),
('56f2164068469', 9, '', 0),
('56f17a5231e5c', 10, '', 0),
('56f17c3331c74', 10, '', 0),
('56f17d4063ec9', 10, '', 0),
('56f1d4493e2f3', 10, '', 0),
('56f1f456ec772', 10, '', 0),
('56f1fae137be4', 10, '', 0),
('56f2164068469', 10, '', 0),
('56f17a5231e5c', 11, '', 0),
('56f17c3331c74', 11, '', 0),
('56f17d4063ec9', 11, '', 0),
('56f1d4493e2f3', 11, '', 0),
('56f1f456ec772', 11, '', 0),
('56f1fae137be4', 11, '', 0),
('56f2164068469', 11, '', 0),
('56f17a5231e5c', 12, 'lower back', 1),
('56f17c3331c74', 12, '', 0),
('56f17d4063ec9', 12, '', 0),
('56f1d4493e2f3', 12, '', 0),
('56f1f456ec772', 12, '', 0),
('56f1fae137be4', 12, '', 0),
('56f2164068469', 12, '', 0);

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

--
-- Dumping data for table `client_finding`
--

INSERT INTO `client_finding` (`client_id`, `finding_type_id`, `client_finding_remark`, `client_finding_checked`) VALUES
('56f17a5231e5c', 1, '', 1),
('56f17c3331c74', 1, '', 0),
('56f17d4063ec9', 1, '', 0),
('56f1d4493e2f3', 1, '', 1),
('56f1f456ec772', 1, '', 0),
('56f1fae137be4', 1, '', 0),
('56f2164068469', 1, '', 0),
('56f17a5231e5c', 2, '', 0),
('56f17c3331c74', 2, '', 0),
('56f17d4063ec9', 2, '', 0),
('56f1d4493e2f3', 2, '', 0),
('56f1f456ec772', 2, '', 0),
('56f1fae137be4', 2, '', 0),
('56f2164068469', 2, '', 0),
('56f17a5231e5c', 3, '', 0),
('56f17c3331c74', 3, '', 0),
('56f17d4063ec9', 3, '', 0),
('56f1d4493e2f3', 3, '', 0),
('56f1f456ec772', 3, '', 0),
('56f1fae137be4', 3, '', 0),
('56f2164068469', 3, '', 0),
('56f17a5231e5c', 4, '', 0),
('56f17c3331c74', 4, '', 0),
('56f17d4063ec9', 4, '', 1),
('56f1d4493e2f3', 4, '', 0),
('56f1f456ec772', 4, '', 0),
('56f1fae137be4', 4, '', 0),
('56f2164068469', 4, '', 0),
('56f17a5231e5c', 5, '', 0),
('56f17c3331c74', 5, '', 0),
('56f17d4063ec9', 5, '', 0),
('56f1d4493e2f3', 5, '', 0),
('56f1f456ec772', 5, '', 0),
('56f1fae137be4', 5, '', 0),
('56f2164068469', 5, '', 0),
('56f17a5231e5c', 6, '', 0),
('56f17c3331c74', 6, '', 0),
('56f17d4063ec9', 6, '', 0),
('56f1d4493e2f3', 6, '', 0),
('56f1f456ec772', 6, '', 0),
('56f1fae137be4', 6, '', 0),
('56f2164068469', 6, '', 0),
('56f17a5231e5c', 7, '', 0),
('56f17c3331c74', 7, '', 1),
('56f17d4063ec9', 7, '', 0),
('56f1d4493e2f3', 7, '', 0),
('56f1f456ec772', 7, '', 0),
('56f1fae137be4', 7, '', 0),
('56f2164068469', 7, '', 0),
('56f17a5231e5c', 8, '', 0),
('56f17c3331c74', 8, '', 0),
('56f17d4063ec9', 8, 'Mom', 1),
('56f1d4493e2f3', 8, 'x', 1),
('56f1f456ec772', 8, '', 0),
('56f1fae137be4', 8, '', 0),
('56f2164068469', 8, '', 0);

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
  `health_fund_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `health_fund`
--

INSERT INTO `health_fund` (`health_fund_id`, `health_fund_name`) VALUES
(1, 'ACA Health'),
(2, 'AHM Health Insurance'),
(3, 'Australian Unity Health Ltd'),
(4, 'Budget Direct'),
(5, 'Bupa Australia'),
(6, 'CBHS Health Fund Limited'),
(7, 'CUA Health Limited'),
(8, 'Defence Health Limited'),
(9, 'Frank Health insurance'),
(10, 'GMF Health'),
(11, 'GMHBA Limited'),
(12, 'Grand United Health'),
(13, 'HBF Health Fund'),
(14, 'Health Care Insurance Ltd'),
(15, 'Health Insurance Fund of Australia Ltd'),
(16, 'Health Partners'),
(17, 'Health.com.au'),
(18, 'Medibank Private Ltd'),
(19, 'onemedifund'),
(20, 'Navy Health'),
(21, 'NIB Health Funds Ltd'),
(22, 'Peoplecare Health Insurance'),
(23, 'Phoenix Health Fund Ltd'),
(24, 'Queensland Country Health Fund Ltd'),
(25, 'Railway and Transport Health Fund Ltd'),
(26, 'Reserve Bank Health Society'),
(27, 'St Lukes'),
(28, 'The Doctors’ Health Fund'),
(29, 'Teachers Health Fund'),
(30, 'Transport Health Pty Ltd'),
(31, 'TUH'),
(32, 'Uni Health'),
(33, 'Westfund Ltd');

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
  `report_create_user` varchar(50) NOT NULL,
  `report_update_datetime` datetime NOT NULL,
  `report_update_user` varchar(50) NOT NULL,
  `report_void_datetime` datetime NOT NULL,
  `report_void_user` varchar(50) NOT NULL,
  `client_id` varchar(23) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `report_date`, `report_detail`, `report_recommendation`, `report_hour`, `therapist_id`, `membership_no`, `pateint_id`, `report_create_datetime`, `report_create_user`, `report_update_datetime`, `report_update_user`, `report_void_datetime`, `report_void_user`, `client_id`) VALUES
('56f2a73b8f8cb', '2016-03-24', 'a', 'b', '1.75', 2, 0, 0, '2016-03-24 01:24:59', 'default', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '56f1d4493e2f3');

-- --------------------------------------------------------

--
-- Table structure for table `therapist`
--

CREATE TABLE `therapist` (
  `therapist_id` smallint(6) NOT NULL,
  `therapist_name` varchar(50) NOT NULL,
  `therapist_username` varchar(10) NOT NULL,
  `therapist_password` varchar(50) NOT NULL
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
  MODIFY `health_fund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `therapist`
--
ALTER TABLE `therapist`
  MODIFY `therapist_id` smallint(6) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
