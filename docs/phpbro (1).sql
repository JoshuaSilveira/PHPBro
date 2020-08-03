-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 23, 2020 at 10:04 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.10

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
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `is_findable` tinyint(1) NOT NULL,
  `experience` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `current_weight` int(11) DEFAULT NULL,
  `current_height` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `city`, `is_findable`, `experience`, `first_name`, `last_name`, `current_weight`, `current_height`, `is_admin`) VALUES
(1, 'sam', '5f4dcc3b5aa765d61d8327deb882cf99', 'sam.bebenek@gmail.com', 'Toronto', 1, 0, 'Sam', 'Bebenek', 210, 69, 1),
(2, 'bob', '084e0343a0486ff05530df6c705c8bb4', 'bob@test.com', 'Toronto', 1, 0, 'Bob', 'Roberts', 190, 78, 0),
(3, 'robert', '084e0343a0486ff05530df6c705c8bb4', 'robert@test.com', 'Mississauga', 1, 0, 'Robert', 'Bobson', 345, 63, 0);

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
  `description` varchar(1024) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` (`id`, `name`, `image_ext`, `calories`, `protein`, `prep_time`, `is_vegan`, `description`, `url`) VALUES
(1, 'Carrot Soup', '', 176, 6, 50, 1, 'This easy carrot soup recipe is a great way to use up a bag of carrots that were forgotten in your produce drawer. The carrots cook together with aromatics like onions, garlic and fresh herbs before being puréed into a silky smooth soup that\'s delicious for dinner or packed up for lunch. Source: EatingWell Magazine, Soup Cookbook', 'http://www.eatingwell.com/recipe/249990/carrot-soup/'),
(2, 'Vegan Smoothie Bowl', NULL, 338, NULL, 10, 0, NULL, 'http://www.eatingwell.com/recipe/256740/vegan-smoothie-bowl/'),
(3, 'Banana', NULL, 110, NULL, 0, 1, 'A plain banana.', 'https://www.hsph.harvard.edu/nutritionsource/food-features/bananas/'),
(4, 'Beer Lime Grilled Chicken', '', 182, NULL, 54, 0, NULL, 'https://www.allrecipes.com/recipe/62423/beer-lime-grilled-chicken/?internalSource=staff%20pick&referringId=1232&referringContentType=Recipe%20Hub'),
(6, 'Orange', '', 10, 0, 10, 1, 'A plain old orange', 'www.google.com');

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
  ADD UNIQUE KEY `usename` (`username`),
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meals`
--
ALTER TABLE `meals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
