-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for lnu-is
CREATE DATABASE IF NOT EXISTS `lnu-is` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `lnu-is`;

-- Dumping structure for table lnu-is.blood_types
CREATE TABLE IF NOT EXISTS `blood_types` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.blood_types: ~0 rows (approximately)

-- Dumping structure for table lnu-is.civil_statuses
CREATE TABLE IF NOT EXISTS `civil_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.civil_statuses: ~0 rows (approximately)

-- Dumping structure for table lnu-is.countries
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.countries: ~0 rows (approximately)

-- Dumping structure for table lnu-is.educ_buildings
CREATE TABLE IF NOT EXISTS `educ_buildings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_buildings_users` (`updated_by`),
  CONSTRAINT `educ_buildings_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_buildings: ~0 rows (approximately)

-- Dumping structure for table lnu-is.educ_courses
CREATE TABLE IF NOT EXISTS `educ_courses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `curriculum_id` int unsigned DEFAULT NULL,
  `grade_level_id` int unsigned DEFAULT NULL,
  `grade_period_id` tinyint unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `shorten` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `units` tinyint DEFAULT NULL,
  `description` longtext,
  `status_id` tinyint unsigned DEFAULT NULL,
  `group_course_id` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_courses_educ_curriculum` (`curriculum_id`),
  KEY `educ_courses_users` (`updated_by`),
  KEY `educ_courses_educ_grade_level` (`grade_level_id`),
  KEY `educ_courses_educ_grade_period` (`grade_period_id`),
  KEY `educ_courses_educ_courses` (`group_course_id`),
  KEY `educ_courses_educ_course_status` (`status_id`),
  CONSTRAINT `educ_courses_educ_course_status` FOREIGN KEY (`status_id`) REFERENCES `educ_course_status` (`id`),
  CONSTRAINT `educ_courses_educ_courses` FOREIGN KEY (`group_course_id`) REFERENCES `educ_courses` (`id`),
  CONSTRAINT `educ_courses_educ_curriculum` FOREIGN KEY (`curriculum_id`) REFERENCES `educ_curriculum` (`id`),
  CONSTRAINT `educ_courses_educ_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `educ_year_level` (`id`),
  CONSTRAINT `educ_courses_educ_grade_period` FOREIGN KEY (`grade_period_id`) REFERENCES `educ_grade_period` (`id`),
  CONSTRAINT `educ_courses_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_courses: ~0 rows (approximately)
INSERT INTO `educ_courses` (`id`, `curriculum_id`, `grade_level_id`, `grade_period_id`, `name`, `shorten`, `code`, `units`, `description`, `status_id`, `group_course_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 15, 1, 'abva', 'a', 'SE', 3, 'fsafsa', 1, 1, 1, '2023-03-03 21:49:58', '2023-03-03 21:49:58'),
	(2, 1, 16, 1, 'fsa', 'sfa', 'sfa', 3, 'gsa', 1, 1, 1, '2023-03-07 21:09:18', '2023-03-07 21:09:23'),
	(3, 2, 15, 2, 'bb', 'cc', 'c', 3, 'saf', 1, NULL, 1, '2023-03-07 21:10:44', '2023-03-07 21:10:44'),
	(4, 3, 19, 1, 'aa', 'aa', 'aa', 3, 'aa', 1, NULL, 1, '2023-03-08 07:51:57', '2023-03-08 07:51:58'),
	(5, 1, 15, 2, 'aa', 'aa', 'aa', 3, 'aa', 1, NULL, 1, '2023-03-08 11:37:16', '2023-03-08 11:37:17');

-- Dumping structure for table lnu-is.educ_course_status
CREATE TABLE IF NOT EXISTS `educ_course_status` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_course_status_users` (`updated_by`),
  CONSTRAINT `educ_course_status_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_course_status: ~0 rows (approximately)
INSERT INTO `educ_course_status` (`id`, `name`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'Open', 1, '2023-02-25 02:21:32', '2023-02-25 02:21:33'),
	(2, 'Closed', 1, '2023-02-25 02:22:09', '2023-02-25 02:22:10');

-- Dumping structure for table lnu-is.educ_curriculum
CREATE TABLE IF NOT EXISTS `educ_curriculum` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `program_id` int unsigned DEFAULT NULL,
  `year_from` year DEFAULT NULL,
  `year_to` year DEFAULT NULL,
  `status_id` tinyint unsigned DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_curriculum_educ_programs` (`program_id`),
  KEY `educ_curriculum_users` (`updated_by`),
  KEY `educ_curriculum_educ_course_status` (`status_id`),
  CONSTRAINT `educ_curriculum_educ_course_status` FOREIGN KEY (`status_id`) REFERENCES `educ_course_status` (`id`),
  CONSTRAINT `educ_curriculum_educ_programs` FOREIGN KEY (`program_id`) REFERENCES `educ_programs` (`id`),
  CONSTRAINT `educ_curriculum_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_curriculum: ~2 rows (approximately)
INSERT INTO `educ_curriculum` (`id`, `program_id`, `year_from`, `year_to`, `status_id`, `remarks`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, '2013', '2023', 1, NULL, 1, '2023-03-03 21:39:10', '2023-03-03 21:39:10'),
	(2, 2, '2023', '2023', 1, NULL, 1, '2023-03-03 21:40:09', '2023-03-03 21:40:09'),
	(3, 3, '2023', '2023', 1, NULL, 1, '2023-03-08 07:51:21', '2023-03-08 07:51:22');

-- Dumping structure for table lnu-is.educ_department
CREATE TABLE IF NOT EXISTS `educ_department` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `shorten` varchar(10) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_department_users` (`updated_by`),
  CONSTRAINT `educ_department_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_department: ~3 rows (approximately)
INSERT INTO `educ_department` (`id`, `name`, `shorten`, `code`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'College of Arts and Sciences', 'CAS', 'A', 1, '2023-02-25 04:23:12', '2023-02-25 04:23:13'),
	(2, 'College of Management & Entrepreneurship', 'CME', 'M', 1, '2023-02-25 04:24:14', '2023-02-25 04:24:14'),
	(3, 'College of Education', 'COE', 'E', 1, '2023-02-25 04:24:34', '2023-02-25 04:24:35'),
	(4, 'Graduate School', 'GS', 'G', 1, '2023-02-25 04:26:04', '2023-02-25 04:26:05');

-- Dumping structure for table lnu-is.educ_grade_period
CREATE TABLE IF NOT EXISTS `educ_grade_period` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_grade_period_users` (`updated_by`),
  CONSTRAINT `educ_grade_period_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_grade_period: ~2 rows (approximately)
INSERT INTO `educ_grade_period` (`id`, `name`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'First Semester', 1, '2023-02-25 01:35:06', '2023-02-25 01:35:07'),
	(2, 'Second Semester', 1, '2023-02-25 01:35:08', '2023-02-25 01:35:07');

-- Dumping structure for table lnu-is.educ_programs
CREATE TABLE IF NOT EXISTS `educ_programs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int unsigned DEFAULT NULL,
  `program_level_id` tinyint unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `shorten` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `description` longtext,
  `status_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_programs_educ_programs_level` (`program_level_id`),
  KEY `educ_programs_users` (`updated_by`),
  KEY `educ_programs_educ_department` (`department_id`),
  KEY `educ_programs_educ_course_status` (`status_id`),
  CONSTRAINT `educ_programs_educ_course_status` FOREIGN KEY (`status_id`) REFERENCES `educ_course_status` (`id`),
  CONSTRAINT `educ_programs_educ_department` FOREIGN KEY (`department_id`) REFERENCES `educ_department` (`id`),
  CONSTRAINT `educ_programs_educ_programs_level` FOREIGN KEY (`program_level_id`) REFERENCES `educ_programs_level` (`id`),
  CONSTRAINT `educ_programs_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_programs: ~2 rows (approximately)
INSERT INTO `educ_programs` (`id`, `department_id`, `program_level_id`, `name`, `shorten`, `code`, `description`, `status_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 5, 'Bachelor of Science in Information Technology', 'BSIT', NULL, NULL, 1, 1, '2023-02-25 08:40:35', '2023-03-08 01:50:54'),
	(2, 1, 5, 'Bachelor of Science in Biology major in ', 'BSBIO-', NULL, NULL, 1, 1, '2023-02-25 13:06:12', '2023-03-08 01:47:32'),
	(3, 4, 6, 'Master of Science in Information Technology', 'MSIT', NULL, NULL, 1, 1, '2023-03-08 07:51:01', '2023-03-08 07:51:02');

-- Dumping structure for table lnu-is.educ_programs_code
CREATE TABLE IF NOT EXISTS `educ_programs_code` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `program_id` int unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_programs_code_users` (`updated_by`),
  KEY `educ_programs_code_educ_programs` (`program_id`),
  KEY `educ_programs_code_educ_course_status` (`status_id`),
  CONSTRAINT `educ_programs_code_educ_course_status` FOREIGN KEY (`status_id`) REFERENCES `educ_course_status` (`id`),
  CONSTRAINT `educ_programs_code_educ_programs` FOREIGN KEY (`program_id`) REFERENCES `educ_programs` (`id`),
  CONSTRAINT `educ_programs_code_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_programs_code: ~0 rows (approximately)
INSERT INTO `educ_programs_code` (`id`, `program_id`, `name`, `status_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Al', 1, 1, '2023-03-07 18:12:39', '2023-03-07 18:12:39'),
	(2, 1, 'AlS', 1, 1, '2023-03-07 18:38:53', '2023-03-07 18:38:53'),
	(3, 2, 'AB', 1, 1, '2023-03-07 21:44:17', '2023-03-07 21:44:18');

-- Dumping structure for table lnu-is.educ_programs_level
CREATE TABLE IF NOT EXISTS `educ_programs_level` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_level_users` (`updated_by`),
  CONSTRAINT `courses_level_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_programs_level: ~7 rows (approximately)
INSERT INTO `educ_programs_level` (`id`, `name`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'KINDERGARTEN', 1, '2023-02-24 23:50:39', '2023-02-24 23:50:39'),
	(2, 'ELEMENTARY', 1, '2023-02-24 23:50:47', '2023-02-24 23:50:48'),
	(3, 'JUNIOR HIGH SCHOOL', 1, '2023-02-24 23:51:03', '2023-02-24 23:51:04'),
	(4, 'SENIOR HIGH SCHOOL', 1, '2023-02-24 23:51:13', '2023-02-24 23:51:13'),
	(5, 'UNDERGRADUATE', 1, '2023-02-24 23:51:20', '2023-02-24 23:51:22'),
	(6, 'MASTERAL', 1, '2023-02-24 23:51:57', '2023-02-24 23:51:57'),
	(7, 'DOCTORAL', 1, '2023-02-24 23:52:16', '2023-02-24 23:52:17');

-- Dumping structure for table lnu-is.educ_rooms
CREATE TABLE IF NOT EXISTS `educ_rooms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `building_id` int unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_rooms_educ_buildings` (`building_id`),
  KEY `educ_rooms_users` (`updated_by`),
  CONSTRAINT `educ_rooms_educ_buildings` FOREIGN KEY (`building_id`) REFERENCES `educ_buildings` (`id`),
  CONSTRAINT `educ_rooms_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_rooms: ~0 rows (approximately)

-- Dumping structure for table lnu-is.educ_time_max
CREATE TABLE IF NOT EXISTS `educ_time_max` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `min_student` int DEFAULT NULL,
  `max_student` int DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_time_period_users` (`updated_by`),
  CONSTRAINT `educ_time_period_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_time_max: ~1 rows (approximately)
INSERT INTO `educ_time_max` (`id`, `time_from`, `time_to`, `min_student`, `max_student`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, '07:30:00', '07:00:00', 10, 40, 1, '2023-02-25 03:07:24', '2023-02-25 03:07:25');

-- Dumping structure for table lnu-is.educ_year_level
CREATE TABLE IF NOT EXISTS `educ_year_level` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `program_level_id` tinyint unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `level` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ_year_level_educ_programs_level` (`program_level_id`),
  KEY `educ_year_level_users` (`updated_by`),
  CONSTRAINT `educ_year_level_educ_programs_level` FOREIGN KEY (`program_level_id`) REFERENCES `educ_programs_level` (`id`),
  CONSTRAINT `educ_year_level_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ_year_level: ~20 rows (approximately)
INSERT INTO `educ_year_level` (`id`, `program_level_id`, `name`, `level`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Kinder 1', 1, 1, '2023-02-25 01:23:16', '2023-02-25 01:23:17'),
	(2, 1, 'Kinder 2', 2, 1, '2023-02-25 01:23:30', '2023-02-25 01:23:31'),
	(3, 2, 'Grade 1', 1, 1, '2023-02-25 01:23:48', '2023-02-25 01:23:49'),
	(4, 2, 'Grade 2', 2, 1, '2023-02-25 01:24:03', '2023-02-25 01:24:04'),
	(5, 2, 'Grade 3', 3, 1, '2023-02-25 01:24:15', '2023-02-25 01:24:15'),
	(6, 2, 'Grade 4', 4, 1, '2023-02-25 01:24:30', '2023-02-25 01:24:31'),
	(7, 2, 'Grade 5', 5, 1, '2023-02-25 01:24:41', '2023-02-25 01:24:41'),
	(8, 2, 'Grade 6', 6, 1, '2023-02-25 01:24:51', '2023-02-25 01:24:51'),
	(9, 3, 'Grade 7', 7, 1, '2023-02-25 01:25:11', '2023-02-25 01:25:11'),
	(10, 3, 'Grade 8', 8, 1, '2023-02-25 01:25:21', '2023-02-25 01:25:21'),
	(11, 3, 'Grade 9', 9, 1, '2023-02-25 01:25:33', '2023-02-25 01:25:34'),
	(12, 3, 'Grade 10', 10, 1, '2023-02-25 01:25:44', '2023-02-25 01:25:44'),
	(13, 4, 'Grade 11', 11, 1, '2023-02-25 01:26:22', '2023-02-25 01:26:22'),
	(14, 4, 'Grade 12', 12, 1, '2023-02-25 01:26:32', '2023-02-25 01:26:32'),
	(15, 5, '1st Year', 1, 1, '2023-02-25 01:26:45', '2023-02-25 01:26:45'),
	(16, 5, '2nd Year', 2, 1, '2023-02-25 01:26:54', '2023-02-25 01:26:54'),
	(17, 5, '3rd Year', 3, 1, '2023-02-25 01:27:04', '2023-02-25 01:27:04'),
	(18, 5, '4th Year', 4, 1, '2023-02-25 01:27:16', '2023-02-25 01:27:16'),
	(19, 6, 'Masteral', NULL, 1, '2023-02-25 01:28:15', '2023-02-25 01:28:16'),
	(20, 7, 'Doctoral', NULL, 1, '2023-02-25 01:28:27', '2023-02-25 01:28:27');

-- Dumping structure for table lnu-is.educ__offered_courses
CREATE TABLE IF NOT EXISTS `educ__offered_courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `offered_curriculum_id` bigint unsigned DEFAULT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `min_student` tinyint unsigned DEFAULT NULL,
  `max_student` tinyint unsigned DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `section` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `section_code` varchar(50) DEFAULT NULL,
  `instructor_id` bigint unsigned DEFAULT NULL,
  `status_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_courses: ~4 rows (approximately)
INSERT INTO `educ__offered_courses` (`id`, `offered_curriculum_id`, `course_id`, `min_student`, `max_student`, `code`, `section`, `section_code`, `instructor_id`, `status_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(19, 19, 3, 10, 40, 'c', '1', 'AB11', NULL, NULL, 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(20, 20, 1, 10, 40, 'SE', '1', 'Al11', NULL, 1, 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(21, 21, 1, 10, 40, 'SE', '1', 'AlS11', NULL, 1, 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(22, 23, 1, 10, 40, 'SE', '1', 'Al11', NULL, 1, 1, '2023-03-08 01:51:10', '2023-03-08 01:51:10'),
	(23, 24, 1, 10, 40, 'SE', '1', 'AlS11', NULL, 1, 1, '2023-03-08 01:51:10', '2023-03-08 01:51:10');

-- Dumping structure for table lnu-is.educ__offered_curriculum
CREATE TABLE IF NOT EXISTS `educ__offered_curriculum` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `offered_program_id` bigint unsigned DEFAULT NULL,
  `curriculum_id` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ__offered_curriculum_educ_curriculum` (`curriculum_id`),
  KEY `educ__offered_curriculum_educ__offered_programs` (`offered_program_id`),
  KEY `educ__offered_curriculum_users` (`updated_by`),
  CONSTRAINT `educ__offered_curriculum_educ__offered_programs` FOREIGN KEY (`offered_program_id`) REFERENCES `educ__offered_programs` (`id`),
  CONSTRAINT `educ__offered_curriculum_educ_curriculum` FOREIGN KEY (`curriculum_id`) REFERENCES `educ_curriculum` (`id`),
  CONSTRAINT `educ__offered_curriculum_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_curriculum: ~6 rows (approximately)
INSERT INTO `educ__offered_curriculum` (`id`, `offered_program_id`, `curriculum_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(17, 40, 1, 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(18, 41, 1, 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(19, 42, 2, 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(20, 43, 1, 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(21, 44, 1, 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(22, 45, 2, 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(23, 46, 1, 1, '2023-03-08 01:51:10', '2023-03-08 01:51:10'),
	(24, 47, 1, 1, '2023-03-08 01:51:10', '2023-03-08 01:51:10'),
	(25, 48, 2, 1, '2023-03-08 01:51:10', '2023-03-08 01:51:10');

-- Dumping structure for table lnu-is.educ__offered_department
CREATE TABLE IF NOT EXISTS `educ__offered_department` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_year_id` int unsigned DEFAULT NULL,
  `department_id` int unsigned DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `shorten` varchar(10) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ__offered_department_educ_department` (`department_id`),
  KEY `educ__offered_department_educ__offered_school_year` (`school_year_id`),
  KEY `educ__offered_department_users` (`updated_by`),
  CONSTRAINT `educ__offered_department_educ__offered_school_year` FOREIGN KEY (`school_year_id`) REFERENCES `educ__offered_school_year` (`id`),
  CONSTRAINT `educ__offered_department_educ_department` FOREIGN KEY (`department_id`) REFERENCES `educ_department` (`id`),
  CONSTRAINT `educ__offered_department_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_department: ~1 rows (approximately)
INSERT INTO `educ__offered_department` (`id`, `school_year_id`, `department_id`, `name`, `shorten`, `code`, `updated_by`, `created_at`, `updated_at`) VALUES
	(9, 32, 1, 'College of Arts and Sciences', 'CAS', 'A', 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(10, 33, 1, 'College of Arts and Sciences', 'CAS', 'A', 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(11, 34, 1, 'College of Arts and Sciences', 'CAS', 'A', 1, '2023-03-08 01:51:09', '2023-03-08 01:51:09');

-- Dumping structure for table lnu-is.educ__offered_programs
CREATE TABLE IF NOT EXISTS `educ__offered_programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_year_id` int unsigned DEFAULT NULL,
  `program_id` int unsigned DEFAULT NULL,
  `department_id` int unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ__offered_programs_educ_programs` (`program_id`),
  KEY `educ__offered_programs_users` (`updated_by`),
  KEY `educ__offered_programs_educ__offered_school_year` (`school_year_id`),
  KEY `educ__offered_programs_educ_department` (`department_id`) USING BTREE,
  CONSTRAINT `educ__offered_programs_educ__offered_school_year` FOREIGN KEY (`school_year_id`) REFERENCES `educ__offered_school_year` (`id`),
  CONSTRAINT `educ__offered_programs_educ_department` FOREIGN KEY (`department_id`) REFERENCES `educ_department` (`id`),
  CONSTRAINT `educ__offered_programs_educ_programs` FOREIGN KEY (`program_id`) REFERENCES `educ_programs` (`id`),
  CONSTRAINT `educ__offered_programs_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_programs: ~6 rows (approximately)
INSERT INTO `educ__offered_programs` (`id`, `school_year_id`, `program_id`, `department_id`, `name`, `updated_by`, `created_at`, `updated_at`) VALUES
	(40, 32, 1, 1, 'Al', 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(41, 32, 1, 1, 'AlS', 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(42, 32, 2, 1, 'AB', 1, '2023-03-07 21:44:50', '2023-03-07 21:44:50'),
	(43, 33, 1, 1, 'Al', 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(44, 33, 1, 1, 'AlS', 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(45, 33, 2, 1, 'AB', 1, '2023-03-08 00:45:04', '2023-03-08 00:45:04'),
	(46, 34, 1, 1, 'Al', 1, '2023-03-08 01:51:09', '2023-03-08 01:51:09'),
	(47, 34, 1, 1, 'AlS', 1, '2023-03-08 01:51:09', '2023-03-08 01:51:09'),
	(48, 34, 2, 1, 'AB', 1, '2023-03-08 01:51:09', '2023-03-08 01:51:09');

-- Dumping structure for table lnu-is.educ__offered_room
CREATE TABLE IF NOT EXISTS `educ__offered_room` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `offered_course_id` bigint unsigned DEFAULT NULL,
  `offered_schedule_id` bigint unsigned DEFAULT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `udpated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `udpated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_room: ~0 rows (approximately)

-- Dumping structure for table lnu-is.educ__offered_schedule
CREATE TABLE IF NOT EXISTS `educ__offered_schedule` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `offered_course_id` bigint unsigned DEFAULT NULL,
  `day` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_schedule: ~0 rows (approximately)

-- Dumping structure for table lnu-is.educ__offered_school_year
CREATE TABLE IF NOT EXISTS `educ__offered_school_year` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `year_from` year DEFAULT NULL,
  `year_to` year DEFAULT NULL,
  `grade_period_id` tinyint unsigned DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `date_extension` date DEFAULT NULL,
  `enrollment_from` date DEFAULT NULL,
  `enrollment_to` date DEFAULT NULL,
  `enrollment_extension` date DEFAULT NULL,
  `add_dropping_from` date DEFAULT NULL,
  `add_dropping_to` date DEFAULT NULL,
  `add_dropping_extension` date DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educ__offered_school_year_educ_grade_period` (`grade_period_id`),
  KEY `educ__offered_school_year_users` (`updated_by`),
  CONSTRAINT `educ__offered_school_year_educ_grade_period` FOREIGN KEY (`grade_period_id`) REFERENCES `educ_grade_period` (`id`),
  CONSTRAINT `educ__offered_school_year_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.educ__offered_school_year: ~2 rows (approximately)
INSERT INTO `educ__offered_school_year` (`id`, `year_from`, `year_to`, `grade_period_id`, `date_from`, `date_to`, `date_extension`, `enrollment_from`, `enrollment_to`, `enrollment_extension`, `add_dropping_from`, `add_dropping_to`, `add_dropping_extension`, `updated_by`, `created_at`, `updated_at`) VALUES
	(32, '2023', '2024', 2, '2023-03-07', '2023-03-07', '2023-03-07', '2023-03-07', '2023-03-07', '2023-03-07', '2023-03-07', '2023-03-07', '2023-03-07', 1, '2023-03-07 21:44:48', '2023-03-07 21:44:48'),
	(33, '2023', '2024', 1, '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', 1, '2023-03-08 00:45:02', '2023-03-08 00:45:02'),
	(34, '2024', '2025', 1, '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', '2023-03-08', 1, '2023-03-08 01:43:55', '2023-03-08 01:43:55');

-- Dumping structure for table lnu-is.eligibilities
CREATE TABLE IF NOT EXISTS `eligibilities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `shorten` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.eligibilities: ~0 rows (approximately)

-- Dumping structure for table lnu-is.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lnu-is.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table lnu-is.fam_relations
CREATE TABLE IF NOT EXISTS `fam_relations` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.fam_relations: ~0 rows (approximately)

-- Dumping structure for table lnu-is.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lnu-is.jobs: ~0 rows (approximately)

-- Dumping structure for table lnu-is.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lnu-is.migrations: ~5 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_02_17_152725_create_jobs_table', 2);

-- Dumping structure for table lnu-is.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lnu-is.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table lnu-is.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lnu-is.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table lnu-is.psgc_brgys
CREATE TABLE IF NOT EXISTS `psgc_brgys` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `city_mun_uacs` int unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `uacs` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uacs` (`uacs`),
  KEY `psgc_brgys_psgc_city_muns` (`city_mun_uacs`),
  CONSTRAINT `psgc_brgys_psgc_city_muns` FOREIGN KEY (`city_mun_uacs`) REFERENCES `psgc_city_muns` (`uacs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.psgc_brgys: ~0 rows (approximately)

-- Dumping structure for table lnu-is.psgc_city_muns
CREATE TABLE IF NOT EXISTS `psgc_city_muns` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `province_uacs` int unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `uacs` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `psgc_city_mun_psgc_provinces` (`province_uacs`),
  KEY `aucs` (`uacs`) USING BTREE,
  CONSTRAINT `psgc_city_mun_psgc_provinces` FOREIGN KEY (`province_uacs`) REFERENCES `psgc_provinces` (`uacs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.psgc_city_muns: ~0 rows (approximately)

-- Dumping structure for table lnu-is.psgc_provinces
CREATE TABLE IF NOT EXISTS `psgc_provinces` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `region_uacs` int unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `uacs` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uacs` (`uacs`),
  KEY `psgc_provinces_psgc_regions` (`region_uacs`),
  CONSTRAINT `psgc_provinces_psgc_regions` FOREIGN KEY (`region_uacs`) REFERENCES `psgc_regions` (`uacs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.psgc_provinces: ~0 rows (approximately)

-- Dumping structure for table lnu-is.psgc_regions
CREATE TABLE IF NOT EXISTS `psgc_regions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `uacs` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uacs` (`uacs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.psgc_regions: ~0 rows (approximately)

-- Dumping structure for table lnu-is.status
CREATE TABLE IF NOT EXISTS `status` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `button` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_users` (`user_id`),
  CONSTRAINT `status_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.status: ~3 rows (approximately)
INSERT INTO `status` (`id`, `name`, `button`, `icon`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Active', 'btn btn-success btn-success-scan', 'fa fa-check', 1, '2023-02-18 20:54:09', '2023-02-18 20:54:10'),
	(2, 'Inactive', 'btn btn-danger btn-danger-scan', 'fa fa-times', 1, '2023-02-18 20:54:53', '2023-02-18 20:54:54'),
	(3, 'On-hold', 'btn btn-warning btn-warning-scan', 'fa fa-times', 1, '2023-02-23 23:12:32', '2023-02-23 23:12:33');

-- Dumping structure for table lnu-is.students_courses
CREATE TABLE IF NOT EXISTS `students_courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `offered_course_id` bigint unsigned DEFAULT NULL,
  `grade` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `graded_by` bigint unsigned DEFAULT NULL,
  `student_course_status_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_courses_users` (`user_id`),
  KEY `students_courses_educ__offered_courses` (`offered_course_id`),
  KEY `students_courses_users_graded_by` (`graded_by`),
  KEY `students_courses_students_course_status` (`student_course_status_id`),
  KEY `students_courses_users_by` (`updated_by`),
  CONSTRAINT `students_courses_educ__offered_courses` FOREIGN KEY (`offered_course_id`) REFERENCES `educ__offered_courses` (`id`),
  CONSTRAINT `students_courses_students_course_status` FOREIGN KEY (`student_course_status_id`) REFERENCES `students_course_status` (`id`),
  CONSTRAINT `students_courses_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `students_courses_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `students_courses_users_graded_by` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.students_courses: ~0 rows (approximately)

-- Dumping structure for table lnu-is.students_course_status
CREATE TABLE IF NOT EXISTS `students_course_status` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `shorten` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_course_status_users` (`updated_by`),
  CONSTRAINT `students_course_status_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.students_course_status: ~4 rows (approximately)
INSERT INTO `students_course_status` (`id`, `name`, `shorten`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'Passed', 'Passed', 1, '2023-02-25 02:49:39', '2023-02-25 02:49:39'),
	(2, 'Failed', 'Failed', 1, '2023-02-25 03:53:10', '2023-02-25 03:53:11'),
	(3, 'Drop', 'DR', 1, '2023-02-25 02:49:51', '2023-02-25 02:49:52'),
	(4, 'Incomplete', 'INC', 1, '2023-02-25 03:53:12', '2023-02-25 03:53:11');

-- Dumping structure for table lnu-is.students_info
CREATE TABLE IF NOT EXISTS `students_info` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `program_level_id` tinyint unsigned DEFAULT NULL,
  `program_id` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.students_info: ~0 rows (approximately)

-- Dumping structure for table lnu-is.systems
CREATE TABLE IF NOT EXISTS `systems` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `shorten` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `button` varchar(100) DEFAULT NULL,
  `order` decimal(10,2) DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.systems: ~5 rows (approximately)
INSERT INTO `systems` (`id`, `name`, `shorten`, `icon`, `button`, `order`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Student Information Management System', 'SIMS', 'fa fa-user-graduate', 'button-primary', 1.00, 1, '2023-02-18 12:58:55', '2023-02-25 03:33:45'),
	(2, 'Library Management System', 'LMS', 'fa fa-book', 'button-info', 2.00, 1, '2023-02-18 12:59:25', '2023-02-18 12:59:26'),
	(3, 'Registrar Information Management System', 'RIMS', 'fa fa-list-alt', 'button-warning', 4.00, 1, '2023-02-24 02:43:02', '2023-03-04 03:40:59'),
	(4, 'Admission Management System', 'ADMS', 'fa fa-university', 'button-success', 3.00, 1, '2023-02-24 20:14:02', '2023-03-04 03:19:31'),
	(6, 'Human Resource Information Management System', 'HRIMS', 'fa fa-users', 'button-danger', 5.00, 1, '2023-02-24 21:39:03', '2023-03-04 03:41:32');

-- Dumping structure for table lnu-is.systems_defaults
CREATE TABLE IF NOT EXISTS `systems_defaults` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `system_id` int unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `systems_defaults_systems` (`system_id`),
  KEY `systems_defaults_users_level` (`level_id`),
  KEY `systems_defaults_users_role` (`role_id`),
  KEY `systems_defaults_users` (`user_id`),
  CONSTRAINT `systems_defaults_systems` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`),
  CONSTRAINT `systems_defaults_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `systems_defaults_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`),
  CONSTRAINT `systems_defaults_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.systems_defaults: ~0 rows (approximately)
INSERT INTO `systems_defaults` (`id`, `system_id`, `role_id`, `level_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 2, 1, '2023-02-23 16:21:54', '2023-02-23 16:21:54');

-- Dumping structure for table lnu-is.systems_defaults_nav
CREATE TABLE IF NOT EXISTS `systems_defaults_nav` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `system_id` int unsigned DEFAULT NULL,
  `system_nav_id` int unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `systems_defaults_nav_systems` (`system_id`),
  KEY `systems_defaults_nav_systems_nav` (`system_nav_id`),
  KEY `systems_defaults_nav_users_role` (`role_id`),
  KEY `systems_defaults_nav_users_level` (`level_id`),
  KEY `systems_defaults_nav_users` (`user_id`),
  CONSTRAINT `systems_defaults_nav_systems` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`),
  CONSTRAINT `systems_defaults_nav_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `systems_defaults_nav_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`),
  CONSTRAINT `systems_defaults_nav_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.systems_defaults_nav: ~2 rows (approximately)
INSERT INTO `systems_defaults_nav` (`id`, `system_id`, `system_nav_id`, `role_id`, `level_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 2, 2, 1, '2023-02-23 16:22:42', '2023-02-23 16:22:43'),
	(2, 1, 2, 2, 2, 1, '2023-02-23 16:24:33', '2023-02-23 16:24:34');

-- Dumping structure for table lnu-is.systems_defaults_nav_sub
CREATE TABLE IF NOT EXISTS `systems_defaults_nav_sub` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `system_id` int unsigned DEFAULT NULL,
  `system_nav_sub_id` int unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `systems_defaults_nav_sub_systems` (`system_id`),
  KEY `systems_defaults_nav_sub_systems_nav_sub` (`system_nav_sub_id`),
  KEY `systems_defaults_nav_sub_users_role` (`role_id`),
  KEY `systems_defaults_nav_sub_users_level` (`level_id`),
  KEY `systems_defaults_nav_sub_users` (`user_id`),
  CONSTRAINT `systems_defaults_nav_sub_systems` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`),
  CONSTRAINT `systems_defaults_nav_sub_systems_nav_sub` FOREIGN KEY (`system_nav_sub_id`) REFERENCES `systems_nav_sub` (`id`),
  CONSTRAINT `systems_defaults_nav_sub_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `systems_defaults_nav_sub_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`),
  CONSTRAINT `systems_defaults_nav_sub_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.systems_defaults_nav_sub: ~0 rows (approximately)

-- Dumping structure for table lnu-is.systems_nav
CREATE TABLE IF NOT EXISTS `systems_nav` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `system_id` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `order` decimal(10,2) DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `systems_nav_systems` (`system_id`),
  KEY `systems_nav_users` (`user_id`),
  CONSTRAINT `systems_nav_systems` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`),
  CONSTRAINT `systems_nav_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.systems_nav: ~11 rows (approximately)
INSERT INTO `systems_nav` (`id`, `system_id`, `name`, `url`, `icon`, `order`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Home', 'home', 'fa fa-book', 1.00, 1, '2023-02-18 17:38:23', '2023-02-24 17:01:00'),
	(2, 1, 'Information', 'info', 'fa fa-info', 2.00, 1, '2023-02-18 17:42:47', '2023-02-24 16:58:05'),
	(3, 1, 'Subjects', 'subjects', 'fa fa-book', 3.00, 1, '2023-02-18 17:49:38', '2023-02-24 16:38:42'),
	(4, 2, 'Home', 'home', 'fa fa-home', 1.00, 1, '2023-02-18 19:46:13', '2023-02-18 19:46:14'),
	(5, 3, 'Home', 'home', 'fa fa-home', 1.00, 1, '2023-02-24 15:53:13', '2023-02-24 16:05:01'),
	(6, 1, 'Teachers', 'teachers', 'fa fa-users', 4.00, 1, '2023-02-24 17:15:48', '2023-02-24 17:17:22'),
	(7, 4, 'Home', 'home', 'fa fa-home', 1.00, 1, '2023-02-24 20:16:42', '2023-02-24 20:16:42'),
	(8, 4, 'Students', 'students', 'fa fa-user-graduate', 2.00, 1, '2023-02-24 20:17:42', '2023-02-24 20:17:42'),
	(9, 4, 'Admit', 'admit', 'fa fa-edit', 3.00, 1, '2023-02-24 20:18:07', '2023-02-24 20:30:29'),
	(10, 6, 'Home', 'home', 'fa fa-home', 1.00, 1, '2023-02-24 21:39:44', '2023-02-24 21:39:44'),
	(11, 6, 'Employee', 'employee', 'fa fa-users', 2.00, 1, '2023-02-24 21:40:10', '2023-02-24 21:40:10'),
	(12, 6, 'Payroll', 'payroll', 'fa fa-th-list', 3.00, 1, '2023-02-24 21:42:15', '2023-02-24 21:42:15'),
	(13, 3, 'School Year', 'school_year', 'fa fa-school', 6.00, 1, '2023-02-25 06:41:35', '2023-03-08 02:07:42'),
	(14, 3, 'Sections', 'sections', 'fa fa-list-alt', 5.00, 1, '2023-03-08 00:49:08', '2023-03-08 02:07:39'),
	(15, 3, 'Programs', 'programs', 'fa fa-graduation-cap', 4.00, 1, '2023-03-08 01:55:37', '2023-03-08 02:07:15'),
	(16, 3, 'Departments', 'departments', 'fa fa-university', 3.00, 1, '2023-03-08 01:55:57', '2023-03-08 02:37:48'),
	(17, 3, 'Students', 'students', 'fa fa-user-circle', 2.00, 1, '2023-03-08 02:08:55', '2023-03-08 02:09:07');

-- Dumping structure for table lnu-is.systems_nav_sub
CREATE TABLE IF NOT EXISTS `systems_nav_sub` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `system_nav_id` int unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `order` decimal(10,2) DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `systems_nav_sub_systems_nav` (`system_nav_id`),
  KEY `systems_nav_sub_users` (`user_id`),
  CONSTRAINT `systems_nav_sub_systems_nav` FOREIGN KEY (`system_nav_id`) REFERENCES `systems_nav` (`id`),
  CONSTRAINT `systems_nav_sub_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.systems_nav_sub: ~5 rows (approximately)
INSERT INTO `systems_nav_sub` (`id`, `system_nav_id`, `name`, `url`, `icon`, `order`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 3, 'Grades', 'grades', 'fa fa-book', 2.00, 1, '2023-02-18 17:50:06', '2023-02-24 17:02:57'),
	(2, 3, 'Info', 'info', 'fa fa-info', 1.00, 1, '2023-02-24 16:48:54', '2023-02-24 17:21:15'),
	(3, 3, 'Schedule', 'schedule', 'fa fa-calendar', 3.00, 1, '2023-02-24 17:22:07', '2023-02-24 17:22:35'),
	(4, 12, 'Generate', 'generate', 'fa fa-plus-square', 1.00, 1, '2023-02-24 21:42:49', '2023-02-24 21:43:21'),
	(5, 12, 'View', 'payroll_view', 'fa fa-list', 2.00, 1, '2023-02-24 21:44:02', '2023-02-24 21:44:02');

-- Dumping structure for table lnu-is.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `id_no` int unsigned DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middlename` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `users_users_level` (`level_id`),
  KEY `users_users` (`user_id`),
  KEY `users_status` (`status_id`),
  CONSTRAINT `users_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `users_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lnu-is.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `level_id`, `id_no`, `lastname`, `firstname`, `middlename`, `extname`, `remember_token`, `picture`, `status_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'cmcelestialjr', 'eyJpdiI6IlNaeVpmNE00dVY0eVo4OEVyUU1FOWc9PSIsInZhbHVlIjoicHJqZ1dwbEZuVTFDRFhXQmpWMVpiMDJtNDg1SzVJZEtnNnhGeDczZ05RakJOd29ENHFXSXp4OFhlaUdrNzlXWW5neldzamRERTRIYXRJSHFFbmYzUHloRmRsMUxjVnpSMll5YVIrS0VWNGc9IiwibWFjIjoiY2UxMjk0ZjY0ZWQyMDZiODZhOGZmODZlY2NlYzZlZTcyNGQyODllY2MwNTYyYThiNGU3MWVmY2RjZDBjNTJiNSIsInRhZyI6IiJ9', 1, NULL, 'Celestial', 'Cesar', 'Manlapas', 'Jr', NULL, '', 1, 1, '2023-02-17 18:04:07', '2023-02-23 16:01:56'),
	(2, 'sample', 'eyJpdiI6Im4veGcvWDMxSkdnMG1BS2cwNGJ2b0E9PSIsInZhbHVlIjoic1ZZdGQvaUtPN1FleWJNcklQTFZMU3NMZHhEVGRiT25GSzZKOFJ4TkxXK0dPT3haejVoYVQ3eEFseXhXekk0WCtCMjlhc3g0eXgzcUl2Wk12dHhvek5qdzlYNnFSdW9JTHgwVUFKZG43Tmc9IiwibWFjIjoiNmU0YjIyNWNjNDVlMDI5NGI4MWQwNjAzNjczMjM2ODYxMGFmYTRlYjA1YjBjMWEwMTI3NjA1NWVlZjkzZDNkMCIsInRhZyI6IiJ9', 1, NULL, 'sample', 'sample', 'sample', '', NULL, NULL, 1, 1, NULL, NULL);

-- Dumping structure for table lnu-is.users_level
CREATE TABLE IF NOT EXISTS `users_level` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.users_level: ~6 rows (approximately)
INSERT INTO `users_level` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'SuperAdmin', '2023-02-17 23:55:20', '2023-02-17 23:55:21'),
	(2, 'Administrator', '2023-02-17 23:55:48', '2023-02-17 23:55:49'),
	(3, 'Editor', '2023-02-17 23:58:49', '2023-02-17 23:58:50'),
	(4, 'Encoder', '2023-02-17 23:58:55', '2023-02-17 23:58:56'),
	(5, 'Validator', '2023-02-17 23:59:01', '2023-02-17 23:59:02'),
	(6, 'Viewer', '2023-02-17 23:59:07', '2023-02-17 23:59:08');

-- Dumping structure for table lnu-is.users_role
CREATE TABLE IF NOT EXISTS `users_role` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.users_role: ~2 rows (approximately)
INSERT INTO `users_role` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Student', '2023-02-17 23:51:48', '2023-02-17 23:52:00'),
	(2, 'Employee', '2023-02-17 23:51:57', '2023-02-17 23:51:59');

-- Dumping structure for table lnu-is.users_role_list
CREATE TABLE IF NOT EXISTS `users_role_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_role_list_users` (`user_id`),
  KEY `user_role_list_users_role` (`role_id`),
  KEY `user_role_list_users_by` (`updated_by`),
  CONSTRAINT `user_role_list_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_role_list_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `user_role_list_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.users_role_list: ~3 rows (approximately)
INSERT INTO `users_role_list` (`id`, `user_id`, `role_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 1, '2023-02-18 12:49:45', '2023-02-18 12:49:45'),
	(2, 1, 1, 1, '2023-02-18 19:23:07', '2023-02-18 19:23:07'),
	(3, 2, 2, 1, '2023-02-23 23:22:09', '2023-02-23 23:22:10');

-- Dumping structure for table lnu-is.users_systems
CREATE TABLE IF NOT EXISTS `users_systems` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `system_id` int unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_system_systems` (`system_id`),
  KEY `users_system_users_role` (`role_id`),
  KEY `users_system_users_by` (`updated_by`),
  KEY `users_system_users` (`user_id`),
  KEY `users_systems_users_level` (`level_id`),
  CONSTRAINT `users_system_systems` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`),
  CONSTRAINT `users_system_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users_system_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_system_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`),
  CONSTRAINT `users_systems_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.users_systems: ~6 rows (approximately)
INSERT INTO `users_systems` (`id`, `user_id`, `system_id`, `role_id`, `level_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(10, 2, 1, 1, 1, 1, '2023-03-07 15:58:41', '2023-03-07 15:58:41'),
	(11, 2, 2, 1, 1, 1, '2023-03-07 15:58:41', '2023-03-07 15:58:41'),
	(12, 2, 3, 1, 1, 1, '2023-03-07 15:58:41', '2023-03-07 15:58:41'),
	(13, 2, 4, 1, 1, 1, '2023-03-07 15:58:41', '2023-03-07 15:58:41'),
	(14, 2, 6, 1, 1, 1, '2023-03-07 15:58:41', '2023-03-07 15:58:41'),
	(15, 1, 1, 1, 1, 1, '2023-03-08 02:09:39', '2023-03-08 02:09:39'),
	(16, 1, 2, 1, 1, 1, '2023-03-08 02:09:39', '2023-03-08 02:09:39'),
	(17, 1, 3, 1, 1, 1, '2023-03-08 02:09:39', '2023-03-08 02:09:39'),
	(18, 1, 4, 1, 1, 1, '2023-03-08 02:09:39', '2023-03-08 02:09:39'),
	(19, 1, 6, 1, 1, 1, '2023-03-08 02:09:39', '2023-03-08 02:09:39');

-- Dumping structure for table lnu-is.users_systems_nav
CREATE TABLE IF NOT EXISTS `users_systems_nav` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `system_nav_id` int unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_systems_nav_users` (`user_id`),
  KEY `users_systems_nav_systems_nav` (`system_nav_id`),
  KEY `users_systems_nav_users_by` (`updated_by`),
  KEY `users_systems_nav_users_role` (`role_id`),
  KEY `users_systems_nav_users_level` (`level_id`),
  CONSTRAINT `users_systems_nav_systems_nav` FOREIGN KEY (`system_nav_id`) REFERENCES `systems_nav` (`id`),
  CONSTRAINT `users_systems_nav_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users_systems_nav_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_systems_nav_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`),
  CONSTRAINT `users_systems_nav_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.users_systems_nav: ~13 rows (approximately)
INSERT INTO `users_systems_nav` (`id`, `user_id`, `system_nav_id`, `role_id`, `level_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(14, 2, 1, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(15, 2, 2, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(16, 2, 3, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(17, 2, 4, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(18, 2, 5, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(19, 2, 6, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(20, 2, 7, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(21, 2, 8, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(22, 2, 9, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(23, 2, 10, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(24, 2, 11, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(25, 2, 12, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(26, 2, 13, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(27, 1, 1, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(28, 1, 2, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(29, 1, 3, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(30, 1, 4, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(31, 1, 5, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(32, 1, 6, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(33, 1, 7, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(34, 1, 8, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(35, 1, 9, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(36, 1, 10, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(37, 1, 11, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(38, 1, 12, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(39, 1, 13, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(40, 1, 14, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(41, 1, 15, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(42, 1, 16, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41'),
	(43, 1, 17, 1, 1, 1, '2023-03-08 02:09:41', '2023-03-08 02:09:41');

-- Dumping structure for table lnu-is.users_systems_nav_sub
CREATE TABLE IF NOT EXISTS `users_systems_nav_sub` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `system_nav_sub_id` int unsigned DEFAULT NULL,
  `role_id` tinyint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_systems_nav_sub_users` (`user_id`),
  KEY `users_systems_nav_sub_systems_nav_sub` (`system_nav_sub_id`),
  KEY `users_systems_nav_sub_users_role` (`role_id`),
  KEY `users_systems_nav_sub_users_by` (`updated_by`),
  KEY `users_systems_nav_sub_users_level` (`level_id`),
  CONSTRAINT `users_systems_nav_sub_systems_nav_sub` FOREIGN KEY (`system_nav_sub_id`) REFERENCES `systems_nav_sub` (`id`),
  CONSTRAINT `users_systems_nav_sub_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users_systems_nav_sub_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_systems_nav_sub_users_level` FOREIGN KEY (`level_id`) REFERENCES `users_level` (`id`),
  CONSTRAINT `users_systems_nav_sub_users_role` FOREIGN KEY (`role_id`) REFERENCES `users_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is.users_systems_nav_sub: ~5 rows (approximately)
INSERT INTO `users_systems_nav_sub` (`id`, `user_id`, `system_nav_sub_id`, `role_id`, `level_id`, `updated_by`, `created_at`, `updated_at`) VALUES
	(6, 2, 1, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(7, 2, 2, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(8, 2, 3, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(9, 2, 4, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(10, 2, 5, 1, 1, 1, '2023-03-07 15:58:42', '2023-03-07 15:58:42'),
	(11, 1, 1, 1, 1, 1, '2023-03-08 02:09:42', '2023-03-08 02:09:42'),
	(12, 1, 2, 1, 1, 1, '2023-03-08 02:09:42', '2023-03-08 02:09:42'),
	(13, 1, 3, 1, 1, 1, '2023-03-08 02:09:42', '2023-03-08 02:09:42'),
	(14, 1, 4, 1, 1, 1, '2023-03-08 02:09:42', '2023-03-08 02:09:42'),
	(15, 1, 5, 1, 1, 1, '2023-03-08 02:09:42', '2023-03-08 02:09:42');

-- Dumping structure for table lnu-is._education_bg
CREATE TABLE IF NOT EXISTS `_education_bg` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `level_id` tinyint unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `course_id` int unsigned DEFAULT NULL,
  `period_from` varchar(50) DEFAULT NULL,
  `period_to` varchar(50) DEFAULT NULL,
  `units_earned` varchar(50) DEFAULT NULL,
  `year_grad` year DEFAULT NULL,
  `honors` varchar(255) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `education_bg_users` (`user_id`),
  KEY `education_bg_educ_levels` (`level_id`),
  KEY `education_bg_courses` (`course_id`),
  KEY `education_bg_users_by` (`updated_by`),
  CONSTRAINT `education_bg_courses` FOREIGN KEY (`course_id`) REFERENCES `educ_programs` (`id`),
  CONSTRAINT `education_bg_educ_levels` FOREIGN KEY (`level_id`) REFERENCES `lnu-ids`.`educ_levels` (`id`),
  CONSTRAINT `education_bg_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `education_bg_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._education_bg: ~0 rows (approximately)

-- Dumping structure for table lnu-is._eligibility
CREATE TABLE IF NOT EXISTS `_eligibility` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `eligibility_id` int unsigned DEFAULT NULL,
  `rating` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `license_no` varchar(100) DEFAULT NULL,
  `date_validity` date DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eligibility_eligibilities` (`eligibility_id`),
  KEY `eligibility_users` (`updated_by`),
  CONSTRAINT `eligibility_eligibilities` FOREIGN KEY (`eligibility_id`) REFERENCES `eligibilities` (`id`),
  CONSTRAINT `eligibility_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._eligibility: ~0 rows (approximately)

-- Dumping structure for table lnu-is._family_bg
CREATE TABLE IF NOT EXISTS `_family_bg` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `lastname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `extname` varchar(100) DEFAULT NULL,
  `relation_id` tinyint unsigned DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `employer` varchar(255) DEFAULT NULL,
  `employer_address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `family_bg_users` (`user_id`),
  KEY `family_bg_fam_relations` (`relation_id`),
  KEY `family_bg_users_by` (`updated_by`),
  CONSTRAINT `family_bg_fam_relations` FOREIGN KEY (`relation_id`) REFERENCES `fam_relations` (`id`),
  CONSTRAINT `family_bg_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `family_bg_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._family_bg: ~0 rows (approximately)

-- Dumping structure for table lnu-is._learning
CREATE TABLE IF NOT EXISTS `_learning` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `hours` decimal(10,2) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `conducted_by` varchar(255) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `learning_users` (`user_id`),
  KEY `learning_users_by` (`updated_by`),
  CONSTRAINT `learning_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `learning_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._learning: ~0 rows (approximately)

-- Dumping structure for table lnu-is._other_organization
CREATE TABLE IF NOT EXISTS `_other_organization` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `other_organization_users` (`user_id`),
  KEY `other_organization_users_by` (`updated_by`),
  CONSTRAINT `other_organization_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `other_organization_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table lnu-is._other_organization: ~0 rows (approximately)

-- Dumping structure for table lnu-is._other_recognition
CREATE TABLE IF NOT EXISTS `_other_recognition` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `other_recognition_users` (`user_id`),
  KEY `other_recognition_users_by` (`updated_by`),
  CONSTRAINT `other_recognition_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `other_recognition_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._other_recognition: ~0 rows (approximately)

-- Dumping structure for table lnu-is._other_skills
CREATE TABLE IF NOT EXISTS `_other_skills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `other_skills_users` (`user_id`),
  KEY `other_skills_users_by` (`updated_by`),
  CONSTRAINT `other_skills_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `other_skills_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._other_skills: ~0 rows (approximately)

-- Dumping structure for table lnu-is._personal_info
CREATE TABLE IF NOT EXISTS `_personal_info` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `sex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `civil_status_id` tinyint unsigned DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `blood_type_id` tinyint unsigned DEFAULT NULL,
  `gsis_bp_no` varchar(100) DEFAULT NULL,
  `pagibig_no` varchar(100) DEFAULT NULL,
  `philhealth_no` varchar(100) DEFAULT NULL,
  `sss_no` varchar(100) DEFAULT NULL,
  `tin_no` varchar(100) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `citizenship_by` varchar(50) DEFAULT NULL,
  `country_id` int unsigned DEFAULT NULL,
  `telephone_no` varchar(50) DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `contact_no2` varchar(50) DEFAULT NULL,
  `contact_no_official` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `email_official` varchar(100) DEFAULT NULL,
  `res_lot` varchar(50) DEFAULT NULL,
  `res_street` varchar(50) DEFAULT NULL,
  `res_subd` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `res_brgy_id` int unsigned DEFAULT NULL,
  `res_municipality_id` int unsigned DEFAULT NULL,
  `res_province_id` int unsigned DEFAULT NULL,
  `res_zip_code` int unsigned DEFAULT NULL,
  `same_res` varchar(10) DEFAULT NULL,
  `per_lot` varchar(50) DEFAULT NULL,
  `per_street` varchar(50) DEFAULT NULL,
  `per_subd` varchar(200) DEFAULT NULL,
  `per_brgy_id` int unsigned DEFAULT NULL,
  `per_municipality_id` int unsigned DEFAULT NULL,
  `per_province_id` int unsigned DEFAULT NULL,
  `per_zip_code` int unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personal_info_users` (`user_id`),
  KEY `personal_info_civil_statuses` (`civil_status_id`),
  KEY `personal_info_blood_types` (`blood_type_id`),
  KEY `personal_info_countries` (`country_id`),
  KEY `personal_info_psgc_brgys` (`res_brgy_id`),
  KEY `personal_info_psgc_city_muns` (`res_municipality_id`),
  KEY `personal_info_psgc_provinces` (`res_province_id`),
  KEY `personal_info_psgc_brgys_per` (`per_brgy_id`),
  KEY `personal_info_psgc_city_muns_per` (`per_municipality_id`),
  KEY `personal_info_psgc_provinces_per` (`per_province_id`),
  CONSTRAINT `personal_info_blood_types` FOREIGN KEY (`blood_type_id`) REFERENCES `blood_types` (`id`),
  CONSTRAINT `personal_info_civil_statuses` FOREIGN KEY (`civil_status_id`) REFERENCES `civil_statuses` (`id`),
  CONSTRAINT `personal_info_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `personal_info_psgc_brgys` FOREIGN KEY (`res_brgy_id`) REFERENCES `psgc_brgys` (`uacs`),
  CONSTRAINT `personal_info_psgc_brgys_per` FOREIGN KEY (`per_brgy_id`) REFERENCES `psgc_brgys` (`uacs`),
  CONSTRAINT `personal_info_psgc_city_muns` FOREIGN KEY (`res_municipality_id`) REFERENCES `psgc_city_muns` (`uacs`),
  CONSTRAINT `personal_info_psgc_city_muns_per` FOREIGN KEY (`per_municipality_id`) REFERENCES `psgc_city_muns` (`uacs`),
  CONSTRAINT `personal_info_psgc_provinces` FOREIGN KEY (`res_province_id`) REFERENCES `psgc_provinces` (`uacs`),
  CONSTRAINT `personal_info_psgc_provinces_per` FOREIGN KEY (`per_province_id`) REFERENCES `psgc_provinces` (`id`),
  CONSTRAINT `personal_info_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._personal_info: ~0 rows (approximately)

-- Dumping structure for table lnu-is._references
CREATE TABLE IF NOT EXISTS `_references` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `extname` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `references_users` (`user_id`),
  KEY `references_users_by` (`updated_by`),
  CONSTRAINT `references_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `references_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._references: ~0 rows (approximately)

-- Dumping structure for table lnu-is._voluntary
CREATE TABLE IF NOT EXISTS `_voluntary` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `hours` decimal(10,2) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `voluntary_users` (`updated_by`),
  CONSTRAINT `voluntary_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._voluntary: ~0 rows (approximately)

-- Dumping structure for table lnu-is._work
CREATE TABLE IF NOT EXISTS `_work` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` varchar(50) DEFAULT NULL,
  `position_title` varchar(100) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `gov_service` varchar(50) DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_users` (`user_id`),
  KEY `work_users_by` (`updated_by`),
  CONSTRAINT `work_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `work_users_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lnu-is._work: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
