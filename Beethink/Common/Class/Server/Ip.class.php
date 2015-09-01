<?php
/**
 * ip 操作类
 */
class Ip
{
  /*获取客户端IP地址*/
  static function get_client_ip()
  {
    $ip='';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $arr=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);   /*获取通过代理服务器访问服务器的IP地址,如果经过多个代理服务器,则逗号分割*/
        $pos=array_search('unknown',$arr);
        if(false!==$pos) unset($arr[$pos]);
        $ip=trim($arr[0]);
    }elseif(isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }elseif(isset($_SERVER['REMOTE_ADDR']))
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    /*ip地址合法验证*/
    return (self::checkIP($ip))?$ip:'0.0.0.0';
  }
  /**
   * 验证ip是否合法
   */
  static function checkIP($ip)
  {
    return false!==ip2long($ip);
  }
  /**
   * 获取整形ip
   */
  static function getInt($ip)
  {
    if(self::checkIP($ip))
    {
      return bindec(decbin(ip2long($ip)));
    } 
    return 0;
  }
}