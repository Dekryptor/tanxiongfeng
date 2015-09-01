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
/*Table structure for table `tbl_chatmsg_bak` */

DROP TABLE IF EXISTS `tbl_chatmsg_bak`;

CREATE TABLE `tbl_chatmsg_bak` (
  `cb_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `cb_user_id` int(4) unsigned NOT NULL COMMENT '发送对象id',
  `cb_perOrGroup` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '对象类型',
  `cb_receive_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '目标对象id',
  `cb_msg` text NOT NULL COMMENT '消息内容',
  `cb_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '消息发送时间',
  `cb_msg_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '消息类型',
  `cb_group_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '组id',
  UNIQUE KEY `cb_id` (`cb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
