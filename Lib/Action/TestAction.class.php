<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1 0001
 * Time: 14:01
 */
class TestAction
{
    static function index()
    {
        Sys::D('StoreVisitStatic');

//        StoreVisitStaticModel::newAccessNum(1);
//        $accessNum=StoreVisitStaticModel::getVisitedNum(1);

//        Sys::D('StoreOrderStatic');
//        $num= StoreOrderStaticModel::getDateOrderNum(1,'20150819');
//
//        var_dump($num);

//        Sys::D('SysMessage');
//        $msgData=SysMessageModel::getMsgList();
//        Sys::D('UserAddress');
//
//        UserAddressModel::newAddress(1,'bee','18224087281','成都市高新区天府软件园D区6栋一楼232');
//        UserAddressModel::disabledAddress(1);
//
//        $data= UserAddressModel::getList(1);
//
//        var_dump($data);

//        Sys::D('AreaLnglat');

//        $data= AreaLnglatModel::getLngLatByCode('340403');

//        $data=AreaLnglatModel::getInfoByCode('510100');
//        $address=AreaLnglatModel::decorateAddress('510100','510100','详细地址');
//        var_dump($address);

         Sys::S('core.PhpExcel.PHPExcel.php');
         PHPExcel::init();

        $PHPExcel=PHPExcel::load();
    }
}