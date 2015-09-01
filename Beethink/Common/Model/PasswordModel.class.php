<?php
/**
 * 密码 强度检查
 */
class PasswordModel
{
  public function checkPsd($psd)
  {
    return $this->checkLevel($psd);
  } 
  /**
   * 判断当前字符类型
   */
  private function charMode($code)
  {
    if($code>=48 && $code<=57) //数字
    {
      return 1;
    }
    if($code>=65 && $code<=90)  //大写字母
    {
      return 2;
    }
    if($code>=97 && $code<=122)  //小写字母
    {
      return 4;
    }
    return 8;  //特殊字符
  }
  /**
   * 计算当前密码一共有多少种类型
   */
  private function bitTotal($num)
  {
    $modes=0;
    for($i=0;$i<4;$i++)
    {
      if($num & 1){
        ++$modes;
      }
      $num>>=1;
    }
    return $modes;
  }
  /**
   * 判断当前密码强度级别
   */
  private function checkLevel($psd)
  {
    $len=strlen($psd);
    $modes=0;
    $i=0;
    if($len<7)  //密码太短
    {
      return 0;   
    }
    for(;$i<$len;$i++)
    {
      $modes|=$this->charMode(ord(substr($psd,$i,1)));
    }
    return $this->bitTotal($modes);
  }
}
?>