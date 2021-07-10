-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2021 at 02:25 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jewellary`
--

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `material_id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `price_per_gram` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`material_id`, `material_name`, `price_per_gram`) VALUES
(3, 'Gold', 10000),
(5, 'Silver', 5000),
(8, 'Copper', 4000);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `ornament_id` varchar(255) NOT NULL,
  `weight` int(10) NOT NULL,
  `delivery_date` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `amount_paid` int(10) NOT NULL,
  `final_amount` int(10) NOT NULL,
  `progress` int(5) NOT NULL,
  `order_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `ornament_id`, `weight`, `delivery_date`, `address`, `amount_paid`, `final_amount`, `progress`, `order_key`) VALUES
(15, 'testing', '6 ', 4, '2021-07-25', 'test desc', 10000, 20000, 0, '60e990665a7a3');

-- --------------------------------------------------------

--
-- Table structure for table `ornaments`
--

CREATE TABLE `ornaments` (
  `ornament_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `ornament_name` varchar(255) NOT NULL,
  `ornament_description` varchar(255) NOT NULL,
  `ornament_weight` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ornaments`
--

INSERT INTO `ornaments` (`ornament_id`, `material_id`, `ornament_name`, `ornament_description`, `ornament_weight`) VALUES
(2, 3, 'Ring', 'Best Ring of the Year 2021', 12),
(5, 8, 'Copper Ring', 'desc', 5),
(6, 5, 'Silver  Ring', 'siver ring desc', 4);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `payment_of` varchar(255) NOT NULL,
  `payment_amount` varchar(255) NOT NULL,
  `total_payment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `payment_of`, `payment_amount`, `total_payment`) VALUES
(7, '60e990665a7a3', '20000', '20000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `ornaments`
--
ALTER TABLE `ornaments`
  ADD PRIMARY KEY (`ornament_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ornaments`
--
ALTER TABLE `ornaments`
  MODIFY `ornament_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
