<?php
/**
 * 用于文件加载
 */
class Loadfile
{
    /*优化require_once*/
    public static function require_cache($fname)
    {
        static $_importFiles=array();
        
        if(isset($_importFiles[$fname]))
        {
            return true;
        }       
        if(is_file($fname) && require($fname))
        {
            return $_importFiles[$fname]=true;
        }
        return false;
    }
    /**
     * 文件导入
     */
    public static function import($className,$type)
    {
        static $_file=array();
        $method='';
        $flag=false;
        $type=strtolower($type);
        $type_refer=array(
			'action'=>'importAction',
			'model'=>'importModel',
			'class'=>'importClass',
			'static'=>'importStatic'
		);
        if(isset($_file[$className]))
        {
            return true;
        }
        //判断文件类型 1=>action 2=>model -1=>其他文件
        $method=$type_refer[$type];

        $flag=self::$method($className);
        
        if(!$flag)
        {
            Error::halt(FILE_NOTFOUND,'文件:'.$className.'未找到');
        }
    }
    /**
     * 导入action
     */
    public static function importAction($actionName)
    {
        $actionName.='.class.php';
        $fpathArr=array();

        $fpathArr=(strpos($actionName,'Core')===0)?array(THINK_PATH.'Common/Action/'.substr($actionName,5)):
        	array(ACTION_PATH.$actionName,THINK_PATH.'Common/Action/'.$actionName);
        
        $flag=false;
        foreach($fpathArr as $v)
        {
            if(is_file($v) && require($v))
            {
                $flag=true;
                break;
            }
        }
        return $flag;
    }
    /**
     * 导入model
     */
    public static function importModel($modelName)
    {
        $modelName.='.class.php';
        $fpathArr=array();
        
        $fpathArr=strpos($modelName,'Core')===0?array(THINK_PATH.'Common/Model/'.substr($modelName,5)):array(
				MODULE_PATH.$modelName,
                THINK_PATH.'Common/Model/'.$modelName
		);
        
        $flag=false;
        foreach($fpathArr as $v)
        {
            if(is_file($v) && require($v))
            {
                $flag=true;
                break;
            }
        }
        return $flag;
    }
    /**
     * 导入并实例化指定类
     */
    static function importClass($className)
    {
    	$className.='.class.php';
        $fpathArr=array();
		$flag=false;
        $fpathArr=strpos($className,'Core')===0?array(THINK_PATH.'Common/Class/'.substr($className,5)):array(
				MODULE_PATH.$className,
                THINK_PATH.'Common/Class/'.$className
		);
        
        foreach($fpathArr as $v)
        {
            if(is_file($v) && require($v))
            {
                $flag=true;
                break;
            }
        }
        return $flag;
    }
    /**
     * 导入静态类
     */
    static function importStatic($className)
    {
    	$className.='.class.php';
        $fpathArr=array();
		$flag=false;
        $fpathArr=strpos($className,'Core')===0?array(THINK_PATH.'Common/Class/'.substr($className,5)):array(
				MODULE_PATH.$className,
                THINK_PATH.'Common/Class/'.$className
		);
        foreach($fpathArr as $v)
        {
            if(is_file($v) && require($v))
            {
                $flag=true;
                break;
            }
        }
        return $flag;
    }
}
?>