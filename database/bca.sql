-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 17, 2020 at 02:09 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bca`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asheet_id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `status` varchar(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `f7` (`asheet_id`),
  KEY `f8` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `asheet_id`, `student_id`, `status`) VALUES
(72, 20, 21, 'Present'),
(73, 20, 22, 'Present'),
(74, 20, 24, 'Present'),
(75, 21, 21, 'Present'),
(76, 21, 22, 'Present'),
(77, 21, 24, 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_sheet`
--

DROP TABLE IF EXISTS `attendance_sheet`;
CREATE TABLE IF NOT EXISTS `attendance_sheet` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `teachers_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `f4` (`teachers_id`),
  KEY `f5` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance_sheet`
--

INSERT INTO `attendance_sheet` (`id`, `teachers_id`, `subject_id`, `date`) VALUES
(20, 1, 16, '2020-03-15'),
(21, 1, 17, '2020-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `rollno` varchar(32) NOT NULL,
  `phone` int(10) UNSIGNED DEFAULT NULL,
  `gender` varchar(6) NOT NULL,
  `semester` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `username`, `password`, `full_name`, `rollno`, `phone`, `gender`, `semester`) VALUES
(21, 'bca17iash3', '$2y$10$gGHXTyXUDscWuYdcIjrx5enMXmr4v7Ep5H92yGuoK72PgAVTli7xm', 'Iashanlang Kharsynteng', '3', NULL, 'male', '6th Semester'),
(22, 'bca17ruth33', '$2y$10$pC3j1J4j3BWs9klzkgrJRuyZYr.cREUjYQqux2bVzJWfYtHQj4U0S', 'Ruth Amanda Mukhim', '33', NULL, 'female', '6th Semester'),
(24, 'bca17petr1', '$2y$10$/EUJ2AOjpX5EDLzxbo0CYe.SAm8YCIllK0W0nklBVxdiiXNIh5tDC', 'Petrus Sohbar', '1', NULL, 'male', '6th Semester');

-- --------------------------------------------------------

--
-- Table structure for table `students_performance`
--

DROP TABLE IF EXISTS `students_performance`;
CREATE TABLE IF NOT EXISTS `students_performance` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `test1` varchar(3) DEFAULT NULL,
  `test2` varchar(3) DEFAULT NULL,
  `test3` varchar(3) DEFAULT NULL,
  `assignment` varchar(3) DEFAULT NULL,
  `subject_ia` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `f10` (`student_id`),
  KEY `f11` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(255) NOT NULL,
  `semester` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `semester`) VALUES
(12, 'C Programming', '1st Semester'),
(14, 'Math 1', '1st Semester'),
(15, 'DCF', '1st Semester'),
(16, 'EVS', '6th Semester'),
(17, 'Data Mining', '6th Semester');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `password`, `full_name`) VALUES
(1, 'james', '$2y$10$vY/6Kct5gILBX5QanTohvuDH86oFoKt8IJKL7nue4szNwegFZ1iTe', 'James Franco');

-- --------------------------------------------------------

--
-- Table structure for table `teaches`
--

DROP TABLE IF EXISTS `teaches`;
CREATE TABLE IF NOT EXISTS `teaches` (
  `teacher_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  KEY `f2` (`teacher_id`),
  KEY `f3` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `f7` FOREIGN KEY (`asheet_id`) REFERENCES `attendance_sheet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `f8` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance_sheet`
--
ALTER TABLE `attendance_sheet`
  ADD CONSTRAINT `f4` FOREIGN KEY (`teachers_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `f5` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students_performance`
--
ALTER TABLE `students_performance`
  ADD CONSTRAINT `f10` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `f11` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teaches`
--
ALTER TABLE `teaches`
  ADD CONSTRAINT `f2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `f3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
