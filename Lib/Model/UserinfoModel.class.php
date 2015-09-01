<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1 0001
 * Time: 15:18
 */
class UserinfoModel
{
    protected static $trueTableName='tbl_userinfo';

    static function getBaseInfo($userId)
    {
        $data=Sys::M(self::$trueTableName)->select('id,no,name,tel,zip,email','id='.$userId,1);

        return $data;
    }

}