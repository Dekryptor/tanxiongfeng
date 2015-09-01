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
/*Table structure for table `tbl_group_user` */

DROP TABLE IF EXISTS `tbl_group_user`;

CREATE TABLE `tbl_group_user` (
  `gu_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '群组用户管理',
  `gu_group_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '群组id',
  `gu_cust_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `gu_mark` varchar(40) NOT NULL COMMENT '群成员备注',
  UNIQUE KEY `gu_id` (`gu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
