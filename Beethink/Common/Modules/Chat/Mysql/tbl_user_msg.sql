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
/*Table structure for table `tbl_user_msg` */

DROP TABLE IF EXISTS `tbl_user_msg`;

CREATE TABLE `tbl_user_msg` (
  `um_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户消息',
  `um_cust_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `um_perOrGroup` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1=>用户 2=>群组',
  `um_receive_userid` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '接收人id',
  `um_receive_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '接收状态 0=>未接收 1=>已接收',
  `um_group_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '群组id 默认为0',
  `um_msg_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '对应消息id',
  UNIQUE KEY `um_id` (`um_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
