<?php
/**
 * 数据返回处理
 */
class JsonReturnDecode
{
    static function output($code,$data,$ext=null)
    {
        $rs=array();
        if($code==SUCCESS)
        {   
            if(is_array($ext))
            {
                ($err_msg=array_shift($ext))||($err_msg='');
                $rs=array(
                    'status'=>array(
                        'msg'=>$err_msg,
                        'code'=>SUCCESS
                    )
                );
                $rs=array_merge($rs,$data);
            } 
            else
            {
                $rs=array(
                    'status'=>array(
                        'msg'=>$ext,
                        'code'=>SUCCESS,
                    ),
                    'data'=>$data
                );
            }   
        }else
        {
            if(is_array($ext))
            {
                $rs=array(
                    'code'=>$code,
                    'msg'=>$data
                );
                $rs=array_merge($rs,$ext);
                
                $rs=array('status'=>$rs);
            }
            else
            {
                $rs=array(
                    'status'=>array(
                        'code'=>$code,
                        'msg'=>$data,
                        'request'=>$ext
                    )
                );
                
            }
        }
        self::mybase_url($rs);
        exit(urldecode(json_encode($rs)));
    }
    //编码处理
    static function mybase_url(&$arr)
    {
        foreach ( ( array ) $arr as $k => $v ) 
        {
            if (!is_array( $v ))
            {
                if($v=='::')
                {
                    $arr[$k]=null;
                }
                else
                {
                    $arr[$k]=urlencode(str_replace(array('\"','[',']','{','}'),'',$v));
                }
        	}
            else
            {
                $arr[$k]=self::mybase_url($v);
        	}
        }
    	return $arr;
    }
}