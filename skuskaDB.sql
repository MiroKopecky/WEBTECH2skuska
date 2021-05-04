-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Út 04.Máj 2021, 16:31
-- Verzia serveru: 8.0.23-0ubuntu0.20.04.1
-- Verzia PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `skuska`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `name` varchar(40) COLLATE utf8_slovak_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8_slovak_ci NOT NULL,
  `aisid` varchar(15) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `studentsAnswerLogs`
--

CREATE TABLE `studentsAnswerLogs` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `testQuestion_id` int NOT NULL,
  `test_id` int NOT NULL,
  `answer` varchar(450) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `name` varchar(40) COLLATE utf8_slovak_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8_slovak_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_slovak_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `test`
--

CREATE TABLE `test` (
  `id` int NOT NULL,
  `code` varchar(8) COLLATE utf8_slovak_ci NOT NULL,
  `teacher_id` int NOT NULL,
  `active` tinyint(1) NOT NULL,
  `timelimit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `testParcipants`
--

CREATE TABLE `testParcipants` (
  `id` int NOT NULL,
  `test_id` int NOT NULL,
  `student_id` int NOT NULL,
  `status` enum('done','solving') COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `testQuestions`
--

CREATE TABLE `testQuestions` (
  `id` int NOT NULL,
  `question` varchar(300) COLLATE utf8_slovak_ci NOT NULL,
  `answer` varchar(400) COLLATE utf8_slovak_ci NOT NULL,
  `test_id` int NOT NULL,
  `type` enum('shortAsnwer','select','pairing','drawing','mathFormula') COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aisid` (`aisid`);

--
-- Indexy pre tabuľku `studentsAnswerLogs`
--
ALTER TABLE `studentsAnswerLogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `testQuestion_id` (`testQuestion_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexy pre tabuľku `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexy pre tabuľku `testParcipants`
--
ALTER TABLE `testParcipants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `testParcipants_ibfk_2` (`test_id`);

--
-- Indexy pre tabuľku `testQuestions`
--
ALTER TABLE `testQuestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `studentsAnswerLogs`
--
ALTER TABLE `studentsAnswerLogs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `test`
--
ALTER TABLE `test`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `testParcipants`
--
ALTER TABLE `testParcipants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `testQuestions`
--
ALTER TABLE `testQuestions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `studentsAnswerLogs`
--
ALTER TABLE `studentsAnswerLogs`
  ADD CONSTRAINT `studentsAnswerLogs_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `testParcipants` (`student_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `studentsAnswerLogs_ibfk_2` FOREIGN KEY (`testQuestion_id`) REFERENCES `testQuestions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `studentsAnswerLogs_ibfk_3` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `testParcipants`
--
ALTER TABLE `testParcipants`
  ADD CONSTRAINT `testParcipants_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `testParcipants_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `testQuestions`
--
ALTER TABLE `testQuestions`
  ADD CONSTRAINT `testQuestions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
