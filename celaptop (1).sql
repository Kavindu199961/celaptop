-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 09:14 AM
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
-- Table structure for table `completed_repairs`
--

CREATE TABLE `completed_repairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `completed_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `completed_repairs`
--

INSERT INTO `completed_repairs` (`id`, `customer_name`, `contact`, `date`, `fault`, `device`, `repair_price`, `serial_number`, `note_number`, `customer_number`, `status`, `completed_at`, `created_at`, `updated_at`) VALUES
(7, 'kavindu', '0765645303', '2025-06-29', 'fererg', 'dell', 65000.00, '12hghjer78542649freg', '438', 'CE-0008', 'completed', '2025-06-29 16:36:18', '2025-06-29 11:06:18', '2025-06-29 11:06:18'),
(8, 'Kavindu', '0765645303', '2025-07-01', 'no power', 'Dell', 2500.00, '1245del234rtyun', '426', 'CE-0015', 'completed', '2025-07-01 09:19:36', '2025-07-01 03:49:36', '2025-07-01 03:49:36');

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE `counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counters`
--

INSERT INTO `counters` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'customer_number', 19, '2025-06-28 09:48:38', '2025-07-02 05:51:24');

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
  `customer_phone` varchar(255) NOT NULL,
  `sales_rep` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_name`, `customer_phone`, `sales_rep`, `issue_date`, `total_amount`, `created_at`, `updated_at`) VALUES
(10, 'INV-000001', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-01', 50000.00, '2025-07-01 04:39:27', '2025-07-01 04:39:27'),
(11, 'INV-000011', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-01', 50000.00, '2025-07-01 06:31:45', '2025-07-01 06:31:45'),
(12, 'INV-000012', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-01', 50000.00, '2025-07-01 09:06:40', '2025-07-01 09:06:40'),
(13, 'INV-000013', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-02', 1288788.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(14, 'INV-000014', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-02', 5000.00, '2025-07-02 05:14:38', '2025-07-02 05:14:38'),
(15, 'INV-000015', 'Kavindu Nelshan', '0765645303', 'Chammika', '2025-07-02', 44996.00, '2025-07-02 05:17:31', '2025-07-02 05:17:31');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `warranty`, `quantity`, `unit_price`, `amount`, `created_at`, `updated_at`) VALUES
(15, 10, 'Laptop asus', '1 montrh', 1, 50000.00, 50000.00, '2025-07-01 04:39:27', '2025-07-01 04:39:27'),
(16, 11, 'Laptop asus', '1 montrh', 1, 50000.00, 50000.00, '2025-07-01 06:31:45', '2025-07-01 06:31:45'),
(17, 12, 'Laptop asus', '1 montrh', 1, 50000.00, 50000.00, '2025-07-01 09:06:40', '2025-07-01 09:06:40'),
(18, 13, 'hh', '-', 2, 2500.00, 5000.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(19, 13, 'uru', '-', 1, 111.00, 111.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(20, 13, '66768', '-', 2, 2222.00, 4444.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(21, 13, 'ououi', '-', 1, 1111.00, 1111.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(22, 13, 'errt', '-', 5, 5555.00, 27775.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(23, 13, 'qwewr', '-', 10, 1000.00, 10000.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(24, 13, 'jyut876', '-', 5, 78.00, 390.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(25, 13, 'tfutut', '-', 1, 250.00, 250.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(26, 13, 'rtutyi67', '-', 5, 855.00, 4275.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(27, 13, 'teryey5', '-', 7, 7777.00, 54439.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(28, 13, 'ryrutru', '-', 8, 8888.00, 71104.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(29, 13, '5eyru', '-', 333, 3333.00, 1109889.00, '2025-07-02 05:02:17', '2025-07-02 05:02:17'),
(30, 14, 'hh', NULL, 2, 2500.00, 5000.00, '2025-07-02 05:14:38', '2025-07-02 05:14:38'),
(31, 15, 'hh', NULL, 2, 2500.00, 5000.00, '2025-07-02 05:17:31', '2025-07-02 05:17:31'),
(32, 15, 'Laptop asus', NULL, 6, 6666.00, 39996.00, '2025-07-02 05:17:31', '2025-07-02 05:17:31');

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
  `customer_number` varchar(255) NOT NULL DEFAULT 'CE-0001',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laptop_repairs`
--

INSERT INTO `laptop_repairs` (`id`, `customer_name`, `contact`, `date`, `fault`, `device`, `repair_price`, `serial_number`, `status`, `customer_number`, `created_at`, `updated_at`, `note_number`) VALUES
(27, 'Kavindu', '0765645303', '2025-07-01', 'no puwer', 'Dell', 0.00, '1245del234rtyun', 'pending', 'CE-0016', '2025-07-01 06:27:01', '2025-07-02 04:32:47', '427'),
(28, 'Kavindu', '0765645303', '2025-07-01', 'efere', 'Dell', 15000.00, '1245del234rtyunp', 'pending', 'CE-0017', '2025-07-01 12:30:53', '2025-07-01 12:31:00', '428'),
(29, 'Kavindu Nelshan', '0765645303', '2025-07-02', '34t5y', 'Dell', 0.00, 'y5688636', 'pending', 'CE-0018', '2025-07-02 05:48:50', '2025-07-02 05:48:50', '429'),
(30, 'janith', '0765645303', '2025-07-02', 'trtrhrth', 'asus', NULL, 'e33rhhetth', 'pending', 'CE-0019', '2025-07-02 05:51:24', '2025-07-02 05:51:24', '430');

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
(31, '2025_07_01_123139_create_shop_items_table', 23);

-- --------------------------------------------------------

--
-- Table structure for table `note_counters`
--

CREATE TABLE `note_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `note_counters`
--

INSERT INTO `note_counters` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'note_number', 430, '2025-07-01 03:45:36', '2025-07-02 05:51:24');

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
('GHHfGSbVaJ1tn71Ff0tRYKqnqv2X5QK5HODjuhdT', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiazY4SDR2RnBuWk9MRUxmMTJseUZ4SGYydlhCYkF3VVNkUVBPdzlPciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1751440216);

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `shop_name`, `phone_number`, `created_at`, `updated_at`) VALUES
(1, 'Chama', '0765645303', '2025-07-01 07:11:17', '2025-07-01 07:11:17'),
(2, 'Chama', '0765645303', '2025-07-01 08:16:32', '2025-07-01 08:16:32');

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
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL DEFAULT '2025-07-01',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_items`
--

INSERT INTO `shop_items` (`id`, `shop_id`, `item_name`, `description`, `warranty`, `serial_number`, `price`, `date`, `created_at`, `updated_at`) VALUES
(8, 2, 'laploknggggg', 't54t544', '6 mounth', '444trg4', 4444.00, '2025-06-27', '2025-07-01 12:24:20', '2025-07-01 12:24:20'),
(9, 1, 'lap', 'evfev', '6 mounth', 'vfeve', 2500.00, '2025-06-30', '2025-07-02 04:30:34', '2025-07-02 04:30:34'),
(10, 1, 'monitor', 'ederfr3', '1 mounth', 'crvgtgth', 875222.00, '2025-06-30', '2025-07-02 04:30:34', '2025-07-02 04:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `whole_sale_price` decimal(10,2) NOT NULL,
  `retail_price` decimal(10,2) NOT NULL,
  `vender` varchar(255) NOT NULL,
  `stock_date` date NOT NULL DEFAULT curdate(),
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `item_name`, `description`, `cost`, `whole_sale_price`, `retail_price`, `vender`, `stock_date`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 'laptop22', 'hcg', 100.00, 2500.00, 5500.00, 'jbb', '2025-06-30', 100, '2025-06-30 16:28:36', '2025-07-01 06:23:34'),
(2, 'laptop', 'hbvvm', 100.00, 150.00, 3000.00, 'jbb', '2025-07-01', 100, '2025-07-01 03:48:28', '2025-07-01 03:48:28');

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
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Admin User', 'admin@celaptop.com', NULL, '$2y$12$zkgptxReVd3tfYjUa0cd0.WDwVxXKHIKSfW/5FHCCxKEssaniaO0C', 'admin', 1, NULL, '2025-06-28 07:53:09', '2025-06-28 07:53:09');

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
-- Indexes for table `completed_repairs`
--
ALTER TABLE `completed_repairs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `counters_key_unique` (`key`);

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
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

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
  ADD UNIQUE KEY `laptop_repairs_customer_number_unique` (`customer_number`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `note_counters`
--
ALTER TABLE `note_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `note_counters_key_unique` (`key`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `completed_repairs`
--
ALTER TABLE `completed_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laptop_repairs`
--
ALTER TABLE `laptop_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `note_counters`
--
ALTER TABLE `note_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shop_items`
--
ALTER TABLE `shop_items`
  ADD CONSTRAINT `shop_items_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
