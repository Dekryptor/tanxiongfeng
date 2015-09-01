<?php
/*基本常量定义*/
$DEFINE_ARRAY=array(
  'CORE_PATH'=>CORE_PATH.'Base/',  //系统核心类库目录
  'TPL_PATH'=>APP_PATH.'Tpl/',      //项目模板目录
  'CONF_PATH'=>APP_PATH.'Conf/',    // 项目配置目录
  'TMP_PATH'=>APP_PATH.'Tmp/',      //项目缓存目录      
  'CACHE_PATH'=>APP_PATH.'Tmp/Cache/', //项目模板缓存目录
  'ACTION_PATH'=>APP_PATH.'Action/',   //action目录
  'COMMON_PATH'=>APP_PATH.'Common/',   // 项目公共目录       
  'RUNTIME_DEF_FILE'=>APP_PATH.'Tmp/Data/define.php',   //常量临时缓存目录
  'RUNTIME_DATA_FILE'=>APP_PATH.'Tmp/Data/data.php',
  'RUNTIME_CLASS_FILE'=>APP_PATH.'Tmp/Class/class.php',
  'APP_SYSTEM'=>PATH_SEPARATOR==';'?'win':'linux'       //当前系统类型
);

?>