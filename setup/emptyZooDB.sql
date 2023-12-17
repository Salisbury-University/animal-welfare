-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2023 at 06:09 PM
-- Server version: 10.5.21-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zooDB`
--
CREATE DATABASE IF NOT EXISTS `zooDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `zooDB`;

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `section` text NOT NULL,
  `sex` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `species_id` varchar(63) NOT NULL,
  `name` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `AnimalWelfareCheckup`
--

CREATE TABLE `AnimalWelfareCheckup` (
  `id` float NOT NULL,
  `zim` int(11) NOT NULL,
  `dates` date NOT NULL COMMENT 'YYYY/MM/DD',
  `reason` varchar(250) NOT NULL,
  `form_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CheckupResponse`
--

CREATE TABLE `CheckupResponse` (
  `id` int(11) NOT NULL,
  `checkup_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diet`
--

CREATE TABLE `diet` (
  `did` int(11) NOT NULL,
  `zim` int(11) NOT NULL,
  `dates` date NOT NULL COMMENT 'YYYY/MM/DD',
  `reason` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `food` varchar(250) NOT NULL,
  `quantitygiven` int(11) NOT NULL,
  `quantityeaten` int(50) NOT NULL,
  `difference` int(50) NOT NULL,
  `units` varchar(250) NOT NULL COMMENT 'Unit for Quantity'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasSectionQuestions`
--

CREATE TABLE `hasSectionQuestions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE `species` (
  `id` varchar(63) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `administrator` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `welfaresubmission`
--

CREATE TABLE `welfaresubmission` (
  `wid` float NOT NULL,
  `zim` int(11) NOT NULL,
  `dates` date NOT NULL COMMENT 'YYYY/MM/DD',
  `reason` varchar(250) NOT NULL,
  `avg_health` float NOT NULL,
  `avg_nutrition` float NOT NULL,
  `avg_pse` float NOT NULL,
  `avg_behavior` float NOT NULL,
  `avg_mental` float NOT NULL,
  `responses` varchar(250) DEFAULT NULL COMMENT '''FormID'', ''SectionID'', ..., ... /n\r\n''Question#'', Response, ..., ... /n\r\n..., ..., ..., ... /n',
  `fid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `species_id` (`species_id`);

--
-- Indexes for table `AnimalWelfareCheckup`
--
ALTER TABLE `AnimalWelfareCheckup`
  ADD PRIMARY KEY (`id`,`zim`),
  ADD KEY `animalID_checkup` (`zim`);

--
-- Indexes for table `CheckupResponse`
--
ALTER TABLE `CheckupResponse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `checkup_id` (`checkup_id`,`question_id`);

--
-- Indexes for table `diet`
--
ALTER TABLE `diet`
  ADD PRIMARY KEY (`did`,`zim`) USING BTREE,
  ADD KEY `animalID_diet` (`zim`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hasSectionQuestions`
--
ALTER TABLE `hasSectionQuestions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `form_id` (`form_id`,`section_id`,`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question` (`question`) USING HASH;

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `welfaresubmission`
--
ALTER TABLE `welfaresubmission`
  ADD PRIMARY KEY (`wid`),
  ADD KEY `animalID_welfareSubmission` (`zim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AnimalWelfareCheckup`
--
ALTER TABLE `AnimalWelfareCheckup`
  MODIFY `id` float NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diet`
--
ALTER TABLE `diet`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasSectionQuestions`
--
ALTER TABLE `hasSectionQuestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `welfaresubmission`
--
ALTER TABLE `welfaresubmission`
  MODIFY `wid` float NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animals`
--
ALTER TABLE `animals`
  ADD CONSTRAINT `animals_ibfk_1` FOREIGN KEY (`species_id`) REFERENCES `species` (`id`);

--
-- Constraints for table `AnimalWelfareCheckup`
--
ALTER TABLE `AnimalWelfareCheckup`
  ADD CONSTRAINT `animalID_checkup` FOREIGN KEY (`zim`) REFERENCES `animals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diet`
--
ALTER TABLE `diet`
  ADD CONSTRAINT `animalID_diet` FOREIGN KEY (`zim`) REFERENCES `animals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `species`
--
ALTER TABLE `species`
  ADD CONSTRAINT `species_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`);

--
-- Constraints for table `welfaresubmission`
--
ALTER TABLE `welfaresubmission`
  ADD CONSTRAINT `animalID_welfareSubmission` FOREIGN KEY (`zim`) REFERENCES `animals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
