<?php
/**
 * Ŀ¼������
 */
class Dir
{
    /*ѭ������Ŀ¼*/
    static function mk_dir($dir,$mode=0777)
    {
        if(is_dir($dir) || @mkdir($dir)) return true;
        if(!self::mk_dir(dirname($dir),$mode)) return false;
        return mkdir($dir,$mode);
    }
}
?>