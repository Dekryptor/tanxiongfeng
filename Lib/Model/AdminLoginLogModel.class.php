<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/12 0012
 * Time: 9:21
 */
class AdminLoginLogModel
{
    const LOGIN_IN=1,
          LOGIN_OUT=2;

    protected static $trueTableName='tbl_admin_login_log';
    /*log data*/
    public static function log($user_id,$action=LOGIN_IN,$ip='127.0.0.1')
    {
        $data=array(
            'user_id'=>$user_id,
            'record_time'=>NOW,
            'action'=>$action,
            'ip'=> bindec(decbin(ip2long($ip)))
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }
    /*获取最近登录的信息*/
    public static function getLatestLoginInfo($user_id,$action=LOGIN_IN)
    {
        $data=Sys::M(self::$trueTableName)->select('*','`user_id`='.$user_id.' AND `action`='.$action,1);

        return $data;
    }
}