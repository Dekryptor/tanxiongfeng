CREATE TABLE `tbl_customer` (
   `c_id` int(11) NOT NULL AUTO_INCREMENT,
   `c_nicname` varchar(50) NOT NULL COMMENT '�ǳ�',
   `c_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '�û����� 0=>��ͨ�û�',
   `c_psd` char(32) NOT NULL COMMENT '����',
   `c_phone` char(11) NOT NULL COMMENT '�ֻ���',
   `c_time` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'ע��ʱ��',
   `c_sex` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '�Ա� 0=>�� 1=>Ů',
   PRIMARY KEY (`c_id`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8