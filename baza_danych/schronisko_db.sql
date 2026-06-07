-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 07, 2026 at 01:55 PM
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
(10, '6a231bebbb213_szlak2.jpg', 'Zdjęcie szlaku', 'okolica'),
(14, '6a2420424f121_zimowykamien.jpg', 'zima', 'schronisko');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_text` varchar(115) NOT NULL,
  `full_text` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `short_text`, `full_text`, `image`, `created_at`) VALUES
(2, 'Weekend z gwiazdami', '15 lutego zapraszamy na nocną obserwację gwiazd z teleskopem. Liczba miejsc ograniczona...', '15 lutego zapraszamy na nocną obserwację gwiazd z profesjonalnym teleskopem. W programie: obserwacja planet, gwiazd podwójnych, mgławic i galaktyk. Prowadzenie: licencjonowany przewodnik astronomiczny. Początek o godzinie 20:00. W cenie: ciepła herbata i poczęstunek. Liczba miejsc ograniczona do 15 osób - rezerwuj już dziś! Warunkiem uczestnictwa jest bezchmurna pogoda.', 'gwiazdy.jpg', '2026-06-05 21:16:27'),
(3, 'Zimowy Wodospad Kamieńczyk', 'Odkryj Wodospad Kamieńczyk w zimowej odsłonie. Lodowe sople i zamarznięta kaskada...', 'Odkryj Wodospad Kamieńczyk w zimowej odsłonie! Najwyższy wodospad polskich Karkonoszy (27 metrów) zimą zamienia się w bajkową krainę lodu. Gigantyczne sople, zamarznięte kaskady i lodowa grota tworzą niezapomniany widok. Trasa prowadzi przez ośnieżony las bukowy - jak z zimowej baśni. Odległość ze schroniska: 2 km, czas spaceru: 45 minut. UWAGA: zimą przy wodospadzie może być bardzo ślisko - koniecznie zabierz raki lub kijki trekkingowe. Najpiękniej po mocnych mrozach w styczniu i lutym!', 'zimowykamienczyk.jpg', '2026-06-05 21:16:27'),
(4, 'Nowe dania w karcie', 'Rozszerzyliśmy menu o tradycyjne potrawy regionalne. Spróbuj naszych domowych specjałów', 'Rozszerzyliśmy menu o tradycyjne potrawy regionalne. Spróbuj naszych domowych pierogów, żurku i placków ziemniaczanych przygotowanych według starych, rodzinnych receptur. Wszystkie dania gotujemy ze świeżych produktów od lokalnych gospodarzy. W ofercie również wegańskie i wegetariańskie opcje. Szczególnie polecamy nasze domowe ciasta - wypiekane codziennie rano!', '6a25513dd7bab_dania.webp', '2026-06-07 11:08:45'),
(5, 'Zimowa promocja - 20% taniej!', 'W lutym oferujemy specjalne ceny na noclegi. Idealna okazja na zimowy wypoczynek...', 'W lutym oferujemy specjalne ceny na noclegi. Idealna okazja na zimowy wypoczynek w górach. Zarezerwuj pobyt minimum na 3 noce, a otrzymasz 20% rabatu. W cenie: śniadanie, dostęp do sauny i wypożyczalnia sprzętu narciarskiego. Promocja trwa do końca lutego! Nie przegap tej okazji - miejsca ograniczone.', '6a2554d95b81e_zimowykamien.jpg', '2026-06-07 11:24:09');

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
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
