<?php
/**
 * 数据格式化处理
 */
class Format
{
    /**
     * 格式化字节大小
     */
    static function format_bytes($size,$delimiter='')
    {
        $units=array('B','KB','MB','GB','TB','PB');
        $i=0;
        while($size>=1024 && $i++<5)
        {
            $size/=1024;
        }
        return round($size,2).$delimiter.$units[$i];
    }
    /*
     * 格式化手机号
     * */
    static function format_tel($phone,$sep=' ')
    {
        $data=array();

        $data[]=substr($phone,0,3);
        $data[]=substr($phone,3,4);
        $data[]=substr($phone,7,4);

        return implode($sep,$data);
    }
}