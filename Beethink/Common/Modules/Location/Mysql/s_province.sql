DROP TABLE IF EXISTS `s_province`;

CREATE TABLE `s_province` (
  `ProvinceID` bigint(20) NOT NULL,
  `ProvinceName` varchar(50) DEFAULT NULL,
  `Recommend` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐 0=>否 1=>是',
  PRIMARY KEY (`ProvinceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `s_province` */

LOCK TABLES `s_province` WRITE;

insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (1,'北京市',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (2,'天津市',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (3,'河北省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (4,'山西省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (5,'内蒙古自治区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (6,'辽宁省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (7,'吉林省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (8,'黑龙江省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (9,'上海市',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (10,'江苏省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (11,'浙江省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (12,'安徽省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (13,'福建省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (14,'江西省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (15,'山东省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (16,'河南省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (17,'湖北省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (18,'湖南省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (19,'广东省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (20,'广西壮族自治区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (21,'海南省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (22,'重庆市',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (23,'四川省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (24,'贵州省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (25,'云南省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (26,'西藏自治区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (27,'陕西省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (28,'甘肃省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (29,'青海省',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (30,'宁夏回族自治区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (31,'新疆维吾尔自治区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (32,'香港特别行政区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (33,'澳门特别行政区',0);
insert  into `s_province`(`ProvinceID`,`ProvinceName`,`Recommend`) values (34,'台湾省',0);

UNLOCK TABLES;