<?php
/**
 * 登陆验证
 * 功能:
 *  .支持用户名密码核对
 *  .返回信息
 *  .用户锁定判断
 *  .用户重复登陆失败次数记录 
 *  .ip检测
 *  .日志记录
 *  .登录成功保存用户信息
 *  
 */
class LoginVerifyModel
{
  private $w=100,
          $h=25,
          $maxErrTimes=3,    //最大允许错误次数
          $trueTableName='tbl_userinfo',
          $checkCode=4,     //验证码长度
          $lockTime=7200;   //锁定2个小时
  /**
   * $name='用户名',
   * $psd='密码'
   * 用户账户判断
   * -1 用户密码错误
   * -2 用户名错误
   * -3 账户被锁定
   * >0 还有n次登陆机会
   *
   */
  public function verify($name,$psd)
  {
    $m=M($this->trueTableName);
    //对用户名和密码验证
    $name=addslashes($name);
    $t=$_SERVER['REQUEST_TIME'];
    $data=$m->select('*',' u_name=\''.$name.'\'',1);
    if($data)
    {
       //判断密码
       if($data['u_psd']==$psd)
       {
          //判断用户是否被锁定
          if($data['u_last_login_time']<=$t)
          {
            //更新 成功登陆次数
            $ip=bindec(decbin(ip2long(get_client_ip())));
            $m->update(array('u_login_err_times'=>0,'u_last_login_time'=>$t,'u_last_login_ip'=>$ip,'u_login_times'=>$data['u_login_times']+1),'u_id='.$data['u_id']);
            $this->loginSuccess($data,$t);
            return 0;
          }
          else
          {
            $this->loginFail(3,$data,'',$t);
            return -3;
          }
       }
       else
       {
          //判断并更新密码错误次数
          if($data['u_login_err_times']+1>$this->maxErrTimes)
          {
            //更新锁定时间
            $m->update(array('u_last_login_time'=>$t+$this->lockTime),$data['u_id']);
            $this->loginFail(3,$data,'',$t);
            return -3;
          }
          else
          {
            $m->update(array('u_login_err_times'=>$data['u_login_err_times']+1),$data['u_id']);
          }
          $this->loginFail(1,$data,$psd,$t);
          return $this->maxErrTimes-$data['u_login_err_times'];   //返回还有几次登陆机会 
       }
    }
    else
    {
      $this->loginFail(2,$name,$psd,$t);
      return -2;
    }
  }
  //处理登陆成功
  private function loginSuccess($data,$t)
  {
    $log=D('LogManager');
    $_SESSION['bee_userinfo']=$data;
    $log->writeLog(0,$data['u_id'],$data['u_type'],$t,1,'登陆成功');
  }
  //处理登陆失败
  private function loginFail($type,$param1,$param2='',$t)
  {
    $log=D('LogManager');
    switch($type)
    {
      case 1://密码错误
        $log->writeLog($type,$param1['u_id'],$param1['u_type'],$t,0,'密码错误:'.$param2);
        break;
      case 2://用户名错误  -1 表示无该用户
        $log->writeLog($type,-1,-1,$t,0,'用户名不存在:'.$param1.'+'.$param2);
        break;
      case 3://已被锁定
        $log->writeLog($type,$param1['u_id'],$param1['u_type'],$t,0,'用户被锁定');
        break;
      default:;
    }
  }
  //更具user判断是否需要验证码
  public function checkIP($user)
  {
    $m=M($this->trueTableName);
    $data=$m->select('u_last_login_ip','u_name='.$user,1);
    if($data && $data['u_last_login_ip']!=bindec(decbin(ip2long(get_client_ip())))) return false;
    return true;
  }
}

?>