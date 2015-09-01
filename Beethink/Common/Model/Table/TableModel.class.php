<?php
/**
 * 1.支持指定列增/降序排列
 * 2.支持分页处理
 * 
 * 
 $data=array(
  'header'=>array(
    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展'),
    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展'),
    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展'),
    array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展')
    
  ),
  'pageData'=>array(
    'condition'=>array(
            '列名'=>array('值','条件','列类型'),   
            '列名'=>array('值','条件','列类型'),
            ...
           ),
          'pg'=>'当前页',
          'order'=>'排序处理',
          'cols'=>'列1,列2,列3...',
          'pageSize'=>'查询限制:15', 
          'pageStrategy'=>'phone',
          'trueTableName'=>'表全名',
          'extWhere'=>'拓展条件查询'
  ),
  'id'=>'table1',           //表id名
  'callback'=>array('IndexAction','setRow')
);

 */ 
class TableModel
{
    protected $order=array(),
                $id='',
                $sortRefer=array(
                    'asc'=>'sorting_desc',
                    'desc'=>'sorting_asc',
                    'sort'=>'sorting'
                );
    
    
    /**
     * 获取table入口
     */
    public function getTable(&$data)
    {
        $tableArr=array();
        $this->id=$data['id'];
        $tableArr[]='<table class="table dataTable no-footer" id="'.$this->id.'" role="grid" aria-describedby='.$this->id.'"_info">';
        $tableArr[]=$this->getHeader($data['header']);
        $pageData=Sys::D('Page')->getPager($data['pageData']);
        $tableArr[]=$this->getBody($pageData['data'],$data['callback']);
        $tableArr[]=$this->getPager($pageData['paging']);
        
        return implode('',$tableArr);
    }
    /**
     * 获取当前页面排序
     */
    public function getOrder()
    {
        $order=isset($_GET['order'])?$_GET['order']:'';
        
        if($order)
        {
            $this->order=explode('|',strtolower($order));
        }
    }
    public function getHeader(&$header)
    {
        //array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展')
        $htmlArr=array();
        $extra='';
        $htmlArr[]='<thead><tr role="row">';
        
        foreach($header as $k=>$v)
        {
            $extra='';
            
            if(isset($v['ext']))
            {
                $extra=$this->getExtra($v['ext']);
            }
            if(strpos($v['sqlColName'],'|'))
            {
                $htmlArr[]='<th class="'.$this->getClass($v['sqlColName']).'" tabindex="0" aria-controls="'.$this->id.'" rowspan="1" style="width:'.$v['w'].'" '.$extra.'>'.$v['name'].'</th>';
            }
            else
            {
                $htmlArr[]='<th tabindex="0" aria-controls="'.$this->id.'" rowspan="1" style="width:'.$v['w'].'" '.$extra.'>'.$v['name'].'</th>';    
            }
        }
        $htmlArr[]='</tr></thead>';
        
        return implode('',$htmlArr);
    }
    /**
     * 额外属性处理
     */
    private function getExtra($data)
    {
        $ret=array();
        foreach((array)$data as $k=>$v)
        {
            $ret[]=$k.'="'.$v.'"';
        }
        return implode(' ',$ret);
    }
    public function getBody(&$data,$callback)
    {
        //数据处理
        $data=call_user_func($callback,$data);
        $htmlArr=array();
        $htmlArr[]='<tbody>';
        $refer=array(
            0=>'odd',
            1=>'even'
        );
        $i=0;
       
        foreach($data as $v)
        {
            $htmlArr[]='<tr class="gradeA "'.$refer[$i++%2].' role="row">'.$v.'</tr>';
        }
        $htmlArr[]='</tbody>';
        
        return implode('',$htmlArr);
    }
    public function parseUrl()
    {
        
    }
    public function getPager($page=1,$pageCount=1,$pageSize=0)
    {
        return '<div>pager</div>';
    }
    public function getClass($colData)
    {
        $data=explode('|',strtolower($colData));
        if($this->order && $data[0]==$this->order[0])
        {
            return isset($this->sortRefer[$data[1]])?$this->sortRefer[$data[1]]:'';
        }
        else
        {
            return $this->sortRefer['sort'];
        }
    }
}

?>