-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 02 2018 г., 22:02
-- Версия сервера: 10.1.30-MariaDB
-- Версия PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `helpdesk`
--

-- --------------------------------------------------------

--
-- Структура таблицы `applications`
--

CREATE TABLE `applications` (
  `id` int(6) NOT NULL,
  `application_theme` varchar(30) NOT NULL,
  `applicant_name` varchar(30) NOT NULL,
  `applicant_address` varchar(50) NOT NULL,
  `applicant_contact` varchar(20) NOT NULL,
  `application_text` varchar(100) NOT NULL,
  `creation_date` date NOT NULL,
  `execution_date` date NOT NULL,
  `application_status` int(3) NOT NULL,
  `assigned_employee` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `applications_archive`
--

CREATE TABLE `applications_archive` (
  `id` int(6) NOT NULL,
  `application_theme` varchar(30) NOT NULL,
  `applicant_name` varchar(30) NOT NULL,
  `applicant_address` varchar(50) NOT NULL,
  `applicant_contact` varchar(20) NOT NULL,
  `application_text` varchar(100) NOT NULL,
  `creation_date` date NOT NULL,
  `execution_date` date NOT NULL,
  `application_status` int(3) NOT NULL,
  `assigned_employee` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `applications_themes`
--

CREATE TABLE `applications_themes` (
  `id` int(6) NOT NULL,
  `theme_name` varchar(20) NOT NULL,
  `theme_solutions` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `applications_themes`
--

INSERT INTO `applications_themes` (`id`, `theme_name`, `theme_solutions`) VALUES
(1, 'Обрыв кабеля', 'Направить бригаду для починки'),
(2, 'Потеря доступа', 'Связаться с службой поддержки');

-- --------------------------------------------------------

--
-- Структура таблицы `departments`
--

CREATE TABLE `departments` (
  `id` int(6) NOT NULL,
  `department_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `departments`
--

INSERT INTO `departments` (`id`, `department_name`) VALUES
(1, 'Работа с клиентами');

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE `organizations` (
  `id` int(6) NOT NULL,
  `organization_name` varchar(30) NOT NULL,
  `organization_address` varchar(50) NOT NULL,
  `organization_contact` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(6) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `real_name` varchar(30) NOT NULL,
  `real_surname` varchar(30) NOT NULL,
  `personal_data` varchar(50) NOT NULL,
  `user_department` int(6) NOT NULL,
  `active_applications` int(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_password`, `real_name`, `real_surname`, `personal_data`, `user_department`, `active_applications`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', '', 0, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `applications_archive`
--
ALTER TABLE `applications_archive`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `applications_themes`
--
ALTER TABLE `applications_themes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `applications_themes`
--
ALTER TABLE `applications_themes`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
