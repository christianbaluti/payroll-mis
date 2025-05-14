-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 09:50 AM
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
-- Database: `hallm3pc_payroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `date_made` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `date_made`, `time_in`, `time_out`, `employee_id`, `remarks`) VALUES
(19, '2025-04-26', '08:53:38', NULL, 215, NULL),
(20, '2025-04-26', '08:53:45', '08:54:05', 213, NULL),
(22, '2025-04-28', '08:23:12', '08:23:18', 215, NULL),
(25, '2025-04-30', '11:51:04', NULL, 215, NULL),
(26, '2025-04-30', '11:51:16', NULL, 217, NULL),
(27, '2025-04-30', '11:51:25', NULL, 218, NULL),
(28, NULL, NULL, NULL, 215, NULL),
(29, '2025-05-01', '02:34:15', '02:50:36', 215, NULL),
(30, '2025-05-02', '11:39:06', '21:41:54', 217, NULL),
(31, '2025-05-02', '11:39:09', '21:41:56', 218, NULL),
(32, '2025-05-02', '11:39:11', '21:41:58', 219, NULL),
(33, '2025-05-02', '12:39:13', '21:42:00', 220, NULL),
(34, '2025-05-02', '12:39:15', '21:42:03', 221, NULL),
(35, '2025-05-07', '00:01:16', '00:01:26', 217, NULL),
(36, '2025-05-07', '00:01:17', '00:01:27', 218, NULL),
(37, '2025-05-07', '00:01:19', '00:01:31', 219, NULL),
(38, '2025-05-07', '00:01:20', '00:01:30', 220, NULL),
(39, '2025-05-07', '00:01:21', '00:01:29', 221, NULL),
(40, '2025-05-08', '01:53:02', '19:35:55', 217, NULL),
(41, '2025-05-08', '01:53:03', '19:35:56', 218, NULL),
(42, '2025-05-08', '01:53:05', '19:35:58', 219, NULL),
(43, '2025-05-08', '01:53:10', '19:36:02', 220, NULL),
(44, '2025-05-08', '01:53:12', '19:36:04', 221, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `location`) VALUES
(9, 'Commercial Branch', 'Blantyre'),
(10, 'Administrative Branch', 'Lilongwe'),
(11, 'Administrative', 'Blantyre');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `head_of_department` varchar(100) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `head_of_department`, `branch_id`) VALUES
(17, 'Human Resource Department', 'Bryan', 10),
(18, 'ICT Department', 'Christian', 9),
(19, 'Supply Chain Department', 'Christian', 10);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `status` varchar(11) NOT NULL DEFAULT 'active',
  `bank_name` varchar(20) DEFAULT NULL,
  `bank_branch` varchar(20) DEFAULT NULL,
  `bank_account_name` varchar(20) DEFAULT NULL,
  `branch_code` varchar(20) DEFAULT NULL,
  `hours_per_week` int(3) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `hourly_rate` int(10) DEFAULT NULL,
  `hours_per_weekday` int(10) DEFAULT NULL,
  `hours_per_weekend` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `position`, `department_id`, `email`, `date_of_joining`, `date_of_birth`, `salary`, `gender`, `status`, `bank_name`, `bank_branch`, `bank_account_name`, `branch_code`, `hours_per_week`, `remarks`, `hourly_rate`, `hours_per_weekday`, `hours_per_weekend`) VALUES
(217, 'Patrick', 'IT officer', 18, 'pmnjoro@hallmark.mw', '2023-04-10', '2025-04-03', 900000.00, 'male', 'active', 'NBS', 'Mzuzu', 'Account name', '1000202020', 40, 'Good', 10000, 8, 4),
(218, 'Christian', 'Data engineer', 17, 'crisnickchristian@gmail.com', '2025-04-11', '2025-05-02', 500000.00, 'male', 'active', 'National Bank', 'Zomba', 'Christian', '202939320398', 40, 'yooooo', 2000, 8, 4),
(219, 'Nsapato', 'Human Resource Manager', 17, 'ictsales@hallmark.mw', '2025-04-17', '2025-04-08', 500000.00, 'female', 'active', 'NBS', 'Blantyre', 'Account name', '100733672', 40, 'Good', 10000, 8, 4),
(220, 'Mnjoro', 'IT officer', 18, 'pmnjoro@hallmark.mw', '2025-04-10', '2025-04-03', 500000.00, 'male', 'active', 'NBS', 'Mzuzu', 'Account name', '1000202020', 40, 'Good', 10000, 8, 4),
(221, 'Baluti', 'Data engineer', 17, 'crisnickchristian@gmail.com', '2025-04-11', '2025-05-02', 500000.00, 'male', 'active', 'National Bank', 'Zomba', 'Christian', '202939320398', 40, 'yooooo', 2000, 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `month` text NOT NULL,
  `year` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `status` text NOT NULL,
  `total_gross_pay` int(11) NOT NULL,
  `total_net_pay` int(11) NOT NULL,
  `total_paye` int(11) NOT NULL,
  `total_pension` int(11) NOT NULL,
  `total_addition` int(11) NOT NULL,
  `total_deduction` int(11) NOT NULL,
  `total_welfare_fund` int(11) NOT NULL,
  `total_weekend_overtime_pay` int(11) NOT NULL,
  `total_weekday_overtime_pay` int(11) NOT NULL,
  `total_overtime_pay` int(11) NOT NULL,
  `datemade` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `month`, `year`, `unique_id`, `status`, `total_gross_pay`, `total_net_pay`, `total_paye`, `total_pension`, `total_addition`, `total_deduction`, `total_welfare_fund`, `total_weekend_overtime_pay`, `total_weekday_overtime_pay`, `total_overtime_pay`, `datemade`) VALUES
(8, '5', 2025, '1747175585166', 'drafted', 2500000, 3636875, 487500, 100625, 50000, 100000, 10000, 1020000, 765000, 1785000, '2025-05-13 23:42:40'),
(9, '5', 2025, '1747177640578', 'drafted', 2900000, 4776000, 1220000, 84000, 2500000, 500000, 10000, 680000, 510000, 1190000, '2025-05-12 23:42:40');

-- --------------------------------------------------------

--
-- Table structure for table `payslip`
--

CREATE TABLE `payslip` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `department` text NOT NULL,
  `Gender` text NOT NULL,
  `branch` text NOT NULL,
  `gross_salary` int(11) NOT NULL,
  `paye` int(11) NOT NULL,
  `pension` int(11) NOT NULL,
  `additions` int(11) NOT NULL,
  `deductions` int(11) NOT NULL,
  `welfare_fund` int(11) NOT NULL,
  `hourly_rate` int(11) NOT NULL,
  `weekend_overtime_hours` int(11) NOT NULL,
  `weekday_overtime_hours` int(11) NOT NULL,
  `weekend_overtime_pay` int(11) NOT NULL,
  `weekday_overtime_pay` int(11) NOT NULL,
  `total_overtime_hours` int(11) NOT NULL,
  `total_overtime_pay` int(11) NOT NULL,
  `bank_name` text NOT NULL,
  `bank_account_name` text NOT NULL,
  `bank_account_number` text NOT NULL,
  `bank_branch` text NOT NULL,
  `month` text NOT NULL,
  `datemade` datetime NOT NULL,
  `status` text NOT NULL,
  `email` text NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payslip`
--

INSERT INTO `payslip` (`id`, `name`, `department`, `Gender`, `branch`, `gross_salary`, `paye`, `pension`, `additions`, `deductions`, `welfare_fund`, `hourly_rate`, `weekend_overtime_hours`, `weekday_overtime_hours`, `weekend_overtime_pay`, `weekday_overtime_pay`, `total_overtime_hours`, `total_overtime_pay`, `bank_name`, `bank_account_name`, `bank_account_number`, `bank_branch`, `month`, `datemade`, `status`, `email`, `year`) VALUES
(11, 'Patrick', 'ICT Department', 'male', '', 500000, 97500, 20125, 10000, 20000, 2000, 10000, 15, 15, 300000, 225000, 30, 525000, 'NBS', 'Account name', '', 'Mzuzu', '5', '2025-05-14 00:33:05', 'drafted', 'pmnjoro@hallmark.mw', 2025),
(12, 'Christian', 'Human Resource Department', 'male', '', 500000, 97500, 20125, 10000, 20000, 2000, 2000, 15, 15, 60000, 45000, 30, 105000, 'National Bank', 'Christian', '', 'Zomba', '5', '2025-05-14 00:33:05', 'drafted', 'crisnickchristian@gmail.com', 2025),
(13, 'Nsapato', 'Human Resource Department', 'female', '', 500000, 97500, 20125, 10000, 20000, 2000, 10000, 15, 15, 300000, 225000, 30, 525000, 'NBS', 'Account name', '', 'Blantyre', '5', '2025-05-14 00:33:05', 'drafted', 'ictsales@hallmark.mw', 2025),
(14, 'Mnjoro', 'ICT Department', 'male', '', 500000, 97500, 20125, 10000, 20000, 2000, 10000, 15, 15, 300000, 225000, 30, 525000, 'NBS', 'Account name', '', 'Mzuzu', '5', '2025-05-14 00:33:05', 'drafted', 'pmnjoro@hallmark.mw', 2025),
(15, 'Baluti', 'Human Resource Department', 'male', '', 500000, 97500, 20125, 10000, 20000, 2000, 2000, 15, 15, 60000, 45000, 30, 105000, 'National Bank', 'Christian', '', 'Zomba', '5', '2025-05-14 00:33:05', 'drafted', 'crisnickchristian@gmail.com', 2025),
(16, 'Patrick', 'ICT Department', 'male', '', 900000, 340000, 28000, 500000, 100000, 2000, 10000, 10, 10, 200000, 150000, 20, 350000, 'NBS', 'Account name', '', 'Mzuzu', '5', '2025-05-14 01:07:20', 'drafted', 'pmnjoro@hallmark.mw', 2025),
(17, 'Christian', 'Human Resource Department', 'male', '', 500000, 220000, 14000, 500000, 100000, 2000, 2000, 10, 10, 40000, 30000, 20, 70000, 'National Bank', 'Christian', '', 'Zomba', '5', '2025-05-14 01:07:20', 'drafted', 'crisnickchristian@gmail.com', 2025),
(18, 'Nsapato', 'Human Resource Department', 'female', '', 500000, 220000, 14000, 500000, 100000, 2000, 10000, 10, 10, 200000, 150000, 20, 350000, 'NBS', 'Account name', '', 'Blantyre', '5', '2025-05-14 01:07:20', 'drafted', 'ictsales@hallmark.mw', 2025),
(19, 'Mnjoro', 'ICT Department', 'male', '', 500000, 220000, 14000, 500000, 100000, 2000, 10000, 10, 10, 200000, 150000, 20, 350000, 'NBS', 'Account name', '', 'Mzuzu', '5', '2025-05-14 01:07:20', 'drafted', 'pmnjoro@hallmark.mw', 2025),
(20, 'Baluti', 'Human Resource Department', 'male', '', 500000, 220000, 14000, 500000, 100000, 2000, 2000, 10, 10, 40000, 30000, 20, 70000, 'National Bank', 'Christian', '', 'Zomba', '5', '2025-05-14 01:07:20', 'drafted', 'crisnickchristian@gmail.com', 2025);

-- --------------------------------------------------------

--
-- Table structure for table `payslip_payroll`
--

CREATE TABLE `payslip_payroll` (
  `id` int(11) NOT NULL,
  `datemade` datetime NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `payslip_id` int(11) NOT NULL,
  `month` text NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payslip_payroll`
--

INSERT INTO `payslip_payroll` (`id`, `datemade`, `payroll_id`, `payslip_id`, `month`, `year`) VALUES
(26, '2025-05-14 00:33:05', 8, 11, '5', 2025),
(27, '2025-05-14 00:33:05', 8, 12, '5', 2025),
(28, '2025-05-14 00:33:05', 8, 13, '5', 2025),
(29, '2025-05-14 00:33:05', 8, 14, '5', 2025),
(30, '2025-05-14 00:33:05', 8, 15, '5', 2025),
(31, '2025-05-14 01:07:20', 9, 16, '5', 2025),
(32, '2025-05-14 01:07:20', 9, 17, '5', 2025),
(33, '2025-05-14 01:07:20', 9, 18, '5', 2025),
(34, '2025-05-14 01:07:20', 9, 19, '5', 2025),
(35, '2025-05-14 01:07:20', 9, 20, '5', 2025);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','hr','manager') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `role`, `password`, `token`) VALUES
(17, 'ictsales@hallmark.mw', '', 'admin', '$2y$10$io4jtRki7CoL3uB0rsy1bOYIAb1ZXnzXW.BjLXZiq71EDH3bPWJni', NULL),
(23, 'hallmarkmalawi@gmail.com', '', 'hr', '$2y$10$Cn5sLMIYObMcWGJoOuzuR.AesM8Gp/mqVw9yOe0xMniz/ltlwobSi', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip`
--
ALTER TABLE `payslip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_payroll`
--
ALTER TABLE `payslip_payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payslip`
--
ALTER TABLE `payslip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payslip_payroll`
--
ALTER TABLE `payslip_payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
