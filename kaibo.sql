/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.5.8-log : Database - kaibo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kaibo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `kaibo`;

/*Table structure for table `tbl_admin_login_log` */

DROP TABLE IF EXISTS `tbl_admin_login_log`;

CREATE TABLE `tbl_admin_login_log` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员登录登出日志记录',
  `user_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `record_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '记录产生时间戳',
  `action` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1=>登录 2=>登出',
  `ip` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '登录ip地址',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_admin_login_log` */

insert  into `tbl_admin_login_log`(`id`,`user_id`,`record_time`,`action`,`ip`) values (1,2,1441065032,2,2130706433),(2,2,1441065099,2,2130706433);

/*Table structure for table `tbl_auth_group` */

DROP TABLE IF EXISTS `tbl_auth_group`;

CREATE TABLE `tbl_auth_group` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '组规则',
  `group_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '成员组id',
  `rules` varchar(120) NOT NULL DEFAULT '' COMMENT '规则ids     0=>表示拥有所有权限,适用于超级管理员',
  `g_rules` varchar(120) NOT NULL DEFAULT '' COMMENT '全局规则ids',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_auth_group` */

insert  into `tbl_auth_group`(`id`,`group_id`,`rules`,`g_rules`) values (1,3,'1,2','1,2'),(2,4,'3,4',''),(3,5,'5,6',''),(4,1,'1,2,3,4,5,6','1,2,3,5');

/*Table structure for table `tbl_global_rule` */

DROP TABLE IF EXISTS `tbl_global_rule`;

CREATE TABLE `tbl_global_rule` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '全局规则',
  `action` varchar(30) NOT NULL COMMENT '对应action 全小写',
  `module` varchar(30) NOT NULL COMMENT '对应module 全小写',
  `name` varchar(50) NOT NULL DEFAULT '0' COMMENT '名称',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_global_rule` */

/*Table structure for table `tbl_group` */

DROP TABLE IF EXISTS `tbl_group`;

CREATE TABLE `tbl_group` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '组类型',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '组名',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `pid` int(4) NOT NULL DEFAULT '0' COMMENT '对应父级id',
  `time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `inherit` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否继承上级规则 (0=>不继承 1=>继承)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_group` */

insert  into `tbl_group`(`id`,`type`,`name`,`description`,`status`,`pid`,`time`,`inherit`) values (3,0,'admin','顶级管理部门,有且只有一个',1,0,0,0),(4,0,'haha','',1,3,0,1),(5,0,'bee','',1,4,0,1),(1,0,'kaibo','测试部门',1,3,0,1);

/*Table structure for table `tbl_group_member` */

DROP TABLE IF EXISTS `tbl_group_member`;

CREATE TABLE `tbl_group_member` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '群组成员管理  一个成员只属于一个部门',
  `group_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '群组id',
  `mem_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '成员id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '可用状态',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_group_member` */

insert  into `tbl_group_member`(`id`,`group_id`,`mem_id`,`status`) values (1,1,2,1);

/*Table structure for table `tbl_image` */

DROP TABLE IF EXISTS `tbl_image`;

CREATE TABLE `tbl_image` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片管理',
  `url` varchar(200) NOT NULL COMMENT '图片地址',
  `w` float unsigned NOT NULL DEFAULT '0' COMMENT '宽',
  `h` float unsigned NOT NULL DEFAULT '0' COMMENT '高',
  `hash` char(32) NOT NULL DEFAULT '' COMMENT 'hash值',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_image` */

insert  into `tbl_image`(`id`,`url`,`w`,`h`,`hash`) values (1,'http://www.baidu.com',200,400,'lfkajslfkjalskjglkajsglkjl'),(2,'http://www.youku.com',400,200,'sladkjflaskglasdjlf');

/*Table structure for table `tbl_login_fail_log` */

DROP TABLE IF EXISTS `tbl_login_fail_log`;

CREATE TABLE `tbl_login_fail_log` (
  `id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '账号登录是败日志记录',
  `ip` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'ip 长整形',
  `record_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `username` varchar(50) NOT NULL COMMENT '登录使用的用户名',
  `psd` varchar(32) NOT NULL COMMENT '登录使用的密码,未使用hash加密'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_login_fail_log` */

insert  into `tbl_login_fail_log`(`id`,`ip`,`record_time`,`username`,`psd`) values (0,2130706433,1439597804,'bee','1231421'),(0,2130706433,1439683483,'daf','asdf'),(0,2130706433,1439683497,'sdfs','sdfs'),(0,2130706433,1439683516,'asdf','sadf'),(0,2130706433,1439683690,'asdf','asdf'),(0,2130706433,1439683720,'asdf','asdf'),(0,2130706433,1439683806,'asdf','asdf'),(0,2130706433,1439683818,'asdf','asdf'),(0,2130706433,1439684692,'asdf','asdf'),(0,2130706433,1439684848,'sda','dasdf'),(0,2130706433,1439685187,'dsaf','asdf'),(0,2130706433,1439686010,'asdasd','asdas'),(0,2130706433,1439686075,'asd','asdasd'),(0,2130706433,1439686584,'asdf','asdf'),(0,2130706433,1439686648,'asdf','asdf'),(0,2130706433,1439686675,'alsdkjf','sdlkfjal'),(0,2130706433,1439686715,'asdfas','asdasd'),(0,2130706433,1439686936,'sadf','asdf'),(0,2130706433,1439686963,'asdf','asdf'),(0,2130706433,1439687071,'asdf','asdf'),(0,2130706433,1439687156,'dsf','asdf'),(0,2130706433,1439687170,'dsff','asdfd'),(0,2130706433,1440199889,'asdasd','asdasd'),(0,2130706433,1440286118,'123','fsdf');

/*Table structure for table `tbl_member` */

DROP TABLE IF EXISTS `tbl_member`;

CREATE TABLE `tbl_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员表';

/*Data for the table `tbl_member` */

/*Table structure for table `tbl_menu` */

DROP TABLE IF EXISTS `tbl_menu`;

CREATE TABLE `tbl_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0=>不可用 1=>可用',
  `class` varchar(30) NOT NULL DEFAULT '' COMMENT '相应class 名,用于样式',
  `action` varchar(30) NOT NULL COMMENT 'Action名',
  `module` varchar(30) NOT NULL COMMENT 'Module名',
  `icon` varchar(30) NOT NULL DEFAULT '' COMMENT '对应icon 如 fa fa-home',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_menu` */

insert  into `tbl_menu`(`id`,`title`,`pid`,`sort`,`url`,`hide`,`tip`,`group`,`is_dev`,`status`,`class`,`action`,`module`,`icon`) values (1,'首页',0,0,'test1.php',0,'','',0,1,'','','',''),(2,'用户资料',1,0,'test2.php',0,'','',0,1,'','','',''),(3,'修改用户资料',1,0,'test3.php',0,'','',0,1,'','','',''),(4,'查看用户列表',1,0,'test4.php',0,'','',0,1,'','test','user',''),(5,'test1',1,0,'test5.php',0,'','',0,1,'','','',''),(6,'test2',2,0,'test6.php',0,'','',0,1,'','','',''),(7,'test3',3,0,'test7.php',0,'','',0,1,'','','',''),(8,'test6',4,0,'test8.php',0,'','',0,1,'','','',''),(9,'test7',5,0,'test9.php',0,'','',0,1,'','','','');

/*Table structure for table `tbl_menu_group` */

DROP TABLE IF EXISTS `tbl_menu_group`;

CREATE TABLE `tbl_menu_group` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单组管理',
  `name` varchar(50) NOT NULL COMMENT '组名',
  `href` varchar(100) NOT NULL COMMENT '链接地址',
  `order` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '序列',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_menu_group` */

/*Table structure for table `tbl_order_baseinfo` */

DROP TABLE IF EXISTS `tbl_order_baseinfo`;

CREATE TABLE `tbl_order_baseinfo` (
  `order_num` char(20) NOT NULL COMMENT '订单号',
  `time` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `user_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态(处理状态)',
  `store_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `total_price` float unsigned NOT NULL DEFAULT '0' COMMENT '总价格',
  `payment_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态  0=>未支付 1=>已支付'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_baseinfo` */

insert  into `tbl_order_baseinfo`(`order_num`,`time`,`user_id`,`status`,`store_id`,`total_price`,`payment_status`) values ('1108180000008',0,1,0,1,0,0),('1108180000013',1439855226,1,0,1,0,0),('1108190000001',1439985058,1,0,1,0,0),('1108190000001',1439985096,1,0,1,0,0),('1108190000001',1439985177,1,0,1,0,0),('1108190000001',1439985178,1,0,1,0,0),('1108190000001',1439990856,1,0,1,0,0),('1108190000001',1439992782,1,0,1,0,0),('1108190000001',1439992782,1,0,1,0,0),('1108190000001',1439992796,1,0,1,0,0),('1108190000001',1439992830,1,0,1,0,0),('1108190000001',1439992852,1,0,1,0,0),('1108190000001',1439992959,1,0,1,0,0),('1108190000001',1439992987,1,0,1,0,0),('1108190000001',1439993014,1,0,1,0,0),('1108190000001',1439993032,1,0,1,0,0),('1108190000001',1439993488,1,0,1,0,0),('1108190000001',1439993555,1,0,1,0,0),('1108190000001',1439993597,1,0,1,0,0),('1108190000036',1439993644,1,0,1,23.5,0),('1108190000037',1439993708,1,0,1,23.5,0),('1108190000038',1439996359,1,0,1,23.5,0),('1108190000039',1439996659,1,0,1,23.5,0),('1108190000040',1439996683,1,0,1,23.5,0),('1108190000041',1439996721,1,0,1,23.5,0),('1108190000042',1439996754,1,0,1,23.5,0),('1108190000043',1439996763,1,0,1,23.5,0);

/*Table structure for table `tbl_order_date_static` */

DROP TABLE IF EXISTS `tbl_order_date_static`;

CREATE TABLE `tbl_order_date_static` (
  `date` char(8) NOT NULL COMMENT '记录每天订单数统计',
  `num` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_date_static` */

insert  into `tbl_order_date_static`(`date`,`num`) values ('20150818',38),('20150819',42),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',30),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9),('20150819',9);

/*Table structure for table `tbl_order_delivery` */

DROP TABLE IF EXISTS `tbl_order_delivery`;

CREATE TABLE `tbl_order_delivery` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单收获信息',
  `order_num` char(20) NOT NULL COMMENT '订单号',
  `username` varchar(40) NOT NULL COMMENT '收货人姓名',
  `tel` char(14) NOT NULL COMMENT '联系方式',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '详细地址',
  `zip_code` char(6) NOT NULL DEFAULT '' COMMENT '邮编地址',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_delivery` */

insert  into `tbl_order_delivery`(`id`,`order_num`,`username`,`tel`,`address`,`zip_code`) values (1,'1108190000038','bee','18224087281','四川省成都市青羊区',''),(2,'1108190000039','bee','18224087281','四川省成都市青羊区',''),(3,'1108190000040','bee','18224087281','四川省成都市青羊区',''),(4,'1108190000041','bee','18224087281','四川省成都市青羊区',''),(5,'1108190000042','bee','18224087281','四川省成都市青羊区',''),(6,'1108190000043','bee','18224087281','四川省成都市青羊区','');

/*Table structure for table `tbl_order_goods` */

DROP TABLE IF EXISTS `tbl_order_goods`;

CREATE TABLE `tbl_order_goods` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单详情',
  `goods_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '产品id',
  `goods_img` varchar(100) NOT NULL DEFAULT '' COMMENT '产品图片地址',
  `price` float unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  `discount_price` float unsigned NOT NULL DEFAULT '0' COMMENT '折后价',
  `count` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `total_price` float unsigned NOT NULL DEFAULT '0' COMMENT '总价',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '产品名',
  `order_num` char(20) NOT NULL COMMENT '订单号',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_goods` */

insert  into `tbl_order_goods`(`id`,`goods_id`,`goods_img`,`price`,`discount_price`,`count`,`total_price`,`name`,`order_num`) values (1,1,'',0,0,3,0,'','1108190000001'),(2,2,'',0,0,1,0,'','1108190000001'),(3,1,'',0,0,3,0,'','1108190000001'),(4,2,'',0,0,1,0,'','1108190000001'),(5,1,'',0,0,3,0,'','1108190000001'),(6,2,'',0,0,1,0,'','1108190000001'),(7,1,'',0,0,3,0,'','1108190000001'),(8,2,'',0,0,1,0,'','1108190000001'),(9,1,'',0,0,3,0,'','1108190000001'),(10,2,'',0,0,1,0,'','1108190000001'),(11,1,'',0,0,3,0,'','1108190000001'),(12,2,'',0,0,1,0,'','1108190000001'),(13,1,'',0,0,3,0,'','1108190000001'),(14,2,'',0,0,1,0,'','1108190000001'),(15,1,'',0,0,3,0,'','1108190000001'),(16,2,'',0,0,1,0,'','1108190000001'),(17,1,'',0,0,3,0,'','1108190000001'),(18,2,'',0,0,1,0,'','1108190000001'),(19,1,'',0,0,3,0,'','1108190000001'),(20,2,'',0,0,1,0,'','1108190000001'),(21,1,'',0,0,3,0,'','1108190000001'),(22,2,'',0,0,1,0,'','1108190000001'),(23,1,'',0,0,3,0,'','1108190000001'),(24,2,'',0,0,1,0,'','1108190000001'),(25,1,'',0,0,3,0,'','1108190000001'),(26,2,'',0,0,1,0,'','1108190000001'),(27,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000036'),(28,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000036'),(29,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000037'),(30,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000037'),(31,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000038'),(32,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000038'),(33,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000039'),(34,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000039'),(35,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000040'),(36,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000040'),(37,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000041'),(38,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000041'),(39,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000042'),(40,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000042'),(41,1,'http://www.youku.com',4.5,0,3,13.5,'康师傅老谭方便面','1108190000043'),(42,2,'http://www.baidu.com',10,0,1,10,'星巴克咖啡','1108190000043');

/*Table structure for table `tbl_order_payment` */

DROP TABLE IF EXISTS `tbl_order_payment`;

CREATE TABLE `tbl_order_payment` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单支付情况 一个订单可以有多条记录',
  `order_num` char(20) NOT NULL COMMENT '订单号',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式 1=>现金券 2=>积分 3=>代金券 4=>银联 5=>支付宝 ...',
  `price` float unsigned NOT NULL DEFAULT '0' COMMENT '支付金额',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_payment` */

/*Table structure for table `tbl_order_remark` */

DROP TABLE IF EXISTS `tbl_order_remark`;

CREATE TABLE `tbl_order_remark` (
  `order_num` char(20) NOT NULL COMMENT '订单号  订单的额外信息',
  `remarks` varchar(200) NOT NULL DEFAULT '' COMMENT '订单备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_remark` */

insert  into `tbl_order_remark`(`order_num`,`remarks`) values ('1108190000043','this is a remark');

/*Table structure for table `tbl_order_status` */

DROP TABLE IF EXISTS `tbl_order_status`;

CREATE TABLE `tbl_order_status` (
  `status` smallint(3) unsigned NOT NULL COMMENT '订单状态参照表',
  `for_store` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '对应商家状态描述',
  `for_user` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '对应用户描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_order_status` */

/*Table structure for table `tbl_order_trace` */

DROP TABLE IF EXISTS `tbl_order_trace`;

CREATE TABLE `tbl_order_trace` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单状态跟踪',
  `order_num` char(20) NOT NULL COMMENT '订单号',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `memo` varchar(120) NOT NULL COMMENT '描述',
  `time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '当前时间戳',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_trace` */

/*Table structure for table `tbl_store_goods` */

DROP TABLE IF EXISTS `tbl_store_goods`;

CREATE TABLE `tbl_store_goods` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '商家商品',
  `store_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `name` varchar(100) NOT NULL COMMENT '商品名',
  `banner` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '商品banner   对应image.id',
  `logo` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '商品logo    对应image.id',
  `price` float unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  `discount_price` float unsigned NOT NULL DEFAULT '0' COMMENT '折后价',
  `describe` varchar(100) NOT NULL DEFAULT '0' COMMENT '商品描述',
  `star` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '星级 1-5 默认为3',
  `comments` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为推荐商品    0=>否 1=>是',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否有效 0=>无效 1=>有效',
  `sales` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_store_goods` */

insert  into `tbl_store_goods`(`id`,`store_id`,`name`,`banner`,`logo`,`price`,`discount_price`,`describe`,`star`,`comments`,`is_hot`,`status`,`sales`) values (1,1,'康师傅老谭方便面',1,2,4.5,0,'酸菜系列',3,1,0,1,0),(2,1,'星巴克咖啡',2,1,10,0,'星巴克',3,0,0,1,0);

/*Table structure for table `tbl_store_order_static` */

DROP TABLE IF EXISTS `tbl_store_order_static`;

CREATE TABLE `tbl_store_order_static` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '商家日期订单数量统计',
  `store_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `date` char(8) NOT NULL COMMENT '日期 20150412',
  `num` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_store_order_static` */

insert  into `tbl_store_order_static`(`id`,`store_id`,`date`,`num`) values (1,1,'20150819',8);

/*Table structure for table `tbl_sys_message` */

DROP TABLE IF EXISTS `tbl_sys_message`;

CREATE TABLE `tbl_sys_message` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统消息',
  `from_user_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '消息来自',
  `user_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型 ( 1=>普通用户 2=>商家)',
  `receiver_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '接收者id',
  `receiver_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '接收者类型',
  `time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  `content` varchar(200) NOT NULL DEFAULT '' COMMENT '接收的内容',
  `img_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '图片',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '查看状态 0=>未查看 1=>已经查看',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_sys_message` */

/*Table structure for table `tbl_ucenter_member` */

DROP TABLE IF EXISTS `tbl_ucenter_member`;

CREATE TABLE `tbl_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  `group_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '所属组的id',
  `login_err_times` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '登陆错误次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

/*Data for the table `tbl_ucenter_member` */

insert  into `tbl_ucenter_member`(`id`,`username`,`password`,`email`,`mobile`,`reg_time`,`reg_ip`,`last_login_time`,`last_login_ip`,`update_time`,`status`,`group_id`,`login_err_times`) values (2,'bee','e10adc3949ba59abbe56e057f20f883e','','',0,0,1441065135,2130706433,0,1,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
