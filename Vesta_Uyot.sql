-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 11 2025 г., 11:37
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
-- База данных: `Vesta_Uyot`
--

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id_group` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id_group`, `group_name`) VALUES
(7, 'ГД-23'),
(1, 'ИС-22'),
(4, 'ИС-24'),
(3, 'ИСВ-23'),
(2, 'ИСП-23'),
(5, 'ОДЛу-22'),
(6, 'ПД-22'),
(8, 'ТАКСХ-24');

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `spec` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_data` date NOT NULL,
  `end_data` date NOT NULL,
  `status_id` int(11) NOT NULL,
  `address_org` text NOT NULL,
  `ruk_org` text NOT NULL,
  `work_done` text DEFAULT NULL,
  `teacher_comment` text DEFAULT NULL,
  `organization_name` varchar(255) NOT NULL,
  `position_ruk` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id_service`, `group_id`, `spec`, `user_id`, `start_data`, `end_data`, `status_id`, `address_org`, `ruk_org`, `work_done`, `teacher_comment`, `organization_name`, `position_ruk`) VALUES
(1, 3, 'Тест', 4, '2025-12-02', '2025-12-22', 3, 'ул. Пушкина, д. Колотушкина', 'Мирон Кто-тоТам Сергенет', 'Ничего не сделал, спал', 'У бездарности с первого раза не примут', 'ООО \"Веста-Уют\"', 'Гений'),
(2, 3, 'Актер', 4, '2025-12-02', '2025-12-22', 2, 'ул. Академика Жукова, д. 25', 'Кенжегулов Калыбек Исламбекович', 'Подчинялся владельцу мира на протяжении всех дней и минут', '', 'Техникум', 'Владелец мира');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int(11) NOT NULL,
  `name_status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `name_status`) VALUES
(1, 'На проверке'),
(2, 'Принято'),
(3, 'На доработку');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `studbilet` varchar(50) DEFAULT NULL,
  `otchestvo` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `surname`, `name`, `group_id`, `studbilet`, `otchestvo`, `email`, `username`, `password`, `user_type_id`) VALUES
(1, 'Жирнова', 'Юлия', NULL, 'Преподаватель', 'Витальевна', 'ZhirnovaYUV@gmail.com', 'teacher', '2c8ade1dca7c5fa01cbceaf1e6bd654b', 2),
(2, 'Мирон', 'Артем', 3, '№ ИСВ23-16', 'Сергеевич', 'kymuskrolik@gmail.com', 'adminka', '5f4dcc3b5aa765d61d8327deb882cf99', 1),
(3, 'тест', 'тест', 3, 'тест', 'тест', 'test@test.test', 'test', '098f6bcd4621d373cade4e832627b4f6', 1),
(4, 'фывфыв', 'фывфыв', 3, 'фывфыв', 'фывфыв', 'фывфыв', 'фывфыв', 'e927dc7852a98359f58fdf3889c002ce', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_type`
--

CREATE TABLE `user_type` (
  `id_user_type` int(11) NOT NULL,
  `name_user` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user_type`
--

INSERT INTO `user_type` (`id_user_type`, `name_user`) VALUES
(1, 'Пользователь'),
(2, 'Администратор');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id_group`),
  ADD UNIQUE KEY `group_name` (`group_name`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `user_type_id` (`user_type_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id_user_type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id_group` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id_user_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`id_status`),
  ADD CONSTRAINT `service_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id_group`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id_user_type`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id_group`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
