-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2025 at 12:27 PM
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
-- Database: `celaptop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completed_repairs`
--

CREATE TABLE `completed_repairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `fault` text NOT NULL,
  `device` varchar(255) NOT NULL,
  `repair_price` decimal(10,2) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `note_number` varchar(255) DEFAULT NULL,
  `customer_number` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'completed',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `completed_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `completed_repairs`
--

INSERT INTO `completed_repairs` (`id`, `user_id`, `customer_name`, `contact`, `date`, `fault`, `device`, `repair_price`, `serial_number`, `note_number`, `customer_number`, `status`, `images`, `completed_at`, `created_at`, `updated_at`) VALUES
(11, 3, 'Kavindu Nelshan', '0765645303', '2025-07-03', 'rrerr', 'asus', 3000.00, '234765889995t5t', '2', 'CE-0030', 'completed', '[\"repairs\\/W23E5F1TQI2WO4gIPhtQQidyAFiEFNx7uJf4l1Pw.png\"]', '2025-07-03 13:11:20', '2025-07-03 07:41:20', '2025-07-03 07:41:20'),
(12, 3, 'Kavindu Nelshan', '0765645303', '2025-07-04', 'no power', 'asus', 1500.00, 'rert64t3345er', '9', 'CE-3-0003', 'completed', '[\"repairs\\/XfAfxIhcFctSqeUbMIGkL49kvQuGCG1YFXhbI0o2.png\"]', '2025-07-04 16:28:10', '2025-07-04 10:58:10', '2025-07-04 10:58:10'),
(13, 3, 'janith premasiri', '0774256445', '2025-07-04', 'No power and buttery replace', 'Dell Inspiron 15 3000', 35000.00, 'dell-2467785vcfku897', '10', 'CE-3-0004', 'completed', '[\"repairs\\/TGKmX3RKyEwGtuZefna3x2fdVMAwWzye5jR5K6hl.jpg\",\"repairs\\/owD08kGjI7kkuzrFHwk08Whlf7JDWw6seAifL6Uu.png\"]', '2025-07-04 16:49:10', '2025-07-04 11:19:10', '2025-07-04 11:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE `counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counters`
--

INSERT INTO `counters` (`id`, `user_id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(2, 3, 'customer_number', 1, '2025-07-03 08:14:12', '2025-07-03 08:14:12'),
(3, 3, 'customer_number_3', 1, '2025-07-04 07:12:47', '2025-07-04 07:12:47'),
(4, 3, 'customer_number_user_3', 5, '2025-07-04 07:14:41', '2025-07-04 14:10:13');

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `sales_rep` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_name`, `customer_phone`, `sales_rep`, `issue_date`, `total_amount`, `created_at`, `updated_at`, `user_id`) VALUES
(24, 'INV-0001', 'Kavindu Nelshan', '0765645303', 'CE laptop repair center', '2025-07-04', 75000.00, '2025-07-04 07:54:04', '2025-07-04 07:54:04', 3),
(26, 'INV-0003', 'Kavindu Nelshan', '0765645303', 'CE laptop repair center', '2025-07-04', 75000.00, '2025-07-04 07:57:10', '2025-07-04 07:57:10', 3);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_counters`
--

CREATE TABLE `invoice_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `last_number` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_counters`
--

INSERT INTO `invoice_counters` (`id`, `user_id`, `last_number`, `created_at`, `updated_at`) VALUES
(1, 3, 3, '2025-07-04 07:54:04', '2025-07-04 07:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `warranty`, `quantity`, `unit_price`, `amount`, `created_at`, `updated_at`, `user_id`) VALUES
(41, 24, 'Laptop asus', '1 montrh', 3, 25000.00, 75000.00, '2025-07-04 07:54:04', '2025-07-04 07:54:04', 3),
(43, 26, 'Laptop asus', '1 montrh', 3, 25000.00, 75000.00, '2025-07-04 07:57:10', '2025-07-04 07:57:10', 3);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_with_stocks`
--

CREATE TABLE `invoice_with_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `sales_rep` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_with_stocks`
--

INSERT INTO `invoice_with_stocks` (`id`, `invoice_number`, `customer_name`, `customer_phone`, `sales_rep`, `issue_date`, `total_amount`, `user_id`, `created_at`, `updated_at`) VALUES
(14, 'INV-000001', 'Kavindu Nelshan', '0765645303', 'CE laptop repair center', '2025-07-04', 2800.00, 3, '2025-07-04 05:30:20', '2025-07-04 05:30:20'),
(15, 'INV-000015', 'janith', '0774512775', 'CE laptop repair center', '2025-07-04', 2200.00, 3, '2025-07-04 05:30:57', '2025-07-04 05:30:57'),
(16, 'INV-000016', 'janith', '0774512775', 'CE laptop repair center', '2025-07-04', 3000.00, 3, '2025-07-04 06:00:02', '2025-07-04 06:00:02'),
(17, 'INV-000017', 'janith', '0774512775', 'CE laptop repair center', '2025-07-04', 3000.00, 3, '2025-07-04 07:39:01', '2025-07-04 07:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_with_stock_items`
--

CREATE TABLE `invoice_with_stock_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_with_stock_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_with_stock_items`
--

INSERT INTO `invoice_with_stock_items` (`id`, `invoice_with_stock_id`, `stock_id`, `warranty`, `quantity`, `unit_price`, `cost_price`, `amount`, `user_id`, `created_at`, `updated_at`) VALUES
(13, 14, 11, '3 month', 1, 2800.00, 1500.00, 2800.00, 3, '2025-07-04 05:30:20', '2025-07-04 05:30:20'),
(14, 15, 10, NULL, 1, 2200.00, 2000.00, 2200.00, 3, '2025-07-04 05:30:57', '2025-07-04 05:30:57'),
(15, 16, 11, NULL, 1, 3000.00, 1500.00, 3000.00, 3, '2025-07-04 06:00:02', '2025-07-04 06:00:02'),
(16, 17, 11, NULL, 1, 3000.00, 1000.00, 3000.00, 3, '2025-07-04 07:39:01', '2025-07-04 07:39:01');

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"ab0aa9af-06f5-4edd-9fea-99c8b82082f4\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:19;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"7827b1be-8243-4d4f-b368-9c68e6f550c5\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1751690091,\"delay\":null}', 0, NULL, 1751690091, 1751690091),
(2, 'default', '{\"uuid\":\"36a273dd-dcb9-4af8-9466-c7271658b733\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:20;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"63e6564a-074e-478f-8427-9e05bde4f11a\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1751690937,\"delay\":null}', 0, NULL, 1751690937, 1751690937),
(3, 'default', '{\"uuid\":\"86088129-7a4e-44b7-a4f0-2eef97ecb72a\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:21;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"afb73fd8-eadb-4935-95cf-73cf7d488b85\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1751691522,\"delay\":null}', 0, NULL, 1751691522, 1751691522),
(4, 'default', '{\"uuid\":\"548f5b2f-0c28-4c2d-9eed-1c76f413ef77\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:22;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"ef835a37-f0ae-4b34-bb20-12a9b0f74545\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1751692354,\"delay\":null}', 0, NULL, 1751692354, 1751692354),
(5, 'default', '{\"uuid\":\"be55aac8-0914-4219-9f45-32505ef48efc\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:23;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"0d02b7b4-9639-4ae6-be1e-8105120fed93\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1751695491,\"delay\":null}', 0, NULL, 1751695491, 1751695491);

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
-- Table structure for table `laptop_repairs`
--

CREATE TABLE `laptop_repairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `fault` text NOT NULL,
  `device` varchar(255) NOT NULL,
  `repair_price` decimal(10,2) DEFAULT NULL,
  `serial_number` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `customer_number` varchar(255) NOT NULL DEFAULT 'CE-0001',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laptop_repairs`
--

INSERT INTO `laptop_repairs` (`id`, `customer_name`, `contact`, `date`, `fault`, `device`, `repair_price`, `serial_number`, `status`, `images`, `customer_number`, `created_at`, `updated_at`, `note_number`, `user_id`) VALUES
(71, 'Kavindu Nelshan', '0774258978', '2025-07-04', 'ththj', 'Accer 15 new6', NULL, '5647tyhtlllfj', 'pending', NULL, 'CE-3-0005', '2025-07-04 14:10:14', '2025-07-04 14:10:14', '1', 3);

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
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '2025_06_14_070044_create_users_table', 2),
(8, '2025_06_14_090104_create_doctors_table', 2),
(9, '2025_06_14_115056_create_channel_fees_table', 3),
(10, '2025_06_15_034609_create_appointments_table', 4),
(11, '2025_06_15_040718_create_appointments_table', 5),
(12, '2025_06_24_103509_create_daily_summaries_table', 6),
(13, '2025_06_24_114423_replace_doctor_id_with_name_in_appointments_table', 7),
(14, '2025_06_24_122044_add_doctor_position_to_appointments_table', 8),
(15, '2025_06_24_143340_make_id_number_phone_nullable_in_appointments_table', 9),
(16, '2025_06_25_110222_create_daily_summaries_table', 10),
(17, '2025_06_25_120837_create_daily_summaries_table', 11),
(18, '2025_06_28_134957_create_laptop_repair_table', 12),
(19, '2025_06_28_142221_create_laptop_repairs_table', 13),
(20, '2025_06_28_143700_create_completed_repairs_table', 14),
(21, '2025_06_28_144637_add_status_to_completed_repairs_table', 15),
(22, '2025_06_28_151057_create_system_settings_table', 16),
(23, '2025_06_28_151540_create_counters_table', 17),
(24, '2025_06_28_155339_create_notecounters_table', 18),
(25, '2025_06_28_162934_create_note_counters_table', 19),
(26, '2025_06_30_205317_create_stock_table', 20),
(27, '2025_07_01_085146_create_note_counters_table', 21),
(28, '2025_07_01_093155_create_invoices_table', 22),
(29, '2025_07_01_093159_create_invoice_items_table', 22),
(30, '2025_07_01_123026_create_shops_table', 23),
(31, '2025_07_01_123139_create_shop_items_table', 23),
(32, '2025_07_02_220045_add_images_to_laptop_repairs_table', 24),
(33, '2025_07_02_223705_add_images_to_completed_repairs_table', 25),
(34, '2025_07_03_001122_create_my_shop_details_table', 26),
(35, '2025_07_03_121901_add_user_id_to_laptop_repairs_table', 27),
(36, '2025_07_03_123343_add_user_id_to_note_counters_table', 28),
(37, '2025_07_03_124435_add_user_id_to_invoices_table', 29),
(38, '2025_07_03_124715_add_user_id_to_my_shop_details_table', 30),
(39, '2025_07_03_125317_add_user_id_to_invoice_items_table', 31),
(40, '2025_07_03_130527_add_user_id_to_stocks_table', 32),
(41, '2025_07_03_130950_add_user_id_to_completed_repairs_table', 33),
(42, '2025_07_03_131714_add_user_id_to_shops_table', 34),
(43, '2025_07_03_131835_add_user_id_to_shop_items_table', 35),
(44, '2025_07_03_132602_remove_user_id_from_my_shop_details_table', 36),
(45, '2025_07_03_132735_add_user_id_to_my_shop_details_table', 37),
(46, '2025_07_03_132903_remove_user_id_from_my_shop_items_table', 38),
(47, '2025_07_03_133909_add_user_id_to_counters_table', 39),
(48, '2025_07_03_134337_add_user_id_to_counters_table', 40),
(49, '2025_07_03_155219_create_invoice_with_stocks_table', 41),
(50, '2025_07_03_232535_create_cashiers_table', 42),
(51, '2025_07_04_102840_add_cost_price_to_invoice_with_stock_items_table', 43),
(52, '2025_07_04_131722_create_invoice_counters_table', 44),
(53, '2025_07_04_171014_create_note_counters_table', 45),
(54, '2025_07_05_102141_add_email_verification_to_users_table', 46),
(55, '2025_07_05_145359_create_payments_table', 47);

-- --------------------------------------------------------

--
-- Table structure for table `my_shop_details`
--

CREATE TABLE `my_shop_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text NOT NULL,
  `hotline` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo_image` varchar(255) DEFAULT NULL,
  `condition_1` text DEFAULT NULL,
  `condition_2` text DEFAULT NULL,
  `condition_3` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `my_shop_details`
--

INSERT INTO `my_shop_details` (`id`, `shop_name`, `description`, `address`, `hotline`, `email`, `logo_image`, `condition_1`, `condition_2`, `condition_3`, `created_at`, `updated_at`, `user_id`) VALUES
(3, 'CE laptop repair center', 'Dealers in Desktop Computers, Laptop Accessories, Repairs Service & import wholesale and retail', '254/1/1/6, Baladaksha Mawatha, New Sathipola Road, Mawanella, Sri Lanka', '0756502913', 'chammikaelectronic@gmail.com', 'shop_logos/HqBIVX2YUwXm1TTrAiF4aB6p2zZxzCnpZws2jLe8.jpg', 'Warranty for 1 Year Less 21 Working Days for computer hardware.', 'No Warranty for Bum Marks, Scratches, Physical damages and any other damage happened by user activities.', 'Goods sold once can\'t return.', '2025-07-03 08:04:04', '2025-07-03 08:18:02', 3);

-- --------------------------------------------------------

--
-- Table structure for table `note_counters`
--

CREATE TABLE `note_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `last_number` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `note_counters`
--

INSERT INTO `note_counters` (`id`, `user_id`, `last_number`, `created_at`, `updated_at`) VALUES
(3, 3, 1, '2025-07-04 14:10:13', '2025-07-04 14:10:14');

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
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `slip_path` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('J4OcGiANm15T5OQkGQxVQfGEbs18uRVR9mRj5wpu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlpiNTIwQ0RFamJ0dmI0TEJxUFNyYkYyUktncjNwV1dvOHA1THNqOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdXBlci1hZG1pbi91c2VycyI7fX0=', 1751710922);

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `user_id`, `shop_name`, `phone_number`, `created_at`, `updated_at`) VALUES
(4, 3, 'Chama', '0765645303', '2025-07-03 07:59:59', '2025-07-03 07:59:59'),
(5, 3, 'CE laptop repair center', '0765645303', '2025-07-04 09:59:44', '2025-07-04 09:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `shop_items`
--

CREATE TABLE `shop_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `date` date NOT NULL DEFAULT '2025-07-01',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_items`
--

INSERT INTO `shop_items` (`id`, `shop_id`, `item_name`, `description`, `warranty`, `serial_number`, `price`, `date`, `created_at`, `updated_at`) VALUES
(14, 4, 'lap', 'no power', NULL, 'wtey', 2500.00, '2025-07-02', '2025-07-03 08:00:29', '2025-07-03 08:00:29'),
(15, 4, 'monitor', 'giuyiyujuty', NULL, 'ytyrt', 3500.00, '2025-07-02', '2025-07-03 08:00:29', '2025-07-03 08:00:29'),
(16, 5, 'lap', 'new', NULL, 'bhgjhmv', 2500.00, '2025-07-04', '2025-07-04 09:59:44', '2025-07-04 09:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `whole_sale_price` decimal(10,2) NOT NULL,
  `retail_price` decimal(10,2) NOT NULL,
  `vender` varchar(255) DEFAULT NULL,
  `stock_date` date NOT NULL DEFAULT curdate(),
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `user_id`, `item_name`, `description`, `cost`, `whole_sale_price`, `retail_price`, `vender`, `stock_date`, `quantity`, `created_at`, `updated_at`) VALUES
(9, 3, 'Jdell mouse', 'new', 1500.00, 2000.00, 2500.00, NULL, '2025-07-04', 20, '2025-07-04 03:54:47', '2025-07-04 05:02:47'),
(10, 3, 'Keyboard', 'new', 2000.00, 2500.00, 3000.00, 'ce laptop center', '2025-07-04', 13, '2025-07-04 03:55:26', '2025-07-04 07:56:56'),
(11, 3, 'dell mouse', 'new', 1000.00, 2000.00, 3000.00, 'ce laptop center', '2025-07-04', 12, '2025-07-04 04:08:47', '2025-07-04 07:39:01'),
(12, 3, 'rgb mouse', 'new', 1500.00, 2000.00, 3000.00, 'ce laptop center', '2025-07-04', 3, '2025-07-04 04:09:27', '2025-07-04 09:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super-admin','admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `email_verified_at`, `verification_token`) VALUES
(3, 'Admin User', 'admin@celaptop.com', '$2y$12$zkgptxReVd3tfYjUa0cd0.WDwVxXKHIKSfW/5FHCCxKEssaniaO0C', 'user', 1, NULL, '2025-06-28 07:53:09', '2025-07-03 15:52:16', NULL, NULL),
(5, 'Super Admin', 'superadmin@gmail.com', '$2y$12$GqqTz1B0JJOex0QSsD1ideJ6bYP/uvksX5XS5ut984O9dcwkt3Tk.', 'super-admin', 1, NULL, '2025-07-03 04:36:21', '2025-07-03 04:36:21', NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cashiers_user_id_foreign` (`user_id`);

--
-- Indexes for table `completed_repairs`
--
ALTER TABLE `completed_repairs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `completed_repairs_user_id_foreign` (`user_id`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `counters_key_unique` (`key`),
  ADD UNIQUE KEY `counters_key_user_id_unique` (`key`,`user_id`),
  ADD KEY `counters_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_user_id_foreign` (`user_id`);

--
-- Indexes for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_counters_user_id_unique` (`user_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_user_id_foreign` (`user_id`);

--
-- Indexes for table `invoice_with_stocks`
--
ALTER TABLE `invoice_with_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_with_stocks_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoice_with_stocks_user_id_foreign` (`user_id`);

--
-- Indexes for table `invoice_with_stock_items`
--
ALTER TABLE `invoice_with_stock_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_with_stock_items_invoice_with_stock_id_foreign` (`invoice_with_stock_id`);

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
-- Indexes for table `laptop_repairs`
--
ALTER TABLE `laptop_repairs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laptop_repairs_customer_number_unique` (`customer_number`),
  ADD KEY `laptop_repairs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_shop_details`
--
ALTER TABLE `my_shop_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `my_shop_details_user_id_foreign` (`user_id`);

--
-- Indexes for table `note_counters`
--
ALTER TABLE `note_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `note_counters_user_id_unique` (`user_id`);

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
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shops_user_id_foreign` (`user_id`);

--
-- Indexes for table `shop_items`
--
ALTER TABLE `shop_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_items_shop_id_foreign` (`shop_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `completed_repairs`
--
ALTER TABLE `completed_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `invoice_with_stocks`
--
ALTER TABLE `invoice_with_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `invoice_with_stock_items`
--
ALTER TABLE `invoice_with_stock_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `laptop_repairs`
--
ALTER TABLE `laptop_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `my_shop_details`
--
ALTER TABLE `my_shop_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `note_counters`
--
ALTER TABLE `note_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD CONSTRAINT `cashiers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `completed_repairs`
--
ALTER TABLE `completed_repairs`
  ADD CONSTRAINT `completed_repairs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `counters`
--
ALTER TABLE `counters`
  ADD CONSTRAINT `counters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  ADD CONSTRAINT `invoice_counters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_with_stocks`
--
ALTER TABLE `invoice_with_stocks`
  ADD CONSTRAINT `invoice_with_stocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_with_stock_items`
--
ALTER TABLE `invoice_with_stock_items`
  ADD CONSTRAINT `invoice_with_stock_items_invoice_with_stock_id_foreign` FOREIGN KEY (`invoice_with_stock_id`) REFERENCES `invoice_with_stocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laptop_repairs`
--
ALTER TABLE `laptop_repairs`
  ADD CONSTRAINT `laptop_repairs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `my_shop_details`
--
ALTER TABLE `my_shop_details`
  ADD CONSTRAINT `my_shop_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `note_counters`
--
ALTER TABLE `note_counters`
  ADD CONSTRAINT `note_counters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shops_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shop_items`
--
ALTER TABLE `shop_items`
  ADD CONSTRAINT `shop_items_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
