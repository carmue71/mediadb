-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 05. Mai 2018 um 19:11
-- Server-Version: 10.2.13-MariaDB
-- PHP-Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `MediaDB`
--
CREATE DATABASE IF NOT EXISTS `MediaDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `MediaDB`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Comment`
--

CREATE TABLE `Comment` (
  `ID_Comment` int(11) NOT NULL,
  `REF_Comment` int(11) DEFAULT NULL COMMENT 'The Parent Comment',
  `REF_User` int(11) NOT NULL,
  `REF_MediaSet` int(11) NOT NULL,
  `Title` varchar(250) NOT NULL,
  `Body` text DEFAULT NULL,
  `LastChange` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `Comment`
--
DELIMITER $$
CREATE TRIGGER `UpdateComment` BEFORE UPDATE ON `Comment` FOR EACH ROW SET NEW.LastChange = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `C_Model_MediaSet`
--

CREATE TABLE `C_Model_MediaSet` (
  `REF_Model` int(11) NOT NULL,
  `REF_MediaSet` int(11) NOT NULL,
  `Comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Device`
--

CREATE TABLE `Device` (
  `ID_Device` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Path` varchar(250) NOT NULL,
  `DisplayPath` varchar(250) DEFAULT NULL COMMENT 'Path used to display the file',
  `Removable` tinyint(1) NOT NULL DEFAULT 0,
  `Network` tinyint(1) NOT NULL DEFAULT 0,
  `Comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `File`
--

CREATE TABLE `File` (
  `ID_File` bigint(11) NOT NULL,
  `Name` varchar(250) NOT NULL COMMENT 'Filename (without path)',
  `Path` varchar(250) NOT NULL COMMENT 'Relative path to the file',
  `Availlable` tinyint(1) DEFAULT 1,
  `REF_Device` int(11) NOT NULL,
  `REF_MediaSet` int(11) NOT NULL,
  `REF_Filetype` int(11) NOT NULL COMMENT 'Type of the file',
  `Title` varchar(250) DEFAULT NULL,
  `Keywords` text DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `Rating` int(11) NOT NULL DEFAULT 0,
  `FileInfo` text DEFAULT NULL,
  `Size` bigint(20) DEFAULT NULL COMMENT 'Size of teh fiel in Byte',
  `ResX` int(11) DEFAULT NULL COMMENT 'Witdth',
  `ResY` int(11) DEFAULT NULL COMMENT 'Height',
  `Created` timestamp NULL DEFAULT NULL COMMENT 'Creation Date of the file',
  `Modified` timestamp NULL DEFAULT NULL COMMENT 'Last Modification Date',
  `Playtime` int(11) DEFAULT 0 COMMENT 'Movie: Playtime in seconds, others 0',
  `ViewedPercent` double DEFAULT 0 COMMENT 'Viewed Percent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `FileType`
--

CREATE TABLE `FileType` (
  `ID_FileType` int(11) NOT NULL,
  `Name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `MediaSet`
--

CREATE TABLE `MediaSet` (
  `ID_MediaSet` int(11) NOT NULL,
  `Title` varchar(250) NOT NULL,
  `Description` text DEFAULT NULL,
  `Keywords` text DEFAULT NULL COMMENT 'Keywords, separated by ,',
  `Published` smallint(6) DEFAULT NULL COMMENT 'Year of publication',
  `REF_Studio` int(11) DEFAULT NULL COMMENT 'Publisher',
  `PublisherCode` varchar(80) DEFAULT NULL,
  `Link` varchar(250) DEFAULT NULL,
  `Picture` varchar(250) DEFAULT NULL COMMENT 'File with title picture - relative to titles directory',
  `Wallpaper` varchar(250) DEFAULT NULL COMMENT 'File with wallpaper - relative to wallpaper directory',
  `Comment` text DEFAULT NULL,
  `Rating` tinyint(4) DEFAULT NULL,
  `Viewed` tinyint(4) NOT NULL DEFAULT 0,
  `Added` timestamp NOT NULL DEFAULT current_timestamp(),
  `Modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `MediaSet`
--
DELIMITER $$
CREATE TRIGGER `UpdateModificationTime` BEFORE UPDATE ON `MediaSet` FOR EACH ROW SET NEW.Modified = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Model`
--

CREATE TABLE `Model` (
  `ID_Model` int(11) NOT NULL COMMENT 'primary key, autoinc',
  `Fullname` varchar(80) NOT NULL COMMENT 'Usual, unique name for the model.',
  `Aliases` varchar(120) DEFAULT NULL COMMENT 'Further names for the model',
  `Gender` char(1) DEFAULT 'F' COMMENT 'Gender of the model: F,M,T,?',
  `Description` text DEFAULT NULL COMMENT 'Further attributes of the model',
  `Keywords` varchar(120) DEFAULT NULL,
  `CupSize` char(3) DEFAULT NULL,
  `Mugshot` varchar(255) DEFAULT NULL COMMENT 'Path to the mugshot - relative to mugshot directory',
  `Thumbnail` varchar(250) DEFAULT NULL,
  `Wallpaper` varchar(255) DEFAULT NULL COMMENT 'Path to the Background Image - relative to wallpaper directory',
  `Twitter` varchar(250) DEFAULT NULL COMMENT 'Twitter-Account of the model',
  `Website` varchar(250) DEFAULT NULL COMMENT 'Website of the model, furhter can be added in the comments',
  `Sites` text DEFAULT NULL COMMENT 'Further sites with information about this model'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Studio`
--

CREATE TABLE `Studio` (
  `ID_Studio` int(11) NOT NULL,
  `Name` varchar(120) NOT NULL,
  `Site` varchar(250) DEFAULT NULL,
  `DefaultSetPath` varchar(250) DEFAULT NULL COMMENT 'Default Path to a set from this site',
  `Logo` varchar(250) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `Wallpaper` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE `User` (
  `ID_User` int(11) NOT NULL,
  `Login` varchar(64) COLLATE utf8_german2_ci NOT NULL,
  `Password` varchar(64) COLLATE utf8_german2_ci NOT NULL,
  `Name` varchar(128) COLLATE utf8_german2_ci NOT NULL,
  `EMail` varchar(255) COLLATE utf8_german2_ci NOT NULL,
  `Role` varchar(10) COLLATE utf8_german2_ci NOT NULL,
  `Avatar` varchar(128) COLLATE utf8_german2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci COMMENT='List of known Users';

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `V_FileWithDevice`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `V_FileWithDevice` (
`ID_File` bigint(11)
,`Name` varchar(250)
,`Path` varchar(250)
,`Title` varchar(250)
,`Comment` text
,`Keywords` text
,`Rating` int(11)
,`Device` varchar(128)
,`DevicePath` varchar(250)
,`SystemPath` varchar(250)
,`REF_MediaSet` int(11)
,`REF_Filetype` int(11)
,`FileInfo` text
,`Size` bigint(20)
,`ResX` int(11)
,`ResY` int(11)
,`Created` timestamp
,`Modified` timestamp
,`Playtime` int(11)
,`REF_Device` int(11)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `V_MediaSetWithStudio`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `V_MediaSetWithStudio` (
`ID_MediaSet` int(11)
,`Title` varchar(250)
,`Description` text
,`Keywords` text
,`Published` smallint(6)
,`REF_Studio` int(11)
,`PublisherCode` varchar(80)
,`Link` varchar(250)
,`Picture` varchar(250)
,`Wallpaper` varchar(250)
,`Comment` text
,`Rating` tinyint(4)
,`Viewed` tinyint(4)
,`Added` timestamp
,`Modified` timestamp
,`Studio` varchar(120)
,`Logo` varchar(250)
);

-- --------------------------------------------------------

--
-- Struktur des Views `V_FileWithDevice`
--
DROP TABLE IF EXISTS `V_FileWithDevice`;

CREATE ALGORITHM=UNDEFINED DEFINER=`torsten`@`%` SQL SECURITY DEFINER VIEW `V_FileWithDevice`  AS  select `F`.`ID_File` AS `ID_File`,`F`.`Name` AS `Name`,`F`.`Path` AS `Path`,`F`.`Title` AS `Title`,`F`.`Comment` AS `Comment`,`F`.`Keywords` AS `Keywords`,`F`.`Rating` AS `Rating`,`D`.`Name` AS `Device`,`D`.`DisplayPath` AS `DevicePath`,`D`.`Path` AS `SystemPath`,`F`.`REF_MediaSet` AS `REF_MediaSet`,`F`.`REF_Filetype` AS `REF_Filetype`,`F`.`FileInfo` AS `FileInfo`,`F`.`Size` AS `Size`,`F`.`ResX` AS `ResX`,`F`.`ResY` AS `ResY`,`F`.`Created` AS `Created`,`F`.`Modified` AS `Modified`,`F`.`Playtime` AS `Playtime`,`F`.`REF_Device` AS `REF_Device` from (`File` `F` left join `Device` `D` on(`F`.`REF_Device` = `D`.`ID_Device`)) ;

-- --------------------------------------------------------

--
-- Struktur des Views `V_MediaSetWithStudio`
--
DROP TABLE IF EXISTS `V_MediaSetWithStudio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`karl`@`%` SQL SECURITY DEFINER VIEW `V_MediaSetWithStudio`  AS  select `M`.`ID_MediaSet` AS `ID_MediaSet`,`M`.`Title` AS `Title`,`M`.`Description` AS `Description`,`M`.`Keywords` AS `Keywords`,`M`.`Published` AS `Published`,`M`.`REF_Studio` AS `REF_Studio`,`M`.`PublisherCode` AS `PublisherCode`,`M`.`Link` AS `Link`,`M`.`Picture` AS `Picture`,`M`.`Wallpaper` AS `Wallpaper`,`M`.`Comment` AS `Comment`,`M`.`Rating` AS `Rating`,`M`.`Viewed` AS `Viewed`,`M`.`Added` AS `Added`,`M`.`Modified` AS `Modified`,`S`.`Name` AS `Studio`,`S`.`Logo` AS `Logo` from (`MediaSet` `M` left join `Studio` `S` on(`M`.`REF_Studio` = `S`.`ID_Studio`)) ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`ID_Comment`),
  ADD KEY `LinkToMediaSet` (`REF_MediaSet`),
  ADD KEY `LinkToParent` (`REF_Comment`),
  ADD KEY `LinkToUser` (`REF_User`);

--
-- Indizes für die Tabelle `C_Model_MediaSet`
--
ALTER TABLE `C_Model_MediaSet`
  ADD PRIMARY KEY (`REF_Model`,`REF_MediaSet`),
  ADD KEY `RefModelMediaSet2` (`REF_MediaSet`);

--
-- Indizes für die Tabelle `Device`
--
ALTER TABLE `Device`
  ADD PRIMARY KEY (`ID_Device`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indizes für die Tabelle `File`
--
ALTER TABLE `File`
  ADD PRIMARY KEY (`ID_File`),
  ADD KEY `REF_Device` (`REF_Device`),
  ADD KEY `IDX_REV_MediaSet` (`REF_MediaSet`),
  ADD KEY `RefFileType` (`REF_Filetype`);

--
-- Indizes für die Tabelle `FileType`
--
ALTER TABLE `FileType`
  ADD PRIMARY KEY (`ID_FileType`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indizes für die Tabelle `MediaSet`
--
ALTER TABLE `MediaSet`
  ADD PRIMARY KEY (`ID_MediaSet`),
  ADD UNIQUE KEY `UniquePublisherCode` (`REF_Studio`,`PublisherCode`),
  ADD UNIQUE KEY `UniqueTitle for Publisher` (`Title`,`REF_Studio`),
  ADD KEY `Ref_Studio` (`REF_Studio`);

--
-- Indizes für die Tabelle `Model`
--
ALTER TABLE `Model`
  ADD PRIMARY KEY (`ID_Model`),
  ADD UNIQUE KEY `Fullname` (`Fullname`);

--
-- Indizes für die Tabelle `Studio`
--
ALTER TABLE `Studio`
  ADD PRIMARY KEY (`ID_Studio`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`ID_User`),
  ADD UNIQUE KEY `UniqueLogin` (`Login`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Comment`
--
ALTER TABLE `Comment`
  MODIFY `ID_Comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `Device`
--
ALTER TABLE `Device`
  MODIFY `ID_Device` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `File`
--
ALTER TABLE `File`
  MODIFY `ID_File` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1773383;

--
-- AUTO_INCREMENT für Tabelle `FileType`
--
ALTER TABLE `FileType`
  MODIFY `ID_FileType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `MediaSet`
--
ALTER TABLE `MediaSet`
  MODIFY `ID_MediaSet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9724;

--
-- AUTO_INCREMENT für Tabelle `Model`
--
ALTER TABLE `Model`
  MODIFY `ID_Model` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key, autoinc', AUTO_INCREMENT=1120;

--
-- AUTO_INCREMENT für Tabelle `Studio`
--
ALTER TABLE `Studio`
  MODIFY `ID_Studio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `LinkToMediaSet` FOREIGN KEY (`REF_MediaSet`) REFERENCES `MediaSet` (`ID_MediaSet`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `LinkToParent` FOREIGN KEY (`REF_Comment`) REFERENCES `Comment` (`ID_Comment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `LinkToUser` FOREIGN KEY (`REF_User`) REFERENCES `User` (`ID_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `C_Model_MediaSet`
--
ALTER TABLE `C_Model_MediaSet`
  ADD CONSTRAINT `RefModelMediaSet1` FOREIGN KEY (`REF_Model`) REFERENCES `Model` (`ID_Model`) ON DELETE CASCADE,
  ADD CONSTRAINT `RefModelMediaSet2` FOREIGN KEY (`REF_MediaSet`) REFERENCES `MediaSet` (`ID_MediaSet`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `File`
--
ALTER TABLE `File`
  ADD CONSTRAINT `RefDevice` FOREIGN KEY (`REF_Device`) REFERENCES `Device` (`ID_Device`) ON DELETE CASCADE,
  ADD CONSTRAINT `RefFileType` FOREIGN KEY (`REF_Filetype`) REFERENCES `FileType` (`ID_FileType`),
  ADD CONSTRAINT `RefMediaSet` FOREIGN KEY (`REF_MediaSet`) REFERENCES `MediaSet` (`ID_MediaSet`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `MediaSet`
--
ALTER TABLE `MediaSet`
  ADD CONSTRAINT `Ref_Studio` FOREIGN KEY (`REF_Studio`) REFERENCES `Studio` (`ID_Studio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
