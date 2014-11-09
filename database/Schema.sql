-- MySQL dump 10.13  Distrib 5.5.25, for Win32 (x86)
--
-- Host: localhost    Database: fitpa
-- ------------------------------------------------------
-- Server version	5.5.25

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
-- Table structure for table `administrators`
--

DROP TABLE IF EXISTS `administrators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrators` (
  `administratorsid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `housenumber` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `homephonenumber` varchar(50) DEFAULT NULL,
  `mobilenumber` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gymnasiumid` int(11) DEFAULT NULL,
  `gymnasiumname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`administratorsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrators`
--

LOCK TABLES `administrators` WRITE;
/*!40000 ALTER TABLE `administrators` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cardio`
--

DROP TABLE IF EXISTS `cardio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cardio` (
  `cardioid` int(11) NOT NULL AUTO_INCREMENT,
  `cardioprg` varchar(255) NOT NULL,
  `exercisec` varchar(255) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `distance` varchar(50) DEFAULT NULL,
  `speed` varchar(50) DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `journal` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cardioid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cardio`
--

LOCK TABLES `cardio` WRITE;
/*!40000 ALTER TABLE `cardio` DISABLE KEYS */;
/*!40000 ALTER TABLE `cardio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cardiojournal`
--

DROP TABLE IF EXISTS `cardiojournal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cardiojournal` (
  `cardiojournalid` int(11) NOT NULL AUTO_INCREMENT,
  `cardioid` int(11) NOT NULL,
  `cardioprg` varchar(255) DEFAULT NULL,
  `exercisec` varchar(255) DEFAULT NULL,
  `logtime` varchar(255) DEFAULT NULL,
  `logdistance` varchar(255) DEFAULT NULL,
  `logsets` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cardiojournalid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cardiojournal`
--

LOCK TABLES `cardiojournal` WRITE;
/*!40000 ALTER TABLE `cardiojournal` DISABLE KEYS */;
/*!40000 ALTER TABLE `cardiojournal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `clientid` int(11) NOT NULL AUTO_INCREMENT,
  `cleint` varchar(255) DEFAULT NULL,
  `goalid` int(11) DEFAULT NULL,
  PRIMARY KEY (`clientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `createevents`
--

DROP TABLE IF EXISTS `createevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `createevents` (
  `event` int(11) NOT NULL AUTO_INCREMENT,
  `goalsid` int(11) NOT NULL,
  `day` varchar(50) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `targetarea` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `createevents`
--

LOCK TABLES `createevents` WRITE;
/*!40000 ALTER TABLE `createevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `createevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `createreport`
--

DROP TABLE IF EXISTS `createreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `createreport` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `reportname` varchar(50) DEFAULT NULL,
  `gymnasiumname` varchar(255) NOT NULL,
  `specialist` varchar(255) DEFAULT NULL,
  `customerid` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `createreport`
--

LOCK TABLES `createreport` WRITE;
/*!40000 ALTER TABLE `createreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `createreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `createtasks`
--

DROP TABLE IF EXISTS `createtasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `createtasks` (
  `tasks` int(11) NOT NULL AUTO_INCREMENT,
  `event` int(11) NOT NULL,
  `exercise` varchar(50) DEFAULT NULL,
  `sets` varchar(255) DEFAULT NULL,
  `repetitions` int(11) DEFAULT NULL,
  PRIMARY KEY (`tasks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `createtasks`
--

LOCK TABLES `createtasks` WRITE;
/*!40000 ALTER TABLE `createtasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `createtasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `customerid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` char(40) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `housenumber` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postcode` int(11) DEFAULT NULL,
  `homephonenumber` int(11) DEFAULT NULL,
  `mobilenumber` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` enum('man','woman') DEFAULT 'man',
  `title` varchar(255) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `usertype` enum('specialty','customer') DEFAULT 'customer',
  `goalsid` int(11) DEFAULT NULL,
  `medicaldata` varchar(255) DEFAULT NULL,
  `gymnasiumid` int(11) DEFAULT NULL,
  `gymnasiumname` varchar(255) DEFAULT NULL,
  `specialistid` int(11) DEFAULT NULL,
  PRIMARY KEY (`customerid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'stevenli','1111','li','aaa','s',NULL,'aaa',NULL,NULL,NULL,NULL,1111,NULL,'www@126.com','man','asd','aaaa','customer',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customerreport`
--

DROP TABLE IF EXISTS `customerreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customerreport` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `gymnasiumid` int(11) DEFAULT NULL,
  `gymnasiumname` varchar(255) NOT NULL,
  `housenumber` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `custpmerid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customerreport`
--

LOCK TABLES `customerreport` WRITE;
/*!40000 ALTER TABLE `customerreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `customerreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `excercise`
--

DROP TABLE IF EXISTS `excercise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `excercise` (
  `excerciseid` int(11) NOT NULL AUTO_INCREMENT,
  `excercisename` varchar(50) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`excerciseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `excercise`
--

LOCK TABLES `excercise` WRITE;
/*!40000 ALTER TABLE `excercise` DISABLE KEYS */;
/*!40000 ALTER TABLE `excercise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goals` (
  `goalsid` int(11) NOT NULL AUTO_INCREMENT,
  `goal` varchar(255) DEFAULT NULL,
  `measure` varchar(50) DEFAULT NULL,
  `milestone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`goalsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goals`
--

LOCK TABLES `goals` WRITE;
/*!40000 ALTER TABLE `goals` DISABLE KEYS */;
/*!40000 ALTER TABLE `goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gymnasium`
--

DROP TABLE IF EXISTS `gymnasium`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gymnasium` (
  `gymnasiumid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `gymnasiumname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `housenumber` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `homephonenumber` varchar(50) DEFAULT NULL,
  `officephonenumber` varchar(255) DEFAULT NULL,
  `mobilenumber` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`gymnasiumid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gymnasium`
--

LOCK TABLES `gymnasium` WRITE;
/*!40000 ALTER TABLE `gymnasium` DISABLE KEYS */;
/*!40000 ALTER TABLE `gymnasium` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gymnasiumreport`
--

DROP TABLE IF EXISTS `gymnasiumreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gymnasiumreport` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `gymnasiumid` int(11) DEFAULT NULL,
  `gymnasiumname` varchar(255) NOT NULL,
  `housenumber` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gymnasiumreport`
--

LOCK TABLES `gymnasiumreport` WRITE;
/*!40000 ALTER TABLE `gymnasiumreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `gymnasiumreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marketing`
--

DROP TABLE IF EXISTS `marketing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marketing` (
  `advertisementid` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  PRIMARY KEY (`advertisementid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketing`
--

LOCK TABLES `marketing` WRITE;
/*!40000 ALTER TABLE `marketing` DISABLE KEYS */;
/*!40000 ALTER TABLE `marketing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messaging`
--

DROP TABLE IF EXISTS `messaging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messaging` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `sendname` varchar(50) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `senddate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messaging`
--

LOCK TABLES `messaging` WRITE;
/*!40000 ALTER TABLE `messaging` DISABLE KEYS */;
/*!40000 ALTER TABLE `messaging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nutrition`
--

DROP TABLE IF EXISTS `nutrition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nutrition` (
  `nutritionid` int(11) NOT NULL AUTO_INCREMENT,
  `nutritionprg` varchar(255) NOT NULL,
  `tbd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`nutritionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nutrition`
--

LOCK TABLES `nutrition` WRITE;
/*!40000 ALTER TABLE `nutrition` DISABLE KEYS */;
/*!40000 ALTER TABLE `nutrition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `performancecalcualtion`
--

DROP TABLE IF EXISTS `performancecalcualtion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `performancecalcualtion` (
  `performanceid` int(11) NOT NULL AUTO_INCREMENT,
  `goalsid` int(11) NOT NULL,
  `goal` varchar(255) DEFAULT NULL,
  `measurelog` varchar(255) DEFAULT NULL,
  `milestonelog` varchar(255) DEFAULT NULL,
  `graphdata` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`performanceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `performancecalcualtion`
--

LOCK TABLES `performancecalcualtion` WRITE;
/*!40000 ALTER TABLE `performancecalcualtion` DISABLE KEYS */;
/*!40000 ALTER TABLE `performancecalcualtion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `productid` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registrationadmin`
--

DROP TABLE IF EXISTS `registrationadmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registrationadmin` (
  `registrationid` int(11) NOT NULL AUTO_INCREMENT,
  `accounttype` varchar(255) NOT NULL,
  `uername` varchar(50) NOT NULL,
  `readlegal` varchar(255) NOT NULL,
  `readmedical` varchar(255) NOT NULL,
  `gymnasiumname` varchar(255) NOT NULL,
  PRIMARY KEY (`registrationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrationadmin`
--

LOCK TABLES `registrationadmin` WRITE;
/*!40000 ALTER TABLE `registrationadmin` DISABLE KEYS */;
/*!40000 ALTER TABLE `registrationadmin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resistance`
--

DROP TABLE IF EXISTS `resistance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resistance` (
  `resistanceid` int(11) NOT NULL AUTO_INCREMENT,
  `resistanceprg` varchar(255) NOT NULL,
  `exerciser` varchar(255) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `repetitions` int(11) DEFAULT NULL,
  `sets` varchar(50) DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `journal` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`resistanceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resistance`
--

LOCK TABLES `resistance` WRITE;
/*!40000 ALTER TABLE `resistance` DISABLE KEYS */;
/*!40000 ALTER TABLE `resistance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resistancejournal`
--

DROP TABLE IF EXISTS `resistancejournal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resistancejournal` (
  `resistancejournalid` int(11) NOT NULL AUTO_INCREMENT,
  `resistanceid` int(11) NOT NULL,
  `resistanceprg` varchar(255) DEFAULT NULL,
  `exerciser` varchar(255) DEFAULT NULL,
  `logweight` varchar(255) DEFAULT NULL,
  `logrepetitions` varchar(255) DEFAULT NULL,
  `logsets` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`resistancejournalid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resistancejournal`
--

LOCK TABLES `resistancejournal` WRITE;
/*!40000 ALTER TABLE `resistancejournal` DISABLE KEYS */;
/*!40000 ALTER TABLE `resistancejournal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialist`
--

DROP TABLE IF EXISTS `specialist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialist` (
  `specialistid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `housenumber` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postcode` int(11) DEFAULT NULL,
  `homephonenumber` int(11) DEFAULT NULL,
  `mobilenumber` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `gender` enum('man','woman') DEFAULT 'man',
  `usertype` enum('specialty','customer') DEFAULT 'specialty',
  `specialtyid` int(11) DEFAULT NULL,
  `specialtycertification` varchar(255) DEFAULT NULL,
  `gymnasiumid` int(11) DEFAULT NULL,
  `gymnasiumname` varchar(255) DEFAULT NULL,
  `customerid` int(11) DEFAULT NULL,
  PRIMARY KEY (`specialistid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialist`
--

LOCK TABLES `specialist` WRITE;
/*!40000 ALTER TABLE `specialist` DISABLE KEYS */;
/*!40000 ALTER TABLE `specialist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialistreport`
--

DROP TABLE IF EXISTS `specialistreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialistreport` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `gymnasiumid` int(11) DEFAULT NULL,
  `gymnasiumname` varchar(255) NOT NULL,
  `housenumber` varchar(255) DEFAULT NULL,
  `suburb` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `specialistid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialistreport`
--

LOCK TABLES `specialistreport` WRITE;
/*!40000 ALTER TABLE `specialistreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `specialistreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialty`
--

DROP TABLE IF EXISTS `specialty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialty` (
  `specialtyid` int(11) NOT NULL AUTO_INCREMENT,
  `specialty` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`specialtyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialty`
--

LOCK TABLES `specialty` WRITE;
/*!40000 ALTER TABLE `specialty` DISABLE KEYS */;
/*!40000 ALTER TABLE `specialty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trackprogress`
--

DROP TABLE IF EXISTS `trackprogress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trackprogress` (
  `trackprogressid` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `status` enum('complete','partially','not') DEFAULT 'complete',
  `comments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`trackprogressid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trackprogress`
--

LOCK TABLES `trackprogress` WRITE;
/*!40000 ALTER TABLE `trackprogress` DISABLE KEYS */;
INSERT INTO `trackprogress` VALUES (1,1,'s','complete','asdf'),(2,2,'a','partially','qwer'),(3,3,'d','not','zxcv');
/*!40000 ALTER TABLE `trackprogress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weightjournal`
--

DROP TABLE IF EXISTS `weightjournal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weightjournal` (
  `weightid` int(11) NOT NULL AUTO_INCREMENT,
  `weight` varchar(50) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`weightid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weightjournal`
--

LOCK TABLES `weightjournal` WRITE;
/*!40000 ALTER TABLE `weightjournal` DISABLE KEYS */;
/*!40000 ALTER TABLE `weightjournal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-10  3:45:32
