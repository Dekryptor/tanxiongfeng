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
/*Table structure for table `tbl_chat_user` */

DROP TABLE IF EXISTS `tbl_chat_user`;

CREATE TABLE `tbl_chat_user` (
  `cu_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `cu_cust_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `cu_friend_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '好友id',
  `cu_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否已经建立了好友关系 0=>否 1=>是',
  `cu_remark` varchar(100) NOT NULL COMMENT '备注(如果有备注,则直接显示备注)',
  `cu_classify_id` int(4) unsigned NOT NULL DEFAULT '1' COMMENT '所属分组id   默认为1 表示我的好友',
  UNIQUE KEY `cu_id` (`cu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
