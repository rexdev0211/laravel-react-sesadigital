-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2021 at 06:43 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `estate`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `admin_type` enum('superadmin','admin','estate_manager','company') COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_id` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_balance` double(65,6) NOT NULL DEFAULT 0.000000,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `created_by`, `admin_type`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `phone`, `photo`, `address`, `route_id`, `wallet_balance`, `device_token`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'superadmin', 'Sangeeta', NULL, 'admin@gmail.com', NULL, '$2y$10$w.MXL7UKeJl04x4CMwrlQuzlO2k5XWRfyeYWQzHl4NPlC0UbW4QEO', NULL, NULL, NULL, NULL, NULL, 0.000000, NULL, '', 'active', '2020-08-23 22:57:37', '2020-08-23 22:57:37'),
(2, 1, 'company', 'Test', 'Company', 'company@gmail.com', NULL, '$2y$10$w.MXL7UKeJl04x4CMwrlQuzlO2k5XWRfyeYWQzHl4NPlC0UbW4QEO', NULL, '123456789', NULL, 'Jaipur, India', '1,23,25,28,42,43,44,45,46,47,48,49,50,51,52,53,67,68', 0.000000, NULL, 'test', 'active', '2020-10-03 04:31:25', '2020-10-04 14:12:55'),
(3, 1, 'estate_manager', 'System', 'manager', 'manager@gmail.com', NULL, '$2y$10$9vdgR3ZMY/jqZT9hMpv1xenH7LjMQy.XctQQL3nuMDeQ0nQxrQSo6', NULL, '4235453425', NULL, 'dfdjfhdgj dgfdsfg dsgfjds', NULL, 0.000000, NULL, 'system', 'active', '2020-10-03 13:44:44', '2020-10-25 09:25:32'),
(4, 1, 'admin', 'admin', 'fdfggf', 'admin1@gmail.com', NULL, '$2y$10$w.MXL7UKeJl04x4CMwrlQuzlO2k5XWRfyeYWQzHl4NPlC0UbW4QEO', NULL, '3423334435', NULL, 'wewqewqe', '1,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,67,93,94,95,96,97,98,99,100,101,102,103,104,105', 0.000000, NULL, 'admin', 'active', '2020-10-06 21:51:43', '2020-10-25 08:22:21'),
(5, 1, 'admin', 'dffg', 'fdh', 'gf@fgg.dsd', NULL, '$2y$10$iWsQ0AgTanA0ya2./oYl3u9.9GuT4ALjABhWzcQ3IdIsqdh6oQLyO', NULL, '4454554', NULL, 'erwewtre', NULL, 0.000000, NULL, 'dffg', 'active', '2020-10-25 08:30:50', '2020-10-25 08:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `adverts`
--

CREATE TABLE `adverts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adverts`
--

INSERT INTO `adverts` (`id`, `created_id`, `name`, `start_date`, `end_date`, `photo`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'cxc', '2020-10-01', '2021-06-30', 'adverts/TeV5v8advert.png', 'cxc', 'active', '2020-10-09 23:54:37', '2021-03-27 06:14:13'),
(2, 10, 'cvcv', '2021-02-13', '2021-06-30', 'adverts/36DlUladvert1.png', 'cvcv', 'active', '2021-02-12 22:42:57', '2021-03-27 06:13:55'),
(3, 10, 'cxvxcxbvc', '2021-02-11', '2021-06-30', 'adverts/2cRgPaadvert2.png', 'cxvxcxbvc', 'active', '2021-02-12 22:43:46', '2021-03-27 06:13:42');

-- --------------------------------------------------------

--
-- Table structure for table `artisans`
--

CREATE TABLE `artisans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `write_up` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artisan_categories`
--

CREATE TABLE `artisan_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artisan_groups`
--

CREATE TABLE `artisan_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artisan_id` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'multiple artisan ids with comma separated',
  `estate_id` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artisan_linked_categories`
--

CREATE TABLE `artisan_linked_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `artisan_id` int(11) NOT NULL,
  `artisan_category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artisan_ratings`
--

CREATE TABLE `artisan_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `artisan_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 0,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artisan_signins`
--

CREATE TABLE `artisan_signins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `artisan_id` int(11) DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_type` enum('single','multiple','free') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `access_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_level_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `to_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','processing','sent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estates`
--

CREATE TABLE `estates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL COMMENT 'user table id whose type is estate manager',
  `company_id` int(11) DEFAULT NULL COMMENT 'user table id whose type is company',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_balance` double(65,6) NOT NULL DEFAULT 0.000000,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `near_by_location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_signout_required` tinyint(4) NOT NULL DEFAULT 0,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estates`
--

INSERT INTO `estates` (`id`, `created_id`, `manager_id`, `company_id`, `name`, `wallet_balance`, `phone`, `address`, `near_by_location`, `photo`, `description`, `is_signout_required`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 3, 'Fidtec', 0.000000, '73745', '764784', NULL, NULL, NULL, 0, 'fidtec', 'active', '2021-03-31 23:51:04', '2021-03-31 23:51:04'),
(2, 1, 4, NULL, 'gfhdgd', 0.000000, '54345', 'hgjh', NULL, NULL, NULL, 0, 'gfhdgd', 'active', '2021-03-31 23:52:43', '2021-03-31 23:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date NOT NULL,
  `end_time` time NOT NULL,
  `access_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `type` enum('vegan','non-vegan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_share` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `good_buy_items`
--

CREATE TABLE `good_buy_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `good_id` int(11) DEFAULT NULL,
  `good_item_id` int(11) DEFAULT NULL,
  `number_of_item` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `total_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `status` enum('request','received','picked_up','delivered','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'request',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `good_items`
--

CREATE TABLE `good_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `good_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_item` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'multiple artisan ids with comma separated',
  `group_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_type_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `created_id`, `estate_id`, `name`, `user_id`, `group_type`, `group_type_value`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Test', NULL, 'gender', 'male', 'test', 'active', '2021-04-01 01:24:11', '2021-04-01 01:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `estate_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `triggerType` enum('send','schedule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'send',
  `channelType` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduleDate` date DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','active','processing','sent') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `created_id`, `estate_id`, `name`, `triggerType`, `channelType`, `scheduleDate`, `subject`, `description`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'ffg', 'schedule', 'email', '2021-04-02', 'fg', 'gfhghg', 'ffg', 'pending', '2021-04-01 02:30:03', '2021-04-01 02:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `next_of_kins`
--

CREATE TABLE `next_of_kins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `relationship_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `house_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other','unspecified') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
  `marital_status` enum('married','single','divorced','widowed','unspecified','other') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
  `assign_panic_alert` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>no,1=>yes',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_id` int(11) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `notifi_type` enum('panic','walk-in','sign-in','system_message','contact') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_category` tinyint(4) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_json` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_seen` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>no,1=>yes',
  `is_read` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>no,1=>yes',
  `is_show_guard` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `from_id`, `to_id`, `estate_id`, `notifi_type`, `contact_category`, `title`, `message`, `photo`, `location`, `location_json`, `is_seen`, `is_read`, `is_show_guard`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 2, 'contact', 1, 'ghfghdfg', 'fjhjghj', 'notifications/6gzVb3content-management.png', NULL, NULL, 1, 1, 1, '2021-04-03 23:28:40', '2021-04-04 04:17:34'),
(2, 5, NULL, 2, 'contact', 1, 'fgfdgf', 'fgfhfghfg', 'notifications/mY44G6content-management.png', NULL, NULL, 1, 1, 1, '2021-04-03 23:31:35', '2021-04-04 04:17:34'),
(3, 5, NULL, 2, 'contact', 1, 'uujghj', 'fggfhh', 'notifications/FAuYjrcontent-management.png', NULL, NULL, 1, 1, 1, '2021-04-03 23:37:11', '2021-04-04 04:17:34'),
(4, 5, NULL, 2, 'panic', NULL, 'Panic Alert', 'A panic alert has been initiated by Tset Test', NULL, '', '0', 1, 1, 0, '2021-04-04 02:15:09', '2021-04-04 04:17:34'),
(5, 5, NULL, 2, 'panic', NULL, 'Panic Alert', 'A panic alert has been initiated by Tset Test', NULL, '', '0', 1, 0, 0, '2021-04-04 02:16:35', '2021-04-04 04:17:34'),
(6, 5, NULL, 2, 'panic', NULL, 'Panic Alert', 'A panic alert has been initiated by Tset Test', NULL, '', '0', 1, 0, 0, '2021-04-04 02:17:31', '2021-04-04 04:17:34'),
(7, 5, NULL, 2, 'contact', 2, 'testing', 'testing..............................', 'notifications/odpM7Aclipboard.png,notifications/cOzqWZvideo-camera.png,notifications/psuvDQWhatsApp Image 2021-03-29 at 4.19.52 PM.jpeg', NULL, NULL, 1, 1, 0, '2021-04-04 04:16:44', '2021-04-04 04:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_type` enum('monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yearly',
  `price` double(20,2) NOT NULL DEFAULT 0.00,
  `estate_service_charge` double(20,2) NOT NULL DEFAULT 0.00,
  `commission_fee` double(20,2) NOT NULL DEFAULT 0.00,
  `total_price` double(20,2) NOT NULL DEFAULT 0.00,
  `show_total_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `can_add_user` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `created_id`, `name`, `package_type`, `price`, `estate_service_charge`, `commission_fee`, `total_price`, `show_total_price`, `can_add_user`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Test Monthly Package', 'monthly', 900.00, 0.00, 100.00, 1000.00, 'N1k', 3, 'test', 'active', '2021-01-29 10:50:47', '2021-01-29 10:51:39'),
(2, 1, 'Test yearly Package', 'yearly', 1900.00, 0.00, 100.00, 2000.00, 'N2k', 3, 'test', 'active', '2021-01-29 10:50:47', '2021-01-29 10:51:39'),
(3, 1, 'Test 2 For Monthly Pay', 'monthly', 2900.00, 0.00, 100.00, 3000.00, 'N3K', 5, 'test-2-for-monthly-pay', 'active', '2021-01-31 11:07:18', '2021-01-31 11:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `page_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `page_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE latin1_general_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `created_id`, `estate_id`, `page_title`, `page_description`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Test 1', '<p>Test 1</p>', 'test-1', 'active', '2021-04-11 23:15:57', '2021-04-11 23:15:57'),
(2, 1, 2, 'Test1', '<p>Test 1</p>', 'test1', 'active', '2021-04-11 23:16:09', '2021-04-11 23:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pay_type` enum('wallet_credit','subscription','product_pay','power_product_pay') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_gateway` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `package_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_price` double(20,2) NOT NULL DEFAULT 0.00,
  `package_estate_service_charge` double(20,2) DEFAULT 0.00,
  `package_commission_fee` double(20,2) NOT NULL DEFAULT 0.00,
  `package_total_price` double(20,2) NOT NULL DEFAULT 0.00,
  `package_can_add_user` int(11) DEFAULT NULL,
  `package_valid_till` date DEFAULT NULL,
  `purchased_product_id` int(11) DEFAULT NULL,
  `power_product_id` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `created_id`, `user_id`, `pay_type`, `pay_gateway`, `estate_id`, `transaction_id`, `amount`, `payment_reference`, `description`, `package_id`, `package_name`, `package_price`, `package_estate_service_charge`, `package_commission_fee`, `package_total_price`, `package_can_add_user`, `package_valid_till`, `purchased_product_id`, `power_product_id`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 'wallet_credit', 'monnify', 2, 'MNFY|47|20210403155628|001488', 20000.00, '688229106', 'Credit', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16174618025', 'Successful', '2021-04-03 09:26:42', '2021-04-03 09:26:42'),
(2, 5, 5, 'wallet_credit', 'monnify', 2, 'MNFY|33|20210403155921|001482', 100.00, '993599704', 'testing', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16174619725', 'Successful', '2021-04-03 09:29:32', '2021-04-03 09:29:32'),
(3, 5, 5, 'product_pay', 'wallet', 2, 'PROD|1617462011|000001', 800.00, NULL, 'Test12 product purchased.', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 1, NULL, '16174620115', 'Successful', '2021-04-03 09:30:11', '2021-04-03 09:30:11'),
(4, 5, 5, 'product_pay', 'wallet', 2, 'PROD|1617462497|000002', 800.00, NULL, 'Test12 product purchased.', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 2, NULL, '16174624975', 'Successful', '2021-04-03 09:38:17', '2021-04-03 09:38:17'),
(5, 5, 5, 'wallet_credit', 'monnify', 2, 'MNFY|47|20210404091048|001503', 500.00, '282691988', 'Testing', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175238635', 'Successful', '2021-04-04 02:41:03', '2021-04-04 02:41:03'),
(6, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404092801|001505', 3000.00, '588177119', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175248955', 'Successful', '2021-04-04 02:58:15', '2021-04-04 02:58:15'),
(7, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404092947|001506', 3000.00, '150130250', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175250015', 'Successful', '2021-04-04 03:00:01', '2021-04-04 03:00:01'),
(8, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404094306|001507', 2000.00, '917099230', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175257995', 'Successful', '2021-04-04 03:13:19', '2021-04-04 03:13:19'),
(9, 5, 5, 'subscription', 'monnify', 2, 'MNFY|33|20210404094536|001495', 2000.00, '889694139', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175259505', 'Successful', '2021-04-04 03:15:50', '2021-04-04 03:15:50'),
(10, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404094603|001508', 2000.00, '561026631', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175259765', 'Successful', '2021-04-04 03:16:16', '2021-04-04 03:16:16'),
(11, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404094644|001509', 2000.00, '617983578', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175260175', 'Successful', '2021-04-04 03:16:57', '2021-04-04 03:16:57'),
(12, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404111522|001511', 1000.00, '505978798', 'Purchase subscription package', NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175313365', 'Successful', '2021-04-04 04:45:36', '2021-04-04 04:45:36'),
(13, 5, 5, 'subscription', 'monnify', 2, 'MNFY|47|20210404114258|001513', 2000.00, '624184684', 'Purchase subscription package', 2, 'Test yearly Package', 1900.00, 0.00, 100.00, 2000.00, 3, '2021-05-04', NULL, NULL, '16175329905', 'Successful', '2021-04-04 05:13:10', '2021-04-04 05:13:10'),
(14, 5, 5, 'wallet_credit', 'monnify', 2, 'MNFY|47|20210404144523|001517', 100.00, '633655235', NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '16175439375', 'Successful', '2021-04-04 08:15:37', '2021-04-04 08:15:37');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` enum('live','sandbox') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sandbox',
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sandbox_api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sandbox_api_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sandbox_contract_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sandbox_payment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `created_id`, `name`, `payment_mode`, `api_key`, `api_secret_key`, `contract_code`, `payment_url`, `sandbox_api_key`, `sandbox_api_secret_key`, `sandbox_contract_code`, `sandbox_payment_url`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'monnify', 'sandbox', NULL, NULL, NULL, NULL, 'MK_TEST_J4H7VHWDUG', 'T4H886T2Y3MDEW2CVSPU6K7H4HQ45CQH', '1972625175', 'https://sandbox.monnify.com', 'monnify', 'active', '2021-01-20 22:17:24', '2021-01-26 11:28:50');

-- --------------------------------------------------------

--
-- Table structure for table `payment_monnify_logs`
--

CREATE TABLE `payment_monnify_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `json_response` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `power_products`
--

CREATE TABLE `power_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `power_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `power_unit` int(11) DEFAULT NULL,
  `power_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('available','bought') COLLATE utf8mb4_unicode_ci DEFAULT 'available',
  `purchased_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` enum('fixed','variable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `total_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `amount_pay_type` enum('full','installment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `installment_type` enum('flat','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'flat',
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `created_id`, `estate_id`, `name`, `photo`, `amount_type`, `amount`, `total_amount`, `amount_pay_type`, `installment_type`, `description`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Test', 'products/dHMl0ccontent-management.png', 'fixed', 2000.00, 2000.00, 'full', 'flat', '<p><strong>What is Lorem Ipsum?</strong></p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.&nbsp;<br>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'test', 'active', '2021-04-03 09:22:49', '2021-04-03 09:23:05'),
(2, 1, 1, 'Test12', NULL, 'fixed', 800.00, 800.00, 'full', 'flat', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.&nbsp;<br>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'test12', 'active', '2021-04-03 09:25:41', '2021-04-04 05:21:08'),
(3, 1, 1, 'Test', NULL, 'fixed', 2000.00, 2000.00, 'full', 'flat', NULL, 'test-1', 'active', '2021-04-04 05:21:55', '2021-04-04 05:21:55'),
(4, 1, 1, '1000000000000000000', NULL, 'fixed', 2500.00, 2500.00, 'installment', 'flat', '<p>testing</p>', '1000000000000000000', 'active', '2021-04-04 05:25:45', '2021-04-04 05:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_installments`
--

CREATE TABLE `product_installments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchased_products`
--

CREATE TABLE `purchased_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amountType` enum('fixed','variable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `totalAmount` double(20,2) NOT NULL DEFAULT 0.00,
  `amountPayType` enum('full','installment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `installmentType` enum('flat','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'flat',
  `paidAmount` double(20,2) NOT NULL DEFAULT 0.00,
  `producInstallment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paidInstallment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paidStatus` enum('partial','complete') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchased_products`
--

INSERT INTO `purchased_products` (`id`, `user_id`, `estate_id`, `name`, `photo`, `amountType`, `amount`, `totalAmount`, `amountPayType`, `installmentType`, `paidAmount`, `producInstallment`, `paidInstallment`, `description`, `slug`, `paidStatus`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 'Test12', NULL, 'fixed', 800.00, 800.00, 'full', 'flat', 800.00, NULL, NULL, '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.&nbsp;<br>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '16174620115', 'complete', '2021-04-03 09:30:11', '2021-04-03 09:30:11'),
(2, 5, 2, 'Test12', NULL, 'fixed', 800.00, 800.00, 'full', 'flat', 800.00, NULL, NULL, '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.&nbsp;<br>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '16174624975', 'complete', '2021-04-03 09:38:17', '2021-04-03 09:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE `relationships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `relationships`
--

INSERT INTO `relationships` (`id`, `created_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mother', 'mother', 'active', '2020-11-25 00:48:25', '2020-11-25 00:48:25'),
(2, 1, 'Father', 'father', 'active', '2020-11-25 08:43:50', '2020-11-25 08:43:50'),
(3, 1, 'Child', 'child', 'active', '2020-11-25 08:43:56', '2020-11-25 08:43:56'),
(4, 1, 'Brother', 'brother', 'active', '2020-11-25 08:44:04', '2020-11-25 08:44:04'),
(5, 1, 'Sister', 'sister', 'active', '2020-11-25 08:44:15', '2020-11-25 08:44:15'),
(6, 1, 'Wife', 'wife', 'active', '2020-11-25 08:49:43', '2020-11-25 08:49:43'),
(7, 1, 'Husband', 'husband', 'active', '2020-11-25 08:49:58', '2020-11-25 08:49:58'),
(8, 1, 'Friend', 'friend', 'active', '2020-11-25 08:50:29', '2020-11-25 08:50:29'),
(9, 1, 'Business Partner', 'business-partner', 'active', '2020-11-25 08:51:07', '2020-11-25 08:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `roleType` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `created_id`, `user_id`, `roleType`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'resident', 'Resident', 'resident', 'active', '2020-11-18 22:30:00', '2021-03-31 23:20:51'),
(2, 1, NULL, 'guard', 'Security Gaurd', 'security-gaurd', 'active', '2020-12-07 12:33:27', '2021-03-31 23:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `role_routes`
--

CREATE TABLE `role_routes` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `route_id` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_routes`
--

INSERT INTO `role_routes` (`id`, `role_id`, `route_id`, `created_at`, `updated_at`) VALUES
(1, 1, '1,23,35,36,37,38,39,40,42,54,69,87,100,106,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,145,148,149,172,181', '2020-11-18 22:58:44', '2021-03-21 08:55:32'),
(2, 2, '1,54,124,130,131,132,133,134,142,143,144', '2020-12-07 12:33:28', '2021-02-27 21:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL,
  `route_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_display` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `is_estate_manager` tinyint(4) NOT NULL DEFAULT 0,
  `is_company` tinyint(4) NOT NULL DEFAULT 0,
  `is_role` tinyint(4) NOT NULL DEFAULT 0,
  `is_guard` tinyint(4) NOT NULL DEFAULT 0,
  `is_resident` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `parent_id`, `route_key`, `name`, `is_display`, `display_order`, `is_admin`, `is_estate_manager`, `is_company`, `is_role`, `is_guard`, `is_resident`, `created_at`, `updated_at`) VALUES
(1, 0, 'dashboard', 'Dashboard', 'yes', 1, 1, 1, 1, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(2, 0, 'admin', 'Admins', 'yes', 100, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(3, 2, 'admin.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(4, 2, 'admin.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(5, 2, 'admin.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(6, 2, 'admin.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(7, 2, 'admin.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(8, 2, 'admin.managePermission', 'Permission', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(9, 0, 'manager', 'Estate Managers', 'yes', 200, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(10, 9, 'manager.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(11, 9, 'manager.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(12, 9, 'manager.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(13, 9, 'manager.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(14, 9, 'manager.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(15, 9, 'manager.managePermission', 'Permission', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(16, 0, 'company', 'Company', 'yes', 300, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(17, 16, 'company.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(18, 16, 'company.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(19, 16, 'company.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(20, 16, 'company.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(21, 16, 'company.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(22, 16, 'company.managePermission', 'Permission', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(23, 0, 'estate', 'Estate', 'yes', 400, 0, 1, 1, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(24, 23, 'estate.add', 'Add', 'yes', 0, 0, 0, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(25, 23, 'estate.edit', 'Edit', 'yes', 0, 0, 0, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(26, 23, 'estate.status', 'Manage status', 'yes', 0, 0, 0, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(27, 23, 'estate.delete', 'Delete', 'yes', 0, 0, 0, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(28, 23, 'estate.manageArtisan', 'Manage artisans', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(29, 0, 'resident', 'Resident', 'yes', 600, 1, 1, 0, 1, 1, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(30, 29, 'resident.add', 'Add', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(31, 29, 'resident.edit', 'Edit', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(32, 29, 'resident.active', 'Active', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(33, 29, 'resident.inactive', 'Inactive', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(34, 29, 'resident.delete', 'Delete', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(35, 0, 'resident.user', 'Resident Users', 'yes', 700, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(36, 35, 'resident.user.add', 'Add', 'yes', 0, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(37, 35, 'resident.user.edit', 'Edit', 'yes', 0, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(38, 35, 'resident.user.active', 'Active', 'yes', 0, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(39, 35, 'resident.user.inactive', 'Inactive', 'yes', 0, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(40, 35, 'resident.user.delete', 'Delete', 'yes', 0, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(41, 35, 'resident.user.addNoOfUser', 'Can Update No. of Users', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(42, 0, 'guard', 'Security Guards', 'yes', 500, 1, 1, 1, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(43, 42, 'guard.add', 'Add', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(44, 42, 'guard.edit', 'Edit', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(45, 42, 'guard.active', 'Active', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(46, 42, 'guard.inactive', 'Inactive', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(47, 42, 'guard.delete', 'Delete', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(48, 0, 'role', 'Roles', 'yes', 1550, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(49, 48, 'role.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(50, 48, 'role.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(51, 48, 'role.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(52, 48, 'role.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(53, 48, 'role.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(54, 0, 'setting', 'Settings', 'yes', 1700, 0, 0, 0, 1, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(55, 0, 'setting.sms', 'SMS Settings', 'yes', 1800, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(56, 55, 'setting.sms.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(57, 55, 'setting.sms.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(58, 55, 'setting.sms.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(59, 55, 'setting.sms.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(60, 55, 'setting.sms.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(61, 0, 'template', 'Templates', 'no', 1600, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(62, 61, 'template.add', 'Add', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(63, 61, 'template.edit', 'Edit', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(64, 61, 'template.active', 'Active', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(65, 61, 'template.inactive', 'Inactive', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(66, 61, 'template.delete', 'Delete', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(67, 42, 'guard.resetProfile', 'Reset Profile', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(68, 23, 'estate.assignGuard', 'Assign/Reassign Guard', 'yes', 0, 1, 1, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(69, 0, 'artisan', 'Artisans', 'yes', 800, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(70, 69, 'artisan.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(71, 69, 'artisan.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(72, 69, 'artisan.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(73, 69, 'artisan.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(74, 69, 'artisan.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(75, 0, 'artisan.category', 'Artisan Categories', 'yes', 900, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(76, 75, 'artisan.category.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(77, 75, 'artisan.category.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(78, 75, 'artisan.category.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(79, 75, 'artisan.category.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(80, 75, 'artisan.category.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(81, 0, 'artisan.group', 'Artisan Groups', 'yes', 1000, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(82, 81, 'artisan.group.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(83, 81, 'artisan.group.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(84, 81, 'artisan.group.active', 'Active', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(85, 81, 'artisan.group.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(86, 81, 'artisan.group.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(87, 0, 'sendMe', 'Send Me Files', 'yes', 1300, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(88, 87, 'sendMe.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(89, 87, 'sendMe.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(90, 87, 'sendMe.status', 'Manage Status', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(91, 87, 'sendMe.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(92, 87, 'sendMe.items', 'Manage Items', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(93, 29, 'resident.resetProfile', 'Reset Profile', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(94, 0, 'group', 'Groups', 'yes', 1100, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(95, 94, 'group.add', 'Add', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(96, 94, 'group.edit', 'Edit', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(97, 94, 'group.active', 'Active', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(98, 94, 'group.inactive', 'Inactive', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(99, 94, 'group.delete', 'Delete', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(100, 0, 'advert', 'Adverts', 'yes', 1200, 1, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(101, 100, 'advert.add', 'Add', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(102, 100, 'advert.edit', 'Edit', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(103, 100, 'advert.active', 'Active', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(104, 100, 'advert.inactive', 'Inactive', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(105, 100, 'advert.delete', 'Delete', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(106, 0, 'product', 'Products', 'yes', 1400, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(107, 106, 'product.add', 'Add', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(108, 106, 'product.edit', 'Edit', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(109, 106, 'product.active', 'Active', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(110, 106, 'product.inactive', 'Inactive', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(111, 106, 'product.delete', 'Delete', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(112, 0, 'setting.relationship', 'Relationship Setting', 'no', 1900, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(113, 112, 'setting.relationship.add', 'Add', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(114, 112, 'setting.relationship.edit', 'Edit', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(115, 112, 'setting.relationship.active', 'Active', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(116, 112, 'setting.relationship.inactive', 'Inactive', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(117, 112, 'setting.relationship.delete', 'Delete', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(118, 23, 'estate.contact', 'Contact Estate', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(119, 69, 'artisan.signin', 'SignIn', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(120, 69, 'artisan.rate', 'Rate Artisan', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(121, 69, 'artisan.call', 'Call Artisan', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(122, 54, 'setting.editProfile', 'Edit Profile', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(123, 54, 'setting.viewProfile', 'View Profile', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(124, 54, 'setting.changePassword', 'Change Password', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(125, 54, 'setting.visitorSetting', 'Visitor Setting', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(126, 0, 'nextOfKin', 'Next Of Kin', 'yes', 701, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(127, 126, 'nextOfKin.add', 'Add', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(128, 126, 'nextOfKin.edit', 'Edit', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(129, 126, 'nextOfKin.delete', 'Delete', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(130, 0, 'visitor', 'Visitors', 'yes', 702, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(131, 130, 'visitor.add', 'Add', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(132, 130, 'visitor.edit', 'Edit', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(133, 130, 'visitor.active', 'Active', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(134, 130, 'visitor.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(135, 130, 'visitor.delete', 'Delete', 'yes', 0, 0, 0, 0, 1, 1, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(136, 0, 'event', 'Events', 'yes', 703, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(137, 136, 'event.add', 'Add', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(138, 136, 'event.edit', 'Edit', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(139, 136, 'event.active', 'Active', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(140, 136, 'event.inactive', 'Inactive', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(141, 136, 'event.delete', 'Delete', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(142, 29, 'resident.checkInOut', 'Check In/Out', 'yes', 0, 0, 0, 0, 1, 1, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(143, 130, 'visitor.checkInOut', 'Check In/Out', 'yes', 0, 0, 0, 0, 1, 1, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(144, 130, 'visitor.walkInOut', 'Walk In/Out', 'yes', 0, 0, 0, 0, 1, 1, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(145, 130, 'visitor.visits', 'Visit List', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(146, 0, 'report', 'Reports', 'yes', 2000, 0, 0, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(147, 146, 'report.dailyActivity', 'Daily Activity', 'yes', 0, 0, 0, 1, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(148, 0, 'payment', 'Payment', 'yes', 2100, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(149, 148, 'payment.wallet', 'Wallet', 'yes', 0, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(150, 148, 'payment.method', 'Payment Method', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(151, 148, 'payment.method.add', 'Add', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(152, 148, 'payment.method.edit', 'Edit', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(153, 148, 'payment.method.active', 'Active', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(154, 148, 'payment.method.inactive', 'Inactive', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(155, 148, 'payment.method.delete', 'Delete', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(156, 0, 'package', 'Package', 'no', 1500, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(157, 156, 'package.add', 'Add', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(158, 156, 'package.edit', 'Edit', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(159, 156, 'package.delete', 'Delete', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(160, 156, 'package.status', 'Manage Status', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(161, 0, 'page', 'Pages', 'yes', 1750, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(162, 161, 'page.add', 'Add', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(163, 161, 'page.edit', 'Edit', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(164, 161, 'page.status', 'Manage Status', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(165, 161, 'page.delete', 'Delete', 'yes', 0, 1, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(166, 23, 'estate.requiredSignOut', 'Required Signout', 'no', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(167, 0, 'message', 'Messages', 'yes', 1250, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(168, 167, 'message.add', 'Add', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(169, 167, 'message.edit', 'Edit', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(170, 167, 'message.delete', 'Delete', 'yes', 0, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(172, 0, 'powerProduct', 'Power Products', 'yes', 1450, 1, 1, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(173, 172, 'powerProduct.add', 'Add', 'yes', 1450, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(174, 172, 'powerProduct.edit', 'Edit', 'yes', 1450, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(175, 172, 'powerProduct.status', 'Status', 'yes', 1450, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(176, 172, 'powerProduct.delete', 'Delete', 'yes', 1450, 1, 1, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(177, 146, 'report.productReport', 'Product Reports', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(178, 146, 'report.powerReport', 'Power Reports', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(179, 87, 'sendMe.assignEstate', 'Assign Estates', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(181, 0, 'good', 'Goods(Vegan/Non-vegan)', 'yes', 1350, 0, 0, 0, 1, 0, 1, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(182, 181, 'good.add', 'Add', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(183, 181, 'good.edit', 'Edit', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(184, 181, 'good.status', 'Manage Status', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(185, 181, 'good.delete', 'Delete', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01'),
(186, 181, 'good.items', 'Items', 'yes', 0, 0, 0, 0, 0, 0, 0, '2020-08-28 15:52:01', '2020-08-28 15:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `send_mes`
--

CREATE TABLE `send_mes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `send_me_buy_items`
--

CREATE TABLE `send_me_buy_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `send_me_id` int(11) DEFAULT NULL,
  `send_me_item_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `send_me_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_me_item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_type` enum('item','kg') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'item',
  `item_price` double(20,2) NOT NULL DEFAULT 0.00,
  `quantity` double(20,2) DEFAULT NULL,
  `total_amount` double(20,2) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `send_me_estate_items`
--

CREATE TABLE `send_me_estate_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `send_me_id` int(11) DEFAULT NULL,
  `send_me_item_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_on` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `send_me_items`
--

CREATE TABLE `send_me_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `send_me_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_type` enum('item','kg') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'item',
  `item_price` double(20,2) NOT NULL DEFAULT 0.00,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setting_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `created_id`, `key`, `key_value`, `setting_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'sender_id', 'dhfgdfgdg fgdf gfghfg', 'sms', 'active', '2020-09-27 05:14:00', '2020-09-27 08:17:04'),
(2, 1, 'api_username', 'dhfgdfgdg', 'sms', 'active', '2020-09-27 05:14:00', '2020-09-27 08:17:04'),
(3, 1, 'api_password', 'dhfgdfgdg', 'sms', 'active', '2020-09-27 05:14:00', '2020-09-27 08:17:04'),
(4, 1, 'api_url', 'dhfgdfgdg', 'sms', 'active', '2020-09-27 05:14:00', '2020-09-27 08:17:04'),
(5, 1, 'currency_code', 'NGN', 'currency', 'active', '2020-09-27 05:14:00', '2020-09-27 08:17:04'),
(6, 1, 'currency_symbol', '₦', 'currency', 'active', '2020-09-27 05:14:00', '2020-09-27 08:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `to_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','processing','sent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_settings`
--

CREATE TABLE `sms_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `sender_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_settings`
--

INSERT INTO `sms_settings` (`id`, `user_id`, `created_id`, `estate_id`, `sender_id`, `api_username`, `api_password`, `api_url`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 13, 1, 1, 'sesadigital', 'sesadigital', 'sms123456', 'http://sms.myposapp.com/api', 'sesadigital', 'active', '2020-11-30 22:20:38', '2020-12-23 15:34:03'),
(2, 10, 1, NULL, 'ffdf', 'dfg', 'gfhg', 'http://sms.myposapp.com/api', 'dfg', 'active', '2020-12-23 15:27:06', '2020-12-23 15:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `template_type` enum('email','sms') NOT NULL DEFAULT 'email',
  `subject_tags` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `template_tags` varchar(255) DEFAULT NULL,
  `template` longtext DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `name`, `template_type`, `subject_tags`, `subject`, `template_tags`, `template`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Username - password send', 'email', '{SITE_TITLE}', 'Welcome to {SITE_TITLE}', '{SITE_TITLE},{NAME},{USERNAME},{PASSWORD}', 'Dear {NAME},\n\nWelcome to {SITE_TITLE}. \nPlease find the below login details to access your panel on {SITE_TITLE}.\n\nUsername:  {USERNAME}\nPassword: {PASSWORD}\n\nThanks & Regards\n{SITE_TITLE}', 'username-password-send', 'active', '2020-10-03 04:21:08', '2020-10-03 04:18:24'),
(2, 'Reset Profile', 'email', '{SITE_TITLE}', 'Profile Reset - {SITE_TITLE}', '{SITE_TITLE},{NAME},{USERNAME},{PASSWORD}', 'Dear {NAME},\n\nYour profile has been reset on {SITE_TITLE}. \nPlease find the below login details to access your panel on {SITE_TITLE}.\n\nUsername:  {USERNAME}\nPassword: {PASSWORD}\n\nThanks & Regards\n{SITE_TITLE}', 'reset-profile', 'active', '2020-10-04 03:58:52', '2020-10-03 22:28:52'),
(3, 'Assign/Reassign estate notification to guards', 'email', '{ACTION_TYPE}', 'You have been {ACTION_TYPE} an estate', '{SITE_TITLE},{NAME},{ESTATE},{DATE}', 'Dear {NAME},\n\nYou have been {ACTION_TYPE} an estate on {SITE_TITLE} as a security person.  Please find the below detail:-\n\nEstate:  {ESTATE}\nDate: {DATE}\n\nThanks & Regards\n{SITE_TITLE}', 'assign-reassign-estate-notification-to-guards', 'active', '2020-10-04 11:11:32', '2020-10-04 05:41:32'),
(4, 'Panic Alert', 'sms', NULL, NULL, '{NAME},{ESTATE}', 'Hi, A panic alert is detected by {NAME} from {ESTATE}.', 'panic-alert', 'active', '2020-12-01 03:57:48', '2020-11-30 22:27:48'),
(5, 'Panic Alert', 'email', '{SITE_TITLE},{NAME}', 'Panic Alert by {NAME} - {SITE_TITLE}', '{SITE_TITLE},{NAME},{ESTATE},{LOCATION},{DATE},{TIME}', 'Dear,\n\nA panic alert is detected by {NAME} from {ESTATE}. Below are the details:\n\nLocation: {LOCATION}\nDate: {DATE}\nTime: {TIME}\n\nThanks & Regards\n{SITE_TITLE}', 'panic-alert-1', 'active', '2020-12-01 04:02:29', '2020-11-30 22:32:29'),
(6, 'Walk-in Notification', 'email', '{SITE_TITLE}', 'Walk-in Notification - {SITE_TITLE}', '{SITE_TITLE},{NAME},{VISITOE_NAME},{PHONE},{MESSAGE},{DATE}', 'Dear {NAME},\n\nYou have received a walk-in request on {SITE_TITLE}. We are waiting for your action. \n\nPlease find the below details.\n\nName: {VISITOE_NAME}\nPhone:{PHONE}\nDate: {DATE}\nMessage:{MESSAGE}\n\nThanks & Regards\n{SITE_TITLE}', 'walk-in-notification', 'active', '2020-12-13 06:28:19', '2020-12-13 01:06:04'),
(7, 'Walk-in Notification for not authentication', 'email', '{SITE_TITLE}', 'Walk-in Notification - {SITE_TITLE}', '{SITE_TITLE},{NAME},{VISITOE_NAME},{PHONE},{MESSAGE},{DATE}', 'Dear {NAME},\n\nA walk-in visitor is on their way to them. Please find the below details from walk-in visitor.\n\nName: {VISITOE_NAME}\nPhone:{PHONE}\nDate: {DATE}\nMessage:{MESSAGE}\n\nThanks & Regards\n{SITE_TITLE}', 'walk-in-notification-for-not-authentication', 'active', '2020-12-14 19:36:26', '2020-12-14 14:06:26'),
(8, 'Visitor add notification to resident', 'email', '{SITE_TITLE}', 'New visitor signed in - {SITE_TITLE}', '{SITE_TITLE},{NAME},{ACCESS_CODE}', 'Dear {NAME},\n\nYour visitor has been successful signed in with access code {ACCESS_CODE}. Kindly forward the access code to the visitor.\n\nThanks & Regards\n{SITE_TITLE}', 'visitor-add-notification-to-resident', 'active', '2020-12-16 15:51:18', '2020-12-16 10:28:00'),
(9, 'Visitor add notification to resident', 'sms', NULL, NULL, '{NAME},{ACCESS_CODE}', 'Dear {NAME}, Your visitor has been successful signed in with access code {ACCESS_CODE}. Kindly forward the access code to the visitor.', 'visitor-add-notification-to-resident-1', 'active', '2020-12-16 15:53:04', '2020-12-16 10:23:04'),
(10, 'Username - password send', 'sms', NULL, NULL, '{SITE_TITLE},{NAME},{USERNAME},{PASSWORD}', 'Dear {NAME}, Welcome to {SITE_TITLE}. Your Username: {USERNAME} and Password: {PASSWORD}.', 'username-password-send-1', 'active', '2020-12-23 20:17:38', '2020-12-23 14:47:38'),
(11, 'Event add notification to resident', 'email', '{SITE_TITLE}', 'New visitor signed in - {SITE_TITLE}', '{SITE_TITLE},{NAME},{ACCESS_CODE}', 'Dear {NAME},\r\n\r\nYour event has been successful signed in with access code {ACCESS_CODE}. Kindly forward the access code to the visitor(s).\r\n\r\nThanks & Regards\r\n{SITE_TITLE}', 'event-add-notification-to-resident', 'active', '2021-02-06 15:51:18', '2021-02-06 10:21:18'),
(12, 'Event add notification to resident', 'sms', NULL, NULL, '{NAME},{ACCESS_CODE}', 'Dear {NAME}, Your event has been successful signed in with access code {ACCESS_CODE}. Kindly forward the access code to the visitor(s).', 'event-add-notification-to-resident-1', 'active', '2021-02-06 15:51:18', '2021-02-06 10:21:18'),
(13, 'Contact Estate', 'email', '{SITE_TITLE},{NAME}', 'A contact query reported by {NAME} - {SITE_TITLE}', '{SITE_TITLE},{NAME},{ESTATE},{CATEGORY},{TITLE},{MESSAGE}', 'Dear,\n\nA contact query has been submitted by {NAME} from {ESTATE}. Below are the details:\n\nName: {NAME}\nCategory: {CATEGORY}\nTitle: {TITLE}\nMessage: {MESSAGE}\n\nThanks & Regards\n{SITE_TITLE}', 'contact-estate', 'active', '2021-04-04 02:24:01', '2021-04-03 20:57:15'),
(14, 'Contact Estate', 'sms', NULL, NULL, '{NAME},{ESTATE}', 'Hi, A contact query has been submitted by {NAME} from {ESTATE}.', 'contact-estate-1', 'active', '2021-04-04 02:25:12', '2021-04-03 20:55:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_type` enum('superadmin','admin','estate_manager','company','resident','guard','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `estate_id` int(11) DEFAULT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resident_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `house_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other','unspecified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unspecified',
  `resident_category` enum('alpha','non-alpha') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resident_status` enum('owner','tenant') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resident_type` enum('resident','business') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `add_no_of_user` int(11) DEFAULT NULL,
  `route_id` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_balance` double(20,2) NOT NULL DEFAULT 0.00,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_password_updated` tinyint(4) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created_id`, `user_type`, `estate_id`, `resident_id`, `company_id`, `manager_id`, `username`, `resident_code`, `first_name`, `last_name`, `email`, `phone`, `photo`, `dob`, `house_code`, `address`, `gender`, `resident_category`, `resident_status`, `resident_type`, `add_no_of_user`, `route_id`, `role_id`, `wallet_balance`, `device_token`, `email_verified_at`, `password`, `has_password_updated`, `remember_token`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'superadmin', NULL, NULL, NULL, NULL, 'xzdds2', NULL, 'Site', 'Manager', 'admin@gmail.com', NULL, NULL, NULL, NULL, NULL, 'male', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 'eaMx1dwPor3cue9uAczqjO:APA91bHopd8YbNAnkk-E_2faQ0U0A1w_imDiQFJXgCLV-s3-UbJcsEa7s3j8rjbGBzhrdkKU7HNwAGELNH8J6tYZ_n5SWnjSuAUPvPnxvUCt34knSyltWwYm523QckMCjumSZbnoEnBU', NULL, '$2y$10$w.MXL7UKeJl04x4CMwrlQuzlO2k5XWRfyeYWQzHl4NPlC0UbW4QEO', 0, NULL, 'xzdds', 'active', '2020-11-26 23:51:23', '2021-02-05 14:07:37'),
(2, 1, 'estate_manager', NULL, NULL, NULL, NULL, NULL, NULL, 'Fidtec', 'Manager', 'figtecltdm@mailinator.com', '12345678', NULL, NULL, NULL, 'dfdff', 'unspecified', NULL, NULL, NULL, NULL, '1,23,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,67,68,93,94,95,96,97,98,99,106,107,108,109,110,111,167,168,169,170', NULL, 0.00, NULL, NULL, '$2y$10$Ief1VDChZcDiUHbaG7dV7OaFJcnEVJ2hZbyweCUTX4FucFxNOieIW', 0, NULL, 'fidtec', 'active', '2021-03-31 21:42:11', '2021-04-01 02:28:13'),
(3, 1, 'company', NULL, NULL, NULL, NULL, NULL, NULL, 'securityc', 'Frim', 'securityc@mailinator.com', '454645654', NULL, NULL, NULL, 'sdfdfdfgdf', 'unspecified', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '$2y$10$W7cE5PNoeWi1LbSNMivHduwf4m.8MlIL9Ur5aki3R3ynnRgJPeXiC', 0, NULL, 'securityc', 'active', '2021-03-31 22:03:48', '2021-03-31 22:03:48'),
(4, 1, 'estate_manager', NULL, NULL, NULL, NULL, NULL, NULL, 'figtec', 'm1', 'figtecm1@mailinator.com', '355465465', NULL, NULL, NULL, 'fdfsgfgdf', 'unspecified', NULL, NULL, NULL, NULL, '1,23,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,67,68,93,94,95,96,97,98,99,106,107,108,109,110,111,167,168,169,170,172,173,174,175,176', NULL, 0.00, NULL, NULL, '$2y$10$Ief1VDChZcDiUHbaG7dV7OaFJcnEVJ2hZbyweCUTX4FucFxNOieIW', 0, NULL, 'figtec', 'active', '2021-03-31 23:51:38', '2021-04-04 01:00:52'),
(5, 1, 'resident', 2, NULL, NULL, NULL, 'test.1', '0001', 'Tset', 'Test', 'test2206@mailinator.com', '458875468', NULL, NULL, '12233', 'dsfdsfg', 'male', 'alpha', 'owner', 'resident', 5, NULL, '1', 19100.00, NULL, NULL, '$2y$10$w.MXL7UKeJl04x4CMwrlQuzlO2k5XWRfyeYWQzHl4NPlC0UbW4QEO', 1, NULL, 'tset', 'active', '2021-04-01 01:33:45', '2021-04-04 08:15:37'),
(6, 1, 'guard', NULL, NULL, NULL, NULL, 'ghfh.1', '0001', 'ghfh', 'hfgh', 'fgdfg@hghj.ghfh', '65667', NULL, '2021-04-04', NULL, 'ytyutu', 'male', NULL, NULL, NULL, NULL, NULL, '2', 0.00, NULL, NULL, '$2y$10$ZeuPdzlVrzf8BzwSQs5QlOQtC3yM1UaxfKDvEjggVFZcDLB544A.S', 0, NULL, 'ghfh', 'active', '2021-04-04 04:28:57', '2021-04-04 04:30:10'),
(7, 1, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 'Test', 'Admin', 'admin@mailinator.com', '3324544', NULL, NULL, NULL, 'fghfghfg', 'unspecified', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, '$2y$10$w.MXL7UKeJl04x4CMwrlQuzlO2k5XWRfyeYWQzHl4NPlC0UbW4QEO', 0, NULL, 'test', 'active', '2021-04-12 22:00:12', '2021-04-12 22:40:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_checks`
--

CREATE TABLE `user_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tag_number` int(11) DEFAULT NULL,
  `check_in_by_id` int(11) DEFAULT NULL,
  `check_in_at` datetime DEFAULT NULL,
  `check_out_by_id` int(11) DEFAULT NULL,
  `check_out_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `estate_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_type` enum('single','multiple','free') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `access_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_level_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_signout_required` tinyint(4) NOT NULL DEFAULT 0,
  `is_signout` tinyint(4) NOT NULL DEFAULT 0,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_settings`
--

CREATE TABLE `visitor_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `check_in` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=>no,1=>yes',
  `check_out` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=>no,1=>yes',
  `is_user_select` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>no,1=>yes',
  `user_id_check_in` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id_check_out` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authenticate_walkin` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>no,1=>yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_visits`
--

CREATE TABLE `visitor_visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `visit_type` enum('walk-in','check-in') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check-in',
  `estate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visitor_id` int(11) DEFAULT NULL,
  `tag_number` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_in_by_id` int(11) DEFAULT NULL,
  `visit_in_at` datetime DEFAULT NULL,
  `visit_out_by_id` int(11) DEFAULT NULL,
  `visit_out_at` datetime DEFAULT NULL,
  `decline_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approve','complete','decline','cancle') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `adverts`
--
ALTER TABLE `adverts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artisans`
--
ALTER TABLE `artisans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artisan_categories`
--
ALTER TABLE `artisan_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artisan_groups`
--
ALTER TABLE `artisan_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artisan_linked_categories`
--
ALTER TABLE `artisan_linked_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artisan_ratings`
--
ALTER TABLE `artisan_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artisan_signins`
--
ALTER TABLE `artisan_signins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estates`
--
ALTER TABLE `estates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `good_buy_items`
--
ALTER TABLE `good_buy_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `good_items`
--
ALTER TABLE `good_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `next_of_kins`
--
ALTER TABLE `next_of_kins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_monnify_logs`
--
ALTER TABLE `payment_monnify_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `power_products`
--
ALTER TABLE `power_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_installments`
--
ALTER TABLE `product_installments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchased_products`
--
ALTER TABLE `purchased_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_routes`
--
ALTER TABLE `role_routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `send_mes`
--
ALTER TABLE `send_mes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `send_me_buy_items`
--
ALTER TABLE `send_me_buy_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `send_me_estate_items`
--
ALTER TABLE `send_me_estate_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `send_me_items`
--
ALTER TABLE `send_me_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_settings`
--
ALTER TABLE `sms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_checks`
--
ALTER TABLE `user_checks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_settings`
--
ALTER TABLE `visitor_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_visits`
--
ALTER TABLE `visitor_visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `adverts`
--
ALTER TABLE `adverts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artisans`
--
ALTER TABLE `artisans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artisan_categories`
--
ALTER TABLE `artisan_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artisan_groups`
--
ALTER TABLE `artisan_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artisan_linked_categories`
--
ALTER TABLE `artisan_linked_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artisan_ratings`
--
ALTER TABLE `artisan_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artisan_signins`
--
ALTER TABLE `artisan_signins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estates`
--
ALTER TABLE `estates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `good_buy_items`
--
ALTER TABLE `good_buy_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `good_items`
--
ALTER TABLE `good_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `next_of_kins`
--
ALTER TABLE `next_of_kins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_monnify_logs`
--
ALTER TABLE `payment_monnify_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `power_products`
--
ALTER TABLE `power_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_installments`
--
ALTER TABLE `product_installments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchased_products`
--
ALTER TABLE `purchased_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `relationships`
--
ALTER TABLE `relationships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_routes`
--
ALTER TABLE `role_routes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `send_mes`
--
ALTER TABLE `send_mes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `send_me_buy_items`
--
ALTER TABLE `send_me_buy_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `send_me_estate_items`
--
ALTER TABLE `send_me_estate_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `send_me_items`
--
ALTER TABLE `send_me_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_settings`
--
ALTER TABLE `sms_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_checks`
--
ALTER TABLE `user_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_settings`
--
ALTER TABLE `visitor_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_visits`
--
ALTER TABLE `visitor_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
