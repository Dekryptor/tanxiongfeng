<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/12 0012
 * Time: 12:57
 */
class LoginFailLogModel
{
    protected static $trueTableName='tbl_login_fail_log';
    /*ÈÕÖ¾¼ÇÂ¼*/
    public static function log($username,$psd,$ip='')
    {
        if(empty($ip))
        {
            Sys::S('core.Server.Ip');
            $ip = Ip::get_client_ip();
        }

        $data=array(
            'username'=>addslashes($username),
            'psd'=>addslashes($psd),
            'ip'=>bindec(decbin(ip2long($ip))),
            'record_time'=>NOW
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }
}