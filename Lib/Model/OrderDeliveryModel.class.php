<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/19 0019
 * Time: 22:16
 */
class OrderDeliveryModel
{
    protected static $trueTableName='tbl_order_delivery';

    /*同步用户配送信息*/
    static function sync($orderNum,$userName,$tel,$address,$zip_code='')
    {
        $data=array(
            'order_num'=>array($orderNum,'string'),
            'userName'=>array($userName,'string'),
            'tel'=>array($tel,'string'),
            'address'=>array($address,'string'),
            'zip_code'=>array($zip_code,'string')
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }
}