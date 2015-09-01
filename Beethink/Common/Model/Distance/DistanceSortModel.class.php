<?php
/**
 *     功能：通用排序模型
 *   创建人：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
*/

class DistanceSortModel
{
  protected $PG=1,      //当前页
            $startPage=1, //起始位置
            $trueTableName='',
            $pageSize=15, //获取记录数
            $keepLength=10, //最终需要保留的长度
            $limit='',      //记录数限制
            $sortData=array(),  //待排序的数组数据
            $lat=0,         //纬经度
            $lng=0,
            $sql='',      //查询语句
            $countSql='',  //统计数量语句
            $totalCount=0,   //总记录数
            $handle=null;   //数据库操作柄 
  /**
	 *     功能：
     *       by：
     *     参数：
     * $data=array(
          'condition'=>array(
            '列名'=>array('值','条件','列类型'),   
            '列名'=>array('值','条件','列类型'),
            ...
           ),
          'pg'=>'当前页',
          //'order'=>'排序处理',
          'cols'=>'列1,列2,列3...',
          'pageSize'=>'查询限制:15', 
          //'pageStrategy'=>'分页策略，默认为getNormalPage',
          'trueTableName'=>'表全名',
          'extWhere'=>'拓展条件查询',
          'lat_name'=>'cp_lat',    //对应字段 
          'lng_name'=>'cp_lng',
          'lat'=>0,   //经纬值
          'lng'=>0,   
          'keepLength'=>'最终保留长度'
        );
     *     返回：
     *     日期：
     *   修改人：
     * 修改时间：
     *     备注：
     *  1.支持的列类型有 int,smallint,tinyint,mediumint,float,double,BOOL
     *  2.条件包括：= != LIKE IN BETWEEN % %%
	 */
  public function getPager(&$data)
  {
    //基本数据处理
    $this->pageSize=isset($data['pageSize'])?(int)$data['pageSize']:$this->pageSize;
    $pg=$this->PG=isset($data['pg'])?(int)$data['pg']:1;
    
    $where=$this->getWhere($data['condition']);
    $extWhere=''; 
    if(isset($data['extWhere']) && $data['extWhere'])
    {
        $extWhere=' '.$data['extWhere'];
    }
    
    $this->trueTableName=$data['trueTableName'];
    $this->handle=M($this->trueTableName);
    
    $this->lat_name=$data['lat_name'];
    $this->lng_name=$data['lng_name'];
    
    $this->lng=$data['lng'];
    $this->lat=$data['lat'];
    
    $this->sortStrategy=isset($data['sortStrategy']) && $data['sortStrategy']?$data['sortStrategy'].'Strategy':'coordinateStrategy';
    $this->keepLength=isset($data['keepLength'])?(int)$data['keepLength']:8;
    $cols=isset($data['cols'])?$data['cols']:'*';
    ($where)&&($where=' WHERE '.$where);
    $this->sql='SELECT '.$cols.' FROM '.$this->trueTableName.$extWhere.$where;
    $this->countSql='SELECT COUNT(0) AS total FROM '.$this->trueTableName.$extWhere.$where;
    
    $this->getData();
  
    usort($this->sortData,array($this,'sortByDistance'));
   
    if($this->sortData)
    {
      $this->sortData=array_slice($this->sortData,($this->PG-1)*$this->keepLength,$this->keepLength);  
    
    } 
    
    return array(
      'data'=>$this->sortData,
      'paging'=>$this->getPaging($pg,$this->totalCount)
    );
  }
  /**
   * 获取总记录数
   */
  public function getTotalCount()
  {
    return $this->totalCount;
  }
  
   //距离排序
    public function sortByDistance($a,$b)
    {
        if($a['distance']==$b['distance'])
        {
            return 0;
        }
        return $a['distance']>$b['distance']?1:-1;
    }

  /**
   * 获取查询结果 
   */
  protected function getData()
  { 
    $rs=$this->handle->query($this->sql.$this->getLimit());
    $len=count($rs);
    $this->totalCount+=$len;
    
    if($len==$this->pageSize)
    {
        //$this->coordinateStrategy($rs);
        ++$this->startPage;
        $this->getData();
    }
   
    $this->coordinateStrategy($rs);
    //usort($this->sortData,array($this,'sortByDistance'));

    //$this->sortData=array_slice($this->sortData,0,$this->keepLength);
  }
  //坐标 排序
  private function coordinateStrategy($rs) 
  {
    if(!$rs)
    {
        return;    
    }
    $oDistance=D('Location.Distance');
    
    foreach($rs as &$v)
    {
        $v['distance']=$oDistance->getDistance($v[$this->lng_name],$v[$this->lat_name],$this->lng,$this->lat);
    }
    $this->sortData=array_merge($this->sortData,$rs);
  }
    
  /**
   * 获取limit
   */
  protected function getLimit()
  {
    $s=($this->startPage-1)*$this->pageSize;
    
    return ' LIMIT '.$s.','.$this->pageSize;
  }
    /**
    *     功能：获取where条件
    *       by：BEE
    *     参数：
    *           $data=array(
                  '字段名1'=>array(值,'='),
                  '字段名2'=>array(array(1,4),'between'),
                  '字段名3'=>array(array('1','2','3'),'in'))
                )
    *           
    *     返回：
    *     日期：
    *   修改人：
    * 修改时间：
    *     备注：$type 1=》post 2=>get
    */
  public function getWhere(&$data)
  {
    if(!is_array($data)) return false;
    $rsArr=array();
    foreach($data as $k=>$v)
    {
      if($v[0]==='') continue;
      switch($v[1])
      {
      case '<':
      case '<=':;
      case '>':;
      case '>=':;
      case '=':;
      case '!=':
        $rsArr[]=$k.$v[1].$this->_addslashes($v[0],$v[2]);
        break;
      case 'like':
      case '%':
        $rsArr[]=$k.' LIKE \''.addslashes($v[0]).'%\'';
        break;
      case '%%':
        $rsArr[]=$k.' LIKE \'%'.addslashes($v[0]).'%\'';
        break;
      case 'in':
        //$rsArr[]=$k.' IN ('.implode(',',$v[0]).')';
        $rsArr[]=$k.' IN ('.$this->_addslashes($v[0],$v[2]).')';
        break;
      case 'find':
        $rsArr[]='find_in_set('.$k.',\''.addslashes($v[0]).'\')';
        break;
      case '!find':
        $rsArr[]='!find_in_set('.$k.',\''.addslashes($v[0]).'\')';
        break;
      case 'between':
      case '-':      
        $rsArr[]=$k.' BETWEEN '.$this->_addslashes($v[0][0],$v[2]).' AND '.$this->_addslashes($v[0][1],$v[2]);
        break;
      default:break;
      }
    }
    
    return implode(' AND ',$rsArr);
  } 
  /**
   * 类型安全矫正
   * $val 值
    $type 字段对应数据库类型
    return 处理后的数据
   */
  function _addslashes($val,$type)
  {
    $type=strtolower($type);
    switch($type)
    {
    case 'int':
    case 'smallint':
    case 'tinyint':
    case 'mediumint':
      return (int)$val;
    case 'float':
      return (float)$val;
    case 'double':
        return (double)$val;
    case 'bool':
      return (bool)$val;
    case 'ignore':
        return $val;
    default:
      return '\''.addslashes($val).'\'';
    }
  }
  //recommend 策略
 public function getPaging($pg,$totalCount)
 {
    $pageCount=ceil($totalCount/$this->keepLength);
    return array('page'=>$pg,'totalCount'=>$totalCount,'numberOfPage'=>$pageCount);
 }           
}

?>