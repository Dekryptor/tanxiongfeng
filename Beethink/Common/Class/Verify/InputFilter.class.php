<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2015/7/6 0006
 * Time: 13:47
 * 输入数据校验过滤
 */
class InputFilter
{
    private static $filterRefer=array('get','post');
//    定义一些常用的过滤规则
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
        'chinese' => '/^[\x4e00-\x9fa5]$/',//中文字符串
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
    /*
     * dataFilter
     *
     * 默认值默认 ::　表示改值是必须的，如果未定义该值，则会错误提示
     *
     * */
    static function dataFilter($data,$type)
    {
        $retData=array();
        $default_val='';
        foreach($data as $k=>$v)
        {
            $default_val=isset($v[0])?$v[0]:'::';

            $val=self::getVal($k,$default_val,$type);
            (isset($v[1]))||($v[1]='');
            (isset($v[2]))||($v[2]='');
            (isset($v[3]))||($v[3]='');
            if($val=='::')
            {
                self::errorHandle('unlawful request (parameter is not complete)');
            }else if($val==$default_val)
            {
                $retData[$k]=$default_val;
            }else
            {
                $retData[$k]=self::wordProcessing($val,$v[1],$v[2],$v[3]);
            }
        }

        return $retData;
    }
    /*
     * get the special value
     * */
    static function getVal($k,$default_val,$type)
    {
        switch($type)
        {
            case 'post':
                return isset($_POST[$k])?$_POST[$k]:$default_val;
            case 'get':
                return isset($_GET[$k])?$_GET[$k]:$default_val;
            case 'cookie':
                return isset($_COOKIE[$k])?$_COOKIE[$k]:$default_val;
            case 'session':
                return isset($_SESSION[$k])?$_SESSION[$k]:$default_val;
            case 'request':
                return isset($_REQUEST[$k])?$_REQUEST[$k]:$default_val;
            default:
                self::errorHandle('undefined request type : '.$type);
        }
    }
    /*
     * 对词进行相应的处理
     *
     * $rule=>指定规则
     * $rule_detail=>指定规则的内容
     * $memo=>当数据不符合指定规则时，显示相应提示
     * */
    static function wordProcessing($val,$rule,$rule_detail,$memo='')
    {
        $rule=strtolower($rule);
        $ret_val='';

        switch($rule)
        {
            case 'int':
                $ret_val=(int)$val;
                break;
            case 'string':
                $ret_val=addslashes($val);
                break;
            case 'double':
                $ret_val=(double)$val;
                break;
            case 'float':
                $ret_val=(float)$val;
                break;
            case 'regexp':
                $ret_val=self::regularExpression($val,$rule_detail);
                break;
            case 'regexp_defined':
                if(isset(self::$preg[$rule_detail]))
                {
                    $ret_val=self::regularExpression($val,self::$preg[$rule_detail]);
                    break;
                }
                else
                {
                    self::errorHandle('regexp not found : '.$rule_detail);
                }
            case 'function':
                $ret_val=call_user_func($rule_detail,$val);
                break;
            case 'xss':
                $ret_val=Sys::D('Xss.Xss')->RemoveXSS($val);
                break;
            case 'unique':
                $flag=self::ifUnique($val,$rule_detail[0],$rule_detail[1]);
                $ret_val=$flag?false:$val;
                break;
            case 'not_unique':
                $flag=self::ifUnique($val,$rule_detail[0],$rule_detail[1]);
                $ret_val=$flag?$val:false;
                break;
            case 'length':
                $ret_val=self::lengthCheck($val,$rule_detail);
                break;
            case 'multi':
                $ret_val=self::multi($val,$rule_detail,$memo);
                break;
            case 'ignore':
                $ret_val=$val;
                break;
            default:
                $ret_val=addslashes($val);
                break;
        }
        if($ret_val===false)
        {
            self::errorHandle($memo?$memo:'illegal data');
        }
        return $ret_val;
    }
    /*
     * 长度检测
     * */
    static function lengthCheck($val,$rule_detail)
    {
        $len=strlen($val);

        if(is_array($rule_detail))
        {
            if($rule_detail[0]<=$len && $rule_detail[1]>=$len) {
                return $val;
            }
        }
        else
        {
            if($rule_detail==$len)
            {
                return $val;
            }
        }
        return false;
    }
    /*
     * 多个条件
     * */
    static function multi($val,$rule_detail,$memo='')
    {
        $ret_val='';
        foreach((Array)$rule_detail as $v)
        {
            (isset($v[1]))||($v[1]='');
            (isset($v[2]))||($v[2]=$memo);
            $ret_val=self::wordProcessing($val,$v[0],$v[1],$v[2]);
        }
        return $ret_val;
    }
    /*
     * 判断是否唯一
     * */
    static function ifUnique($val,$table_name,$where)
    {
        $where=sprintf($where,$val);
        $rs=Sys::M($table_name)->select('COUNT(0) AS total',$where,1);

        return $rs['total'];
    }

    /*
     * regular expression
     * */
    static function regularExpression($val,$rule_detail)
    {
        return preg_match($rule_detail,$val)?$val:false;
    }
    /*
     * error handle
     * */
    static function errorHandle($data)
    {
        if(defined('AJAX_REQUEST') && AJAX_REQUEST==1)
        {
            Exception::redirect('error',$data,5);
        }
        else
        {
            $ret=Sys::S('core.JsonReturn');
            JsonReturn::output(FAIL,$data);
        }
    }
}
/*
 * $data=array(
            'sex'=>array(null,'regexp_defined','int_1','数据格式不正确'),
            'psd'=>array(null,'regexp_defined','psd_2','密码格式错误'),
            'tel'=>array(null,'regexp_defined','sj','手机格式错误'),
            'regexp'=>array(null,'regexp','/^(name)$/'),
            'haha'=>array('test',''),
            'content'=>array(null,'xss'),
            'int'=>array(1,'int'),
            'string'=>array(null,'string'),
            'ignore'=>array('','ignore'),
            'length'=>array('','length',array(5,7),'长度不合适'),
            'function'=>array('test','function',array($this,'test')),
            'unique'=>array(null,'unique',array('sys_menu','title=\'%s\''),'标题不唯一哦'),
            'not_unique'=>array(null,'not_unique',array('sys_menu','title=\'%s\''),'标题存在唯一性'),
            'multi'=>array(null,'multi',array(
                array('length',array(5,9)),
                array('string'),
                array('function',array($this,'test'))
            ),'数据格式不支持'),
        );
 * */