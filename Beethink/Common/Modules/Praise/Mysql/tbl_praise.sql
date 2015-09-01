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
/*Table structure for table `tbl_praise` */

DROP TABLE IF EXISTS `tbl_praise`;

CREATE TABLE `tbl_praise` (
  `p_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '点赞',
  `p_user_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `p_obj_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '点赞对象id',
  `p_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '点赞时间戳',
  UNIQUE KEY `p_id` (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
