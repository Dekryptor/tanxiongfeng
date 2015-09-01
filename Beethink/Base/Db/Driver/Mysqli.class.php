<?php
class Mysqli
{
  //连接成功标识
  protected $connected=false,
  //当前的连接资源标识
  $_linkID=array(),
  $linkID,
  //上一次执行的sql语句
  $queryStr,
  //上一次查询资源的标识
  $queryID,
  //上一次插入的ID
  $lastInsID,
  //受影响行数
  $affected_rows,
  $numRows,
  $numFields;
   
  //配置信息
  private $config=array();
  /*读取配置信息*/
  public function __construct($config='')
  {
    if(!empty($config))
    {
      $this->config=$config;
    }
  }
  public function connect($config=array(),$linkNum=0)
  {
    if(isset($this->_linkID[$linkNum])){return $this->_linkID[$linkNum];}
    if(empty($config)){$config=$this->config;}
    $port=isset($config['port'])?$config['port']:3306;
    @$this->linkID=new Mysqli($config['host'],$config['uname'],$config['psd'],$config['dbname'],$port);
    if(mysqli_connect_errno())
    {
      halt(500,'数据库连接问题');
    }
    $this->_linkID[$linkNum]=$this->linkID;
    $this->linkID->query('SET NAMES '.$config['charset']);
    $this->connected=true;
  }
  public function initConnect()
  {
    if(!$this->connected) $this->connect();
  }
  public function query($str)
  {
    $rs=array();
    $this->initConnect();

    $this->queryID=$this->linkID->query($str);
    if(!$this->queryID)
    {
      halt(501,'数据库查询失败'.$str);
    }
    else
    {
      $this->numRows=$this->queryID->num_rows;
      $this->numFields=$this->queryID->field_count;
      
      while($rows=mysqli_fetch_array($this->queryID))
      {
        $rs[]=$rows;
      }
    }
    $this->free();
    return $rs;
  }
  //释放结果集
  private function free()
  {
    if($this->queryID)
    {
      mysqli_free_result($this->queryID);
      $this->queryID=null;
    }
  }
  /*对表名和字段加上` */
  private function parseKey($key)
  {
    $key = trim($key);
    if(!preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
       $key = '`'.$key.'`';
    }
    return $key;
  }
  /*获取数据表的字段信息*/
  public function getFields($tableName)
  {
    $this->initConnect();
    $result = $this->query('SHOW COLUMNS FROM '.$this->parseKey($tableName));
    $info=array();
    
    if(!$result){return $info;} 
    foreach($result as $v)
    {
      $info[]=$v['Field'];
    }
    return $info;
  }
  /*执行语句*/
  public function execute($str)
  {
    $this->initConnect();
    $this->queryStr=$str;
    /*释放前一次的查询结果*/
   
    $result=$this->linkID->query($str);
    if($result)
    {
      $this->affected_rows=$this->linkID->affected_rows;
      $this->lastInsID=mysqli_insert_id($this->linkID);
    }
    return $this->affected_rows;
  }
  
  /*关闭数据库*/
  public function close()
  {
    if($this->linkID){mysqli_close($this->linkID);}
    $this->linkID=null;
  } 
  public function __get($key)
  {
    return isset($this->$key)?$this->$key:null;
  }
}
//$config
//$config=array(
//  'dbname'=>'test',
//  'host'=>'localhost',
//  'port'=>'3306',
//  'psd'=>'',
//  'charset'=>'utf8',
//  'uname'=>'root'
//);
//$db=new db_Mysqli($config);
//$db->connect();
//$rs=$db->query('select * from ecs_admin_action');
//$rows=$db->execute('insert into ecs_adsense values(122,"adfddf",300)');
//$fields=$db->getFields('ecs_adsense');
?>