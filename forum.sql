-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Apr 2022 um 11:40
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `forum`
--
CREATE DATABASE IF NOT EXISTS `forum` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `forum`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entries`
--

CREATE TABLE `entries` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `topic` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `entries`
--

INSERT INTO `entries` (`ID`, `user_id`, `date`, `topic`, `content`) VALUES
(1, 1, '2022-03-31 14:34:25', 'Warum ist das Wort \"Abkürzung\" länger als \"Umweg\"??', 'Hallo Leutis, also ich hab jz mal so ne Fraaage… Warum ist das Wort \"Abkürzung\"\r\neig länger als \"Umweg\"??? Macht das überhaupt Sinn!!1!11!!!elf!!!!\r\nWäre echt schnieke, wenn das mal einer beantworten könnte.'),
(3, 3, '2022-03-31 14:35:09', 'Wie kommen die Rasen-betreten-verboten-Schilder auf den Rasen?', 'Bruda, wallah das safe illegal. Es kann doch nicht angehen, dass ich als normaler,\r\nfreier, DEUTSCHER Bürger nicht auf den dreckigen Rasen vom ollen Johannes darf,\r\nER SELBST ABER SCHON! ICH BIN EIN RUHIGER MENSCH, ABER DAS EKELT MICH AN!!!! Würd mich sehr freuen, wenn ihr die Frage beantworten könntet. Danke, vielen Dank, danke.'),
(4, 5, '2022-03-31 14:35:26', 'Sollte ich meinen Eltern sagen, dass ich adoptiert bin?', 'Ich, männlich, 23, bin seit etwa 23 Jahren potenzieller Mann. Das werdet ihr Ottos\r\njz safe nicht glauben, aber tatsächlich wurde ich adoptiert. Meine Eltern wissen das\r\nnoch nicht, aber ich glaube, in meinem Alter wird das jetzt mal langsam an der Zeit, meinen Eltern zu sagen, dass sie mich adoptiert haben. ODER??? Meine Eltern haben keinen sehr großen Intelligenzfaktor darum kp'),
(12, 7, '2022-04-09 09:26:39', 'Welche Lehne im Kino gehört mir?', 'Diese Frage ist relativ simpel, jedoch kann man sie nur beantworten, wenn man den Kontext kennt :)'),
(16, 4, '2022-04-21 08:44:43', 'WIE KANN ICH CAPSLOCK ABSTELLEN?', 'ICH BRAUCHE EURE HILFE LEUTE. ICH KANN CAPSLOCK EINFACH NICHT ABSTELLEN!!! MEINE SCHWESTER WAR AN MEINEM COMPUTER UND HAT FORTNITE GESPIELT UND JETZT IST ALLES WAS ICH SCHREIBE IN CAPS!'),
(17, 7, '2022-04-21 08:50:01', 'Wie kann ich testen, ob meine Schildkröte schwul ist?', 'Hallo! Ich habe mir letztens eine zweite (männliche) Schildkröte gekauft, aber ich habe das Gefühl, sie steht nicht so ganz auf die Hildegart (die erste Schildkröte), dabei ist die übels hot, ich schwör. Kann ich irgendwie testen, ob meine Schildkröte schwul ist?'),
(18, 2, '2022-04-21 09:09:02', 'Was passiert, wenn ich mich zweimal halb tot lache?', 'OMG, ich schwöre auf meinen linken Zeh am rechten Fuß, ich habe so Angst zu lachen!!! Ich habe mich gestern bei so einem Meme auf Facebook so halbtot gelacht (UND NEIN, ICH BIN KEIN BOOMER), jetzt habe ich Bedenken, dass ich krepieren werde, Hilfe...');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `replies`
--

CREATE TABLE `replies` (
  `entry_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd_hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`ID`, `username`, `email`, `pwd_hash`) VALUES
(1, 'bwl_anton32', 'bwl_anton32@waehltdiefdp.de', '$2y$10$RS3Du.9Gt73Qxi4LfjnXgOaF0wnTDNDpxenNdC4jhC5g/upTu0eB2'),
(2, 'ElSeñorFilli', 'elsenor@fillinger.es', '$2y$10$0lFdkiYMMUWAxJfbg4cWXuzOpXCBFapOj2vYTYguK6vj7OLAhrOCG'),
(3, 'jesusmaria', 'jesus.maria@wirhassenhandwerker.de', '$2y$10$SMxYTMciNhQklRgXAp6BpOzYcZh7PJfEYMG3qEsdpQ6mCdURa6o/e'),
(4, 'jOHANNES', 'johannes@unlustigerwitz.de', '$2y$10$nZK6vneYGTns9epLAu1fIerwuIyFJxRajHfmZ1pGdFOIuarPXyt.2'),
(5, 'Tomaten Marc', 'marc@tomate.de', '$2y$10$JE9TpROOr1CON5OIf6ynKuf4a9OgSC.Y6qY4rpz5IgUgKfgaMPbh2'),
(6, 'Tudor', 'tudorstuhl@mail.de', '$2y$10$Y/c4YzFZ9h4qDCEZZbAB8O0Mxwh7OAbZok.OEhoT9imsbMzuXW4dm'),
(7, 'Lenchen', 'mirwirdschlecht@wennichdichansehe.de', '$2y$10$l.QGRyRC/8bKRyPicZmsteh.unKFKMorf4pm9aUrQOj3jI0Tk1hjS');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `entries`
--
ALTER TABLE `entries`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
