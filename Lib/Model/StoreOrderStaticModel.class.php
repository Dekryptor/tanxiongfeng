<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/19 0019
 * Time: 21:36
 */
class StoreOrderStaticModel
{
    protected static $trueTableName = 'tbl_store_order_static';

    /*sync data*/
    static function sync($storeId, $orderNum, $date = 'YYmmdd')
    {
        $rec = self::ifExists($storeId, $date);
        $data=array();

        $data = array(
            'store_id' => array($storeId, 'int'),
            'date' => $date
        );
        $handle=Sys::M(self::$trueTableName);

        if($rec)
        {
            $data['num']=array('num+1','ignore');

            return $handle->update($data,'id='.$rec['id']);
        }
        else
        {
            $data['num']=array(1,'int');

            return Sys::M(self::$trueTableName)->insert($data);
        }
    }

    /*判断记录是否存在*/
    static function ifExists($storeId, $date = 'YYmmdd')
    {
        $data = Sys::M(self::$trueTableName)->select('`id`','`store_id`='.$storeId.' AND date=\''.$date.'\'', 1);

        return $data;
    }

    /*获取默认日期*/
    static function getDefaultDate()
    {
        return date('Ymd',NOW);
    }
}