<?php
class ExceptionAction
{
    static function index()
    {
        $msg=isset($_GET['msg'])?$_GET['msg']:'';
        $t=(isset($_GET['t']) && (int)$_GET['t']>0)?(int)$_GET['t']:8;
        $msgType=isset($_GET['msg_type'])?$_GET['msg_type']:0;
        $url=isset($_GET['url'])?urldecode($_GET['url']):'';

        View::assign('msgType',$msgType?'操作成功!':'操作失败!');
        View::assign('msg',$msg);
        View::assign('time',$t);
        View::assign('url',$url);

        View::display();
    }
}