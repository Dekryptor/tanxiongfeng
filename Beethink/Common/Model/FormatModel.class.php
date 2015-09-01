<?php
/**
 * 数据格式化处理
 */
class FormatModel
{
    /**
     * 格式化字节大小
     */
    public static function formate_bytes($size,$delimiter='')
    {
        $units=array('B','KB','MB','GB','TB','PB');
        $i=0;
        while($size>=1024 && $i++<5)
        {
            $size/=1024;
        }
        return round($size,2).$delimiter.$units[$i];
    }    
}
?>