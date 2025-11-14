-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 07:37 AM
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
-- Database: `company_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'fooledgill', 'agsragil2', '2025-11-13 16:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `cloud_solutions`
--

CREATE TABLE `cloud_solutions` (
  `id` int(11) NOT NULL,
  `solution_name` varchar(100) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `provider` enum('AWS','Google Cloud','Azure','Other') NOT NULL,
  `service_type` enum('Storage','Computing','Database','Networking','Hybrid') NOT NULL,
  `implementation_date` date NOT NULL,
  `status` enum('Planning','In Progress','Completed','Maintenance') DEFAULT 'Planning',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cloud_solutions`
--

INSERT INTO `cloud_solutions` (`id`, `solution_name`, `client_name`, `description`, `provider`, `service_type`, `implementation_date`, `status`, `created_at`) VALUES
(1, 'Cloud Migration', 'PT. Teknologi Maju', 'Migrasi infrastruktur on-premise ke cloud', 'AWS', 'Computing', '2023-01-10', 'Completed', '2025-11-13 16:26:38'),
(2, 'Data Backup System', 'RS. Sehat Medika', 'Sistem backup data pasien yang aman', 'Google Cloud', 'Storage', '2023-03-20', 'In Progress', '2025-11-13 16:26:38'),
(3, 'Hybrid Infrastructure', 'Bank Sentral', 'Infrastruktur hybrid untuk sistem perbankan', 'Azure', 'Hybrid', '2023-05-15', 'In Progress', '2025-11-13 16:26:38'),
(6, 'Hybrid Infrastructure', 'Bank Sentral', 'Infrastruktur hybrid untuk sistem perbankan', 'Azure', 'Hybrid', '2023-05-15', 'In Progress', '2025-11-13 16:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 'John Doe', 'john@example.com', 'Saya tertarik dengan layanan web development Anda.', 'pending', '2025-11-13 16:05:51'),
(2, 'Jane Smith', 'jane@example.com', 'Mohon informasi lebih lanjut tentang mobile apps.', 'pending', '2025-11-13 16:05:51'),
(3, 'Ahmad Rizki', 'ahmad@example.com', 'Bagaimana cara berlangganan cloud solutions?', 'pending', '2025-11-13 16:05:51'),
(7, 'Ragil Agustino Ananda Suryanto', 'agsragil88@gmail.com', 'Saya sangat menhargai pelayanan terbaik anda', 'pending', '2025-11-14 06:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `mobile_apps`
--

CREATE TABLE `mobile_apps` (
  `id` int(11) NOT NULL,
  `app_name` varchar(100) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `platform` enum('iOS','Android','Both') NOT NULL,
  `app_store_url` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `release_date` date DEFAULT NULL,
  `status` enum('Planning','In Progress','Testing','Released','On Hold') DEFAULT 'Planning',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobile_apps`
--

INSERT INTO `mobile_apps` (`id`, `app_name`, `client_name`, `description`, `platform`, `app_store_url`, `start_date`, `release_date`, `status`, `created_at`) VALUES
(1, 'Food Delivery App', 'PT. Kuliner Nusantara', 'Aplikasi pesan antar makanan dengan fitur tracking', 'Both', 'https://play.google.com/store/apps/fooddelivery', '2023-02-01', '2023-05-01', 'Released', '2025-11-13 16:26:38'),
(2, 'Fitness Tracker', 'Sehat Bersama', 'Aplikasi tracking kebugaran dengan sosial media', 'iOS', 'https://apps.apple.com/fitness-tracker', '2023-03-15', NULL, 'Testing', '2025-11-13 16:26:38'),
(3, 'E-Learning Platform', 'Pendidikan Indonesia', 'Platform pembelajaran online untuk siswa', 'Both', NULL, '2023-07-01', NULL, 'In Progress', '2025-11-13 16:26:38'),
(4, 'Food Delivery App', 'PT. Kuliner Nusantara', 'Aplikasi pesan antar makanan dengan fitur tracking', 'Both', 'https://play.google.com/store/apps/fooddelivery', '2023-02-01', '2023-05-01', 'Released', '2025-11-13 16:55:51'),
(5, 'Fitness Tracker', 'Sehat Bersama', 'Aplikasi tracking kebugaran dengan sosial media', 'iOS', 'https://apps.apple.com/fitness-tracker', '2023-03-15', NULL, 'Testing', '2025-11-13 16:55:51'),
(6, 'E-Learning Platform', 'Pendidikan Indonesia', 'Platform pembelajaran online untuk siswa', 'Both', NULL, '2023-07-01', NULL, 'In Progress', '2025-11-13 16:55:51'),
(10, 'Fitness Tracker', 'Health Corp', 'Aplikasi tracking aktivitas olahraga', '', NULL, '0000-00-00', NULL, 'In Progress', '2025-11-14 06:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `security_services`
--

CREATE TABLE `security_services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `service_type` enum('Audit','Penetration Testing','Security Assessment','Implementation','Training') NOT NULL,
  `security_level` enum('Basic','Intermediate','Advanced','Enterprise') NOT NULL,
  `assessment_date` date NOT NULL,
  `next_assessment_date` date DEFAULT NULL,
  `status` enum('Scheduled','In Progress','Completed','Follow-up Required') DEFAULT 'Scheduled',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_services`
--

INSERT INTO `security_services` (`id`, `service_name`, `client_name`, `description`, `service_type`, `security_level`, `assessment_date`, `next_assessment_date`, `status`, `created_at`) VALUES
(1, 'Security Audit', 'PT. Finansial Indonesia', 'Audit keamanan sistem perbankan', 'Audit', 'Enterprise', '2023-02-15', '2023-08-15', 'Completed', '2025-11-13 16:26:38'),
(2, 'Penetration Testing', 'E-Commerce Corp', 'Uji penetrasi untuk website e-commerce', 'Penetration Testing', 'Advanced', '2023-04-10', NULL, 'In Progress', '2025-11-13 16:26:38'),
(3, 'Security Training', 'PT. Asuransi Nusantara', 'Pelatihan keamanan siber untuk karyawan', 'Training', 'Intermediate', '2023-05-20', '2023-11-20', 'Completed', '2025-11-13 16:26:38'),
(4, 'Security Audit', 'PT. Finansial Indonesia', 'Audit keamanan sistem perbankan', 'Audit', 'Enterprise', '2023-02-15', '2023-08-15', 'Completed', '2025-11-13 16:55:51'),
(5, 'Penetration Testing', 'E-Commerce Corp', 'Uji penetrasi untuk website e-commerce', 'Penetration Testing', 'Advanced', '2023-04-10', NULL, 'In Progress', '2025-11-13 16:55:51'),
(6, 'Security Training', 'PT. Asuransi Nusantara', 'Pelatihan keamanan siber untuk karyawan', 'Training', 'Intermediate', '2023-05-20', '2023-11-20', 'Completed', '2025-11-13 16:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `web_projects`
--

CREATE TABLE `web_projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `technology` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Planning','In Progress','Completed','On Hold') DEFAULT 'Planning',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `web_projects`
--

INSERT INTO `web_projects` (`id`, `project_name`, `client_name`, `description`, `technology`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'E-Commerce Platform', 'PT. Retail Indonesia', 'Platform e-commerce dengan fitur pembayaran terintegrasi', 'PHP, Laravel, MySQL', '2023-01-15', '2023-04-20', 'Completed', '2025-11-13 16:26:38'),
(2, 'Corporate Website', 'CV. Mitra Sejahtera', 'Website perusahaan dengan CMS custom', 'React, Node.js, MongoDB', '2023-03-10', '2023-05-15', 'Completed', '2025-11-13 16:26:38'),
(3, 'Banking Portal', 'Bank Digital Nusantara', 'Portal perbankan online dengan keamanan tinggi', 'Java Spring, Oracle', '2023-06-01', NULL, 'In Progress', '2025-11-13 16:26:38'),
(7, 'Banking Portal', 'Bank Digital Nusantara', 'Portal perbankan online dengan keamanan tinggi', 'Java Spring, Oracle', '2023-06-01', NULL, 'In Progress', '2025-11-13 16:55:51'),
(10, 'Membuat WEB game ', 'Ragil', 'wdasdwawdswda', 'htmk, css, JavaScript', '2025-11-15', '2025-12-19', 'Planning', '2025-11-14 06:38:59'),
(13, 'Dashboard Analytics', 'Startup Tech', 'Dashboard untuk monitoring data real-time', '', '0000-00-00', NULL, 'Planning', '2025-11-14 06:43:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cloud_solutions`
--
ALTER TABLE `cloud_solutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_provider` (`provider`),
  ADD KEY `idx_service_type` (`service_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `mobile_apps`
--
ALTER TABLE `mobile_apps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_platform` (`platform`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `security_services`
--
ALTER TABLE `security_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_service_type` (`service_type`),
  ADD KEY `idx_security_level` (`security_level`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `web_projects`
--
ALTER TABLE `web_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cloud_solutions`
--
ALTER TABLE `cloud_solutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mobile_apps`
--
ALTER TABLE `mobile_apps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `security_services`
--
ALTER TABLE `security_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `web_projects`
--
ALTER TABLE `web_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
