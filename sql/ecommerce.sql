-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 11:00 AM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `hashed_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` enum('active','abandoned','completed','pending','cancelled','expired','paid') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `customer_id`, `status`, `created_at`) VALUES
(1, 7, 'active', '2025-03-24 10:35:04'),
(2, 6, 'completed', '2025-03-26 03:58:06'),
(3, 6, 'completed', '2025-03-26 04:13:35'),
(4, 6, 'completed', '2025-03-26 08:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1 CHECK (`quantity` >= 0),
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 199.99),
(1, 3, 1, 149.99),
(1, 4, 1, 89.99),
(1, 5, 2, 499.99),
(2, 2, 1, 59.99),
(3, 1, 3, 199.99),
(4, 2, 2, 59.99),
(4, 3, 3, 149.99),
(4, 4, 2, 89.99);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Audio'),
(2, 'Wearables'),
(3, 'Accessories'),
(4, 'Displays');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('M','F','O') DEFAULT 'O'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `hashed_password`, `contact`, `dob`, `gender`) VALUES
(5, 'david', 'johnson', 'david@yahoo.com', '$2y$10$vdlrTuJ0KxFGSpIuuhV09eVMrk4u/YNwjPm8fhMFuhcQsKUqo2Mku', '01232423221', '1996-02-05', 'O'),
(6, 'Iftikher', 'Azam', 'iftikher.azam@northsouth.edu', '$2y$10$X6S8F1qBvK7Evk2.qXlA.u7iSRF88P9YP1gyXdTvgGhYt4hIkz0rS', '01627355279', '2002-02-01', 'M'),
(7, 'Ifitikher', 'Tawfeeq', 'nazeef.tawfeeq@gmail.com', '$2y$10$hV0M0gAc16HMrbhq45DS0O/8fY5E7vjLMsBUOJTVipIGFImFNh.Bm', '01798584499', '2002-09-22', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE `delivery_addresses` (
  `address_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_addresses`
--

INSERT INTO `delivery_addresses` (`address_id`, `customer_id`, `address`) VALUES
(4, 5, 'North South University, Dhaka, Bangladesh'),
(5, 5, 'BAF Shaheen English Medium School, Cantonment, Dhaka'),
(6, 5, 'Independent University Bangladesh, Dhaka, Bangladesh'),
(7, 6, 'North South University, Bashundhara Residential Area, Dhaka'),
(8, 6, 'Independent University Bangladesh, BRA, Dhaka'),
(10, 7, 'West Nakhalpara, Tejgaon, Dhaka-1215'),
(11, 7, 'BAF Shaheen English Medium School, Cantonment, Dhaka');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL CHECK (`total_price` >= 0),
  `status` enum('pending','paid','processing','shipped','out_for_delivery','delivered','cancelled','returned','refunded','failed','on_hold') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `address_id`, `total_price`, `status`, `created_at`) VALUES
(1, 6, 8, 59.99, 'pending', '2025-03-26 03:58:46'),
(2, 6, 8, 599.97, 'pending', '2025-03-26 07:03:56'),
(3, 6, 7, 749.93, 'pending', '2025-03-26 08:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1 CHECK (`quantity` >= 0),
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 2, 1, 59.99),
(2, 1, 3, 199.99),
(3, 2, 2, 59.99),
(3, 3, 3, 149.99),
(3, 4, 2, 89.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `quantity` int(11) DEFAULT 0 CHECK (`quantity` >= 0),
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `quantity`, `image_url`, `category_id`) VALUES
(1, 'Wireless Headphones', 'Experience high-quality sound without the hassle of wires. These wireless headphones offer crystal-clear audio, comfortable design, and long battery life—perfect for music, calls, and on-the-go convenience.', 199.99, 47, '/ecommerce-frontend/Client/assets/Images/products/wireless_headphone.png', 1),
(2, 'Bluetooth Speaker', 'Portable Bluetooth speakers with powerful sound and deep bass. Enjoy wireless music streaming, long battery life, and a compact design—perfect for parties, travel, and outdoor fun.', 59.99, 37, '/ecommerce-frontend/Client/assets/Images/products/bluetooth_speaker.jpg', 1),
(3, 'Smart Watch', 'Stay connected and track your fitness with this sleek smart watch. Featuring health monitoring, notifications, and long battery life, it’s the perfect companion for your active lifestyle.', 149.99, 17, '/ecommerce-frontend/Client/assets/Images/products/smart_watch.png', 2),
(4, 'Gaming Keyboard', 'Enhance your gameplay with this high-performance gaming keyboard. Featuring responsive mechanical keys, customizable RGB lighting, and durable design for ultimate precision and speed.', 89.99, 28, '/ecommerce-frontend/Client/assets/Images/products/gaming_keyboard_yellow.jpg', 3),
(5, '4k Monitor', 'Experience stunning clarity and vibrant colors with this 4K UHD monitor. Perfect for gaming, design, and productivity, it delivers ultra-sharp visuals and smooth performance.', 499.99, 10, '/ecommerce-frontend/Client/assets/Images/products/asus_monitor.jpg', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `idx_carts_customer_id` (`customer_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_id`,`product_id`),
  ADD KEY `fk_products_cart_items` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_addresses_customer_id` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_customers_orders` (`customer_id`),
  ADD KEY `fk_addresses_orders` (`address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_products_category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_carts_cart_items` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_cart_items` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD CONSTRAINT `fk_addresses` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_addresses_orders` FOREIGN KEY (`address_id`) REFERENCES `delivery_addresses` (`address_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customers_orders` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orders_order_items` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_order_items` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category_products` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
