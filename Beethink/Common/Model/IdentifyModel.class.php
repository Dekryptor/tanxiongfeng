<?php
/**
 *     功能：获取随机n位验证码
 *   创建人：
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
*/
class IdentifyModel
{
    /**
     * 获取只有数字的随机$len 位的验证码
     */
    public function getNumCode($len)
    {
        if($len==6)
        {
            return '111111';       
        }
        $arr=array();
        $len=$this->getLen($len);
        for($len;$len>0;$len--)
        {
            $arr[]=mt_rand(0,9);
        }
        return implode('',$arr);
    }
    /**
     * 获取包含数字和字符的 $len 位的验证码
     * 
     */
    public function getCharCode($len)
    {
        $arr=array();
        $len=$this->getLen($len);
        for($len;$len>0;$len--)
        {            
            $arr[]=strtoupper(dechex(mt_rand(0,15)));
        }
        return implode('',$arr);
    }
    public function getLen($len)
    {
        ($len<=0)&&($len=6);
        return $len;
    }
    /**
     * 获取用户校验码
     */
    public function getIdentifyCode()
    {
        return uniqid(mt_rand(100,999));
    }
    /**
     * 获取由数字组称的编号
     */
    public function getSerialNum()
    {
        return NOW.mt_rand(100,999);   
    }
    /**
     * 订单号
     */
    public function getOrder()
    {
        return date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))),0,8);
    }
}
?>