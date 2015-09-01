<?php
/**
 * 密码 强度检查
 */
class Password
{
  static function checkPsd($psd)
  {
    return self::checkLevel($psd);
  }

  /**
   * 判断当前字符类型
   */
  static function charMode($code)
  {
    if ($code >= 48 && $code <= 57) //数字
    {
      return 1;
    }
    if ($code >= 65 && $code <= 90)  //大写字母
    {
      return 2;
    }
    if ($code >= 97 && $code <= 122)  //小写字母
    {
      return 4;
    }
    return 8;  //特殊字符
  }

  /**
   * 计算当前密码一共有多少种类型
   */
  static function bitTotal($num)
  {
    $modes = 0;
    for ($i = 0; $i < 4; $i++) {
      if ($num & 1) {
        ++$modes;
      }
      $num >>= 1;
    }
    return $modes;
  }

  /**
   * 判断当前密码强度级别
   */
  static function checkLevel($psd)
  {
    $len = strlen($psd);
    $modes = 0;
    $i = 0;
    if ($len < 7)  //密码太短
    {
      return 0;
    }
    for (; $i < $len; $i++) {
      $modes |= self::charMode(ord(substr($psd, $i, 1)));
    }
    return self::bitTotal($modes);
  }
}