-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb:3306
-- Generation Time: Jun 15, 2026 at 09:27 PM
-- Server version: 11.8.5-MariaDB
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guru_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `status` enum('pending','success','failed','expired','cancelled','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `transaction_code`, `student_id`, `course_id`, `total_amount`, `note`, `status`, `created_at`, `updated_at`) VALUES
(6, 'BKG-20260529-WEPDU', 3, 1, 1000000, 'sdf sdf dsg sdfg dsfg dfg', 'success', '2026-05-29 14:35:49', '2026-06-02 15:23:34'),
(7, 'BKG-20260603-Y046Q', 16, 1, 1000000, 'sdf dsfg dgfs dfg fg fhg fgh fgh', 'success', '2026-06-03 05:25:27', '2026-06-07 15:07:07'),
(8, 'BKG-20260607-HRDPL', 18, 1, 1000000, 'sad asd sadf sdf', 'success', '2026-06-07 09:07:15', '2026-06-07 16:09:52'),
(10, 'BKG-20260610-QHFI6', 20, 1, 1000000, 'ewr wer wrwer werwer wefegretger', 'success', '2026-06-10 14:47:10', '2026-06-10 14:48:14'),
(11, 'BKG-20260610-1PZOZ', 23, 1, 1000000, 'sdsad asdfsadfs dfsdfdsf', 'success', '2026-06-10 14:49:17', '2026-06-10 14:49:39'),
(12, 'BKG-20260612-4PGJN', 25, 1, 1000000, NULL, 'pending', '2026-06-12 19:34:29', '2026-06-12 19:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:8:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"f\";s:10:\"controller\";s:1:\"g\";s:3:\"uri\";s:1:\"h\";s:6:\"method\";s:6:\"action\";s:6:\"action\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:87:{i:0;a:8:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"tampil-users\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:6:\"/users\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:1;a:8:{s:1:\"a\";i:8;s:1:\"b\";s:16:\"view-tambah-user\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:13:\"/users/create\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:6:\"create\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:8:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"post-tambah-user\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:6:\"/users\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:8:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"view-edit-user\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:18:\"/users/{user}/edit\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:4:\"edit\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:4;a:8:{s:1:\"a\";i:11;s:1:\"b\";s:16:\"post-update-user\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:13:\"/users/{user}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:5;a:8:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"hapus-user\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:13:\"/users/{user}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:8:{s:1:\"a\";i:14;s:1:\"b\";s:18:\"update-status-akun\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:20:\"/users/toggle/{user}\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:12:\"toggleStatus\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:8:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"user-update-role\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:14:\"UserController\";s:1:\"g\";s:25:\"/users/{user}/update-role\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:10:\"updateRole\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:8:{s:1:\"a\";i:16;s:1:\"b\";s:10:\"categories\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:18:\"CategoriController\";s:1:\"g\";s:11:\"/categories\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:8:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"categories-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:18:\"CategoriController\";s:1:\"g\";s:11:\"/categories\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:8:{s:1:\"a\";i:18;s:1:\"b\";s:15:\"categories-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:18:\"CategoriController\";s:1:\"g\";s:22:\"/categories/{category}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:8:{s:1:\"a\";i:19;s:1:\"b\";s:16:\"categories-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:18:\"CategoriController\";s:1:\"g\";s:22:\"/categories/{category}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:8:{s:1:\"a\";i:20;s:1:\"b\";s:8:\"bookings\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"BookingController\";s:1:\"g\";s:9:\"/bookings\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:8:{s:1:\"a\";i:21;s:1:\"b\";s:13:\"bookings-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"BookingController\";s:1:\"g\";s:19:\"/bookings/{booking}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:8:{s:1:\"a\";i:22;s:1:\"b\";s:14:\"bookings-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"BookingController\";s:1:\"g\";s:19:\"/bookings/{booking}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:8:{s:1:\"a\";i:23;s:1:\"b\";s:12:\"certificates\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CertificateController\";s:1:\"g\";s:13:\"/certificates\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:8:{s:1:\"a\";i:24;s:1:\"b\";s:16:\"certificates-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CertificateController\";s:1:\"g\";s:13:\"/certificates\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:8:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"certificates-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CertificateController\";s:1:\"g\";s:27:\"/certificates/{certificate}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:8:{s:1:\"a\";i:26;s:1:\"b\";s:9:\"schedules\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"ClassScheduleController\";s:1:\"g\";s:10:\"/schedules\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:8:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"schedules-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"ClassScheduleController\";s:1:\"g\";s:10:\"/schedules\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:8:{s:1:\"a\";i:28;s:1:\"b\";s:14:\"schedules-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"ClassScheduleController\";s:1:\"g\";s:21:\"/schedules/{schedule}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:8:{s:1:\"a\";i:29;s:1:\"b\";s:15:\"schedules-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"ClassScheduleController\";s:1:\"g\";s:21:\"/schedules/{schedule}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:8:{s:1:\"a\";i:30;s:1:\"b\";s:7:\"courses\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"CourseController\";s:1:\"g\";s:8:\"/courses\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:8:{s:1:\"a\";i:31;s:1:\"b\";s:11:\"courses-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"CourseController\";s:1:\"g\";s:8:\"/courses\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:8:{s:1:\"a\";i:32;s:1:\"b\";s:12:\"courses-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"CourseController\";s:1:\"g\";s:17:\"/courses/{course}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:8:{s:1:\"a\";i:33;s:1:\"b\";s:13:\"courses-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"CourseController\";s:1:\"g\";s:17:\"/courses/{course}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:8:{s:1:\"a\";i:34;s:1:\"b\";s:9:\"materials\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CourseMaterialController\";s:1:\"g\";s:10:\"/materials\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:8:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"materials-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CourseMaterialController\";s:1:\"g\";s:10:\"/materials\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:8:{s:1:\"a\";i:36;s:1:\"b\";s:14:\"materials-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CourseMaterialController\";s:1:\"g\";s:21:\"/materials/{material}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:8:{s:1:\"a\";i:37;s:1:\"b\";s:15:\"materials-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CourseMaterialController\";s:1:\"g\";s:21:\"/materials/{material}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:8:{s:1:\"a\";i:38;s:1:\"b\";s:6:\"videos\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CourseVideoController\";s:1:\"g\";s:7:\"/videos\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:8:{s:1:\"a\";i:39;s:1:\"b\";s:10:\"videos-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CourseVideoController\";s:1:\"g\";s:7:\"/videos\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:32;a:8:{s:1:\"a\";i:40;s:1:\"b\";s:11:\"videos-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CourseVideoController\";s:1:\"g\";s:15:\"/videos/{video}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:33;a:8:{s:1:\"a\";i:41;s:1:\"b\";s:12:\"videos-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"CourseVideoController\";s:1:\"g\";s:15:\"/videos/{video}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:8:{s:1:\"a\";i:42;s:1:\"b\";s:15:\"course-students\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"CourseStudentController\";s:1:\"g\";s:16:\"/course-students\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:7:{s:1:\"a\";i:43;s:1:\"b\";s:19:\"course-students-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"CourseStudentController\";s:1:\"g\";s:16:\"/course-students\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";}i:36;a:8:{s:1:\"a\";i:44;s:1:\"b\";s:20:\"course-students-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"CourseStudentController\";s:1:\"g\";s:32:\"/course-students/{courseStudent}\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:8:{s:1:\"a\";i:45;s:1:\"b\";s:21:\"course-students-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"CourseStudentController\";s:1:\"g\";s:32:\"/course-students/{courseStudent}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:8:{s:1:\"a\";i:46;s:1:\"b\";s:8:\"payments\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"PaymentController\";s:1:\"g\";s:9:\"/payments\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:8:{s:1:\"a\";i:47;s:1:\"b\";s:16:\"payments-approve\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"PaymentController\";s:1:\"g\";s:27:\"/payments/{payment}/approve\";s:1:\"h\";s:5:\"patch\";s:6:\"action\";s:7:\"approve\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:8:{s:1:\"a\";i:48;s:1:\"b\";s:15:\"payments-reject\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"PaymentController\";s:1:\"g\";s:26:\"/payments/{payment}/reject\";s:1:\"h\";s:5:\"patch\";s:6:\"action\";s:6:\"reject\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:8:{s:1:\"a\";i:49;s:1:\"b\";s:7:\"reviews\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"ReviewController\";s:1:\"g\";s:8:\"/reviews\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:8:{s:1:\"a\";i:50;s:1:\"b\";s:12:\"review-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"ReviewController\";s:1:\"g\";s:17:\"/reviews/{review}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:8:{s:1:\"a\";i:51;s:1:\"b\";s:8:\"earnings\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherEarningController\";s:1:\"g\";s:9:\"/earnings\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:44;a:8:{s:1:\"a\";i:52;s:1:\"b\";s:20:\"earnings-edit-status\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherEarningController\";s:1:\"g\";s:21:\"/earnings/{id}/status\";s:1:\"h\";s:5:\"patch\";s:6:\"action\";s:12:\"updateStatus\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:45;a:8:{s:1:\"a\";i:53;s:1:\"b\";s:8:\"teachers\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherProfileController\";s:1:\"g\";s:9:\"/teachers\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:46;a:8:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"teachers-verify\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherProfileController\";s:1:\"g\";s:26:\"/teachers/{profile}/verify\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"verify\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:8:{s:1:\"a\";i:55;s:1:\"b\";s:12:\"teachers-add\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherProfileController\";s:1:\"g\";s:9:\"/teachers\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:2;}}i:48;a:8:{s:1:\"a\";i:56;s:1:\"b\";s:13:\"teachers-edit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherProfileController\";s:1:\"g\";s:20:\"/teachers/{profile?}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:1:{i:0;i:2;}}i:49;a:8:{s:1:\"a\";i:57;s:1:\"b\";s:14:\"teachers-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherProfileController\";s:1:\"g\";s:19:\"/teachers/{profile}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:8:{s:1:\"a\";i:58;s:1:\"b\";s:14:\"teachers-by-id\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"TeacherProfileController\";s:1:\"g\";s:19:\"/teachers/{profile}\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:4:\"show\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:8:{s:1:\"a\";i:59;s:1:\"b\";s:13:\"tampil-kursus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"StudentCourseController\";s:1:\"g\";s:14:\"/tampil-kursus\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:3;}}i:52;a:8:{s:1:\"a\";i:60;s:1:\"b\";s:13:\"siswa-booking\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"BookingController\";s:1:\"g\";s:9:\"/bookings\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:3;}}i:53;a:8:{s:1:\"a\";i:61;s:1:\"b\";s:12:\"booking-form\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"BookingController\";s:1:\"g\";s:16:\"/bookings/create\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:6:\"create\";s:1:\"r\";a:1:{i:0;i:3;}}i:54;a:8:{s:1:\"a\";i:62;s:1:\"b\";s:16:\"history-bookings\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"BookingController\";s:1:\"g\";s:17:\"/history-bookings\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:11:\"showHistory\";s:1:\"r\";a:1:{i:0;i:3;}}i:55;a:8:{s:1:\"a\";i:63;s:1:\"b\";s:10:\"my-courses\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"StudentCourseController\";s:1:\"g\";s:11:\"/my-courses\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:8:\"myCourse\";s:1:\"r\";a:1:{i:0;i:3;}}i:56;a:8:{s:1:\"a\";i:64;s:1:\"b\";s:12:\"form-reviews\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"ReviewController\";s:1:\"g\";s:35:\"/student/courses/{course_id}/review\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:6:\"create\";s:1:\"r\";a:1:{i:0;i:3;}}i:57;a:8:{s:1:\"a\";i:65;s:1:\"b\";s:13:\"store-reviews\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:16:\"ReviewController\";s:1:\"g\";s:16:\"/student/reviews\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:3;}}i:58;a:8:{s:1:\"a\";i:66;s:1:\"b\";s:13:\"ruang-belajar\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:23:\"StudentCourseController\";s:1:\"g\";s:33:\"student/courses/{course_id}/learn\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:9:\"roomLearn\";s:1:\"r\";a:1:{i:0;i:3;}}i:59;a:8:{s:1:\"a\";i:67;s:1:\"b\";s:15:\"admin-dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:19:\"DashboardController\";s:1:\"g\";s:16:\"/admin-dashboard\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:14:\"dashboardAdmin\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:8:{s:1:\"a\";i:68;s:1:\"b\";s:14:\"guru-dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:19:\"DashboardController\";s:1:\"g\";s:15:\"/guru-dashboard\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:13:\"dashboardGuru\";s:1:\"r\";a:1:{i:0;i:2;}}i:61;a:8:{s:1:\"a\";i:69;s:1:\"b\";s:15:\"siswa-dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:19:\"DashboardController\";s:1:\"g\";s:16:\"/siswa-dashboard\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:14:\"dashboardSiswa\";s:1:\"r\";a:1:{i:0;i:3;}}i:62;a:8:{s:1:\"a\";i:70;s:1:\"b\";s:18:\"form-payment-class\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"PaymentController\";s:1:\"g\";s:34:\"/payments-class/{transaction_code}\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:15:\"showPaymentForm\";s:1:\"r\";a:1:{i:0;i:3;}}i:63;a:8:{s:1:\"a\";i:71;s:1:\"b\";s:20:\"simpan-payment-class\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:17:\"PaymentController\";s:1:\"g\";s:34:\"/payments-class/{transaction_code}\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:19:\"storeStudentPayment\";s:1:\"r\";a:1:{i:0;i:3;}}i:64;a:8:{s:1:\"a\";i:72;s:1:\"b\";s:16:\"company-accounts\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CompanyAccountController\";s:1:\"g\";s:17:\"/company-accounts\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:8:{s:1:\"a\";i:73;s:1:\"b\";s:20:\"add-company-accounts\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CompanyAccountController\";s:1:\"g\";s:17:\"/company-accounts\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:1;}}i:66;a:8:{s:1:\"a\";i:74;s:1:\"b\";s:21:\"edit-company-accounts\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CompanyAccountController\";s:1:\"g\";s:27:\"/company-accounts/{account}\";s:1:\"h\";s:3:\"put\";s:6:\"action\";s:6:\"update\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:8:{s:1:\"a\";i:75;s:1:\"b\";s:22:\"hapus-company-accounts\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CompanyAccountController\";s:1:\"g\";s:27:\"/company-accounts/{account}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:68;a:8:{s:1:\"a\";i:76;s:1:\"b\";s:18:\"siswa-biodata-form\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"StudentBiodataController\";s:1:\"g\";s:8:\"/biodata\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:9:\"siswaForm\";s:1:\"r\";a:1:{i:0;i:3;}}i:69;a:8:{s:1:\"a\";i:77;s:1:\"b\";s:19:\"siswa-biodata-store\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"StudentBiodataController\";s:1:\"g\";s:8:\"/biodata\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:3;}}i:70;a:8:{s:1:\"a\";i:78;s:1:\"b\";s:13:\"biodata-siswa\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"StudentBiodataController\";s:1:\"g\";s:16:\"/student-biodata\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:5:\"index\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:8:{s:1:\"a\";i:79;s:1:\"b\";s:19:\"biodata-siswa-hapus\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"StudentBiodataController\";s:1:\"g\";s:21:\"/student-biodata/{id}\";s:1:\"h\";s:6:\"delete\";s:6:\"action\";s:7:\"destroy\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:8:{s:1:\"a\";i:80;s:1:\"b\";s:22:\"course-progress-toggle\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CourseProgressController\";s:1:\"g\";s:23:\"/course-progress/toggle\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:14:\"toggleProgress\";s:1:\"r\";a:1:{i:0;i:3;}}i:73;a:8:{s:1:\"a\";i:81;s:1:\"b\";s:21:\"guru-schedules-create\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:22:\"GuruScheduleController\";s:1:\"g\";s:22:\"/guru/schedules/create\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:6:\"create\";s:1:\"r\";a:1:{i:0;i:2;}}i:74;a:8:{s:1:\"a\";i:82;s:1:\"b\";s:20:\"guru-schedules-store\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:22:\"GuruScheduleController\";s:1:\"g\";s:21:\"/guru/schedules/store\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:5:\"store\";s:1:\"r\";a:1:{i:0;i:2;}}i:75;a:8:{s:1:\"a\";i:83;s:1:\"b\";s:10:\"store-quiz\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"QuizManagementController\";s:1:\"g\";s:11:\"/quiz/store\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:9:\"storeQuiz\";s:1:\"r\";a:1:{i:0;i:2;}}i:76;a:8:{s:1:\"a\";i:84;s:1:\"b\";s:10:\"build-quiz\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"QuizManagementController\";s:1:\"g\";s:20:\"/quiz/{quizId}/build\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:9:\"buildQuiz\";s:1:\"r\";a:1:{i:0;i:2;}}i:77;a:8:{s:1:\"a\";i:85;s:1:\"b\";s:14:\"store-question\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"QuizManagementController\";s:1:\"g\";s:24:\"/quiz/{quizId}/questions\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:13:\"storeQuestion\";s:1:\"r\";a:1:{i:0;i:2;}}i:78;a:8:{s:1:\"a\";i:86;s:1:\"b\";s:15:\"review-students\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"QuizManagementController\";s:1:\"g\";s:21:\"/quiz/{quizId}/review\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:14:\"reviewStudents\";s:1:\"r\";a:1:{i:0;i:2;}}i:79;a:8:{s:1:\"a\";i:87;s:1:\"b\";s:21:\"review-single-student\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"QuizManagementController\";s:1:\"g\";s:33:\"/quiz/{quizId}/review/{studentId}\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:19:\"reviewSingleStudent\";s:1:\"r\";a:1:{i:0;i:2;}}i:80;a:8:{s:1:\"a\";i:88;s:1:\"b\";s:12:\"submit-grade\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"QuizManagementController\";s:1:\"g\";s:29:\"/quiz/answer/{answerId}/grade\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:11:\"submitGrade\";s:1:\"r\";a:1:{i:0;i:2;}}i:81;a:8:{s:1:\"a\";i:89;s:1:\"b\";s:20:\"show-detail-material\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"CourseMaterialController\";s:1:\"g\";s:15:\"/materials/{id}\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:4:\"show\";s:1:\"r\";a:1:{i:0;i:2;}}i:82;a:8:{s:1:\"a\";i:90;s:1:\"b\";s:14:\"quiz-introduce\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"StudentQuizController\";s:1:\"g\";s:28:\"/materials/{materialId}/quiz\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:9:\"introduce\";s:1:\"r\";a:1:{i:0;i:3;}}i:83;a:8:{s:1:\"a\";i:91;s:1:\"b\";s:9:\"quiz-take\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"StudentQuizController\";s:1:\"g\";s:19:\"/quiz/{quizId}/take\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:8:\"takeQuiz\";s:1:\"r\";a:1:{i:0;i:3;}}i:84;a:8:{s:1:\"a\";i:92;s:1:\"b\";s:11:\"quiz-submit\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"StudentQuizController\";s:1:\"g\";s:21:\"/quiz/{quizId}/submit\";s:1:\"h\";s:4:\"post\";s:6:\"action\";s:10:\"submitQuiz\";s:1:\"r\";a:1:{i:0;i:3;}}i:85;a:8:{s:1:\"a\";i:93;s:1:\"b\";s:12:\"quiz-history\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:21:\"StudentQuizController\";s:1:\"g\";s:13:\"/quiz/history\";s:1:\"h\";s:3:\"get\";s:6:\"action\";s:7:\"history\";s:1:\"r\";a:1:{i:0;i:3;}}i:86;a:8:{s:1:\"a\";i:94;s:1:\"b\";s:14:\"student-verify\";s:1:\"c\";s:3:\"web\";s:1:\"f\";s:24:\"StudentBiodataController\";s:1:\"g\";s:28:\"/student-biodata/{id}/verify\";s:1:\"h\";s:5:\"patch\";s:6:\"action\";s:6:\"verify\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"guru\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"siswa\";s:1:\"c\";s:3:\"web\";}}}', 1781590773);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bahasa Jepang', 'bahasa-jepang', 'lorem', '2026-05-27 15:22:25', '2026-05-27 15:22:25'),
(2, 'Bahasa Inggris', 'bahasa-inggris', 'dasdasdsad', '2026-05-27 15:22:36', '2026-05-27 15:22:36'),
(3, 'Matematika', 'matematika', 'dfasd asdasfdsdf sdf sdf', '2026-05-27 15:22:49', '2026-05-27 15:22:49'),
(4, 'Mengaji', 'mengaji', 'fsdf sdf dsfsd fsd', '2026-05-27 15:22:58', '2026-05-27 15:22:58'),
(5, 'Musik / Vokal', 'musik-vokal', 'asfsd fsdf sdfsdfsdf', '2026-05-27 15:23:10', '2026-05-27 15:23:10'),
(6, 'Skill Kerja Jepang', 'skill-kerja-jepang', 'fsedf sdf sdfsdf sdf sdfsd', '2026-05-27 15:23:22', '2026-05-27 15:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_code` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `issued_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `student_id`, `course_id`, `certificate_code`, `file_path`, `issued_at`, `created_at`, `updated_at`) VALUES
(2, 3, 1, 'GH-CERT-2026-U7SMWL', 'certificates/nqpM6En055srxXaZsDGutXpEPO7ydRD0UIMBy58I.pdf', '2026-06-03', '2026-06-03 00:33:05', '2026-06-03 00:33:05'),
(3, 16, 1, 'GH-CERT-2026-F0HQQD', 'certificates/D8acAzK467Bwgnu5XWiatzXjkk8c65AgCELqhvyQ.pdf', '2026-06-13', '2026-06-13 00:12:29', '2026-06-13 00:12:29');

-- --------------------------------------------------------

--
-- Table structure for table `class_schedules`
--

CREATE TABLE `class_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `platform` enum('zoom','google_meet') DEFAULT NULL,
  `meeting_link` text DEFAULT NULL,
  `meeting_id` varchar(255) DEFAULT NULL,
  `meeting_password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_schedules`
--

INSERT INTO `class_schedules` (`id`, `course_id`, `material_id`, `topic`, `start_time`, `end_time`, `platform`, `meeting_link`, `meeting_id`, `meeting_password`, `created_at`, `updated_at`) VALUES
(4, 1, 1, 'Live Class: fundamentals123', '2026-06-11 05:00:00', '2026-06-11 06:00:00', 'zoom', 'https://meet.google.com/token-tes', '123432', '123453', '2026-06-10 16:12:16', '2026-06-10 16:12:16'),
(6, 1, 3, 'sfd fsdf sdfs df123', '2026-06-11 01:02:00', '2026-06-11 01:50:00', 'google_meet', 'https://meet.google.com/token-tes', '123432', 'rdt456', '2026-06-10 17:04:59', '2026-06-10 17:05:28'),
(7, 4, NULL, 'zdfsdfsdfdsf', '2026-06-15 12:07:00', '2026-06-16 12:07:00', 'zoom', 'https://gemini.google.com', NULL, NULL, '2026-06-15 04:08:04', '2026-06-15 04:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `company_accounts`
--

CREATE TABLE `company_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_accounts`
--

INSERT INTO `company_accounts` (`id`, `bank_name`, `account_number`, `account_name`, `branch`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BRI', '1234567890', 'Mahmudin M.Pd.', 'Mataram', 1, '2026-06-06 17:03:32', '2026-06-06 17:06:56'),
(2, 'BNI', '1234567890', 'Mahmudin M.Pd.', 'Mataram', 1, '2026-06-06 17:04:25', '2026-06-06 17:07:03'),
(3, 'BCA', '1234567890', 'Mahmudin M.Pd.', 'Mataram', 1, '2026-06-06 17:05:03', '2026-06-06 17:07:12'),
(4, 'Mandiri', '1234567890', 'Mahmudin M.Pd.', 'Mataram', 1, '2026-06-06 17:05:31', '2026-06-06 17:07:18'),
(5, 'BSI', '1234567890', 'Mahmudin M.Pd.', 'Mataram', 1, '2026-06-06 17:06:04', '2026-06-06 17:07:26');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `teacher_id`, `category_id`, `title`, `description`, `cover_image`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Kelas Online Bahasa Inggris', 'The standard chunk of Lorem Ipsum used since 1966 is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 'courses/covers/pWDy5DZnwVNuS2fEQmyJzQ0Io7C5bNWFsVf5497J.png', 1000000.00, 'published', '2026-05-28 15:42:24', '2026-06-12 20:22:45'),
(3, 15, 6, 'Dasar-dasar Teknik Kerja di Jepang', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'courses/covers/02TXGEaxJI8X6i2uXndEXLT6RDOWTDUrxUBROOb2.jpg', 100000.00, 'published', '2026-06-03 05:13:21', '2026-06-15 21:00:39'),
(4, 2, 3, 'Aljabar Linear', 'Lorem ipsum adalah teks placeholder (pengisi) standar dalam desain grafis dan percetakan. Berasal dari naskah klasik Latin karya Cicero pada tahun 45 SM, teks ini digunakan untuk menampilkan tata letak visual tanpa mengalihkan perhatian dari elemen desain.', 'courses/covers/L1Bh3CyMblVRlkEPBotEGv52CozdewL4PB3D4tFA.png', 500000.00, 'published', '2026-06-03 19:21:22', '2026-06-12 20:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `course_materials`
--

CREATE TABLE `course_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_materials`
--

INSERT INTO `course_materials` (`id`, `course_id`, `title`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'Introduction', 'courses/materials/5NKocjCA558jjR3WAUdsk94rOwzbHPLySRsJIgeT.pdf', '2026-05-28 15:44:21', '2026-06-10 21:14:30'),
(2, 1, 'Vocabullary', 'courses/materials/FnqAKcS7xUoZ65UPbkJ8qmZmLULZltV2JevioMVS.pdf', '2026-06-03 05:31:28', '2026-06-10 21:14:54'),
(3, 1, 'Vocabullary session 2', 'courses/materials/vGdnX0J1zsZUwfUTm4Cm28OnOEvx1qwPKm6KwCeX.pdf', '2026-06-03 17:57:19', '2026-06-10 21:15:17'),
(4, 4, 'Indroduction', 'courses/materials/0nsmTlYGBV8BkanwsYUVjW5jl3hAEJGFBT3NXw7X.pdf', '2026-06-15 07:10:28', '2026-06-15 07:10:28'),
(5, 3, 'Introduction Bahasa Jepang', 'courses/materials/0PjyhRaoUJPWQ9pghmY9k3Pg80qDyY7vSYd1azp3.pdf', '2026-06-15 07:12:46', '2026-06-15 07:12:46');

-- --------------------------------------------------------

--
-- Table structure for table `course_students`
--

CREATE TABLE `course_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_students`
--

INSERT INTO `course_students` (`id`, `course_id`, `student_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'completed', '2026-05-28 16:21:14', '2026-06-03 00:33:05'),
(2, 1, 16, 'active', '2026-06-07 11:26:07', '2026-06-15 03:54:42'),
(3, 1, 18, 'active', '2026-06-07 16:09:52', '2026-06-15 03:54:35'),
(4, 1, 20, 'active', '2026-06-10 14:48:14', '2026-06-11 22:18:05'),
(5, 1, 23, 'active', '2026-06-10 14:49:39', '2026-06-11 22:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `course_videos`
--

CREATE TABLE `course_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_type` enum('material','recording') NOT NULL DEFAULT 'material',
  `video_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_videos`
--

INSERT INTO `course_videos` (`id`, `course_id`, `title`, `video_type`, `video_url`, `created_at`, `updated_at`) VALUES
(8, 1, 'Fundamentals', 'material', 'http://localhost:8002/storage/videos/converted/Download_1780754768.webm', '2026-06-06 13:59:48', '2026-06-06 14:06:10'),
(9, 1, 'sdfsdfsdfsd', 'recording', 'http://localhost:8002/storage/videos/converted/Download_1780849545.webm', '2026-06-07 16:25:55', '2026-06-07 16:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_05_160212_create_permission_tables', 1),
(8, '2025_07_07_084233_add_route_data_to_permissions_table', 2),
(9, '2026_05_27_081015_create_categoris_table', 3),
(10, '2026_05_27_081156_create_teacher_profiles_table', 4),
(11, '2026_05_27_081255_create_courses_table', 5),
(12, '2026_05_27_081347_create_course_materials_table', 6),
(13, '2026_05_27_081448_create_course_videos_table', 7),
(14, '2026_05_27_081532_create_class_schedules_table', 8),
(15, '2026_05_27_081645_create_course_students_table', 9),
(17, '2026_05_27_081807_create_payments_table', 11),
(18, '2026_05_27_081917_create_teacher_earnings_table', 12),
(19, '2026_05_27_081951_create_certificates_table', 13),
(20, '2026_05_27_082036_create_reviews_table', 14),
(21, '2026_05_27_081731_create_bookings_table', 15),
(22, '2026_06_06_163911_create_company_accounts_table', 16),
(23, '2026_06_08_125041_create_student_biodatas_table', 17),
(24, '2026_06_10_140259_create_user_progress_table', 18),
(25, '2026_06_10_160058_add_material_id_to_class_schedules_table', 19),
(26, '2026_06_10_183911_create_quizzes_table', 20),
(27, '2026_06_10_183934_create_questions_table', 21),
(28, '2026_06_10_183948_create_question_options_table', 22),
(29, '2026_06_10_185852_create_student_answers_table', 23);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 18),
(3, 'App\\Models\\User', 20),
(3, 'App\\Models\\User', 21),
(3, 'App\\Models\\User', 23),
(2, 'App\\Models\\User', 24),
(3, 'App\\Models\\User', 25);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_proof_path` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `earning_status` varchar(50) NOT NULL DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `course_id`, `invoice_number`, `amount`, `payment_proof_path`, `status`, `earning_status`, `verified_at`, `verified_by`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'INV-202606-NC7LB', 1000000.00, 'payment_proofs/vU9TKFzd5bXsHq6ozYjTOwtKc04EY33Zk7XI22Rv.jpg', 'approved', 'paid', '2026-06-02 15:23:34', 1, NULL, '2026-06-02 14:08:28', '2026-06-11 20:09:59'),
(2, 16, 1, 'INV-202606-RDRGB', 1000000.00, 'payment_proofs/8rOrZFA6VIr0X2pERmnYHQGiljyTnDq4RxTyLCF4.jpg', 'approved', 'paid', '2026-06-07 15:07:07', 1, NULL, '2026-06-07 09:34:14', '2026-06-11 20:05:49'),
(4, 18, 1, 'INV-202606-00NKJ', 1000000.00, 'payment_proofs/wIgejdwZHAFJE3FZHgcJzEd899XI4U7k9TVVKa78.jpg', 'approved', 'pending', '2026-06-11 22:17:35', 1, NULL, '2026-06-07 16:08:31', '2026-06-11 22:17:35'),
(5, 20, 1, 'INV-202606-CMBBU', 1000000.00, 'payment_proofs/uaMty2sWWcM6bV6lXq8RtPpsgCxgGiGzkmsgWlKa.jpg', 'approved', 'pending', '2026-06-11 22:18:05', 1, NULL, '2026-06-10 14:47:25', '2026-06-11 22:18:05'),
(6, 23, 1, 'INV-202606-VS79W', 1000000.00, 'payment_proofs/o2VJvoL6TSrNhEilTLAb0p9lbcZfcUnZXrF1u5bm.jpg', 'approved', 'pending', '2026-06-11 22:18:14', 1, NULL, '2026-06-10 14:49:28', '2026-06-11 22:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `controller`, `uri`, `method`, `action`) VALUES
(7, 'tampil-users', 'web', '2025-07-07 10:06:01', '2025-07-07 11:09:18', 'UserController', '/users', 'get', 'index'),
(8, 'view-tambah-user', 'web', '2025-07-07 10:06:01', '2025-07-07 11:09:34', 'UserController', '/users/create', 'get', 'create'),
(9, 'post-tambah-user', 'web', '2025-07-07 10:06:01', '2025-07-07 11:09:56', 'UserController', '/users', 'post', 'store'),
(10, 'view-edit-user', 'web', '2025-07-07 10:06:01', '2025-07-07 11:10:24', 'UserController', '/users/{user}/edit', 'get', 'edit'),
(11, 'post-update-user', 'web', '2025-07-07 10:06:01', '2025-07-07 11:11:05', 'UserController', '/users/{user}', 'put', 'update'),
(12, 'hapus-user', 'web', '2025-07-07 10:06:01', '2025-07-07 10:06:01', 'UserController', '/users/{user}', 'delete', 'destroy'),
(14, 'update-status-akun', 'web', '2026-05-27 12:54:06', '2026-05-27 12:54:06', 'UserController', '/users/toggle/{user}', 'post', 'toggleStatus'),
(15, 'user-update-role', 'web', '2026-05-27 13:01:19', '2026-05-27 13:01:19', 'UserController', '/users/{user}/update-role', 'post', 'updateRole'),
(16, 'categories', 'web', '2026-05-27 15:31:35', '2026-05-27 15:31:35', 'CategoriController', '/categories', 'get', 'index'),
(17, 'categories-add', 'web', '2026-05-27 15:32:24', '2026-05-27 16:09:05', 'CategoriController', '/categories', 'post', 'store'),
(18, 'categories-edit', 'web', '2026-05-27 15:33:24', '2026-05-27 15:33:24', 'CategoriController', '/categories/{category}', 'put', 'update'),
(19, 'categories-hapus', 'web', '2026-05-27 15:34:06', '2026-05-27 15:34:06', 'CategoriController', '/categories/{category}', 'delete', 'destroy'),
(20, 'bookings', 'web', '2026-05-27 15:35:14', '2026-05-27 15:35:14', 'BookingController', '/bookings', 'get', 'index'),
(21, 'bookings-edit', 'web', '2026-05-27 15:36:22', '2026-05-27 15:36:22', 'BookingController', '/bookings/{booking}', 'put', 'update'),
(22, 'bookings-hapus', 'web', '2026-05-27 15:37:10', '2026-05-27 15:37:10', 'BookingController', '/bookings/{booking}', 'delete', 'destroy'),
(23, 'certificates', 'web', '2026-05-27 15:37:48', '2026-05-27 15:37:48', 'CertificateController', '/certificates', 'get', 'index'),
(24, 'certificates-add', 'web', '2026-05-27 15:38:38', '2026-05-27 15:38:38', 'CertificateController', '/certificates', 'post', 'store'),
(25, 'certificates-hapus', 'web', '2026-05-27 15:39:16', '2026-05-27 15:39:16', 'CertificateController', '/certificates/{certificate}', 'delete', 'destroy'),
(26, 'schedules', 'web', '2026-05-27 15:40:13', '2026-05-27 15:40:13', 'ClassScheduleController', '/schedules', 'get', 'index'),
(27, 'schedules-add', 'web', '2026-05-27 15:40:59', '2026-05-27 15:40:59', 'ClassScheduleController', '/schedules', 'post', 'store'),
(28, 'schedules-edit', 'web', '2026-05-27 15:41:42', '2026-05-27 15:41:42', 'ClassScheduleController', '/schedules/{schedule}', 'put', 'update'),
(29, 'schedules-hapus', 'web', '2026-05-27 15:42:29', '2026-05-27 15:42:29', 'ClassScheduleController', '/schedules/{schedule}', 'delete', 'destroy'),
(30, 'courses', 'web', '2026-05-27 15:43:41', '2026-05-27 15:43:41', 'CourseController', '/courses', 'get', 'index'),
(31, 'courses-add', 'web', '2026-05-27 15:44:51', '2026-05-27 15:44:51', 'CourseController', '/courses', 'post', 'store'),
(32, 'courses-edit', 'web', '2026-05-27 15:45:45', '2026-05-27 15:45:45', 'CourseController', '/courses/{course}', 'put', 'update'),
(33, 'courses-hapus', 'web', '2026-05-27 15:46:27', '2026-05-27 15:46:27', 'CourseController', '/courses/{course}', 'delete', 'destroy'),
(34, 'materials', 'web', '2026-05-27 15:47:42', '2026-05-27 15:47:42', 'CourseMaterialController', '/materials', 'get', 'index'),
(35, 'materials-add', 'web', '2026-05-27 15:48:22', '2026-05-27 15:48:22', 'CourseMaterialController', '/materials', 'post', 'store'),
(36, 'materials-edit', 'web', '2026-05-27 15:49:12', '2026-05-27 15:49:12', 'CourseMaterialController', '/materials/{material}', 'put', 'update'),
(37, 'materials-hapus', 'web', '2026-05-27 15:52:38', '2026-05-27 15:52:38', 'CourseMaterialController', '/materials/{material}', 'delete', 'destroy'),
(38, 'videos', 'web', '2026-05-27 15:54:18', '2026-05-27 15:54:18', 'CourseVideoController', '/videos', 'get', 'index'),
(39, 'videos-add', 'web', '2026-05-27 15:54:58', '2026-05-27 15:56:41', 'CourseVideoController', '/videos', 'post', 'store'),
(40, 'videos-edit', 'web', '2026-05-27 15:56:21', '2026-05-27 15:57:03', 'CourseVideoController', '/videos/{video}', 'put', 'update'),
(41, 'videos-hapus', 'web', '2026-05-27 15:57:54', '2026-05-27 15:57:54', 'CourseVideoController', '/videos/{video}', 'delete', 'destroy'),
(42, 'course-students', 'web', '2026-05-27 16:01:39', '2026-05-27 16:01:39', 'CourseStudentController', '/course-students', 'get', 'index'),
(43, 'course-students-add', 'web', '2026-05-27 16:02:23', '2026-05-27 16:02:23', 'CourseStudentController', '/course-students', 'post', 'store'),
(44, 'course-students-edit', 'web', '2026-05-27 16:03:22', '2026-05-27 16:03:22', 'CourseStudentController', '/course-students/{courseStudent}', 'post', 'update'),
(45, 'course-students-hapus', 'web', '2026-05-27 16:04:43', '2026-05-27 16:04:43', 'CourseStudentController', '/course-students/{courseStudent}', 'delete', 'destroy'),
(46, 'payments', 'web', '2026-05-27 16:06:42', '2026-05-27 16:06:42', 'PaymentController', '/payments', 'get', 'index'),
(47, 'payments-approve', 'web', '2026-05-27 16:07:42', '2026-05-27 16:09:23', 'PaymentController', '/payments/{payment}/approve', 'patch', 'approve'),
(48, 'payments-reject', 'web', '2026-05-27 16:08:40', '2026-05-27 16:08:40', 'PaymentController', '/payments/{payment}/reject', 'patch', 'reject'),
(49, 'reviews', 'web', '2026-05-27 16:10:33', '2026-05-27 16:10:33', 'ReviewController', '/reviews', 'get', 'index'),
(50, 'review-hapus', 'web', '2026-05-27 16:11:40', '2026-05-27 16:11:40', 'ReviewController', '/reviews/{review}', 'delete', 'destroy'),
(51, 'earnings', 'web', '2026-05-27 16:12:32', '2026-05-27 16:12:32', 'TeacherEarningController', '/earnings', 'get', 'index'),
(52, 'earnings-edit-status', 'web', '2026-05-27 16:13:54', '2026-06-02 23:38:38', 'TeacherEarningController', '/earnings/{id}/status', 'patch', 'updateStatus'),
(53, 'teachers', 'web', '2026-05-27 16:14:51', '2026-05-27 16:14:51', 'TeacherProfileController', '/teachers', 'get', 'index'),
(54, 'teachers-verify', 'web', '2026-05-27 16:16:15', '2026-06-12 07:38:59', 'TeacherProfileController', '/teachers/{profile}/verify', 'put', 'verify'),
(55, 'teachers-add', 'web', '2026-05-28 14:42:05', '2026-05-28 14:42:05', 'TeacherProfileController', '/teachers', 'post', 'store'),
(56, 'teachers-edit', 'web', '2026-05-28 14:49:01', '2026-06-12 06:50:18', 'TeacherProfileController', '/teachers/{profile?}', 'put', 'update'),
(57, 'teachers-hapus', 'web', '2026-05-28 14:50:07', '2026-05-28 14:50:07', 'TeacherProfileController', '/teachers/{profile}', 'delete', 'destroy'),
(58, 'teachers-by-id', 'web', '2026-05-28 14:52:40', '2026-05-28 14:52:40', 'TeacherProfileController', '/teachers/{profile}', 'get', 'show'),
(59, 'tampil-kursus', 'web', '2026-05-29 11:39:45', '2026-05-29 11:40:12', 'StudentCourseController', '/tampil-kursus', 'get', 'index'),
(60, 'siswa-booking', 'web', '2026-05-29 12:34:19', '2026-05-29 12:34:19', 'BookingController', '/bookings', 'post', 'store'),
(61, 'booking-form', 'web', '2026-05-29 12:37:22', '2026-05-29 12:37:22', 'BookingController', '/bookings/create', 'get', 'create'),
(62, 'history-bookings', 'web', '2026-05-29 14:29:47', '2026-05-29 14:29:47', 'BookingController', '/history-bookings', 'get', 'showHistory'),
(63, 'my-courses', 'web', '2026-05-29 15:24:48', '2026-05-29 15:24:48', 'StudentCourseController', '/my-courses', 'get', 'myCourse'),
(64, 'form-reviews', 'web', '2026-05-29 15:26:58', '2026-05-29 15:29:08', 'ReviewController', '/student/courses/{course_id}/review', 'get', 'create'),
(65, 'store-reviews', 'web', '2026-05-29 15:28:44', '2026-05-29 15:28:44', 'ReviewController', '/student/reviews', 'post', 'store'),
(66, 'ruang-belajar', 'web', '2026-05-29 16:13:56', '2026-05-29 16:13:56', 'StudentCourseController', 'student/courses/{course_id}/learn', 'get', 'roomLearn'),
(67, 'admin-dashboard', 'web', '2026-05-31 11:24:08', '2026-05-31 11:24:08', 'DashboardController', '/admin-dashboard', 'get', 'dashboardAdmin'),
(68, 'guru-dashboard', 'web', '2026-05-31 11:24:55', '2026-05-31 11:24:55', 'DashboardController', '/guru-dashboard', 'get', 'dashboardGuru'),
(69, 'siswa-dashboard', 'web', '2026-05-31 11:25:31', '2026-05-31 11:25:31', 'DashboardController', '/siswa-dashboard', 'get', 'dashboardSiswa'),
(70, 'form-payment-class', 'web', '2026-06-02 14:00:21', '2026-06-02 14:00:21', 'PaymentController', '/payments-class/{transaction_code}', 'get', 'showPaymentForm'),
(71, 'simpan-payment-class', 'web', '2026-06-02 14:01:07', '2026-06-02 14:01:07', 'PaymentController', '/payments-class/{transaction_code}', 'post', 'storeStudentPayment'),
(72, 'company-accounts', 'web', '2026-06-06 16:54:25', '2026-06-06 16:54:25', 'CompanyAccountController', '/company-accounts', 'get', 'index'),
(73, 'add-company-accounts', 'web', '2026-06-06 16:55:23', '2026-06-08 17:18:17', 'CompanyAccountController', '/company-accounts', 'post', 'store'),
(74, 'edit-company-accounts', 'web', '2026-06-06 16:56:17', '2026-06-06 16:58:07', 'CompanyAccountController', '/company-accounts/{account}', 'put', 'update'),
(75, 'hapus-company-accounts', 'web', '2026-06-06 16:57:02', '2026-06-06 16:58:32', 'CompanyAccountController', '/company-accounts/{account}', 'delete', 'destroy'),
(76, 'siswa-biodata-form', 'web', '2026-06-08 16:06:44', '2026-06-08 16:06:44', 'StudentBiodataController', '/biodata', 'get', 'siswaForm'),
(77, 'siswa-biodata-store', 'web', '2026-06-08 16:10:51', '2026-06-08 16:10:51', 'StudentBiodataController', '/biodata', 'post', 'store'),
(78, 'biodata-siswa', 'web', '2026-06-08 18:12:11', '2026-06-08 18:12:11', 'StudentBiodataController', '/student-biodata', 'get', 'index'),
(79, 'biodata-siswa-hapus', 'web', '2026-06-08 18:13:12', '2026-06-08 18:13:12', 'StudentBiodataController', '/student-biodata/{id}', 'delete', 'destroy'),
(80, 'course-progress-toggle', 'web', '2026-06-10 13:56:29', '2026-06-10 13:56:29', 'CourseProgressController', '/course-progress/toggle', 'post', 'toggleProgress'),
(81, 'guru-schedules-create', 'web', '2026-06-10 15:24:27', '2026-06-10 15:24:27', 'GuruScheduleController', '/guru/schedules/create', 'get', 'create'),
(82, 'guru-schedules-store', 'web', '2026-06-10 15:25:20', '2026-06-10 15:25:20', 'GuruScheduleController', '/guru/schedules/store', 'post', 'store'),
(83, 'store-quiz', 'web', '2026-06-10 19:24:53', '2026-06-10 19:34:02', 'QuizManagementController', '/quiz/store', 'post', 'storeQuiz'),
(84, 'build-quiz', 'web', '2026-06-10 19:25:56', '2026-06-10 19:32:27', 'QuizManagementController', '/quiz/{quizId}/build', 'get', 'buildQuiz'),
(85, 'store-question', 'web', '2026-06-10 19:27:24', '2026-06-10 19:32:06', 'QuizManagementController', '/quiz/{quizId}/questions', 'post', 'storeQuestion'),
(86, 'review-students', 'web', '2026-06-10 19:28:43', '2026-06-10 19:34:43', 'QuizManagementController', '/quiz/{quizId}/review', 'get', 'reviewStudents'),
(87, 'review-single-student', 'web', '2026-06-10 19:29:39', '2026-06-10 19:35:37', 'QuizManagementController', '/quiz/{quizId}/review/{studentId}', 'get', 'reviewSingleStudent'),
(88, 'submit-grade', 'web', '2026-06-10 19:31:04', '2026-06-10 19:31:04', 'QuizManagementController', '/quiz/answer/{answerId}/grade', 'post', 'submitGrade'),
(89, 'show-detail-material', 'web', '2026-06-10 19:49:50', '2026-06-10 19:49:50', 'CourseMaterialController', '/materials/{id}', 'get', 'show'),
(90, 'quiz-introduce', 'web', '2026-06-10 21:49:28', '2026-06-10 21:49:28', 'StudentQuizController', '/materials/{materialId}/quiz', 'get', 'introduce'),
(91, 'quiz-take', 'web', '2026-06-10 21:50:34', '2026-06-10 21:50:34', 'StudentQuizController', '/quiz/{quizId}/take', 'get', 'takeQuiz'),
(92, 'quiz-submit', 'web', '2026-06-10 21:51:30', '2026-06-10 21:51:30', 'StudentQuizController', '/quiz/{quizId}/submit', 'post', 'submitQuiz'),
(93, 'quiz-history', 'web', '2026-06-10 22:25:08', '2026-06-10 22:25:08', 'StudentQuizController', '/quiz/history', 'get', 'history'),
(94, 'student-verify', 'web', '2026-06-15 06:19:00', '2026-06-15 06:19:00', 'StudentBiodataController', '/student-biodata/{id}/verify', 'patch', 'verify');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text DEFAULT NULL,
  `type` enum('multiple_choice','essay','pdf_attachment') NOT NULL,
  `pdf_file_path` varchar(255) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `type`, `pdf_file_path`, `points`, `created_at`, `updated_at`) VALUES
(1, 1, 'The manager decided to postpone the meeting until next Monday due to the unexpected power outage.\r\nWhat is the synonym of the bold word?', 'multiple_choice', NULL, 20, '2026-06-10 21:04:57', '2026-06-10 21:04:57'),
(2, 1, 'She has a very vivid imagination; she can write incredibly detailed fantasy stories.\r\nWhat does the word \"vivid\" mean in this context?', 'multiple_choice', NULL, 20, '2026-06-10 21:06:33', '2026-06-10 21:06:33'),
(3, 1, 'The instructions provided by the teacher were so ambiguous that half of the class ended up doing the wrong assignment.\r\nWhat is the antonym of the word \"ambiguous\"?', 'multiple_choice', NULL, 20, '2026-06-10 21:08:25', '2026-06-10 21:08:25'),
(4, 1, 'Despite facing many obstacles during the research project, the team remained persistent and finally found a solution.\r\nThe word \"persistent\" means...', 'multiple_choice', NULL, 20, '2026-06-10 21:09:22', '2026-06-10 21:09:22'),
(5, 1, 'The old wooden bridge was quite fragile, so we had to cross it very carefully.\r\nWhich of the following words has the opposite meaning to \"fragile\"?', 'multiple_choice', NULL, 20, '2026-06-10 21:10:20', '2026-06-10 21:10:20'),
(6, 2, 'tell us about your daily activities', 'essay', NULL, 100, '2026-06-10 21:21:37', '2026-06-10 21:21:37'),
(7, 3, 'Please find and create at least 50 pronouns and submit them within 50 minutes.', 'pdf_attachment', 'quiz_questions_pdf/pPWnwBfMrHul46HoHgK1lmcqKZpocIxWM6ePkT30.pdf', 10, '2026-06-10 21:26:44', '2026-06-10 21:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cancel', 0, '2026-06-10 21:04:57', '2026-06-10 21:04:57'),
(2, 1, 'Advance', 1, '2026-06-10 21:04:57', '2026-06-10 21:04:57'),
(3, 1, 'Delay', 0, '2026-06-10 21:04:57', '2026-06-10 21:04:57'),
(4, 1, 'Resume', 0, '2026-06-10 21:04:57', '2026-06-10 21:04:57'),
(5, 2, 'Clear and detailed', 0, '2026-06-10 21:06:33', '2026-06-10 21:06:33'),
(6, 2, 'Weak and boring', 1, '2026-06-10 21:06:33', '2026-06-10 21:06:33'),
(7, 2, 'Old and ancient', 0, '2026-06-10 21:06:33', '2026-06-10 21:06:33'),
(8, 2, 'Logical and mathematical', 0, '2026-06-10 21:06:33', '2026-06-10 21:06:33'),
(9, 3, 'Unclear', 0, '2026-06-10 21:08:25', '2026-06-10 21:08:25'),
(10, 3, 'Clear', 1, '2026-06-10 21:08:25', '2026-06-10 21:08:25'),
(11, 3, 'Complicated', 0, '2026-06-10 21:08:25', '2026-06-10 21:08:25'),
(12, 3, 'Hidden', 0, '2026-06-10 21:08:25', '2026-06-10 21:08:25'),
(13, 4, 'Giving up easily', 0, '2026-06-10 21:09:22', '2026-06-10 21:09:22'),
(14, 4, 'Working slowly', 0, '2026-06-10 21:09:22', '2026-06-10 21:09:22'),
(15, 4, 'Refusing to give up', 0, '2026-06-10 21:09:22', '2026-06-10 21:09:22'),
(16, 4, 'Getting angry quickly', 1, '2026-06-10 21:09:22', '2026-06-10 21:09:22'),
(17, 5, 'Delicate', 0, '2026-06-10 21:10:20', '2026-06-10 21:10:20'),
(18, 5, 'Weak', 1, '2026-06-10 21:10:20', '2026-06-10 21:10:20'),
(19, 5, 'Sturdy', 0, '2026-06-10 21:10:20', '2026-06-10 21:10:20'),
(20, 5, 'Broken', 0, '2026-06-10 21:10:20', '2026-06-10 21:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `material_id`, `title`, `description`, `duration_minutes`, `created_at`, `updated_at`) VALUES
(1, 1, 'pretest vocabullary', 'srf sdfsdfsdfsdf sdfsd fsdfsdf sdfsdfsdfsd', 30, '2026-06-10 20:34:37', '2026-06-10 20:34:37'),
(2, 2, 'Daily Activity', 'student test writing daily activity', 30, '2026-06-10 21:17:18', '2026-06-10 21:17:18'),
(3, 3, 'Personal Pronoun', 'student search and write 50 personal prnoun', 50, '2026-06-10 21:24:55', '2026-06-10 21:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `student_id`, `teacher_id`, `course_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(4, 3, 2, 1, 4, 'hg hgj ggjhg hjgjgj jg jgjgj', '2026-05-29 17:02:37', '2026-05-29 17:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-07-05 08:19:05', '2025-07-05 08:19:05'),
(2, 'guru', 'web', '2025-07-05 08:19:14', '2025-07-05 08:19:14'),
(3, 'siswa', 'web', '2025-07-07 08:07:50', '2025-07-07 09:32:16');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(57, 1),
(58, 1),
(67, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(78, 1),
(79, 1),
(94, 1),
(7, 2),
(16, 2),
(23, 2),
(24, 2),
(26, 2),
(27, 2),
(28, 2),
(30, 2),
(34, 2),
(35, 2),
(36, 2),
(38, 2),
(39, 2),
(40, 2),
(51, 2),
(52, 2),
(53, 2),
(55, 2),
(56, 2),
(68, 2),
(81, 2),
(82, 2),
(83, 2),
(84, 2),
(85, 2),
(86, 2),
(87, 2),
(88, 2),
(89, 2),
(7, 3),
(10, 3),
(11, 3),
(59, 3),
(60, 3),
(61, 3),
(62, 3),
(63, 3),
(64, 3),
(65, 3),
(66, 3),
(69, 3),
(70, 3),
(71, 3),
(76, 3),
(77, 3),
(80, 3),
(90, 3),
(91, 3),
(92, 3),
(93, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('uaWeZ1UfFlWNFZhD8oeaPp1Ph8ZRaoDnBV5F3BiA', 1, '172.19.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTW1kUnZQUVJSSDZaVVFFMnBvdlVnQXM2R3BCb3BveXFrVVBFOG95aSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9jb3Vyc2VzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1781557239),
('zHh2laVp5Zft0mR3xw9PVpakacNPk63o5A9C90kd', 3, '172.19.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ0J5cEhpS205OWZxV052NGFnbkhKdWdmVjdQZzVPdTV4YVhPeGU0NCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9teS1jb3Vyc2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1781557589);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score_achieved` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `user_id`, `question_id`, `answer_text`, `is_correct`, `score_achieved`, `created_at`, `updated_at`) VALUES
(6, 3, 3, '9', 0, 0, '2026-06-10 22:19:25', '2026-06-10 22:19:25'),
(7, 3, 1, '2', 1, 20, '2026-06-10 22:19:25', '2026-06-10 22:19:25'),
(8, 3, 4, '15', 0, 0, '2026-06-10 22:19:25', '2026-06-10 22:19:25'),
(9, 3, 5, '20', 0, 0, '2026-06-10 22:19:25', '2026-06-10 22:19:25'),
(10, 3, 2, '7', 0, 0, '2026-06-10 22:19:25', '2026-06-10 22:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `student_biodatas`
--

CREATE TABLE `student_biodatas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_biodatas`
--

INSERT INTO `student_biodatas` (`id`, `user_id`, `nisn`, `institution_name`, `birth_date`, `gender`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 20, '1234567890', 'SMK Pariwisata', '2008-06-01', 'L', 'jalan basuki rahmat 05 praya', 'approved', '2026-06-08 16:15:36', '2026-06-15 06:26:30'),
(3, 23, '1234567888', 'SMK Kuripan', '2005-06-16', 'L', 'jalan raden puguh waker puyung', 'approved', '2026-06-08 18:45:12', '2026-06-15 06:26:28'),
(4, 3, '5566778899', 'STMIK Lombok', '2007-05-10', 'L', 'kampung rabitah jalan basuki rahmat 05 praya-mantang', 'approved', '2026-06-10 13:47:29', '2026-06-15 06:56:07'),
(5, 16, '2223334445', 'STMIK Lombok', '2006-06-09', 'L', 'lorem ipsum dolor sit amet', 'approved', '2026-06-10 14:42:45', '2026-06-15 06:26:24'),
(6, 18, '1112223334', 'STMIK Lombok', '2004-06-10', 'L', 'sdfsd fsdfsdfsdf sdfsdfsdf', 'approved', '2026-06-10 14:45:55', '2026-06-15 06:25:26'),
(7, 25, '9900887766', 'SMK Kuripan', '2007-06-13', 'L', 'jalan raya kuripan no 112', 'approved', '2026-06-12 17:52:20', '2026-06-15 06:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_earnings`
--

CREATE TABLE `teacher_earnings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `amount_earned` decimal(10,2) NOT NULL,
  `status` enum('unpaid','withdrawn') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_earnings`
--

INSERT INTO `teacher_earnings` (`id`, `teacher_id`, `payment_id`, `amount_earned`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 700000.00, 'withdrawn', '2026-06-11 20:05:49', '2026-06-11 20:05:49'),
(2, 2, 1, 700000.00, 'withdrawn', '2026-06-11 20:09:59', '2026-06-11 20:09:59'),
(3, 2, 4, 700000.00, 'unpaid', '2026-06-11 22:17:35', '2026-06-11 22:17:35'),
(4, 2, 5, 700000.00, 'unpaid', '2026-06-11 22:18:05', '2026-06-11 22:18:05'),
(5, 2, 6, 700000.00, 'unpaid', '2026-06-11 22:18:14', '2026-06-11 22:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profiles`
--

CREATE TABLE `teacher_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `skills_tags` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `cv_file` varchar(255) DEFAULT NULL,
  `average_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_profiles`
--

INSERT INTO `teacher_profiles` (`id`, `user_id`, `title`, `bio`, `skills_tags`, `verification_status`, `cv_file`, `average_rating`, `bank_name`, `bank_account_number`, `bank_account_name`, `created_at`, `updated_at`) VALUES
(1, 2, 'S.Pd.', 'lorem ipsum dolor sit amettrtdrttrtr', '[\"Bahasa Inggris\",\"Bahasa Arab\"]', 'approved', 'cv_teachers/0Qydw8YqXBGy8mvujo17Xdg476m2IkVZuMqfoYvS.pdf', 0.00, 'seabank', '1234567890', 'Budi Santoso', '2026-05-28 14:54:11', '2026-06-13 00:55:12'),
(3, 15, 'S.Pd.', 'guru dua merupakan guru yang multitalent dalam menguasai bahasa', '[\"Bahasa Jepang\",\"Bahasa Inggris\",\"Bahasa China\"]', 'approved', 'cv_teachers/v1DWfkcEpMjeAp0OOCnxD5h1VbQ0IRy0c3UWfIRJ.pdf', 0.00, 'BRI', '1234567890', 'Sabarudin S.Pd.', '2026-06-06 15:55:34', '2026-06-06 15:55:34'),
(8, 24, 'Bahasa Jepang dan Matematika', 'lorem ipsum dolor sit amet', '[\"Bahasa Jepang\",\"Matematika Aljabar\"]', 'pending', 'cv_teachers/jBDzH1elPMRphLzJXJODUW07LDlRy7QWYDKKh6OA.pdf', 0.00, 'BRI', '1234567890', 'Salman', '2026-06-12 17:27:43', '2026-06-12 17:28:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default-avatar.png',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone_number`, `avatar`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Guru Hub', 'admin@gmail.com', NULL, '$2y$12$0mhEJQObQZxGEwdNCMYo3euZzNTk4nKvSyjgAq8tdun8ym1UkxuXi', NULL, 'default-avatar.png', 1, NULL, '2025-07-05 09:02:19', '2026-06-12 20:37:00'),
(2, 'Budi Santoso, S.Pd.', 'guru@gmail.com', NULL, '$2y$12$0yDiXElOLAV4HD.dZHfrw.wPmjd4AbzyelCAPtHmtNeqw1.jjYBKG', NULL, 'default-avatar.png', 1, NULL, '2025-07-05 09:03:33', '2026-06-12 20:37:36'),
(3, 'Bayu Nigroho', 'siswa@gmail.com', NULL, '$2y$12$jfMR8Epsy414IymgfKDZo.tVrbOoOz2VBIyP5AJrgEJG1wilmApYO', '082345678901', 'avatars/DdhGJqbUEH1kLZcldJiS4A8QJMIepNlQWl3hbFkh.png', 1, NULL, '2025-07-07 10:05:54', '2026-06-12 20:36:27'),
(15, 'Siti Rahmawati, M.Pd.', 'gurudua@gmail.com', NULL, '$2y$12$3sVILjvM9jgeLvaSKXfls.kW2c.Mt7e3gk64LDITqbGYS5t4j6BMO', '+6284444444444', 'default-avatar.png', 1, NULL, '2026-05-29 08:34:54', '2026-06-12 20:38:00'),
(16, 'Choirul Shobri', 'siswadua@gmail.com', NULL, '$2y$12$mVq.2YVYCDZj0w1xejYMmORpt42w.gl9PVQ2vkxCtjVCvJEWinU6y', '+6285555555555', 'avatars/QjAg1XzCBtuyQlPHdlkXLdCpPsk9fv5PY0DlN3QO.png', 1, NULL, '2026-05-29 08:36:01', '2026-06-12 20:36:09'),
(18, 'Bilal Zakaria', 'siswatiga@gmail.com', NULL, '$2y$12$bXn/scmM0j6QTP2Yc5roRuFdkFGI/zgPJDDRaq/SBdIG01DL/emRO', '1234567890', 'avatars/rGSmavKWs7CbWXnebvt1eLUBuooYB20UBy11nem9.png', 1, NULL, '2026-06-06 17:53:05', '2026-06-12 20:35:47'),
(20, 'Raka Pratama', 'siswaempat@gmail.com', NULL, '$2y$12$bPu3UUTUpA8XWLY6mXM9Le8lqWz1.ZbW4WV3o2kk7wqNqp9hAY0fK', '081234567893', 'avatars/cPXZXEoCWjuKzHOzcJYxTspe2XN8eWuScfBB7zeM.png', 1, NULL, '2026-06-08 12:18:10', '2026-06-12 20:35:31'),
(23, 'Dimas Permata', 'siswalima@gmail.com', NULL, '$2y$12$cDkOk/OfSQ23/DGcSvxEkeoas.H7there1Rr.7pRljPEp0OO/Ga9m', '081234567895', 'avatars/YpSSL6tyjX9UKpoh1R8CDi7KiUDYnZeBvIGss7PM.png', 1, NULL, '2026-06-08 18:35:12', '2026-06-12 20:35:14'),
(24, 'Salman S.Pd., M.Pd.', 'salman@guru.com', NULL, '$2y$12$IHwsmKK.t4EIb1whTmIG3.0QgFY9d/7t6ftMiX5qXNwPd4db1AebC', '081234567891', 'avatars/4FI6v1gnvNF7dZZGHKrIY01J100jy1523a51LYHk.png', 1, NULL, '2026-06-12 17:26:00', '2026-06-12 17:28:54'),
(25, 'Adingga Fitriyanto', 'siswaenam@gmail.com', NULL, '$2y$12$PK5CCDIzewOymPkhI25PLe3hkRrrPuuCytweDxUVy0JUboDQ06Pwe', '0987654321', 'avatars/yDNJy2NFeGRecvm6RZX8G8MriafV33VYN4Z4HytQ.png', 1, NULL, '2026-06-12 17:50:35', '2026-06-12 20:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `progressable_id` bigint(20) UNSIGNED NOT NULL,
  `progressable_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`id`, `user_id`, `progressable_id`, `progressable_type`, `created_at`, `updated_at`) VALUES
(1, 3, 8, 'App\\Models\\Video', '2026-06-10 14:17:52', '2026-06-10 14:17:52'),
(2, 3, 9, 'App\\Models\\Video', '2026-06-10 14:17:54', '2026-06-10 14:17:54'),
(3, 3, 1, 'App\\Models\\Material', '2026-06-10 14:17:56', '2026-06-10 14:17:56'),
(4, 3, 2, 'App\\Models\\Material', '2026-06-10 14:17:57', '2026-06-10 14:17:57'),
(6, 16, 1, 'App\\Models\\Material', '2026-06-10 14:44:18', '2026-06-10 14:44:18'),
(7, 16, 2, 'App\\Models\\Material', '2026-06-10 14:44:30', '2026-06-10 14:44:30'),
(8, 16, 8, 'App\\Models\\Video', '2026-06-10 14:44:35', '2026-06-10 14:44:35'),
(9, 16, 9, 'App\\Models\\Video', '2026-06-10 14:44:36', '2026-06-10 14:44:36'),
(10, 18, 1, 'App\\Models\\Material', '2026-06-10 14:46:05', '2026-06-10 14:46:05'),
(11, 18, 2, 'App\\Models\\Material', '2026-06-10 14:46:10', '2026-06-10 14:46:10'),
(12, 20, 8, 'App\\Models\\Video', '2026-06-10 14:48:25', '2026-06-10 14:48:25'),
(13, 20, 9, 'App\\Models\\Video', '2026-06-10 14:48:26', '2026-06-10 14:48:26'),
(14, 20, 1, 'App\\Models\\Material', '2026-06-10 14:48:31', '2026-06-10 14:48:31'),
(17, 23, 1, 'App\\Models\\Material', '2026-06-10 14:49:55', '2026-06-10 14:49:55'),
(18, 23, 8, 'App\\Models\\Video', '2026-06-10 14:53:48', '2026-06-10 14:53:48'),
(19, 23, 9, 'App\\Models\\Video', '2026-06-10 14:53:49', '2026-06-10 14:53:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_transaction_code_unique` (`transaction_code`),
  ADD KEY `bookings_student_id_foreign` (`student_id`),
  ADD KEY `bookings_course_id_foreign` (`course_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificates_certificate_code_unique` (`certificate_code`),
  ADD KEY `certificates_student_id_foreign` (`student_id`),
  ADD KEY `certificates_course_id_foreign` (`course_id`);

--
-- Indexes for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_schedules_course_id_foreign` (`course_id`),
  ADD KEY `class_schedules_material_id_foreign` (`material_id`);

--
-- Indexes for table `company_accounts`
--
ALTER TABLE `company_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_teacher_id_foreign` (`teacher_id`),
  ADD KEY `courses_category_id_foreign` (`category_id`);

--
-- Indexes for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_materials_course_id_foreign` (`course_id`);

--
-- Indexes for table `course_students`
--
ALTER TABLE `course_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_students_course_id_foreign` (`course_id`),
  ADD KEY `course_students_student_id_foreign` (`student_id`);

--
-- Indexes for table `course_videos`
--
ALTER TABLE `course_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_videos_course_id_foreign` (`course_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_invoice_number_unique` (`invoice_number`),
  ADD KEY `payments_student_id_foreign` (`student_id`),
  ADD KEY `payments_course_id_foreign` (`course_id`),
  ADD KEY `payments_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_options_question_id_foreign` (`question_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_material_id_foreign` (`material_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_student_id_foreign` (`student_id`),
  ADD KEY `reviews_teacher_id_foreign` (`teacher_id`),
  ADD KEY `reviews_course_id_foreign` (`course_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_answers_user_id_foreign` (`user_id`),
  ADD KEY `student_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `student_biodatas`
--
ALTER TABLE `student_biodatas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_biodatas_nisn_unique` (`nisn`),
  ADD KEY `student_biodatas_user_id_foreign` (`user_id`);

--
-- Indexes for table `teacher_earnings`
--
ALTER TABLE `teacher_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_earnings_teacher_id_foreign` (`teacher_id`),
  ADD KEY `teacher_earnings_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_progress_unique` (`user_id`,`progressable_id`,`progressable_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `class_schedules`
--
ALTER TABLE `class_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `company_accounts`
--
ALTER TABLE `company_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course_materials`
--
ALTER TABLE `course_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `course_students`
--
ALTER TABLE `course_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course_videos`
--
ALTER TABLE `course_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_biodatas`
--
ALTER TABLE `student_biodatas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teacher_earnings`
--
ALTER TABLE `teacher_earnings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD CONSTRAINT `class_schedules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_schedules_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `course_materials` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD CONSTRAINT `course_materials_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_students`
--
ALTER TABLE `course_students`
  ADD CONSTRAINT `course_students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_videos`
--
ALTER TABLE `course_videos`
  ADD CONSTRAINT `course_videos_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `course_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_biodatas`
--
ALTER TABLE `student_biodatas`
  ADD CONSTRAINT `student_biodatas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_earnings`
--
ALTER TABLE `teacher_earnings`
  ADD CONSTRAINT `teacher_earnings_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_earnings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD CONSTRAINT `teacher_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
