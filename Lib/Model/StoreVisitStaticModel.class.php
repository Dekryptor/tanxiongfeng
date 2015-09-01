<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1 0001
 * Time: 12:44
 */
class StoreVisitStaticModel
{
    protected static $trueTableName='tbl_store_visit_static';

    /*新增访问数*/
    static function newAccessNum($storeId,$date='')
    {
        (empty($date))&&($date=date('Ymd',NOW));

        /*判断当前记录否存在*/
        $staticInfo=self::findStatic($storeId,$date);

        if(empty($staticInfo))
        {
            $data=array(
                'store_id'=>array($storeId,'int'),
                'num'=>array(1,'int'),
                'date'=>$date
            );

            return Sys::M(self::$trueTableName)->insert($data);
        }

        $data=array(
            'num'=>array('num+1','ignore')
        );

        return Sys::M(self::$trueTableName)->update($data,'store_id='.$storeId.' AND date=\''.$date.'\'');
    }
    /*获取指定日期的访问数
        $date='Ymd'
    */
    static function getVisitedNum($storeId,$date='')
    {
        (empty($date))&&($date=date('Ymd',NOW));
        $data=Sys::M(self::$trueTableName)->select('num','store_id='.$storeId.' AND date=\''.$date.'\'',1);

        return $data?$data['num']:0;
    }
    /*查询指定商店,指定日期的记录信息*/
    static function findStatic($storeId,$date='Ymd')
    {
        $data=Sys::M(self::$trueTableName)->select('store_id','store_id='.$storeId.' AND date=\''.$date.'\'',1);

        return $data;
    }
}