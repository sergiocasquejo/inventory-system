-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2015 at 03:07 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kmagrivate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `sales_branches`
--

CREATE TABLE IF NOT EXISTS `sales_branches` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `post_code` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sales_branches`
--

INSERT INTO `sales_branches` (`id`, `name`, `address`, `city`, `state`, `post_code`, `country`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'KM Agrivate', 'Leyte', 'Leyte', '', '6017', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(6, 'KM Agrivate', 'Cebu', 'Cebu', '', '6017', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(8, 'sdfasdfasdf', 'safs', 'sadfas', '', '234234', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_brands`
--

CREATE TABLE IF NOT EXISTS `sales_brands` (
`brand_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sales_brands`
--

INSERT INTO `sales_brands` (`brand_id`, `name`, `description`) VALUES
(1, 'CJ FEEDS', 'CJ FEEDS'),
(2, 'THUNDER BIRD GAME FOWL FEEDS ', 'THUNDER BIRD GAME FOWL FEEDS '),
(3, 'POULTRY', 'POULTRY'),
(4, 'KUSOG FEEDS', 'KUSOG FEEDS'),
(5, 'MEDICINES', '');

-- --------------------------------------------------------

--
-- Table structure for table `sales_categories`
--

CREATE TABLE IF NOT EXISTS `sales_categories` (
`category_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `image` varchar(120) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sales_categories`
--

INSERT INTO `sales_categories` (`category_id`, `name`, `slug`, `description`, `status`, `image`) VALUES
(1, 'SWINE', '', '', 0, ''),
(2, 'HOG', '', '', 0, ''),
(3, 'AQUA', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `sales_categories_to_brands`
--

CREATE TABLE IF NOT EXISTS `sales_categories_to_brands` (
`category_to_brand_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `brand_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sales_categories_to_brands`
--

INSERT INTO `sales_categories_to_brands` (`category_to_brand_id`, `category_id`, `brand_id`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 2, 1),
(4, 2, 4),
(5, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_credits`
--

CREATE TABLE IF NOT EXISTS `sales_credits` (
`credit_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `customer_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `product` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `uom` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `date_of_credit` date NOT NULL,
  `encoded_by` int(10) unsigned DEFAULT NULL,
  `is_paid` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sales_credits`
--

INSERT INTO `sales_credits` (`credit_id`, `branch_id`, `customer_name`, `address`, `contact_number`, `product`, `quantity`, `uom`, `total_amount`, `comments`, `date_of_credit`, `encoded_by`, `is_paid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 6, 'John Doe', 'Lorem ipsum dolor', '3434-343-343', '5', '1.00', 'sack(s)', '56.00', 'dfasdf', '2015-03-13', 1, 0, '2015-03-13 07:35:32', '2015-03-13 07:35:32', NULL),
(7, 5, 'asdfasd', 'sfas', '4234234-34234-23423', '3', '3.00', 'kg', '180.00', 'asdfasdfsdf', '2015-03-14', 1, 0, '2015-03-13 17:19:45', '2015-03-13 17:19:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_expenses`
--

CREATE TABLE IF NOT EXISTS `sales_expenses` (
`expense_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `expense_type` enum('PRODUCT EXPENSES','STORE EXPENSES') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'STORE EXPENSES',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `uom` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `encoded_by` int(10) unsigned NOT NULL,
  `date_of_expense` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_media`
--

CREATE TABLE IF NOT EXISTS `sales_media` (
`media_id` bigint(20) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `media_order` int(11) NOT NULL,
  `mediable_id` bigint(20) unsigned NOT NULL,
  `mediable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_primary` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_migrations`
--

CREATE TABLE IF NOT EXISTS `sales_migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_migrations`
--

INSERT INTO `sales_migrations` (`migration`, `batch`) VALUES
('2015_02_23_052944_confide_setup_users_table', 1),
('2015_02_23_053205_create_branches_table', 1),
('2015_02_23_054032_create_products_table', 1),
('2015_02_23_054406_create_products_categories_table', 1),
('2015_02_23_054532_create_products_pricing_table', 1),
('2015_02_23_054552_create_stocks_on_hand_table', 1),
('2015_02_23_054616_create_products_to_categories_table', 1),
('2015_02_23_054642_create_unit_of_measures_table', 1),
('2015_02_23_054806_create_expenses_table', 1),
('2015_02_23_054824_create_sales_table', 1),
('2015_02_23_061014_create_media_table', 1),
('2015_02_25_121105_create_brands_table', 1),
('2015_02_25_122024_create_categories_to_brands_table', 1),
('2015_02_25_122347_create_credits_table', 1),
('2015_03_04_082219_create_pricing_history_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_password_reminders`
--

CREATE TABLE IF NOT EXISTS `sales_password_reminders` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_products`
--

CREATE TABLE IF NOT EXISTS `sales_products` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `uom` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `brand_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `encoded_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sales_products`
--

INSERT INTO `sales_products` (`id`, `name`, `description`, `comments`, `uom`, `brand_id`, `category_id`, `status`, `encoded_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Product B', '', '', '["kg","sacks"]', 3, 0, 1, 1, '2015-03-06 05:55:05', '2015-03-12 08:00:04', NULL),
(4, 'Product A', 'adsfasdf', 'asdfasdfadsf', '["kg","sack(s)"]', 2, 0, 1, 1, '2015-03-06 05:57:29', '2015-03-12 08:00:12', NULL),
(5, 'Test A Lorem ipsum', '', '', '["kg","sack(s)"]', 1, 1, 1, 1, '2015-03-06 06:50:41', '2015-03-12 08:15:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_products_to_categories`
--

CREATE TABLE IF NOT EXISTS `sales_products_to_categories` (
`product_to_category_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_product_price_history`
--

CREATE TABLE IF NOT EXISTS `sales_product_price_history` (
`price_history_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `per_unit` varchar(120) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `sales_product_price_history`
--

INSERT INTO `sales_product_price_history` (`price_history_id`, `product_id`, `branch_id`, `supplier_id`, `supplier_price`, `selling_price`, `per_unit`) VALUES
(16, 4, 5, NULL, '100.00', '105.00', 'kg'),
(17, 3, 5, NULL, '56.00', '60.00', 'kg'),
(18, 3, 6, NULL, '65.00', '70.00', 'kg'),
(19, 5, 6, NULL, '44.00', '56.00', 'sack(s)'),
(20, 5, 6, NULL, '56.00', '555.00', 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `sales_product_pricing`
--

CREATE TABLE IF NOT EXISTS `sales_product_pricing` (
`price_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `per_unit` varchar(120) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `sales_product_pricing`
--

INSERT INTO `sales_product_pricing` (`price_id`, `product_id`, `branch_id`, `supplier_id`, `supplier_price`, `selling_price`, `per_unit`) VALUES
(17, 4, 5, NULL, '100.00', '105.00', 'kg'),
(18, 3, 5, NULL, '56.00', '60.00', 'kg'),
(19, 3, 6, NULL, '65.00', '70.00', 'kg'),
(20, 5, 6, NULL, '44.00', '56.00', 'sack(s)'),
(21, 5, 6, NULL, '56.00', '555.00', 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `sales_sales`
--

CREATE TABLE IF NOT EXISTS `sales_sales` (
`sale_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `uom` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `date_of_sale` date NOT NULL,
  `encoded_by` int(10) unsigned DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sales_sales`
--

INSERT INTO `sales_sales` (`sale_id`, `branch_id`, `product_id`, `supplier_price`, `selling_price`, `quantity`, `uom`, `total_amount`, `comments`, `date_of_sale`, `encoded_by`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 5, NULL, '45.00', '56.00', '1.00', 'kg', '56.00', '', '2015-03-12', 2, 1, '2015-03-12 07:10:09', '2015-03-12 08:15:10', NULL),
(3, 5, 4, '100.00', '105.00', '1.00', 'kg', '105.00', 'dfasdf', '2015-03-12', 2, 1, '2015-03-12 08:07:51', '2015-03-12 08:14:39', NULL),
(4, 5, 4, '100.00', '105.00', '1.00', 'kg', '105.00', 'lorem ipsum dolor', '2015-03-13', 3, 1, '2015-03-13 06:35:33', '2015-03-13 06:35:33', NULL),
(5, 5, 4, '100.00', '105.00', '1.00', 'kg', '105.00', 'dfasdf', '2015-03-13', 3, 1, '2015-03-13 06:36:21', '2015-03-13 06:36:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_stocks_on_hand`
--

CREATE TABLE IF NOT EXISTS `sales_stocks_on_hand` (
`stock_on_hand_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `total_stocks` decimal(10,2) unsigned NOT NULL,
  `uom` varchar(120) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Dumping data for table `sales_stocks_on_hand`
--

INSERT INTO `sales_stocks_on_hand` (`stock_on_hand_id`, `product_id`, `branch_id`, `total_stocks`, `uom`) VALUES
(20, 4, 5, '1.56', 'kg'),
(22, 3, 5, '233.00', 'kg'),
(23, 5, 5, '254.00', 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `sales_unit_of_measures`
--

CREATE TABLE IF NOT EXISTS `sales_unit_of_measures` (
`uom_id` int(10) unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `is_decimal` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sales_unit_of_measures`
--

INSERT INTO `sales_unit_of_measures` (`uom_id`, `name`, `label`, `is_decimal`, `deleted_at`) VALUES
(1, 'kg', 'Kilogram', 1, NULL),
(2, 'pack(s)', 'Pack(s)', 0, NULL),
(3, 'pcs', 'Pieces', 0, NULL),
(4, 'sack(s)', 'Sack(s)', 0, NULL),
(5, 'gram', 'Gram(s)', 0, NULL),
(6, 'bottle', 'Bottle', 0, NULL),
(7, 'ml', 'Milliliter', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_users`
--

CREATE TABLE IF NOT EXISTS `sales_users` (
`id` int(10) unsigned NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `contact_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `first_name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sales_users`
--

INSERT INTO `sales_users` (`id`, `username`, `email`, `password`, `display_name`, `contact_no`, `address`, `birthdate`, `first_name`, `last_name`, `is_admin`, `confirmation_code`, `remember_token`, `confirmed`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'owner', 'serg.casquejo@yahoo.com', '$2y$10$PNLtrhJfcZ2RyfjZxS.M8Oy0Jey0Tvxeh0M488sqp4CmCVDjpS0ke', 'john', '88 (02) 123456', '(12) 03 4567890', '1997-01-01', 'John', 'Doe', 1, 'e1e07c1cb3e1d52f198536799c524fb7', 'Ib90WE5td6IHDkDKHokJJu8kBklUbLqA5O8pGyzCfiUgt7Ytx5qOBhrKN16n', 1, 1, 0, '0000-00-00 00:00:00', '2015-03-13 17:28:01', NULL),
(2, 'janedoe', 'janedoe@gmail.com', '$2y$10$mq.BvcQF8TDWXPsewJLBwukJ6JvxBysOjc9KvcJwRzqhxvITAE8q2', 'Jane Doe', '236-635-654', 'Lorem ipsum door sit amit', '2015-03-09', 'Jane', 'Doe', 0, '', 'alfGqbM32GoYv5UJoQeBbm8xZcm0k76G4STjmJGbCkiGsl7lkvroLxF303jS', 1, 1, 5, '2015-03-07 19:22:34', '2015-03-13 17:28:13', NULL),
(3, 'johnsmith', 'johnsmith@gmail.com', '$2y$10$/WNvJXH2az1rTm38NFCVde6fgU3OIIG2TBZzqRQCr13xFsR2DsKdi', 'John Smith', '3434-3432-34234', 'lorem ipsum dolor', '2015-03-13', 'John', 'Smith', 0, '', 'NiqnbZKnBmcTLDMMyj6XFC1rHHfV3zgU8QZGHKZAJ2FedCsX1Mnd7i7zUcvG', 1, 1, 5, '2015-03-13 06:29:29', '2015-03-13 07:02:30', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sales_branches`
--
ALTER TABLE `sales_branches`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `branches_address_unique` (`address`);

--
-- Indexes for table `sales_brands`
--
ALTER TABLE `sales_brands`
 ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `sales_categories`
--
ALTER TABLE `sales_categories`
 ADD PRIMARY KEY (`category_id`), ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `sales_categories_to_brands`
--
ALTER TABLE `sales_categories_to_brands`
 ADD PRIMARY KEY (`category_to_brand_id`), ADD KEY `categories_to_brands_brand_id_foreign` (`brand_id`), ADD KEY `categories_to_brands_category_id_foreign` (`category_id`);

--
-- Indexes for table `sales_credits`
--
ALTER TABLE `sales_credits`
 ADD PRIMARY KEY (`credit_id`), ADD KEY `credits_encoded_by_foreign` (`encoded_by`);

--
-- Indexes for table `sales_expenses`
--
ALTER TABLE `sales_expenses`
 ADD PRIMARY KEY (`expense_id`), ADD KEY `expenses_encoded_by_foreign` (`encoded_by`), ADD KEY `expenses_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sales_media`
--
ALTER TABLE `sales_media`
 ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `sales_products`
--
ALTER TABLE `sales_products`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `products_name_unique` (`name`);

--
-- Indexes for table `sales_products_to_categories`
--
ALTER TABLE `sales_products_to_categories`
 ADD PRIMARY KEY (`product_to_category_id`), ADD KEY `products_to_categories_product_id_foreign` (`product_id`), ADD KEY `products_to_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `sales_product_price_history`
--
ALTER TABLE `sales_product_price_history`
 ADD PRIMARY KEY (`price_history_id`), ADD KEY `product_price_history_product_id_foreign` (`product_id`), ADD KEY `product_price_history_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sales_product_pricing`
--
ALTER TABLE `sales_product_pricing`
 ADD PRIMARY KEY (`price_id`), ADD KEY `product_pricing_product_id_foreign` (`product_id`), ADD KEY `product_pricing_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sales_sales`
--
ALTER TABLE `sales_sales`
 ADD PRIMARY KEY (`sale_id`), ADD KEY `sales_encoded_by_foreign` (`encoded_by`), ADD KEY `sales_product_id_foreign` (`product_id`), ADD KEY `sales_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sales_stocks_on_hand`
--
ALTER TABLE `sales_stocks_on_hand`
 ADD PRIMARY KEY (`stock_on_hand_id`), ADD KEY `stocks_on_hand_product_id_foreign` (`product_id`), ADD KEY `stocks_on_hand_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sales_unit_of_measures`
--
ALTER TABLE `sales_unit_of_measures`
 ADD PRIMARY KEY (`uom_id`), ADD UNIQUE KEY `unit_of_measures_name_unique` (`name`), ADD UNIQUE KEY `unit_of_measures_label_unique` (`label`);

--
-- Indexes for table `sales_users`
--
ALTER TABLE `sales_users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_username_unique` (`username`), ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sales_branches`
--
ALTER TABLE `sales_branches`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `sales_brands`
--
ALTER TABLE `sales_brands`
MODIFY `brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sales_categories`
--
ALTER TABLE `sales_categories`
MODIFY `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sales_categories_to_brands`
--
ALTER TABLE `sales_categories_to_brands`
MODIFY `category_to_brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sales_credits`
--
ALTER TABLE `sales_credits`
MODIFY `credit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sales_expenses`
--
ALTER TABLE `sales_expenses`
MODIFY `expense_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_media`
--
ALTER TABLE `sales_media`
MODIFY `media_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_products`
--
ALTER TABLE `sales_products`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sales_products_to_categories`
--
ALTER TABLE `sales_products_to_categories`
MODIFY `product_to_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_product_price_history`
--
ALTER TABLE `sales_product_price_history`
MODIFY `price_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `sales_product_pricing`
--
ALTER TABLE `sales_product_pricing`
MODIFY `price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `sales_sales`
--
ALTER TABLE `sales_sales`
MODIFY `sale_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sales_stocks_on_hand`
--
ALTER TABLE `sales_stocks_on_hand`
MODIFY `stock_on_hand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `sales_unit_of_measures`
--
ALTER TABLE `sales_unit_of_measures`
MODIFY `uom_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sales_users`
--
ALTER TABLE `sales_users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales_categories_to_brands`
--
ALTER TABLE `sales_categories_to_brands`
ADD CONSTRAINT `categories_to_brands_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `sales_brands` (`brand_id`) ON DELETE CASCADE,
ADD CONSTRAINT `categories_to_brands_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `sales_categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_credits`
--
ALTER TABLE `sales_credits`
ADD CONSTRAINT `credits_encoded_by_foreign` FOREIGN KEY (`encoded_by`) REFERENCES `sales_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_expenses`
--
ALTER TABLE `sales_expenses`
ADD CONSTRAINT `expenses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `sales_branches` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `expenses_encoded_by_foreign` FOREIGN KEY (`encoded_by`) REFERENCES `sales_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_products_to_categories`
--
ALTER TABLE `sales_products_to_categories`
ADD CONSTRAINT `products_to_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `sales_categories` (`category_id`) ON DELETE CASCADE,
ADD CONSTRAINT `products_to_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_product_price_history`
--
ALTER TABLE `sales_product_price_history`
ADD CONSTRAINT `product_price_history_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `sales_branches` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `product_price_history_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_product_pricing`
--
ALTER TABLE `sales_product_pricing`
ADD CONSTRAINT `product_pricing_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `sales_branches` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `product_pricing_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_sales`
--
ALTER TABLE `sales_sales`
ADD CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `sales_branches` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `sales_encoded_by_foreign` FOREIGN KEY (`encoded_by`) REFERENCES `sales_users` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_stocks_on_hand`
--
ALTER TABLE `sales_stocks_on_hand`
ADD CONSTRAINT `stocks_on_hand_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `sales_branches` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `stocks_on_hand_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
