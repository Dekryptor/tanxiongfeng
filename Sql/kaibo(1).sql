/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.6.25 : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`test` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `test`;

/*Table structure for table `app_info` */

DROP TABLE IF EXISTS `app_info`;

CREATE TABLE `app_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) NOT NULL COMMENT '应用包名',
  `app_name` varchar(255) NOT NULL COMMENT '应用名',
  `logo` varchar(255) DEFAULT NULL COMMENT '应用的图标URL',
  `banner` varchar(255) DEFAULT NULL COMMENT '应用的横幅图片URL',
  `cover` varchar(255) DEFAULT NULL COMMENT '应用的封面图片URL',
  `description` varchar(255) DEFAULT NULL COMMENT '应用描述',
  `download` varchar(255) DEFAULT NULL COMMENT '应用下载量',
  `category` varchar(255) DEFAULT NULL COMMENT '应用分类名',
  `rating` double(10,2) unsigned DEFAULT NULL COMMENT '应用评分',
  `review_count` int(11) DEFAULT NULL COMMENT '应用评论数',
  `package_size` varchar(32) DEFAULT NULL COMMENT '应用的包大小',
  `url` text NOT NULL COMMENT '应用下载地址',
  `country` text COMMENT '应用投放国家，形如|US|CN|GB|',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用类型，0-GP，1-APK',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '应用系列任务的总开关，0-任务关闭，1-任务开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='记录应用信息';

/*Data for the table `app_info` */

insert  into `app_info`(`id`,`package_name`,`app_name`,`logo`,`banner`,`cover`,`description`,`download`,`category`,`rating`,`review_count`,`package_size`,`url`,`country`,`type`,`active`) values (1,'com.shere.easytouch','AnastasiaDate','http://cdn.avazutracking.net/images/201505/052/c3983677f7cca99941b396f2dd745cf7_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'9.1M','http://apx.avazutracking.net/iclk/redirect.php?id=eWeumToXD3xMgTeHKToQgTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AU|CA|CH|DK|FI|GB|NO|NZ|SE|US|',0,1),(2,'com.bandainamcogames.outcast','Outcast Odyssey','http://cdn.avazutracking.net/images/201505/052/2e9b4e6f113e39de87e29865efff575e_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'93M','http://apx.avazutracking.net/iclk/redirect.php?id=eT9RmNJXD3xMgT2aeT9QgTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|CA|DK|IS|NL|NO|SE|TW|US|ZA|',0,1),(3,'com.ijinshan.kbatterydoctor_en','Battery Doctor (Battery Saver)','http://cdn.avazutracking.net/images/201506/052/33f56db4fa7bea8958cd2901cc76e365_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,NULL,'http://apx.avazutracking.net/iclk/redirect.php?id=eT8HeNoXD3xMgTe5KWeHgTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','AC|AD|AF|AG|AI|AL|AM|AO|AQ|AR|AS|AT|AU|AW|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BJ|BM|BN|BO|BQ|BR|BS|BT|BV|BW|BY|BZ|CA|CC|CD|CF|CG|CI|CK|CL|CM|CO|CR|CS|CU|CV|CW|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EE|EG|ER|ET|EU|FI|FJ|FK|FO|FR|FX|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GP|GQ|GR|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|IL|IM|IN|IO|IQ|IR|IS|IT|JE|JM|JO|JP|KE|KG|KH|KI|KM|KP|KR|KW|KY|KZ|LA|LB|LI|LR|LS|LT|LV|LY|MK|PF|SV|TD|TF|TL',0,1),(4,'air.com.sgn.juicejam.gp','Juice Jam','http://cdn.avazutracking.net/images/201505/052/68f53d3661bcab5cc34decc4f5a2ca9e_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'40M','http://apx.avazutracking.net/iclk/redirect.php?id=mTerK3jMIWuXeW45mNJXD3xMgT4QKUGa&trafficsourceid=15749','|CA|GB|IE|NZ|US|ZA|',0,1),(5,'com.leo.appmaster','LEO Privacy Guard','http://cdn.avazutracking.net/images/201504/052/7d4e378f60ceb549f59346157e76d3b0_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'3.5M','http://apx.avazutracking.net/iclk/redirect.php?id=eTonKU9XD3xMgTeReUbngTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|ID|IN|IR|PH|TH|VN|',0,1),(6,'com.gameturn.aow','Vikings - Age of Warlords','http://cdn.avazutracking.net/images/201507/052/aec671a9d3c0a26ce79a6196b60bbfc5_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'48M','http://apx.avazutracking.net/iclk/redirect.php?id=eWeuKT4XD3xMgTeHKTGHgTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AT|BG|BR|BY|CZ|HR|HU|ID|IE|IL|LT|MY|NO|PH|PL|RO|RS|SA|SI|SK|TH|TR|UA|',0,1),(7,'com.snailgameusa.tp','Taichi Panda','http://cdn.avazutracking.net/images/201503/052/be9bcd15288022f4985f00cef67e4778_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'42M','http://apx.avazutracking.net/iclk/redirect.php?id=eTo0KUoXD3xMgTeRmTe0gTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AT|AU|CA|CH|DE|DK|FR|GB|NO|NZ|SE|US|',0,1),(8,'com.igg.android.marbleheroes','Marble Heroes','http://cdn.avazutracking.net/images/201507/052/456f3bb7f3fa583debe6406caf6011ec_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'41M','http://apx.avazutracking.net/iclk/redirect.php?id=eW2QmTjXD3xMgTeQKW2rgTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AU|CH|DK|GB|NL|NO|NZ|SE|SG|US|',0,1),(9,'com.ne.hdv','HD Video Downloader','http://cdn.avazutracking.net/images/201506/052/aacf92a73b8a357b22e95150d441fa9f_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'1.4M','http://apx.avazutracking.net/iclk/redirect.php?id=eW4neN2XD3xMgTeueUoagTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AU|CA|GB|IE|NZ|US|',0,1),(10,'net.ym.overseas.intl.cashme','CashMe Rewards','http://cdn.avazutracking.net/images/201506/052/5e6b312da1b89fea39bc86d1b52e0659_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'6.7M','http://apx.avazutracking.net/iclk/redirect.php?id=eUbnmTeXD3xMgTGUeUergTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|ID|MY|SG|TW|',0,1),(11,'com.mediocre.smashhit','Smash Hit','http://cdn.avazutracking.net/images/201506/052/7a3b467285e69cbad94d89b75d0d7803_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'49M','http://apx.avazutracking.net/iclk/redirect.php?id=eUb5KUJXD3xMgTGUeW4agTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AU|FI|GB|IE|NL|NO|NZ|SE|',0,1),(12,'com.wego.android','Wego Flights & Hotels','http://cdn.avazutracking.net/images/201503/052/1195e5d03dac5f652dc89fd31b4aa29a_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'9.0M','http://apx.avazutracking.net/iclk/redirect.php?id=eWoReTGXD3xMgTGReU2agTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|SA|SG|',0,1),(13,'com.wagawin.android2','Wagawin','http://cdn.avazutracking.net/images/201505/052/10991bff321b808263cb829743f427d9_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'28M','http://apx.avazutracking.net/iclk/redirect.php?id=eTouKU2XD3xMgTeRKWe5gTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AT|CH|DE|',0,1),(14,'com.takatap.rewards','Takatap','http://cdn.avazutracking.net/images/201505/052/2e4e2dd2c76a01cba682aee8a61f1dd1_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,NULL,'http://apx.avazutracking.net/iclk/redirect.php?id=eT90KNoXD3xMgT2aKW8ngTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AC|AD|AE|AF|AG|AI|AL|AM|AN|AO|AQ|AS|AU|AW|AX|AZ|BA|BB|BD|BG|BH|BI|BL|BM|BN|BR|BS|BT|BV|BW|BZ|CA|CC|CD|CG|CI|CK|CM|CS|CV|CX|CY|CZ|DK|DM|DZ|EE|EG|EH|ER|ET|FI|FJ|FK|FM|FO|GB|GD|GE|GG|GH|GI|GL|GM|GN|GR|GS|GU|GW|GY|HM|HR|HU|ID|IE|IL|IM|IN|IO|IQ|IR|IS|JE|JM|JO|JP|KE|KH|KI|KM|KN|KP|KR|KW|KY|LA|LB|LC|LK|LR|LS|LT|LV|LY|MA|MD|ME|MF|MH|MK|MM|MN|MO|MP|MR|MS|MT|MU|MV|MW|MY|MZ|NA|NF|NG|NL|NO|NP|NR|UNKNOWN_CTR|',0,1),(15,'com.UCMobile.intl','UC Browser - Surf it Fast','http://cdn.avazutracking.net/images/201501/052/861437daf1d89be46814b0770c0daa5d_100x100.png',NULL,NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,NULL,'http://apx.avazutracking.net/iclk/redirect.php?id=eToQeWJXD3xMgTeRKWJHgTuwD3jnKT8umG-0N-0N&trafficsourceid=15749','|AR|BO|BR|BY|CO|DZ|EG|GH|GT|KR|KW|KZ|LK|MX|NG|NP|OM|QA|SG|SV|TH|TR|UZ|ZA|',0,1),(16,'com.abtnprojects.ambatana','Ambatana: Sell & Buy Stuff','http://cdn.castplatform.com/images/cba5bffb-1545-4dc6-8e23-d97c078c50f1.jpg','http://cdn.castplatform.com/images/bccd1673-6aae-4ee7-a279-f883306e8969.jpg',NULL,'Download and run 1 minute.',NULL,NULL,NULL,NULL,'8.5M','http://media.yemonisoni.com/get?t=s&aff_id=26290&packageName=com.lionmobi.powerclean&id=102047&fos=Android&uts=1437020104','|US|',0,1);

/*Table structure for table `tbl_admin_login_log` */

DROP TABLE IF EXISTS `tbl_admin_login_log`;

CREATE TABLE `tbl_admin_login_log` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员登录登出日志记录',
  `user_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `record_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '记录产生时间戳',
  `action` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1=>登录 2=>登出',
  `ip` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '登录ip地址',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_admin_login_log` */

/*Table structure for table `tbl_auth_group` */

DROP TABLE IF EXISTS `tbl_auth_group`;

CREATE TABLE `tbl_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `g_rules` varchar(120) NOT NULL DEFAULT '' COMMENT '对应的全局规则id串',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_auth_group` */

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

/*Table structure for table `tbl_image` */

DROP TABLE IF EXISTS `tbl_image`;

CREATE TABLE `tbl_image` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片集合',
  `url` varchar(200) NOT NULL COMMENT '地址',
  `w` float unsigned NOT NULL DEFAULT '0' COMMENT '宽 ',
  `h` float unsigned NOT NULL DEFAULT '0' COMMENT '高',
  `hash` char(32) NOT NULL DEFAULT '' COMMENT 'hash值',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_image` */

/*Table structure for table `tbl_login_fail_log` */

DROP TABLE IF EXISTS `tbl_login_fail_log`;

CREATE TABLE `tbl_login_fail_log` (
  `id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '账号登录是败日志记录',
  `ip` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'ip 长整形',
  `record_time` int(4) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `username` varchar(50) NOT NULL COMMENT '登录使用的用户名',
  `psd` varchar(32) NOT NULL COMMENT '登录使用的密码,未使用hash加密'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_login_fail_log` */

insert  into `tbl_login_fail_log`(`id`,`ip`,`record_time`,`username`,`psd`) values (0,2130706433,1439529756,'bee','3216464644654'),(0,2130706433,1439530207,'bee','3216464644654'),(0,2130706433,1439530436,'bee','3216464644654');

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
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `module` varchar(30) NOT NULL COMMENT 'module名  全小写',
  `action` varchar(30) NOT NULL COMMENT 'action名   全小写',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_menu` */

insert  into `tbl_menu`(`id`,`title`,`pid`,`sort`,`url`,`hide`,`tip`,`group`,`is_dev`,`status`,`module`,`action`) values (1,'首页',0,0,'test1.php',0,'','',0,0,'',''),(2,'用户资料',1,0,'test2.php',0,'','',0,0,'',''),(3,'修改用户资料',1,0,'test3.php',0,'','',0,0,'',''),(4,'查看用户列表',1,0,'test4.php',0,'','',0,0,'',''),(5,'test1',1,0,'test5.php',0,'','',0,0,'',''),(6,'test2',2,0,'test6.php',0,'','',0,0,'',''),(7,'test3',3,0,'test7.php',0,'','',0,0,'',''),(8,'test6',4,0,'test8.php',0,'','',0,0,'',''),(9,'test7',5,0,'test9.php',0,'','',0,0,'','');

/*Table structure for table `tbl_order_baseinfo` */

DROP TABLE IF EXISTS `tbl_order_baseinfo`;

CREATE TABLE `tbl_order_baseinfo` (
  `id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单基本信息',
  `user_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `store_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `order_num` char(20) NOT NULL COMMENT '订单号',
  `rec_time` int(4) NOT NULL DEFAULT '0' COMMENT '时间戳',
  `price` float unsigned NOT NULL DEFAULT '0' COMMENT '总价',
  `discount_price` float unsigned NOT NULL DEFAULT '0' COMMENT '折后价'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_baseinfo` */

/*Table structure for table `tbl_order_date_static` */

DROP TABLE IF EXISTS `tbl_order_date_static`;

CREATE TABLE `tbl_order_date_static` (
  `date` char(8) NOT NULL COMMENT '记录每天订单数统计',
  `num` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_date_static` */

/*Table structure for table `tbl_order_delivery` */

DROP TABLE IF EXISTS `tbl_order_delivery`;

CREATE TABLE `tbl_order_delivery` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单收获信息',
  `order_num` char(20) NOT NULL COMMENT '订单号',
  `username` varchar(40) NOT NULL COMMENT '收货人姓名',
  `tel` char(14) NOT NULL COMMENT '联系方式',
  `method` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '付款方式',
  `payment_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '付款状态',
  `describe` varchar(100) NOT NULL DEFAULT '' COMMENT '订单额外描述内容',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_delivery` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_order_goods` */

/*Table structure for table `tbl_order_status` */

DROP TABLE IF EXISTS `tbl_order_status`;

CREATE TABLE `tbl_order_status` (
  `status` smallint(3) unsigned NOT NULL COMMENT '订单状态参照表',
  `for_store` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '对应商家状态描述',
  `for_user` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '对应用户描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_order_status` */

insert  into `tbl_order_status`(`status`,`for_store`,`for_user`) values (1,'????',''),(2,'????',''),(3,'????','');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_store_goods` */

/*Table structure for table `tbl_store_order_static` */

DROP TABLE IF EXISTS `tbl_store_order_static`;

CREATE TABLE `tbl_store_order_static` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '商家日期订单数量统计',
  `store_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `date` char(8) NOT NULL COMMENT '日期 20150412',
  `num` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_store_order_static` */

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
  `status` tinyint(4) DEFAULT '1' COMMENT '账户状态  0=>无效 1=>有效  ',
  `login_err_times` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '连续错误登录次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

/*Data for the table `tbl_ucenter_member` */

insert  into `tbl_ucenter_member`(`id`,`username`,`password`,`email`,`mobile`,`reg_time`,`reg_ip`,`last_login_time`,`last_login_ip`,`update_time`,`status`,`login_err_times`) values (2,'bee','e10adc3949ba59abbe56e057f20f883e','','',0,0,1439535040,2130706433,0,1,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
