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

        //StoreVisitStaticModel::newAccessNum(1);

        $accessNum=StoreVisitStaticModel::getVisitedNum(1);

        var_dump($accessNum);
    }
}