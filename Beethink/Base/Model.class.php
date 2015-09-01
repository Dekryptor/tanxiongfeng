<?php
class Model
{
  //表名
  protected $trueTableName='',
            $conn=null;
  //当前数据库对象
  public function __construct($trueTableName='')
  {
    $this->trueTableName=empty($trueTableName)?$this->getModelName():$trueTableName;
    $this->db(0);
  }
  public function db($linkNum,$config=array())
  {
    static $_db=array();
    if(isset($_db[$linkNum]))
    {
        return $this->conn=$_db[$linkNum];
    }
    (empty($config))&&($config=Conf::param('db'));
    $dbType=ucfirst($config['dbtype']);
    return $this->conn=$_db[$linkNum]=new $dbType($config);
  }
  /*获取当前模型名称*/
  public function getModelName()
  {
    return substr(get_class($this),0,-5);
  }
  /*
    新增数据
  */
  public function insert($data='')
  {
    if(empty($data) && !is_array($data)){return false;} 
    //分析表达式
    $this->execute('INSERT INTO '.$this->trueTableName.$this->_parseData($data));
    return $this->getLastInsId();
  }
  /*获取插入的id*/
  public function getLastInsId()
  {
    return $this->conn->lastInsID;
  }
  /*获取列信息*/
  public function getFields()
  {
    return $this->conn->getFields($this->trueTableName);  
  }
  /*执行sql语句*/
  public function execute($sql)
  {
    return $this->conn->execute($sql);
  }
  /*获取影响的行数*/
  public function getNumRows()
  {
    return $this->conn ->numRows;
  }
  /*分析表达式*/
  public function _parseOptions($options='')
  {
    $options=trim($options);
    return empty($options)?'':' WHERE '.$options;
  }
  /*value 分析*/
  public function parseValue($value)
  {
    return '\''.$this->escapeString($value).'\'';
  }
  /*数据处理*/
  public function _parseData($data=array())
  {
    $field=array_map(array($this,'parseKey'),array_keys($data));
    $value=array_map(array($this,'parseDataByType'),$data);
    return '('.implode(',',$field).') VALUES('.implode(',',$value).')';
  }
  /*字段名分析添加`*/
  public function parseKey($key)
  {
    $key = trim($key);
    if(!preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
       $key = '`'.$key.'`';
    }
    return $key;
  }
  /*SQL指令安全过滤*/
  public function escapeString($str)
  {
    return addslashes($str);
  } 
  /*执行更新操作*/
  public function update($data=array(),$options='')
  {    
    return $this->execute('UPDATE '.$this->trueTableName.$this->_before_update($data).$this->_parseOptions($options));
  }
  /*更新之前执行*/
  public function _before_update($data=array())
  {
    $rs=array();
    foreach($data as $k=>$v)
    {
        $rs[]=$this->parseKey($k).'='.$this->parseDataByType($v);   
    }
    return ' SET '.implode(',',$rs);
  }
  /**
   * 更新操作数据处理
   */
  public function parseDataByType($data)
  {
    if(is_array($data) && isset($data[1]))
    {
        switch($data[1])
        {
            case 'int':return (int)$data[0];
            case 'float':return (float)$data[0];
            case 'double':return (double)$data[0];
            case 'ignore':return $data[0];
            case 'string':;
            default:return $this->parseValue($data[0]);
        }
    }
    else
    {
        return $this->parseValue($data);
    }
  }
  
  /*删除数据*/
  public function delete($options='')
  {
    return $this->conn->execute('DELETE FROM '.$this->trueTableName.$this->_parseOptions($options));
  }
  /*查询数据*/
  public function select($field='*',$options='',$limit='')
  {
    (empty($field))&&($field='*');
    $rs=$this->conn->query('SELECT '.$field.' FROM '.$this->trueTableName.$this->_parseOptions($options).($limit?' LIMIT '.$limit:''));
  
    return $limit==1?($rs?$rs[0]:array()):$rs;
  }
  /*SQL查询*/
  public function query($sql)
  {
    return $this->conn->query($sql);
  }
  /**
   * 变量获取
   */
   public function get($k)
   {
     return $this->conn->$k;
   }
}