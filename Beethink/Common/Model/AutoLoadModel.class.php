<?php
/**
 * 功能:模块自动加载
 * 创建人:BEE
 * 
 * 描述:
 *  1.文件冲突检测
 *  2.数据库冲突检测
 *  3.自动生成Action文件
 *  4.自动生成Model文件
 * 
 */
 
define('ERROR',0);      //错误
define('CONFLICT',1);   //冲突
define('NOPOWER',2);           //无创建文件权限

class AutoLoadModel
{
    protected $module_name='',                                                     //模型名
              $sqlTypeFilter=array('table','procedure','view','function'),         //数据库支持类型
              $module_path='',                                                     //模型位置
              $conn=null,                                                          //数据库句柄
              $dbData=array(),                                                     //数据库配置信息
              $dealData=array(),                                                   //冲突数据解决方式
              $flag=false,                                                         //处理状态
              $data=array(),                                                       //最终分析的数据
              $error=array();                                                      //错误信息
    
    public function init($module_name,$dealData=array(),$flag=false)
    {
        $this->module_name=ucfirst($module_name);
        $this->module_path=THINK_PATH.'Common/Modules/'.$this->module_name.'/';
        $this->dealData=$dealData;
        $this->flag=$flag;
        
        $this->checkConflict();
    }
    public function conn($dbData)
    {
        $this->dbData=$dbData;
        $this->conn=mysql_connect($dbData['host'].':'.$dbData['port'],$dbData['username'],$dbData['psd']);
        mysql_query('SET NAMES '.$dbData['charset']);
        mysql_select_db($dbData['dbname'],$this->conn);
    } 
    /**
     * 文件冲突检测
     * 
     * 指定对应冲突文件,是覆盖或者忽略
     * 
     * 处理方式值 0=>忽略 1=>覆盖
     * 
     * $dealData=array(
     *  'table'=>array('表名'=>'处理方式'),
     *  'procedure'=>array('存储过程名'=>'处理方式'),
     *  'view'=>array('视图名'=>'处理方式'),
     *  'function'=>array('函数名'=>'处理方式'),
     *  'module'=>array('模型名'=>'处理方式'),
     *  'action'=>array('控制器名'=>'处理方式')
     * );
     */
    private function checkConflict()
    {
        $dir=scandir($this->module_path);
      
        $action_file=array();
        $model_file=array();
        $sql_file=array();
        
        foreach($dir as $v)
        {
            if($v=='.' || $v=='..'){continue;}
            
            if($v=='Action')
            {
               $action_file=$this->commonCheck($v,'Action');    
                
            }else if($v=='Model')
            {
                $model_file=$this->commonCheck($v,'Model');
                
            }else if($v=='Mysql')
            {
                $sql_file=$this->sqlCheck($v);
            } 
        }
        
        if($this->error && $this->flag===false)  //如果未处理,则通知处理
        {
            $this->showError();
        }
        else    //按照处理的方式进行操作
        {
          $this->createData();  //数据自动创建
          
          $this->halt('<span style="color:green;">('.$this->module_name.')模块生成成功!</span>');
        }     
    }
    /**
     * 数据自动创建
     */
    private function createData()
    {
        foreach($this->data as $k=>$v)
        {
          call_user_func(array($this,$k.'Strategy'),$v);  
        }   
    }
    /**
     * 创建action
     */
    private function actionStrategy($data)
    {
        $this->copyHandle($data,'action');
    }
    /**
     * 创建model
     */
    private function modelStrategy($data)
    {
        $this->copyHandle($data,'model');
    }
    /**
     * 数据表创建
     */
    private function tableStrategy($data)
    {
        foreach($data as $v)
        {
            $rs=M('')->execute('DROP TABLE IF EXISTS `'.$v.'`');
            $rs=M('')->execute('CREATE TABLE `'.$v.'` LIKE `'.$this->dbData['dbname'].'`.`'.$v.'`');
        }
        return true;
    }
    
    /**
     * 视图创建
     */
    private function viewStrategy($data)
    {
        foreach($data as $v)
        {
            $rs=M('')->execute('DROP VIEW IF EXISTS `'.$v.'`');
            $rs=M('')->execute('CREATE VIEW `'.$v.'` LIKE `'.$this->dbData['dbname'].'`.`'.$v.'`');
        }
        return true;
    }
    /**
     * 存储过程创建
     */
    private function procedureStrategy($data)
    {
        $oM=M('');
        $conf=$oM->get('config');
        foreach($data as $v)
        {
            $rs=$this->query('SHOW PROCEDURE STATUS WHERE `Name`=\''.$v.'\' AND `Db`=\''.$this->dbData['dbname'].'\'');
            
            if($rs)
            {
                $tpl=$this->query('SHOW CREATE PROCEDURE `'.$v.'`');
                M('')->execute('DROP PROCEDURE IF EXISTS '.$v);
                M('')->execute($tpl[0]['Create Procedure']);
            }
            else
            {
                $this->halt('<span style="color:RED">procedure:'.$v.'</span>在 '.$this->dbData['dbname'].' 库中不存在!');
            }
        }
    }
    /**
     * mysql函数
     */
    private function functionStrategy($data)
    {
        $oM=M('');
        $conf=$oM->get('config');
        
        //设置创建函数的功能开启
        M('')->execute('set global log_bin_trust_function_creators=1');
        
        foreach($data as $v)
        {
            $rs=$this->query('SHOW FUNCTION STATUS WHERE `Name`=\''.$v.'\' AND `Db`=\''.$this->dbData['dbname'].'\'');  //判断库中是否已经存在
            
            if($rs)
            {
                $tpl=$this->query('SHOW CREATE FUNCTION `'.$v.'`');
                M('')->execute('DROP FUNCTION IF EXISTS '.$v);
                M('')->execute($tpl[0]['Create FUNCTION']);
            }
            else
            {
                $this->halt('<span style="color:RED">function : '.$v.'</span>在 '.$this->dbData['dbname'].' 库中不存在!');
            }
        }
    }
    /**
     * 文件复制处理
     */
    private function copyHandle($file_arr,$type)
    {
        $type=strtolower($type);
        $dst_dir=$type=='action'?ACTION_PATH:MODULE_PATH;
      
        foreach($file_arr as $v)
        {
            copy($v,$dst_dir.basename($v));
        }
        
        return true;
    }
    /**
     * 数据库生成
     */
    private function sqlHandle($file_arr)
    {
        foreach($file_arr as $v)
        {
            $this->parseSQL($v);
        }
    }
    /**
     * 判断是否覆盖
     */
    private function ifCover($typeName,$v)
    {
        $typeName=strtolower($typeName);
        return isset($this->dealData[$typeName]) && in_array($v,$this->dealData[$typeName])?1:0;
    }
    /**
     * action model公共检测模块
     */
    private function commonCheck($v,$type)
    {
        $fpath=$type=='Action'?$this->module_path.'Action/':$this->module_path.'Model/';
        $file_arr=scandir($fpath);
        $baseDir=$type=='Action'?ACTION_PATH:MODULE_PATH;
        $retArr=array();
        
        foreach($file_arr as $v)
        {
            if($v=='.' || $v=='..'){continue;}
            $tmpPath=$baseDir.$v;
            
            if(is_file($tmpPath))  //如果文件已经存在
            {
                if($this->flag)  //判断是否覆盖处理
                {
                    if( $this->ifCover($type,$v) )
                    {
                        $this->setData($type,$fpath.$v);  
                    }
                }
                else
                {
                    $this->error(CONFLICT,strtolower($type),$v);    
                }
            }
            else
            {
                $this->setData($type,$fpath.$v);
            }        
        }
        return true;
    }
    /**
     * 设置数据
     */
    private function setData($type,$val)
    {
        $type=strtolower($type);
        if(!isset($this->data[$type]))
        {
            $this->data[$type]=array();
        }
        
        $this->data[$type][]=$val;
    }
    /**
     * 数据库处理
     */
    private function sqlCheck($v)
    {
        $fpath=$this->module_path.'/Mysql/conf.php';
        
        if(!is_file($fpath))
        {
            return true;
        }
        
        //检查对应文件是否存在
        require($fpath);
       
        foreach($conf as $k=>$v)
        {
            if(in_array($k,$this->sqlTypeFilter))
            {
                
                call_user_func(array($this,$k.'CheckStrategy'),$v);
            }
            else
            {
                $this->halt('检查到非法数据:'.(is_array($v)?var_dump($v):$v));
            }
        }
    }
    /**
     * 错误信息展示
     */
    private function showError()
    {
        $htmlArr=array();
        
        $htmlArr[]='<h2>提示:检测到数据冲突,选择解决方案</h2>';
        $htmlArr[]='<p style="color:#CCC;font-size:12px;">勾选表示将替换掉指定数据!!!</p>';
        
        $htmlArr[]='<form action="" method="POST">';
        
        foreach($this->error as $k=>$v)
        {
            $htmlArr[]='<span style="font-size:12px;">数据类型:</span><span style="color:RED;font-weight:800;">'.$k.'</span>';
            $htmlArr[]='<hr />';
            
            foreach($v AS $v1)
            {
                $htmlArr[]='<input name="'.$k.'[]" style="vertical-align:middle;" type="checkbox" value="'.$v1.'" />'.$v1;
            }
            $htmlArr[]='<hr />';
        }
        
        
        $htmlArr[]='<p><input style="width:100px;height:30px;" name="sub" type="submit" value="确定" /></p>';
        $htmlArr[]='</form>';
        exit(implode('',$htmlArr));
    }
    /**
     * 查询
     */
    private function query($sql)
    {
       $rs=mysql_query($sql,$this->conn);
       $retData=array();
       
       while($row=@mysql_fetch_assoc($rs))
       {
            $retData[]=$row;
       }
       return $retData;
    }
    /**
     * 执行
     */
    public function execute($sql)
    {
        $rs=mysql_query($sql,$this->conn);
        return mysql_affected_rows($this->conn);
    }
    /**
     * 数据表检查
     */
    private function tableCheckStrategy($data)
    {
        foreach($data as $v)
        {
            $rs=M('')->query('SHOW TABLES LIKE \''.$v.'\'');

            $this->sqlDataFilter('table',$v,$rs);
            
        }
    }
    /**
     * 数据库对应类型数据控制
     */
    private function sqlDataFilter($type,$data,$rs)
    {
        if($rs)
        {
            if($this->flag)
            {
                if($this->ifCover($type,$data))
                {
                    $this->setData($type,$data);
                }
            }
            else
            {
                $this->error(CONFLICT,$type,$data);
            }
        }
        else
        {
            $this->setData($type,$data);
        }
        
    }
    /**
     * view 检测
     */
     private function viewCheckStrategy($data)
     {
        foreach($data as $v)
        {
            $rs=M('')->query('SHOW TABLES LIKE \''.$v.'\'');
            $this->sqlDataFilter('view',$v,$rs);
        }
     }
     /**
      * procedure检测
      * 
      */
     private function procedureCheckStrategy($data)
     {
        $oM=M('');
        $conf=$oM->get('config');
        foreach($data as $v)
        {
            $rs=$oM->query('SHOW PROCEDURE STATUS WHERE `Name`=\''.$v.'\' AND `Db`=\''.$conf['dbname'].'\'');
            $this->sqlDataFilter('procedure',$v,$rs);
        }
     }
     /**
      * Function 检查
      */
     private function functionCheckStrategy($data)
     {
        $oM=M('');
        $conf=$oM->get('config');
        foreach($data as $v)
        {
            $rs=$oM->query('SHOW FUNCTION STATUS WHERE `Name`=\''.$v.'\' AND `Db`=\''.$conf['dbname'].'\'');
            $this->sqlDataFilter('function',$v,$rs);
        }
     }
    //从文件中逐条取SQL
    function parseSQL($sqlfile) 
    {
        $sql=file_get_contents($sqlfile);
        $arr=explode(';',$sql);
        $oDb=M('Test');
       
        foreach($arr as $v)
        {
            $str=trim($v);
            if($str)
            {
                $oDb->execute($str);    
            }
        }
    }
    
    /**
     * 错误处理
     */
    private function error($no,$type,$name='')
    {
        if($no==CONFLICT)
        {
            if(!isset($this->error[$type]))
            {
                $this->error[$type]=array();
            }
            $this->error[$type][]=$name;   
        }
    }
    /**
     * 目录结构生成
     */
    public function builtDir($name)
    {
        $name=ucfirst($name);
        $module_path=THINK_PATH.'Common/Modules/'.$name.'/';
        //判断目录是否已经存在
        $dir_arr=array(
            $module_path,
            $module_path.'Action/',
            $module_path.'Model/',
            $module_path.'Doc/',
            $module_path.'Mysql/'
        );
        
        foreach($dir_arr as $v)
        {
            if(!is_dir($v))
            {
                if(!mkdir($v,777))
                {
                    $this->halt('<span style="color:RED">模块('.$name.')在创建目录过程中,无执行权限</span>');
                }
            }
        }
        /**
         *  自动生成文件
         */
         $sqlFile=$module_path.'Mysql/conf.php';
         
         if(!is_file($sqlFile))
         {
           touch($sqlFile);
           //写入默认数据
           $content='<?php
$conf=array(
    \'table\'=>array(),
    \'procedure\'=>array(),
    \'view\'=>array()
);
?>';
           file_put_contents($sqlFile,$content); 
         }
        $this->halt('<span style="color:GREEN;font-weight:800;">'.$name.'</span>模块目录生成成功!');
    }
    /**
     * 出错处理
     */
    public function halt($error_msg)
    {
        exit($error_msg);
    }
}
?>