<?php
class Mysql
{
  protected $connected=false,       //连接成功标识
            $linkID,                //当前的连接资源标识
            $queryStr,              //上一次执行的sql语句
            $queryID,               //上一次查询资源的标识
            $lastInsID,             //上一次插入的ID
            $numRows,               //受影响行数
            $config=array();        //配置信息
  
  /*读取配置信息*/
  public function __construct($config=array())
  {
    $this->connect($config);
  }
  public function connect($config=array())
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
    $this->linkID=$config['type']==1?mysql_pconnect($config['host'].':'.$config['port'],$config['uname'],$config['psd']):
    mysql_connect($config['host'].':'.$config['port'],$config['uname'],$config['psd']);
    
    if($this->linkID)
    {
      mysql_query('SET NAMES '.$config['charset']);
      mysql_select_db($config['dbname']) or Error::halt(603,'连接数据库:'.$config['dbname'].'失败');
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
    ($this->connected)||($this->connect());
  }
  /*执行sql查询语句*/
  public function query($str)
  {
    $this->initConnect();
    //释放上一次查询到的结果
    if($this->queryID){$this->free();}
    $this->queryID=mysql_query($str,$this->linkID) or Error::halt(QUERY_ERROR,'Query Failed:'.$str.'<br />'.mysql_error($this->linkID));
    
    if($this->queryID)
    {
      $this->numRows=mysql_num_rows($this->queryID);
      return $this->getAll();  
    }
    return false;
  }
  /*获取所有的查询数据*/
  private function getAll()
  {
    $ret=array();
    if($this->numRows>0)
    {
      while($row=mysql_fetch_assoc($this->queryID))
      {
        $ret[]=$row;
      }
    } 
    return $ret;
  }
  /*释放查询结果*/
  public function free()
  {
    ($this->queryID)&&(mysql_free_result($this->queryID));
    $this->queryID=null;
  }
  /*获取数据表的字段信息*/
  public function getFields($tableName)
  {
    $result = $this->query('SHOW COLUMNS FROM '.$this->parseKey($tableName));
    $info=array();
    if(!$result){return $info;} 
    foreach($result as $v)
    {
      $info[]=$v['Field'];
    }
    return $info;
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
  /*执行语句*/
  public function execute($str)
  {
    $this->initConnect();
    $result=mysql_query($str,$this->linkID) or Error::halt(QUERY_ERROR,'Query Failed:'.$str.'<br />'.mysql_error($this->linkID));
    $this->numRows=mysql_affected_rows($this->linkID);
    $this->lastInsID=mysql_insert_id($this->linkID);
    return $this->numRows;
  }
  /*关闭数据库*/
  public function close()
  {
    if($this->linkID){mysql_close($this->linkID);}
    $this->linkID=null;
  } 
  public function __get($key)
  {
    return isset($this->$key)?$this->$key:'';
  }
}
?>