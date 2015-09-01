<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1 0001
 * Time: 15:25
 */
class UserAddressModel
{
    protected static $trueTableName='tbl_user_address';
    /*获取地址列表*/
    static function getList($userId)
    {
        $data=Sys::M(self::$trueTableName)->select('id,user_id,username,tel,address','user_id='.$userId.' AND status=1');

        return $data;
    }
    /*新增地址*/
    static function newAddress($userId,$username,$tel,$address)
    {
        $data=array(
            'user_id'=>array($userId,'int'),
            'username'=>array($username,'string'),
            'tel'=>array($tel,'string'),
            'address'=>array($address,'string'),
            'status'=>array(1,'int')
        );

        return Sys::M(self::$trueTableName)->insert($data);
    }
    /*废弃地址*/
    static function disabledAddress($id)
    {
        $data=array(
            'status'=>array(0,'int')
        );

        return Sys::M(self::$trueTableName)->update($data,'id='.$id);
    }
}