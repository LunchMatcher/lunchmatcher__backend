-- MySQL dump 10.13  Distrib 5.5.33, for Linux (i686)
--
-- Host: localhost    Database: lunch_matcher
-- ------------------------------------------------------
-- Server version	5.5.33-log

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
-- Table structure for table `general_config`
--

DROP TABLE IF EXISTS `general_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_config` (
  `field` varchar(255) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `description` text CHARACTER SET latin1,
  `editable` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'Y'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_config`
--

LOCK TABLES `general_config` WRITE;
/*!40000 ALTER TABLE `general_config` DISABLE KEYS */;
INSERT INTO `general_config` VALUES ('email_from','Lunch Matcher','Admin From Email','Admin From Email','Y'),('admin_email','admin@lunchmatcher.com','Admin Email','Admin Email','Y'),('admin_contactus_email','admin@lunchmatcher.com','Contact Us Email','Email to get Contact us messages','Y'),('site_name','Lunch Matcher','Website Name','This should be the website name','Y'),('default_pagination','20','Pagination Limit','Pagination Limit','Y'),('register_activation_mail','N','Register Activation Email','Value shoud be \'Y\' for yes and \'N\' for no','Y'),('time_format','H:i:s','Time format','Time format to display','Y'),('date_format','d M,Y','Date format','Date format','Y');
/*!40000 ALTER TABLE `general_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `match_log_master`
--

DROP TABLE IF EXISTS `match_log_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_log_master` (
  `match_logid` bigint(20) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) NOT NULL,
  `match_time_from` datetime NOT NULL,
  `match_time_to` datetime NOT NULL,
  `match_latitude` decimal(10,8) NOT NULL,
  `match_longitude` decimal(11,8) NOT NULL,
  `match_radius` float(4,2) NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`match_logid`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `match_log_master`
--

LOCK TABLES `match_log_master` WRITE;
/*!40000 ALTER TABLE `match_log_master` DISABLE KEYS */;
INSERT INTO `match_log_master` VALUES (1,1,'2015-02-18 06:18:34','2015-02-18 08:18:40',10.02367610,76.31162350,5.00,'2015-02-18 06:20:18'),(2,3,'2015-02-18 06:21:01','2015-02-18 07:21:04',9.96845000,76.28221500,5.00,'2015-02-18 06:21:46'),(3,10,'2015-02-17 08:02:46','2015-02-17 10:02:49',10.02367610,76.31162350,5.00,'2015-02-17 08:03:07'),(4,11,'2015-02-16 10:42:24','2015-02-16 13:42:32',37.26816200,-121.90647100,8.00,'2015-02-16 13:42:51');
/*!40000 ALTER TABLE `match_log_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `match_log_restaurants`
--

DROP TABLE IF EXISTS `match_log_restaurants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_log_restaurants` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `match_logid` bigint(20) NOT NULL,
  `restaurant_id` varchar(80) NOT NULL COMMENT 'restaurant unique id from google',
  PRIMARY KEY (`id`),
  KEY `match_logid` (`match_logid`),
  CONSTRAINT `match_log_restaurants_ibfk_1` FOREIGN KEY (`match_logid`) REFERENCES `match_log_master` (`match_logid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `match_log_restaurants`
--

LOCK TABLES `match_log_restaurants` WRITE;
/*!40000 ALTER TABLE `match_log_restaurants` DISABLE KEYS */;
/*!40000 ALTER TABLE `match_log_restaurants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_admins`
--

DROP TABLE IF EXISTS `member_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_admins` (
  `member_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_time` datetime NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'Y',
  `role` int(8) NOT NULL DEFAULT '1' COMMENT '1-Super Admin',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_admins`
--

LOCK TABLES `member_admins` WRITE;
/*!40000 ALTER TABLE `member_admins` DISABLE KEYS */;
INSERT INTO `member_admins` VALUES (1,'admin','admin','admin.adminforall@gmail.com','2015-02-12 09:58:09','Y',1);
/*!40000 ALTER TABLE `member_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_feedbacks`
--

DROP TABLE IF EXISTS `member_feedbacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_feedbacks` (
  `feed_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) NOT NULL,
  `given_user` bigint(20) NOT NULL,
  `rating` int(5) NOT NULL,
  `feed_back` text NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`feed_id`),
  KEY `member_id` (`member_id`),
  KEY `given_user` (`given_user`),
  CONSTRAINT `member_feedbacks_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member_master` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `member_feedbacks_ibfk_2` FOREIGN KEY (`given_user`) REFERENCES `member_master` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_feedbacks`
--

LOCK TABLES `member_feedbacks` WRITE;
/*!40000 ALTER TABLE `member_feedbacks` DISABLE KEYS */;
INSERT INTO `member_feedbacks` VALUES (1,1,3,5,'hai this is a test','2015-02-16 13:22:54'),(2,1,10,4,'testing','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `member_feedbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_master`
--

DROP TABLE IF EXISTS `member_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_master` (
  `member_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `auth_id` varchar(200) NOT NULL,
  `auth_type` enum('linkedin','facebook','twitter','general') NOT NULL DEFAULT 'general',
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `created_time` datetime NOT NULL,
  `is_block` char(1) NOT NULL DEFAULT 'N',
  `score` double(8,2) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_master`
--

LOCK TABLES `member_master` WRITE;
/*!40000 ALTER TABLE `member_master` DISABLE KEYS */;
INSERT INTO `member_master` VALUES (1,'','general','Umesh','K Krishnan','umesh@newagesmb.com','Male','2015-02-12 18:17:58','Y',0.00),(3,'','general','Asker','','asker@newagesmb.com','Male','2015-02-12 14:23:52','N',0.00),(9,'','linkedin','tea','ttttttttttttttttt','ttre@fdf.co','Male','2015-02-16 00:00:00','N',0.00),(10,'','linkedin','345435','34534','umesh@newagesmb.come','Male','2015-02-13 00:00:00','N',0.00),(11,'','general','test','test','test@hh.vgg','Male','2015-02-16 06:08:19','N',0.00),(12,'','general','test11','test11','test11@hh.vc','Male','2015-02-16 00:00:00','N',0.00),(13,'','general','test2','test2','test2@hh.com','Male','2015-02-16 00:00:00','N',0.00);
/*!40000 ALTER TABLE `member_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_preferences`
--

DROP TABLE IF EXISTS `member_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_preferences` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) NOT NULL,
  `gender_exclude` enum('Male','Female','Other') NOT NULL,
  `exclude_pre_match` char(1) NOT NULL DEFAULT 'Y',
  `notification_time` time NOT NULL,
  `companies_to_exclude` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `member_preferences_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member_master` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_preferences`
--

LOCK TABLES `member_preferences` WRITE;
/*!40000 ALTER TABLE `member_preferences` DISABLE KEYS */;
INSERT INTO `member_preferences` VALUES (1,1,'Male','Y','07:30:00','');
/*!40000 ALTER TABLE `member_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_profile`
--

DROP TABLE IF EXISTS `member_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_profile` (
  `profile_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) NOT NULL,
  `member_dob` date NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `location` varchar(200) NOT NULL,
  `country_code` varchar(20) NOT NULL,
  PRIMARY KEY (`profile_id`),
  KEY `FK_member_profile` (`member_id`),
  CONSTRAINT `FK_member_profile` FOREIGN KEY (`member_id`) REFERENCES `member_master` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_profile`
--

LOCK TABLES `member_profile` WRITE;
/*!40000 ALTER TABLE `member_profile` DISABLE KEYS */;
INSERT INTO `member_profile` VALUES (1,13,'0000-00-00','','',''),(2,9,'0000-00-00','','',''),(3,12,'0000-00-00','','','');
/*!40000 ALTER TABLE `member_profile` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-19  2:14:21
