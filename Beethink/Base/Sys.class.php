<?php
class Sys
{
    static $_class=array();
    
    static function init($className)
    {
        return ucfirst(strtr($className,'.#','/.'));
    }
    /**
     * 实例化模型
     */
    static function D($modelName)
    {
        $modelName=self::init($modelName);
        $modelName.='Model';
        $baseName=basename($modelName);

        if(!isset(self::$_class[$modelName]))
        {
            Loadfile::import($modelName,'model');
            self::$_class[$modelName]=$baseName;
        }

        return $baseName;
    }
    /**
     * 实例化控制器
     */
    static function A($actionName)
    {
        $className=self::init($actionName);
        $className.='Action';

        $baseName=basename($className);
        if(!isset(self::$_class[$className]))
        {
            Loadfile::import($className,'action');
            self::$_class[$className]=$baseName;
        }

        return $baseName;
    }
    /**
     * 实例化数据库操作对象
     */
    static function M($tableName)
    {
        static $tableHandle=array();
        
        if(isset($tableHandle[$tableName]))
        {
            return $tableHandle[$tableName];
        }   
        else
        {
          return  $tableHandle[$tableName]=new Model($tableName);
        }
    }
    /**
     * 加载一个静态类
     */
    static function S($className)
    {
    	static $cls_name=array();
        $className=self::init($className);

        $baseName=basename($className);
        if(!isset($cls_name[$className]))
        {
            Loadfile::import($className,'static');
            $cls_name[$className]=$baseName;
        }


        return $baseName;
    }
    /*
     * @class_name 要加载的类名
     * */
    static function I($class_name,$config=null)
    {
        static $cls_name=array();
        $class_name=self::init($class_name);
        if(isset($cls_name[$class_name]))
        {
            return $cls_name[$class_name];
        }
        Loadfile::import($class_name,'class');

        return new $class_name($config);
    }
}