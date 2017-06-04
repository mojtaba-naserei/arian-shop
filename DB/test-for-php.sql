-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 04, 2017 at 10:15 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test-for-php`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `description` text COLLATE utf8_persian_ci NOT NULL,
  `create_time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `total_disscount` int(11) NOT NULL,
  `peyk_code` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `edit_time` int(10) NOT NULL,
  `session` varchar(50) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `tracking_code` varchar(100) COLLATE utf8_persian_ci NOT NULL,
  `pay_id` int(11) NOT NULL,
  `time` int(10) NOT NULL,
  `card_number` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `port_name` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_menu`
--

CREATE TABLE `products_menu` (
  `product_code` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `product_name` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `product_type` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `product_number` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_disscount` int(11) NOT NULL,
  `product_pic` text COLLATE utf8_persian_ci,
  `product_description` text COLLATE utf8_persian_ci NOT NULL,
  `create_time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_order`
--

CREATE TABLE `products_order` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_code` int(11) NOT NULL,
  `order_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_managers`
--

CREATE TABLE `restaurant_managers` (
  `restaurant_id` int(11) NOT NULL,
  `restaurant_type` tinyint(1) NOT NULL,
  `restaurant_name` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `restaurant_city` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `restaurant_tel` varchar(12) COLLATE utf8_persian_ci NOT NULL,
  `restaurant_address` text COLLATE utf8_persian_ci NOT NULL,
  `manager_name` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `manager_mobile` varchar(11) COLLATE utf8_persian_ci NOT NULL,
  `manager_pass` varchar(50) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transporter`
--

CREATE TABLE `transporter` (
  `transporter_id` int(11) NOT NULL,
  `transporter_name` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `transporter_mobile` varchar(11) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name_family` varchar(50) NOT NULL,
  `user_mobile` varchar(11) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `user_address` text NOT NULL,
  `user_pass` varchar(50) NOT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT '2',
  `session` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name_family`, `user_mobile`, `user_address`, `user_pass`, `user_type`, `session`) VALUES
(1, 'root', '09213369379', '', '7b24afc8bc80e548d66c4e7ff72171c5', 1, '')

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_menu`
--
ALTER TABLE `products_menu`
  ADD PRIMARY KEY (`product_code`);

--
-- Indexes for table `products_order`
--
ALTER TABLE `products_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_managers`
--
ALTER TABLE `restaurant_managers`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
  ADD PRIMARY KEY (`transporter_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `products_menu`
--
ALTER TABLE `products_menu`
  MODIFY `product_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `products_order`
--
ALTER TABLE `products_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `restaurant_managers`
--
ALTER TABLE `restaurant_managers`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `transporter`
--
ALTER TABLE `transporter`
  MODIFY `transporter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
