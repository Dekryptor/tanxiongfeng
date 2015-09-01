<?php
class LogoutAction
{
    public static function index()
    {
        Sys::D('AdminLoginLog');
        AdminLoginLogModel::log($_SESSION['userinfo']['id'],AdminLoginLogModel::LOGIN_OUT);

        if(isset($_SESSION['userinfo']))
        {
            unset($_SESSION['userinfo']);
        }
        if(isset($_SESSION['authority']))
        {
            unset($_SESSION['authority']);
        }

        Error::halt(SUCCESS,'登出成功!','./Login_index.jsp');
    }
}