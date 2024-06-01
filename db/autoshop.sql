-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 17 2023 г., 09:57
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `autoshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cards`
--

CREATE TABLE `cards` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cards`
--

INSERT INTO `cards` (`id`, `name`, `description`, `price`, `image`, `qty`, `category_id`) VALUES
(1, 'Автомобиль 1', 'LADA Vesta — это седан от российского производителя АвтоВАЗ, представленный в 2015 году, который сочетает в себе современный дизайн и доступную цену. По сравнению с предыдущими моделями LADA, Vesta предлагает улучшенный интерьер, больший выбор опций и лучшее качество сборки.\r\n\r\n\r\n\r\n\r\n', 1200000, '1.jpg', 3, 1),
(2, 'Автомобиль 2', 'LADA Vesta — это седан от российского производителя АвтоВАЗ, представленный в 2015 году, который сочетает в себе современный дизайн и доступную цену. По сравнению с предыдущими моделями LADA, Vesta предлагает улучшенный интерьер, больший выбор опций и лучшее качество сборки.\r\n\r\n\r\n\r\n\r\n', 1500000, '2.jpg', 2, 1),
(3, 'Мотоцикл 1', 'Indian Scout — это культовый круизер от американского производителя Indian Motorcycle, сочетающий в себе классический дизайн с современной техникой. Отличается высококачественной отделкой, мощным двигателем и комфортной посадкой для длительных поездок.\r\n\r\n\r\n\r\n\r\n', 900000, '3.jpg', 3, 2),
(4, 'Мотоцикл 2', 'Indian Scout — это культовый круизер от американского производителя Indian Motorcycle, сочетающий в себе классический дизайн с современной техникой. Отличается высококачественной отделкой, мощным двигателем и комфортной посадкой для длительных поездок.\r\n\r\n\r\n\r\n\r\n', 600000, '4.jpg', 2, 2),
(5, 'Автомобиль 3', 'LADA Vesta — это седан от российского производителя АвтоВАЗ, представленный в 2015 году, который сочетает в себе современный дизайн и доступную цену. По сравнению с предыдущими моделями LADA, Vesta предлагает улучшенный интерьер, больший выбор опций и лучшее качество сборки.\r\n\r\n\r\n\r\n\r\n', 2000000, '5.jpg', 9, 1),
(6, 'Автомобиль 4', 'LADA Vesta — это седан от российского производителя АвтоВАЗ, представленный в 2015 году, который сочетает в себе современный дизайн и доступную цену. По сравнению с предыдущими моделями LADA, Vesta предлагает улучшенный интерьер, больший выбор опций и лучшее качество сборки.\r\n\r\n\r\n\r\n\r\n', 2100000, '6.jpg', 2, 1),
(18, 'Автомобиль 5', 'LADA Vesta — это седан от российского производителя АвтоВАЗ, представленный в 2015 году, который сочетает в себе современный дизайн и доступную цену. По сравнению с предыдущими моделями LADA, Vesta предлагает улучшенный интерьер, больший выбор опций и лучшее качество сборки.', 13000000, 'photo_2023-06-08_16.53-pic_32ratio_1200x800-1200x800-92360.jpg', 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `cart_qty` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `title`) VALUES
(1, 'Автомобиль'),
(2, 'Мотоцикл'),
(3, 'Премиум');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` varchar(10) NOT NULL,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `order_date`) VALUES
('#GTFK74', 21, '1500000.00', '2023-10-16 16:21:38');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `order_id` varchar(10) NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `total_price`) VALUES
(79, '#GTFK74', 2, 1, '1500000.00');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `status_id`) VALUES
(1, 'уйцуцй', '$2y$10$ZcKCMMMxhDmdrvGQPky5Neqm7VgFJ8ubRgWGDl2Cq2VMGtZCXP1Zq', '', 1),
(2, 'eqwewqeqw', '$2y$10$OqEDTEGNC6j0ol/GB.8p6uVeAqm96dAMmkw1c7n3fLXrsx.3fop0W', '', 1),
(3, '123', '$2y$10$MDgStfJvF7Y6u.dV5cAKSubzrrxErH6E77FkIB0z8VvlHFD0gDYOy', '', 1),
(4, 'йцу', '$2y$10$Z60NXAa7m9za9eDtDHc9h.EecMp6ZTFWVOqdlfK84gW.5z0bZRxtG', '', 2),
(5, '1234', '$2y$10$jq5yPYquDwI08VG3LS5Xx.hpMAD0H2yE82arqaiu98UZwRQlOGIDi', '', 1),
(6, 'qwe', '$2y$10$yp7YR95izJZJjTpLQp2A0O3XngkX/g0/lA4TM66Z/mcmv3vXgAcky', '', 1),
(7, '12345', '$2y$10$29JqXScWEhqlQtA79eAc2e97aKLjcck1CT2KM1817GfP.jy3tn0Ni', '', 1),
(8, '1', '$2y$10$ABbazfbq.TeBEP6DbL1bAOl5Ys7joaQGjgrioYk4RKGTUVJj22otW', '', 1),
(9, '3213121212412', '$2y$10$KqbPqqQDJUq4s1iC1VBUe.8vAdL7ayk8uj9oQdX6nnGitVngP5JJ2', '', 1),
(10, 'mur', '$2y$10$HJLKHLrM8sFVOJ173oKRfeIsxRGi8g0ZZZYZWqB3nqaajj4h.HxOm', '', 1),
(11, '222', '$2y$10$o3e3zImIWMhBqOAl/7ihQ.wPak5LunMeiVNFt0WkYPVzjPf9pZfue', '', 1),
(12, '13', '$2y$10$.9Z..puRoRMjf/w0tBqfmuO8YzlEQBzd7SjOYW.YxhKdmrXFgm0WS', '', 2),
(13, '4', '$2y$10$PgQuxqBrKBlmFspjGBRU7elmYHZGIzeDxOM15dz5p1RsBCyoJETwK', '', 2),
(14, 'yalubluvlada', '$2y$10$FCVbccbGstAu0aHEBg9K/erwnuLYMyXfnvg1ekzuJ4f4S2O8Ub85a', '', 2),
(15, '333', '$2y$10$vbrvHDEQ.aIsUzfxDghLvOny7WOw/0BA6fS2KCZPIbwAIDK4M1acO', '', 2),
(16, '233223', '$2y$10$ALy5Om48ikUuseAIaME2QuoAjDCNzQkyYrIcjvyXFs5Tq195IJDRm', '', 2),
(17, 'admin', '$2y$10$G/Oszfi8x4e0B43J29sGc.TD/d0jIkFPgzx3HxjQrMFybRue4cCEq', '', 1),
(18, 'rafael', '$2y$10$F7De3UbjIdF8ISxc5KgBRuy6seoLzZ.hFdzp6YuTyeCxXT1yQwJmS', 'rafael@gmail.com', 2),
(19, 'vladislav', '$2y$10$6xCtzN/KuXmlarKQVndEU.ZKGvhEXVpbvXeZr3I64TZx5Sps0Jera', 'vladislavpolyarnyj@gmail.com', 2),
(20, 'цйцйцй', '$2y$10$kU0PJ5.FzPy3Z2lsce9V8OqFbgGqswUBtnolEyFgxz/k2uHqJ0.kC', 'eqw@ewqeq.ewq', 2),
(21, 'shailushay', '$2y$10$Zc4.KMcvTOnGUoAC9BPD8.AWsg8ydmw8U8zvRkQOn6O6VOOkdIltG', 'alina.sidorovaalina@yandex.ru', 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cards_ibfk_1` (`category_id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_items_ibfk_1` (`order_id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `status_id` (`status_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `cards` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
