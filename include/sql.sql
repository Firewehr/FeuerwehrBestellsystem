-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: mysqlsvr11.world4you.com
-- Erstellungszeit: 31. Jul 2017 um 07:31
-- Server-Version: 5.1.73
-- PHP-Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `ff_manhartsbrunnatdb2`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `beilagen`
--

CREATE TABLE `beilagen` (
  `rowid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `betrag` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellungen`
--

CREATE TABLE `bestellungen` (
  `rowid` int(11) NOT NULL,
  `zeitstempel` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `position` int(11) NOT NULL,
  `ausgeliefert` int(11) NOT NULL,
  `tischnummer` int(11) NOT NULL,
  `kellner` text CHARACTER SET latin1 NOT NULL,
  `bestellung` int(11) NOT NULL,
  `kueche` int(11) NOT NULL,
  `delete` int(11) NOT NULL,
  `zeitKueche` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `print` tinyint(1) NOT NULL,
  `timestampBestellung` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestampAuslieferung` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestampBezahlung` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `kellnerZahlung` text CHARACTER SET utf8 NOT NULL,
  `ZusatzInfo` text COLLATE latin1_german1_ci NOT NULL,
  `betrag` double NOT NULL,
  `bestellt` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `positionen`
--

CREATE TABLE `positionen` (
  `rowid` int(11) NOT NULL,
  `Positionsname` text CHARACTER SET latin1 NOT NULL,
  `Betrag` decimal(10,2) NOT NULL,
  `type` int(2) NOT NULL,
  `Kurzbezeichnung` varchar(30) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `maxBestellbar` int(11) NOT NULL DEFAULT '-1',
  `reihenfolge` int(11) NOT NULL,
  `color` varchar(8) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `icon` varchar(30) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `print`
--

CREATE TABLE `print` (
  `rowid` int(11) NOT NULL,
  `bestellungID` int(11) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tische`
--

CREATE TABLE `tische` (
  `tischnummer` int(11) NOT NULL,
  `tischname` varchar(7) NOT NULL,
  `zeitstempel` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `color` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE `type` (
  `rowid` int(2) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `beilagen`
--
ALTER TABLE `beilagen`
  ADD PRIMARY KEY (`rowid`);

--
-- Indizes für die Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD PRIMARY KEY (`rowid`),
  ADD UNIQUE KEY `rowid` (`rowid`),
  ADD KEY `tischnummer` (`tischnummer`),
  ADD KEY `position` (`position`);

--
-- Indizes für die Tabelle `positionen`
--
ALTER TABLE `positionen`
  ADD PRIMARY KEY (`rowid`),
  ADD UNIQUE KEY `rowid` (`rowid`),
  ADD KEY `type` (`type`);

--
-- Indizes für die Tabelle `print`
--
ALTER TABLE `print`
  ADD PRIMARY KEY (`rowid`);

--
-- Indizes für die Tabelle `tische`
--
ALTER TABLE `tische`
  ADD PRIMARY KEY (`tischnummer`),
  ADD UNIQUE KEY `tischnummer` (`tischnummer`),
  ADD KEY `tischnummer_2` (`tischnummer`);

--
-- Indizes für die Tabelle `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`rowid`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `beilagen`
--
ALTER TABLE `beilagen`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT für Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT für Tabelle `positionen`
--
ALTER TABLE `positionen`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
--
-- AUTO_INCREMENT für Tabelle `print`
--
ALTER TABLE `print`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT für Tabelle `tische`
--
ALTER TABLE `tische`
  MODIFY `tischnummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100012;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;COMMIT;


INSERT INTO `users` (`id`, `username`, `password`, `timestamp`, `admin`) VALUES
(1, 'admin', '$2a$10$w8aXzAADK9NvQN2FRGQ.9.TLwZChoT4psKE4OAApg5XDIIJz2kSGS', '2015-09-03 15:15:07', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
