-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Апр 23 2019 г., 16:33
-- Версия сервера: 10.1.37-MariaDB-0+deb9u1
-- Версия PHP: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sa.pharmacy.net`
--
CREATE DATABASE IF NOT EXISTS `sa.pharmacy.net` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sa.pharmacy.net`;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL COMMENT 'Идентификатор',
  `login` varchar(15) NOT NULL COMMENT 'Логин пользователя',
  `password` varchar(15) NOT NULL COMMENT 'Пароль пользователя'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Очистить таблицу перед добавлением данных `user`
--

TRUNCATE TABLE `user`;
--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'admin', '');

-- --------------------------------------------------------

--
-- Структура таблицы `user_card`
--

DROP TABLE IF EXISTS `user_card`;
CREATE TABLE `user_card` (
  `id` int(11) NOT NULL COMMENT 'Идентификатор',
  `user_id` int(11) NOT NULL COMMENT 'Внешний ключ',
  `name` varchar(50) NOT NULL COMMENT 'Полное имя',
  `email` varchar(50) NOT NULL COMMENT 'Электронная почта',
  `date_registration` date NOT NULL COMMENT 'Дата регистрации',
  `img` blob COMMENT 'Аватар пользователя'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Очистить таблицу перед добавлением данных `user_card`
--

TRUNCATE TABLE `user_card`;
--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `user_card`
--
ALTER TABLE `user_card`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_card`
--
ALTER TABLE `user_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `user_card`
--
ALTER TABLE `user_card`
  ADD CONSTRAINT `user_card_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
