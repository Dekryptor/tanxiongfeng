<?php
/**
 *     ���ܣ���ȡ���nλ��֤��
 *   �����ˣ�
 *     ���ڣ�
 *   �޸��ˣ�
 * �޸����ڣ�
 *     ��ע��
 */
class SerialNumber
{
    /**
     * ��ȡֻ�����ֵ����$len λ����֤��
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
     * ��ȡ�������ֺ��ַ��� $len λ����֤��
     *
     * @$upperCash>0  ,ȫ��д
     *            <0  ,ȫСд
     *            =0  ,������
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
     * ��ȡ����11λ���Ķ������
     * @$business_no => ҵ��id
     * @$growth_id   => ������id
     */
    static function orderNum($business_no,$growth_id)
    {
        $len=7-strlen($growth_id);
        $growth_id=str_repeat(0,$len).$growth_id;
        return $business_no.substr(date('y',NOW),0,1).date('md',NOW).$growth_id;
    }
    /**
     * ���к�
     */
    static function getSerialNum()
    {
        return date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))),0,8);
    }
}