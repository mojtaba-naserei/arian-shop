CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `total_disscount` int(11) NOT NULL,
  `peyk_code` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `edit_time` int(10) NOT NULL,
  `session` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `tracking_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pay_id` int(11) NOT NULL,
  `time` int(10) NOT NULL,
  `card_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `port_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `products_menu` (
  `product_code` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `product_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `product_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `product_number` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_disscount` int(11) NOT NULL,
  `product_pic` text COLLATE utf8_unicode_ci,
  `product_description` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `products_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `food_code` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `restaurant_managers` (
  `restaurant_id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_type` tinyint(1) NOT NULL,
  `restaurant_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_tel` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `restaurant_address` text COLLATE utf8_unicode_ci NOT NULL,
  `manager_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `manager_mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `manager_pass` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`restaurant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `transporter` (
  `transporter_id` int(11) NOT NULL AUTO_INCREMENT,
  `transporter_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `transporter_mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`transporter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name_family` varchar(50) NOT NULL,
  `user_mobile` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_address` text NOT NULL,
  `user_pass` varchar(50) NOT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `users` (`user_id`, `user_name_family`, `user_mobile`, `user_address`, `user_pass`, `user_type`) VALUES
(1, 'root', '09213369379', 'ندارد', '202cb962ac59075b964b07152d234b70', 1)


