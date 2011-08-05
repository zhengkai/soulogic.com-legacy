-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: soulogic
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

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
-- Current Database: `soulogic`
--

/*!40000 DROP DATABASE IF EXISTS `soulogic`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `soulogic` /*!40100 DEFAULT CHARACTER SET ucs2 */;

USE `soulogic`;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `subtitle` varchar(200) NOT NULL DEFAULT '',
  `author` varchar(200) NOT NULL DEFAULT '',
  `preview` text NOT NULL,
  `content` mediumtext NOT NULL,
  `comment` text NOT NULL,
  `provenance_text` varchar(200) NOT NULL DEFAULT '',
  `provenance_url` varchar(200) NOT NULL DEFAULT '',
  `original_tag` enum('N','Y') CHARACTER SET ascii NOT NULL DEFAULT 'Y',
  `publish_tag` enum('N','Y') CHARACTER SET ascii NOT NULL DEFAULT 'N',
  `rss_tag` enum('N','Y') CHARACTER SET ascii NOT NULL DEFAULT 'Y',
  `delete_tag` enum('N','Y') CHARACTER SET ascii NOT NULL DEFAULT 'N',
  `visits` int(10) unsigned NOT NULL DEFAULT '0',
  `initial` char(1) NOT NULL DEFAULT '',
  `folder` int(10) unsigned NOT NULL DEFAULT '0',
  `keywords` varchar(200) NOT NULL DEFAULT '',
  `date_o` varchar(19) NOT NULL DEFAULT '',
  `date_m` datetime NOT NULL,
  `date_p` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `index` (`delete_tag`,`publish_tag`,`folder`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bookmark`
--

DROP TABLE IF EXISTS `bookmark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookmark` (
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `folder` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `name` varchar(20) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=ucs2 COMMENT='Config';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `counter`
--

DROP TABLE IF EXISTS `counter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `referer_host` varchar(255) NOT NULL DEFAULT '',
  `date_a` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `referer_host` (`referer_host`),
  KEY `date_a` (`date_a`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `content` mediumblob NOT NULL,
  `header` varchar(200) NOT NULL DEFAULT '',
  `date_c` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `counter` int(10) unsigned NOT NULL DEFAULT '0',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `delete_tag` enum('N','Y') CHARACTER SET ascii NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `folder`
--

DROP TABLE IF EXISTS `folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `folder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `folder` int(10) unsigned NOT NULL DEFAULT '0',
  `sequence` int(10) unsigned NOT NULL DEFAULT '0',
  `urlname` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guestbook`
--

DROP TABLE IF EXISTS `guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guestbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder` int(10) unsigned NOT NULL DEFAULT '0',
  `guest` varchar(20) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `content` text,
  `date_a` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `del_tag` enum('N','Y') CHARACTER SET ascii NOT NULL DEFAULT 'N',
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`,`folder`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icon_track`
--

DROP TABLE IF EXISTS `icon_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icon_track` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog` int(10) unsigned NOT NULL,
  `date_access` date NOT NULL,
  `time_access` time NOT NULL,
  `referer` tinytext NOT NULL,
  `referer_site` tinytext NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trackback`
--

DROP TABLE IF EXISTS `trackback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trackback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blogId` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `excerpt` tinytext NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '',
  `blog_name` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogId` (`blogId`,`url`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Current Database: `soulogic_pda`
--

/*!40000 DROP DATABASE IF EXISTS `soulogic_pda`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `soulogic_pda` /*!40100 DEFAULT CHARACTER SET ucs2 */;

USE `soulogic_pda`;

--
-- Table structure for table `msg`
--

DROP TABLE IF EXISTS `msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` char(50) NOT NULL,
  `length` int(11) NOT NULL,
  `itype` tinyint(3) unsigned NOT NULL,
  `datetime_c` datetime NOT NULL,
  `datetime_m` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itype` (`itype`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `msg_content`
--

DROP TABLE IF EXISTS `msg_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msg_content` (
  `id` int(11) NOT NULL,
  `content_ext` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=ucs2;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-05 16:40:25
