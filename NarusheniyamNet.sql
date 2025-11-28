-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 28 2025 г., 09:50
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `NarusheniyamNet`
--

-- --------------------------------------------------------

--
-- Структура таблицы `application`
--

CREATE TABLE `application` (
  `id_application` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `car_number` varchar(10) NOT NULL,
  `name_application` text NOT NULL,
  `status_application_id` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `application`
--

INSERT INTO `application` (`id_application`, `user_id`, `car_number`, `name_application`, `status_application_id`) VALUES
(1, 1, 'О337КМ 95', 'Превышение скорости', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `status_application`
--

CREATE TABLE `status_application` (
  `id_status_application` int(1) NOT NULL,
  `name_status_application` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `status_application`
--

INSERT INTO `status_application` (`id_status_application`, `name_status_application`) VALUES
(1, 'новое'),
(2, 'подтверждено'),
(3, 'отклонено');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(1) NOT NULL,
  `surname` varchar(5) NOT NULL,
  `name` varchar(5) NOT NULL,
  `otchestvo` varchar(9) NOT NULL,
  `phone` varchar(19) NOT NULL,
  `email` varchar(14) NOT NULL,
  `username` varchar(4) NOT NULL,
  `password` varchar(8) NOT NULL,
  `user_role_id` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `surname`, `name`, `otchestvo`, `phone`, `email`, `username`, `password`, `user_role_id`) VALUES
(1, 'Мирон', 'Артем', 'Сергеевич', ' + 7(977)-601-66-67', 'pochta@mail.tu', 'copp', 'password', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE `user_role` (
  `id_user_role` int(1) NOT NULL,
  `name_user_role` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id_user_role`, `name_user_role`) VALUES
(1, 'администратор'),
(2, 'пользователь');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id_application`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_application_id` (`status_application_id`);

--
-- Индексы таблицы `status_application`
--
ALTER TABLE `status_application`
  ADD PRIMARY KEY (`id_status_application`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_role_id` (`user_role_id`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_user_role`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`status_application_id`) REFERENCES `status_application` (`id_status_application`),
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id_user_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
