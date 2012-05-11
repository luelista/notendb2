-- MySQL dump 10.13  Distrib 5.5.16, for Linux (i686)
--
-- Host: localhost    Database: notendb
-- ------------------------------------------------------
-- Server version	5.5.16-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `datei`
--

DROP TABLE IF EXISTS `datei`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datei` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `jahr` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `hj` tinyint(4) NOT NULL,
  `schulform` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `stufe` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kurs`
--

DROP TABLE IF EXISTS `kurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kurs` (
  `kuid` bigint(20) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gewichtung` int(11) NOT NULL,
  `art` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `wochenstunden` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `thema` text COLLATE utf8_unicode_ci NOT NULL,
  `display_position` int(11) NOT NULL,
  `export_position` int(11) NOT NULL,
  PRIMARY KEY (`kuid`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kurs_template`
--

DROP TABLE IF EXISTS `kurs_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kurs_template` (
  `ktid` int(11) NOT NULL AUTO_INCREMENT,
  `schulform` varchar(8) NOT NULL,
  `stufe` varchar(4) NOT NULL,
  `hj` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gewichtung` int(11) NOT NULL,
  `art` varchar(10) NOT NULL,
  `wochenstunden` varchar(10) NOT NULL,
  `thema` text NOT NULL,
  `display_position` int(11) NOT NULL,
  `export_position` int(11) NOT NULL,
  PRIMARY KEY (`ktid`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lehrer`
--

DROP TABLE IF EXISTS `lehrer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lehrer` (
  `lid` bigint(20) NOT NULL AUTO_INCREMENT,
  `kuerzel` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `anrede` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `titel` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `geburtsdatum` date NOT NULL DEFAULT '0000-00-00',
  `is_admin` tinyint(4) NOT NULL,
  `kommentar` text COLLATE utf8_unicode_ci NOT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastlogin_from` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`lid`),
  UNIQUE KEY `kuerzel` (`kuerzel`)
) ENGINE=MyISAM AUTO_INCREMENT=343 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `rel_lehrer_kurs`
--

DROP TABLE IF EXISTS `rel_lehrer_kurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_lehrer_kurs` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `r_kuid` bigint(20) NOT NULL,
  `r_lid` bigint(20) NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `schuljahr` (`r_kuid`,`r_lid`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_schueler_kurs`
--

DROP TABLE IF EXISTS `rel_schueler_kurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_schueler_kurs` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `r_sid` bigint(20) NOT NULL,
  `r_kuid` bigint(20) NOT NULL,
  `note` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fehlstunden` int(11) NOT NULL,
  `fehlstunden_un` int(11) NOT NULL,
  `kommentar` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `schuljahr` (`r_sid`,`r_kuid`)
) ENGINE=MyISAM AUTO_INCREMENT=393 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schueler`
--

DROP TABLE IF EXISTS `schueler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schueler` (
  `sid` bigint(20) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `geburtsdatum` date NOT NULL DEFAULT '0000-00-00',
  `username` varchar(100) NOT NULL,
  `klasse` varchar(50) NOT NULL,
  `strasse` varchar(100) NOT NULL,
  `plz` varchar(10) NOT NULL,
  `ort` varchar(100) NOT NULL,
  `telefon` varchar(40) NOT NULL,
  `bemerkungen` text NOT NULL,
  `kommentar` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`),
  KEY `did` (`did`)
) ENGINE=MyISAM AUTO_INCREMENT=456 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tutor`
--

DROP TABLE IF EXISTS `tutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor` (
  `tid` mediumint(9) NOT NULL,
  `r_did` bigint(20) NOT NULL,
  `r_lid` bigint(20) NOT NULL,
  `is_tutor` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Dump completed on 2012-05-11 10:28:23
