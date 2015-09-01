<?php
/**
 * 用于文件加载
 */
class LoadfileModel
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
        //特殊字符转换
      
        $type=strtolower($type);
        
        if(isset($_file[$className]))
        {
            return true;
        }
        //判断文件类型 1=>action 2=>model -1=>其他文件
        $filetype=$type=='action'?1:($type=='model'?2:-1);
        $flag=false;
        
        if($filetype==1)
        {
            $flag=self::importAction($className);
        }else if($filetype==2)
        {
            $flag=self::importModel($className);
        }else
        {
            $flag=self::importOther($className);
        }
        if(!$flag)
        {
            exception::halt(FILE_NOTFOUND,'文件:'.$className.'未找到');
        }
    }
    /**
     * 导入action
     */
    public static function importAction($actionName)
    {
        return require(ACTION_PATH.$actionName.'Action.class.php');
    }
    /**
     * 导入model
     */
    public static function importModel($modelName)
    {
        $modelName.='Model.class.php';
        $fpathArr=array(
            MODULE_PATH.$modelName,
            THINK_PATH.'Common/Class/'.$modelName
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
     * 导入其他文件
     */
    public static function importOther($className)
    {
        return require($className);
    }
}
?>