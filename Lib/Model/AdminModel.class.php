<?php
/*????????*/
class AdminModel
{
    /*????ж????*/
    public static function init()
    {
        if(!self::ifUserLogined())
        {
            Error::halt(10001,'???δ???');

            return false;
        }

        if(self::isAdmin())
        {
            return true;
        }
        else
        {
            self::getAuthInfo();
            //????????????????
            Sys::D('Menu');
            $menu_id=MenuModel::getIdByActionAndModule(ACTION,MODULE);

            if($menu_id)
            {
                return self::checkMenuAuth($menu_id);
            }
            Sys::D('GlobalRule');
            $menu_id=GlobalRuleModel::getIdByActionAndModule(ACTION,MODULE);

            return self::checkGlobalAuth($menu_id);
        }
    }
    /*menu 权限检测*/
    private static function checkMenuAuth($menu_id)
    {
        return in_array($menu_id,$_SESSION['authority']['rules']);
    }
    /*全局规则检测*/
    private static function checkGlobalAuth($menu_id)
    {
        return in_array($menu_id,$_SESSION['authority']['g_rules']);
    }
    /*判断用户是否已经登录*/
    private static function ifUserLogined()
    {
        return isset($_SESSION['userinfo']) && isset($_SESSION['userinfo']['id']);
    }
    /*是否为管理员*/
    private static function isAdmin()
    {
        return $_SESSION['userinfo']['id']==1;
    }
    /*获取权限信息,并保存到session中*/
    private static function getAuthInfo()
    {
        if(!isset($_SESSION['authority']))
        {
            Sys::D('Group');
            $_SESSION['authority']=GroupModel::getInheritRule($_SESSION['userinfo']['group_id']);
        }

        return true;
    }
}