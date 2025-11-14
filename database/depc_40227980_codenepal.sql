-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.dep.com.np
-- Generation Time: Nov 09, 2025 at 07:41 AM
-- Server version: 10.6.22-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `depc_40227980_codenepal`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_generated_exercises`
--

CREATE TABLE `ai_generated_exercises` (
  `id` int(11) NOT NULL,
  `lesson_temp_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `starter_code` text DEFAULT NULL,
  `solution` text DEFAULT NULL,
  `difficulty` varchar(20) DEFAULT NULL,
  `points` int(11) DEFAULT 10,
  `exercise_type` enum('standard','ai-validated') DEFAULT 'standard',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_generated_lessons`
--

CREATE TABLE `ai_generated_lessons` (
  `id` int(11) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `lesson_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `topics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `ai_generated_test_cases`
--

CREATE TABLE `ai_generated_test_cases` (
  `id` int(11) NOT NULL,
  `exercise_temp_id` int(11) NOT NULL,
  `input` text DEFAULT NULL,
  `expected_output` text NOT NULL,
  `is_hidden` tinyint(1) DEFAULT 0,
  `order_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_generation_log`
--

CREATE TABLE `ai_generation_log` (
  `id` int(11) NOT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `action_type` varchar(50) NOT NULL,
  `prompt_text` text DEFAULT NULL,
  `response_text` longtext DEFAULT NULL,
  `response_time_ms` int(11) DEFAULT NULL,
  `success` tinyint(1) DEFAULT 1,
  `error_message` text DEFAULT NULL,
  `retry_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_generation_sessions`
--

CREATE TABLE `ai_generation_sessions` (
  `id` varchar(50) NOT NULL,
  `admin_user_id` varchar(36) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_description` text DEFAULT NULL,
  `difficulty` varchar(20) DEFAULT NULL,
  `lesson_count` int(11) NOT NULL,
  `status` enum('outline','generating','completed','failed','cancelled') DEFAULT 'outline',
  `progress_percentage` int(11) DEFAULT 0,
  `current_lesson` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_validation_results`
--

CREATE TABLE `ai_validation_results` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `exercise_id` varchar(50) NOT NULL,
  `user_code` text NOT NULL,
  `ai_score` int(11) NOT NULL,
  `ai_feedback` text DEFAULT NULL,
  `passed` tinyint(1) DEFAULT 0,
  `validated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `course_id` varchar(50) NOT NULL,
  `verification_code` varchar(50) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completed_exercises`
--

CREATE TABLE `completed_exercises` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `exercise_id` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `completed_exercises`
--

INSERT INTO `completed_exercises` (`id`, `user_id`, `exercise_id`, `score`, `completed_at`) VALUES
(9, 'admin-001', 'py-ex-1', 100, '2025-10-28 12:12:36'),
(10, 'admin-001', 'py-ex-2', 100, '2025-10-28 12:13:07'),
(11, 'admin-001', 'py-ex-2-2', 100, '2025-10-28 12:13:44'),
(12, 'admin-001', 'py-ex-3', 100, '2025-10-28 12:31:22'),
(13, 'admin-001', 'py-ex-4', 100, '2025-10-28 12:35:34'),
(14, 'admin-001', 'py-ex-4-2', 100, '2025-10-28 12:39:29'),
(16, 'admin-001', 'py-ex-10', 100, '2025-10-28 14:48:34'),
(17, 'admin-001', 'py-ex-11', 100, '2025-10-28 14:48:34'),
(18, 'admin-001', 'py-ex-12', 100, '2025-10-28 14:48:34'),
(19, 'admin-001', 'py-ex-13', 100, '2025-10-28 14:48:34'),
(20, 'admin-001', 'py-ex-14', 100, '2025-10-28 14:48:34'),
(21, 'admin-001', 'py-ex-15', 100, '2025-10-28 14:48:34'),
(22, 'admin-001', 'py-ex-16', 100, '2025-10-28 14:48:34'),
(23, 'admin-001', 'py-ex-17', 100, '2025-10-28 14:48:34'),
(24, 'admin-001', 'py-ex-18', 100, '2025-10-28 14:48:34'),
(25, 'admin-001', 'py-ex-19', 100, '2025-10-28 14:48:34'),
(28, 'admin-001', 'py-ex-20', 100, '2025-10-28 14:48:34'),
(29, 'admin-001', 'py-ex-21', 100, '2025-10-28 14:48:34'),
(30, 'admin-001', 'py-ex-22', 100, '2025-10-28 14:48:34'),
(31, 'admin-001', 'py-ex-23', 100, '2025-10-28 14:48:34'),
(32, 'admin-001', 'py-ex-24', 100, '2025-10-28 14:48:34'),
(33, 'admin-001', 'py-ex-25', 100, '2025-10-28 14:48:34'),
(34, 'admin-001', 'py-ex-26', 100, '2025-10-28 14:48:34'),
(35, 'admin-001', 'py-ex-27', 100, '2025-10-28 14:48:34'),
(36, 'admin-001', 'py-ex-28', 100, '2025-10-28 14:48:34'),
(37, 'admin-001', 'py-ex-29', 100, '2025-10-28 14:48:34'),
(39, 'admin-001', 'py=ex=30', 100, '2025-10-28 14:48:34'),
(42, 'admin-001', 'py-ex-4-3', 100, '2025-10-28 14:48:34'),
(43, 'admin-001', 'py-ex-5', 100, '2025-10-28 14:48:34'),
(44, 'admin-001', 'py-ex-6', 100, '2025-10-28 14:48:34'),
(45, 'admin-001', 'py-ex-7', 100, '2025-10-28 14:48:34'),
(46, 'admin-001', 'py-ex-8', 100, '2025-10-28 14:48:34'),
(47, 'admin-001', 'py-ex-9', 100, '2025-10-28 14:48:34');

-- --------------------------------------------------------

--
-- Table structure for table `completed_lessons`
--

CREATE TABLE `completed_lessons` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `lesson_id` varchar(50) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `completed_lessons`
--

INSERT INTO `completed_lessons` (`id`, `user_id`, `lesson_id`, `completed_at`) VALUES
(5, 'admin-001', 'py-adv-lesson-1', '2025-10-23 10:10:01'),
(6, 'admin-001', 'py-adv-lesson-2', '2025-10-23 10:24:10'),
(9, 'admin-001', 'py-adv-lesson-3', '2025-10-28 12:31:22'),
(10, 'admin-001', 'py-adv-lesson-4', '2025-10-28 12:35:34'),
(13, 'admin-001', 'py-adv-lesson-10', '2025-10-28 14:48:34'),
(14, 'admin-001', 'py-adv-lesson-11', '2025-10-28 14:48:34'),
(15, 'admin-001', 'py-adv-lesson-12', '2025-10-28 14:48:34'),
(16, 'admin-001', 'py-adv-lesson-13', '2025-10-28 14:48:34'),
(17, 'admin-001', 'py-adv-lesson-14', '2025-10-28 14:48:34'),
(18, 'admin-001', 'py-adv-lesson-15', '2025-10-28 14:48:34'),
(19, 'admin-001', 'py-adv-lesson-16', '2025-10-28 14:48:34'),
(20, 'admin-001', 'py-adv-lesson-17', '2025-10-28 14:48:34'),
(21, 'admin-001', 'py-adv-lesson-18', '2025-10-28 14:48:34'),
(22, 'admin-001', 'py-adv-lesson-19', '2025-10-28 14:48:34'),
(24, 'admin-001', 'py-adv-lesson-20', '2025-10-28 14:48:34'),
(25, 'admin-001', 'py-adv-lesson-21', '2025-10-28 14:48:34'),
(26, 'admin-001', 'py-adv-lesson-22', '2025-10-28 14:48:34'),
(27, 'admin-001', 'py-adv-lesson-23', '2025-10-28 14:48:34'),
(28, 'admin-001', 'py-adv-lesson-24', '2025-10-28 14:48:34'),
(29, 'admin-001', 'py-adv-lesson-25', '2025-10-28 14:48:34'),
(30, 'admin-001', 'py-adv-lesson-26', '2025-10-28 14:48:34'),
(31, 'admin-001', 'py-adv-lesson-27', '2025-10-28 14:48:34'),
(32, 'admin-001', 'py-adv-lesson-28', '2025-10-28 14:48:34'),
(33, 'admin-001', 'py-adv-lesson-29', '2025-10-28 14:48:34'),
(35, 'admin-001', 'py-adv-lesson-30', '2025-10-28 14:48:34'),
(37, 'admin-001', 'py-adv-lesson-5', '2025-10-28 14:48:34'),
(38, 'admin-001', 'py-adv-lesson-6', '2025-10-28 14:48:34'),
(39, 'admin-001', 'py-adv-lesson-7', '2025-10-28 14:48:34'),
(40, 'admin-001', 'py-adv-lesson-8', '2025-10-28 14:48:34'),
(41, 'admin-001', 'py-adv-lesson-9', '2025-10-28 14:48:34');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `language` enum('python','javascript','cpp') NOT NULL,
  `category` enum('beginner','intermediate','advanced') NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `is_free` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `language`, `category`, `duration`, `thumbnail_url`, `is_published`, `is_free`, `created_at`, `updated_at`) VALUES
('Python-Advance', 'Python Advance', 'A complete advanced-level Python course covering decorators, generators, asynchronous programming, database integration, and API-driven applications with real projects for Nepali learners.', 'python', 'advanced', '24 Weeks', NULL, 1, 1, '2025-10-23 09:43:43', '2025-10-23 09:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` varchar(50) NOT NULL,
  `lesson_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starter_code` text NOT NULL,
  `solution` text NOT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'easy',
  `exercise_type` enum('standard','ai-validated') DEFAULT 'standard',
  `points` int(11) DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `lesson_id`, `title`, `description`, `starter_code`, `solution`, `difficulty`, `exercise_type`, `points`, `created_at`, `updated_at`) VALUES
('py-ex-1', 'py-adv-lesson-1', 'Print Hello World', 'Write a Python program that prints \"Hello, World!\" to the screen.', '# Write your code here\r\n', 'print(\"Hello, World!\")', 'easy', 'standard', 10, '2025-10-24 06:35:28', '2025-10-24 06:35:28'),
('py-ex-10', 'py-adv-lesson-10', 'Print Student Info', 'student = {\"name\": \"Razz\", \"age\": 18, \"city\": \"Kathmandu\"}\r\n', 'student = {\"name\": \"Razz\", \"age\": 18, \"city\": \"Kathmandu\"}\r\n# Write your code here\r\n', 'print(student[\"name\"])\r\nprint(student[\"city\"])\r\n', 'easy', 'standard', 10, '2025-10-28 12:55:09', '2025-10-28 12:55:09'),
('py-ex-11', 'py-adv-lesson-11', 'Functions in Python', 'Define a function greet() that prints \"Hello, welcome to CodeNepal!\" and call it.', '# Write your function here\r\n', 'def greet():\r\n    print(\"Hello, welcome to CodeNepal!\")\r\n\r\ngreet()\r\n', 'easy', 'standard', 10, '2025-10-28 12:56:50', '2025-10-28 12:56:50'),
('py-ex-12', 'py-adv-lesson-12', 'Modules and Packages in Python', 'Use the math module to print the square root of 64.', '# Write your code here\r\n', 'import math\r\nprint(math.sqrt(64))\r\n', 'easy', 'standard', 10, '2025-10-28 12:58:32', '2025-10-28 12:58:32'),
('py-ex-13', 'py-adv-lesson-13', 'Create and Write File', 'Create a file named example.txt and write the following lines:\r\nHello, CodeNepal!\r\nPython File Handling.\r\n', '# Write your code here\r\n', 'with open(\"example.txt\", \"w\") as f:\r\n    f.write(\"Hello, CodeNepal!\\n\")\r\n    f.write(\"Python File Handling.\")\r\n', 'easy', 'standard', 10, '2025-10-28 13:00:37', '2025-10-28 13:00:37'),
('py-ex-14', 'py-adv-lesson-14', 'Safe Division', 'Take an integer input from the user and divide 10 by that number. Handle the ZeroDivisionError to print \"Cannot divide by zero!\".', '# Write your code here\r\nnum = int(input(\"Enter a number: \"))\r\n# Division logic with exception handling\r\n', 'try:\r\n    num = int(input(\"Enter a number: \"))\r\n    print(10 / num)\r\nexcept ZeroDivisionError:\r\n    print(\"Cannot divide by zero!\")\r\n', 'easy', 'standard', 10, '2025-10-28 13:03:56', '2025-10-28 13:03:56'),
('py-ex-15', 'py-adv-lesson-15', 'Person Class', 'Create a class named Person with attributes name and age. Create an object with name \"Razz\" and age 18, then print both attributes.', '# Write your code here\r\n', 'class Person:\r\n    def __init__(self, name, age):\r\n        self.name = name\r\n        self.age = age\r\n\r\np1 = Person(\"Razz\", 18)\r\nprint(p1.name)\r\nprint(p1.age)\r\n', 'easy', 'standard', 10, '2025-10-28 13:06:03', '2025-10-28 13:06:03'),
('py-ex-16', 'py-adv-lesson-16', 'Animal and Dog', 'Create a class Animal with a method speak() that prints \"Animal makes a sound\". Create a class Dog that inherits from Animal and create an object of Dog. Call the speak() method.', '# Write your code here\r\n', 'class Animal:\r\n    def speak(self):\r\n        print(\"Animal makes a sound\")\r\n\r\nclass Dog(Animal):\r\n    pass\r\n\r\nd = Dog()\r\nd.speak()\r\n', 'easy', 'standard', 10, '2025-10-28 13:07:54', '2025-10-28 13:07:54'),
('py-ex-17', 'py-adv-lesson-17', 'Using len() with Different Types', 'Use the len() function to print the length of the string \"Python\" and the list [1, 2, 3, 4, 5].', '# Write your code here\r\n', 'print(len(\"Python\"))\r\nprint(len([1, 2, 3, 4, 5]))\r\n', 'easy', 'standard', 10, '2025-10-28 13:11:18', '2025-10-28 13:11:18'),
('py-ex-18', 'py-adv-lesson-18', 'Access Public and Private Variables', 'Create a class Person with a public variable name and a private variable __age. Try printing both from outside the class. Observe what happens.', '# Write your code here\r\n', 'class Person:\r\n    def __init__(self, name, age):\r\n        self.name = name      # public\r\n        self.__age = age      # private\r\n\r\np = Person(\"Razz\", 18)\r\nprint(p.name)   # works\r\n# print(p.__age)  # would cause AttributeError\r\n', 'easy', 'standard', 10, '2025-10-28 13:14:07', '2025-10-28 13:14:07'),
('py-ex-19', 'py-adv-lesson-19', 'Add Messages Before and After Function', 'Create a decorator that prints \"Start\" before a function and \"End\" after it. Apply it to a greet() function that prints \"Hello, CodeNepal!\".', '# Write your code here\r\ndef greet():\r\n    print(\"Hello, CodeNepal!\")\r\n', 'def decorator(func):\r\n    def wrapper():\r\n        print(\"Start\")\r\n        func()\r\n        print(\"End\")\r\n    return wrapper\r\n\r\n@decorator\r\ndef greet():\r\n    print(\"Hello, CodeNepal!\")\r\n\r\ngreet()\r\n', 'easy', 'standard', 10, '2025-10-28 13:15:31', '2025-10-28 13:15:31'),
('py-ex-2', 'py-adv-lesson-2', 'Store and Print Variables', 'Create two variables: one that stores name Ram (string) and one that stores age 18 (number). Then print both values in a single line.', '# Create your variables below\r\n\r\n\r\n# Print them here\r\n', 'name = \"Ram\"\r\nage = 18\r\nprint(name, age)', 'easy', 'standard', 10, '2025-10-24 06:38:45', '2025-10-24 06:38:45'),
('py-ex-2-2', 'py-adv-lesson-2', 'Identify the Data Type', 'Create three variables:\r\nx with value 25\r\ny with value 3.14\r\nz with value \"Python\"\r\nThen print the data type of each variable using the type() function.', '# Write your code here\r\n', 'x = 25\r\ny = 3.14\r\nz = \"Python\"\r\n\r\nprint(type(x))\r\nprint(type(y))\r\nprint(type(z))\r\n', 'easy', 'standard', 10, '2025-10-26 06:43:22', '2025-10-26 06:43:22'),
('py-ex-20', 'py-adv-lesson-20', 'Simple Number Generator', 'Create a generator function my_generator() that yields numbers 1, 2, and 3 one by one. Use next() to print all values.', '# Write your generator function here\r\n', 'def my_generator():\r\n    yield 1\r\n    yield 2\r\n    yield 3\r\n\r\ngen = my_generator()\r\nprint(next(gen))  # 1\r\nprint(next(gen))  # 2\r\nprint(next(gen))  # 3\r\n', 'easy', 'standard', 10, '2025-10-28 13:17:37', '2025-10-28 13:17:37'),
('py-ex-21', 'py-adv-lesson-21', 'Write to a File Safely', 'Use a context manager (with statement) to write \"Hello, CodeNepal!\" to a file called hello.txt.', '# Write your code here\r\n', 'with open(\"hello.txt\", \"w\") as file:\r\n    file.write(\"Hello, CodeNepal!\")\r\n', 'easy', 'standard', 10, '2025-10-28 13:20:21', '2025-10-28 13:20:21'),
('py-ex-22', 'py-adv-lesson-22', 'Iterate over a List', 'Create an iterator from the list nums = [5, 10, 15] and print each element using next().\r\nStarter Code:', 'nums = [5, 10, 15]\r\n# Create iterator here\r\n\r\n# Print each element using next()\r\n', 'nums = [5, 10, 15]\r\nit = iter(nums)\r\n\r\nprint(next(it))  # 5\r\nprint(next(it))  # 10\r\nprint(next(it))  # 15\r\n', 'easy', 'standard', 10, '2025-10-28 13:22:58', '2025-10-28 13:22:58'),
('py-ex-23', 'py-adv-lesson-23', 'Square a Number', 'Write a lambda function to calculate the square of a number x = 7 and print the result.\r\nStarter Code:', '# Write your lambda function here\r\n', 'square = lambda x: x ** 2\r\nprint(square(7))  # 49\r\n', 'easy', 'standard', 10, '2025-10-28 13:24:19', '2025-10-28 13:24:19'),
('py-ex-24', 'py-adv-lesson-24', 'Cube Numbers', 'Given a list numbers = [1, 2, 3, 4], use map() with a lambda function to calculate the cube of each number and print the resulting list.', 'numbers = [1, 2, 3, 4]\r\n# Use map and lambda here\r\n', 'numbers = [1, 2, 3, 4]\r\ncubes = list(map(lambda x: x ** 3, numbers))\r\nprint(cubes)  # [1, 8, 27, 64]\r\n', 'easy', 'standard', 10, '2025-10-28 13:25:35', '2025-10-28 13:25:35'),
('py-ex-25', 'py-adv-lesson-25', 'Match or Search Text', 'Check if the string \"Hello CodeNepal\" starts with \"Hello\" using re.match() and search for \"CodeNepal\" using re.search(). Print whether each match was found.\r\n', 'import re\r\ntext = \"Hello CodeNepal\"\r\n\r\n# Use re.match() and re.search() here\r\n', 'import re\r\ntext = \"Hello CodeNepal\"\r\n\r\nmatch = re.match(\"Hello\", text)\r\nsearch = re.search(\"CodeNepal\", text)\r\n\r\nprint(\"Match found\" if match else \"Match not found\")   # Match found\r\nprint(\"Search found\" if search else \"Search not found\") # Search found\r\n', 'easy', 'standard', 10, '2025-10-28 13:27:28', '2025-10-28 13:27:28'),
('py-ex-26', 'py-adv-lesson-26', 'Squares of Numbers', 'Create a list of squares for numbers 0 to 9 using list comprehension.', '# Create a list of squares\r\n# Use list comprehension\r\n', 'squares = [x**2 for x in range(10)]\r\nprint(squares)  # [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]\r\n', 'easy', 'standard', 10, '2025-10-28 13:30:22', '2025-10-28 13:30:22'),
('py-ex-27', 'py-adv-lesson-27', 'Counting Numbers', 'itertools.count() to create an iterator starting at 10, with a step of 3. Print the first 5 numbers using next().', 'import itertools\r\n\r\n# Create a count iterator starting at 10, step 3\r\n', 'import itertools\r\n\r\ncounter = itertools.count(start=10, step=3)\r\nfor _ in range(5):\r\n    print(next(counter))\r\n# Output: 10 13 16 19 22\r\n', 'easy', 'standard', 10, '2025-10-28 13:32:00', '2025-10-28 13:32:00'),
('py-ex-28', 'py-adv-lesson-28', 'Print Numbers in a Thread', 'Create a thread that prints numbers from 1 to 5. Ensure the main program waits until the thread finishes.', 'import threading\r\n\r\ndef print_numbers():\r\n    # Print numbers 1 to 5\r\n', 'import threading\r\n\r\ndef print_numbers():\r\n    for i in range(1, 6):\r\n        print(i)\r\n\r\nt1 = threading.Thread(target=print_numbers)\r\nt1.start()\r\nt1.join()\r\n', 'easy', 'standard', 10, '2025-10-28 13:33:57', '2025-10-28 13:33:57'),
('py-ex-29', 'py-adv-lesson-29', 'Hello Coroutine', ': Define an asynchronous function greet() that prints \"Hello CodeNepal\" and run it using asyncio.run()', 'import asyncio\r\n\r\nasync def greet():\r\n    # print greeting\r\n', 'import asyncio\r\n\r\nasync def greet():\r\n    print(\"Hello CodeNepal\")\r\n\r\nasyncio.run(greet())\r\n', 'easy', 'standard', 10, '2025-10-28 13:35:21', '2025-10-28 13:35:21'),
('py-ex-3', 'py-adv-lesson-3', 'Input and Printing', 'Take the input and print Hello, <input>\r\nNote : Input will automatically \"CodeNepal\"', '# Write your code here\r\n', 'name = input()\r\nprint(\"Hello,\", name)\r\n', 'easy', 'standard', 10, '2025-10-28 12:16:57', '2025-10-28 12:16:57'),
('py-ex-4', 'py-adv-lesson-4', 'Operators in Python', 'Take two numbers as input from the user and print their sum, difference, product, division, floor division, remainder, and power.\r\nShould print :\r\nAddition: 13\r\nSubtraction: 7\r\nMultiplication: 30\r\nDivision: 3.3333333333333335\r\nFloor Division: 3\r\nRemainder: 1\r\nPower: 1000', '# Write your code here\r\n', 'a = int(input())\r\nb = int(input())\r\n\r\nprint(\"Addition:\", a + b)\r\nprint(\"Subtraction:\", a - b)\r\nprint(\"Multiplication:\", a * b)\r\nprint(\"Division:\", a / b)\r\nprint(\"Floor Division:\", a // b)\r\nprint(\"Remainder:\", a % b)\r\nprint(\"Power:\", a ** b)\r\n', 'easy', 'standard', 10, '2025-10-28 12:35:06', '2025-10-28 12:35:06'),
('py-ex-4-2', 'py-adv-lesson-4', 'Compare Numbers', 'Take two numbers as input and print True/False for each comparison (==, !=, >, <, >=, <=).', '# Write your code here\r\n', 'x = int(input())\r\ny = int(input())\r\n\r\nprint(x == y)\r\nprint(x != y)\r\nprint(x > y)\r\nprint(x < y)\r\nprint(x >= y)\r\nprint(x <= y)\r\n', 'easy', 'standard', 10, '2025-10-28 12:39:10', '2025-10-28 12:39:10'),
('py-ex-4-3', 'py-adv-lesson-4', 'Age Checker', 'Take age as input and check:\r\n\r\nIs age between 18 and 30?\r\n\r\nIs age not above 60?\r\nPrint the results using and, or, not.', '# Write your code here\r\n', 'age = int(input())\r\n\r\nprint(age >= 18 and age <= 30)\r\nprint(age > 30 or age < 60)\r\nprint(not(age > 60))\r\n', 'easy', 'standard', 10, '2025-10-28 12:40:35', '2025-10-28 12:40:35'),
('py-ex-5', 'py-adv-lesson-5', 'Access Characters', 'Take a string input from the user and print the first and last character of the string.\r\n', '# Write your code here\r\n', 'text = input()\r\nprint(text[0])\r\nprint(text[-1])\r\n', 'easy', 'standard', 10, '2025-10-28 12:42:23', '2025-10-28 12:42:23'),
('py-ex-6', 'py-adv-lesson-6', 'Check Adult', 'Take age as input. Print \"You are an adult.\" if age is 18 or more.', '# Write your code here\r\n', 'age = int(input())\r\nif age >= 18:\r\n    print(\"You are an adult.\")\r\n', 'easy', 'standard', 10, '2025-10-28 12:44:57', '2025-10-28 12:44:57'),
('py-ex-7', 'py-adv-lesson-7', 'Print Numbers', 'Use a for loop to print numbers from 1 to 10.', '# Write your code here\r\n', 'for i in range(1, 11):\r\n    print(i)\r\n', 'easy', 'standard', 10, '2025-10-28 12:47:28', '2025-10-28 12:47:28'),
('py-ex-8', 'py-adv-lesson-8', 'Print First & Last Element', 'Take a list of numbers [10, 20, 30, 40, 50] and print the first and last element.\r\n', 'numbers = [10, 20, 30, 40, 50]\r\n# Write your code here\r\n', 'print(numbers[0])\r\nprint(numbers[-1])\r\n', 'easy', 'standard', 10, '2025-10-28 12:50:34', '2025-10-28 12:50:34'),
('py-ex-9', 'py-adv-lesson-9', 'Print Tuple Elements', 'Given a tuple fruits = (\"apple\", \"banana\", \"cherry\"), print the first and last elements.', 'fruits = (\"apple\", \"banana\", \"cherry\")\r\n# Write your code here\r\n', 'print(fruits[0])\r\nprint(fruits[-1])\r\n', 'easy', 'standard', 10, '2025-10-28 12:53:22', '2025-10-28 12:53:22'),
('py=ex=30', 'py-adv-lesson-30', 'Greeting Module', '# define greet function here\r\n', '# import my_module and call greet\r\n', '# my_module.py\r\ndef greet(name):\r\n    print(f\"Hello, {name}!\")\r\n\r\n# main.py\r\nimport my_module\r\n\r\nmy_module.greet(\"Razz\")\r\n', 'easy', 'standard', 10, '2025-10-28 13:37:08', '2025-10-28 13:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `hints`
--

CREATE TABLE `hints` (
  `id` int(11) NOT NULL,
  `exercise_id` varchar(50) NOT NULL,
  `hint_text` text NOT NULL,
  `order_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` varchar(50) NOT NULL,
  `course_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `order_index` int(11) NOT NULL,
  `is_locked` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `content`, `order_index`, `is_locked`, `created_at`, `updated_at`) VALUES
('py-adv-lesson-1', 'Python-Advance', 'Introduction to Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 1: Introduction to Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 1: Introduction to Python</h1>\r\n\r\n<p>Welcome to the <strong>Python Beginner to Advanced Course</strong> by CodeNepal 👨‍💻🐍</p>\r\n\r\n<p>In this lesson, you will learn what Python is, why it is so popular, and how programs are executed.</p>\r\n\r\n<hr>\r\n\r\n<h2>What is Python?</h2>\r\n\r\n<p>Python is a <strong>high-level, interpreted programming language</strong> that focuses on readability and simplicity.<br>\r\nIt is widely used in web apps, AI/ML, scripting, data science, APIs, cybersecurity, automation, and more.</p>\r\n\r\n<hr>\r\n\r\n<h2>Why Python?</h2>\r\n\r\n<ul>\r\n  <li>Easy to learn and read (beginner-friendly)</li>\r\n  <li>Cross-platform (Windows, Linux, macOS)</li>\r\n  <li>Huge community & libraries</li>\r\n  <li>Used in trending technologies like AI & automation</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Where Python is Used</h2>\r\n\r\n<table>\r\n  <tr>\r\n    <th>Area</th>\r\n    <th>Real Use</th>\r\n  </tr>\r\n  <tr>\r\n    <td>Web Development</td>\r\n    <td>Backend apps (Django, Flask)</td>\r\n  </tr>\r\n  <tr>\r\n    <td>AI/ML</td>\r\n    <td>Data analysis & modeling</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Automation</td>\r\n    <td>System scripts & tasks</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Cybersecurity</td>\r\n    <td>Tools & scanners</td>\r\n  </tr>\r\n  <tr>\r\n    <td>APIs</td>\r\n    <td>Backend servers</td>\r\n  </tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>How Python Runs (Interpreter)</h2>\r\n\r\n<p>Python executes your code <strong>line-by-line</strong>, which makes development and debugging easy.</p>\r\n\r\n<pre> \r\nYour Code (.py) → Python Interpreter → Output\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Running Python Code</h2>\r\n\r\n<p>There are two ways to run Python:</p>\r\n\r\n<table>\r\n  <tr>\r\n    <th>Mode</th>\r\n    <th>When to use</th>\r\n  </tr>\r\n  <tr>\r\n    <td>Interactive (REPL)</td>\r\n    <td>Testing small snippets</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Script (.py file)</td>\r\n    <td>Writing full programs</td>\r\n  </tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nprint(\"Hello from CodeNepal!\")\r\n</pre>\r\n\r\n<p>When you run this code, Python shows:</p>\r\n\r\n<p><strong>Hello from CodeNepal!</strong></p>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will start learning <strong>Variables and Data Types</strong>, the foundation of all programs.</p>\r\n\r\n</body>\r\n</html>\r\n', 1, 0, '2025-10-23 09:47:31', '2025-10-23 11:14:53'),
('py-adv-lesson-10', 'Python-Advance', 'Dictionaries in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 10: Dictionaries in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 10: Dictionaries in Python</h1>\r\n\r\n<p>A <strong>dictionary</strong> is an <strong>unordered collection of key-value pairs</strong>.  \r\nIt is very useful for storing data where each value has a unique key.</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating a Dictionary</h2>\r\n\r\n<pre>\r\nstudent = {\r\n    \"name\": \"Razz\",\r\n    \"age\": 18,\r\n    \"city\": \"Kathmandu\"\r\n}\r\n</pre>\r\n\r\n<ul>\r\n  <li>Keys must be <strong>unique</strong> and <strong>immutable</strong> (<code>str</code>, <code>int</code>, <code>tuple</code>, etc.)</li>\r\n  <li>Values can be <strong>any type</strong>: numbers, strings, lists, other dictionaries</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Accessing Values</h2>\r\n\r\n<pre>\r\nprint(student[\"name\"])   # Razz\r\nprint(student.get(\"age\")) # 18\r\n</pre>\r\n\r\n<p><code>get()</code> is safer — returns <code>None</code> if key does not exist</p>\r\n\r\n<hr>\r\n\r\n<h2>Adding / Updating Items</h2>\r\n\r\n<pre>\r\nstudent[\"email\"] = \"razz@example.com\"  # add new key\r\nstudent[\"age\"] = 19                    # update value\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Removing Items</h2>\r\n\r\n<pre>\r\nstudent.pop(\"city\")     # removes key \'city\'\r\ndel student[\"age\"]      # removes key \'age\'\r\nstudent.clear()         # removes all items\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Dictionary Methods</h2>\r\n\r\n<table>\r\n  <tr><th>Method</th><th>Use</th></tr>\r\n  <tr><td><code>.keys()</code></td><td>Get all keys</td></tr>\r\n  <tr><td><code>.values()</code></td><td>Get all values</td></tr>\r\n  <tr><td><code>.items()</code></td><td>Get all key-value pairs</td></tr>\r\n  <tr><td><code>.copy()</code></td><td>Copy dictionary</td></tr>\r\n  <tr><td><code>.update()</code></td><td>Update with another dictionary</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\nstudent = {\"name\": \"Razz\", \"age\": 18}\r\nprint(student.keys())    # dict_keys([\'name\', \'age\'])\r\nprint(student.values())  # dict_values([\'Razz\', 18])\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Iterating Over Dictionaries</h2>\r\n\r\n<pre>\r\nfor key, value in student.items():\r\n    print(key, \":\", value)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nperson = {\"name\": \"Razz\", \"country\": \"Nepal\"}\r\nperson[\"age\"] = 18\r\nfor k, v in person.items():\r\n    print(k, v)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Functions in Python</strong> — reusable blocks of code.</p>\r\n\r\n</body>\r\n</html>\r\n', 10, 1, '2025-10-23 09:55:05', '2025-10-23 11:34:09'),
('py-adv-lesson-11', 'Python-Advance', 'Functions in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 11: Functions in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 11: Functions in Python</h1>\r\n\r\n<p>A <strong>function</strong> is a <strong>reusable block of code</strong> that performs a specific task.  \r\nFunctions help make code organized and avoid repetition.</p>\r\n\r\n<hr>\r\n\r\n<h2>Defining a Function</h2>\r\n\r\n<pre>\r\ndef greet():\r\n    print(\"Hello, welcome to CodeNepal!\")\r\n</pre>\r\n\r\n<ul>\r\n  <li><code>def</code> keyword is used to define a function</li>\r\n  <li>Function name follows standard variable naming rules</li>\r\n  <li>Code inside function must be <strong>indented</strong></li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Calling a Function</h2>\r\n\r\n<pre>\r\ngreet()\r\n</pre>\r\n\r\n<p>Output:</p>\r\n\r\n<pre>\r\nHello, welcome to CodeNepal!\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Functions with Parameters</h2>\r\n\r\n<p>Parameters allow passing information to a function.</p>\r\n\r\n<pre>\r\ndef greet_user(name):\r\n    print(f\"Hello, {name}!\")\r\n\r\ngreet_user(\"Razz\")  # Hello, Razz!\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Functions with Return Values</h2>\r\n\r\n<p>Functions can return values using <code>return</code>.</p>\r\n\r\n<pre>\r\ndef add(a, b):\r\n    return a + b\r\n\r\nresult = add(5, 3)\r\nprint(result)  # 8\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Default Parameters</h2>\r\n\r\n<p>You can give a default value to parameters.</p>\r\n\r\n<pre>\r\ndef greet(name=\"Friend\"):\r\n    print(f\"Hello, {name}!\")\r\n\r\ngreet()       # Hello, Friend!\r\ngreet(\"Razz\") # Hello, Razz!\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Keyword Arguments</h2>\r\n\r\n<p>You can call functions using <strong>key=value</strong> syntax.</p>\r\n\r\n<pre>\r\ndef info(name, age):\r\n    print(f\"Name: {name}, Age: {age}\")\r\n\r\ninfo(age=18, name=\"Razz\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\ndef square(num):\r\n    return num ** 2\r\n\r\nprint(square(5))  # 25\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Modules and Packages</strong> — how to organize code into reusable files.</p>\r\n\r\n</body>\r\n</html>\r\n', 11, 1, '2025-10-23 09:55:30', '2025-10-23 11:34:52'),
('py-adv-lesson-12', 'Python-Advance', 'Modules and Packages in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 12: Modules and Packages in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 12: Modules and Packages in Python</h1>\r\n\r\n<p>As programs grow bigger, organizing code into <strong>modules</strong> and <strong>packages</strong> makes it easier to manage and reuse.</p>\r\n\r\n<hr>\r\n\r\n<h2>What is a Module?</h2>\r\n\r\n<p>A <strong>module</strong> is a file containing Python code (functions, classes, variables).  \r\nYou can <strong>import</strong> it into another program.</p>\r\n\r\n<p><strong>Example: <code>math</code> module</strong></p>\r\n\r\n<pre>\r\nimport math\r\n\r\nprint(math.sqrt(16))  # 4.0\r\nprint(math.pi)        # 3.141592653589793\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Importing Specific Functions</h2>\r\n\r\n<pre>\r\nfrom math import sqrt, pi\r\n\r\nprint(sqrt(25))  # 5.0\r\nprint(pi)        # 3.141592653589793\r\n</pre>\r\n\r\n<p>- This avoids importing the whole module.</p>\r\n\r\n<hr>\r\n\r\n<h2>Aliases</h2>\r\n\r\n<p>You can give a module a short name using <code>as</code>:</p>\r\n\r\n<pre>\r\nimport math as m\r\n\r\nprint(m.sqrt(49))  # 7.0\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Creating Your Own Module</h2>\r\n\r\n<p>1. Create a Python file: <code>mymodule.py</code></p>\r\n\r\n<pre>\r\ndef greet(name):\r\n    print(f\"Hello, {name}!\")\r\n</pre>\r\n\r\n<p>2. Import it in another file:</p>\r\n\r\n<pre>\r\nimport mymodule\r\n\r\nmymodule.greet(\"Razz\")  # Hello, Razz!\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>What is a Package?</h2>\r\n\r\n<p>A <strong>package</strong> is a folder containing multiple modules, along with a special <code>__init__.py</code> file.</p>\r\n\r\n<pre>\r\nmy_package/\r\n    __init__.py\r\n    module1.py\r\n    module2.py\r\n</pre>\r\n\r\n<p>You can import modules from a package:</p>\r\n\r\n<pre>\r\nfrom my_package import module1\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\n# Using built-in module\r\nimport random\r\n\r\nprint(random.randint(1, 10))  # random number between 1 and 10\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>File Handling in Python</strong> — reading and writing files.</p>\r\n\r\n</body>\r\n</html>\r\n', 12, 1, '2025-10-23 09:55:53', '2025-10-23 11:35:48'),
('py-adv-lesson-13', 'Python-Advance', 'File Handling in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 13: File Handling in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 13: File Handling in Python</h1>\r\n\r\n<p>Python allows you to <strong>read and write files</strong> on your computer.  \r\nThis is useful for storing data, logging, or reading configuration.</p>\r\n\r\n<hr>\r\n\r\n<h2>Opening a File</h2>\r\n\r\n<p>Use the <code>open()</code> function to open a file.</p>\r\n\r\n<pre>\r\nfile = open(\"example.txt\", \"w\")  # \"w\" = write mode\r\n</pre>\r\n\r\n<p><strong>Modes:</strong></p>\r\n\r\n<table>\r\n  <tr><th>Mode</th><th>Meaning</th></tr>\r\n  <tr><td><code>r</code></td><td>Read (default)</td></tr>\r\n  <tr><td><code>w</code></td><td>Write (creates or overwrites file)</td></tr>\r\n  <tr><td><code>a</code></td><td>Append (add at end)</td></tr>\r\n  <tr><td><code>x</code></td><td>Create (fails if file exists)</td></tr>\r\n  <tr><td><code>rb, wb</code></td><td>Read/write in binary</td></tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>Writing to a File</h2>\r\n\r\n<pre>\r\nfile = open(\"example.txt\", \"w\")\r\nfile.write(\"Hello, CodeNepal!\\n\")\r\nfile.write(\"Python File Handling.\")\r\nfile.close()\r\n</pre>\r\n\r\n<p>- Always <strong>close the file</strong> after writing.</p>\r\n\r\n<hr>\r\n\r\n<h2>Reading from a File</h2>\r\n\r\n<pre>\r\nfile = open(\"example.txt\", \"r\")\r\ncontent = file.read()\r\nprint(content)\r\nfile.close()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Reading Line by Line</h2>\r\n\r\n<pre>\r\nfile = open(\"example.txt\", \"r\")\r\nfor line in file:\r\n    print(line.strip())  # remove extra newline\r\nfile.close()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using <code>with</code> Statement</h2>\r\n\r\n<p>The <code>with</code> statement <strong>automatically closes</strong> the file.</p>\r\n\r\n<pre>\r\nwith open(\"example.txt\", \"w\") as f:\r\n    f.write(\"Hello using with statement!\")\r\n</pre>\r\n\r\n<pre>\r\nwith open(\"example.txt\", \"r\") as f:\r\n    print(f.read())\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nwith open(\"data.txt\", \"w\") as f:\r\n    f.write(\"Python is fun!\\nLearning step by step.\")\r\n\r\nwith open(\"data.txt\", \"r\") as f:\r\n    print(f.read())\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn <strong>Error and Exception Handling</strong> in Python to manage runtime errors safely.</p>\r\n\r\n</body>\r\n</html>\r\n', 13, 1, '2025-10-23 09:56:11', '2025-10-23 11:36:17'),
('py-adv-lesson-14', 'Python-Advance', 'Error and Exception Handling in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 14: Error and Exception Handling in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 14: Error and Exception Handling in Python</h1>\r\n\r\n<p>Errors can occur while running programs.  \r\nPython provides <strong>exception handling</strong> to deal with errors gracefully.</p>\r\n\r\n<hr>\r\n\r\n<h2>Common Errors</h2>\r\n\r\n<table>\r\n  <tr><th>Error Type</th><th>Example</th></tr>\r\n  <tr><td><code>ZeroDivisionError</code></td><td>Dividing by zero</td></tr>\r\n  <tr><td><code>TypeError</code></td><td>Adding string + number</td></tr>\r\n  <tr><td><code>ValueError</code></td><td>Converting invalid string to int</td></tr>\r\n  <tr><td><code>FileNotFoundError</code></td><td>Reading a missing file</td></tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>try / except</h2>\r\n\r\n<p>Handle errors using <code>try</code> and <code>except</code>.</p>\r\n\r\n<pre>\r\ntry:\r\n    num = int(input(\"Enter a number: \"))\r\n    print(10 / num)\r\nexcept ZeroDivisionError:\r\n    print(\"Cannot divide by zero!\")\r\nexcept ValueError:\r\n    print(\"Invalid input! Enter a number.\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Handling Multiple Exceptions</h2>\r\n\r\n<pre>\r\ntry:\r\n    x = int(input(\"Enter number: \"))\r\n    y = int(input(\"Enter another number: \"))\r\n    print(x / y)\r\nexcept (ZeroDivisionError, ValueError):\r\n    print(\"Error occurred! Check input or division.\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>else and finally</h2>\r\n\r\n<p>- <code>else</code> runs if <strong>no exception occurs</strong><br>\r\n- <code>finally</code> runs <strong>always</strong>, even if an exception occurs</p>\r\n\r\n<pre>\r\ntry:\r\n    num = int(input(\"Enter number: \"))\r\n    print(10 / num)\r\nexcept ZeroDivisionError:\r\n    print(\"Cannot divide by zero!\")\r\nelse:\r\n    print(\"Division successful\")\r\nfinally:\r\n    print(\"Program ended\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Raising Exceptions</h2>\r\n\r\n<p>You can <strong>raise your own errors</strong> using <code>raise</code>.</p>\r\n\r\n<pre>\r\nage = int(input(\"Enter age: \"))\r\nif age < 0:\r\n    raise ValueError(\"Age cannot be negative!\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\ntry:\r\n    x = int(input(\"Enter a number: \"))\r\n    print(100 / x)\r\nexcept ZeroDivisionError:\r\n    print(\"Division by zero!\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn <strong>Object-Oriented Programming (OOP) Basics</strong> — classes and objects in Python.</p>\r\n\r\n</body>\r\n</html>\r\n', 14, 1, '2025-10-23 09:56:33', '2025-10-23 11:37:10'),
('py-adv-lesson-15', 'Python-Advance', 'Object-Oriented Programming (OOP) Basics', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 15: Object-Oriented Programming (OOP) Basics</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 15: Object-Oriented Programming (OOP) Basics</h1>\r\n\r\n<p>Python supports <strong>Object-Oriented Programming (OOP)</strong> which allows you to model real-world objects in code.  \r\nThe main concepts are <strong>classes</strong> and <strong>objects</strong>.</p>\r\n\r\n<hr>\r\n\r\n<h2>What is a Class?</h2>\r\n\r\n<p>A <strong>class</strong> is a blueprint for creating objects.</p>\r\n\r\n<pre>\r\nclass Person:\r\n    pass\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Creating an Object</h2>\r\n\r\n<p>An <strong>object</strong> is an instance of a class.</p>\r\n\r\n<pre>\r\nclass Person:\r\n    name = \"Razz\"\r\n    age = 18\r\n\r\np1 = Person()\r\nprint(p1.name)  # Razz\r\nprint(p1.age)   # 18\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>The <code>__init__</code> Method</h2>\r\n\r\n<p><code>__init__</code> is a special method called a <strong>constructor</strong>.  \r\nIt runs when an object is created.</p>\r\n\r\n<pre>\r\nclass Person:\r\n    def __init__(self, name, age):\r\n        self.name = name\r\n        self.age = age\r\n\r\np1 = Person(\"Razz\", 18)\r\nprint(p1.name)  # Razz\r\nprint(p1.age)   # 18\r\n</pre>\r\n\r\n<p>- <code>self</code> refers to the current object</p>\r\n\r\n<hr>\r\n\r\n<h2>Methods in Classes</h2>\r\n\r\n<p>Classes can have <strong>functions (methods)</strong>.</p>\r\n\r\n<pre>\r\nclass Person:\r\n    def __init__(self, name):\r\n        self.name = name\r\n\r\n    def greet(self):\r\n        print(f\"Hello, my name is {self.name}\")\r\n\r\np1 = Person(\"Razz\")\r\np1.greet()  # Hello, my name is Razz\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Encapsulation (Private Variables)</h2>\r\n\r\n<p>Variables starting with <code>_</code> or <code>__</code> are treated as <strong>private</strong>.</p>\r\n\r\n<pre>\r\nclass Person:\r\n    def __init__(self, name):\r\n        self.__name = name  # private\r\n\r\n    def get_name(self):\r\n        return self.__name\r\n\r\np1 = Person(\"Razz\")\r\nprint(p1.get_name())  # Razz\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nclass Car:\r\n    def __init__(self, model):\r\n        self.model = model\r\n\r\n    def drive(self):\r\n        print(f\"{self.model} is driving!\")\r\n\r\ncar1 = Car(\"Toyota\")\r\ncar1.drive()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn <strong>Inheritance in Python OOP</strong> — how classes can share functionality.</p>\r\n\r\n</body>\r\n</html>\r\n', 15, 1, '2025-10-23 09:56:58', '2025-10-23 11:38:03'),
('py-adv-lesson-16', 'Python-Advance', 'Inheritance in Python OOP', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 16: Inheritance in Python OOP</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 16: Inheritance in Python OOP</h1>\r\n\r\n<p><strong>Inheritance</strong> allows one class to <strong>reuse code</strong> from another class.  \r\nThe class that inherits is called <strong>child</strong>, and the class being inherited from is called <strong>parent</strong>.</p>\r\n\r\n<hr>\r\n\r\n<h2>Basic Inheritance</h2>\r\n\r\n<pre>\r\nclass Animal:\r\n    def speak(self):\r\n        print(\"Animal makes a sound\")\r\n\r\nclass Dog(Animal):\r\n    pass\r\n\r\nd = Dog()\r\nd.speak()  # Animal makes a sound\r\n</pre>\r\n\r\n<p>- <code>Dog</code> inherits the <code>speak</code> method from <code>Animal</code></p>\r\n\r\n<hr>\r\n\r\n<h2>Overriding Methods</h2>\r\n\r\n<p>Child class can <strong>override</strong> parent methods.</p>\r\n\r\n<pre>\r\nclass Animal:\r\n    def speak(self):\r\n        print(\"Animal makes a sound\")\r\n\r\nclass Dog(Animal):\r\n    def speak(self):\r\n        print(\"Dog barks\")\r\n\r\nd = Dog()\r\nd.speak()  # Dog barks\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using <code>super()</code></h2>\r\n\r\n<p><code>super()</code> allows calling <strong>parent class methods</strong> inside child class.</p>\r\n\r\n<pre>\r\nclass Animal:\r\n    def speak(self):\r\n        print(\"Animal makes a sound\")\r\n\r\nclass Dog(Animal):\r\n    def speak(self):\r\n        super().speak()\r\n        print(\"Dog barks\")\r\n\r\nd = Dog()\r\nd.speak()\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\nAnimal makes a sound\r\nDog barks\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Multiple Inheritance</h2>\r\n\r\n<p>Python allows <strong>multiple inheritance</strong> (child inherits from multiple parents).</p>\r\n\r\n<pre>\r\nclass A:\r\n    def methodA(self):\r\n        print(\"A method\")\r\n\r\nclass B:\r\n    def methodB(self):\r\n        print(\"B method\")\r\n\r\nclass C(A, B):\r\n    pass\r\n\r\nc = C()\r\nc.methodA()\r\nc.methodB()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nclass Person:\r\n    def greet(self):\r\n        print(\"Hello!\")\r\n\r\nclass Student(Person):\r\n    def study(self):\r\n        print(\"Studying...\")\r\n\r\ns1 = Student()\r\ns1.greet()\r\ns1.study()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn <strong>Polymorphism in Python OOP</strong> — using the same interface for different data types or classes.</p>\r\n\r\n</body>\r\n</html>\r\n', 16, 1, '2025-10-23 09:57:18', '2025-10-23 11:38:43'),
('py-adv-lesson-17', 'Python-Advance', 'Polymorphism in Python OOP', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 17: Polymorphism in Python OOP</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 17: Polymorphism in Python OOP</h1>\r\n\r\n<p><strong>Polymorphism</strong> means <strong>\"many forms\"</strong> — the same operation can behave differently depending on the object or data type.</p>\r\n\r\n<hr>\r\n\r\n<h2>Polymorphism with Functions</h2>\r\n\r\n<p>Python functions can work with <strong>different data types</strong>.</p>\r\n\r\n<pre>\r\nprint(len(\"Hello\"))       # 5\r\nprint(len([1, 2, 3, 4]))  # 4\r\n</pre>\r\n\r\n<p>- <code>len()</code> works with both strings and lists — same function, different behavior</p>\r\n\r\n<hr>\r\n\r\n<h2>Polymorphism with Classes</h2>\r\n\r\n<p>Different classes can have the <strong>same method name</strong>.</p>\r\n\r\n<pre>\r\nclass Dog:\r\n    def speak(self):\r\n        print(\"Dog barks\")\r\n\r\nclass Cat:\r\n    def speak(self):\r\n        print(\"Cat meows\")\r\n\r\nanimals = [Dog(), Cat()]\r\nfor animal in animals:\r\n    animal.speak()\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\nDog barks\r\nCat meows\r\n</pre>\r\n\r\n<p>- Each object responds differently to the same method <code>speak()</code></p>\r\n\r\n<hr>\r\n\r\n<h2>Method Overloading (Python Style)</h2>\r\n\r\n<p>Python does not support traditional method overloading, but you can use <strong>default arguments</strong>.</p>\r\n\r\n<pre>\r\nclass Math:\r\n    def add(self, a, b=0):\r\n        return a + b\r\n\r\nm = Math()\r\nprint(m.add(5))     # 5\r\nprint(m.add(5, 10)) # 15\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Operator Overloading</h2>\r\n\r\n<p>You can define <strong>custom behavior</strong> for operators in classes using special methods.</p>\r\n\r\n<pre>\r\nclass Point:\r\n    def __init__(self, x, y):\r\n        self.x = x\r\n        self.y = y\r\n\r\n    def __add__(self, other):\r\n        return Point(self.x + other.x, self.y + other.y)\r\n\r\np1 = Point(1, 2)\r\np2 = Point(3, 4)\r\np3 = p1 + p2\r\nprint(p3.x, p3.y)  # 4 6\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nclass Bird:\r\n    def fly(self):\r\n        print(\"Flying high!\")\r\n\r\nclass Plane:\r\n    def fly(self):\r\n        print(\"Plane taking off!\")\r\n\r\nthings = [Bird(), Plane()]\r\nfor thing in things:\r\n    thing.fly()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Encapsulation and Private Members</strong> in Python OOP.</p>\r\n\r\n</body>\r\n</html>\r\n', 17, 1, '2025-10-23 09:57:35', '2025-10-23 11:50:08'),
('py-adv-lesson-18', 'Python-Advance', 'Encapsulation and Private Members in Python OOP', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 18: Encapsulation and Private Members in Python OOP</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 18: Encapsulation and Private Members in Python OOP</h1>\r\n\r\n<p><strong>Encapsulation</strong> is the concept of <strong>restricting access</strong> to certain parts of an object.  \r\nIt helps protect data and hide internal implementation.</p>\r\n\r\n<hr>\r\n\r\n<h2>Public vs Private Members</h2>\r\n\r\n<ul>\r\n  <li><strong>Public</strong>: accessible from anywhere</li>\r\n  <li><strong>Private</strong>: accessible only within the class</li>\r\n</ul>\r\n\r\n<h3>Public Example</h3>\r\n<pre>\r\nclass Person:\r\n    def __init__(self, name):\r\n        self.name = name  # public\r\n\r\np = Person(\"Razz\")\r\nprint(p.name)  # Razz\r\n</pre>\r\n\r\n<h3>Private Example</h3>\r\n<pre>\r\nclass Person:\r\n    def __init__(self, name):\r\n        self.__name = name  # private\r\n\r\np = Person(\"Razz\")\r\n# print(p.__name)  # Error: AttributeError\r\n</pre>\r\n\r\n<p>- Use <code>__</code> before a variable to make it <strong>private</strong>.</p>\r\n\r\n<hr>\r\n\r\n<h2>Accessing Private Members</h2>\r\n\r\n<p>Use <strong>getter</strong> and <strong>setter</strong> methods:</p>\r\n\r\n<pre>\r\nclass Person:\r\n    def __init__(self, name):\r\n        self.__name = name\r\n\r\n    def get_name(self):\r\n        return self.__name\r\n\r\n    def set_name(self, name):\r\n        self.__name = name\r\n\r\np = Person(\"Razz\")\r\nprint(p.get_name())  # Razz\r\np.set_name(\"Raj\")\r\nprint(p.get_name())  # Raj\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Why Encapsulation?</h2>\r\n\r\n<ul>\r\n  <li>Protects data from being modified directly</li>\r\n  <li>Provides control over data access</li>\r\n  <li>Improves code maintainability</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nclass BankAccount:\r\n    def __init__(self, balance):\r\n        self.__balance = balance\r\n\r\n    def deposit(self, amount):\r\n        self.__balance += amount\r\n\r\n    def get_balance(self):\r\n        return self.__balance\r\n\r\naccount = BankAccount(1000)\r\naccount.deposit(500)\r\nprint(account.get_balance())  # 1500\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Decorators</strong> — a powerful tool to modify functions dynamically.</p>\r\n\r\n</body>\r\n</html>\r\n', 18, 1, '2025-10-23 09:57:58', '2025-10-23 11:50:52'),
('py-adv-lesson-19', 'Python-Advance', 'Python Decorators', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 19: Python Decorators</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 19: Python Decorators</h1>\r\n\r\n<p><strong>Decorators</strong> are a way to <strong>modify or enhance functions</strong> without changing their code directly.  \r\nThey are commonly used for logging, authentication, and performance measurement.</p>\r\n\r\n<hr>\r\n\r\n<h2>Basic Function Example</h2>\r\n<pre>\r\ndef greet():\r\n    print(\"Hello, CodeNepal!\")\r\n\r\ngreet()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Creating a Simple Decorator</h2>\r\n\r\n<p>A decorator is a function that <strong>takes another function</strong> as input and returns a new function.</p>\r\n\r\n<pre>\r\ndef decorator(func):\r\n    def wrapper():\r\n        print(\"Before function\")\r\n        func()\r\n        print(\"After function\")\r\n    return wrapper\r\n\r\ndef greet():\r\n    print(\"Hello!\")\r\n\r\ngreet = decorator(greet)\r\ngreet()\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\nBefore function\r\nHello!\r\nAfter function\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using <code>@</code> Syntax</h2>\r\n\r\n<pre>\r\ndef decorator(func):\r\n    def wrapper():\r\n        print(\"Before function\")\r\n        func()\r\n        print(\"After function\")\r\n    return wrapper\r\n\r\n@decorator\r\ndef greet():\r\n    print(\"Hello!\")\r\n\r\ngreet()\r\n</pre>\r\n\r\n<p>- <code>@decorator</code> is <strong>equivalent</strong> to <code>greet = decorator(greet)</code></p>\r\n\r\n<hr>\r\n\r\n<h2>Decorators with Arguments</h2>\r\n\r\n<pre>\r\ndef decorator(func):\r\n    def wrapper(name):\r\n        print(\"Before function\")\r\n        func(name)\r\n        print(\"After function\")\r\n    return wrapper\r\n\r\n@decorator\r\ndef greet(name):\r\n    print(f\"Hello, {name}!\")\r\n\r\ngreet(\"Razz\")\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\nBefore function\r\nHello, Razz!\r\nAfter function\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\ndef bold(func):\r\n    def wrapper():\r\n        return \"&lt;b&gt;\" + func() + \"&lt;/b&gt;\"\r\n    return wrapper\r\n\r\n@bold\r\ndef say():\r\n    return \"CodeNepal\"\r\n\r\nprint(say())  # <b>CodeNepal</b>\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Generators</strong> — for memory-efficient iteration over large datasets.</p>\r\n\r\n</body>\r\n</html>\r\n', 19, 1, '2025-10-23 09:58:20', '2025-10-23 11:51:29'),
('py-adv-lesson-2', 'Python-Advance', 'Variables & Data Types', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 2: Variables & Data Types</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 2: Variables & Data Types</h1>\r\n\r\n<p>In Python, a <strong>variable</strong> is a name that stores a value in memory.<br>\r\nYou can think of a variable as a labelled container that holds data.</p>\r\n\r\n<hr>\r\n\r\n<h2>Declaring a Variable</h2>\r\n\r\n<p>Python does not require type declaration.<br>\r\nYou simply assign a value using <code>=</code>.</p>\r\n\r\n<pre>\r\nname = \"CodeNepal\"\r\nage = 18\r\nis_student = True\r\n</pre>\r\n\r\n<p>Here:</p>\r\n<ul>\r\n  <li><code>name</code> → string</li>\r\n  <li><code>age</code> → integer</li>\r\n  <li><code>is_student</code> → boolean</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Basic Data Types</h2>\r\n\r\n<table>\r\n  <tr>\r\n    <th>Type</th>\r\n    <th>Example</th>\r\n    <th>Meaning</th>\r\n  </tr>\r\n  <tr>\r\n    <td><code>int</code></td>\r\n    <td>10, 50, -4</td>\r\n    <td>Whole numbers</td>\r\n  </tr>\r\n  <tr>\r\n    <td><code>float</code></td>\r\n    <td>3.14, 9.8</td>\r\n    <td>Decimal numbers</td>\r\n  </tr>\r\n  <tr>\r\n    <td><code>str</code></td>\r\n    <td>\"Hello\"</td>\r\n    <td>Text</td>\r\n  </tr>\r\n  <tr>\r\n    <td><code>bool</code></td>\r\n    <td>True/False</td>\r\n    <td>Logical values</td>\r\n  </tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>Checking Data Type</h2>\r\n\r\n<p>You can use <code>type()</code> to check a variable type:</p>\r\n\r\n<pre>\r\nx = 42\r\nprint(type(x))  #\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Reassigning Variables</h2>\r\n\r\n<p>Variables in Python are dynamic — you can change their values anytime.</p>\r\n\r\n<pre>\r\nmessage = \"Hello\"\r\nmessage = 123  # now message is an int\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Multiple Assignments</h2>\r\n\r\n<p>Python allows assigning multiple variables in one line.</p>\r\n\r\n<pre>\r\na, b, c = 1, 2, 3\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nname = \"Razz\"\r\ncountry = \"Nepal\"\r\nprint(name, country)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Input from User</h2>\r\n\r\n<p>To read data from keyboard, use <code>input()</code>:</p>\r\n\r\n<pre>\r\nuser_name = input(\"Enter your name: \")\r\nprint(\"Welcome, \" + user_name)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn how to <strong>take input, convert data types</strong>, and handle numbers properly in Python.</p>\r\n\r\n</body>\r\n</html>\r\n', 2, 1, '2025-10-23 09:48:12', '2025-10-23 11:22:20'),
('py-adv-lesson-20', 'Python-Advance', 'Python Generators', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 20: Python Generators</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 20: Python Generators</h1>\r\n\r\n<p><strong>Generators</strong> are special functions that <strong>yield values one at a time</strong>, instead of returning all values at once.  \r\nThey are <strong>memory-efficient</strong>, especially for large datasets.</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating a Generator</h2>\r\n\r\n<p>Use the <code>yield</code> keyword instead of <code>return</code>.</p>\r\n\r\n<pre>\r\ndef my_generator():\r\n    yield 1\r\n    yield 2\r\n    yield 3\r\n\r\ngen = my_generator()\r\nprint(next(gen))  # 1\r\nprint(next(gen))  # 2\r\nprint(next(gen))  # 3\r\n</pre>\r\n\r\n<p>- <code>next()</code> retrieves the next value from the generator  \r\n- When all values are exhausted, it raises <code>StopIteration</code></p>\r\n\r\n<hr>\r\n\r\n<h2>Using Generator in a Loop</h2>\r\n\r\n<pre>\r\ndef numbers(n):\r\n    for i in range(n):\r\n        yield i\r\n\r\nfor num in numbers(5):\r\n    print(num)\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\n0\r\n1\r\n2\r\n3\r\n4\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Generator Expressions</h2>\r\n\r\n<p>Similar to <strong>list comprehensions</strong>, but lazy evaluation.</p>\r\n\r\n<pre>\r\nsquares = (x**2 for x in range(5))\r\nprint(next(squares))  # 0\r\nprint(next(squares))  # 1\r\n\r\nfor s in squares:\r\n    print(s)\r\n</pre>\r\n\r\n<p>- Memory-efficient: values are <strong>generated on-the-fly</strong>  \r\n- Useful for large datasets or streaming data</p>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\ndef even_numbers(n):\r\n    for i in range(n):\r\n        if i % 2 == 0:\r\n            yield i\r\n\r\nfor num in even_numbers(10):\r\n    print(num)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Context Managers (<code>with</code>)</strong> — for safe resource handling.</p>\r\n\r\n</body>\r\n</html>\r\n', 20, 1, '2025-10-23 09:58:42', '2025-10-23 11:52:01'),
('py-adv-lesson-21', 'Python-Advance', 'Python Context Managers (`with` Statement)', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 21: Python Context Managers</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 21: Python Context Managers (<code>with</code> Statement)</h1>\r\n\r\n<p><strong>Context managers</strong> are used to manage resources safely, such as files, network connections, or locks.  \r\nThe <code>with</code> statement ensures that resources are <strong>properly cleaned up</strong> after use.</p>\r\n\r\n<hr>\r\n\r\n<h2>Using <code>with</code> for Files</h2>\r\n\r\n<pre>\r\nwith open(\"example.txt\", \"w\") as f:\r\n    f.write(\"Hello CodeNepal!\")\r\n\r\n# No need to call f.close(), it\'s done automatically\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Traditional File Handling vs Context Manager</h2>\r\n\r\n<pre>\r\n# Without \'with\'\r\nf = open(\"example.txt\", \"w\")\r\nf.write(\"Hello\")\r\nf.close()\r\n\r\n# With \'with\'\r\nwith open(\"example.txt\", \"w\") as f:\r\n    f.write(\"Hello\")\r\n</pre>\r\n\r\n<p>- Using <code>with</code> <strong>avoids resource leaks</strong> even if an error occurs.</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating Custom Context Managers</h2>\r\n\r\n<p>Use the <code>contextlib</code> module or define <code>__enter__</code> and <code>__exit__</code> methods.</p>\r\n\r\n<h3>Using <code>__enter__</code> and <code>__exit__</code></h3>\r\n\r\n<pre>\r\nclass MyContext:\r\n    def __enter__(self):\r\n        print(\"Entering\")\r\n        return self\r\n\r\n    def __exit__(self, exc_type, exc_value, traceback):\r\n        print(\"Exiting\")\r\n\r\nwith MyContext() as mc:\r\n    print(\"Inside context\")\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\nEntering\r\nInside context\r\nExiting\r\n</pre>\r\n\r\n<h3>Using <code>contextlib</code></h3>\r\n\r\n<pre>\r\nfrom contextlib import contextmanager\r\n\r\n@contextmanager\r\ndef my_context():\r\n    print(\"Entering\")\r\n    yield\r\n    print(\"Exiting\")\r\n\r\nwith my_context():\r\n    print(\"Inside context\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nwith open(\"data.txt\", \"w\") as file:\r\n    file.write(\"Python Context Managers\")\r\n\r\nwith open(\"data.txt\", \"r\") as file:\r\n    print(file.read())\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Iterators</strong> — how objects can be iterated manually.</p>\r\n\r\n</body>\r\n</html>\r\n', 21, 1, '2025-10-23 09:59:04', '2025-10-23 11:52:28'),
('py-adv-lesson-22', 'Python-Advance', 'Python Iterators', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 22: Python Iterators</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 22: Python Iterators</h1>\r\n\r\n<p><strong>Iterators</strong> are objects that allow <strong>traversing a collection</strong> one element at a time.  \r\nThey implement the methods <code>__iter__()</code> and <code>__next__()</code>.</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating an Iterator from a List</h2>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3]\r\nit = iter(numbers)\r\n\r\nprint(next(it))  # 1\r\nprint(next(it))  # 2\r\nprint(next(it))  # 3\r\n# print(next(it))  # StopIteration\r\n</pre>\r\n\r\n<p>- <code>iter()</code> creates an iterator from an iterable  \r\n- <code>next()</code> retrieves the next item</p>\r\n\r\n<hr>\r\n\r\n<h2>Using Iterators in a Loop</h2>\r\n\r\n<pre>\r\nnumbers = [10, 20, 30]\r\nfor num in iter(numbers):\r\n    print(num)\r\n</pre>\r\n\r\n<p>- <code>for</code> loops automatically use the iterator protocol</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating a Custom Iterator</h2>\r\n\r\n<pre>\r\nclass MyNumbers:\r\n    def __init__(self, start, end):\r\n        self.current = start\r\n        self.end = end\r\n\r\n    def __iter__(self):\r\n        return self\r\n\r\n    def __next__(self):\r\n        if self.current > self.end:\r\n            raise StopIteration\r\n        else:\r\n            self.current += 1\r\n            return self.current - 1\r\n\r\nnums = MyNumbers(1, 5)\r\nfor n in nums:\r\n    print(n)\r\n</pre>\r\n\r\n<p>Output:</p>\r\n<pre>\r\n1\r\n2\r\n3\r\n4\r\n5\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nletters = [\"a\", \"b\", \"c\"]\r\nit = iter(letters)\r\nprint(next(it))  # a\r\nprint(next(it))  # b\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Lambda Functions</strong> — anonymous one-line functions.</p>\r\n\r\n</body>\r\n</html>\r\n', 22, 1, '2025-10-23 09:59:25', '2025-10-23 11:52:56'),
('py-adv-lesson-23', 'Python-Advance', 'Python Lambda Functions', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 23: Python Lambda Functions</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 23: Python Lambda Functions</h1>\r\n\r\n<p><strong>Lambda functions</strong> are <strong>anonymous, one-line functions</strong> in Python.  \r\nThey are useful for short, simple operations without defining a full function using <code>def</code>.</p>\r\n\r\n<hr>\r\n\r\n<h2>Basic Syntax</h2>\r\n\r\n<pre>\r\nlambda arguments: expression\r\n</pre>\r\n\r\n<p>Example:</p>\r\n\r\n<pre>\r\nsquare = lambda x: x ** 2\r\nprint(square(5))  # 25\r\n</pre>\r\n\r\n<ul>\r\n  <li><code>x</code> is the input argument</li>\r\n  <li><code>x ** 2</code> is the expression returned</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Multiple Arguments</h2>\r\n\r\n<pre>\r\nadd = lambda a, b: a + b\r\nprint(add(3, 7))  # 10\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using Lambda with <code>map()</code></h2>\r\n\r\n<p><code>map()</code> applies a function to each item of a list.</p>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3, 4]\r\nsquared = list(map(lambda x: x ** 2, numbers))\r\nprint(squared)  # [1, 4, 9, 16]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using Lambda with <code>filter()</code></h2>\r\n\r\n<p><code>filter()</code> selects items from a list that satisfy a condition.</p>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3, 4, 5]\r\neven = list(filter(lambda x: x % 2 == 0, numbers))\r\nprint(even)  # [2, 4]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using Lambda with <code>sorted()</code></h2>\r\n\r\n<pre>\r\npoints = [(2, 3), (1, 2), (4, 1)]\r\nsorted_points = sorted(points, key=lambda x: x[1])\r\nprint(sorted_points)  # [(4, 1), (1, 2), (2, 3)]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nnums = [5, 10, 15]\r\ndoubled = list(map(lambda x: x * 2, nums))\r\nprint(doubled)  # [10, 20, 30]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Map, Filter, and Reduce Functions</strong> for functional programming.</p>\r\n\r\n</body>\r\n</html>\r\n', 23, 1, '2025-10-23 09:59:41', '2025-10-23 11:54:01');
INSERT INTO `lessons` (`id`, `course_id`, `title`, `content`, `order_index`, `is_locked`, `created_at`, `updated_at`) VALUES
('py-adv-lesson-24', 'Python-Advance', 'Python map(), filter(), and reduce()', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 24: Python map, filter, and reduce</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 24: Python <code>map()</code>, <code>filter()</code>, and <code>reduce()</code></h1>\r\n\r\n<p>Python provides <strong>functional programming tools</strong> like <code>map()</code>, <code>filter()</code>, and <code>reduce()</code> to work with sequences efficiently.</p>\r\n\r\n<hr>\r\n\r\n<h2>1. <code>map()</code></h2>\r\n\r\n<p>Applies a function to each element of an iterable.</p>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3, 4]\r\nsquared = list(map(lambda x: x ** 2, numbers))\r\nprint(squared)  # [1, 4, 9, 16]\r\n</pre>\r\n\r\n<ul>\r\n  <li><code>map(func, iterable)</code> returns a map object (convert to <code>list</code> to see results)</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>2. <code>filter()</code></h2>\r\n\r\n<p>Selects elements that satisfy a condition.</p>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3, 4, 5]\r\neven = list(filter(lambda x: x % 2 == 0, numbers))\r\nprint(even)  # [2, 4]\r\n</pre>\r\n\r\n<ul>\r\n  <li><code>filter(func, iterable)</code> returns a filter object (convert to <code>list</code>)</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>3. <code>reduce()</code></h2>\r\n\r\n<p>Applies a function <strong>cumulatively</strong> to items of an iterable.  \r\nRequires <code>functools.reduce</code>.</p>\r\n\r\n<pre>\r\nfrom functools import reduce\r\n\r\nnumbers = [1, 2, 3, 4]\r\nsum_all = reduce(lambda x, y: x + y, numbers)\r\nprint(sum_all)  # 10\r\n</pre>\r\n\r\n<ul>\r\n  <li><code>reduce(func, iterable)</code> combines elements using the function</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Example: Combine <code>map</code> and <code>filter</code></h2>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3, 4, 5, 6]\r\n\r\n# Square only even numbers\r\nresult = list(map(lambda x: x ** 2, filter(lambda x: x % 2 == 0, numbers)))\r\nprint(result)  # [4, 16, 36]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nnums = [1, 2, 3, 4, 5]\r\nsquares = list(map(lambda x: x**2, nums))\r\nevens = list(filter(lambda x: x % 2 == 0, nums))\r\nfrom functools import reduce\r\nsum_nums = reduce(lambda a, b: a+b, nums)\r\n\r\nprint(squares)\r\nprint(evens)\r\nprint(sum_nums)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Regular Expressions (<code>re</code> module)</strong> — for text pattern matching.</p>\r\n\r\n</body>\r\n</html>\r\n', 24, 1, '2025-10-23 10:00:25', '2025-10-23 11:54:34'),
('py-adv-lesson-25', 'Python-Advance', 'Python Regular Expressions (RE Module)', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 25: Python Regular Expressions</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n    table { border-collapse: collapse; width: 80%; margin-bottom: 20px; }\r\n    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }\r\n    th { background-color: #eee; }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 25: Python Regular Expressions (<code>re</code> Module)</h1>\r\n\r\n<p><strong>Regular expressions (regex)</strong> allow you to <strong>search, match, and manipulate text patterns</strong> efficiently. Python provides the <code>re</code> module for regex operations.</p>\r\n\r\n<hr>\r\n\r\n<h2>Importing <code>re</code></h2>\r\n\r\n<pre>\r\nimport re\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Matching Patterns</h2>\r\n\r\n<ul>\r\n  <li><code>re.match()</code> checks for a match <strong>at the beginning</strong> of a string</li>\r\n  <li><code>re.search()</code> searches <strong>anywhere</strong> in the string</li>\r\n</ul>\r\n\r\n<pre>\r\nimport re\r\n\r\ntext = \"CodeNepal Python\"\r\nmatch = re.match(\"CodeNepal\", text)\r\nprint(match)  # &lt;re.Match object; ...&gt;\r\n\r\nsearch = re.search(\"Python\", text)\r\nprint(search) # &lt;re.Match object; ...&gt;\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Finding All Matches</h2>\r\n\r\n<pre>\r\ntext = \"The rain in Nepal falls in Nepal\"\r\nmatches = re.findall(\"Nepal\", text)\r\nprint(matches)  # [\'Nepal\', \'Nepal\']\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Replacing Text</h2>\r\n\r\n<pre>\r\ntext = \"I love Python\"\r\nnew_text = re.sub(\"Python\", \"Java\", text)\r\nprint(new_text)  # I love Java\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Splitting Text</h2>\r\n\r\n<pre>\r\ntext = \"apple,banana,cherry\"\r\nfruits = re.split(\",\", text)\r\nprint(fruits)  # [\'apple\', \'banana\', \'cherry\']\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Common Regex Patterns</h2>\r\n\r\n<table>\r\n  <tr><th>Pattern</th><th>Meaning</th></tr>\r\n  <tr><td>.</td><td>Any character except newline</td></tr>\r\n  <tr><td>\\d</td><td>Digit (0-9)</td></tr>\r\n  <tr><td>\\D</td><td>Non-digit</td></tr>\r\n  <tr><td>\\w</td><td>Word character (letters, digits, _)</td></tr>\r\n  <tr><td>\\s</td><td>Whitespace</td></tr>\r\n  <tr><td>^</td><td>Start of string</td></tr>\r\n  <tr><td>$</td><td>End of string</td></tr>\r\n  <tr><td>*</td><td>0 or more repetitions</td></tr>\r\n  <tr><td>+</td><td>1 or more repetitions</td></tr>\r\n  <tr><td>?</td><td>0 or 1 repetition</td></tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nimport re\r\n\r\ntext = \"My number is 9876543210\"\r\npattern = \"\\d+\"\r\nnumbers = re.findall(pattern, text)\r\nprint(numbers)  # [\'9876543210\']\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Comprehensions</strong> — list, dictionary, and set comprehensions for concise code.</p>\r\n\r\n</body>\r\n</html>\r\n', 25, 1, '2025-10-23 10:00:56', '2025-10-23 11:55:22'),
('py-adv-lesson-26', 'Python-Advance', 'Python Comprehensions', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 26: Python Comprehensions</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n    hr { margin: 30px 0; border: 0; border-top: 1px solid #ccc; }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 26: Python Comprehensions</h1>\r\n\r\n<p><strong>Comprehensions</strong> provide a <strong>concise way</strong> to create lists, dictionaries, and sets. They make code shorter and more readable.</p>\r\n\r\n<hr>\r\n\r\n<h2>List Comprehension</h2>\r\n\r\n<pre>\r\n# Create a list of squares\r\nsquares = [x**2 for x in range(5)]\r\nprint(squares)  # [0, 1, 4, 9, 16]\r\n</pre>\r\n\r\n<p>Syntax: <code>[expression for item in iterable if condition]</code></p>\r\n\r\n<pre>\r\n# Squares of even numbers only\r\neven_squares = [x**2 for x in range(10) if x % 2 == 0]\r\nprint(even_squares)  # [0, 4, 16, 36, 64]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Dictionary Comprehension</h2>\r\n\r\n<pre>\r\n# Number : Square\r\nsquares_dict = {x: x**2 for x in range(5)}\r\nprint(squares_dict)  # {0: 0, 1: 1, 2: 4, 3: 9, 4: 16}\r\n</pre>\r\n\r\n<p>Can include conditionals:</p>\r\n\r\n<pre>\r\neven_squares_dict = {x: x**2 for x in range(10) if x % 2 == 0}\r\nprint(even_squares_dict)  # {0: 0, 2: 4, 4: 16, 6: 36, 8: 64}\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Set Comprehension</h2>\r\n\r\n<pre>\r\n# Unique squares\r\nsquares_set = {x**2 for x in range(5)}\r\nprint(squares_set)  # {0, 1, 4, 9, 16}\r\n</pre>\r\n\r\n<p>Sets automatically remove duplicates</p>\r\n\r\n<hr>\r\n\r\n<h2>Nested Comprehension (Optional)</h2>\r\n\r\n<pre>\r\nmatrix = [[1, 2, 3], [4, 5, 6]]\r\nflat = [num for row in matrix for num in row]\r\nprint(flat)  # [1, 2, 3, 4, 5, 6]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nnums = [1, 2, 3, 4, 5]\r\nsquared_even = [x**2 for x in nums if x % 2 == 0]\r\nprint(squared_even)  # [4, 16]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Itertools Module</strong> — powerful tools for iteration and combinatorics.</p>\r\n\r\n</body>\r\n</html>\r\n', 26, 1, '2025-10-23 10:01:17', '2025-10-23 11:55:56'),
('py-adv-lesson-27', 'Python-Advance', 'Python Itertools Module', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 27: Python itertools Module</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n    hr { margin: 30px 0; border: 0; border-top: 1px solid #ccc; }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 27: Python <code>itertools</code> Module</h1>\r\n\r\n<p>The <code>itertools</code> module provides <strong>powerful tools</strong> for iteration, combinatorics, and working with sequences efficiently.</p>\r\n\r\n<hr>\r\n\r\n<h2>Importing <code>itertools</code></h2>\r\n\r\n<pre>\r\nimport itertools\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2><code>count()</code></h2>\r\n\r\n<p>Creates an <strong>infinite iterator</strong> starting from a number.</p>\r\n\r\n<pre>\r\nimport itertools\r\n\r\ncounter = itertools.count(start=5, step=2)\r\nprint(next(counter))  # 5\r\nprint(next(counter))  # 7\r\nprint(next(counter))  # 9\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2><code>cycle()</code></h2>\r\n\r\n<p>Repeats an iterable <strong>infinitely</strong>.</p>\r\n\r\n<pre>\r\ncolors = [\'red\', \'green\', \'blue\']\r\ncycled = itertools.cycle(colors)\r\n\r\nprint(next(cycled))  # red\r\nprint(next(cycled))  # green\r\nprint(next(cycled))  # blue\r\nprint(next(cycled))  # red\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2><code>repeat()</code></h2>\r\n\r\n<p>Repeats a <strong>single value</strong> multiple times.</p>\r\n\r\n<pre>\r\nfor item in itertools.repeat(\"Hello\", 3):\r\n    print(item)\r\n</pre>\r\n\r\n<pre>\r\nOutput:\r\nHello\r\nHello\r\nHello\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2><code>permutations()</code></h2>\r\n\r\n<p>Generates all possible <strong>orderings</strong> of a sequence.</p>\r\n\r\n<pre>\r\nitems = [1, 2, 3]\r\nfor p in itertools.permutations(items):\r\n    print(p)\r\n</pre>\r\n\r\n<pre>\r\nOutput:\r\n(1, 2, 3)\r\n(1, 3, 2)\r\n(2, 1, 3)\r\n(2, 3, 1)\r\n(3, 1, 2)\r\n(3, 2, 1)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2><code>combinations()</code></h2>\r\n\r\n<p>Generates <strong>all possible combinations</strong> of a given length.</p>\r\n\r\n<pre>\r\nitems = [1, 2, 3]\r\nfor c in itertools.combinations(items, 2):\r\n    print(c)\r\n</pre>\r\n\r\n<pre>\r\nOutput:\r\n(1, 2)\r\n(1, 3)\r\n(2, 3)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nimport itertools\r\n\r\nletters = [\'A\', \'B\', \'C\']\r\nfor comb in itertools.combinations(letters, 2):\r\n    print(comb)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Threading and Concurrency</strong> — running multiple tasks simultaneously.</p>\r\n\r\n</body>\r\n</html>\r\n', 27, 1, '2025-10-23 10:01:50', '2025-10-23 11:56:27'),
('py-adv-lesson-28', 'Python-Advance', 'Python Threading and Concurrency', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 28: Python Threading and Concurrency</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n    hr { margin: 30px 0; border: 0; border-top: 1px solid #ccc; }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 28: Python Threading and Concurrency</h1>\r\n\r\n<p><strong>Threading</strong> allows Python to run <strong>multiple tasks simultaneously</strong> within a single program.  \r\nUseful for I/O-bound tasks like network requests, file operations, or GUI apps.</p>\r\n\r\n<hr>\r\n\r\n<h2>Importing Threading</h2>\r\n\r\n<pre>\r\nimport threading\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Creating a Simple Thread</h2>\r\n\r\n<pre>\r\nimport threading\r\n\r\ndef print_numbers():\r\n    for i in range(5):\r\n        print(i)\r\n\r\nt1 = threading.Thread(target=print_numbers)\r\nt1.start()  # Starts the thread\r\nt1.join()   # Waits for thread to finish\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using Multiple Threads</h2>\r\n\r\n<pre>\r\nimport threading\r\n\r\ndef task(name):\r\n    for i in range(3):\r\n        print(f\"{name} running {i}\")\r\n\r\nt1 = threading.Thread(target=task, args=(\"Thread-1\",))\r\nt2 = threading.Thread(target=task, args=(\"Thread-2\",))\r\n\r\nt1.start()\r\nt2.start()\r\n\r\nt1.join()\r\nt2.join()\r\n</pre>\r\n\r\n<p>- <code>args</code> passes arguments to the target function  \r\n- Threads run <strong>concurrently</strong></p>\r\n\r\n<hr>\r\n\r\n<h2>Daemon Threads</h2>\r\n\r\n<p>Daemon threads <strong>run in the background</strong> and exit automatically when main program ends.</p>\r\n\r\n<pre>\r\nimport threading\r\nimport time\r\n\r\ndef background_task():\r\n    while True:\r\n        print(\"Running in background...\")\r\n        time.sleep(1)\r\n\r\nt = threading.Thread(target=background_task)\r\nt.daemon = True\r\nt.start()\r\n\r\nprint(\"Main program ends\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Thread Safety</h2>\r\n\r\n<p>Use <strong>Locks</strong> to prevent multiple threads from modifying shared resources simultaneously.</p>\r\n\r\n<pre>\r\nimport threading\r\n\r\nlock = threading.Lock()\r\ncounter = 0\r\n\r\ndef increment():\r\n    global counter\r\n    for _ in range(1000):\r\n        with lock:\r\n            counter += 1\r\n\r\nthreads = [threading.Thread(target=increment) for _ in range(5)]\r\nfor t in threads:\r\n    t.start()\r\nfor t in threads:\r\n    t.join()\r\n\r\nprint(counter)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nimport threading\r\n\r\ndef greet(name):\r\n    print(f\"Hello, {name}!\")\r\n\r\nt = threading.Thread(target=greet, args=(\"Razz\",))\r\nt.start()\r\nt.join()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Asyncio</strong> — asynchronous programming for high-performance tasks.</p>\r\n\r\n</body>\r\n</html>\r\n', 28, 1, '2025-10-23 10:02:16', '2025-10-23 11:56:57'),
('py-adv-lesson-29', 'Python-Advance', 'Python Asyncio — Asynchronous Programming', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 29: Python Asyncio</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n    hr { margin: 30px 0; border: 0; border-top: 1px solid #ccc; }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 29: Python <code>asyncio</code> — Asynchronous Programming</h1>\r\n\r\n<p><strong>Asyncio</strong> allows Python to run <strong>tasks asynchronously</strong>.  \r\nThis is useful for I/O-bound operations like web requests, file reading, or APIs without blocking the program.</p>\r\n\r\n<hr>\r\n\r\n<h2>Importing <code>asyncio</code></h2>\r\n\r\n<pre>\r\nimport asyncio\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Defining an Asynchronous Function</h2>\r\n\r\n<p>Use <code>async def</code> to define a coroutine.</p>\r\n\r\n<pre>\r\nimport asyncio\r\n\r\nasync def greet():\r\n    print(\"Hello CodeNepal\")\r\n</pre>\r\n\r\n<ul>\r\n<li><code>async</code> marks it as <strong>asynchronous</strong></li>\r\n<li>Does <strong>not run immediately</strong>; must be awaited</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Running a Coroutine</h2>\r\n\r\n<pre>\r\nasync def greet():\r\n    print(\"Hello CodeNepal\")\r\n\r\nasyncio.run(greet())\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using <code>await</code></h2>\r\n\r\n<p>Use <code>await</code> to wait for another coroutine.</p>\r\n\r\n<pre>\r\nimport asyncio\r\n\r\nasync def say_after(delay, message):\r\n    await asyncio.sleep(delay)\r\n    print(message)\r\n\r\nasync def main():\r\n    print(\"Start\")\r\n    await say_after(2, \"Hello after 2 seconds\")\r\n    print(\"End\")\r\n\r\nasyncio.run(main())\r\n</pre>\r\n\r\n<pre>\r\nOutput:\r\nStart\r\n(wait 2 seconds)\r\nHello after 2 seconds\r\nEnd\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Running Multiple Coroutines Concurrently</h2>\r\n\r\n<pre>\r\nimport asyncio\r\n\r\nasync def task(name, delay):\r\n    await asyncio.sleep(delay)\r\n    print(f\"{name} finished\")\r\n\r\nasync def main():\r\n    await asyncio.gather(\r\n        task(\"Task 1\", 2),\r\n        task(\"Task 2\", 1)\r\n    )\r\n\r\nasyncio.run(main())\r\n</pre>\r\n\r\n<pre>\r\nOutput:\r\nTask 2 finished\r\nTask 1 finished\r\n</pre>\r\n\r\n<p><code>asyncio.gather()</code> runs multiple tasks <strong>concurrently</strong></p>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nimport asyncio\r\n\r\nasync def greet(name):\r\n    await asyncio.sleep(1)\r\n    print(f\"Hello, {name}!\")\r\n\r\nasyncio.run(greet(\"Razz\"))\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Python Modules and Packages</strong> — organizing code effectively.</p>\r\n\r\n</body>\r\n</html>\r\n', 29, 1, '2025-10-23 10:02:40', '2025-10-23 11:58:16'),
('py-adv-lesson-3', 'Python-Advance', 'Input & Type Conversion', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 3: Input & Type Conversion</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 3: Input & Type Conversion</h1>\r\n\r\n<p>In this lesson, you will learn how to take input from the user and convert it into the correct data type.</p>\r\n\r\n<hr>\r\n\r\n<h2><code>input()</code> Function</h2>\r\n\r\n<p>The <code>input()</code> function is used to take input from the keyboard:</p>\r\n\r\n<pre>\r\nname = input(\"Enter your name: \")\r\nprint(\"Hello,\", name)\r\n</pre>\r\n\r\n<p>⚠ Note: All input from <code>input()</code> is stored as a <strong>string</strong> by default.</p>\r\n\r\n<hr>\r\n\r\n<h2>Example</h2>\r\n\r\n<pre>\r\nage = input(\"Enter your age: \")\r\nprint(age, type(age))\r\n</pre>\r\n\r\n<p>Even if you enter <code>18</code>, Python treats it as <code>\"18\"</code> (string), not integer.</p>\r\n\r\n<hr>\r\n\r\n<h2>Type Conversion (Casting)</h2>\r\n\r\n<p>To convert input into another data type:</p>\r\n\r\n<table>\r\n  <tr>\r\n    <th>Conversion Function</th>\r\n    <th>Use</th>\r\n  </tr>\r\n  <tr>\r\n    <td><code>int()</code></td>\r\n    <td>string → integer</td>\r\n  </tr>\r\n  <tr>\r\n    <td><code>float()</code></td>\r\n    <td>string → decimal</td>\r\n  </tr>\r\n  <tr>\r\n    <td><code>str()</code></td>\r\n    <td>anything → string</td>\r\n  </tr>\r\n  <tr>\r\n    <td><code>bool()</code></td>\r\n    <td>to boolean</td>\r\n  </tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>Example: String → Integer</h2>\r\n\r\n<pre>\r\nage = int(input(\"Enter your age: \"))\r\nprint(age + 2)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Example: String → Float</h2>\r\n\r\n<pre>\r\nprice = float(input(\"Enter price: \"))\r\nprint(price * 2)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Combining Text + Variables</h2>\r\n\r\n<pre>\r\nname = input(\"Enter your name: \")\r\nage = int(input(\"Enter your age: \"))\r\nprint(\"My name is\", name, \"and I am\", age, \"years old.\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nnum1 = int(input(\"Enter first number: \"))\r\nnum2 = int(input(\"Enter second number: \"))\r\nprint(\"Sum =\", num1 + num2)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Operators in Python</strong> (arithmetic, comparison, and logical operators).</p>\r\n\r\n</body>\r\n</html>\r\n', 3, 1, '2025-10-23 09:50:41', '2025-10-23 11:23:57'),
('py-adv-lesson-30', 'Python-Advance', 'Python Modules and Packages', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 30: Python Modules and Packages</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n    hr { margin: 30px 0; border: 0; border-top: 1px solid #ccc; }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 30: Python Modules and Packages</h1>\r\n\r\n<p><strong>Modules</strong> and <strong>packages</strong> help organize Python code into reusable and maintainable components.</p>\r\n\r\n<hr>\r\n\r\n<h2>What is a Module?</h2>\r\n\r\n<p>A <strong>module</strong> is a <code>.py</code> file containing functions, classes, or variables.  \r\nYou can <strong>import</strong> a module to use its functionality.</p>\r\n\r\n<pre>\r\n# file: my_module.py\r\ndef greet(name):\r\n    print(f\"Hello, {name}!\")\r\n</pre>\r\n\r\n<pre>\r\n# main.py\r\nimport my_module\r\n\r\nmy_module.greet(\"Razz\")  # Hello, Razz!\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Importing Specific Items</h2>\r\n\r\n<pre>\r\nfrom my_module import greet\r\ngreet(\"Raj\")  # Hello, Raj!\r\n</pre>\r\n\r\n<ul>\r\n<li>Avoids importing the entire module</li>\r\n<li>Only imports the needed function or class</li>\r\n</ul>\r\n\r\n<hr>\r\n\r\n<h2>Using Aliases</h2>\r\n\r\n<pre>\r\nimport my_module as mm\r\nmm.greet(\"Razz\")  # Hello, Razz!\r\n</pre>\r\n\r\n<p><code>as</code> gives a <strong>shortcut name</strong> for the module</p>\r\n\r\n<hr>\r\n\r\n<h2>What is a Package?</h2>\r\n\r\n<p>A <strong>package</strong> is a folder containing <strong>modules</strong> and an <code>__init__.py</code> file.  \r\nIt allows <strong>hierarchical organization</strong> of code.</p>\r\n\r\n<pre>\r\nmy_package/\r\n    __init__.py\r\n    module1.py\r\n    module2.py\r\n</pre>\r\n\r\n<pre>\r\nfrom my_package import module1\r\nmodule1.some_function()\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Standard Library Modules</h2>\r\n\r\n<p>Python provides many built-in modules:</p>\r\n\r\n<ul>\r\n<li><code>math</code> — mathematical functions</li>\r\n<li><code>os</code> — operating system utilities</li>\r\n<li><code>sys</code> — system-specific parameters</li>\r\n<li><code>random</code> — random numbers</li>\r\n<li><code>datetime</code> — date and time operations</li>\r\n<li><code>json</code> — working with JSON data</li>\r\n</ul>\r\n\r\n<pre>\r\nimport math\r\nprint(math.sqrt(16))  # 4.0\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nimport random\r\nprint(random.randint(1, 10))  # random number between 1 and 10\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>✅ Congratulations!  \r\nYou have completed <strong>Python Advanced Course (Lessons 1–30)</strong>.  \r\nGet Your Certificate Now!</p>\r\n\r\n<p>Next steps: practice real-world projects, explore Python libraries like <code>pandas</code>, <code>numpy</code>, <code>requests</code>, and continue building your Python skills.</p>\r\n\r\n</body>\r\n</html>\r\n', 30, 1, '2025-10-23 10:03:31', '2025-10-23 11:58:49'),
('py-adv-lesson-4', 'Python-Advance', 'Operators in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 4: Operators in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 4: Operators in Python</h1>\r\n\r\n<p>Operators are symbols that perform actions on values or variables.<br>\r\nPython supports different types of operators such as arithmetic, comparison, logical, and more.</p>\r\n\r\n<hr>\r\n\r\n<h2>1. Arithmetic Operators</h2>\r\n\r\n<table>\r\n  <tr>\r\n    <th>Operator</th>\r\n    <th>Example</th>\r\n    <th>Meaning</th>\r\n  </tr>\r\n  <tr><td><code>+</code></td><td>a + b</td><td>Addition</td></tr>\r\n  <tr><td><code>-</code></td><td>a - b</td><td>Subtraction</td></tr>\r\n  <tr><td><code>*</code></td><td>a * b</td><td>Multiplication</td></tr>\r\n  <tr><td><code>/</code></td><td>a / b</td><td>Division (float)</td></tr>\r\n  <tr><td><code>//</code></td><td>a // b</td><td>Floor division (no decimals)</td></tr>\r\n  <tr><td><code>%</code></td><td>a % b</td><td>Remainder</td></tr>\r\n  <tr><td><code>**</code></td><td>a ** b</td><td>Exponent (power)</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\na = 10\r\nb = 3\r\nprint(a % b)   # 1\r\nprint(a ** b)  # 1000\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>2. Comparison Operators</h2>\r\n\r\n<p>These compare two values and return <code>True</code> or <code>False</code>.</p>\r\n\r\n<table>\r\n  <tr><th>Operator</th><th>Meaning</th></tr>\r\n  <tr><td><code>==</code></td><td>Equal</td></tr>\r\n  <tr><td><code>!=</code></td><td>Not equal</td></tr>\r\n  <tr><td><code>&gt;</code></td><td>Greater than</td></tr>\r\n  <tr><td><code>&lt;</code></td><td>Less than</td></tr>\r\n  <tr><td><code>&gt;=</code></td><td>Greater or equal</td></tr>\r\n  <tr><td><code>&lt;=</code></td><td>Less or equal</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\nx = 5\r\nprint(x > 3)   # True\r\nprint(x == 8)  # False\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>3. Logical Operators</h2>\r\n\r\n<p>Used to combine conditions.</p>\r\n\r\n<table>\r\n  <tr><th>Operator</th><th>Meaning</th></tr>\r\n  <tr><td><code>and</code></td><td>True if both are True</td></tr>\r\n  <tr><td><code>or</code></td><td>True if at least one is True</td></tr>\r\n  <tr><td><code>not</code></td><td>Reverses the result</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\nage = 20\r\nprint(age > 18 and age < 30)  # True\r\nprint(not(age > 18))          # False\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>4. Assignment Operators</h2>\r\n\r\n<table>\r\n  <tr><th>Operator</th><th>Meaning</th></tr>\r\n  <tr><td><code>=</code></td><td>Assign</td></tr>\r\n  <tr><td><code>+=</code></td><td>Add and assign</td></tr>\r\n  <tr><td><code>-=</code></td><td>Subtract and assign</td></tr>\r\n  <tr><td><code>*=</code></td><td>Multiply and assign</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\nx = 5\r\nx += 3  # same as x = x + 3\r\nprint(x)  # 8\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\na = 10\r\nb = 4\r\nprint(\"Addition:\", a + b)\r\nprint(\"Comparison:\", a > b)\r\nprint(\"Logical:\", a > 5 and b < 10)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will explore <strong>Strings in Python</strong> — one of the most important data types.</p>\r\n\r\n</body>\r\n</html>\r\n', 4, 1, '2025-10-23 09:51:15', '2025-10-23 11:24:49'),
('py-adv-lesson-5', 'Python-Advance', 'Strings in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 5: Strings in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 5: Strings in Python</h1>\r\n\r\n<p>A <strong>string</strong> is a sequence of characters enclosed in quotes.<br>\r\nYou can use single (<code>\' \'</code>), double (<code>\" \"</code>), or triple quotes (<code>\'\'\' \'\'\'</code> or <code>\"\"\" \"\"\"</code>).</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating Strings</h2>\r\n\r\n<pre>\r\nname = \"CodeNepal\"\r\ngreeting = \'Hello\'\r\nparagraph = \"\"\"This is a\r\nmulti-line string.\"\"\"\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Accessing Characters (Indexing)</h2>\r\n\r\n<p>Strings are <strong>indexed</strong>, meaning each character has a position.</p>\r\n\r\n<pre>\r\ntext = \"Python\"\r\nprint(text[0])   # P\r\nprint(text[3])   # h\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>String Length</h2>\r\n\r\n<pre>\r\nmsg = \"Hello\"\r\nprint(len(msg))  # 5\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Slicing Strings</h2>\r\n\r\n<p>You can extract a part of a string using slicing.</p>\r\n\r\n<pre>\r\nword = \"CodeNepal\"\r\nprint(word[0:4])   # Code\r\nprint(word[4:9])   # Nepal\r\n</pre>\r\n\r\n<p>Format: <code>string[start : end]</code> (end index is excluded)</p>\r\n\r\n<hr>\r\n\r\n<h2>Common String Methods</h2>\r\n\r\n<table>\r\n  <tr><th>Method</th><th>Use</th></tr>\r\n  <tr><td><code>.lower()</code></td><td>lowercase</td></tr>\r\n  <tr><td><code>.upper()</code></td><td>uppercase</td></tr>\r\n  <tr><td><code>.title()</code></td><td>Capitalize each word</td></tr>\r\n  <tr><td><code>.strip()</code></td><td>remove spaces</td></tr>\r\n  <tr><td><code>.replace(a, b)</code></td><td>replace text</td></tr>\r\n  <tr><td><code>.split()</code></td><td>split into list</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\ns = \"  Hello Python  \"\r\nprint(s.strip())          # Hello Python\r\nprint(s.upper())          # HELLO PYTHON\r\nprint(s.replace(\"Hello\", \"Hi\"))  # Hi Python\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>String Concatenation</h2>\r\n\r\n<pre>\r\nfirst = \"Hello\"\r\nlast = \"World\"\r\nprint(first + \" \" + last)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>f-Strings (Modern Way)</h2>\r\n\r\n<pre>\r\nname = \"Razz\"\r\nage = 18\r\nprint(f\"My name is {name} and I am {age} years old.\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\ntext = \"Welcome to Python\"\r\nprint(text.lower())\r\nprint(text[0:7])\r\nprint(f\"Length of text is {len(text)}\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn <strong>Conditional Statements</strong> (<code>if/elif/else</code>) to make decisions in programs.</p>\r\n\r\n</body>\r\n</html>\r\n', 5, 1, '2025-10-23 09:51:49', '2025-10-23 11:25:31'),
('py-adv-lesson-6', 'Python-Advance', 'Conditional Statements (if / elif / else)', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 6: Conditional Statements in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 6: Conditional Statements in Python</h1>\r\n\r\n<p>Conditional statements allow your program to make decisions based on certain conditions.</p>\r\n\r\n<hr>\r\n\r\n<h2>1. if Statement</h2>\r\n\r\n<p>The <code>if</code> statement executes code only if a condition is <code>True</code>.</p>\r\n\r\n<pre>\r\nage = 18\r\nif age >= 18:\r\n    print(\"You are an adult.\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>2. if-else Statement</h2>\r\n\r\n<p>Use <code>else</code> to execute code when the condition is <code>False</code>.</p>\r\n\r\n<pre>\r\nage = 16\r\nif age >= 18:\r\n    print(\"You are an adult.\")\r\nelse:\r\n    print(\"You are a minor.\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>3. if-elif-else Statement</h2>\r\n\r\n<p>For multiple conditions, use <code>elif</code> (else if).</p>\r\n\r\n<pre>\r\nscore = 85\r\nif score >= 90:\r\n    print(\"Grade A\")\r\nelif score >= 75:\r\n    print(\"Grade B\")\r\nelif score >= 60:\r\n    print(\"Grade C\")\r\nelse:\r\n    print(\"Grade F\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>4. Nested if Statements</h2>\r\n\r\n<p>You can place an <code>if</code> inside another <code>if</code> statement.</p>\r\n\r\n<pre>\r\nnum = 10\r\nif num > 0:\r\n    print(\"Positive\")\r\n    if num % 2 == 0:\r\n        print(\"Even number\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>5. Comparison Operators</h2>\r\n\r\n<table>\r\n  <tr><th>Operator</th><th>Meaning</th></tr>\r\n  <tr><td><code>==</code></td><td>Equal</td></tr>\r\n  <tr><td><code>!=</code></td><td>Not equal</td></tr>\r\n  <tr><td><code>&gt;</code></td><td>Greater than</td></tr>\r\n  <tr><td><code>&lt;</code></td><td>Less than</td></tr>\r\n  <tr><td><code>&gt;=</code></td><td>Greater or equal</td></tr>\r\n  <tr><td><code>&lt;=</code></td><td>Less or equal</td></tr>\r\n</table>\r\n\r\n<hr>\r\n\r\n<h2>6. Logical Operators with Conditionals</h2>\r\n\r\n<p>Combine conditions using <code>and</code>, <code>or</code>, <code>not</code>.</p>\r\n\r\n<pre>\r\nage = 20\r\nhas_id = True\r\nif age >= 18 and has_id:\r\n    print(\"Entry allowed\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nnum = int(input(\"Enter a number: \"))\r\nif num > 0:\r\n    print(\"Positive\")\r\nelif num < 0:\r\n    print(\"Negative\")\r\nelse:\r\n    print(\"Zero\")\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Loops in Python</strong> — <code>for</code> and <code>while</code> loops to repeat code.</p>\r\n\r\n</body>\r\n</html>\r\n', 6, 1, '2025-10-23 09:52:26', '2025-10-23 11:26:10'),
('py-adv-lesson-7', 'Python-Advance', 'Loops in Python (for / while)', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 7: Loops in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 7: Loops in Python (for / while)</h1>\r\n\r\n<p>Loops allow you to <strong>repeat a block of code</strong> multiple times without writing it again and again.</p>\r\n\r\n<hr>\r\n\r\n<h2>The <code>for</code> Loop</h2>\r\n\r\n<p>Used to iterate over a sequence (like a list, string, or range).</p>\r\n\r\n<pre>\r\nfruits = [\"apple\", \"banana\", \"cherry\"]\r\n\r\nfor fruit in fruits:\r\n    print(fruit)\r\n</pre>\r\n\r\n<p>- Loops over each item in the list and prints it.<br>\r\n- Works with <strong>strings</strong> too:</p>\r\n\r\n<pre>\r\nfor letter in \"Python\":\r\n    print(letter)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Using <code>range()</code> with for Loop</h2>\r\n\r\n<p><code>range()</code> generates a sequence of numbers.</p>\r\n\r\n<pre>\r\nfor i in range(5):\r\n    print(i)\r\n</pre>\r\n\r\n<p>Output: 0 1 2 3 4</p>\r\n\r\n<ul>\r\n  <li><code>range(start, end)</code> → numbers from start to end-1</li>\r\n  <li><code>range(start, end, step)</code> → numbers with step</li>\r\n</ul>\r\n\r\n<pre>\r\nfor i in range(2, 10, 2):\r\n    print(i)\r\n</pre>\r\n\r\n<p>Output: 2 4 6 8</p>\r\n\r\n<hr>\r\n\r\n<h2>The <code>while</code> Loop</h2>\r\n\r\n<p>Runs <strong>as long as a condition is True</strong>.</p>\r\n\r\n<pre>\r\ncount = 0\r\n\r\nwhile count < 5:\r\n    print(\"Count:\", count)\r\n    count += 1\r\n</pre>\r\n\r\n<p>- Be careful: <strong>infinite loop</strong> if the condition never becomes False</p>\r\n\r\n<hr>\r\n\r\n<h2>Loop Control Statements</h2>\r\n\r\n<ul>\r\n  <li><code>break</code> → stops the loop completely</li>\r\n  <li><code>continue</code> → skips the current iteration</li>\r\n  <li><code>pass</code> → does nothing (placeholder)</li>\r\n</ul>\r\n\r\n<pre>\r\nfor i in range(5):\r\n    if i == 3:\r\n        break\r\n    print(i)\r\n\r\nfor i in range(5):\r\n    if i == 2:\r\n        continue\r\n    print(i)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nfor i in range(1, 6):\r\n    print(\"Number:\", i)\r\n\r\ncount = 5\r\nwhile count > 0:\r\n    print(count)\r\n    count -= 1\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Lists in Python</strong> — a fundamental data structure to store multiple values.</p>\r\n\r\n</body>\r\n</html>\r\n', 7, 1, '2025-10-23 09:52:52', '2025-10-23 11:27:01'),
('py-adv-lesson-8', 'Python-Advance', 'Lists in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 8: Lists in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 8: Lists in Python</h1>\r\n\r\n<p>A <strong>list</strong> is an <strong>ordered collection</strong> of items, which can be of different data types.  \r\nLists are <strong>mutable</strong>, meaning you can change, add, or remove elements.</p>\r\n\r\n<hr>\r\n\r\n<h2>Creating a List</h2>\r\n\r\n<pre>\r\nfruits = [\"apple\", \"banana\", \"cherry\"]\r\nnumbers = [1, 2, 3, 4, 5]\r\nmixed = [1, \"apple\", True]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Accessing Elements</h2>\r\n\r\n<p>Lists are <strong>indexed</strong>, starting from 0.</p>\r\n\r\n<pre>\r\nfruits = [\"apple\", \"banana\", \"cherry\"]\r\nprint(fruits[0])   # apple\r\nprint(fruits[2])   # cherry\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Slicing Lists</h2>\r\n\r\n<pre>\r\nnumbers = [1, 2, 3, 4, 5]\r\nprint(numbers[1:4])   # [2, 3, 4]\r\nprint(numbers[:3])    # [1, 2, 3]\r\nprint(numbers[2:])    # [3, 4, 5]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Modifying Lists</h2>\r\n\r\n<pre>\r\nfruits = [\"apple\", \"banana\", \"cherry\"]\r\nfruits[1] = \"orange\"\r\nprint(fruits)  # [\'apple\', \'orange\', \'cherry\']\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>List Methods</h2>\r\n\r\n<table>\r\n  <tr><th>Method</th><th>Use</th></tr>\r\n  <tr><td><code>append(x)</code></td><td>Add x at the end</td></tr>\r\n  <tr><td><code>insert(i, x)</code></td><td>Add x at index i</td></tr>\r\n  <tr><td><code>remove(x)</code></td><td>Remove first occurrence of x</td></tr>\r\n  <tr><td><code>pop()</code></td><td>Remove last element</td></tr>\r\n  <tr><td><code>sort()</code></td><td>Sort list ascending</td></tr>\r\n  <tr><td><code>reverse()</code></td><td>Reverse the list</td></tr>\r\n  <tr><td><code>len()</code></td><td>Get number of elements</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\nnumbers = [3, 1, 4]\r\nnumbers.append(2)\r\nnumbers.sort()\r\nprint(numbers)  # [1, 2, 3, 4]\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Iterating Over a List</h2>\r\n\r\n<pre>\r\nfruits = [\"apple\", \"banana\", \"cherry\"]\r\n\r\nfor fruit in fruits:\r\n    print(fruit)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\ncolors = [\"red\", \"green\", \"blue\"]\r\ncolors.append(\"yellow\")\r\nprint(colors[1:])   # [\'green\', \'blue\', \'yellow\']\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Tuples and Sets</strong> — two more important collection types in Python.</p>\r\n\r\n</body>\r\n</html>\r\n', 8, 1, '2025-10-23 09:53:24', '2025-10-23 11:32:46'),
('py-adv-lesson-9', 'Python-Advance', 'Tuples and Sets in Python', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Lesson 9: Tuples and Sets in Python</title>\r\n  <style>\r\n    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }\r\n    h1, h2, h3 { color: #2c3e50; }\r\n    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }\r\n    table, th, td { border: 1px solid #ddd; }\r\n    th, td { padding: 8px; text-align: left; }\r\n    pre, code { \r\n      background-color: #000 !important; \r\n      color: #fff !important; \r\n      padding: 10px; \r\n      border-radius: 5px; \r\n      display: block;\r\n      overflow-x: auto;\r\n      font-family: Consolas, monospace;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<h1>Lesson 9: Tuples and Sets in Python</h1>\r\n\r\n<p>In Python, besides lists, there are other collection types: <strong>tuples</strong> and <strong>sets</strong>.</p>\r\n\r\n<hr>\r\n\r\n<h2>1. Tuples</h2>\r\n\r\n<p>A <strong>tuple</strong> is an <strong>ordered collection</strong> of items, like a list, but <strong>immutable</strong> (cannot be changed).</p>\r\n\r\n<h3>Creating a Tuple</h3>\r\n\r\n<pre>\r\nfruits = (\"apple\", \"banana\", \"cherry\")\r\nnumbers = (1, 2, 3)\r\nmixed = (1, \"apple\", True)\r\n</pre>\r\n\r\n<h3>Accessing Elements</h3>\r\n\r\n<pre>\r\nfruits = (\"apple\", \"banana\", \"cherry\")\r\nprint(fruits[0])   # apple\r\nprint(fruits[2])   # cherry\r\n</pre>\r\n\r\n<h3>Tuple Methods</h3>\r\n\r\n<p>Tuples have very few methods because they are immutable:</p>\r\n\r\n<table>\r\n  <tr><th>Method</th><th>Use</th></tr>\r\n  <tr><td><code>count(x)</code></td><td>Number of occurrences of x</td></tr>\r\n  <tr><td><code>index(x)</code></td><td>Index of first occurrence of x</td></tr>\r\n</table>\r\n\r\n<pre>\r\nnumbers = (1, 2, 2, 3)\r\nprint(numbers.count(2))  # 2\r\nprint(numbers.index(3))  # 3\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>2. Sets</h2>\r\n\r\n<p>A <strong>set</strong> is an <strong>unordered collection</strong> of <strong>unique</strong> elements.  \r\nSets are <strong>mutable</strong> (you can add/remove items) but do not allow duplicates.</p>\r\n\r\n<h3>Creating a Set</h3>\r\n\r\n<pre>\r\nfruits = {\"apple\", \"banana\", \"cherry\"}\r\nnumbers = {1, 2, 3, 4, 4}   # duplicates removed automatically\r\nprint(numbers)  # {1, 2, 3, 4}\r\n</pre>\r\n\r\n<h3>Set Operations</h3>\r\n\r\n<table>\r\n  <tr><th>Operation</th><th>Example</th></tr>\r\n  <tr><td><code>add(x)</code></td><td>Add an element</td></tr>\r\n  <tr><td><code>remove(x)</code></td><td>Remove element (error if not exists)</td></tr>\r\n  <tr><td><code>discard(x)</code></td><td>Remove element (no error if missing)</td></tr>\r\n  <tr><td><code>union()</code></td><td>Combine sets</td></tr>\r\n  <tr><td><code>intersection()</code></td><td>Common elements</td></tr>\r\n  <tr><td><code>difference()</code></td><td>Elements in first but not in second</td></tr>\r\n</table>\r\n\r\n<p><strong>Example:</strong></p>\r\n\r\n<pre>\r\na = {1, 2, 3}\r\nb = {3, 4, 5}\r\n\r\nprint(a.union(b))        # {1, 2, 3, 4, 5}\r\nprint(a.intersection(b)) # {3}\r\nprint(a.difference(b))   # {1, 2}\r\n</pre>\r\n\r\n<hr>\r\n\r\n<h2>Try It Yourself</h2>\r\n\r\n<pre>\r\nmy_tuple = (10, 20, 30)\r\nmy_set = {10, 20, 20, 30, 40}\r\n\r\nprint(my_tuple[1])\r\nprint(my_set)\r\n</pre>\r\n\r\n<hr>\r\n\r\n<p>In the next lesson, we will learn about <strong>Dictionaries in Python</strong> — key-value collections.</p>\r\n\r\n</body>\r\n</html>\r\n', 9, 1, '2025-10-23 09:54:01', '2025-10-23 11:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `test_cases`
--

CREATE TABLE `test_cases` (
  `id` int(11) NOT NULL,
  `exercise_id` varchar(50) NOT NULL,
  `input` text DEFAULT NULL,
  `expected_output` text NOT NULL,
  `is_hidden` tinyint(1) DEFAULT 0,
  `order_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_cases`
--

INSERT INTO `test_cases` (`id`, `exercise_id`, `input`, `expected_output`, `is_hidden`, `order_index`) VALUES
(10, 'py-ex-1', '', 'Hello, World!', 1, 1),
(11, 'py-ex-2', '', 'Ram 18', 1, 1),
(15, 'py-ex-2-2', '', '<class \'int\'>\n<class \'float\'>\n<class \'str\'>', 0, 1),
(20, 'py-ex-3', 'CodeNepal', 'Hello, CodeNepal', 0, 1),
(21, 'py-ex-4', '', 'Addition: 13 Subtraction: 7 Multiplication: 30 Division: 3.3333333333333335 Floor Division: 3 Remainder: 1 Power: 1000', 1, 1),
(22, 'py-ex-4', '', 'Addition: 13 Subtraction: 7 Multiplication: 30 Division: 3.3333333333333335 Floor Division: 3 Remainder: 1 Power: 1000', 1, 2),
(23, 'py-ex-4-2', '', 'x = int(input()) y = int(input())  print(x == y) print(x != y) print(x > y) print(x < y) print(x >= y) print(x <= y)', 0, 1),
(24, 'py-ex-4-3', '', 'True True True', 1, 1),
(25, 'py-ex-5', '', 'P n', 1, 1),
(26, 'py-ex-6', '19', 'You are an adult.', 0, 1),
(27, 'py-ex-7', '', '1 to 20 (Serially)', 0, 1),
(28, 'py-ex-8', '', '10,50   ', 0, 1),
(29, 'py-ex-9', '', 'apple,cherry', 0, 1),
(30, 'py-ex-10', '', 'Razz, kathmandu', 0, 1),
(31, 'py-ex-11', '', 'Hello, welcome to CodeNepal!', 0, 1),
(32, 'py-ex-12', '', '8.0', 0, 1),
(33, 'py-ex-13', '', 'Content of example.txt: Hello, CodeNepal! Python File Handling.', 0, 1),
(34, 'py-ex-14', '', 'Cannot divisible by zero or 10.0', 0, 1),
(35, 'py-ex-15', '', 'Razz, 18', 0, 1),
(36, 'py-ex-16', '', 'Animal makes a sound', 0, 1),
(37, 'py-ex-17', '', '6, 5', 0, 1),
(38, 'py-ex-18', '', 'Private public concepts.', 0, 1),
(39, 'py-ex-19', '', 'Decorator concepts.', 0, 1),
(40, 'py-ex-20', '', 'Basic Generator', 0, 1),
(41, 'py-ex-21', '', 'File Writing with \"with\"', 0, 1),
(42, 'py-ex-22', '', 'Iterator...', 0, 1),
(43, 'py-ex-23', '', 'square lamda.', 0, 1),
(44, 'py-ex-24', '', 'Using map()', 0, 1),
(45, 'py-ex-25', '', 'Basic match and search', 0, 1),
(46, 'py-ex-26', '', 'List Comprehension', 0, 1),
(47, 'py-ex-27', '', 'count()', 0, 1),
(48, 'py-ex-28', '', 'Simple Thread', 0, 1),
(49, 'py-ex-29', '', 'Basic Coroutine', 0, 1),
(50, 'py=ex=30', '', 'Create and Import a Module', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `avatar_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `name`, `role`, `avatar_url`, `created_at`, `updated_at`) VALUES
('5585981e-3075-476c-8ccd-1e979198b560', 'razzacharya6@gmail.com', '$2y$10$LPQgiZeS1cPiIOKA/Xi09eQvHung259p2eifBIvty.uugXeRmm2Ma', 'harculus holland', 'user', NULL, '2025-10-23 03:17:05', '2025-10-23 03:17:05'),
('admin-001', 'admin@codenepal.com', '$2y$10$HCOqaHL7fIV9TEAPAWl2e.jIb0HFSoQgw47MRDFxpgcsMrSP2.f9O', 'Admin User', 'admin', NULL, '2025-10-23 03:23:41', '2025-10-23 03:24:54'),
('user-001', 'user@codenepal.com', '$2y$10$6PU2S29B1ade2KwdbNLDeOHY5ymuQannr0SPD3hDxNiB9UD8i3HTO', 'Test User', 'user', NULL, '2025-10-23 03:23:41', '2025-10-23 03:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `course_id` varchar(50) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`id`, `user_id`, `course_id`, `enrolled_at`, `last_accessed`) VALUES
(3, 'admin-001', 'Python-Advance', '2025-10-23 10:04:33', '2025-10-23 10:04:33'),
(4, 'user-001', 'Python-Advance', '2025-10-29 03:20:25', '2025-10-29 03:20:25'),
(5, '5585981e-3075-476c-8ccd-1e979198b560', 'Python-Advance', '2025-11-09 12:29:42', '2025-11-09 12:29:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_generated_exercises`
--
ALTER TABLE `ai_generated_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lesson_temp` (`lesson_temp_id`);

--
-- Indexes for table `ai_generated_test_cases`
--
ALTER TABLE `ai_generated_test_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exercise_temp` (`exercise_temp_id`);

--
-- Indexes for table `ai_generation_log`
--
ALTER TABLE `ai_generation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_action` (`action_type`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `ai_generation_sessions`
--
ALTER TABLE `ai_generation_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_session_progress` (`status`,`progress_percentage`);

--
-- Indexes for table `ai_validation_results`
--
ALTER TABLE `ai_validation_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_exercise` (`exercise_id`),
  ADD KEY `idx_validated` (`validated_at`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `verification_code` (`verification_code`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_verification` (`verification_code`);

--
-- Indexes for table `completed_exercises`
--
ALTER TABLE `completed_exercises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_completion` (`user_id`,`exercise_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_exercise` (`exercise_id`);

--
-- Indexes for table `completed_lessons`
--
ALTER TABLE `completed_lessons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_completion` (`user_id`,`lesson_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_lesson` (`lesson_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_language` (`language`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_published` (`is_published`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lesson` (`lesson_id`),
  ADD KEY `idx_difficulty` (`difficulty`);

--
-- Indexes for table `hints`
--
ALTER TABLE `hints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exercise` (`exercise_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course` (`course_id`),
  ADD KEY `idx_order` (`course_id`,`order_index`);

--
-- Indexes for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exercise` (`exercise_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`user_id`,`course_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_course` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_generated_exercises`
--
ALTER TABLE `ai_generated_exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_generated_lessons`
--
ALTER TABLE `ai_generated_lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_generated_test_cases`
--
ALTER TABLE `ai_generated_test_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_generation_log`
--
ALTER TABLE `ai_generation_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_validation_results`
--
ALTER TABLE `ai_validation_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `completed_exercises`
--
ALTER TABLE `completed_exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `completed_lessons`
--
ALTER TABLE `completed_lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `hints`
--
ALTER TABLE `hints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `test_cases`
--
ALTER TABLE `test_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `completed_exercises`
--
ALTER TABLE `completed_exercises`
  ADD CONSTRAINT `completed_exercises_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `completed_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `completed_lessons`
--
ALTER TABLE `completed_lessons`
  ADD CONSTRAINT `completed_lessons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `completed_lessons_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `exercises_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hints`
--
ALTER TABLE `hints`
  ADD CONSTRAINT `hints_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD CONSTRAINT `test_cases_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
