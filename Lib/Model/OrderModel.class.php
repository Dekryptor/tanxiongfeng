<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/13 0013
 * Time: 20:42
 */
class OrderModel
{
    CONST BUSINESS_NORMAL=1;
    /*
     * 添加订单
     *
     * $goodsData=array(
     *  array('id'=>产品,'num'=>数量)
     * );
     *
     * $receiveData=array(
     *  'username'=>'用户名',
     *  'tel'=>'手机号',
     *  'address'=>'详细地址',
     *  'postCode'=>'邮编'
     *  'email'=>'邮箱地址'
     * );
    */
    static function add($user_id,$store_id,$goodsData=array(),$receiveData=array(),$remark='')
    {
        Sys::D('OrderDateStatic');
        Sys::S('core.SerialNumber.SerialNumber');

        $date=date('Ymd',NOW);

        OrderDateStaticModel::sync($date);

        $curDayMaxId=OrderDateStaticModel::getOrderId($date);
        $orderNum=SerialNumber::orderNum(self::BUSINESS_NORMAL,$curDayMaxId);

        $totalPrice=self::saveGoodsInfo($goodsData,$orderNum);
        /*订单基本信息*/
        Sys::D('OrderBaseinfo');

        OrderBaseinfoModel::addOrder($user_id,$store_id,$orderNum,$totalPrice);
        /*同步店铺订单统计信息*/
        Sys::D('StoreOrderStatic');

        StoreOrderStaticModel::sync($store_id,$orderNum,$date);
        /*同步派送信息*/
        Sys::D('OrderDelivery');
        (isset($receiveData['zipCode']))||($receiveData['zipCode']='');

        OrderDeliveryModel::sync($orderNum,$receiveData['username'],$receiveData['tel'],$receiveData['address'],$receiveData['zipCode']);
        /*备注*/
        if($remark)
        {
            Sys::D('OrderRemark');

            OrderRemarkModel::sync($orderNum,$remark);
        }
    }
    /*保存商品信息*/
    private static function saveGoodsInfo(&$goodsData,$orderNum)
    {
        Sys::D('StoreGoods');
        $data=array();
        $totalPrice=0;
        $goodsTotalPrice=0;
        $goodsInfo=array();

        foreach($goodsData as $v)
        {
            $goodsInfo=StoreGoodsModel::getOrderGoodsInfo($v[0]);
            /*
             * goods_id goods_img  price discount_price count total_price name order_num
             * */
            $totalPrice=( $goodsInfo['discount_price']>0?$goodsInfo['discount_price']:$goodsInfo['price'] ) * $v[1];
            $goodsTotalPrice+=$totalPrice;

            $data[]=sprintf('(%s,\'%s\',%s,%s,%s,%s,\'%s\',\'%s\')',
                $v[0],
                $goodsInfo['url'],
                $goodsInfo['price'],
                $goodsInfo['discount_price'],
                $v[1],
                $totalPrice,
                $goodsInfo['name'],
                $orderNum
                );

        }

        Sys::D('OrderGoods');

        OrderGoodsModel::save($data);

        return $goodsTotalPrice;
    }
}