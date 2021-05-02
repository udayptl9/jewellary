-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 02, 2021 at 05:20 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

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
(5, 'Silver', 5000);

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
  `progress` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `ornament_id`, `weight`, `delivery_date`, `address`, `amount_paid`, `final_amount`, `progress`) VALUES
(2, 'Uday S Patil', '2', 10, '2021-04-06', 'At Post: Hosur', 15000, 20000, 1);

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
(2, 3, 'Ring', 'Best Ring of the Year 2021', 12);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ornaments`
--
ALTER TABLE `ornaments`
  MODIFY `ornament_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
