-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 21. dub 2015, 20:25
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
  `title` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5912 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
`id_ingredient` int(11) NOT NULL,
  `recipes` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `ingredient` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `quantity` varchar(11) COLLATE utf8_czech_ci NOT NULL,
  `unit` varchar(10) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `title` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `short_description` text COLLATE utf8_czech_ci NOT NULL,
  `preparation` text COLLATE utf8_czech_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `main_image` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `gallery` varchar(30) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `title` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `image` varchar(30) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id_user` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `articles`
--
ALTER TABLE `articles`
 ADD PRIMARY KEY (`id_article`), ADD UNIQUE KEY `uk_title` (`title`);

--
-- Klíče pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
 ADD PRIMARY KEY (`id_ingredient`), ADD UNIQUE KEY `uk_recipes_ingredience` (`recipes`,`ingredient`);

--
-- Klíče pro tabulku `products`
--
ALTER TABLE `products`
 ADD PRIMARY KEY (`title`);

--
-- Klíče pro tabulku `recipes`
--
ALTER TABLE `recipes`
 ADD PRIMARY KEY (`title`);

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
MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5912;
--
-- AUTO_INCREMENT pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
MODIFY `id_ingredient` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
