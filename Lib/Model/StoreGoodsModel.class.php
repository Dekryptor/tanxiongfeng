<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/15 0015
 * Time: 17:50
 */
class StoreGoodsModel
{
    protected static $trueTableName='tbl_store_goods';

    /*get the order info by goods_id*/
    static function getOrderGoodsInfo($goods_id)
    {
        $data=Sys::M(self::$trueTableName)->select('`logo`,`name`,`price`,`discount_price`','`id`='.$goods_id,1);

        if(empty($data))
        {
            $data=array(
                'logo'=>0,
                'name'=>'',
                'price'=>0,
                'discount_price'=>0
            );
        }

        Sys::D('Image');
        $imgData=ImageModel::getImageInfo($data['logo']);

        return array_merge($data,$imgData);
    }
}