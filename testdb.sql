-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 03 2021 г., 00:15
-- Версия сервера: 10.4.21-MariaDB
-- Версия PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `testdb`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_user` (IN `in_name` TEXT CHARSET utf8, IN `in_pass` TEXT CHARSET utf8, IN `in_email` TEXT CHARSET utf8)  BEGIN
DECLARE
new_user TEXT;
INSERT INTO `testdb`.`user` (`name`, `email`) VALUES(in_name, in_email);
SELECT CONCAT('user_',LAST_INSERT_ID()) INTO new_user;
PREPARE add_role FROM CONCAT('GRANT SELECT, UPDATE ON `testdb`.`user` TO ',new_user,' IDENTIFIED BY ''',in_pass,'''');
EXECUTE add_role;
SELECT LAST_INSERT_ID();
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `email` text COLLATE utf8_bin NOT NULL,
  `photo` text COLLATE utf8_bin DEFAULT NULL,
  `key` varbinary(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD UNIQUE KEY `email` (`email`) USING HASH,
  ADD UNIQUE KEY `name` (`name`) USING HASH;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
