<?php
/*视图输出*/
class View
{
    protected static $tVar=array(),        //模板输出变量
                    $lVar=array(),          //layout变量
                    $tplFile='',           //当前的模板文件
                    $runTimeFile='',       //缓存文件.
                    $tpl_l_delim='{{',     //标签开始
                    $tpl_r_delim='}}',     //便签结束
                    $spc_param=array('styles','scripts','livescripts'),    //特殊key值
                    $suffix='';            //模板后缀

    static function init()
    {
        self::$suffix=Conf::param('tpl_template_suffix');
        self::$runTimeFile=RUNTIME_PATH.''.md5(MODULE.'Action'.ACTION).''.self::$suffix;
    }
    static function getHeader($data)
    {
        (isset($data['title']))||($data['title']='BEETHINK');
        (isset($data['other']))||($data['other']='');
        (isset($data['charset']))||($data['charset']=Conf::param('tpl_charset'));

        $str='<!DOCTYPE html>
<html>
<head>
<meta charset="%s">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">%s
<link rel="shortcut icon" href="../Common/images/favicon.png" type="image/png">
<title>%s</title>';

        $str=sprintf($str,$data['charset'],$data['other'],$data['title']);
        if(isset(self::$tVar['styles']))
        {
            $str.=self::getFile(self::$tVar['styles'],'css');
        }
        return $str;
    }
    /*
     * 模板变量赋值
     * */
    static function assign($k,$v,$order=0)
    {
        if(in_array($k,self::$spc_param))
        {
            if($k=='livescripts')
            {
                self::$tVar[$k]=isset(self::$tVar[$k])?self::$tVar[$k].$v:$v;
            }
            else if(is_array($v))
            {
                if(isset(self::$tVar[$k]))
                {
                    self::$tVar[$k]=$order?array_merge($v,self::$tVar[$k]):array_merge(self::$tVar[$k],$v);
                }
                else
                {
                    self::$tVar[$k]=$v;
                }
            }
        }
        else
        {
            self::$tVar[$k]=$v;
        }
    }
    /*
     * 渲染layout变量,模板渲染完成后,会自动销毁 l_assign的值
     * */
    static function l_assign($k,$v,$order='')
    {
        if(in_array($k,self::$spc_param) && is_array($v))
        {
            if($k=='livescripts')
            {
                self::$lVar[$k]=isset(self::$lVar['livescripts'])?self::$lVar[$k].'<br/>'.$v:$v;
            }else if($k=='styles')
            {
                self::assign($k,$v,$order);
            }
            else if(is_array($v))
            {
                if(isset(self::$tVar[$k]))
                {
                    self::$lVar[$k]=$order?array_merge($v,self::$lVar[$k]):array_merge(self::$lVar[$k],$v);
                }
                else
                {
                    self::$lVar[$k]=$v;
                }
            }
        }
        else
        {
            self::$lVar[$k]=$v;
        }
    }
    /*
     * 获取指定的值
     * */
    static function get($k='')
    {
        return $k==''?self::$tVar:(isset(self::$tVar[$k])?self::$tVar[$k]:null);
    }
    /**
     * 获取模板文件
     */
    static function getTplFile($file)
    {
        $file=strtr($file,'.#','/.');
        if(!$file)
        {
            $fpath=TPL_PATH.''.MODULE.'/'.ACTION.''.self::$suffix;
        }
        else if(strpos($file,'core')===0)  //判断模板是否从默认模板中加载
        {
            $fpath=THINK_PATH.'Common/Tpl/'.substr($file,5).self::$suffix;
        }
        else
        {
            $fpath=TPL_PATH.$file.self::$suffix;
        }
        //判断是否为有效文件
        if(is_file($fpath))
        {
            return $fpath;
        }
        else
        {
            Error::halt(FILE_NOTFOUND,'模板文件:'.$file.'未找到');
        }
    }    
    /**
     * 加载模板和页面的输出
     *   
     * 功能：
     *  1.可指定文件保存名
     *  2.可指定文件路径
     * 
     */
    static function display($tpl='',$data=array())
    {
        self::init();

        $tplFile=self::getTplFile($tpl);
        $con= self::compiler($tplFile,self::$tVar);
        $header=self::getHeader($data);
        exit($header.$con);
    }
    /*
     * 只渲染模板,不输出
     * */
    static function layout($tpl='')
    {
        $tplFile=self::getTplFile($tpl);
        $con=self::compiler($tplFile,self::$lVar);
        self::$lVar=null;
        return $con;
    }
    //编译模板文件内容
    static function compiler($tplFile,&$param)
    {
        $content=file_get_contents($tplFile);
        $matches=self::getMatchRs($content);
        $tmp_arr=array();
        //匹配成功
        foreach($matches[0] as $k=>$v)
        {
            if(strpos($v,':'))
            {
                $tmp_arr=explode(':',$matches[1][$k]);
                $d=$tmp_arr[0]=='Layout'?self::layoutHandle($tmp_arr[1],self::$lVar):self::commonTplHandle($matches[1][$k],$param);
            }
            elseif(strpos($v,'.'))
            {
                $d=self::arrayHandle($matches[1][$k],$param);
            }
            else
            {
                $tmp=substr($matches[1][$k],1);
                if($tmp=='scripts' && isset($param['scripts']))
                {
                    $param['scripts']=self::getFile($param['scripts'],'js');
                }else if($tmp=='livescripts' && isset($param['livescripts']))
                {
                    $param['livescripts']=sprintf(self::getScriptTpl($param),$param['livescripts']);
                }
                $d=isset($param[$tmp])?$param[$tmp]:'';
            }
            $content=str_replace($v,$d,$content);
        }
        //缓存处理后的内容到 Tmp/Cache中      
        file_put_contents(self::$runTimeFile,$content);
        return $content;
    }
    /*
     * 获取脚本模板
     * */
    static function getScriptTpl(&$param)
    {
        return isset($param['scriptTml'])?$param['scriptTml']:'<script>%s</script>';
    }
    /*
     * 数组处理
     * 支持多维数组
     * */
    static function arrayHandle($data,&$param)
    {
        $tmp_arr=explode('.',substr($data,1));
        $len=count($tmp_arr);
        $d='';
        $i=1;
        if(isset($param[$tmp_arr[0]]))
        {
            $d=$param[$tmp_arr[0]];
            while($i<$len)
            {
                if(isset($d[$tmp_arr[$i]]))
                {
                    $d=$d[$tmp_arr[$i]];
                }
                else
                {
                    return '';
                }
                ++$i;
            }
            return $d;
        }
        return '';
    }
    /*
     * layout处理
     * */
    static function layoutHandle($data,&$param)
    {
        $index=strrpos($data,'.');
        $a=substr($data,0,$index);
        $m=substr($data,$index+1);

        $oA=Sys::A($a);
        return $oA::$m();
    }
    /**
     * 获取匹配的结果
     * 
     */
    static function getMatchRs($content)
    {
        $matches=array();
        $B=str_replace('{','\\{',self::$tpl_l_delim);
        $E=str_replace('}','\\}',self::$tpl_r_delim);
        //解析模板中的布局标签  /\{\{(\S+?)\}\}/
        preg_match_all('/'.$B.'(\S+?)'.$E.'/',$content,$matches);
        return $matches;
    }
    /**
     * 获取模板路径
     */
    static function getCommonTplPath($path)
    {
        $path=strtr($path,'.#','/.');
        $fPath='';
        if(strpos($path,'core')===0)
        {
            $fPath=THINK_PATH.'Common/Tpl/'.substr($path,5).self::$suffix;
        }
        else
        {
            $fPath=TPL_PATH.$path.self::$suffix;
        }
        if(!is_file($fPath))
        {
            Error::halt(FILE_NOTFOUND,'模板文件不存在:'.$path);
        }
        return $fPath;
    }
    //处理公共模板中的变量
    static function commonTplHandle($v,&$param)
    { 
      $fPath=self::getCommonTplPath($v);    
      $con=file_get_contents($fPath);
      $matches=self::getMatchRs($con);
      foreach($matches[0] as $k=>$v)
      {
        $tmp=substr($matches[1][$k],1);
        $d=isset($param[$tmp])?$param[$tmp]:'';
        $con=str_replace($v,$d,$con);
      }
      return $con;
    }
    /*
    解析出path 内层的数组元素将被打包成一个文件
    兼容 .js .css
    
    $path=array(
        'file1',
        'packname'=>array('file2','file3')
    );
    同时支持
    $path=file1;
    */
    static function getFile($path,$type)
    {
        $htmlArr=array();
        
        if(is_array($path))
        {
            return self::getPackFile($path,$type);    
        }
        else
        {
            return self::getSingleFile($path,$type);
        }
    }
    /**
        文件打包,且缓存到Runtime目录下
     */
    static function getPackFile($data,$type)
    {
       $packArr=array();
       $runtimeDir='./Runtime/';
       $suffix=$type=='css'?'.css':'.js';
       $runtimeName=''; 
        
       foreach($data as $k=>$v)
       {
            if(is_array($v))
            {
                $runtimeName=$k.$suffix;
                $runtimePath=RUNTIME_PATH.$k.$suffix;

                if(Rewrite::checkCache($runtimePath))
                {
                    $packArr[]=self::getLink($type,$runtimeName);
                    continue;
                }
                $conData=array();
                foreach($v as $v1)
                {
                    $tmpPath=self::parsePath($v1,$type);
                    $conData[]=file_get_contents($tmpPath);
                }                  
                /**
                 * 打包数据,并缓存
                 */
                $packContent=implode('',$conData);
                file_put_contents($runtimePath,$packContent);
                $packArr[]=self::getLink($type,$runtimeName);
            }
            else
            {
                $packArr[]=self::getSingleFile($v,$type);
            }
       }
       
       return implode('',$packArr);
    }
    /**
     * 单个文件
     */
    static function getSingleFile($path,$type)
    {
        $tmpPath=self::parsePath($path,$type);
        $runtimeName=basename($tmpPath);
        
        $runtimePath=RUNTIME_PATH.$runtimeName;

        if(Rewrite::checkCache($runtimePath))
        {
            return self::getLink($type,$runtimeName);
        }

        file_put_contents($runtimePath,file_get_contents($tmpPath));
        return self::getLink($type,$runtimeName);
    }
    /*
    解析地址
    */
    static function parsePath($data,$type)
    {
      $basePath='';  
      $type=ucfirst($type);
      $data=strtr($data,'.#','/.');
      $suffix=$type=='Css'?'.css':'.js';
      
      $fpath=substr($data,0,4)=='core'?THINK_PATH.'Common/'.$type.'/'.substr($data,5).$suffix:COMMON_PATH.$type.'/'.$data.$suffix;
      
      if(!is_file($fpath))
      {
        Error::halt(FILE_NOTFOUND,'需要包含的文件不存在:'.$fpath);
      }
      
      return $fpath;
    }
    /*按文件类型拼接*/
    static function getLink($type,$path)
    {
      if($type=='css')
      {
        return  '<link href="./Runtime/'.$path.'" rel="stylesheet" />';
      }
      elseif($type=='js')
      {
        return  '<script src="./Runtime/'.$path.'"></script>';
      }
    }
}
?>