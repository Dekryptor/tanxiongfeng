<?php
/**
 * 
 * 功能:
 *  1.自动创建文件目录
 *  2.文件压缩
 *  3.常量定义
 *  4.配置信息
 * 
 */
class Init
{
    /**
     * 入口
     */
    public static function _init()
    {
        self::initDefine();
        self::initApp();
        self::initConf();
        self::initRewrite();
        self::initBaseFile();
        self::initCustom();     //加载并缓存用户自定义文件
        self::initAction();
        self::initHtaccess();
        self::initException();
        
        self::cache();
    }
    /**
     * 常量定义
     */
    public static function initDefine()
    {
        require(THINK_PATH.'Common/Define/define.php');
       
        foreach($DEFINE_ARR as $k=>$v)
        {
            defined($k) || define($k,$v);
        }
        
        return true;
    }
    /**
     * 初始化app结构
     */
    public static function initApp()
    {
        //目录结构
        $dir=array(
            LIB_PATH,   
            ACTION_PATH,   
            MODULE_PATH,
            AJAX_PATH,
            
            COMMON_PATH,
            COMMON_PATH.'Js',
            COMMON_PATH.'Css',
            COMMON_PATH.'Image',
            COMMON_PATH.'Php',
            
            TPL_PATH,
            TPL_PATH.'Default/',
            TPL_PATH.'Index/',
            TPL_PATH.'Exception/',
            CONF_PATH,
            RUNTIME_PATH,
            RUNTIME_PATH.'Define/',
            RUNTIME_PATH.'Class/',
            RUNTIME_PATH.'Custom/',    //用户指定自动加载文件
            RUNTIME_PATH.'Conf/'
        ); 
        
        //基本目录创建
        foreach($dir as $v)
        {
            self::mk_dir($v);
        }
    }
    static function initRewrite()
    {
        $fpath=APP_PATH.'.htaccess';
    
        if(!is_file($fpath))
        {
            file_put_contents($fpath,file_get_contents(THINK_PATH.'Common/Rewrite/rewrite'));
        }
    }
    /**
     * 配置处理
     * 1.读取配置信息
     * 2.缓存配置信息
    */
    static function initConf()
    {
        $c=file_get_contents(THINK_PATH.'Common/Conf/conf.php');
        if(!is_file(CONF_PATH.'conf.php'))
        {
            file_put_contents(CONF_PATH.'conf.php',$c);
        }
        self::initConfHandle();     
    }
    /**
     * 导入,缓存并导入配置信息 Conf.class.php
     */
    static function initConfHandle()
    {
        require(APP_PATH.'Conf/conf.php');
        require(CORE_PATH.'Conf.class.php');
        Conf::param($CONF);
        return true;
    }
    
    /**
     * 写入测试Action
     */
    static function initAction()
    {
        $actionPath=ACTION_PATH.'IndexAction.class.php';
        $tplPath=TPL_PATH.'Index/index.html';
        
        if(!is_file($actionPath))
        {
            file_put_contents($actionPath,file_get_contents(THINK_PATH.'Common/Template/Index/IndexAction.class.php'));    
        }
        if(!is_file($tplPath))
        {
          file_put_contents($tplPath,file_get_contents(THINK_PATH.'Common/Template/Index/index.html'));  
        }
    }
    /**
     * 初始化基本数据
     */
    static function initBaseFile()
    {
        $baseList=Conf::param('basefile');
        foreach($baseList as $v)
        {   
            require($v);
        }
        return true;
    }
    /**
     * 自动加载文件
     */
    static function initCustom()
    {
        $customList=Conf::param('autoload');
        
        foreach($customList as $v)
        {
            require($v);
        }
        
        return true;
    }
    /**
     * 错误处理
     */
    static function initException()
    {
        $actionPath=ACTION_PATH.'ExceptionAction.class.php';
        $tplPath=TPL_PATH.'Exception/index.html';
        if(!is_file($actionPath))
        {
            file_put_contents($actionPath,file_get_contents(THINK_PATH.'Common/Tpl/Exception_index'));
        }
        if(!is_file($tplPath))
        {
           file_put_contents($tplPath,file_get_contents(THINK_PATH.'Common/Tpl/Exception/index.html'));
        }
    }
    /**
     * 数据缓存
     */
    static function cache()
    {
        self::cacheDefine();
        self::cacheConf();
        self::cacheBaseFile();
        self::cacheCustom();
    }
    /**
     * 缓存常量
     */
    static function cacheDefine()
    {
        $defArr=array();
        require(THINK_PATH.'Common/Define/define.php');
       
        foreach($DEFINE_ARR as $k=>$v)
        {
            defined($k) || define($k,$v);
            $defArr[]=$C[]='define(\''.$k.'\',\''.$v.'\');';
        }
        return file_put_contents(RUNTIME_PATH.'Define/define.php','<?php '.implode('',$defArr).' ?>');
    }
    /**
     * 缓存用户自定义加载文件
     */
    static function cacheCustom()
    {
        $customList=Conf::param('autoload');
        
        $cArr=array();
        $cArr[]='<?php ';
        foreach($customList as $v)
        {
            $cArr[]=self::strip_whitespace(file_get_contents($v));
        }
        $cArr[]=' ?>';
        
        return file_put_contents(RUNTIME_PATH.'Custom/Custom.php',implode('',$cArr));
    }
    /**
     * 缓存配置文件
     */
    static function cacheConf()
    {
        $cArr=array();
        $con=array();
        $cArr[]='<?php ';

        $con=file_get_contents(CONF_PATH.'conf.php');
        $cArr[]=self::strip_whitespace($con);
        $con=file_get_contents(CORE_PATH.'Conf.class.php');
        $cArr[]=self::strip_whitespace($con);
        $cArr[]='Conf::param($CONF);';
        $cArr[]=' ?>';
        return file_put_contents(RUNTIME_PATH.'Conf/conf.php',implode('',$cArr));
    }
    /**
     * 缓存待基础支持文件
     */
    static function cacheBaseFile()
    {
        $baseList=Conf::param('basefile');
        
        $cArr=array();
        $con='';
        $cArr[]='<?php ';

        foreach($baseList as $v)
        {
            $con=file_get_contents($v);
            $cArr[]=self::strip_whitespace($con);
        }
        $cArr[]=' ?>';
        $fpath=RUNTIME_PATH.'Class/class.php';
        
        return file_put_contents($fpath,implode('',$cArr));
    }
    /**
     * 自动生成重写规则
     */
    static function initHtaccess()
    {
        $fpath=APP_PATH.'.htaccess';
        if(!is_file($fpath))
        {
            file_put_contents($fpath,file_get_contents(THINK_PATH.'Common/Rewrite/rewrite'));
        }
    }
    /**
    * 循环创建目录
    */
    static function mk_dir($dir='',$mode=0777)
    {
        if(is_dir($dir) || mkdir($dir)) return true;
        if(!mk_dir(dirname($dir),$mode)) return false;
        return mkdir($dir,$mode);
    }
    /* 去除代码中的空白和注释 */
    static function strip_whitespace(&$content) 
    {
        $stripStr = '';
        //分析php源码
        $tokens = token_get_all($content);
        $last_space = false;
        for($i=0;isset($tokens[$i]); $i++) 
        {
            if (is_string($tokens[$i])) {
                $last_space = false;
                $stripStr .= $tokens[$i];
            } 
            else 
            {
              switch ($tokens[$i][0]) {
                  //过滤各种PHP注释
                  case T_COMMENT:
                  case T_DOC_COMMENT:
                  case T_OPEN_TAG:
                  case T_CLOSE_TAG:
                      break;
                  //过滤空格
                  case T_WHITESPACE:
                      if (!$last_space) {
                          $stripStr .= ' ';
                          $last_space = true;
                      }
                      break;
                  default:
                      $last_space = false;
                      $stripStr .= $tokens[$i][1];
              }
            }
        }
        return $stripStr;
    }
}
?>