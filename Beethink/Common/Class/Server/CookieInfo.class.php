<?php
class CookieInfo
{
  /*设置cookie*/
  static function cookie($name,$val,$param=array())
  {
    /*默认设置*/
    $config=array(
         'expire'=>518400,
    );
    (is_array($param))&&($config=array_merge($config,$param));
    if(''===$val)
    {
        return isset($_COOKIE[$name])?$_COOKIE[$name]:null;   //获取指定cookie
    }else
    {
        if(is_null($val))
        {
            setcookie($name,'',time()-3600);
            unset($_COOKIE[$name]);  //删除指定的cookie
        }
        else
        {
            $expire=!empty($config['expire'])?time()+intval($config):0;
            setcookie($name,$val,$expire);
            $_COOKIE[$name]=$val;
        }  
    }  
  }
}
?>