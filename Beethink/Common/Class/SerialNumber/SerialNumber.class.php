<?php
/**
 *     功能：获取随机n位验证码
 *   创建人：
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
 */
class SerialNumber
{
    /**
     * 获取只有数字的随机$len 位的验证码
     */
    static function randNum($len)
    {
        $arr=array();
        $len=self::getLen($len);
        for($len;$len>0;$len--)
        {
            $arr[]=mt_rand(0,9);
        }
        return implode('',$arr);
    }
    /**
     * 获取包含数字和字符的 $len 位的验证码
     *
     * @$upperCash>0  ,全大写
     *            <0  ,全小写
     *            =0  ,不区分
     */
    static function randCharAndNum($len,$toUpper=1)
    {
        $char_arr=array();
        $len=self::getLen($len);
        $str='';

        for($len;$len>0;$len--)
        {
            $char_arr[]=dechex(mt_rand(0,15));
        }
        $str=implode('',$char_arr);

        return  ($toUpper==0)?$str:($toUpper>0?strtoupper($str):strtolower($str));
    }
    static function getLen($len)
    {
        ($len<=0)&&($len=6);
        return $len;
    }
    /**
     * 获取订单11位数的订单编号
     * @$business_no => 业务id
     * @$growth_id   => 自增长id
     */
    static function orderNum($business_no,$growth_id)
    {
        $len=7-strlen($growth_id);
        $growth_id=str_repeat(0,$len).$growth_id;
        return $business_no.substr(date('y',NOW),0,1).date('md',NOW).$growth_id;
    }
    /**
     * 序列号
     */
    static function getSerialNum()
    {
        return date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))),0,8);
    }
}