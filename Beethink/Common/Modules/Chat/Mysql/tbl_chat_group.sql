/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.5.8-log : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `tbl_chat_group` */

DROP TABLE IF EXISTS `tbl_chat_group`;

CREATE TABLE `tbl_chat_group` (
  `cg_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '群组表',
  `cg_name` varchar(50) NOT NULL COMMENT '群名',
  `cg_cust_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  `cg_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  UNIQUE KEY `g_id` (`cg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
