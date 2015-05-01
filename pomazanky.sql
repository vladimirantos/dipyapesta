-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pát 01. kvě 2015, 11:20
-- Verze serveru: 5.6.21
-- Verze PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `pomazanky`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
`id_article` int(11) NOT NULL,
  `language` varchar(2) COLLATE utf8_czech_ci NOT NULL,
  `title` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `articles`
--

INSERT INTO `articles` (`id_article`, `language`, `title`, `content`, `date`) VALUES
(1, 'cs', 'a', 'a', '2015-05-01 07:30:38'),
(2, 'en', 'aaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaa', '2015-05-01 07:15:20'),
(3, 'de', 'xy', 'xya', '2015-05-01 07:42:30'),
(4, 'en', 'a', 'a', '2015-05-01 07:31:00'),
(5, 'cs', 'xx', 'xx', '2015-05-01 09:00:40'),
(6, 'en', 'eee', 'eeeaw', '2015-05-01 09:04:28');

-- --------------------------------------------------------

--
-- Struktura tabulky `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
`id_ingredience` int(11) NOT NULL,
  `language` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `recipes` int(11) NOT NULL,
  `ingredience` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(10) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `products`
--

CREATE TABLE IF NOT EXISTS `products` (
`id_product` int(11) NOT NULL,
  `title` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `language` varchar(2) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `short_description` text COLLATE utf8_czech_ci NOT NULL,
  `preparation` text COLLATE utf8_czech_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `main_image` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `gallery` varchar(30) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `products`
--

INSERT INTO `products` (`id_product`, `title`, `language`, `description`, `short_description`, `preparation`, `date`, `main_image`, `gallery`) VALUES
(1, 'Pokus', 'de', 'ahoj', 'ahojkyaaaawwwe', 'ahoj\n', '2015-05-01 08:01:47', '', ''),
(1, 'pokusde', 'en', 'pokus', 'pokud', 'oiwe', '2015-05-01 08:02:07', '', '');

-- --------------------------------------------------------

--
-- Struktura tabulky `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
`id_recipe` int(11) NOT NULL,
  `language` varchar(2) COLLATE utf8_czech_ci NOT NULL,
  `title` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `image` varchar(30) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id_user` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id_user`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@dipyapesta.cz', '$2y$10$YMfmMZ2zRCTibaGJzBTDA.kwCEK/gZE4sv.l85Q1JSvOrd55NvvD.');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `articles`
--
ALTER TABLE `articles`
 ADD PRIMARY KEY (`id_article`,`language`);

--
-- Klíče pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
 ADD PRIMARY KEY (`id_ingredience`,`language`), ADD KEY `fk_recipes` (`recipes`);

--
-- Klíče pro tabulku `products`
--
ALTER TABLE `products`
 ADD PRIMARY KEY (`id_product`,`language`);

--
-- Klíče pro tabulku `recipes`
--
ALTER TABLE `recipes`
 ADD PRIMARY KEY (`id_recipe`,`language`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id_user`), ADD UNIQUE KEY `uk_user` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `articles`
--
ALTER TABLE `articles`
MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
MODIFY `id_ingredience` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `products`
--
ALTER TABLE `products`
MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pro tabulku `recipes`
--
ALTER TABLE `recipes`
MODIFY `id_recipe` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
ADD CONSTRAINT `fk_recipes` FOREIGN KEY (`recipes`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
