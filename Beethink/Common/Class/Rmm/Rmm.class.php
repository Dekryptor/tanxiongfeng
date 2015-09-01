<?php
/**
 * @author BEE
 * @copyright 2014
 * 功能描述:
 *  1.实现中英文分离
 *  2.提供正向与逆向最大匹配功能
 *  3.使用前需实现 self::inDic($key) 方法
 */
class Rmm
{
   static $RankDic=array(),  //词典
            $conn=null,
            $SourceStr='',
            $SplitStr='',
            $matched=array(),     //已经匹配的内容
            $SplitChar=' ';  //整理字符串时添加的中间字符
  static function init()
  {
    self::$conn=mysql_connect('localhost','root','');
    mysql_select_db('beelibrary');
    mysql_query('set names utf8');
  }
  //判断某中文词是否在词典中
  static function inDic($key)
  {
    if(isset(self::$matched[$key]))
    {
      return self::$matched[$key];
    }
    $len=strlen($key);
    $sql='SELECT d_id FROM tbl_dic WHERE d_len='.$len.' AND d_name=\''.$key.'\' LIMIT 1';
    $rs=mysql_query($sql);
    $flag=mysql_fetch_row($rs)?true:false;
    self::$matched[$key]=$flag;
    return $flag;
  }

  /*
    分词算法 入口
    返回最大匹配结果 
  */
  static public function SplitRMM($str)
  {
    if($str=='') return '';
    //中英文分割
    $splitStr=self::UpdateStr($str);
    $tmpData=explode(' ',$splitStr);
    
    //将中文进行双向匹配
    $rs=array();
    foreach($tmpData as $v)
    {
      if(self::isGBK($v))
      {
        if(strlen($v)>9)
        {
          $rs=array_merge($rs,self::match($v));
        }
        else
        {
          $rs[]=$v;
        }
      }
      else
      {
        $rs[]=strtolower($v);
      }
    }
    return $rs;
  }
  /*
    双向最大匹配
    综合正,逆向匹配方式
    返回匹配成功最长的结果
  */
  static function match($str)
  {
    $forward=self::forwardMatch($str);
    $reverse=self::reverseMatch($str);
    $tFLen=strlen(implode('',$forward));
    $tRLen=strlen(implode('',$reverse));
    return $tFLen>$tRLen?$forward:$reverse;
  }
  /*
  获取数组元素中长度最长的
  */
  /*
  正向最大匹配
  最大长度为7
  最小长度为2
  */
  static function forwardMatch($str)
  {
    $limit=21;  // 词库中最大词长度
    $sLen=strlen($str);
    $rs=array();
    //分割到长度为0
    while($sLen>0)
    {
      $flag=false;
      $cur=substr($str,0,$limit);
      $curLen=strlen($cur);
      for($curLen;$curLen>3;$curLen-=3)
      {
        $wd=substr($cur,0,$curLen);
        if(self::inDic($wd))
        {
          $flag=true;
          $rs[]=$wd;
          $str=substr($str,$curLen);
          $sLen-=$curLen;
          break;
        }
      }
      if(!$flag)
      {
        //$rs[]=substr($cur,0,3);  丢弃未匹配的词
        $str=substr($str,3);
        $sLen-=3;
      } 
    }
    return $rs;
  }
  /*
  逆向最大匹配
  最大词长度 7
  最小词长度 2
  */  
  static function reverseMatch($str)
  {
    $limit=21;
    $sLen=strlen($str);
    $rs=array();
    while($sLen>0)
    {
      $flag=false;
      $cur=substr($str,-$limit);
      $curLen=strlen($cur)-3;
      for($i=0;$i<$curLen;$i+=3)
      {
        $wd=substr($cur,$i);
        if(self::inDic($wd))
        {
          $flag=true;
          array_unshift($rs,$wd);
          $sLen=$sLen-($limit-$i);
          $str=substr($str,0,$sLen);
          break;
        }
      }
      if(!$flag)
      {
        //array_unshift($rs,substr($cur,-3)); 丢弃未匹配的词
        $str=substr($str,0,-3);
        $sLen-=3;
      }
    }
    return $rs;
  }
  /*字符串处理 分离中/英*/
  static public function UpdateStr($str)
  {
      $spc=self::SplitChar;
      $slen=strlen($str);
      $okstr='';$code=0;
      $prechar=0;     //0-空白 1-英文 2-中文 3-符号
      for($i=0;$i<$slen;$i++)     
      {
        $code=ord($str[$i]);
        if($code<0x81)    //英文
        {
          if($code<0x33)  //英文的空白符
          { 
            if($prechar!=0 && $str[$i]!="\r" && $str[$i]!="\n") $okstr.=$str[$i];
            $prechar=0;
            continue;
          }
          else if(preg_match("/[^0-9a-zA-Z@\.%#:\/\&_-]/",$str[$i]))
          {
            if($prechar==0)
            {
              $okstr.=$str[$i];
              $prechar=3;
            }
            else
            {
              $okstr.=$spc.$str[$i];
              $prechar=3;
            }
          }
          else
          {
            if($prechar==3 || $prechar==2)
            {
              $okstr.=$spc.$str[$i];
              $prechar=1;
            }
            else
            {
              if(preg_match("/[@#%:]/",$str[$i]))
              {
                $okstr.=$str[$i];$prechar=3;
              }
              else
              {
                $okstr.=$str[$i];
                $prechar=1;
              }
            }
          }
        }
        else
        {
          if($prechar!=0 && $prechar!=2) $okstr.=$spc;   //utf8下中文占三个字节
          $c=substr($str,$i,3);                          //$str[$i].$str[$i+1].$str[$i+2];
          $n=hexdec(bin2hex($c));
          if($n<0xA13F && $n>0xAA40)    ////中文符号
          {
            if($prechar!=0) $okstr.=$spc.$c;
            $prechar=3;
          }
          else       //汉字
          {
            $okstr.=$c;
            $prechar=2;
          }
          $i+=2;   //+2是因为for 中的i还会自加1
        }
      }
      return $okstr;
  }
  static function isGBK($str)     //中文返回false
  {
    return ord($str)>0x80;
  }
}
/*
$rs=array();
$str='mysql语句';

$rmm=new RMM();
$rmm->SplitRMM($str);
*/