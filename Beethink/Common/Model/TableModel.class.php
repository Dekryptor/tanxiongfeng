<?php
/**
 * 设置 Table 表头
 * $header=array(
 * array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'','style'=>'background:#CCC;样式拓展')
 * ...
 * );
 * 如果设置了sqlColsName索引 则认为该列支持排序操作
 $data=array('trueTableName'=>'','url'=>'','PS'=>15,'cols'=>'','where'=>'','order'=>'','where'=>'','order'=>'0/1')
 */
//
//$data=array(
//  'header'=>array(
//    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展'),
//    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展'),
//    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展'),
//    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展')
//    
//  ),
//  'pageData'=>array(
//    'condition'=>array(
//          '列名'=>array('值','条件','列类型'),   
//          '列名'=>array('值','条件','列类型'),
//         ),
//    'order'=>'默认排序',
//    'cols'=>'需要的 列1,列2,列3...',
//    'pageSize'=>'每页显示记录数:15', 
//    'trueTableName'=>'表全名'
//  ),
//  'callback'=>array('IndexAction','setRow')
//);  
class TableModel extends Action
{
  protected $rowsBgColor=array('bgColor1','bgColor0');
  private   $pageModel=null,
            $order='',
            $sortOrder='',  //排序方式
            $rowNum=0,      //函数
            $colNum=0,      //列数
            $URL='';
  
  /**
   * 入口
   */
  public function getTable(&$data)
  {
    $this->pageModel=D('Page');
    $this->rowNum=isset($data['pageData']['pageSize'])?$data['pageData']['pageSize']:15;
    //url获取
    $this->URL=$this->pageModel->urlFilter($_GET,array('data','m','a','order'));
    //$this->URL.=$this->URL?'&PG=':'PG=';
    $this->PG=$this->pageModel->getCurPage();
    
    $this->URL='./'.basename($_SERVER['SCRIPT_FILENAME']).'?'.($this->URL?$this->URL.'&':$this->URL);
    //$this->order=isset($_GET['order'])?strtolower(trim($_GET['order'])):'';
    $this->getOrder();
    $rs=$this->pageModel->getPager($data['pageData']);
    
    return '<table id="table" class="tbl">'.$this->getHeader($data['header']).$this->getBody($rs['rs'],$data['callback']).'</table>'.$rs['page'];
  }
  /**
   * 获取当前order
   */
  private function getOrder()
  {
    if(isset($_GET['order']))
    { 
      //格式检测
      $order=$_GET['order'];
      if(preg_match('/^\S+\|(asc|desc)$/i',$order,$match))
      {
       $order=explode('|',strtolower($order));
       $this->order=$order[0];
       $this->sortOrder=$order[1]; 
       return;
      }
    }
    $this->order='';
    $this->sortOrder='';
  }
  /**
   * 获取当前页面order的相对情形
   */
  private function getRelOrder($order)
  {
    $rel=array('asc'=>'desc','desc'=>'asc');
    $pageOrder=explode('|',$order);
    return isset($pageOrder[1])&&in_array($pageOrder[1],$rel)?$pageOrder[0].'|'.$rel[$pageOrder[1]]:'';
  }
  
  /**
   * 获取table header
   */
  protected function getHeader(&$header)
  { 
    $orderRefer=array('asc'=>'desc','desc'=>'asc');
    $imgRefer=array('asc'=>'up','desc'=>'down');
    $this->colNum=count($header);
   
    //array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比'),
    $href='';
    $headArr=array();
    $headerArr[]='<thead class="thead">';
    //当前页面的排序方式
  
    foreach($header as $v)
    {
      $headerArr[]=$this->getHeaderCell($v);
    }
    $headerArr[]='</thead>';
    return implode('',$headerArr);
  }
  /**
   * 获取thead单元格
   */
  private function getHeaderCell(&$data)
  {
    $imgRefer=array('asc'=>'up','desc'=>'down');
    $orderRefer=array('asc'=>'desc','desc'=>'asc');
    $href='javascript:;';
    $tmpData=array();
    if(isset($data['sqlColName']))
    {
      $tmpData=explode('|',$data['sqlColName']);
      
      if($this->order==$tmpData[0])
      {
        $class='order '.$imgRefer[$this->sortOrder];
        $href=$this->URL.'order='.$this->order.'|'.$orderRefer[$this->sortOrder];
      }
      else
      {
        $class='order';
        $href=$this->URL.'order='.$data['sqlColName'];
      }
      
      $name='<a class="'.$class.'" href="'.$href.'">'.$data['name'].'</a>'; 
    }
    else
    {
      $name=$data['name'];
    }
    $ext=isset($data['ext'])?' '.$data['ext']:'';
    return '<th'.$ext.' width="'.$data['w'].'">'.$name.'</th>';
  }
  /**
   * 行补全
   */
  private function fillPageRow($i)
  {
    $rowArr=array();
    for($i;$i<=$this->rowNum;$i++)
    {
      $class=$this->rowsBgColor[$i%2];
      $rowArr[]='<tr class="'.$class.'"><td colspan="'.$this->colNum.'">&nbsp;</td></tr>';
    }
  
    return implode('',$rowArr);
  }
  /**
   * 获取tbody
   */
  private function getBody(&$data,$callback)
  {
    if($data===false)
    {
      $rows='';
    }
    else
    {
      $rows=call_user_func($callback,$data);  
    }
    
    if($rows)
    {
      $i=1;
      $bodyArr=array();
      foreach($rows as $v)
      {
        $class=$this->rowsBgColor[$i%2];
        $bodyArr[]='<tr class="'.$class.'">'.$v.'</tr>';
        ++$i;
      }
      $empty='';
      
      if($i<=$this->rowNum)
      {
        $empty=$this->fillPageRow($i);
      }
      return '<tbody>'.implode('',$bodyArr).$empty.'</tbody>';  
    }
    else
    {
      return '<tbody><tr><td class="empty" colspan="'.$this->colNum.'">暂无内容</td></tr></tbody>';
    }   
  }
  /**
   * setRow
   * 
   * 需要在宿主类中调用其setRow方法
   */
  public function setRow(&$data)
  {
     
  }
}
?>