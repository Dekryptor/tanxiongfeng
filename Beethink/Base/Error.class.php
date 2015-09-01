<?php
/**
 * 错误处理机制
 */
class Error
{
    CONST REDIRECT=800;

    static function halt($msg_code,$msg,$url='',$t=5)
    {
        /*判断是否是ajax请求*/
        if(defined('AJAX_REQUEST') && AJAX_REQUEST==1)
        {
            self::jsonReturn($msg_code,$msg,$url);
        }
        else
        {
            self::showInPage($msg_code,$msg,$url,$t);
        }
    }
    /*返回json对象*/
    private static function jsonReturn($msgCode,$msg,$url='')
    {
        Sys::S('core.Return.JsonReturn');
        JsonReturn::output($msgCode,$msg,$url);
    }
    /*页面错误信息展示*/
    private static function showInPage($msgCode,$msg='',$url='',$t=5)
    {
        switch($msgCode)
        {
            case QUERY_ERROR:
                $msg='数据库命令执行失败:<br />'.$msg;
                exit($msg);
            case CONNECT_FAIL:
                $errArr='';
                foreach($msg as $k=>$v)
                {
                    $errArr[]=$k.'=>'.$v;
                }
                $msg='Fail To Connect Database:'.implode('<br />',$errArr);
                exit($msg);
            //数据库
            case SUCCESS:
                self::redirect($url,$msg,$t,SUCCESS);
                break;
            case FAIL:
                self::redirect($url,$msg,$t,FAIL);
                break;
            default:
                self::redirect($url,$msg,$t,SUCCESS);
                break;
        }
    }
    /**
    * 控制页面跳转 
    */
    public static function redirect($redirectUrl,$msg,$t,$type=SUCCESS)
    {
        $type=$type==SUCCESS?'操作成功!':'操作失败!';

        header('Location:'.DOMAIN.'index.php?M=Exception&A=index&t='.$t.'&msg='.$msg.'&msg_type='.$type.'&url='.urlencode($redirectUrl));
    }
}