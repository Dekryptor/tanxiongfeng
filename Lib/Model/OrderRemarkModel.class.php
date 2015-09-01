<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/19 0019
 * Time: 23:01
 */
class OrderRemarkModel
{
    protected static $trueTableName='tbl_order_remark';

    /*同步订单备注*/
    static function sync($orderNum,$remark)
    {
        $data=array(
            'order_num'=>array($orderNum,'string'),
            'remarks'=>array($remark,'string')
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }
}