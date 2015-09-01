<?php
/**
 * 目录操作类
 */
class Dir
{
    /*循环创建目录*/
    static function mk_dir($dir,$mode=0777)
    {
        if(is_dir($dir) || @mkdir($dir)) return true;
        if(!self::mk_dir(dirname($dir),$mode)) return false;
        return mkdir($dir,$mode);
    }
}
?>