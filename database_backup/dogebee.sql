-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-09-15 08:29:22
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `dogebee`
--

-- --------------------------------------------------------

--
-- 資料表結構 `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `brand` varchar(128) NOT NULL,
  `products` varchar(128) NOT NULL,
  `price` int(11) NOT NULL,
  `style` varchar(128) NOT NULL,
  `remarks` varchar(128) NOT NULL,
  `pictures` varchar(128) NOT NULL,
  `link` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `items`
--

INSERT INTO `items` (`id`, `brand`, `products`, `price`, `style`, `remarks`, `pictures`, `link`) VALUES
(1, 'Sennheiser', 'IE800S', 3799, 'Neutral', 'in', './photos/products/ie800s.png', './product_ie800s.html'),
(2, 'Sennheiser', 'HD 660S', 2490, 'Neutral', 'over', './photos/products/hd660s.png', ''),
(3, 'Sennheiser', 'HD 490Pro', 2770, 'Neutral', 'over', './photos/products/hd490pro.png', ''),
(4, 'Sennheiser', 'HD 660S2', 3040, 'Neutral', 'over', './photos/products/hd660s2.jpg', ''),
(5, 'Sennheiser', 'HE-1', 570000, 'Neutral', 'over', './photos/products/he-1.jpg', ''),
(6, 'Sennheiser', 'IE100 Pro', 780, 'Bright', 'in', './photos/products/ie100pro.png', ''),
(7, 'Sennheiser', 'IE600', 3580, 'Bright', 'in', './photos/products/ie600.png', ''),
(8, 'Sennheiser', 'IE900', 7900, 'Bright', 'in', './photos/products/ie900.jpg', ''),
(9, 'Sennheiser', 'HD600NEW', 2150, 'Bright', 'over', './photos/products/hd600_new.jpg', ''),
(10, 'Sennheiser', 'HD800S', 8000, 'Bright', 'over', './photos/products/hd800s.jpg', ''),
(11, 'Sennheiser', 'HD650', 2400, 'Bass', 'over', './photos/products/hd650.png', ''),
(12, 'Beyerdynamic', 'DT900 Pro X', 2290, 'Neutral', 'over', './photos/products/dt900prox.png', ''),
(13, 'Beyerdynamic', 'T1 v3', 5980, 'Bass', 'over', './photos/products/t1v3.jpg', ''),
(14, 'Audio-Technica', 'ATH-M20X', 380, 'Neutral', 'over', './photos/products/ath-m20x.jpg', ''),
(15, 'Audio-Technica', 'ATH-AWKT', 14230, 'Bass', 'over', './photos/products/ath-awkt.jpg', ''),
(16, 'Moondrop', 'Aria2', 580, 'Neutral', 'in', './photos/products/aria2.jpg', ''),
(17, 'Moondrop', '星野2', 800, 'Neutral', 'in', './photos/products/星野2.jpg', ''),
(18, 'Moondrop', '菊DSP', 150, 'Bright', 'in', './photos/products/菊DSP.png', ''),
(19, 'Moondrop', '蘭', 250, 'Bright', 'in', './photos/products/蘭.jpg', ''),
(20, 'Moondrop', '夏空', 1100, 'Bright', 'half-in', './photos/products/夏空.jpg', ''),
(21, 'Moondrop', 'Para', 2400, 'Bright', 'over', './photos/products/para.jpg', ''),
(22, 'Shure', 'KSE 1500', 23380, 'Neutral', 'in', './photos/products/kse1500.jpg', ''),
(23, 'Shure', '840A', 1010, 'Unkown', 'over', './photos/products/840a.png', ''),
(24, 'Sony', 'MDR-MV1', 2750, 'Neutral', 'over', './photos/products/mdr-mv1.png', ''),
(25, 'Sony', 'IER-M9', 5970, 'Bass', 'in', './photos/products/ier-m9.jpg', ''),
(26, 'Sony', 'IER-M7', 3070, 'Bass', 'in', './photos/products/ier-m7.jpg', ''),
(27, 'Sony', 'MDR-CD900ST', 1390, 'Unkown', 'over', './photos/products/mdr-cd900st.jpg', ''),
(28, 'Audeze', 'MM-100', 2800, 'Neutral', 'over', './photos/products/mm-100.jpg', ''),
(29, 'Audeze', 'MM-500', 14000, 'Neutral', 'over', './photos/products/mm-500.jpg', ''),
(30, 'AKG', 'K701', 1480, 'Unkown', 'over', './photos/products/k701.png', ''),
(31, 'AKG', 'K702', 1390, 'Unkown', 'over', './photos/products/k702.png', ''),
(32, 'AKG', 'K712 Pro', 2280, 'Bright', 'over', './photos/products/k712pro.png', ''),
(33, 'Philips', 'SHP 9500', 800, 'Bright', 'over', './photos/products/shp9500.jpg', ''),
(34, 'Philips', 'X2HR', 750, 'Bass', 'over', './photos/products/x2hr.jpg', ''),
(35, 'Philips', 'X3', 1100, 'Bright', 'over', './photos/products/x3.jpg', ''),
(36, 'NiceHCK', '喜馬拉雅', 1850, 'Bright', 'in', './photos/products/himalaya.jpg', ''),
(37, 'NiceHCK', '小藍帽', 150, 'Unkown', 'half-in', './photos/products/bluecap.png', ''),
(38, 'Final', 'E3000', 380, 'Bass', 'in', './photos/products/e3000.jpg', ''),
(39, 'Final', 'D8000 Pro', 22980, 'Bass', 'over', './photos/products/d8000pro.jpg', ''),
(42, 'er', 'er', 20, 'Neutral', 'over', 'photos/687f9a4c307d7.png', 'er');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(128) NOT NULL,
  `order_date` varchar(128) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(128) NOT NULL,
  `quantity` int(11) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `image_url` varchar(128) NOT NULL,
  `price_per_item` int(11) NOT NULL,
  `email` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `order_date`, `amount`, `status`, `quantity`, `item_name`, `image_url`, `price_per_item`, `email`) VALUES
(1, 'ORD-68b31a90624f6', '', 8690, 'test', 1, 'Bright Sennheiser IE900', './photos/products/ie900.jpg', 7900, 's201432@uccke.edu.hk'),
(2, 'ORD-68b31b4f5d550', '2025-08-30 17:39:59', 165, '0', 1, 'Bright Moondrop 菊DSP', './photos/products/菊DSP.png', 150, 's201432@uccke.edu.hk'),
(3, 'ORD-68b31d2e4d085', '2025-08-30 17:47:58', 4179, '0', 1, 'Neutural Sennheiser IE800S', './photos/products/ie800s.png', 3799, 's201432@uccke.edu.hk'),
(4, 'ORD-68b31d3902e1f', '2025-08-30 17:48:09', 2739, '0', 1, 'Neutural Sennheiser HD 660S', './photos/products/hd660s.png', 2490, 's201432@uccke.edu.hk'),
(5, 'ORD-68b31dee44cc7', '2025-08-30 17:51:10', 4179, '0', 1, 'Neutural Sennheiser IE800S', './photos/products/ie800s.png', 3799, 's201432@uccke.edu.hk'),
(6, 'ORD-68b31e1b641ab', '2025-08-30 17:51:55', 2739, '0', 1, 'Neutural Sennheiser HD 660S', './photos/products/hd660s.png', 2490, 's201432@uccke.edu.hk'),
(7, 'ORD-68b31e301f35e', '2025-08-30 17:52:16', 4179, '0', 1, 'Neutural Sennheiser IE800S', './photos/products/ie800s.png', 3799, 's201432@uccke.edu.hk'),
(8, 'ORD-68b31ee241458', '2025-08-30 17:55:14', 4179, '0', 1, 'Neutural Sennheiser IE800S', './photos/products/ie800s.png', 3799, 's201432@uccke.edu.hk'),
(9, 'ORD-68b31ef83156a', '2025-08-30 17:55:36', 5786, '0', 1, 'Neutural Sennheiser HD 660S', './photos/products/hd660s.png', 2490, 's201432@uccke.edu.hk'),
(10, 'ORD-68b31ef83156a', '2025-08-30 17:55:36', 5786, '0', 1, 'Neutural Sennheiser HD 490Pro', './photos/products/hd490pro.png', 2770, 's201432@uccke.edu.hk'),
(11, 'ORD-68b31f2d0a7df', '2025-08-30 17:56:29', 858, '0', 1, 'Bright Sennheiser IE100 Pro', './photos/products/ie100pro.png', 780, 'eee@c'),
(12, 'ORD-68b812c502009', '2025-09-03 12:04:53', 3047, '0', 1, 'Neutral Sennheiser HD 490Pro', './photos/products/hd490pro.png', 2770, 'eee@c');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `contact`, `city`, `address`, `admin`) VALUES
(1, 'Noah', 's201432@uccke.edu.hk', '12345678', '', 'Hong Kong', '1, Lee Hong Lane', 0),
(2, '404', 'eee@c', 'eee', 'eee', 'eee', 'eee', 1),
(3, '123', '123@1', '123', '123', '123', '123', 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
