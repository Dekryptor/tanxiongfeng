<?php
/**
 * 定义基本配置信息
 */ 
$CONF=array(
    //自定义文件加载
    'load_ext_config'=>'',                                  //多个文件以逗号隔开,file1,file2,file3 自动补全.php
    //cookie 设置
    'cookie_expire'=>3600,                                  //有效时间
    'cookie_domain'=>'',                                    //有效域名
    'cookie_path'=>'/',                                     //cookie 路径
    //默认设定
    'charset'=>'utf8',
    'contenttype'=>'text/html',
    'timezone'=>'PRC',                                      //定义时间区域 
    //自动加载文件
    'autoload'=>array(),
    'basefile'=>array(                                      //自定义基础文件列表加载
         CORE_PATH.'Db/Driver/DbMysqli.class.php',       //mysql驱动
         CORE_PATH.'App.class.php',                //页面控制器驱动
         CORE_PATH.'Model.class.php',                 //model驱动
         CORE_PATH.'Error.class.php',                 //错误控制器
         CORE_PATH.'View.class.php',                  //模板解析驱动
         CORE_PATH.'Sys.class.php',                   //
         CORE_PATH.'Loadfile.class.php',                //文件依赖自动导入
         CORE_PATH.'Rewrite.class.php'                //路由控制  
    ),                                    
    //数据库配置   
    'db'=>array(
        'dbtype'=>'DbMysqli',
        'host'=>'localhost',
        'port'=>3306,
        'uname'=>'root',                                    //用户名
        'psd'=>'',
        'dbname'=>'test',                                   //库名
        'charset'=>'utf8', 
        'type'=>1                                           //连接方式 0=>connect 1=>pconnect
    ),
    'url'=>array(
        'dep'=>'/',                                         //参数分隔符   index.php/Index/index/name/bee
        'suffix'=>'',                                       //URL伪静态后缀 .html
    ),
    /*错误处理*/
    'error_msg'=>'您浏览的页面暂时发生错误!请稍后再试',
    'error_page'=>'',                                       //错误定向页面
    'show_error_msg'=>false,                                //显示错误信息
    /*模板引擎设置*/
    'tpl_charset'=>'utf-8',
    'tpl_content_type'=>'text/html',                        //默认输出类型
    'tpl_action_error'=>APP_PATH.'Tpl/dispathch_jump.tpl', //默认错误跳转
    'tpl_action_success'=>APP_PATH.'Tpl/dispath_jump.tpl', //默认成功跳转
    'tpl_exception_page'=>APP_PATH.'Tpl/exception.tpl',    //异常页面跳转
    'tpl_template_suffix'=>'.html',                         //默认模板后缀名
    'tpl_cache_on'=>true,                                  //是否开启页面缓存
    'tpl_cache_time'=>0,                                    //设置页面缓存事件 在开始页面缓存后有效
    'tpl_cache_suffix'=>'.html',
    'rewrite_module'=>'normal',                             //重写模式
    'data_auth_key'=>'hello bee'                            //数据加密密钥
);

?>