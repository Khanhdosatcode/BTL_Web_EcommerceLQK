-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 06:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommercelqk`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

create database ecommercelqk;
use ecommercelqk;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Laptop'),
(2, 'Điện thoại'),
(3, 'Máy ảnh'),
(4, 'Tai nghe');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `export_price` bigint(20) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `UnitsSold` int(11) DEFAULT 0,
  `UnitsAvailable` int(11) DEFAULT NULL,
  `NguongCanhBao` int(11) DEFAULT 5,
  `import_price` bigint(20) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `category_id`, `export_price`, `description`, `Image`, `UnitsSold`, `UnitsAvailable`, `NguongCanhBao`, `import_price`) VALUES
(1, 'Laptop assus I3210', 'Assus', 1, 30000000, 'Laptop Dell', '1.jpg', 70, 50, 5, 31000000),
(2, 'Laptop-lenovo-legion', 'Lenovo', 1, 30000000, '2', '2.jpg', 80, 4, 5, 35000000),
(3, 'Laptop Dell Latitude L3440-I51235U', 'Delll', 1, 14000000, NULL, '3.jpg', 30, 5, 5, 16000000),
(4, 'Laptop Dell Inspiron 3520', 'Dell', 1, 14000000, NULL, '4.jpg', 40, 50, 5, 14000000),
(5, 'Laptop Dell Latitude L3441', 'Dell', 1, 16000000, NULL, '5.jpg', 20, 30, 4, 18000000),
(7, 'Laptop Asus Vivobook S16-oled', 'Asus', 1, 18000000, '', '7.jpg', 20, 10, 4, 20000000),
(8, 'Laptop Apple Macbook Air Mgn63saa', 'Macbook', 1, 19000000, '12313131', '8.jpg', 110, 2, 5, 19000000),
(9, 'Tai Nghe Dareu Eh416', 'Dareu', 4, 2000000, '12313131', '9.jpg', 120, 6, 10, 2000000),
(33, 'Máy Ảnh 11222', 'khánh', 2, 1600000, 'khánh2222222', '6753372e1edd0.png,675d703735926.png', 10, 20, 5, 1400000);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `promotion_name` varchar(255) DEFAULT NULL,
  `discount` decimal(5,2) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `promotion_name`, `discount`, `product_id`, `start_time`, `end_time`) VALUES
(1, 'Khuyến mại mùa hè', 30.00, '1,3,5,7', '1970-01-01 01:00:00', '1970-01-01 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `password_hash`, `role`, `created_at`, `image`, `username`) VALUES
(1, 'Lý Quốc Khánh ', 'lyquockhanh020903@gmail.com', '0982020903', 'Hà Nội', '$2y$10$kM.5SxB7oROqnpRnTMq9T.WnOZ3tUmaXBoycfK8ivKZEWvy6vr8qO', 'admin', '2024-11-20 13:53:10', 'khanh.jpg', 'quockhanh'),
(2, 'Khánh Quốc Lý', 'khanhquocly020903@gmail.com', '0987654321', 'Hồ Chí Minh', '$2y$10$kM.5SxB7oROqnpRnTMq9T.WnOZ3tUmaXBoycfK8ivKZEWvy6vr8qO', 'admin', '2024-11-20 13:53:10', NULL, 'khanhquoc'),
(3, 'Lý Khánh Quốc', 'lkhanhlinh432@gmail.com', '0938456765', 'Đà Nẵng', 'hashedpassword3', 'admin', '2024-11-20 13:53:10', NULL, 'khanhly'),
(4, 'Hoàng Thu Cúcc', 'd.phan@example.com', '0912345678', 'Hà Nội', '$2y$10$kM.5SxB7oROqnpRnTMq9T.WnOZ3tUmaXBoycfK8ivKZEWvy6vr8qO', 'customer', '2024-11-20 13:53:10', 'cuc.jpg', 'thucuc'),
(5, 'Hoàng Thi E', 'e.hoang@example.com', '0965321456', 'Cần Thơ', '$2y$10$kM.5SxB7oROqnpRnTMq9T.WnOZ3tUmaXBoycfK8ivKZEWvy6vr8qO', 'customer', '2024-11-20 13:53:10', NULL, 'khanh1'),
(6, 'Nguyễn Văn A', 'nguyenvana@example.com', '0912345679', 'Hà Nội', '$2y$10$Hq9T8J1MvU0TkFvjpM07yeV6q6BdIs3qjqu1FJeuMTcBzZ2z3Tyyq', 'customer', '2024-12-07 07:20:00', 'nguyenvana.jpg', 'nguyenvana'),
(7, 'Trần Thị B', 'tranthib@example.com', '0918765432', 'Đà Nẵng', '$2y$10$45JAdFs4HjmUEyk7vAL7f0ZlAqHJZxErTkRLpzNxdoXelqJYnp7iW', 'customer', '2024-12-07 07:30:00', 'tranthib.jpg', 'tranthib'),
(8, 'Phan Quốc C', 'phanquoc@example.com', '0987435123', 'Hồ Chí Minh', '$2y$10$yFJ12WJKh50vfW.sXeAiO3NmYXME0e2iBRpFvDSDjd7SbxkQq7nfu', 'customer', '2024-12-07 07:45:00', 'phanquoc.jpg', 'phanquoc'),
(9, 'Đào Minh D', 'daominhd@example.com', '0923456789', 'Cần Thơ', '$2y$10$F2eKr7U5nmk1jOvxF0Kkm2eImUwNh2SlBbGzqozzYc0lwhdHHwGxu', 'customer', '2024-12-07 08:00:00', 'daominhd.jpg', 'daominhd'),
(10, 'Vũ Tấn E', 'vutane@example.com', '0909876543', 'Hà Nội', '$2y$10$8WLftO7fRt8zrrlT9mc12wZGk.zwMjEKsdqqsdAlSKvTayl5.LZOC', 'customer', '2024-12-07 08:10:00', 'vutane.jpg', 'vutane'),
(11, 'Lê Thị F', 'lethif@example.com', '0931234567', 'Hà Nội', '$2y$10$CkVszHW3RlgK7z1gHgGz/eZ9PZnNdoZRkUDTqf8sLrZXFflfRjoOa', 'customer', '2024-12-07 08:20:00', 'lethif.jpg', 'lethif'),
(12, 'Ngô Minh G', 'ngominhg@example.com', '0945678901', 'Đà Nẵng', '$2y$10$XK0zBdYZEDi5l/xk9XI2XQ5HV4aGn6hTLTZhxwdrDJxeC5ehd1T4u', 'customer', '2024-12-07 08:30:00', 'ngominhg.jpg', 'ngominhg'),
(13, 'Võ Quốc H', 'voquoch@example.com', '0913456789', 'Hồ Chí Minh', '$2y$10$RpL6lXj7cOUHDHimHT5GlkeOeq6kV9dbF8uNlxeHL4gFOhAfvwkMm', 'customer', '2024-12-07 08:40:00', 'voquoch.jpg', 'voquoch'),
(14, 'Bùi Thị I', 'buih@example.com', '0934567890', 'Cần Thơ', '$2y$10$55YHz60Tp4zq2c4OECqMkOdKnLNgZcLxvnQ91xYmE72K2gOSeiBFS', 'customer', '2024-12-07 08:50:00', 'buih.jpg', 'buih'),
(15, 'Trương Văn J', 'truongvanj@example.com', '0971234567', 'Hà Nội', '$2y$10$VhcvGV1G.zb1.Wkblxx6cqLC1ZLnVzU5GBFhDi6ffBqgqkEk9ppHm', 'customer', '2024-12-07 09:00:00', 'truongvanj.jpg', 'truongvanj'),
(16, 'Phạm Thị K', 'phamthik@example.com', '0912345678', 'Hà Nội', '$2y$10$EXAMPLEHASH1', 'customer', '2024-12-07 09:10:00', 'phamthik.jpg', 'phamthik'),
(17, 'Lê Minh L', 'leminhl@example.com', '0901234567', 'Hồ Chí Minh', '$2y$10$EXAMPLEHASH2', 'customer', '2024-12-07 09:20:00', 'leminhl.jpg', 'leminhl'),
(18, 'Nguyễn Thị M', 'nguyenmt@example.com', '0987654321', 'Đà Nẵng', '$2y$10$EXAMPLEHASH3', 'customer', '2024-12-07 09:30:00', 'nguyenmt.jpg', 'nguyenmt'),
(19, 'Trần Quốc N', 'tranquocn@example.com', '0923456789', 'Cần Thơ', '$2y$10$EXAMPLEHASH4', 'customer', '2024-12-07 09:40:00', 'tranquocn.jpg', 'tranquocn'),
(20, 'Bùi Thị O', 'buiothi@example.com', '0911122334', 'Hà Nội', '$2y$10$EXAMPLEHASH5', 'customer', '2024-12-07 09:50:00', 'buiothi.jpg', 'buiothi'),
(21, 'Võ Tấn P', 'votnp@example.com', '0933445567', 'Hồ Chí Minh', '$2y$10$EXAMPLEHASH6', 'customer', '2024-12-07 10:00:00', 'votnp.jpg', 'votnp'),
(22, 'Nguyễn Văn Q', 'nguyenvq@example.com', '0977889901', 'Đà Nẵng', '$2y$10$EXAMPLEHASH7', 'customer', '2024-12-07 10:10:00', 'nguyenvq.jpg', 'nguyenvq'),
(23, 'Lê Thị R', 'lethir@example.com', '0932334455', 'Cần Thơ', '$2y$10$EXAMPLEHASH8', 'customer', '2024-12-07 10:20:00', 'lethir.jpg', 'lethir'),
(24, 'Trương Minh S', 'truongmins@example.com', '0988776655', 'Hà Nội', '$2y$10$EXAMPLEHASH9', 'customer', '2024-12-07 10:30:00', 'truongmins.jpg', 'truongmins'),
(25, 'Phan Thị T', 'phanthit@example.com', '0912456789', 'Hồ Chí Minh', '$2y$10$EXAMPLEHASH10', 'customer', '2024-12-07 10:40:00', 'phanthit.jpg', 'phanthit');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
