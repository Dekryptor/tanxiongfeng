CREATE TABLE `tbl_customer` (
   `c_id` int(11) NOT NULL AUTO_INCREMENT,
   `c_nicname` varchar(50) NOT NULL COMMENT '昵称',
   `c_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型 0=>普通用户',
   `c_psd` char(32) NOT NULL COMMENT '密码',
   `c_phone` char(11) NOT NULL COMMENT '手机号',
   `c_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
   `c_sex` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '性别 0=>男 1=>女',
   PRIMARY KEY (`c_id`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8