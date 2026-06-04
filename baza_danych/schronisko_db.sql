-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 04, 2026 at 11:46 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schronisko_db`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` char(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `pass`) VALUES
(1, 'admin@example.com', '$2y$10$N0xy3BqyCR0Nu9tWexnnhOba3oO3dMtZ.mShddXyGqXLd0WDhqoXu');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `name`, `alt`, `category`) VALUES
(1, 'nocleg.png', 'zdjęcie noclegu', 'schronisko'),
(2, 'schronisko_zewn.jpg', 'zdjęcie schroniska', 'schronisko'),
(3, 'stolowka.png', 'zdjęcie stołówki', 'schronisko'),
(4, 'szalas.jpg', 'szałas', 'szalas'),
(5, 'szalas_grill.png', 'grill w szałasie', 'szalas'),
(6, 'szalas_wnetrze.png', 'wnętrze szałasu', 'szalas'),
(7, 'szlak.png', 'zdjęcie szlaku', 'okolica'),
(8, 'wodospad_zdj.png', 'wodospad', 'okolica'),
(9, 'szlak2.jpg', 'zdjęcie szlaku', 'okolica');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
