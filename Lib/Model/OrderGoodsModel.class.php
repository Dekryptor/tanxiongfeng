<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/17 0017
 * Time: 21:48
 */
class OrderGoodsModel
{
    protected static $trueTableName='tbl_order_goods';

    /*同时保存多条记录*/
    static function save($data)
    {
        if(is_array($data))
        {
            $data=implode(',',$data);
        }

        return Sys::M(self::$trueTableName)->execute('INSERT INTO '.self::$trueTableName.'(`goods_id`,`goods_img`,`price`,`discount_price`,`count`,`total_price`,`name`,`order_num`) VALUES'.$data);
    }
}