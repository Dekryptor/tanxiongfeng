<?php
header('Content-Type:text/html;charset=utf-8');
session_start();
set_time_limit(10);
//基本配置
define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
define('APP_DEBUG',true);
define('NOW',$_SERVER['REQUEST_TIME']);
define('THINK_PATH','G:/www/beeAdmin/beethink/');
//实际项目特定配置
define('BOOK_ORDER',2);    //订座
define('DELIVER',1);        //外送
define('ORDER_MENU',6); //订餐
define('STORE',1);      //商家
define('CUSTOMER',2);   //用户
define('ACTIVITY',8);   //活动
define('GETCASH',7);       //提现
define('FILLCASH',8);      //充值
define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/beeAdmin/');

require THINK_PATH.(APP_DEBUG?'debug.php':'index.php');
?>
