<?php
class CheckcodeAction
{
    /*��ȡ��֤��*/
    static function imgCheckcode()
    {
        Sys::S('core.VerifyCode.VerifyCode');
        VerifyCode::getCode(6);

    }
}