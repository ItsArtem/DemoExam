-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 28 2025 г., 09:49
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
-- База данных: `Moy_ne_sam`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pay_type`
--

CREATE TABLE `pay_type` (
  `id_pay_type` int(1) NOT NULL,
  `name_pay` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `pay_type`
--

INSERT INTO `pay_type` (`id_pay_type`, `name_pay`) VALUES
(1, 'Наличными'),
(2, 'Банковская карта');

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id_service` int(1) NOT NULL,
  `address` text NOT NULL,
  `user_id` int(1) NOT NULL,
  `service_type_id` int(1) NOT NULL,
  `data` date NOT NULL,
  `time` time NOT NULL,
  `pay_type_id` int(1) NOT NULL,
  `status_id` int(1) NOT NULL,
  `reason_cancel` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id_service`, `address`, `user_id`, `service_type_id`, `data`, `time`, `pay_type_id`, `status_id`, `reason_cancel`) VALUES
(1, 'ул. Пушкина, д. 1', 1, 2, '2025-10-21', '18:00:00', 1, 1, '-'),
(2, 'ул. Пушкина, д. 2', 1, 4, '2025-10-21', '19:00:00', 1, 1, '-'),
(3, 'ул. Пушкина, д. 3', 1, 2, '2025-10-21', '20:00:00', 2, 1, '-'),
(4, 'Московская обл., ул. Пушкина, д. 12', 4, 3, '2025-11-30', '15:44:00', 1, 1, NULL),
(5, 'ул. ААА, д. 111', 2, 3, '2025-11-30', '20:00:00', 1, 2, '79998887711'),
(6, 'ул. ААА, д. 111', 2, 3, '2025-11-30', '20:00:00', 1, 3, '79998887711'),
(7, 'ул. ААА, д. 1123', 2, 2, '2025-11-22', '14:00:00', 1, 1, '79998887711'),
(8, '9', 6, 1, '0009-09-09', '09:09:00', 1, 1, '9'),
(9, 'вава вавав вавав ', 2, 4, '2025-11-30', '15:28:00', 1, 1, '89991112233');

-- --------------------------------------------------------

--
-- Структура таблицы `service_type`
--

CREATE TABLE `service_type` (
  `id_service_type` int(1) NOT NULL,
  `name_service` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `service_type`
--

INSERT INTO `service_type` (`id_service_type`, `name_service`) VALUES
(1, 'Общий клининг'),
(2, 'Генеральная уборка'),
(3, 'Послестроительная уборка'),
(4, 'Химчистка ковров и мебели');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int(1) NOT NULL,
  `name_status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `name_status`) VALUES
(1, 'Новая заявка'),
(2, 'Услуга оказана'),
(3, 'Услуга отменена');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(1) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `otchestvo` varchar(50) NOT NULL,
  `phone` bigint(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_type_id` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `surname`, `name`, `otchestvo`, `phone`, `email`, `username`, `password`, `user_type_id`) VALUES
(1, 'Мирон', 'Артем', 'Сергеевич', 89776016667, 'kymuskrolik@gmail.com', 'adminka', '5f4dcc3b5aa765d61d8327deb882cf99', 2),
(2, 'test', 'test', 'test', 79999999999, 'test@mail.ru', 'test', '098f6bcd4621d373cade4e832627b4f6', 1),
(3, 'test2', 'test2', 'test2', 79998887766, 'test2@mail.ru', 'test2', 'c4d8a57e2ca5dc5d71d2cf3dbbbbaabe', 1),
(4, 'Фамилия1', 'Имя1', 'Отчество1', 81111111111, 'Email@email.email', 'xDaer', '220466675e31b9d20c051d5e57974150', 1),
(5, 'Фамилия1', 'Имя1', 'Отчество1', 81111111111, 'sadasd@email.email', 'фывыфв', 'f5bb0c8de146c67b44babbf4e6584cc0', 1),
(6, '9', '9', '9', 9, 'vgodz_s@mail.ru', '9', '45c48cce2e2d7fbdea1afc51c7c6ad26', 1),
(7, 'test11', 'test11', 'test11', 81111111111, '123123@ffdd.df', 'test1', '5a105e8b9d40e1329780d62ea2265d8a', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_type`
--

CREATE TABLE `user_type` (
  `id_user_type` int(1) NOT NULL,
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
-- Индексы таблицы `pay_type`
--
ALTER TABLE `pay_type`
  ADD PRIMARY KEY (`id_pay_type`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_type_id` (`service_type_id`),
  ADD KEY `pay_type_id` (`pay_type_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Индексы таблицы `service_type`
--
ALTER TABLE `service_type`
  ADD PRIMARY KEY (`id_service_type`);

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
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Индексы таблицы `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id_user_type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pay_type`
--
ALTER TABLE `pay_type`
  MODIFY `id_pay_type` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `service_type`
--
ALTER TABLE `service_type`
  MODIFY `id_service_type` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id_user_type` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`pay_type_id`) REFERENCES `pay_type` (`id_pay_type`),
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `service_ibfk_3` FOREIGN KEY (`service_type_id`) REFERENCES `service_type` (`id_service_type`),
  ADD CONSTRAINT `service_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `status` (`id_status`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id_user_type`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
