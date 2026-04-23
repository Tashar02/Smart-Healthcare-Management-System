-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 05:39 AM
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
-- Database: `restaurent`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `c_name` varchar(250) NOT NULL,
  `short_desc` varchar(250) NOT NULL,
  `long_desc` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `c_name`, `short_desc`, `long_desc`) VALUES
(7, 'North Indian', 'This is a popular category in Northern India', 'Indian cuisine encompasses a wide variety of regional cuisine native to India. Given the range of diversity in soil type, climate and occupations, these cuisines vary significantly from each other and use locally available chocolates, herbs, vegetables and fruits. The dishes are then served according to taste in either mild, medium or hot. Indian food is also heavily influenced by religious and cultural choices, like Hinduism and traditions.'),
(8, 'Chinese', 'Chinese cuisine is an important part of Chinese culture, which includes cuisine originating from the diverse regions of China.', 'A number of different styles contribute to Chinese cuisine but perhaps the best known and most influential are Cantonese cuisine, Shandong cuisine, Jiangsu cuisine (specifically Huaiyang cuisine) and Sichuan cuisine.'),
(9, 'South Indian', 'South Indian cuisine includes the cuisines of the five southern states of India Andhra Pradesh, Karnataka, Kerala, Tamil Nadu and Telangana.', 'The cuisines of Andhra Pradesh are the spiciest in all of India. Generous use of chili and tamarind make the dishes tangy and hot. The majority of dishes are vegetable or lentil-based.'),
(10, 'Snacks', ' A snack is a small portion of food eaten between meals.', 'A snack is a small portion of food eaten between meals. This may be a snack food, such as potato chips or baby carrots, but can also simply be a small amount of any food.'),
(11, 'Himalayan Food', 'Nepalese cuisine comprises a variety of cuisines based upon ethnicity, soil and climate relating to Nepal cultural diversity and geography.', 'Much of the cuisine is variation on Asian themes. Other foods have hybrid Tibetan, Indian and Thai origins. They were originally filled with buffalo meat but now also with goat or chicken, as well as vegetarian preparations. Special foods such as sel roti, finni roti and patre are eaten during festivals such as Tihar.'),
(13, 'dgyhgdhfj', '33wetryty dsthjgredsf', 'sdgzfhjgkffhghdfjgkujh,jh hjfgktyg'),
(14, 'dgyhgdhfj', 'sdftyjgkuilukujyhfgdsdasfzgdrhtjujgh', 'srtr awtyruyi retrytuyi');

-- --------------------------------------------------------

--
-- Table structure for table `delivary`
--

CREATE TABLE `delivary` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivary`
--

INSERT INTO `delivary` (`id`, `name`, `address`, `phone`) VALUES
(1, 'Ashifur Rahman', 'Jashore', '01925221970');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` int(11) NOT NULL,
  `cat_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(250) NOT NULL,
  `price` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `cat_id`, `name`, `image`, `price`) VALUES
(1, 9, 'Dosa', 'fo1.jpg', 75),
(7, 7, 'Egg Role', 'fo2.jpg', 100),
(8, 8, 'Chowmin', 'fo3.jpg', 250),
(9, 10, 'French Fries', 'fo4.jpg', 75),
(10, 11, 'Momos', 'fo5.jpg', 100),
(11, 8, 'Hakka Noodles', 'fo6.jpg', 150),
(17, 7, 'Briyani', 'fo7.jpg', 300),
(18, 9, 'ABC', 'fo4.jpg', 350);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `orderitem` varchar(100) DEFAULT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `paymentmethod` varchar(100) DEFAULT NULL,
  `tarnsectionid` varchar(100) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `address`, `number`, `orderitem`, `quantity`, `paymentmethod`, `tarnsectionid`, `price`) VALUES
(1, 'Ashifur Rahman', 'Jashore', '+8801925221970', 'Momos,Hakka Noodles', '1,5', 'bkash', 'td23543676565', '975'),
(5, 'Ashifur Rahman', 'Jashore', '+8801925221970', 'momos', '1', 'bkash', 'td23543676565', '115'),
(6, 'Ashifur Rahman', 'House 30 , Road 31, Rupnagar, Mirpur-2', '01925221970', '2', '2', 'bkash', '', '500'),
(7, 'Ashifur Rahman', 'House 30 , Road 31, Rupnagar, Mirpur-2', '01925221970', '2', '2', 'bkash', '', '500'),
(8, 'Ashifur1111', 'House 30 , Road 31, Rupnagar, Mirpur-2', '01925221970', '3', '3', 'bkash', '', '663.84'),
(9, 'ABC', 'gfhd', '017296545', '3', '3', 'bkash', '', '500');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `timestamp` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `timestamp`) VALUES
(4, 'Ashifur Rahman', 'ashif@gmail.com', '12345', '14:10:2020 07:54:29pm'),
(6, 'ashifur', 'ashifur@gmail.com', '1234', '31:01:2026 01:09:15am'),
(7, 'Ashifur', 'ashifur1234@gmail.com', '12345', '10:03:2026 12:28:08am'),
(8, 'ABC', 'abc@gmail.com', '12345', '10:03:2026 09:14:25am');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivary`
--
ALTER TABLE `delivary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `delivary`
--
ALTER TABLE `delivary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
