<?php
class CheckcodeAction
{
    /*获取验证码*/
    static function imgCheckcode()
    {
        Sys::S('core.VerifyCode.VerifyCode');
        VerifyCode::getCode(6);

    }
}