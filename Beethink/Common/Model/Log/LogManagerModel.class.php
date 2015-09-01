<?php
class LogManagerModel
{
  protected $trueTableName='tbl_log_manager';
  //添加日志
  public function writeLog($type,$userId,$userType,$time,$result,$memo,$ip='')
  {
    if(empty($ip)) $ip=bindec(decbin(ip2long(get_client_ip())));   //长整型ip
    $m=M($this->trueTableName);
    return $m->insert(array('lm_type'=>$type,'lm_user_id'=>$userId,'lm_time'=>$time,'lm_user_type'=>$userType,'lm_result'=>$result,'lm_memo'=>$memo,'lm_ip'=>$ip));
  }
  //写日志
  public function delLog($options='')
  {
    if(empty($options)) return false; 
    $m=M($this->trueTableName);
    //删除日志同时写入日志
    return $m->update(array('lm_status'=>1),$options);
  } 
}
?>