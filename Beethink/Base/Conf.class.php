<?php
class Conf
{
    static $conf=array();
    
    public static function param($data)
    {
        if(is_string($data))
        {
            return isset(self::$conf[$data])?self::$conf[$data]:null;
        }
        else if(empty($data))
        {
            return self::$conf;
        }
        else
        {
            self::$conf=array_merge(self::$conf,array_change_key_case($data));
            return true;
        }
    }
}
?>