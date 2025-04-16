-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 16, 2025 at 07:47 PM
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
(2, 6, 'completed', '2025-03-26 03:58:06'),
(3, 6, 'completed', '2025-03-26 04:13:35'),
(4, 6, 'completed', '2025-03-26 08:26:50'),
(8, 6, 'active', '2025-03-28 10:32:55'),
(9, 12, 'completed', '2025-04-16 13:38:27');

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
(2, 2, 1, 59.99),
(3, 1, 3, 199.99),
(4, 2, 2, 59.99),
(4, 3, 3, 149.99),
(4, 4, 2, 89.99),
(8, 1, 1, 199.99),
(8, 2, 1, 59.99),
(8, 3, 1, 149.99),
(8, 4, 1, 89.99),
(8, 5, 1, 499.99),
(9, 5, 1, 499.99),
(9, 12, 1, 59.99),
(9, 27, 1, 329.99);

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
(12, 'Nazeef', 'Jalal', 'nazeef.tawfeeq@gmail.com', '$2y$10$AswgOhcrS/lX9R6BIZjCaeDcreY4phO23yS3gBLG5nx1NlRU.4/ZC', '01798584499', '2002-09-22', 'M');

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
(15, 12, 'North South University, Dhaka, Bangladesh');

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
(3, 6, 7, 749.93, 'pending', '2025-03-26 08:51:25'),
(8, 12, 15, 889.97, 'pending', '2025-04-16 13:38:35');

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
(3, 4, 2, 89.99),
(8, 5, 1, 499.99),
(8, 12, 1, 59.99),
(8, 27, 1, 329.99);

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
(2, 'Bluetooth Speaker', 'Portable Bluetooth speakers with powerful sound and deep bass. Enjoy wireless music streaming, long battery life, and a compact design—perfect for parties, travel, and outdoor fun.', 59.99, 36, '/ecommerce-frontend/Client/assets/Images/products/bluetooth_speaker.jpg', 1),
(3, 'Smart Watch', 'Stay connected and track your fitness with this sleek smart watch. Featuring health monitoring, notifications, and long battery life, it’s the perfect companion for your active lifestyle.', 149.99, 15, '/ecommerce-frontend/Client/assets/Images/products/smart_watch.png', 2),
(4, 'Gaming Keyboard', 'Enhance your gameplay with this high-performance gaming keyboard. Featuring responsive mechanical keys, customizable RGB lighting, and durable design for ultimate precision and speed.', 89.99, 25, '/ecommerce-frontend/Client/assets/Images/products/gaming_keyboard_yellow.jpg', 3),
(5, '4k Monitor', 'Experience stunning clarity and vibrant colors with this 4K UHD monitor. Perfect for gaming, design, and productivity, it delivers ultra-sharp visuals and smooth performance.', 499.99, 3, '/ecommerce-frontend/Client/assets/Images/products/asus_monitor.jpg', 4),
(6, 'USB-C Charger', 'Fast charging USB-C power adapter.', 29.99, 50, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(7, 'Mechanical Keyboard', 'Tactile keys with RGB lighting.', 89.99, 20, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(8, 'Gaming Mouse', 'Precision gaming mouse with customizable DPI.', 49.99, 35, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(9, 'Laptop Stand', 'Ergonomic laptop stand for better posture.', 39.99, 45, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(10, 'Noise Cancelling Headphones', 'Distraction-free listening with ANC.', 129.99, 30, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 1),
(11, 'Smartphone Tripod', 'Adjustable tripod for mobile phones.', 19.99, 60, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(12, 'Webcam', '1080p webcam with built-in microphone.', 59.99, 39, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 4),
(13, 'Portable SSD 1TB', 'High-speed external SSD drive.', 99.99, 25, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(14, 'Wireless Mouse', 'Smooth and quiet wireless mouse.', 24.99, 50, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(15, 'Bluetooth Earbuds', 'Compact earbuds with long battery life.', 79.99, 55, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 1),
(16, 'LED Desk Lamp', 'Dimmable LED desk lamp with USB port.', 34.99, 70, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(17, 'Wireless Router', 'Dual-band wireless router.', 119.99, 18, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(18, 'Power Bank 20000mAh', 'High-capacity portable power bank.', 49.99, 48, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(19, 'Smart Light Bulb', 'WiFi-enabled color-changing LED bulb.', 22.99, 75, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(20, 'Smartwatch Pro', 'Advanced smartwatch with health tracking.', 199.99, 22, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 2),
(21, 'HDMI Cable 2m', 'High-speed HDMI cable for 4K video.', 12.99, 100, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(22, 'Wireless Charging Pad', 'Fast wireless charging station.', 29.99, 37, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(23, 'Ergonomic Chair', 'Comfortable chair for long hours.', 249.99, 10, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(24, 'External Hard Drive 2TB', 'Reliable portable storage.', 79.99, 27, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(25, 'Tablet Stand', 'Adjustable tablet holder.', 19.99, 53, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(26, 'VR Headset', 'Immersive virtual reality headset.', 299.99, 12, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 2),
(27, 'Gaming Monitor 27\"', 'High refresh rate monitor.', 329.99, 8, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 4),
(28, 'Smart Thermostat', 'Control temperature remotely.', 149.99, 16, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(29, 'Graphics Tablet', 'Digital drawing tablet.', 139.99, 20, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(30, 'Bluetooth Car Adapter', 'Stream music and calls in car.', 24.99, 65, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(31, 'Streaming Microphone', 'Clear voice recording.', 89.99, 28, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(32, 'Portable Projector', 'Mini projector for movies.', 219.99, 11, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 4),
(33, 'Fitness Tracker', 'Track activity and sleep.', 69.99, 32, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 2),
(34, 'USB Hub', 'Expand your USB ports.', 17.99, 70, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(35, 'Webcam Cover', 'Protect your privacy.', 4.99, 90, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(36, 'WiFi Repeater', 'Boost wireless signal.', 29.99, 45, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(37, 'Laptop Cooling Pad', 'Keep your laptop cool.', 34.99, 33, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(38, 'Stylus Pencil', 'For drawing on tablets.', 24.99, 40, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 2),
(39, 'Bluetooth Keyboard', 'Wireless keyboard for tablets.', 39.99, 27, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(40, 'Desk Organizer', 'Keep your desk tidy.', 14.99, 60, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(41, 'USB LED Strip Light', 'Decorative USB LED lighting.', 11.99, 66, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(42, 'Gaming Controller', 'Wireless game controller.', 59.99, 23, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(43, 'Router Mount Bracket', 'Wall-mount for routers.', 9.99, 48, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(44, 'Camera Lens Cleaner', 'Keep your lenses spotless.', 8.99, 50, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(45, 'Phone Holder for Car', 'Dashboard phone mount.', 18.99, 42, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(46, 'Surge Protector', 'Protect from power surges.', 24.99, 40, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(47, 'Gaming Chair', 'Comfortable chair for gaming.', 299.99, 7, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(48, 'Multifunction Printer', 'Print, scan, and copy.', 189.99, 15, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 4),
(49, 'Laptop Backpack', 'Water-resistant backpack.', 59.99, 30, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(50, 'Smart Plug', 'Control your plugs remotely.', 16.99, 58, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(51, 'Digital Alarm Clock', 'LED display with alarms.', 19.99, 45, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(52, 'USB Fan', 'Compact USB-powered fan.', 9.99, 72, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(53, 'Drawing Glove', 'Reduce friction on tablet.', 6.99, 67, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(54, 'USB Microphone', 'Plug-and-play mic.', 39.99, 35, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 3),
(55, 'Smart Scale', 'Track weight and BMI.', 54.99, 25, '/ecommerce-frontend/Client/assets/Images/products/default_product.png', 2);

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
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

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
