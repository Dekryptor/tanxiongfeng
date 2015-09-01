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
/*Table structure for table `tbl_chat_message` */

DROP TABLE IF EXISTS `tbl_chat_message`;

CREATE TABLE `tbl_chat_message` (
  `cm_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '聊天内容',
  `cm_content` text NOT NULL COMMENT '内容',
  `cm_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `cm_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '消息类型（10 文本，11:表情url,20图片，21语音，22.其他文件，30好友请求，31群请求, 41 删除好友 51 退群 61 好友引荐）',
  UNIQUE KEY `cm_id` (`cm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
