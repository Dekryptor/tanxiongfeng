<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/18 0018
 * Time: 7:31
 */
class OrderAction
{
    public function test()
    {

        Sys::D('Order');

        $goodsData=array(
            array(1,3),
            array(2,1)
        );
        $receiveData=array(
            'username'=>'bee',
            'tel'=>'18224087281',
            'email'=>'1107942585@qq.com',
            'address'=>'四川省成都市青羊区'
        );

        OrderModel::add(1,1,$goodsData,$receiveData,'this is a remark');
    }
}