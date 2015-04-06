-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2015 at 06:39 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_branches`
--

INSERT INTO `sales_branches` (`id`, `name`, `address`, `city`, `state`, `post_code`, `country`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'KM Agrivet', 'Leyte', 'Leyte', '', '6017', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(6, 'KM Agrivate', 'Cebu', 'Cebu', '', '434', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015-03-07 19:08:10'),
(7, 'KM Agrivet', 'Barangay Gacat, BayBay City', 'Baybay', '', '6017', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_brands`
--

CREATE TABLE IF NOT EXISTS `sales_brands` (
`brand_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_categories`
--

INSERT INTO `sales_categories` (`category_id`, `name`, `slug`, `description`, `status`, `image`) VALUES
(2, 'HOG/SWINE', '', '', 0, ''),
(3, 'AQUA', '', '', 0, ''),
(4, 'adsfdsa', '', 'xxx', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `sales_categories_to_brands`
--

CREATE TABLE IF NOT EXISTS `sales_categories_to_brands` (
`category_to_brand_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `brand_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_categories_to_brands`
--

INSERT INTO `sales_categories_to_brands` (`category_to_brand_id`, `category_id`, `brand_id`) VALUES
(3, 2, 1),
(5, 3, 1),
(6, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_credits`
--

CREATE TABLE IF NOT EXISTS `sales_credits` (
`credit_id` bigint(20) unsigned NOT NULL,
  `sale_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `is_paid` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_credits`
--

INSERT INTO `sales_credits` (`credit_id`, `sale_id`, `customer_id`, `is_paid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 55, 1, 0, '2015-03-25 04:25:32', '2015-03-25 04:25:32', NULL),
(2, 58, 1, 0, '2015-03-25 06:39:22', '2015-03-25 06:39:22', NULL),
(3, 59, 12, 0, '2015-03-25 07:27:11', '2015-03-25 07:27:11', NULL),
(4, 64, 1, 0, '2015-03-26 05:00:27', '2015-03-26 05:00:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_customers`
--

CREATE TABLE IF NOT EXISTS `sales_customers` (
`customer_id` bigint(20) NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `customer_name` varchar(70) NOT NULL,
  `address` varchar(120) NOT NULL,
  `contact_no` varchar(30) NOT NULL,
  `total_credits` decimal(10,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales_customers`
--

INSERT INTO `sales_customers` (`customer_id`, `branch_id`, `customer_name`, `address`, `contact_no`, `total_credits`) VALUES
(1, 7, 'John Doe', 'Lorem ipsum dolor', '', '0.00'),
(3, 7, 'Jane Smith 2', 'ipsum', '444-444-444', '0.00'),
(12, 5, 'jane smith', 'dfasdf', '123456789', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `sales_expenses`
--

CREATE TABLE IF NOT EXISTS `sales_expenses` (
`expense_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `expense_type` enum('PRODUCT EXPENSES','STORE EXPENSES') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'STORE EXPENSES',
  `is_payable` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `is_payable_paid` tinyint(4) NOT NULL DEFAULT '0',
  `stock_on_hand_id` bigint(20) unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_expenses`
--

INSERT INTO `sales_expenses` (`expense_id`, `branch_id`, `expense_type`, `is_payable`, `brand_id`, `supplier_id`, `is_payable_paid`, `stock_on_hand_id`, `name`, `total_amount`, `quantity`, `uom`, `comments`, `status`, `encoded_by`, `date_of_expense`, `created_at`, `updated_at`, `deleted_at`) VALUES
(48, 7, 'PRODUCT EXPENSES', 1, 2, 3, 0, 21, '4', '1000.00', '1.00', 'kg', 'test', 0, 1, '2015-03-30', '2015-03-30 06:28:03', '2015-04-03 06:06:49', '2015-04-03 06:06:49'),
(61, 5, 'PRODUCT EXPENSES', 0, 0, 4, 0, 18, '4', '30.00', '1.00', 'kg', '', 0, 1, '2015-04-01', '2015-04-01 05:45:20', '2015-04-01 05:45:20', NULL),
(62, 5, 'PRODUCT EXPENSES', 0, 0, 4, 0, 0, '1', '186.00', '2.00', 'pack(s)', '', 0, 1, '2015-04-01', '2015-04-01 06:17:23', '2015-04-01 06:17:23', NULL),
(63, 5, 'PRODUCT EXPENSES', 0, 0, 4, 0, 18, '4', '30.00', '1.00', 'kg', 'fasdfsdf', 0, 1, '2015-04-01', '2015-04-01 07:01:06', '2015-04-01 07:01:06', NULL),
(65, 7, 'PRODUCT EXPENSES', 0, 0, 3, 0, 0, '2', '500.00', '1.00', 'sack(s)', '1', 0, 1, '2015-04-03', '2015-04-03 06:02:10', '2015-04-03 06:02:10', NULL),
(66, 7, 'PRODUCT EXPENSES', 1, 0, 4, 0, 0, '1', '178.00', '2.00', 'pack(s)', 'fsadf', 0, 1, '2015-04-03', '2015-04-03 06:03:17', '2015-04-03 06:03:17', NULL),
(67, 7, 'PRODUCT EXPENSES', 1, 0, 3, 0, 0, '2', '45.00', '1.00', 'kg', '', 0, 1, '2015-04-03', '2015-04-03 06:04:29', '2015-04-03 06:06:42', '2015-04-03 06:06:42'),
(68, 5, 'PRODUCT EXPENSES', 1, 2, 4, 0, 18, '4', '1000.00', '1.00', 'kg', 'test', 0, 1, '2015-04-03', '2015-04-03 06:08:36', '2015-04-03 06:08:36', NULL),
(69, 5, 'PRODUCT EXPENSES', 1, 0, 4, 0, 0, '1', '93.00', '1.00', 'pack(s)', 'tst', 0, 1, '2015-04-06', '2015-04-06 04:42:42', '2015-04-06 04:42:42', NULL),
(71, 5, 'STORE EXPENSES', 0, 0, 0, 0, 0, '1', '159.00', '0.00', NULL, '', 0, 1, '2015-04-06', '2015-04-06 04:50:22', '2015-04-06 04:50:22', NULL),
(72, 5, 'STORE EXPENSES', 0, 0, 0, 0, 0, 'fasdfsdf', '3433.00', '0.00', NULL, 'dsfasdf', 0, 1, '2015-04-06', '2015-04-06 04:52:48', '2015-04-06 04:52:48', NULL),
(74, 5, 'STORE EXPENSES', 0, 0, 0, 0, 0, 'dfasdf', '333.00', '0.00', NULL, '', 0, 1, '2015-04-06', '2015-04-06 04:54:50', '2015-04-06 04:54:50', NULL),
(75, 5, 'STORE EXPENSES', 0, 0, 0, 0, 0, 'dfasdf', '333.00', '0.00', NULL, '', 0, 1, '2015-04-06', '2015-04-06 04:54:58', '2015-04-06 04:54:58', NULL),
(76, 5, 'STORE EXPENSES', 0, 0, 0, 0, 0, 'dfasdf', '333.00', '0.00', NULL, '', 0, 1, '2015-04-06', '2015-04-06 04:55:31', '2015-04-06 04:55:31', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `supplier_id` int(11) NOT NULL,
  `brand_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `encoded_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_products`
--

INSERT INTO `sales_products` (`id`, `name`, `description`, `comments`, `uom`, `supplier_id`, `brand_id`, `category_id`, `status`, `encoded_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Piggy Max Supreme Booster', 'For 5 days after birth', 'test', '["pack(s)"]', 4, 1, 2, 1, 1, '2015-03-05 08:25:10', '2015-03-30 05:01:26', NULL),
(2, 'Piggy Max Supreme  Pre-Starter', 'For 30 days after birth', '', '["kg","sack(s)"]', 3, 1, 2, 1, 1, '2015-03-05 08:28:49', '2015-03-30 05:01:43', NULL),
(4, 'Hog Supreme Starter', 'For 45 days after birth', 'For 45 days after birth', '["kg","sack(s)"]', 4, 1, 2, 1, 1, '2015-03-06 05:57:29', '2015-03-30 05:01:52', NULL),
(5, 'Piggy Max Supreme Pre-Starter', 'lorem ipsum dolor', 'test', '["pack(s)"]', 3, 2, 0, 1, 1, '2015-03-30 05:08:46', '2015-03-30 05:08:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_products_to_categories`
--

CREATE TABLE IF NOT EXISTS `sales_products_to_categories` (
`product_to_category_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_product_price_history`
--

INSERT INTO `sales_product_price_history` (`price_history_id`, `product_id`, `branch_id`, `supplier_id`, `supplier_price`, `selling_price`, `per_unit`) VALUES
(8, 1, 5, NULL, '45.00', '50.00', 'pack'),
(9, 2, 5, NULL, '500.00', '554.00', 'sacks'),
(10, 2, 5, NULL, '45.00', '56.00', 'kg'),
(13, 1, 5, NULL, '45.00', '55.00', 'pack'),
(14, 1, 5, NULL, '55.00', '56.00', 'pack'),
(15, 1, 6, NULL, '45.00', '50.00', 'pack'),
(16, 4, 5, NULL, '100.00', '105.00', 'kg'),
(17, 1, 5, NULL, '55.00', '56.00', 'kg'),
(18, 1, 5, NULL, '93.00', '96.00', 'pack(s)'),
(19, 2, 5, NULL, '45.00', '50.00', 'kg'),
(20, 4, 5, NULL, '30.00', '34.00', 'kg'),
(21, 1, 7, NULL, '89.00', '90.00', 'pack(s)'),
(22, 2, 7, NULL, '45.00', '50.00', 'kg'),
(23, 2, 7, NULL, '500.00', '554.00', 'sack(s)'),
(24, 5, 5, NULL, '50.00', '60.00', 'pack(s)');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_product_pricing`
--

INSERT INTO `sales_product_pricing` (`price_id`, `product_id`, `branch_id`, `supplier_id`, `supplier_price`, `selling_price`, `per_unit`) VALUES
(11, 2, 5, NULL, '500.00', '554.00', 'sack(s)'),
(12, 2, 5, NULL, '45.00', '50.00', 'kg'),
(17, 4, 5, NULL, '30.00', '34.00', 'kg'),
(18, 1, 5, NULL, '93.00', '96.00', 'pack(s)'),
(19, 1, 7, NULL, '89.00', '90.00', 'pack(s)'),
(20, 2, 7, NULL, '45.00', '50.00', 'kg'),
(21, 2, 7, NULL, '500.00', '554.00', 'sack(s)'),
(22, 5, 5, NULL, '50.00', '60.00', 'pack(s)');

-- --------------------------------------------------------

--
-- Table structure for table `sales_sales`
--

CREATE TABLE IF NOT EXISTS `sales_sales` (
`sale_id` bigint(20) unsigned NOT NULL,
  `sale_type` enum('SALE','CREDIT') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'SALE',
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_sales`
--

INSERT INTO `sales_sales` (`sale_id`, `sale_type`, `branch_id`, `product_id`, `supplier_price`, `selling_price`, `quantity`, `uom`, `total_amount`, `comments`, `date_of_sale`, `encoded_by`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SALE', 5, 1, '93.00', '96.00', '2.00', 'pack(s)', '192.00', 'lorem ipsum dolor', '2015-04-02', 3, 1, '2015-03-12 21:30:42', '2015-03-12 21:33:48', NULL),
(2, 'SALE', 5, 1, '93.00', '96.00', '3.00', 'pack(s)', '288.00', 'palit 3 packs', '2015-04-03', 2, 1, '2015-03-13 05:16:25', '2015-03-13 05:16:25', NULL),
(6, 'SALE', 5, 4, '30.00', '34.00', '4.00', 'kg', '136.00', 'fasf', '2015-06-11', 2, 1, '2015-03-15 05:26:20', '2015-03-15 05:26:20', NULL),
(7, 'SALE', 5, 2, '45.00', '50.00', '2.00', 'kg', '100.00', 'dsafas', '2015-03-15', 2, 1, '2015-03-15 05:30:40', '2015-03-19 06:53:33', NULL),
(8, 'SALE', 5, 2, '45.00', '50.00', '5.00', 'kg', '250.00', 'fasdfasdf', '2015-03-15', 2, 1, '2015-03-15 05:30:40', '2015-03-19 06:53:20', NULL),
(10, 'SALE', 5, 2, '45.00', '50.00', '3.00', 'kg', '150.00', 'yyyyy', '2015-03-15', 1, 0, '2015-03-14 23:58:35', '2015-03-20 22:45:12', NULL),
(14, 'SALE', 5, 1, '93.00', '96.00', '2.00', 'pack(s)', '192.00', 'asdfsdf', '2015-03-18', 1, 0, '2015-03-20 22:49:57', '2015-03-20 23:31:46', NULL),
(18, 'SALE', 7, 1, '89.00', '90.00', '1.00', 'pack(s)', '90.00', 'fasdfsdf', '2015-03-21', 1, 0, '2015-03-20 23:31:31', '2015-03-20 23:31:31', NULL),
(26, 'SALE', 7, 1, '89.00', '90.00', '1.00', 'pack(s)', '90.00', 'dsfasdf', '2015-03-22', 3, 0, '2015-03-22 04:21:37', '2015-03-22 04:21:37', NULL),
(27, 'SALE', 7, 2, '45.00', '50.00', '1.00', 'kg', '50.00', 'fasdfasdf', '2015-03-22', 3, 0, '2015-03-22 04:21:45', '2015-03-22 04:21:45', NULL),
(29, 'SALE', 7, 2, '45.00', '50.00', '1.00', 'kg', '50.00', 'fasdfsdf', '2015-03-22', 1, 0, '2015-03-22 04:24:12', '2015-03-23 06:25:31', NULL),
(40, 'SALE', 7, 0, '0.00', '0.00', '0.00', '', '2.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 06:35:37', '2015-03-24 06:35:37', NULL),
(41, 'SALE', 7, 0, '0.00', '0.00', '0.00', '', '2.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 06:40:35', '2015-03-24 06:40:35', NULL),
(42, 'SALE', 7, 0, '0.00', '0.00', '0.00', '', '2.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 06:41:58', '2015-03-24 06:41:58', NULL),
(43, 'SALE', 6, 0, '0.00', '0.00', '0.00', '', '5.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 06:43:26', '2015-03-24 06:43:26', NULL),
(44, 'SALE', 6, 0, '0.00', '0.00', '0.00', '', '5.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 06:43:56', '2015-03-24 06:43:56', NULL),
(45, 'SALE', 6, 0, '0.00', '0.00', '0.00', '', '200.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 07:14:03', '2015-03-24 07:14:03', NULL),
(46, 'SALE', 5, 0, '0.00', '0.00', '0.00', '', '1.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 07:15:01', '2015-03-24 07:15:01', NULL),
(49, 'SALE', 7, 0, '0.00', '0.00', '0.00', '', '1.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 07:27:03', '2015-03-24 07:27:03', NULL),
(53, 'SALE', 7, NULL, '0.00', '0.00', '0.00', '', '50.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 08:10:19', '2015-03-24 08:10:19', NULL),
(54, 'SALE', 7, NULL, '0.00', '0.00', '0.00', '', '20.00', 'Partial payment', '2015-03-24', 1, 0, '2015-03-24 08:10:55', '2015-03-24 08:10:55', NULL),
(56, 'SALE', 5, NULL, '0.00', '0.00', '0.00', '', '455.00', 'Partial payment', '2015-03-25', 1, 0, '2015-03-25 06:12:49', '2015-03-25 06:12:49', NULL),
(57, 'SALE', 7, NULL, '0.00', '0.00', '0.00', '', '80.00', 'Partial payment', '2015-03-25', 1, 0, '2015-03-25 06:35:33', '2015-03-25 06:35:33', NULL),
(58, 'CREDIT', 5, 2, '45.00', '50.00', '50.00', 'kg', '2500.00', 'adfasdf', '2015-03-25', 1, 0, '2015-03-25 06:39:22', '2015-03-25 06:39:22', NULL),
(59, 'CREDIT', 5, 1, '93.00', '96.00', '1.00', 'pack(s)', '96.00', 'test', '2015-03-25', 1, 0, '2015-03-25 07:27:10', '2015-03-25 07:27:10', NULL),
(60, 'SALE', 5, NULL, '0.00', '0.00', '0.00', '', '500.00', 'Partial payment', '2015-03-25', 1, 0, '2015-03-25 07:28:23', '2015-03-25 07:28:23', NULL),
(61, 'SALE', 5, NULL, '0.00', '0.00', '0.00', '', '5037.00', 'Partial payment', '2015-03-25', 1, 0, '2015-03-25 07:28:53', '2015-03-25 07:28:53', NULL),
(62, 'SALE', 5, NULL, '0.00', '0.00', '0.00', '', '2000.00', 'Partial payment', '2015-03-25', 1, 0, '2015-03-25 07:29:11', '2015-03-25 07:29:11', NULL),
(63, 'SALE', 5, NULL, '0.00', '0.00', '0.00', '', '28000.00', 'Partial payment', '2015-03-25', 1, 0, '2015-03-25 08:06:02', '2015-03-25 08:06:02', NULL),
(64, 'CREDIT', 7, 2, '45.00', '50.00', '1.00', 'kg', '50.00', 'asdfasdf', '2015-03-26', 1, 0, '2015-03-26 05:00:27', '2015-03-26 05:00:27', NULL),
(65, 'SALE', 5, 1, '93.00', '96.00', '1.00', 'pack(s)', '96.00', 'asdfasdf', '2015-03-26', 1, 0, '2015-03-26 05:06:32', '2015-03-26 05:06:32', NULL),
(66, 'SALE', 7, 2, '45.00', '50.00', '2.00', 'kg', '100.00', 'test', '2015-03-28', 1, 0, '2015-03-27 23:32:14', '2015-03-27 23:32:14', NULL),
(67, 'SALE', 7, 2, '45.00', '50.00', '50.00', 'kg', '2500.00', 'sdfasdf', '2015-03-28', 1, 0, '2015-03-27 23:32:29', '2015-03-27 23:32:29', NULL),
(68, 'SALE', 7, NULL, '0.00', '0.00', '0.00', '', '50.00', '10', '2015-04-06', 1, 0, '2015-04-06 08:23:30', '2015-04-06 08:23:30', NULL),
(69, 'SALE', 0, NULL, '0.00', '0.00', '0.00', '', '100.00', 'dfasdfPartial payment', '2015-04-06', 1, 0, '2015-04-06 08:31:46', '2015-04-06 08:31:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_stocks_on_hand`
--

CREATE TABLE IF NOT EXISTS `sales_stocks_on_hand` (
`stock_on_hand_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `total_stocks` decimal(10,2) unsigned NOT NULL,
  `uom` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `is_payable` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_stocks_on_hand`
--

INSERT INTO `sales_stocks_on_hand` (`stock_on_hand_id`, `product_id`, `branch_id`, `total_stocks`, `uom`, `is_payable`) VALUES
(12, 5, 5, '2.00', 'pack(s)', 0),
(17, 4, 6, '111.00', 'kg', 0),
(18, 4, 5, '917.00', 'kg', 0),
(19, 1, 5, '394.00', 'kg', 0),
(20, 4, 5, '17350.00', 'kg', 0),
(21, 4, 7, '841.00', 'kg', 0),
(22, 2, 5, '2.00', 'pack(s)', 0),
(23, 5, 7, '5.00', 'pack(s)', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_suppliers`
--

CREATE TABLE IF NOT EXISTS `sales_suppliers` (
`supplier_id` bigint(20) unsigned NOT NULL,
  `supplier_name` varchar(120) NOT NULL,
  `location` int(11) NOT NULL,
  `contact_no` varchar(30) NOT NULL,
  `total_payables` decimal(10,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales_suppliers`
--

INSERT INTO `sales_suppliers` (`supplier_id`, `supplier_name`, `location`, `contact_no`, `total_payables`) VALUES
(3, 'Supplier A', 7, '09236954', '0.00'),
(4, 'Supplier C', 5, '09233695248', '893.00'),
(5, 'Supplier B', 7, '092369587', '0.00');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `contact_no` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_users`
--

INSERT INTO `sales_users` (`id`, `username`, `email`, `password`, `display_name`, `contact_no`, `address`, `birthdate`, `first_name`, `last_name`, `is_admin`, `confirmation_code`, `remember_token`, `confirmed`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'owner', 'serg.casquejo@yahoo.com', '$2y$10$PNLtrhJfcZ2RyfjZxS.M8Oy0Jey0Tvxeh0M488sqp4CmCVDjpS0ke', 'john', '236-654-6655', '09236987', '1970-01-16', 'John', 'Doe', 1, 'e1e07c1cb3e1d52f198536799c524fb7', 'kQt2NhWv94bAby2YYF26l6dCrNrLqzNWmGiqbzzirtwcN5KlNYkatmeF8UJU', 1, 0, 5, '0000-00-00 00:00:00', '2015-03-25 08:04:27', NULL),
(2, 'rovelyn', 'ianquijano@gmail.com', '$2y$10$8WQbNhcY7KXKqXilv4v7uOdT5I2yNmP9CwCxUM5fNWAU2h/TDWDcG', 'Rovelyn', '09173052042', 'Barangay Gacat, BayBay City', '1987-03-10', 'Rovelyn', 'Ventula', 0, '', NULL, 1, 1, 5, '2015-03-10 18:22:46', '2015-03-13 05:09:23', NULL),
(3, 'janedoe', 'serg.casquejo@gmail.com', '$2y$10$Jqnlh5Uu7bVVUIzPdKuwmunrro4dbsBrRiYONUFoufYh/tTnZWJuu', 'Jane Doe', '2369-9898-989', 'Cordova Cebu', '2015-03-13', 'Jane', 'Doe', 0, '', 'zXWghGBvSVCsoeaCWyAorNAlBsiDgJszBFDoGKdc6I1iMzAtI3ptbq7OFsde', 1, 1, 7, '2015-03-12 21:30:05', '2015-03-25 08:05:40', NULL);

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
 ADD PRIMARY KEY (`credit_id`), ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `sales_customers`
--
ALTER TABLE `sales_customers`
 ADD PRIMARY KEY (`customer_id`);

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
-- Indexes for table `sales_suppliers`
--
ALTER TABLE `sales_suppliers`
 ADD PRIMARY KEY (`supplier_id`);

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
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sales_brands`
--
ALTER TABLE `sales_brands`
MODIFY `brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sales_categories`
--
ALTER TABLE `sales_categories`
MODIFY `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sales_categories_to_brands`
--
ALTER TABLE `sales_categories_to_brands`
MODIFY `category_to_brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sales_credits`
--
ALTER TABLE `sales_credits`
MODIFY `credit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sales_customers`
--
ALTER TABLE `sales_customers`
MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sales_expenses`
--
ALTER TABLE `sales_expenses`
MODIFY `expense_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
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
MODIFY `price_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `sales_product_pricing`
--
ALTER TABLE `sales_product_pricing`
MODIFY `price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `sales_sales`
--
ALTER TABLE `sales_sales`
MODIFY `sale_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `sales_stocks_on_hand`
--
ALTER TABLE `sales_stocks_on_hand`
MODIFY `stock_on_hand_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `sales_suppliers`
--
ALTER TABLE `sales_suppliers`
MODIFY `supplier_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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
-- Constraints for table `sales_stocks_on_hand`
--
ALTER TABLE `sales_stocks_on_hand`
ADD CONSTRAINT `stocks_on_hand_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `sales_branches` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `stocks_on_hand_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
