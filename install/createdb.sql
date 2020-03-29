-- mediadb
-- Version 0.8
-- Created by Karl Müller
-- Generates an empty database
-- Warning: This script is only ment for initial setup.
--          Do NOT use this for updates - all data will be lost!
-- ------------------------------------------------------------------------
-- Host: localhost
-- Generation Time: Feb 23, 2020 at 05:40 AM
-- Server version: 10.2.29-MariaDB
-- PHP Version: 7.2.5
-- ------------------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mediadb`
--
CREATE DATABASE IF NOT EXISTS `mediadb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mediadb`;

-- User mediadb
-- The password here needs to match the password in the conf.php
CREATE USER 'mediadb'@'localhost' IDENTIFIED BY 'your_secret_password';
GRANT ALL PRIVILEGES ON mediadb.* TO 'mediadb'@'localhost';

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `ID_Comment` int(11) NOT NULL,
  `REF_Comment` int(11) DEFAULT NULL COMMENT 'The Parent Comment',
  `REF_User` int(11) NOT NULL,
  `REF_Episode` int(11) NOT NULL,
  `Title` varchar(250) NOT NULL,
  `Body` text DEFAULT NULL,
  `LastChange` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `Comment`
--
DELIMITER $$
CREATE TRIGGER `UpdateComment` BEFORE UPDATE ON `Comment` FOR EACH ROW SET NEW.LastChange = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `C_Episode_User`
--

CREATE TABLE `C_Episode_User` (
  `REF_Episode` int(11) NOT NULL,
  `REF_User` int(11) NOT NULL,
  `LastWatched` timestamp NULL DEFAULT NULL,
  `LastProgress` double DEFAULT NULL,
  `WatchCount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `C_Actor_Episode`
--

CREATE TABLE `C_Actor_Episode` (
  `REF_Actor` int(11) NOT NULL,
  `REF_Episode` int(11) NOT NULL,
  `Comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `C_WatchedFiles`
--

CREATE TABLE `C_WatchedFiles` (
  `REF_File` int(11) NOT NULL,
  `REF_User` int(11) NOT NULL,
  `Watched` timestamp NOT NULL DEFAULT current_timestamp(),
  `Rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores, when a user last watched a file';

-- --------------------------------------------------------

--
-- Table structure for table `C_WatchList_Episode`
--

CREATE TABLE `C_WatchList_Episode` (
  `REF_WatchList` int(11) NOT NULL,
  `REF_Episode` int(11) NOT NULL,
  `Position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Device`
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
-- Table structure for table `File`
--

CREATE TABLE `File` (
  `ID_File` bigint(11) NOT NULL,
  `Name` varchar(250) NOT NULL COMMENT 'Filename (without path)',
  `Path` varchar(250) NOT NULL COMMENT 'Relative path to the file',
  `Availlable` tinyint(1) DEFAULT 1,
  `REF_Device` int(11) NOT NULL,
  `REF_Episode` int(11) NOT NULL,
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
  `Added` timestamp NULL DEFAULT current_timestamp() COMMENT 'Notes, when the file was added to mediadb',
  `Playtime` int(11) DEFAULT 0 COMMENT 'Movie: Playtime in seconds, others 0',
  `Progress` double DEFAULT 0 COMMENT 'Viewed Percent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `File`
--
DELIMITER $$
CREATE TRIGGER `UpateParentAfterInsert` AFTER INSERT ON `File` FOR EACH ROW UPDATE Episode SET Modified=NOW() WHERE ID_Episode = NEW.REF_Episode
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UpdateParentAfterDelete` BEFORE DELETE ON `File` FOR EACH ROW Update Episode SET Modified=NOW() WHERE ID_Episode = OLD.REF_Episode
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `FileType`
--

CREATE TABLE `FileType` (
  `ID_FileType` int(11) NOT NULL,
  `Name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Episode`
--

CREATE TABLE `Episode` (
  `ID_Episode` int(11) NOT NULL,
  `Title` varchar(250) NOT NULL,
  `Description` text DEFAULT NULL,
  `Keywords` text DEFAULT NULL COMMENT 'Keywords, separated by ,',
  `Published` smallint(6) DEFAULT NULL COMMENT 'Year of publication',
  `REF_Channel` int(11) DEFAULT NULL COMMENT 'Publisher',
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
-- Triggers `Episode`
--
DELIMITER $$
CREATE TRIGGER `UpdateModificationTime` BEFORE UPDATE ON `Episode` FOR EACH ROW SET NEW.Modified = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Actor`
--

CREATE TABLE `Actor` (
  `ID_Actor` int(11) NOT NULL COMMENT 'primary key, autoinc',
  `Fullname` varchar(80) NOT NULL COMMENT 'Usual, unique name for the Actor.',
  `Aliases` varchar(120) DEFAULT NULL COMMENT 'Further names for the Actor',
  `Gender` char(1) DEFAULT 'F' COMMENT 'Gender of the Actor: F,M,T,?',
  `Description` text DEFAULT NULL COMMENT 'Further attributes of the Actor',
  `Keywords` varchar(120) DEFAULT NULL,
  `Mugshot` varchar(255) DEFAULT NULL COMMENT 'Path to the mugshot - relative to mugshot directory',
  `Thumbnail` varchar(250) DEFAULT NULL,
  `Wallpaper` varchar(255) DEFAULT NULL COMMENT 'Path to the Background Image - relative to wallpaper directory',
  `Twitter` varchar(250) DEFAULT NULL COMMENT 'Twitter-Account of the Actor',
  `Website` varchar(250) DEFAULT NULL COMMENT 'Website of the Actor, furhter can be added in the comments',
  `Sites` text DEFAULT NULL COMMENT 'Further sites with information about this Actor',
  `Data` text DEFAULT NULL COMMENT 'Contains arbitry data about the Actor in the format key:value',
  `Rating` decimal(5,1) DEFAULT NULL,
  `Added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `Actor`
--
DELIMITER $$
CREATE TRIGGER `OnNewActor` BEFORE INSERT ON `Actor` FOR EACH ROW BEGIN 
SET NEW.Added = NOW(); 
SET NEW.Modified = NOW(); 
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `OnUpdateActor` BEFORE UPDATE ON `Actor` FOR EACH ROW SET NEW.Modified = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Channel`
--

CREATE TABLE `Channel` (
  `ID_Channel` int(11) NOT NULL,
  `Name` varchar(120) NOT NULL,
  `Site` varchar(250) DEFAULT NULL,
  `DefaultSetPath` varchar(250) DEFAULT NULL COMMENT 'Default Path to a set from this site',
  `Logo` varchar(250) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `Wallpaper` varchar(250) DEFAULT NULL,
  `Twitter` varchar(128) DEFAULT NULL,
  `StudioType` varchar(64) DEFAULT NULL,
  `Modified` timestamp NULL DEFAULT NULL,
  `Added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `Channel`
--
DELIMITER $$
CREATE TRIGGER `UpdateChannelTimestamp` BEFORE UPDATE ON `Channel` FOR EACH ROW SET NEW.Modified = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `User`
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
-- Stand-in structure for view `V_FileWithDevice`
-- (See below for the actual view)
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
,`REF_Episode` int(11)
,`REF_Filetype` int(11)
,`FileInfo` text
,`Size` bigint(20)
,`ResX` int(11)
,`ResY` int(11)
,`Created` timestamp
,`Modified` timestamp
,`Playtime` int(11)
,`REF_Device` int(11)
,`Progress` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `V_EpisodeWithChannel`
-- (See below for the actual view)
--
CREATE TABLE `V_EpisodeWithChannel` (
`ID_Episode` int(11)
,`Title` varchar(250)
,`Description` text
,`Keywords` text
,`Published` smallint(6)
,`REF_Channel` int(11)
,`PublisherCode` varchar(80)
,`Link` varchar(250)
,`Picture` varchar(250)
,`Wallpaper` varchar(250)
,`Comment` text
,`Rating` tinyint(4)
,`Viewed` tinyint(4)
,`Added` timestamp
,`Modified` timestamp
,`Channel` varchar(120)
,`Logo` varchar(250)
);

-- --------------------------------------------------------

--
-- Table structure for table `WatchList`
--

CREATE TABLE `WatchList` (
  `ID_WatchList` int(11) NOT NULL COMMENT 'Primärschlüssel',
  `REF_User` int(11) NOT NULL COMMENT 'Besiter der Liste',
  `Title` varchar(250) NOT NULL COMMENT 'Titel der Liste',
  `Description` text DEFAULT NULL COMMENT 'Summary of the content',
  `MultipleTimes` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'If true, the same set can be added multiple times'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `V_FileWithDevice`
--
DROP TABLE IF EXISTS `V_FileWithDevice`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mediadb`@`localhost` SQL SECURITY INVOKER VIEW `V_FileWithDevice`  AS  select `F`.`ID_File` AS `ID_File`,`F`.`Name` AS `Name`,`F`.`Path` AS `Path`,`F`.`Title` AS `Title`,`F`.`Comment` AS `Comment`,`F`.`Keywords` AS `Keywords`,`F`.`Rating` AS `Rating`,`D`.`Name` AS `Device`,`D`.`DisplayPath` AS `DevicePath`,`D`.`Path` AS `SystemPath`,`F`.`REF_Episode` AS `REF_Episode`,`F`.`REF_Filetype` AS `REF_Filetype`,`F`.`FileInfo` AS `FileInfo`,`F`.`Size` AS `Size`,`F`.`ResX` AS `ResX`,`F`.`ResY` AS `ResY`,`F`.`Created` AS `Created`,`F`.`Modified` AS `Modified`,`F`.`Playtime` AS `Playtime`,`F`.`REF_Device` AS `REF_Device`,`F`.`Progress` AS `Progress` from (`File` `F` left join `Device` `D` on(`F`.`REF_Device` = `D`.`ID_Device`)) ;

-- --------------------------------------------------------

--
-- Structure for view `V_EpisodeWithChannel`
--
DROP TABLE IF EXISTS `V_EpisodeWithChannel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mediadb`@`localhost` SQL SECURITY INVOKER VIEW `V_EpisodeWithChannel`  AS  select `M`.`ID_Episode` AS `ID_Episode`,`M`.`Title` AS `Title`,`M`.`Description` AS `Description`,`M`.`Keywords` AS `Keywords`,`M`.`Published` AS `Published`,`M`.`REF_Channel` AS `REF_Channel`,`M`.`PublisherCode` AS `PublisherCode`,`M`.`Link` AS `Link`,`M`.`Picture` AS `Picture`,`M`.`Wallpaper` AS `Wallpaper`,`M`.`Comment` AS `Comment`,`M`.`Rating` AS `Rating`,`M`.`Viewed` AS `Viewed`,`M`.`Added` AS `Added`,`M`.`Modified` AS `Modified`,`S`.`Name` AS `Channel`,`S`.`Logo` AS `Logo` from (`Episode` `M` left join `Channel` `S` on(`M`.`REF_Channel` = `S`.`ID_Channel`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`ID_Comment`),
  ADD KEY `LinkToEpisode` (`REF_Episode`),
  ADD KEY `LinkToParent` (`REF_Comment`),
  ADD KEY `LinkToUser` (`REF_User`);

--
-- Indexes for table `C_Episode_User`
--
ALTER TABLE `C_Episode_User`
  ADD PRIMARY KEY (`REF_Episode`,`REF_User`),
  ADD KEY `REF_User` (`REF_User`);

--
-- Indexes for table `C_Actor_Episode`
--
ALTER TABLE `C_Actor_Episode`
  ADD PRIMARY KEY (`REF_Actor`,`REF_Episode`),
  ADD KEY `RefActorEpisode2` (`REF_Episode`);

--
-- Indexes for table `C_WatchedFiles`
--
ALTER TABLE `C_WatchedFiles`
  ADD PRIMARY KEY (`REF_User`,`REF_File`);

--
-- Indexes for table `C_WatchList_Episode`
--
ALTER TABLE `C_WatchList_Episode`
  ADD PRIMARY KEY (`REF_Episode`,`REF_WatchList`,`Position`) USING BTREE,
  ADD KEY `REF_WatchList` (`REF_WatchList`);

--
-- Indexes for table `Device`
--
ALTER TABLE `Device`
  ADD PRIMARY KEY (`ID_Device`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `File`
--
ALTER TABLE `File`
  ADD PRIMARY KEY (`ID_File`),
  ADD KEY `REF_Device` (`REF_Device`),
  ADD KEY `IDX_REV_Episode` (`REF_Episode`),
  ADD KEY `RefFileType` (`REF_Filetype`);

--
-- Indexes for table `FileType`
--
ALTER TABLE `FileType`
  ADD PRIMARY KEY (`ID_FileType`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `Episode`
--
ALTER TABLE `Episode`
  ADD PRIMARY KEY (`ID_Episode`),
  ADD UNIQUE KEY `UniquePublisherCode` (`REF_Channel`,`PublisherCode`),
  ADD UNIQUE KEY `UniqueTitle for Publisher` (`Title`,`REF_Channel`),
  ADD KEY `Ref_Channel` (`REF_Channel`);

--
-- Indexes for table `Actor`
--
ALTER TABLE `Actor`
  ADD PRIMARY KEY (`ID_Actor`),
  ADD UNIQUE KEY `Fullname` (`Fullname`);

--
-- Indexes for table `Channel`
--
ALTER TABLE `Channel`
  ADD PRIMARY KEY (`ID_Channel`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`ID_User`),
  ADD UNIQUE KEY `UniqueLogin` (`Login`);

--
-- Indexes for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD PRIMARY KEY (`ID_WatchList`),
  ADD KEY `LinkWatchlistToUser` (`REF_User`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `ID_Comment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Device`
--
ALTER TABLE `Device`
  MODIFY `ID_Device` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `File`
--
ALTER TABLE `File`
  MODIFY `ID_File` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `FileType`
--
ALTER TABLE `FileType`
  MODIFY `ID_FileType` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Episode`
--
ALTER TABLE `Episode`
  MODIFY `ID_Episode` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Actor`
--
ALTER TABLE `Actor`
  MODIFY `ID_Actor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key, autoinc';

--
-- AUTO_INCREMENT for table `Channel`
--
ALTER TABLE `Channel`
  MODIFY `ID_Channel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WatchList`
--
ALTER TABLE `WatchList`
  MODIFY `ID_WatchList` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primärschlüssel';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `LinkToEpisode` FOREIGN KEY (`REF_Episode`) REFERENCES `Episode` (`ID_Episode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `LinkToParent` FOREIGN KEY (`REF_Comment`) REFERENCES `Comment` (`ID_Comment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `LinkToUser` FOREIGN KEY (`REF_User`) REFERENCES `User` (`ID_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `C_Episode_User`
--
ALTER TABLE `C_Episode_User`
  ADD CONSTRAINT `C_Episode_User_ibfk_1` FOREIGN KEY (`REF_Episode`) REFERENCES `Episode` (`ID_Episode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `C_Episode_User_ibfk_2` FOREIGN KEY (`REF_User`) REFERENCES `User` (`ID_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `C_Actor_Episode`
--
ALTER TABLE `C_Actor_Episode`
  ADD CONSTRAINT `RefActorEpisode1` FOREIGN KEY (`REF_Actor`) REFERENCES `Actor` (`ID_Actor`) ON DELETE CASCADE,
  ADD CONSTRAINT `RefActorEpisode2` FOREIGN KEY (`REF_Episode`) REFERENCES `Episode` (`ID_Episode`) ON DELETE CASCADE;

--
-- Constraints for table `C_WatchList_Episode`
--
ALTER TABLE `C_WatchList_Episode`
  ADD CONSTRAINT `C_WatchList_Episode_ibfk_1` FOREIGN KEY (`REF_Episode`) REFERENCES `Episode` (`ID_Episode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `C_WatchList_Episode_ibfk_2` FOREIGN KEY (`REF_WatchList`) REFERENCES `WatchList` (`ID_WatchList`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `File`
--
ALTER TABLE `File`
  ADD CONSTRAINT `RefDevice` FOREIGN KEY (`REF_Device`) REFERENCES `Device` (`ID_Device`) ON DELETE CASCADE,
  ADD CONSTRAINT `RefFileType` FOREIGN KEY (`REF_Filetype`) REFERENCES `FileType` (`ID_FileType`),
  ADD CONSTRAINT `RefEpisode` FOREIGN KEY (`REF_Episode`) REFERENCES `Episode` (`ID_Episode`) ON DELETE CASCADE;

--
-- Constraints for table `Episode`
--
ALTER TABLE `Episode`
  ADD CONSTRAINT `Ref_Channel` FOREIGN KEY (`REF_Channel`) REFERENCES `Channel` (`ID_Channel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD CONSTRAINT `LinkWatchlistToUser` FOREIGN KEY (`REF_User`) REFERENCES `User` (`ID_User`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
