<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/25 0025
 * Time: 7:43
 */
class PathAction
{
    static function index()
    {
        Sys::D('PathTrace');

        echo PathTraceModel::getTraceByUrl('user','test');
    }
}