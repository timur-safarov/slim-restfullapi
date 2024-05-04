SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Структура таблицы `loans`
--

CREATE TABLE `loans` (
  `id` int UNSIGNED NOT NULL COMMENT 'id Заёма',
  `fio` varchar(150) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ФИО заёмщика',
  `sum` float UNSIGNED NOT NULL COMMENT 'Сумма',
  `created_at` int UNSIGNED NOT NULL COMMENT 'Дата заёма'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `api_key_hash` varchar(64) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password_hash`, `api_key`, `api_key_hash`) VALUES
(1, 'admin', 'admin@email.ru', '$2y$10$twAlfFjopHCEPMUuhtjdV.K/Lrgwa4ZZ3vBVpLKqXDvvnpHZukVI.', 'def50200fb5f8f5e33c6c0c41e3ac19143bf31bfb8cb8d4449ae1e231b4908c43e94c8cbfb11cb842361b9f3730c30bff6515336f070f70144e55f12a0881a7a3866d3aff8ae75ccd1711f8c50d01df59becfc31dc2b95df52d1ddbe1d53e7f825ea3b80978f4220807e9a8fb46748f9988afdcc', '0e264d5c16fca74d75638cf4d9ed754c644a47b698a13aa530edb663260b91b4');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `api_key_hash` (`api_key_hash`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id Заёма';

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

