-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 10:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petconnect_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `user_id`, `type_id`, `name`, `age`, `description`, `image_id`, `created_at`) VALUES
(43, 27, 4, 'Сашо', 2, 'Шарен и любопитен', 73, '2025-12-06 14:45:23'),
(44, 27, 2, 'Томи', 7, 'Постоянно гладен и мързелив', 74, '2025-12-06 14:47:15'),
(45, 28, 1, 'Зевс', 6, 'Величествен и чистокръвен', 77, '2025-12-06 15:12:53'),
(46, 30, 1, 'Lara', 7, 'Обучена да носи пръчка', 79, '2025-12-06 15:21:03'),
(47, 31, 9, 'Ricardo', 7, 'Екзотичен и загадъчен', 82, '2025-12-06 15:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `pet_images`
--

CREATE TABLE `pet_images` (
  `id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pet_images`
--

INSERT INTO `pet_images` (`id`, `path`, `post_id`) VALUES
(73, 'uploads/imgPets/6934418307e2e.jpg', NULL),
(74, 'uploads/imgPets/693441f30f14f.jpg', NULL),
(75, 'uploads/posts/post_69344256b475e2.18907328.jpg', 10),
(76, 'uploads/posts/post_6934449a538b64.95678297.jpg', 11),
(77, 'uploads/imgPets/693447f54380c.jpg', NULL),
(78, 'uploads/posts/post_693448619b8824.62068663.jpg', 12),
(79, 'uploads/imgPets/693449df28ff8.png', NULL),
(80, 'uploads/posts/post_69344a35104d01.90942213.jpg', 13),
(81, 'uploads/posts/post_69344a84c5e7d0.69089012.jpg', 14),
(82, 'uploads/imgPets/69344d666e760.jpg', NULL),
(83, 'uploads/imgPets/69348f8c1d484.jpg', NULL),
(84, 'uploads/imgPets/693492edefb3c.jpg', NULL),
(85, 'uploads/imgPets/69349455c4ef5.jpg', NULL),
(86, 'uploads/imgPets/69349481bdcf5.jpg', NULL),
(87, 'uploads/imgPets/693494beee2f4.jpg', NULL),
(88, 'uploads/imgPets/693494e4041aa.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pet_posts`
--

CREATE TABLE `pet_posts` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `title` varchar(2555) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pet_posts`
--

INSERT INTO `pet_posts` (`id`, `pet_id`, `title`, `content`, `created_at`) VALUES
(10, 44, 'Да не повярвате', 'Днес Томи излезе на лов беше точно 2 минути навън и се прибра да се до наспи.', '2025-12-06 14:48:54'),
(11, 43, 'Сашо си има портрет', 'Вчера се пробвах да рисувам и Сашо беше така добър да позира.', '2025-12-06 14:58:34'),
(12, 45, 'Малко на свеж въздух', 'Днес времето ое прекрасно', '2025-12-06 15:14:41'),
(13, 46, 'Истински модел', 'Как да не я харесаш', '2025-12-06 15:22:29'),
(14, 46, 'Тъжна вест', 'Загубихме любимата й топка. Приема го добре но е тъжна от вътре.', '2025-12-06 15:23:48');

-- --------------------------------------------------------

--
-- Table structure for table `pet_post_comments`
--

CREATE TABLE `pet_post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pet_post_comments`
--

INSERT INTO `pet_post_comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(5, 10, 28, 'Бая е дебел', '2025-12-06 15:15:11'),
(6, 11, 28, 'Добре си се справил', '2025-12-06 15:16:01'),
(7, 10, 30, 'Не е дебел с едър кокал е', '2025-12-06 15:18:30'),
(8, 12, 30, 'Да не е слънчоглед', '2025-12-06 15:19:25'),
(9, 14, 30, 'Ако някой намери жълта топка в парка да каже', '2025-12-06 15:24:54'),
(11, 14, 31, 'Дано да я намерите', '2025-12-06 15:37:39');

-- --------------------------------------------------------

--
-- Table structure for table `pet_post_likes`
--

CREATE TABLE `pet_post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pet_post_likes`
--

INSERT INTO `pet_post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(18, 10, 27, '2025-12-06 14:58:50'),
(19, 11, 28, '2025-12-06 15:14:53'),
(20, 10, 28, '2025-12-06 15:14:54'),
(21, 10, 30, '2025-12-06 15:18:40'),
(22, 11, 30, '2025-12-06 15:18:57'),
(23, 14, 30, '2025-12-06 15:25:03'),
(24, 10, 31, '2025-12-06 15:36:38'),
(25, 12, 31, '2025-12-06 15:36:41'),
(26, 11, 31, '2025-12-06 15:36:42'),
(27, 14, 31, '2025-12-06 15:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `pet_types`
--

CREATE TABLE `pet_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pet_types`
--

INSERT INTO `pet_types` (`id`, `name`) VALUES
(4, 'Bird'),
(2, 'Cat'),
(1, 'Dog'),
(5, 'Fish'),
(6, 'Hamster'),
(9, 'Lizard'),
(3, 'Rabbit'),
(8, 'Snake'),
(7, 'Turtle');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(24, '00', '$2y$10$6.6M4.NFBBXLlYNArjiJFuG4qT574/ldOuqohlFfdYOyw6waCbhgG', '00', '2025-12-03 20:13:50'),
(27, 'kirs@gmail.com', '$2y$10$Co3unzTNL53RSFZwztzNX.XfENM8rIh7eKMM9w4brBRxqaoqex522', 'kris', '2025-12-06 14:39:27'),
(28, 'Ivan@gmail.com', '$2y$10$hoYF70/SmuIja.M2iI5eSufNtea2lpNZRC.k91TybeIGV9eMrGI5.', 'Ivan', '2025-12-06 15:08:54'),
(30, 'qwerty2023@gmail.com', '$2y$10$f7k6EmaX8qy1ET9BeBauX.Ups1G.O3njWxh21mIc2B8u6H6EBuJsG', 'Ицо', '2025-12-06 15:17:24'),
(31, 'gogo@gmail.com', '$2y$10$3YlIHDdV.ttD8NOaL0f6qOWve5hBUUOWJqHM6MVaqSKaBQ1vgnhxu', 'Gogo', '2025-12-06 15:34:12'),
(36, 'test@gmail.com', '$2y$10$kkN6jFn2uS5qLm.7DG5CbejqQarF6PkCuTrxykd5zztzOAKZQSwXi', 'test', '2025-12-06 16:28:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `pets_image_id_constraint` (`image_id`);

--
-- Indexes for table `pet_images`
--
ALTER TABLE `pet_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_images_post_id_constraint` (`post_id`);

--
-- Indexes for table `pet_posts`
--
ALTER TABLE `pet_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `pet_post_comments`
--
ALTER TABLE `pet_post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pet_post_likes`
--
ALTER TABLE `pet_post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pet_types`
--
ALTER TABLE `pet_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `pet_images`
--
ALTER TABLE `pet_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `pet_posts`
--
ALTER TABLE `pet_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pet_post_comments`
--
ALTER TABLE `pet_post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pet_post_likes`
--
ALTER TABLE `pet_post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pet_types`
--
ALTER TABLE `pet_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `pet_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pets_image_id_constraint` FOREIGN KEY (`image_id`) REFERENCES `pet_images` (`id`);

--
-- Constraints for table `pet_images`
--
ALTER TABLE `pet_images`
  ADD CONSTRAINT `pet_images_post_id_constraint` FOREIGN KEY (`post_id`) REFERENCES `pet_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_posts`
--
ALTER TABLE `pet_posts`
  ADD CONSTRAINT `pet_posts_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pet_post_comments`
--
ALTER TABLE `pet_post_comments`
  ADD CONSTRAINT `pet_post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `pet_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pet_post_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pet_post_likes`
--
ALTER TABLE `pet_post_likes`
  ADD CONSTRAINT `pet_post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `pet_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pet_post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
