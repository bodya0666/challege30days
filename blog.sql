-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Час створення: Січ 22 2019 р., 03:47
-- Версія сервера: 5.7.23
-- Версія PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `blog`
--

-- --------------------------------------------------------

--
-- Структура таблиці `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'ИТ'),
(2, 'Кино'),
(3, 'Разное'),
(4, 'Спорт');

-- --------------------------------------------------------

--
-- Структура таблиці `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `autor` varchar(64) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `postid` int(11) NOT NULL,
  `autorid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `comment`
--

INSERT INTO `comment` (`id`, `text`, `autor`, `date`, `postid`, `autorid`) VALUES
(1, 'Quisque ullamcorper tempor mi, quis fermentum arcu ornare in.', 'Bohdan', '2018-12-23 21:52:26', 19, 0),
(2, '1', 'Bohdan', '2018-12-23 21:58:40', 19, 0),
(3, '222', 'Bohdan', '2018-12-23 21:59:25', 19, 0),
(4, '11', 'Bohdan', '2018-12-23 21:59:36', 19, 0),
(5, 'Коментарий', 'Bohdan', '2018-12-24 15:22:13', 19, 0),
(10, '1234', 'Bohdan', '2018-12-24 15:26:31', 19, 0),
(11, '123', 'Bohdan', '2018-12-24 15:26:36', 19, 0),
(12, 'edwqe', 'Vasya', '2019-01-21 23:25:30', 24, 17);

-- --------------------------------------------------------

--
-- Структура таблиці `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `autor` varchar(64) NOT NULL,
  `date` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `text` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `hide` int(11) NOT NULL,
  `category` varchar(64) NOT NULL,
  `title` varchar(60) NOT NULL,
  `autorid` int(11) NOT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `post`
--

INSERT INTO `post` (`id`, `autor`, `date`, `text`, `image`, `hide`, `category`, `title`, `autorid`, `views`) VALUES
(5, 'Admin', '2018-12-20 20:37:43.443444', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-5.jpg', 0, 'Разное', 'Дэдпул', 0, 1),
(6, 'Admin', '2018-12-20 20:39:13.706350', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-6.jpg', 0, 'Спорт', 'title', 0, 0),
(7, 'Bohdan', '2018-12-20 20:41:54.739089', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-7.jpg', 0, 'Кино', 'Че Гевара: Дневники мотоциклиста', 0, 0),
(8, 'Admin', '2018-12-20 20:52:15.611439', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-8.jpg', 0, 'Кино', 'Дэдпул', 0, 1),
(9, 'Admin', '2018-12-20 20:52:43.705978', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-9.jpg', 0, 'ИТ', 'Дэдпул', 0, 1),
(10, 'Admin', '2018-12-20 20:53:24.846292', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-10.jpg', 0, 'IT', 'Дэдпул', 0, 5),
(11, 'Admin', '2018-12-20 21:04:04.560206', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu nisi at sapien sodales posuere. Aenean molestie, nibh vitae tempus commodo, quam felis venenatis velit, id placerat tortor risus vel magna. Fusce libero mi, tincidunt a augue id, interdum cursus nisi. Nunc massa sapien, commodo et molestie eu, malesuada et ipsum. Sed gravida tellus ligula, at congue eros sodales a. Integer facilisis vulputate ex eget semper. Mauris varius sed nibh non dapibus. Sed condimentum placerat placerat. Etiam dictum nibh eu sem iaculis, vitae vehicula tellus posuere. Morbi auctor laoreet nunc, eget mollis ante iaculis eu. In lorem erat, elementum nec.', 'images/postid-11.jpg', 0, 'IT', 'Lorem ipsum dolor sit amet,', 0, 1),
(13, 'Bohdan', '2018-12-23 19:52:01.353621', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tincidunt, velit eu cursus laoreet, nisl ante dictum enim, vitae blandit sem dolor at sapien. Etiam magna tellus, imperdiet in nisi finibus, venenatis viverra orci. Phasellus et dignissim arcu, aliquam ullamcorper risus. Quisque ullamcorper tempor mi, quis fermentum arcu ornare in.', 'images/postid-13.jpg', 0, 'ИТ', 'Проверка номер 2', 13, 1),
(19, 'Bohdan', '2018-12-23 19:54:18.216679', 'Lorem ipsum dolor In tincidunt, velit eu cursus laoreet, nisl ante dictum enim, vitae blandit sem dolor at sapien. Etiam magna tellus, imperdiet in nisi finibus, venenatis viverra orci. Phasellus et dignissim arcu, aliquam ullamcorper risus. Quisque ullamcorper tempor mi, quis fermentum arcu ornare ion.', 'images/postid-19.jpg', 0, 'Кино', 'Проверка номер 2', 13, 31),
(20, 'Bohdan', '2018-12-25 01:53:40.107048', 'editLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus elementum, odio ut feugiat malesuada, orci turpis bibendum metus, sit amet pellentesque libero ipsum tempus sapien. Etiam luctus lacinia est dapibus dignissim. Fusce pellentesque euismod est. Quisque ut felis nibh. Mauris.', 'images/postid-20.jpg', 0, 'Кино', 'editТестовое название', 13, 22),
(22, 'test', '2019-01-21 22:27:54.223409', 'dsadasda\r\ndsad\r\ndsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsaddsadasda\r\ndsad', 'images/postid-22.jpeg', 0, 'ИТ', 'Че Гевара: Дневники мотоциклиста', 14, 2),
(24, 'test3231', '2019-01-21 23:00:20.908516', 'Тестовое название Тестовое название Тестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое названиеТестовое название', 'images/postid-24.jpg', 0, 'ИТ', 'Тестовое название123', 17, 19),
(25, 'Vasya', '2019-01-21 23:27:51.154361', 'title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title title', 'images/postid-25.jpg', 0, 'ИТ', 'title', 17, 2);

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `usergroup` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `email`, `usergroup`) VALUES
(12, 'Bohdan', '$2y$10$gc32oXlvHdJaZit7SOyje.Cgc696rehXcghwiorjBH7jEWptq6Tem', 'gd@g.g', 0),
(13, 'Bohdan', '$2y$10$Hwr2w0lYr8R5fr2ExldUB.7FCq0x/nHlJOaHRu3DPV9Ej5dxRftLK', 'tt@q.t', 0),
(14, 'test', '$2y$10$eaIa3GKLil4o17XC.p.bceZvyyvfXeu6QuFW7jzNlUaUsVK1utCWa', 'ewq@q.t', 0),
(15, 'test3231', '$2y$10$bOHSCnGflo6TwD5c9lVRtOV4fjjzocJlr/btl8xsF927ydjUuwojO', 'test@gg.wp', 0),
(16, 'testsdasd', '$2y$10$8AQ/8LoFwBjgVecBLXTfs.b9hlHLdXk2ZlJ5VJAmX3GUvz/tsNXqm', 'w1943738e@gmail.com', 0),
(17, 'Vasya', '$2y$10$ItK7dBl4rWLc4bROru8Y/eZ/n6OUTzmEN.arPYQj41INrhxX6ifXm', 'fd@f.f', 0);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблиці `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблиці `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблиці `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
