<?php
/**
 *     功能：实现分页功能
 *   创建人：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
*/
class PageModel
{
  protected $PG=1,      //当前页
            $trueTableName='',
            $pageCount=0, //总页面数
            $pageSize=15, //获取记录数
            $extWhere='', //拓展查询
            $handle=null;   //操作柄 
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
          'order'=>'排序处理',
          'cols'=>'列1,列2,列3...',
          'pageSize'=>'查询限制:15', 
          'pageStrategy'=>'分页策略，默认为getNormalPage',
          'trueTableName'=>'表全名',
          'extWhere'=>'拓展条件查询'
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
  
    $this->PG=getPage();
    
    $where=$this->getWhere($data['condition']); 
    if(isset($data['extWhere']) && $data['extWhere'])
    {
        $this->extWhere=' '.$data['extWhere'];
    }
    
    $this->trueTableName=$data['trueTableName'];
    $this->handle=M($this->trueTableName);
    
    $cols=isset($data['cols'])?$data['cols']:'*';
    
    $total=$this->getTotal($where);
    $this->pageCount=ceil($total/$this->pageSize);
    $s=($this->PG-1)*$this->pageSize;
    
    $order=isset($data['order'])?$this->getOrder($data['order']):'';
    
    $limit=$this->getLimit($s);
    $rs=$this->getData($cols,$where,$order,$limit);
    $pageHTML=$this->getNormalPage($this->PG,$this->pageCount);
    
    //策略处理
    
    if(isset($data['pageStrategy']) && !empty($data['pageStrategy']))
    {
        if(is_array($data['pageStrategy']))
        {
            $pageHTML=call_user_func($data['pageStrategy'],$this->PG,$this->pageCount,$total);   
        }
        else
        {
            $pageHTML=call_user_func(array($this,$data['pageStrategy'].'Strategy'),$this->PG,$this->pageCount,$total);
        } 
    }
    else
    {
        $pageHTML=$this->getNormalPage($this->PG,$this->pageCount);
    }
    
    return array(
      'data'=>$rs,
      'paging'=>$pageHTML
    );
  }
  /**
   * order完整性检测
   */
  /**
 * protected function checkOrder($order)
 *   {
 *     if($order=='') return false;
 *     $avail=array('asc','desc');
 *     $arr=explode(' ',$order);
 *     return isset($arr[1])&&in_array(strtolower($arr[1]),$avail);
 *   }
 */
  /**
   * 获取总记录数
   * 支持自定义
   */ 
  protected function getTotal($where)
  {
    ($where!=='')&&($where=' WHERE '.$where);
    $rs=$this->handle->query('SELECT COUNT(0) AS total FROM '.$this->trueTableName.$this->extWhere.$where);
    return $rs[0]['total'];
  }
  /**
   * 获取查询结果 
   */
  protected function getData($cols,$where,$order,$limit)
  {  
    ($where)&&($where=' WHERE '.$where);
    $where.=$order.$limit;
   
    return $this->handle->query('SELECT '.$cols.' FROM '.$this->trueTableName.$this->extWhere.$where);
  }
  /**
   * 获取order
   */
  protected function getOrder($order)
  {
    /**
 * if($order && $this->checkOrder($order))
 *     {
 *       return ' ORDER BY '.$order;  
 *     }
 *     return '';
 */
    return $order?' ORDER BY '.$order:'';
  }
  /**
   * 获取limit
   */
  protected function getLimit($s)
  {
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
          $rsArr[]=$k.' IN ('.$this->_addslashes($v[0],$v[2]).')';
          break;
        case 'find':
          $rsArr[]='FIND_IN_SET('.$k.',\''.addslashes($v[0]).'\')';
          break;
        case '!find':
          $rsArr[]='!FIND_IN_SET('.$k.',\''.addslashes($v[0]).'\')';
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
  //获取当前页
  public function getCurPage()
  {
    $curPage=isset($_GET['PG'])?(int)$_GET['PG']:1;
    ($curPage<1)&&($curPage=1);
    return $curPage;
  }
  /**
   * 1.过滤指定的参数
   * 1.过滤值为空的数据 
   */
  public function urlFilter($paramArr,$filter=array())
  {
    $filterArr=$filter?$filter:array('pg','data','m','a');
    $rsArr=array();
    foreach($paramArr as $k=>$v)
    {
      if(!in_array(strtolower($k),$filterArr) && $v)
      {
        $rsArr[]=$k.'='.$v; 
      }
    }
    return implode('&',$rsArr);  
  }
  /**
   * 分页信息
   */
  protected function getNormalPage($PG,$pageCount)
  {
    $s=max(1,$PG-2);
    $e=min($pageCount,$PG+2);
    //分页参数
    $param_string=$this->urlFilter($_GET);
    $param_string.=$param_string?'&PG=':'PG=';
    
    $baseURL='./'.basename($_SERVER['SCRIPT_FILENAME']).'?'.$param_string;
    
    $htmlArr=array();
    for(;$s<=$e;$s++)
    {
      if($s==$PG)
      {
        $htmlArr[]='<a class=\'pg-link pg-sel\' href=\'javascript:;\'>'.$s.'</a>';  
      }
      else
      {
        $htmlArr[]='<a class=\'pg-link\' href=\''.$baseURL.$s.'\'>'.$s.'</a>';
      }
    }
    return '<div class=\'paging\'>'.implode('',$htmlArr).'</div>';
  }
  //recommend 策略
 public function phoneStrategy($pg,$pageCount,$totalCount)
 {
    return array('page'=>$pg,'totalCount'=>$totalCount,'numberOfPage'=>$pageCount);
 }           
}

?>