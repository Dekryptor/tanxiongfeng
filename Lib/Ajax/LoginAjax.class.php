<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/14 0014
 * Time: 7:40
 */
class LoginAjax
{
    const WRONG_CHECK_CODE=18;

    /*用户信息校验*/
    static function verify()
    {
        $data=array(
            'uname'=>array(null,'string','','用户名为空'),
            'password'=>array(null,'length',array(4,16),'密码错误'),
            'checkcode'=>array(null,'string','','验证码为空')
        );
        Sys::S('core.Verify.Input');
        $data=Input::dataFilter($data,'post');

        if(!isset($_SESSION['verify_code']) || strtoupper($data['checkcode']) != $_SESSION['verify_code'])
        {
            Error::halt(self::WRONG_CHECK_CODE,'验证码错误!');
        }
        $oUcenterMember=Sys::D('UcenterMember');
        $loginStatus = UcenterMemberModel::login($data['uname'],$data['password']);

        if($loginStatus >= 10)
        {
            if($loginStatus==10)
            {
                Error::halt(UcenterMemberModel::LOGIN_SUCCESS,array('msg'=>'登录成功','redirect'=>DOMAIN.'Index_index.jsp'));
            }
            else if($loginStatus == UcenterMemberModel::ACCOUNT_LOCKED)
            {
                Error::halt($loginStatus,'账号已被锁定!');
            }else if($loginStatus == UcenterMemberModel::ACCOUNT_DISABLED)
            {
                Error::halt($loginStatus,'账号无效');
            }else
            {
                Error::halt($loginStatus,'用户名或密码错误');
            }
        }else
        {
            $msg=$loginStatus<=0?'您的账号已被锁定':'登录失败,您还有'.$loginStatus.'次机会登录!';

            Error::halt($loginStatus,$msg);
        }
    }
}