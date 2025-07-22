-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 11:31 AM
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

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'Chammika', 3, '2025-07-06 06:03:05', '2025-07-06 06:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `completed_repairs`
--

CREATE TABLE `completed_repairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `ram` enum('4GB','8GB','12GB','16GB','32GB','64GB') DEFAULT NULL,
  `hdd` tinyint(1) NOT NULL DEFAULT 0,
  `ssd` tinyint(1) NOT NULL DEFAULT 0,
  `nvme` tinyint(1) NOT NULL DEFAULT 0,
  `battery` tinyint(1) NOT NULL DEFAULT 0,
  `dvd_rom` tinyint(1) NOT NULL DEFAULT 0,
  `keyboard` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `completed_repairs`
--

INSERT INTO `completed_repairs` (`id`, `user_id`, `customer_name`, `contact`, `email`, `date`, `fault`, `device`, `repair_price`, `serial_number`, `note_number`, `customer_number`, `status`, `images`, `completed_at`, `created_at`, `updated_at`, `ram`, `hdd`, `ssd`, `nvme`, `battery`, `dvd_rom`, `keyboard`) VALUES
(19, 3, 'kavi', '0775486221', NULL, '2025-07-16', 'no power', 'dell gaming', 2500.00, 'freg4g4t43', '486', 'CE-3-0042', 'completed', NULL, '2025-07-16 13:10:42', '2025-07-16 07:40:42', '2025-07-16 07:40:42', NULL, 0, 0, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `complete_shop_repairs`
--

CREATE TABLE `complete_shop_repairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `repair_item_id` bigint(20) UNSIGNED NOT NULL,
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `final_price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complete_shop_repairs`
--

INSERT INTO `complete_shop_repairs` (`id`, `repair_item_id`, `shop_id`, `user_id`, `final_price`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(4, 11, 4, 3, 2500.00, 'kllk', 'completed', '2025-07-16 07:33:38', '2025-07-16 07:33:38');

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
(4, 3, 'customer_number_user_3', 42, '2025-07-04 07:14:41', '2025-07-16 07:32:23'),
(11, 31, 'customer_number_user_31', 1, '2025-07-07 08:17:19', '2025-07-07 08:17:19'),
(12, 32, 'customer_number_user_32', 1, '2025-07-10 13:30:52', '2025-07-10 13:30:52');

-- --------------------------------------------------------

--
-- Table structure for table `credit_invoices`
--

CREATE TABLE `credit_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `credit_shop_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `sales_rep` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','partial','paid') NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_invoices`
--

INSERT INTO `credit_invoices` (`id`, `invoice_number`, `credit_shop_id`, `customer_name`, `customer_phone`, `sales_rep`, `issue_date`, `total_amount`, `paid_amount`, `remaining_amount`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 'CE-INVCR-0009', 3, NULL, NULL, 'Chammika', '2025-07-16', 2500.00, 0.00, 2500.00, 'pending', 3, '2025-07-16 11:05:59', '2025-07-16 11:05:59');

-- --------------------------------------------------------

--
-- Table structure for table `credit_invoice_items`
--

CREATE TABLE `credit_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `credit_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_invoice_items`
--

INSERT INTO `credit_invoice_items` (`id`, `credit_invoice_id`, `description`, `warranty`, `quantity`, `unit_price`, `amount`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 4, 'kryuih', NULL, 1, 2500.00, 2500.00, 3, '2025-07-16 11:05:59', '2025-07-16 11:05:59');

-- --------------------------------------------------------

--
-- Table structure for table `credit_shops`
--

CREATE TABLE `credit_shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_shops`
--

INSERT INTO `credit_shops` (`id`, `name`, `contact`, `address`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'Pro link', NULL, NULL, 3, '2025-07-16 09:40:55', '2025-07-16 09:40:55'),
(4, 'new borm', '0774522125', 'kegalle', 3, '2025-07-16 10:07:56', '2025-07-16 10:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `sales_rep` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estimates`
--

INSERT INTO `estimates` (`id`, `estimate_number`, `customer_name`, `customer_phone`, `sales_rep`, `issue_date`, `expiry_date`, `total_amount`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'CE-EST-0001', 'kavindu', NULL, 'CE laptop repair center', '2025-07-15', '2025-08-14', 2500.00, 3, '2025-07-15 04:06:21', '2025-07-15 04:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `estimate_counters`
--

CREATE TABLE `estimate_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `last_number` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estimate_counters`
--

INSERT INTO `estimate_counters` (`id`, `user_id`, `last_number`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-07-15 04:06:21', '2025-07-15 04:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `estimate_items`
--

CREATE TABLE `estimate_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `warranty` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estimate_items`
--

INSERT INTO `estimate_items` (`id`, `estimate_id`, `description`, `warranty`, `quantity`, `unit_price`, `amount`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'laptop', '1 month', 1, 2500.00, 2500.00, 3, '2025-07-15 04:06:21', '2025-07-15 04:06:21');

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
(38, 'CE-INV-0008', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-14', 1111.00, '2025-07-14 03:26:27', '2025-07-14 03:26:27', 3);

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
(1, 3, 9, '2025-07-04 07:54:04', '2025-07-16 11:05:59');

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
(53, 38, 'Laptop asus', '1 montrh', 1, 1111.00, 1111.00, '2025-07-14 03:26:27', '2025-07-14 03:26:27', 3);

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
  `email` varchar(255) DEFAULT NULL,
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
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ram` enum('4GB','8GB','12GB','16GB','32GB','64GB') DEFAULT NULL,
  `hdd` tinyint(1) NOT NULL DEFAULT 0,
  `ssd` tinyint(1) NOT NULL DEFAULT 0,
  `nvme` tinyint(1) NOT NULL DEFAULT 0,
  `battery` tinyint(1) NOT NULL DEFAULT 0,
  `dvd_rom` tinyint(1) NOT NULL DEFAULT 0,
  `keyboard` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laptop_repairs`
--

INSERT INTO `laptop_repairs` (`id`, `customer_name`, `contact`, `email`, `date`, `fault`, `device`, `repair_price`, `serial_number`, `status`, `images`, `customer_number`, `created_at`, `updated_at`, `note_number`, `user_id`, `ram`, `hdd`, `ssd`, `nvme`, `battery`, `dvd_rom`, `keyboard`) VALUES
(74, 'Kavindu Nelshan', '0765645303', NULL, '2025-07-07', 'kjjj', 'asus', NULL, '234765889995t5t', 'pending', '[\"repairs\\/6q5omVYPQLUs0HydVIP2SxeUxJPjO7IGNXjXGrF9.jpg\"]', 'XX-31-0001', '2025-07-07 08:17:19', '2025-07-07 08:17:19', '425', 31, NULL, 0, 0, 0, 0, 0, 0),
(98, 'Kavindu Nelshan', '0765645303', 'jayathissa1999max@gmail.com', '2025-07-10', 'no power', 'asushhg', NULL, 'yeryeu46u68tyhh', 'pending', NULL, 'XX-32-0001', '2025-07-10 13:30:52', '2025-07-10 13:30:52', '500', 32, NULL, 0, 0, 0, 0, 0, 0);

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
(55, '2025_07_05_145359_create_payments_table', 47),
(56, '2025_07_07_140603_add_email_to_laptop_repairs_table', 48),
(57, '2025_07_07_180800_add_email_to_completed_repairs_table', 49),
(58, '2025_07_07_190011_create_user_email_settings_table', 50),
(59, '2025_07_10_090517_create_shop_names_table', 51),
(60, '2025_07_10_093623_create_repair_items_table', 52),
(61, '2025_07_10_102006_create_repair_items_table', 53),
(62, '2025_07_10_102706_add_specs_to_repair_items_table', 54),
(63, '2025_07_10_110958_create_complete_shop_repairs_table', 55),
(64, '2025_07_10_133057_add_specs_fields_to_laptop_repairs_table', 56),
(65, '2025_07_10_133509_add_specs_fields_to_completed_repairs_table', 57),
(66, '2025_07_10_173115_add_smtp_fields_to_users_table', 58),
(67, '2025_07_15_091310_create_estimates_table', 59),
(68, '2025_07_15_091412_create_estimate_items_table', 59),
(69, '2025_07_15_091433_create_estimate_counters_table', 59),
(70, '2025_07_16_143504_create_credit_shops_table', 60),
(71, '2025_07_16_151629_create_credit_invoices_table', 61),
(72, '2025_07_16_151804_create_credit_invoice_items_table', 61),
(73, '2025_07_18_090844_add_permissions_column_to_users_table', 62);

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
(3, 'CE laptop repair center', 'Dealers in Desktop Computers, Laptop Accessories, Repairs Service & import wholesale and retail', '254/1/1/6, Baladaksha Mawatha, New Sathipola Road, Mawanella, Sri Lanka', '0756502913', 'chammikaelectronic@gmail.com', 'shop_logos/HqBIVX2YUwXm1TTrAiF4aB6p2zZxzCnpZws2jLe8.jpg', 'Warranty for 1 Year Less 21 Working Days for computer hardware.', 'No Warranty for Burn Marks, Scratches, Physical damages and any other damage happened by user activities.', 'Goods sold once can\'t return.', '2025-07-03 08:04:04', '2025-07-07 16:12:07', 3);

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `name`, `amount`, `payment_method`, `bank_name`, `account_number`, `slip_path`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(6, 31, 'Kavindu Nelshan', 55000.00, 'bank_transfer', 'BOC', '78457777', 'payment_slips/UC0pvi4nGgEXYP6jYKQ7SKFquBDkLQYhdqaLW1V2.pdf', 'iwkhwk', 'pending', '2025-07-07 07:18:17', '2025-07-07 07:18:17');

-- --------------------------------------------------------

--
-- Table structure for table `repair_items`
--

CREATE TABLE `repair_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_number` varchar(255) NOT NULL,
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `date` date NOT NULL DEFAULT '2025-07-10',
  `status` enum('pending','in_progress','completed','canceled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ram` enum('4GB','8GB','12GB','16GB','32GB','64GB') DEFAULT NULL,
  `hdd` tinyint(1) NOT NULL DEFAULT 0,
  `ssd` tinyint(1) NOT NULL DEFAULT 0,
  `nvme` tinyint(1) NOT NULL DEFAULT 0,
  `battery` tinyint(1) NOT NULL DEFAULT 0,
  `dvd_rom` tinyint(1) NOT NULL DEFAULT 0,
  `keyboard` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repair_items`
--

INSERT INTO `repair_items` (`id`, `item_number`, `shop_id`, `item_name`, `price`, `description`, `serial_number`, `date`, `status`, `created_at`, `updated_at`, `ram`, `hdd`, `ssd`, `nvme`, `battery`, `dvd_rom`, `keyboard`) VALUES
(9, 'CE-3-0001', 3, 'laptop', NULL, 'no power', '4ergg', '2025-07-16', 'completed', '2025-07-16 05:00:04', '2025-07-16 05:15:38', '12GB', 1, 0, 0, 0, 1, 1),
(10, 'PO-4-0001', 4, 'asus', NULL, 'mother bord replace', '4erggt3rt', '2025-07-15', 'in_progress', '2025-07-16 05:00:54', '2025-07-16 18:46:57', '16GB', 0, 1, 0, 0, 1, 1),
(11, 'PO-4-0002', 4, 'asus', NULL, 'no power', '4erggt3rt', '2025-07-16', 'completed', '2025-07-16 07:31:40', '2025-07-16 07:33:38', NULL, 0, 1, 0, 0, 1, 1),
(12, 'PO-4-0003', 4, 'laptop', 2500.00, 'niii', '4ergg', '2025-07-17', 'pending', '2025-07-16 19:17:18', '2025-07-16 19:17:18', '12GB', 0, 1, 0, 0, 1, 1),
(13, 'HJ-5-0001', 5, 'laptop', 2500.00, 'nbvvhb', '4ergg', '2025-07-17', 'pending', '2025-07-16 19:32:54', '2025-07-16 19:33:57', '16GB', 1, 0, 0, 0, 0, 0),
(14, 'HJ-5-0002', 5, 'laptop', 2500.00, 'fghftdg', '4ergg', '2025-07-17', 'pending', '2025-07-16 19:37:23', '2025-07-16 19:37:23', '16GB', 1, 0, 0, 0, 1, 0),
(15, 'HJ-5-0003', 5, 'laptopjyrjydr', 50000.00, 'sfdsr', '4ergg', '2025-07-14', 'pending', '2025-07-16 19:37:59', '2025-07-16 19:49:01', '8GB', 1, 0, 0, 1, 0, 0),
(16, 'KA-6-0001', 6, 'laptopjyrjydr', 2500.00, 'gfrgtre', '4ergg', '2025-07-17', 'pending', '2025-07-16 19:50:09', '2025-07-16 19:50:09', NULL, 1, 1, 0, 0, 0, 0),
(17, 'KA-6-0002', 6, 'laptopjyrjydr', 2500.00, 'dfegedrfg', '4ergg', '2025-07-17', 'pending', '2025-07-16 19:52:31', '2025-07-16 19:52:31', '8GB', 1, 0, 0, 0, 0, 0),
(18, 'KA-6-0003', 6, 'laptopjyrjydr', 2500.00, NULL, '4erggfg', '2025-07-17', 'pending', '2025-07-16 19:53:38', '2025-07-16 19:53:38', '12GB', 1, 0, 0, 0, 0, 0);

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
('bJbp2LAna97QFKsOZnjTEtEZXbE9Rmxyl1jyVbBB', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidkxCZVRFV0lKWk9JRlFkbXlEN1VWYnhGMWgzZ1hWT2xrMWdNYmp5RSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1752816784);

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
(5, 3, 'CE laptop repair center', '0765645303', '2025-07-04 09:59:44', '2025-07-04 09:59:44'),
(7, 3, 'CE laptop repair center', '0765645303', '2025-07-07 16:25:38', '2025-07-07 16:25:38');

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
(25, 5, 'lap', 'newiiyi', NULL, 'bhgjhmv', 2500.00, '2025-07-01', '2025-07-06 06:23:38', '2025-07-06 06:23:38'),
(44, 4, 'lap', 'no powerggh', NULL, 'wtey', 2500.00, '2025-06-21', '2025-07-06 06:44:06', '2025-07-06 06:44:06'),
(45, 4, '67e575', '7575e7', NULL, NULL, NULL, '2025-07-06', '2025-07-06 06:44:06', '2025-07-06 06:44:06'),
(48, 7, 'lap', 'ra dlkfjfhaekfj', NULL, NULL, NULL, '2025-07-06', '2025-07-07 16:34:32', '2025-07-07 16:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `shop_names`
--

CREATE TABLE `shop_names` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_names`
--

INSERT INTO `shop_names` (`id`, `name`, `contact`, `address`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'cer', '0765645303', NULL, 3, '2025-07-16 04:59:39', '2025-07-16 04:59:39'),
(4, 'power link', '0774258978', NULL, 3, '2025-07-16 05:00:30', '2025-07-16 19:16:55'),
(5, 'hjg', '0774258978', 'nbv m', 3, '2025-07-16 19:32:32', '2025-07-16 19:32:41'),
(6, 'kavindu nelshan', '0765645303', 'jjh', 3, '2025-07-16 19:49:46', '2025-07-16 19:49:46');

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
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_encryption` varchar(255) DEFAULT NULL,
  `email_username` varchar(255) DEFAULT NULL,
  `email_password` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `remember_token`, `permissions`, `created_at`, `updated_at`, `email_verified_at`, `verification_token`, `smtp_host`, `smtp_port`, `smtp_encryption`, `email_username`, `email_password`) VALUES
(3, 'Admin User', 'admin@celaptop.com', '$2y$12$zkgptxReVd3tfYjUa0cd0.WDwVxXKHIKSfW/5FHCCxKEssaniaO0C', 'user', 1, NULL, NULL, '2025-06-28 07:53:09', '2025-07-10 15:17:38', NULL, NULL, 'smtp.gmail.com', 587, 'tls', 'www.kavi1999maxnelshan@gmail.com', 'eyJpdiI6IkNMKzFnb1RnOGErUnI0MW1zTXhGckE9PSIsInZhbHVlIjoiL1lFdlhSK2pMcHBLTWh2UDF2cTl3M2MwZFVhUWJsRENsaXFUV09xOUlNND0iLCJtYWMiOiIwZTZhY2FlOWFkYjk4ZWFhMzYyZmJhMGQ2OTY4NDdhNGM2N2YzYzkzYTU5MmE4ZmFmZTk0M2FkMmM0MGM4NWQ1IiwidGFnIjoiIn0='),
(5, 'Super Admin', 'superadmin@gmail.com', '$2y$12$GqqTz1B0JJOex0QSsD1ideJ6bYP/uvksX5XS5ut984O9dcwkt3Tk.', 'super-admin', 1, NULL, NULL, '2025-07-03 04:36:21', '2025-07-03 04:36:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'Kavindu Nelshan', 'www.kavi1999maxnelshan@gmail.com', '$2y$12$sxwrffpy6HqYmpuCMqXGxukhgrptM2tpXcbT70FhQrB2BQHiGwK52', 'user', 1, NULL, '[\"Invoice\",\"Invoice With Stock\"]', '2025-07-07 07:18:17', '2025-07-18 05:09:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'kavindu nelshan', 'test3@gmail.com', '$2y$12$xo08Mm5.hngQBufYFTnn9.ZBwE1xhc9ST.9/Q3Z8g/1PA5o7LpxDa', 'user', 1, NULL, NULL, '2025-07-10 08:29:15', '2025-07-18 04:04:27', NULL, NULL, 'smtp.gmail.com', 587, 'tls', 'www.kavi1999maxnelshan@gmail.com', 'eyJpdiI6IkF0L000WllRdk1NWWcrb0pPRVBMTXc9PSIsInZhbHVlIjoiMjdjcXF6QmpzczZtaUZPTXpnWndlblBNVUtwOWpTTFhoYnpzZGJBRit5Zz0iLCJtYWMiOiI5YTRmNjkxMjg0NTQ5MjFjZDhkMDYxZTYyNzFkN2UzYTlmMmU0MTE5ODY3NDRkNWMxMGU0N2FmMDU2MTgxYTRmIiwidGFnIjoiIn0='),
(33, 'navod', 'navod@gmail.com', '$2y$12$BplcAax/5obhd78WJIAsWOTXODYuiZaPb4/WVCVF6Ug68u7Pix//W', 'user', 1, NULL, '[\"Invoice\",\"Invoice With Stock\",\"Estimates\",\"Credit Shops\",\"Credit Invoices\",\"Invoice Report\",\"Laptop Repair\"]', '2025-07-18 04:52:34', '2025-07-18 05:01:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_email_settings`
--

CREATE TABLE `user_email_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `complete_shop_repairs`
--
ALTER TABLE `complete_shop_repairs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complete_shop_repairs_repair_item_id_foreign` (`repair_item_id`),
  ADD KEY `complete_shop_repairs_shop_id_foreign` (`shop_id`),
  ADD KEY `complete_shop_repairs_user_id_foreign` (`user_id`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `counters_key_unique` (`key`),
  ADD UNIQUE KEY `counters_key_user_id_unique` (`key`,`user_id`),
  ADD KEY `counters_user_id_foreign` (`user_id`);

--
-- Indexes for table `credit_invoices`
--
ALTER TABLE `credit_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credit_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `credit_invoices_credit_shop_id_foreign` (`credit_shop_id`),
  ADD KEY `credit_invoices_user_id_foreign` (`user_id`);

--
-- Indexes for table `credit_invoice_items`
--
ALTER TABLE `credit_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_invoice_items_credit_invoice_id_foreign` (`credit_invoice_id`),
  ADD KEY `credit_invoice_items_user_id_foreign` (`user_id`);

--
-- Indexes for table `credit_shops`
--
ALTER TABLE `credit_shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_shops_user_id_foreign` (`user_id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estimates_estimate_number_unique` (`estimate_number`),
  ADD KEY `estimates_user_id_foreign` (`user_id`);

--
-- Indexes for table `estimate_counters`
--
ALTER TABLE `estimate_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estimate_counters_user_id_unique` (`user_id`);

--
-- Indexes for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estimate_items_estimate_id_foreign` (`estimate_id`),
  ADD KEY `estimate_items_user_id_foreign` (`user_id`);

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
-- Indexes for table `repair_items`
--
ALTER TABLE `repair_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_items_shop_id_index` (`shop_id`),
  ADD KEY `repair_items_item_number_index` (`item_number`);

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
-- Indexes for table `shop_names`
--
ALTER TABLE `shop_names`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_names_user_id_foreign` (`user_id`);

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
-- Indexes for table `user_email_settings`
--
ALTER TABLE `user_email_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_email_settings_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `completed_repairs`
--
ALTER TABLE `completed_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `complete_shop_repairs`
--
ALTER TABLE `complete_shop_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `credit_invoices`
--
ALTER TABLE `credit_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `credit_invoice_items`
--
ALTER TABLE `credit_invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `credit_shops`
--
ALTER TABLE `credit_shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `estimate_counters`
--
ALTER TABLE `estimate_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `estimate_items`
--
ALTER TABLE `estimate_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `invoice_with_stocks`
--
ALTER TABLE `invoice_with_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `invoice_with_stock_items`
--
ALTER TABLE `invoice_with_stock_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `laptop_repairs`
--
ALTER TABLE `laptop_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `repair_items`
--
ALTER TABLE `repair_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `shop_names`
--
ALTER TABLE `shop_names`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_email_settings`
--
ALTER TABLE `user_email_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `complete_shop_repairs`
--
ALTER TABLE `complete_shop_repairs`
  ADD CONSTRAINT `complete_shop_repairs_repair_item_id_foreign` FOREIGN KEY (`repair_item_id`) REFERENCES `repair_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complete_shop_repairs_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shop_names` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complete_shop_repairs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `counters`
--
ALTER TABLE `counters`
  ADD CONSTRAINT `counters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_invoices`
--
ALTER TABLE `credit_invoices`
  ADD CONSTRAINT `credit_invoices_credit_shop_id_foreign` FOREIGN KEY (`credit_shop_id`) REFERENCES `credit_shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_invoice_items`
--
ALTER TABLE `credit_invoice_items`
  ADD CONSTRAINT `credit_invoice_items_credit_invoice_id_foreign` FOREIGN KEY (`credit_invoice_id`) REFERENCES `credit_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_invoice_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_shops`
--
ALTER TABLE `credit_shops`
  ADD CONSTRAINT `credit_shops_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estimates`
--
ALTER TABLE `estimates`
  ADD CONSTRAINT `estimates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estimate_counters`
--
ALTER TABLE `estimate_counters`
  ADD CONSTRAINT `estimate_counters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD CONSTRAINT `estimate_items_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estimate_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `repair_items`
--
ALTER TABLE `repair_items`
  ADD CONSTRAINT `repair_items_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shop_names` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `shop_names`
--
ALTER TABLE `shop_names`
  ADD CONSTRAINT `shop_names_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_email_settings`
--
ALTER TABLE `user_email_settings`
  ADD CONSTRAINT `user_email_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
