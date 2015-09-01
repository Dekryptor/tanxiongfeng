<?php
class DbMysqli
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
      if($this->linkID)
      {
          return $this->linkID;
      }
      if($config)
      {
          $this->config=$config;
      }
      else
      {
          $config=$this->config;
      }
      $this->linkID= mysqli_connect($config['host'].':'.$config['port'],$config['uname'],$config['psd']);

      if($this->linkID)
      {
          mysqli_query($this->linkID,'SET NAMES '.$config['charset']);
          $this->linkID->select_db($config['dbname']) or Error::halt(603,'连接数据库:'.$config['dbname'].'失败');;
          $this->connected=true;
          return true;
      }
      else
      {
          Error::halt(CONNECT_FAIL,$config);
      }
  }
  public function initConnect()
  {
    if(!$this->connected){
        $this->connect();
    }
  }
  public function query($str)
  {
    $rs=array();
    $this->initConnect();

    $this->queryID=$this->linkID->query($str);

    if(!$this->queryID)
    {
        Error::halt(501,'执行sql语句出错:'.$str);
    }
    else if(is_bool($this->queryID))
    {
        $this->numRows=mysqli_affected_rows($this->linkID);
    }
    else
    {
        $this->numRows=mysqli_affected_rows($this->linkID);
        $this->numFields=$this->queryID->field_count;

        for($i=0;$i<$this->numRows;$i++)
        {
            $rs[]=$this->queryID->fetch_assoc();
        }
        $this->free();

        return $rs;
    }
  }
  //释放结果集
  private function free()
  {
      mysqli_free_result($this->queryID);
      $this->queryID=null;
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
        $info[] = $v['Field'];
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
      $this->affected_rows=mysqli_affected_rows($this->linkID);
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
?>