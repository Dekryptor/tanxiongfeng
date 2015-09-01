<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/8 0008
 * Time: 15:48
 */
class LoginAction
{
    /*后台登陆页面*/
    static function index()
    {

        if(self::isLogin())
        {
            Error::halt('','','','');
            exit('<script>alert("你已经登录,请退出后再登录");window.location.href="./Index_index.jsp";</script>');
        }

        View::assign('scripts',array('core.Bracket.bootstrapValidator#min'));
        View::assign('styles',array('core.Bracket.bootstrapValidator#min'));

        View::display();
    }
    /*判断用户是否已经登陆*/
    private static function isLogin()
    {
        return isset($_SESSION['userinfo']) && isset($_SESSION['userinfo']['id']);
    }
}