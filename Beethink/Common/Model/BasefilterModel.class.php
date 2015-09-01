<?php
/**
 *     功能：
 *           1.对提交的数据判断是否已经设置
 *           2.用户自定义数据处理
 *           3.对数据类型进行验证
 *   创建人：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
 *          1.参数：
 *                 $data=array(
 *                    array('变量名','数据类型','用户自定义方法非函数（可选）','变量描述用于返回提示[可选]]'),
                      array('变量名','数据类型','规则'),
                      array('psd','string','password_2','密码'),
                      ...
                    ); 
      
*/
class BasefilterModel
{
    private static $data=null;
    private static $preg = array(
                'int'=>  '/^-?\d+$/' ,                        //整数(包括0)  int
                'int_1'=>  '/^\d+$/' ,                          //非负整数包括（0） negative
                'int_2'=>  '/^[0-9]*[1-9][0-9]*$/' ,          //正整数 positive
                'int_3'=>  '/^((-\d+)|(0+))$/' ,              //非正整数包括（0） positive
                'int_4'=>  '/^-[0-9]*[1-9][0-9]*$/' ,        //负数  negative
                'float_1'=> '/^\d+(\.\d)+$/' ,               //非负浮点型 包括（0）
                'float_2'=> '/^\d+\.\d+$/' ,               //正浮点型
                'float_3'=> '/^((-\d+\.\d+)|(0+))$/' ,          //非正浮点型 包括（0）
                'num'=> '/^-?\d+\.?\d*$/',            //数字
                'num_1'=> '/^\d+\.?\d*$/',           //非负数
                'num_2'=> '/^-\d+\.?\d*$/',          //非正数
                'num_3'=> '/^((0)|(0\.\d+))$/',    //小于等于0的小数
                'letters'=> '/^[A-Za-z]+$/',          //英文字母
                'capital'=> '/^[A-Z]+$/',             //大写英文字母
                'lc_letters'=> '/^[a-z]+$/',             //小写写英文字母
                'w'=>'/^\w+$/',        //字母、数字、下划线
                'psd_1'=>  '/^[a-z_][a-z0-9_]+$/',        //密码  小写
                'psd_2'=>  '/^[A-Za-z_][A-Za-z0-9_]+$/',             //密码 大小写
                'email'=>  '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/', //邮箱
                'qq'=>'/^\d{5,10}$/',       //qq
                'chinese' => '/^[\u4e00-\u9fa5]$/',//中文字符串
                'sj'=> '/^(\+?86-?)?1[3458][0-9]{9}$/',        //手机      如果是手机，可以支持手机前带上“+86”、“86”、“86-”、“+86-”前缀
                'tel'=> '/^(010|02\d{1}|0[3-9]\d{2})-\d{7,9}(-\d+)?$/',//电话只能匹配中国大陆的。02开头共三位，01开头的只允许010北京的号
                '400'=>  '/^400(-\d{3,4}){2}$/', //400电话 专为客户锁定
                'date_1'=> '/^\d{4}-?((0?[1-9])|(1[0-2]))-?(([0-2]?[1-9])|(3[0-1]))$/',       //日期 年-月-日 -可有可无
                'date_11'=> '/^\d{4}-((0?[1-9])|(1[0-2]))-(([0-2]?[1-9])|(3[0-1]))$/',       //日期 年-月-日 -必须具有
                'date_2'=> '/^\d{4}-((0?[1-9])|(1[0-2]))-(([0-2]?[1-9])|(3[0-1]))\s+(([0-1]?[0-9])|(2[0-3])):[0-5]?[0-9]$/',       //日期  年-月-日 时：分
                'date_21'=> '/^\d{4}-((0?[1-9])|(1[0-2]))-(([0-2]?[1-9])|(3[0-1]))\s+(([0-1]?[0-9])|(2[0-3])):[0-5]?[0-9]:[0-5]?[0-9]$/',       //日期  年-月-日 时：分：秒
                'postcode'=> '/^\d{6}$/',         //邮编
                'identifier'=>'/^[A-Za-z0-9]{16}$/'  //用户标志码
            );
    /**
     * get过滤器
     */
    public static function getFilter($data)
    {
        self::$data=$data;
        $rs=array();
        
        foreach($data as $v)
        {
            if(isset($_GET[$v[0]]))
            {
                $tmp=self::commonHandle($v,$_GET[$v[0]]);
                
                if($tmp===false){
                    return false;
                }
                $rs[$v[0]]=$tmp;
            }
            else
            {
                return false;
            }
        }
        return $rs;
    }
    /**
     * post过滤器
     */
    public static function postFilter($data)
    {
        self::$data=$data;
        $rs=array();
        
        foreach($data as $v)
        {
            if(isset($_POST[$v[0]]))
            {
                $tmp=self::commonHandle($v,$_POST[$v[0]]);
                if($tmp===false){
                    return false;
                }
                $rs[$v[0]]=$tmp;
            }
            else
            {
                return false;
            }
        }
        return $rs;
    }
    /**
     * common handle
     */
    private static function commonHandle($v,$v1)
    {
        if($v[1]=='string')
        {
            $v1=self::stringStrategy($v1);
            //如果值为空,则直接返回false
            if($v1==='')
            {
                return false;
            }
        }
        else
        {
            $strategy=$v[1].'Strategy';
            
            $v1=self::$strategy($v1);
        }
        //判断是否有其他规则限制,或用户自定义函数处理
        if(!isset($v[2])){return $v1;}
        
        if(is_string($v[2]))
        {
            if(isset(self::$preg[$v[2]]))
            {
                if(!preg_match(self::$preg[$v[2]],$v1))
                {
                    Sys::D('Return')->returnJson(FAIL,(isset($v[3])?$v[3]:'').'格式错误',array('msgCode'=>FORMAT_ERROR));
                }
            }
            else
            {
                exit('filter过滤规则尚未定义:'.$v[2]);
            }
        }
        else
        {
            $v1=call_user_func($v[2],$v1);
        }
        return $v[1]=='string'?$v1:(int)$v1;
    }
    /**
     * string类型 处理
     */
    private static function stringStrategy($val)
    {
        return addslashes(urldecode(trim($val)));
    }
    /**
     * int 类型
     */
     private static function intStrategy($val)
     {
        return (int)$val;
     }
     /**
      * float 类型
      */
      private static function floatStrategy($val)
      {
        return (float)$val;
      }
      /**
       * double 类型
       */
      private static function doubleStrategy($val)
      {
        return (double)$val;
      }
}
?>