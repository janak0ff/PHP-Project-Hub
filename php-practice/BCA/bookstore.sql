-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2023 at 05:29 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `mybook`
--

CREATE TABLE `mybook` (
  `id` int(11) NOT NULL,
  `bookName` varchar(50) NOT NULL,
  `sem` varchar(50) DEFAULT NULL,
  `price` int(50) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mybook`
--

INSERT INTO `mybook` (`id`, `bookName`, `sem`, `price`, `code`) VALUES
(1, 'Computer Fundamentals & Applications', 'first sem', 230, 'CACS101'),
(2, 'Society and Technology', 'first sem', 360, 'CACO102'),
(3, 'English I', 'first sem', 150, 'CAEN103'),
(4, 'Mathematics I', 'first sem', 500, 'CAMT104'),
(5, 'Digital Logic', 'first sem', 900, 'CACS105'),
(6, 'C Programming', 'second sem', 1000, 'CACS151'),
(7, 'Financial Accounting', 'second sem', 950, 'CAAC152'),
(8, 'English II', 'second sem', 250, 'CAEN153'),
(9, 'Microprocessor and Computer Architecture', 'second sem', 400, 'CACS155'),
(10, 'Mathematics II', 'second sem', 1500, 'CAMT154'),
(11, 'Data Structures and Algorithms', 'third sem', 2000, 'CACS201'),
(12, 'Probability and Statistics', 'third sem', 360, 'CAST202'),
(13, 'OOP in Java', 'third sem', 2500, 'CACS204'),
(14, 'Web Technology', 'third sem', 1150, 'CACS205'),
(15, 'System Analysis and Design', 'third sem', 850, 'CACS203');

-- --------------------------------------------------------

--
-- Table structure for table `set_a`
--

CREATE TABLE `set_a` (
  `id` int(11) NOT NULL,
  `bookName` varchar(50) NOT NULL,
  `sem` varchar(50) DEFAULT NULL,
  `price` int(50) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `set_a`
--

INSERT INTO `set_a` (`id`, `bookName`, `sem`, `price`, `code`) VALUES
(1, 'Computer Fundamentals & Applications', 'first sem', 230, 'CACS101'),
(2, 'Society and Technology', 'first sem', 360, 'CACO102'),
(3, 'English I', 'first sem', 150, 'CAEN103'),
(4, 'Mathematics I', 'first sem', 500, 'CAMT104'),
(5, 'Digital Logic', 'first sem', 900, 'CACS105');

-- --------------------------------------------------------

--
-- Table structure for table `set_b`
--

CREATE TABLE `set_b` (
  `id` int(11) NOT NULL,
  `bookName` varchar(50) NOT NULL,
  `sem` varchar(50) DEFAULT NULL,
  `price` int(50) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `set_b`
--

INSERT INTO `set_b` (`id`, `bookName`, `sem`, `price`, `code`) VALUES
(1, 'C Programming', 'second sem', 1000, 'CACS151'),
(2, 'Financial Accounting', 'second sem', 950, 'CAAC152'),
(3, 'English II', 'second sem', 250, 'CAEN153'),
(4, 'Microprocessor and Computer Architecture', 'second sem', 400, 'CACS155'),
(5, 'Mathematics II', 'second sem', 1500, 'CAMT154');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mybook`
--
ALTER TABLE `mybook`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mybook`
--
ALTER TABLE `mybook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
