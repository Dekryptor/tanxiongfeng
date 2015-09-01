<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/13 0013
 * Time: 19:46
 */
class OrderBaseinfoModel
{
    CONST BUSINESS_NORMAL=1;

    protected static $trueTableName='tbl_order_baseinfo';
    /*
     * goods_info=array(
     *      array(goods_id,num)
     *
     * );
     * */
    static function addOrder($userId,$storeId,$orderNum,$totalPrice)
    {
        $data=array(
            'user_id'=>array($userId,'int'),
            'store_id'=>array($storeId,'int'),
            'order_num'=>array($orderNum,'string'),
            'time'=>array(NOW,'int'),
            'total_price'=>array($totalPrice,'float')
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }

}