<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2015/7/6 0006
 * Time: 10:16
 */
class UcenterMemberModel
{
    const LOGIN_FAIL=10006,
          WRONG_ACCOUNT=11,
          ACCOUNT_LOCKED=12,
          ACCOUNT_DISABLED=13,           //账户已被禁用
          MAX_LOGIN_ERROR_TIME=5,     //最大登录次数
          LOCK_TIME=86400,              //账户锁定时间(s)
          LOGIN_SUCCESS=10;


    protected static $trueTableName='tbl_ucenter_member';
    /*
     * 用户登陆
     * */
    public static function login($username,$psd)
    {
        $rs=Sys::M(self::$trueTableName)->select('*','`username`=\''.$username.'\'',1);
        if($rs)
        {
            if($rs['password']!=md5($psd))
            {
                if($rs['login_err_times']+1>self::MAX_LOGIN_ERROR_TIME)
                {
                    self::lockAccount($rs['id']);
                }
                else
                {
                    self::loginFail($rs['id']);
                }

                return self::MAX_LOGIN_ERROR_TIME - $rs['login_err_times'];
            }

            if(!$rs['status'])
            {
                return self::ACCOUNT_DISABLED;
            }

            /*判断是否已经被锁定*/
            if($rs['last_login_time']>NOW)
            {
                return self::ACCOUNT_LOCKED;
            }
            else
            {
                self::loginSuccess($rs['id']);
                Sys::D('GroupMember');
                $rs['group_id'] = GroupMemberModel::getGroupId($rs['id']);

                $_SESSION['userinfo']=$rs;
                return self::LOGIN_SUCCESS;
            }
        }
        else
        {
            Sys::D('LoginFailLog');
            LoginFailLogModel::log($username,$psd);

            return self::LOGIN_FAIL;
        }
    }
    /*
     * 成功回调
     * */
    private static function loginSuccess($userId,$ip='')
    {
        if(empty($ip))
        {
            Sys::S('core.Server.Ip');
            $ip=Ip::get_client_ip();

        }
        $intIp=Ip::getInt($ip);

        $data=array(
            'last_login_ip'=>array($intIp,'int'),
            'last_login_time'=>array(NOW,'int'),
            'login_err_times'=>array(0,'int')
        );

        return Sys::M(self::$trueTableName)->update($data,'`id`='.$userId);
    }
    /*
     * 失败回调
     * */
    static function loginFail($userId)
    {
        $data=array(
            '`login_err_times`'=>array('`login_err_times`+1','ignore')
        );

       return Sys::M(self::$trueTableName)->update($data,'`id`='.$userId);
    }
    /*锁定账户*/
    static function lockAccount($userId)
    {
        $data=array(
            'last_login_time'=>array(NOW+self::LOCK_TIME,'int')
        );

        return Sys::M(self::$trueTableName)->update($data,'`id`='.$userId);
    }
    /*更改账户密码
    $password 不为md5加密的字符串
    */
    static function savePassword($userId,$password)
    {
        $password=md5($password);

        $data=array(
            'password'=>array($password,'string')
        );

        return Sys::M(self::$trueTableName)->update($data,'`id`='.$userId);
    }
    /*判断指定用户名是否已经存在*/
    static function isUsernameExisted($username)
    {
        $data=Sys::M(self::$trueTableName)->select('id','username=\''.$username.'\'',1);

        return $data?$data['id']:0;
    }

    /*set the disabled state of the account*/
    static function setAccountStatus($userId,$status=0)
    {
        $status=$status?1:0;

        $data=array(
            'status'=>array(0,'int')
        );

        return Sys::M(self::$trueTableName)->update($data,'`id`='.$userId);
    }
    /*Modify account base information  contain email & phone*/
    static function alterAccountBaseInfo($userId,$email,$mobile)
    {
        $data=array(
            'email'=>array($email,'string'),
            'mobile'=>array($mobile,'string')
        );

        return Sys::M(self::$trueTableName)->update($data,'`id`='.$userId);
    }
}