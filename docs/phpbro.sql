-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2020 at 07:36 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpbro`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `usename` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `is_findable` tinyint(1) NOT NULL,
  `experience` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `current_weight` int(11) DEFAULT NULL,
  `current_height` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `calories`
--

CREATE TABLE `calories` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `intake` int(11) NOT NULL,
  `burned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `body_weight` tinyint(1) NOT NULL,
  `cardio` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `exercises_x_categories`
--

CREATE TABLE `exercises_x_categories` (
  `id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `intent` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_parent` tinyint(1) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_complete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meals`
--

CREATE TABLE `meals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_ext` varchar(255) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `protein` int(11) DEFAULT NULL,
  `prep_time` int(11) DEFAULT NULL,
  `is_vegan` tinyint(1) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `planners`
--

CREATE TABLE `planners` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `monday_exercise_id` int(11) DEFAULT NULL,
  `tuesday_exercise_id` int(11) DEFAULT NULL,
  `wednesday_exercise_id` int(11) DEFAULT NULL,
  `thursday_exercise_id` int(11) DEFAULT NULL,
  `friday_exercise_id` int(11) DEFAULT NULL,
  `saturday_exercise_id` int(11) DEFAULT NULL,
  `sunday_exercise_id` int(11) DEFAULT NULL,
  `planner_is_ongoing` tinyint(1) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_address` varchar(255) NOT NULL,
  `end_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `work_out_items`
--

CREATE TABLE `work_out_items` (
  `id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reps` int(11) DEFAULT NULL,
  `sets` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usename` (`usename`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `calories`
--
ALTER TABLE `calories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercises_x_categories`
--
ALTER TABLE `exercises_x_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercise_id` (`exercise_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planners`
--
ALTER TABLE `planners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `monday_exercise_id` (`monday_exercise_id`),
  ADD KEY `tuesday_exercise_id` (`tuesday_exercise_id`),
  ADD KEY `wednesday_exercise_id` (`wednesday_exercise_id`),
  ADD KEY `thursday_exercise_id` (`thursday_exercise_id`),
  ADD KEY `friday_exercise_id` (`friday_exercise_id`),
  ADD KEY `saturday_exercise_id` (`saturday_exercise_id`),
  ADD KEY `sunday_exercise_id` (`sunday_exercise_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `work_out_items`
--
ALTER TABLE `work_out_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercise_id` (`exercise_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calories`
--
ALTER TABLE `calories`
  ADD CONSTRAINT `calories_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `exercises_x_categories`
--
ALTER TABLE `exercises_x_categories`
  ADD CONSTRAINT `exercises_x_categories_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `exercises_x_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `planners`
--
ALTER TABLE `planners`
  ADD CONSTRAINT `planners_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `planners_ibfk_2` FOREIGN KEY (`monday_exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `planners_ibfk_3` FOREIGN KEY (`tuesday_exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `planners_ibfk_4` FOREIGN KEY (`wednesday_exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `planners_ibfk_5` FOREIGN KEY (`thursday_exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `planners_ibfk_6` FOREIGN KEY (`friday_exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `planners_ibfk_7` FOREIGN KEY (`saturday_exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `planners_ibfk_8` FOREIGN KEY (`sunday_exercise_id`) REFERENCES `exercises` (`id`);

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `work_out_items`
--
ALTER TABLE `work_out_items`
  ADD CONSTRAINT `work_out_items_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`),
  ADD CONSTRAINT `work_out_items_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
