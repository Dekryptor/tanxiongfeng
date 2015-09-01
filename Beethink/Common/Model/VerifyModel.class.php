<?php
/*
数据合法性检验 类
验证返回数据格式格式 合法 return true 否则 return 对应错误信息
*/
class VerifyModel
{
  public function ck_date($date)
  {
    return true;
  }
  public function ck_ip($ip)
  {
    return true;
  }
  public function ck_username($uname)
  {
    return true;
  }
  public function ck_password($psd)
  {
    return true;
  }
  public function ck_time($t)
  {
    return true;
  }
  public function ck_email($email)
  {
    return true;
  }
  public function ck_qqemail($qq)
  {
    return true;
  }
}
?>