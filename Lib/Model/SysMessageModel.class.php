<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1 0001
 * Time: 10:54
 */
class SysMessageModel
{
    protected static $trueTableName='tbl_sys_message';

    CONST CUSTOMER=1,   //用户
            STORE=2;     //商家

    CONST SUGGEST=1,     //建议
            CALLBACK=0;  //反馈

    /*add message*/
    static function addMsg($fromUserId,$userType,$content,$imgId,$msgType)
    {
        $data=array(
            'from_user_id'=>array($fromUserId,'int'),
            'user_type'=>array($userType,'int'),
            'time'=>array(NOW,'int'),
            'content'=>array($content,'string'),
            'img_id'=>array($imgId,'int'),
            'msg_type'=>array($msgType,'int')
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }
    /*update the status that has been viewed*/
    static function updateStatus($id,$status)
    {
        $data=array(
            'status'=>array($status,'int')
        );

        return Sys::M(self::$trueTableName)->update($data,'`id`='.$id);
    }
    /*The Numberic that hasn't been viewed*/
    static function getNumberOfNoViewed()
    {
        $data=Sys::M(self::$trueTableName)->select('COUNT(0) AS total','status=0');

        return $data[0]['total'];
    }
}