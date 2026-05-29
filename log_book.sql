-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 03:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `log_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `equipment_name` varchar(100) DEFAULT NULL,
  `equipment_type` varchar(50) DEFAULT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instrumentissues`
--

CREATE TABLE `instrumentissues` (
  `IssueID` int(11) NOT NULL,
  `InstrumentID` varchar(50) NOT NULL,
  `InstrumentName` varchar(100) NOT NULL,
  `IssueDescription` text NOT NULL,
  `Severity` varchar(20) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `ActionTaken` text NOT NULL,
  `ReportedBy` varchar(100) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `ReportedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `solution_details` varchar(200) NOT NULL,
  `solved_by` varchar(20) NOT NULL,
  `solution_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issue_plant`
--

CREATE TABLE `issue_plant` (
  `ISSUEID` int(11) NOT NULL,
  `SHIFT_ENG_NAME` varchar(100) NOT NULL,
  `SHIFT_ENG_USERNAME` varchar(100) NOT NULL,
  `SHIFT_ENG_EMAIL` varchar(25) NOT NULL,
  `INSTRUMENTNAME` varchar(100) NOT NULL,
  `INSTRUMENTID` varchar(50) DEFAULT NULL,
  `LOCATION` varchar(100) NOT NULL,
  `ISSUEDESCRIPTION` text NOT NULL,
  `ISSUETIME` timestamp NOT NULL DEFAULT current_timestamp(),
  `RATING` varchar(20) NOT NULL,
  `PLANT_S_N` varchar(100) NOT NULL,
  `STATUS` enum('Pending','Resolved') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_plant`
--

INSERT INTO `issue_plant` (`ISSUEID`, `SHIFT_ENG_NAME`, `SHIFT_ENG_USERNAME`, `SHIFT_ENG_EMAIL`, `INSTRUMENTNAME`, `INSTRUMENTID`, `LOCATION`, `ISSUEDESCRIPTION`, `ISSUETIME`, `RATING`, `PLANT_S_N`, `STATUS`) VALUES
(1, 'Ravi Dhabhi', 'Ravi', 'ravi@gmail.com', 'abc', '1', 'xyz', 'Machine udigayu', '2025-04-07 12:45:56', '3', 'Mrunal Doshi', 'Resolved'),
(2, 'Panchasara Divyanshu', 'Divyanshu', 'div@gmail.com', 'pqr', '5', 'Electrical', 'noting', '2025-04-24 07:49:08', '2', 'Parmar Divyank', 'Resolved'),
(3, 'Panchasara Divyanshu', 'Divyanshu', 'div@gmail.com', 'pqo', '7', 'Electrical', 'adjkashfjga', '2025-04-24 07:55:17', '2', 'Parmar Divyank', 'Resolved'),
(4, 'Panchasara Divyanshu', 'Divyanshu', 'div@gmail.com', 'transformer', '10', 'Electrical', 'Transformer making unusual noises', '2026-04-28 10:36:04', '3', 'Mrunal Doshi', 'Resolved'),
(5, 'Panchasara Divyanshu', 'Divyanshu', 'div@gmail.com', 'transformer', '12', 'Electrical sector 2', 'unwanted sound producing ', '2026-04-29 05:03:47', '3', 'Mrunal Doshi', 'Pending'),
(6, 'Panchasara Divyanshu', 'Divyanshu', 'div@gmail.com', 'Rectifier', '784', 'Plant 67', 'Rectifier not stepping properly', '2026-04-29 06:06:08', '3', 'Mrunal Doshi', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `login_table`
--

CREATE TABLE `login_table` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_table`
--

INSERT INTO `login_table` (`username`, `password`, `role`) VALUES
('dev', 'dev10104', 'Shift Engineer'),
('Divyank', 'divyank11', 'Plant Supervisor'),
('Divyanshu', 'div10101', 'Shift Engineer'),
('Mrunal', 'msd1010', 'Plant Supervisor'),
('Neel', '$2y$10$OcbP7m.bjPxSIhIMrA.5o.SZKo9J3e87hFIYdbpdO7kRVpeHf93ZC', 'Admin'),
('Ravi', 'ravi1010', 'Shift Engineer');

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `overtime`
--

INSERT INTO `overtime` (`id`, `date`, `username`, `role`, `hours`, `reason`) VALUES
(1, '2025-03-24', 'dev', 'Shift Engineer', 4, 'no reason');

-- --------------------------------------------------------

--
-- Table structure for table `resolved_issues`
--

CREATE TABLE `resolved_issues` (
  `id` int(11) NOT NULL,
  `issue_id` varchar(50) NOT NULL,
  `instrument_name` varchar(100) NOT NULL,
  `issue_description` text NOT NULL,
  `resolution_details` text NOT NULL,
  `resolved_by` varchar(100) NOT NULL,
  `resolution_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `safety_measures`
--

CREATE TABLE `safety_measures` (
  `id` int(11) NOT NULL,
  `measure_title` varchar(255) NOT NULL,
  `machine_id` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `responsible_person` varchar(100) NOT NULL,
  `priority_level` varchar(20) NOT NULL,
  `implementation_date_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_measures`
--

INSERT INTO `safety_measures` (`id`, `measure_title`, `machine_id`, `location`, `responsible_person`, `priority_level`, `implementation_date_time`) VALUES
(1, 'helmet ', 'meter', 'electrical dept', 'Parmar Divyank', 'medium', '2025-04-24 07:47:43'),
(2, 'helmet', '02', 'electrical dept', 'Mrunal Doshi', 'medium', '2026-04-28 10:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `signup_table`
--

CREATE TABLE `signup_table` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `aadhar_number` varchar(12) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `approved` varchar(10) DEFAULT NULL,
  `registration_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signup_table`
--

INSERT INTO `signup_table` (`id`, `full_name`, `username`, `email`, `password`, `dob`, `mobile_number`, `aadhar_number`, `role`, `approved`, `registration_time`) VALUES
(1, 'Parmar Neel', 'Neel', 'neel@gmail.com', '$2y$10$OcbP7m.bjPxSIhIMrA.5o.SZKo9J3e87hFIYdbpdO7kRVpeHf93ZC', '2007-08-23', '9265714689', '784814457812', 'Admin', 'approved', '2025-03-24'),
(3, 'Parmar dev', 'dev', 'dev@gmail.com', 'dev10104', '2005-08-04', '1048368474', '105247759628', 'Shift Engineer', 'approved', '2025-03-25'),
(4, 'Parmar Divyank', 'Divyank', 'divyank@gmail.com', 'divyank11', '2001-11-25', '8569741602', '856902741602', 'Plant Supervisor', 'approved', '2025-03-25'),
(8, 'Mrunal Doshi', 'Mrunal', 'msd@gmail.com', 'msd1010', '2001-01-11', '4582746311', '458274631145', 'Plant Supervisor', 'approved', '2025-04-07'),
(9, 'Ravi Dhabhi', 'Ravi', 'ravi@gmail.com', 'ravi1010', '2001-11-24', '2244557791', '224455779121', 'Shift Engineer', 'approved', '2025-04-07'),
(10, 'Panchasara Divyanshu', 'Divyanshu', 'div@gmail.com', 'div10101', '2001-05-18', '8582719640', '458271964011', 'Shift Engineer', 'approved', '2025-04-24'),
(11, 'neel', 'Mrunal', 'preet@123.com', 'opopurt78', '2026-03-02', '09265714689', '789415236785', 'Plant Supervisor', 'rejected', '2026-04-28'),
(12, 'neel', 'Mrunal_45', 'preet@123.com', 'erdr56tg', '2026-03-02', '09265714689', '789415236785', 'Plant Supervisor', 'rejected', '2026-04-28');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `ISSUEID` int(100) NOT NULL,
  `INSTRUMENTID` varchar(50) DEFAULT NULL,
  `FILEIMAGE` varchar(1000) DEFAULT NULL,
  `FILEVIDEO` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`ISSUEID`, `INSTRUMENTID`, `FILEIMAGE`, `FILEVIDEO`) VALUES
(1, '1', 'IMG-67f3c9041163c5.34260603.jpg', 'VID-67f3c9041189e2.95444380.mp4'),
(3, '7', 'IMG-6809ee65e1c613.08554101.png', 'VID-6809ee65e20415.57472614.mp4'),
(4, '10', 'IMG-69f08d94bb8e36.16025881.jpeg', NULL),
(5, '12', 'IMG-69f191338a55d5.14335760.jpg', 'VID-69f191338a9223.74756327.mp4'),
(6, '784', 'IMG-69f19fd03ed300.45980442.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_photo`
--

CREATE TABLE `user_photo` (
  `username` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_photo`
--

INSERT INTO `user_photo` (`username`, `photo`) VALUES
('Divyanshu', 'Divyanshu_1777439127.jpeg'),
('Mrunal', 'Mrunal.png'),
('Mrunal_45', 'Mrunal_45_1777387811.jpg'),
('Neel', 'Neel_1744177386.jpeg'),
('Ravi', 'Ravi_1744176000.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instrumentissues`
--
ALTER TABLE `instrumentissues`
  ADD PRIMARY KEY (`InstrumentID`),
  ADD UNIQUE KEY `IssueID` (`IssueID`);

--
-- Indexes for table `issue_plant`
--
ALTER TABLE `issue_plant`
  ADD PRIMARY KEY (`ISSUEID`);

--
-- Indexes for table `login_table`
--
ALTER TABLE `login_table`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resolved_issues`
--
ALTER TABLE `resolved_issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_measures`
--
ALTER TABLE `safety_measures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signup_table`
--
ALTER TABLE `signup_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`ISSUEID`);

--
-- Indexes for table `user_photo`
--
ALTER TABLE `user_photo`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instrumentissues`
--
ALTER TABLE `instrumentissues`
  MODIFY `IssueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `issue_plant`
--
ALTER TABLE `issue_plant`
  MODIFY `ISSUEID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resolved_issues`
--
ALTER TABLE `resolved_issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safety_measures`
--
ALTER TABLE `safety_measures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `signup_table`
--
ALTER TABLE `signup_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `ISSUEID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
