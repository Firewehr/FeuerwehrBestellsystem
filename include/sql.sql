
SQL Structure:
-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



CREATE TABLE IF NOT EXISTS `beilagen` (
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

CREATE TABLE IF NOT EXISTS `bestellungen` (
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
  `betrag` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `positionen`
--

CREATE TABLE IF NOT EXISTS `positionen` (
  `rowid` int(11) NOT NULL,
  `Positionsname` text CHARACTER SET latin1 NOT NULL,
  `Betrag` decimal(10,2) NOT NULL,
  `type` int(2) NOT NULL,
  `Kurzbezeichnung` varchar(30) COLLATE latin1_german1_ci NOT NULL,
  `maxBestellbar` int(11) NOT NULL DEFAULT '-1',
  `reihenfolge` int(11) NOT NULL,
  `color` varchar(6) COLLATE latin1_german1_ci NOT NULL,
  `icon` varchar(30) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tabelle`
--

CREATE TABLE IF NOT EXISTS `tabelle` (
  `row_id` int(6) NOT NULL,
  `temp` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` int(4) NOT NULL,
  `device` varchar(4) NOT NULL,
  `min` int(4) NOT NULL,
  `max` int(4) NOT NULL,
  `licht` int(11) NOT NULL,
  `minlicht` int(11) NOT NULL,
  `maxlicht` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tische`
--

CREATE TABLE IF NOT EXISTS `tische` (
  `rowid` int(11) NOT NULL,
  `tischnummer` int(11) NOT NULL,
  `tischname` varchar(7) NOT NULL,
  `zeitstempel` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kellenr` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `rowid` int(2) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `rowid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `passwordhash` varchar(60) NOT NULL,
  `unused` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
-- Indizes für die Tabelle `tabelle`
--
ALTER TABLE `tabelle`
  ADD PRIMARY KEY (`row_id`);

--
-- Indizes für die Tabelle `tische`
--
ALTER TABLE `tische`
  ADD PRIMARY KEY (`rowid`),
  ADD UNIQUE KEY `tischname` (`tischname`),
  ADD KEY `tischnummer` (`tischnummer`);

--
-- Indizes für die Tabelle `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`rowid`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`rowid`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `positionen`
--
ALTER TABLE `positionen`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tabelle`
--
ALTER TABLE `tabelle`
  MODIFY `row_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tische`
--
ALTER TABLE `tische`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
