-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2020 at 03:23 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


USE dsjas


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dsjas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_identifier` int(11) NOT NULL,
  `account_name` text NOT NULL,
  `account_type` enum('current','savings','shared','misc') NOT NULL DEFAULT 'current',
  `account_balance` decimal(11,2) NOT NULL DEFAULT '0.00',
  `holder_name` text NOT NULL,
  `associated_online_account_id` int(11) NOT NULL,
  `account_disabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_identifier`, `account_name`, `account_type`, `account_balance`, `holder_name`, `associated_online_account_id`, `account_disabled`) VALUES
(1, 'Checking Account', 'current', '1200.00', 'Edna Gooseberry', 1, 0),
(2, 'Savings Account', 'savings', '5420.00', 'Edna Gooseberry', 1, 0),
(3, 'War bond', 'misc', '3600.00', 'Edna Gooseberry', 1, 0),
(4, 'Money Market', 'misc', '1500250.42', 'Edna Gooseberry', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `siteusers`
--

CREATE TABLE `siteusers` (
  `user_id` bigint(20) NOT NULL,
  `username` tinytext NOT NULL,
  `real_name` text NOT NULL,
  `password_hash` longtext NOT NULL,
  `password_hint` text NOT NULL,
  `email` text NOT NULL,
  `date_of_registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `new_account` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `siteusers`
--

INSERT INTO `siteusers` (`user_id`, `username`, `real_name`, `password_hash`, `password_hint`, `email`, `date_of_registration`, `account_enabled`, `new_account`) VALUES
(1, 'admin', 'user', '$2y$10$7XxUcG15dzrmzjqSaHAfsuvP26gvlH6WxXaKWctoi.J.pjhBbErPG', 'DSJAS1234', 'admin@localhost.local', '2020-07-03 16:15:09', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` bigint(20) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `origin_account_id` int(11) NOT NULL,
  `dest_account_id` int(11) NOT NULL,
  `transaction_description` text NOT NULL,
  `transaction_type` enum('transfer','withdrawal','purchase','misc') NOT NULL DEFAULT 'transfer',
  `transaction_amount` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `username` tinytext NOT NULL,
  `real_name` text NOT NULL,
  `password_hash` longtext NOT NULL,
  `password_hint` text NOT NULL,
  `email` text NOT NULL,
  `date_of_registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `real_name`, `password_hash`, `password_hint`, `email`, `date_of_registration`) VALUES
(1, 'edna', 'Edna Gooseberry', '$2y$10$sT134tQqUrJefXCT0m2qeu28SNlKcMbMobCyG9CX/ZwrzHa3t8Zmu', 'Hunter2', 'edna.g@aol.com', '2020-07-03 16:14:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_identifier`);

--
-- Indexes for table `siteusers`
--
ALTER TABLE `siteusers`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_identifier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siteusers`
--
ALTER TABLE `siteusers`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

--
-- Table structure for table `statistics`
--
CREATE TABLE `dsjas`.`statistics` ( `stat_name` VARCHAR(255) NOT NULL , `stat_type` INT NOT NULL , `stat_value` INT NOT NULL , `stat_label` TEXT NOT NULL , `stat_category` TEXT NOT NULL , `sys_data` BOOLEAN NOT NULL , `theme_def` BOOLEAN NOT NULL ) ENGINE = InnoDB;/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

--
-- Indexes for table `statistics`
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`stat_name`);
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
