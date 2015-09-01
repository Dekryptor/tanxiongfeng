<?php
/**
 * 基本常量定义
 */
$DEFINE_ARR=array(
  'CORE_PATH'=>THINK_PATH.'Base/',  //系统核心类库目录
  'TPL_PATH'=>APP_PATH.'Tpl/',      //项目模板目录
  'CONF_PATH'=>APP_PATH.'Conf/',    // 项目配置目录
  'RUNTIME_PATH'=>APP_PATH.'Runtime/',                      //项目缓存目录
  'RUNTIME_DEFINE_FILE'=>APP_PATH.'Runtime/Define/define.php',  //定义常量文件
  'RUNTIME_FUNC_FILE'=>APP_PATH.'Runtime/Func/func.php',        //函数文件
  'RUNTIME_CLASS_FILE'=>APP_PATH.'Runtime/Class/class.php',     //类文件
  'RUNTIME_CONF_FILE'=>APP_PATH.'Runtime/Conf/conf.php',        //配置文件
  'LIB_PATH'=>APP_PATH.'Lib/',
  'ACTION_PATH'=>APP_PATH.'Lib/Action/',   //action目录
  'MODULE_PATH'=>APP_PATH.'Lib/Model/',   //model目录
  'AJAX_PATH'=>APP_PATH.'Lib/Ajax/',       //ajax
  'COMMON_PATH'=>APP_PATH.'Common/',       //项目公共目录       
  'APP_SYSTEM'=>PATH_SEPARATOR==';'?'win':'linux',       //当前系统类型
  //错误号
  //常见页面错误信息
  'PAGE_NOTFOUND'=>404,       //页面未找到
  'PAGE_FORBID'=>403,         //服务不可用
  //数据库错误
  'QUERY_ERROR'=>501,         //查询语句错误
  'CONNECT_FAIL'=>502,        //数据库连接错误
  //类方面错误
  'CLASS_NOTFOUND'=>601,      //类不存在
  'METHOD_NOTFOUND'=>602,     //方法名不存在
  //文件相关
  'FILE_NOTFOUND'=>701,        //文件不存在
  'FORMAT_ERROR'=>555,         //格式错误
  'SUCCESS'=>0,                 //操作成功
  'FAIL'=>10006,                 //操作失败
  'LOGIN_FAIL'=>10001            //重新登录
);
?>