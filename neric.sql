-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2026 at 12:46 AM
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
-- Database: `neric`
--

-- --------------------------------------------------------

--
-- Table structure for table `bmi`
--

CREATE TABLE `bmi` (
  `bmi_id_db` int(11) NOT NULL,
  `user_id_db` int(11) NOT NULL,
  `bmi_db` int(11) NOT NULL,
  `date_db` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_burn_calories`
--

CREATE TABLE `daily_burn_calories` (
  `id` int(11) NOT NULL,
  `calories_user_id_db` int(11) NOT NULL,
  `daily_burn_calories` float NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_burn_calories`
--

INSERT INTO `daily_burn_calories` (`id`, `calories_user_id_db`, `daily_burn_calories`, `date`) VALUES
(1, 3, 7.32, '2024-12-08'),
(2, 3, 6.34, '2024-12-26'),
(3, 3, 7.32, '2024-12-26'),
(4, 3, 6.34, '2024-12-26'),
(5, 3, 7.32, '2024-12-26'),
(6, 3, 4.6, '2024-12-26'),
(7, 3, 7.32, '2025-01-05'),
(8, 3, 15.01, '2025-01-05'),
(9, 15, 11.75, '2025-11-02'),
(10, 16, 66.63, '2026-03-16');

-- --------------------------------------------------------

--
-- Table structure for table `diet_plans`
--

CREATE TABLE `diet_plans` (
  `id` int(11) NOT NULL,
  `diet_name` varchar(255) NOT NULL,
  `diet_image` varchar(255) NOT NULL,
  `diet_description` text NOT NULL,
  `what_is_it` text NOT NULL,
  `why_needed` text NOT NULL,
  `health_benefits` text NOT NULL,
  `disadvantages` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plans`
--

INSERT INTO `diet_plans` (`id`, `diet_name`, `diet_image`, `diet_description`, `what_is_it`, `why_needed`, `health_benefits`, `disadvantages`, `created_at`) VALUES
(1, 'desi plan', 'uploads/captured-photo.jpeg', 'abc 7 days', 'good health', 'yesgood health', 'yesgood health', 'yes notgoodhealth', '2024-10-28 13:50:28'),
(2, 'desi plan1', 'uploads/captured-photo (1).jpeg', 'desc   7days', 'info ', 'yesxc bjdschldchcj', 'noh dkh dchk fhf', 'yessssdbcdckdc', '2024-10-28 14:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `food_id_db` bigint(25) NOT NULL,
  `user_id_db` int(10) DEFAULT NULL,
  `food_name_db` varchar(12) DEFAULT NULL,
  `food_calories_db` decimal(6,2) DEFAULT NULL,
  `protein_db` decimal(6,2) DEFAULT NULL,
  `carbohydrates_db` decimal(6,2) DEFAULT NULL,
  `fats_db` decimal(6,2) DEFAULT NULL,
  `sugar_db` decimal(6,2) DEFAULT NULL,
  `quantity_db` decimal(6,2) DEFAULT NULL,
  `meal_time_db` enum('Breakfast','Lunch','Dinner','Snacks') DEFAULT NULL,
  `date_db` date DEFAULT current_timestamp(),
  `diet_plan_db` tinyint(1) NOT NULL,
  `other_sources_db` tinyint(1) NOT NULL,
  `is_checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`food_id_db`, `user_id_db`, `food_name_db`, `food_calories_db`, `protein_db`, `carbohydrates_db`, `fats_db`, `sugar_db`, `quantity_db`, `meal_time_db`, `date_db`, `diet_plan_db`, `other_sources_db`, `is_checked`) VALUES
(1, 3, 'orange juice', 4.60, 0.10, 1.10, 0.00, 0.80, 10.00, 'Lunch', '2024-10-27', 0, 0, 0),
(2, 3, 'dal', 1.00, 0.10, 0.20, 0.00, 0.00, 1.00, 'Breakfast', '2024-10-27', 0, 0, 0),
(3, 3, 'dal', 1021.80, 65.90, 160.50, 19.50, 21.10, 1000.00, 'Dinner', '2024-10-27', 0, 0, 0),
(4, 3, 'dal', 10.20, 0.70, 1.60, 0.20, 0.20, 10.00, '', '2024-11-01', 0, 0, 0),
(5, 15, 'apple', 5.30, 0.00, 1.40, 0.00, 1.00, 10.00, 'Breakfast', '2025-11-02', 0, 0, 0),
(6, 15, 'dal', 10.20, 0.70, 1.60, 0.20, 0.20, 10.00, 'Lunch', '2025-11-02', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `food_goals`
--

CREATE TABLE `food_goals` (
  `food_goal_id` bigint(20) NOT NULL,
  `user_id_db` int(11) NOT NULL,
  `calories_goal_db` bigint(20) NOT NULL,
  `carbs_goal_db` int(11) NOT NULL,
  `fat_goal_db` int(11) NOT NULL,
  `sugar_goal_db` int(11) NOT NULL,
  `diet_plan_name_db` int(11) NOT NULL,
  `diet_goal_db` int(11) NOT NULL,
  `date_db` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remaining_calories`
--

CREATE TABLE `remaining_calories` (
  `Remaining_food_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Food_name_db` varchar(10) NOT NULL,
  `calories_remain_db` int(11) NOT NULL,
  `Carbs_remain_db` int(11) NOT NULL,
  `Fat_goals_db` int(11) NOT NULL,
  `Sugar_goal_db` int(11) NOT NULL,
  `is_check_db` tinyint(1) NOT NULL,
  `diet_plan_db` int(11) NOT NULL,
  `date_db` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `id_db` int(14) NOT NULL,
  `email_db` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password_db` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `status_db` enum('active','deactivated') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signup`
--

INSERT INTO `signup` (`id_db`, `email_db`, `password_db`, `reset_token_hash`, `reset_token_expires_at`, `status_db`) VALUES
(2, 'ommpatil229@gmail.com', '$2y$10$g47PvsqCNU7EyXWqxWjJ0ehX4UOUEQvqGEln/Li6EG2hS3FZlg7Je', NULL, NULL, 'active'),
(3, 'Parekhvraj45@gmail.com', '$2y$10$XIcVtVEblWN760YyFiab4OVvT4fqsgUkQE5e1vbUsfaNG4f/ZAztu', '7031a75f8319b31ed0960785e38a96a48c13595adc465d193bf8a930bb73ed5c', '2025-02-26 09:05:53', 'active'),
(4, 'Parekhvraj24@gmail.com', '$2y$10$7GXpJnVG1lIwBKJbTNx4QefUqtM6CgSBZYpleEyklgxkD8hs5NTVa', NULL, NULL, 'active'),
(5, 'Parekhvraj2@gmail.com', '$2y$10$2Ho6nbWZ3sBlAATsNnEs1uj8oAj1QTkR2Omm5qk4fEcXdqrfi3EAi', NULL, NULL, 'active'),
(8, 'parekhvraj5@gmail.com', '$2y$10$PhRIPhmz3h8WZcKwLaQdxOo/SSxfA5y7dq39OUA8WuUkeVo9SXW/K', NULL, NULL, 'active'),
(9, 'nairdev2514@gmail.com', '$2y$10$Cj.OSYqFaYw4Y4AnYA9v2.cEQ/ecE9bNGaZCZxuHdAs/brbQCXEfC', NULL, NULL, 'active'),
(10, 'Parekhvraj33@gmail.com', '$2y$10$rJvaxTB7EqoE8TBW.BnHWOPYpFQ9yRPU5Fsp07M6xxLnyIyKD5jf.', NULL, NULL, 'active'),
(11, 'Parekh24@gmail.com', '$2y$10$eS00gTIB3pfuCoaT/83g0ODApZrYALNs.cZ1CaqmaNi4r.2eTtsqO', NULL, NULL, 'active'),
(12, 'nairdev004@gmail.com', '$2y$10$9zD8JMa9xV3OmlC9fcx5a.QmPH/s4ZUEhGZNmTm4Ct/YA/b1bAnHO', NULL, NULL, 'active'),
(13, 'Parekhvraj@gmail.com', '$2y$10$qcAkZcLHQCikCI7qe56LluGfj1sR6g718a54J6A2NiRTJN/hZOGDS', NULL, NULL, 'active'),
(14, 'ayan@gmail.com', '$2y$10$efrgjX.yfZ5idY9nYpCBxuu6IPLVD5YABoS0m0IfA.FiXPOC47p7a', NULL, NULL, 'active'),
(15, 'parekhvraj1@gmail.com', '$2y$10$dyEoW3/bdvNsxPE0MMFPSepTci.D.OtUzN1BS.FKQ7JeI0VWPClxm', NULL, NULL, 'active'),
(16, 'parekhv4@uni.coventry.ac.uk', '$2y$10$1HcziTcYUjYAHBdFFgTxbudcaXnREtR.of2R5E4Wab/DEAt/4wAiK', NULL, NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `userdetails_db`
--

CREATE TABLE `userdetails_db` (
  `id_db` int(11) NOT NULL,
  `name_db` varchar(15) DEFAULT NULL,
  `gender_db` varchar(7) DEFAULT NULL,
  `date_of_birth_db` date DEFAULT NULL,
  `phone_number_db` bigint(10) DEFAULT NULL,
  `address_db` varchar(50) DEFAULT NULL,
  `pre_existing_conditions_db` varchar(30) DEFAULT NULL,
  `Previous_surgeries_hospitalizations_db` varchar(30) DEFAULT NULL,
  `Family_medical_history_db` varchar(30) DEFAULT NULL,
  `allergies_db` varchar(30) DEFAULT NULL,
  `medication_current_db` varchar(30) DEFAULT NULL,
  `diet_db` enum('vegetarian','vegan','omnivore','') DEFAULT NULL,
  `physical_activity_level_db` varchar(25) DEFAULT NULL,
  `height_db` double DEFAULT NULL,
  `weight_db` double DEFAULT NULL,
  `current_symptoms_db` varchar(30) DEFAULT NULL,
  `user_image_db` varchar(50) DEFAULT NULL,
  `allegery_file_db` varchar(50) DEFAULT NULL,
  `select_goal` varchar(25) DEFAULT NULL,
  `weekly_user_routine_db` float DEFAULT NULL,
  `goal_weight_db` float DEFAULT NULL,
  `start_streak_db` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdetails_db`
--

INSERT INTO `userdetails_db` (`id_db`, `name_db`, `gender_db`, `date_of_birth_db`, `phone_number_db`, `address_db`, `pre_existing_conditions_db`, `Previous_surgeries_hospitalizations_db`, `Family_medical_history_db`, `allergies_db`, `medication_current_db`, `diet_db`, `physical_activity_level_db`, `height_db`, `weight_db`, `current_symptoms_db`, `user_image_db`, `allegery_file_db`, `select_goal`, `weekly_user_routine_db`, `goal_weight_db`, `start_streak_db`) VALUES
(2, 'om', 'male', '2007-12-30', 520852085, 'surat', '', '', '', '', '', 'vegetarian', 'moderately_active', 5.2, 61, '', 'user_img/captured-photo (7).jpeg', NULL, 'gain_weight', 227, 67, '2024-10-26'),
(3, 'Vraj', 'male', '2007-12-31', 846999088, '17, Shivanjali row\r\nChhapra Gam Navsari', '', '', '', '', '', 'vegetarian', 'very_active', 5.7, 56, '', 'user_img/captured-photo (8).jpeg', NULL, 'gain_weight', 227, 61, '2024-10-26'),
(12, 'Vrajparekh', 'male', '2007-12-30', 846999088, '17, Shivanjali row\r\nChhapra Gam Navsarireh', '', '', '', '', '', 'omnivore', 'lightly_active', 5, 55, '', 'user_img/', NULL, 'loss_weight', 0, 0, '2024-12-26'),
(14, 'Vraj', 'male', '2007-12-30', 846999088, '17, Shivanjali row\r\nChhapra Gam Navsari', '', '', '', '', '', 'vegetarian', 'moderately_active', 5, 55, '', 'user_img/', NULL, 'gain_weight', 227, 0, '2025-02-26'),
(15, 'Vraj', 'male', '2007-12-31', 846999088, '17, Shivanjali row\r\nChhapra Gam Navsari', '', '', '', '', '', 'vegetarian', 'moderately_active', 5, 58, '', 'user_img/', NULL, 'gain_weight', 227, 0, '2025-11-02'),
(16, 'Vraj', 'male', '2007-12-31', 846999088, '17, Shivanjali row\r\nChhapra Gam Navsari', '', '', '', '', '', 'vegetarian', 'moderately_active', 5.5, 61, '', 'user_img/', NULL, 'gain_weight', 227, 0, '2026-03-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmi`
--
ALTER TABLE `bmi`
  ADD PRIMARY KEY (`bmi_id_db`),
  ADD KEY `bmi_relation` (`user_id_db`);

--
-- Indexes for table `daily_burn_calories`
--
ALTER TABLE `daily_burn_calories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk` (`calories_user_id_db`);

--
-- Indexes for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`food_id_db`);

--
-- Indexes for table `food_goals`
--
ALTER TABLE `food_goals`
  ADD PRIMARY KEY (`food_goal_id`);

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`id_db`);

--
-- Indexes for table `userdetails_db`
--
ALTER TABLE `userdetails_db`
  ADD PRIMARY KEY (`id_db`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmi`
--
ALTER TABLE `bmi`
  MODIFY `bmi_id_db` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_burn_calories`
--
ALTER TABLE `daily_burn_calories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `diet_plans`
--
ALTER TABLE `diet_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `food_id_db` bigint(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `food_goals`
--
ALTER TABLE `food_goals`
  MODIFY `food_goal_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `id_db` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `userdetails_db`
--
ALTER TABLE `userdetails_db`
  MODIFY `id_db` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmi`
--
ALTER TABLE `bmi`
  ADD CONSTRAINT `bmi_relation` FOREIGN KEY (`user_id_db`) REFERENCES `userdetails_db` (`id_db`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `daily_burn_calories`
--
ALTER TABLE `daily_burn_calories`
  ADD CONSTRAINT `fk` FOREIGN KEY (`calories_user_id_db`) REFERENCES `userdetails_db` (`id_db`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userdetails_db`
--
ALTER TABLE `userdetails_db`
  ADD CONSTRAINT `foreign_key` FOREIGN KEY (`id_db`) REFERENCES `signup` (`id_db`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
