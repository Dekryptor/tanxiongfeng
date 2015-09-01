<?php
class App
{
    public static function _init()
    {
        date_default_timezone_set(Conf::param('timezone'));   //设置默认时区
        //地址重写
        Rewrite::init();
    }
}
?>