<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/15 0015
 * Time: 15:16
 */
class OrderDateStaticModel
{
    protected static $trueTableName='tbl_order_date_static';

    /*获取指定日期的最大的订单数+1*/
    static function getOrderId($date='')
    {
        $date=self::getDefaultDate($date);

        $data=Sys::M(self::$trueTableName)->select('`num`','`date`=\''.$date.'\'',1);

        return $data?$data['num']+1:1;
    }
    /*新增日期订单数*/
    static function sync($date='YYmmdd')
    {
        $oM=Sys::M(self::$trueTableName);

        $rs=$oM->select('`num`','`date`=\''.$date.'\'',1);
        if($rs)
        {
            $data=array(
                'num'=>array('`num`+1','ignore')
            );

            return $oM->update($data,'`date`=\''.$date.'\'');
        }
        else
        {
            $data['date']=$date;
            $data['num']=array(1,'int');

            return $oM->insert($data);
        }
    }
    /*获取默认日期*/
    private static function getDefaultDate($date)
    {
        (empty($date))&&($date=date('Ymd',NOW));

        return $date;
    }
}