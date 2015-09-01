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
          'trueTableName'=>'表全名',
          'extWhere'=>'拓展条件查询'
  ),
  'tableId'=>'table1',           //表id名
  'callback'=>array('IndexAction','setRow')
);

 */ 
class Table
{
    protected static $order=array(),
                $tableId='',
                $pg='',
                $urlTpl='',
                $sortOpposite=array(
                    'asc'=>'desc',
                    'desc'=>'asc'
                ),
                $sortRefer=array(
                    'asc'=>'sorting_desc',
                    'desc'=>'sorting_asc',
                    'sort'=>'sorting'
                );
    /**
     * 获取table入口
     */
    static function getTable(&$data)
    {
        self::init();

        $tableArr=array();
        self::$tableId=$data['id'];

        $pageData=Sys::D('Page')->getPager($data['pageData']);

        $tableArr[]='<div class="paging_full_numbers">';
        $tableArr[]='<table class="table dataTable no-footer" id="'.self::$tableId.'">';

        $tableArr[]=self::getHeader($pageData['paging'],$data['header']);
        $tableArr[]=self::getBody($pageData['data'],$data['callback']);
        $tableArr[]='</table>';
        $tableArr[]=self::getPager($pageData['paging']);
        $tableArr[]='</div>';

        return implode('',$tableArr);
    }
    /*初始化数据*/
    static function init()
    {
        self::loadRelyScript();
        self::getOrder();
        self::parseUrl();
    }
    /**
     * 获取当前的排序方式
     */
    static function getOrder()
    {
        $order=isset($_GET['order'])?$_GET['order']:'';

        if($order && strpos($order,'|'))
        {
            self::$order=explode('|',strtolower($order));
        }
    }
    public static function getHeader($paging,&$header)
    {
        //array('name'=>'列名','sqlColName'=>'字段名[|[asc|desc]]','w'=>'宽/百分比','ext'=>'拓展')
        $htmlArr=array();
        $extra='';
        $htmlArr[]='<thead><tr role="row">';
        
        foreach($header as $k=>$v)
        {
            (isset($v['sqlColName']))||($v['sqlColName']='');
            $extra='';
            
            if(isset($v['ext']))
            {
                $extra=self::getExtra($v['ext']);
            }
            if(strpos($v['sqlColName'],'|'))
            {
                $sepData=explode('|',$v['sqlColName']);

                if(self::$order && $sepData[0]==self::$order[0])
                {
                    $sepData[1]=self::$sortOpposite[ self::$order[1] ];
                }

                $link=self::getHref(implode('|',$sepData),$paging['page']);
                $htmlArr[]='<th class="'.self::getClass($v['sqlColName']).'" tabindex="0" aria-controls="'.self::$tableId.'" rowspan="1" style="width:'.$v['w'].'" '.$extra.'><a href="'.$link.'">'.$v['name'].'</a></th>';
            }
            else
            {
                $htmlArr[]='<th tabindex="0" aria-controls="'.self::$tableId.'" rowspan="1" style="width:'.$v['w'].'" '.$extra.'>'.$v['name'].'</th>';    
            }
        }
        $htmlArr[]='</tr></thead>';
        
        return implode('',$htmlArr);
    }
    /*获取链接地址*/
    static function getHref($sqlColName,$page)
    {
        return sprintf(self::$urlTpl,$sqlColName,$page);
    }
    /**
     * 额外属性处理
     */
    private static function getExtra($data)
    {
        $ret=array();
        foreach((array)$data as $k=>$v)
        {
            $ret[]=$k.'="'.$v.'"';
        }
        return implode(' ',$ret);
    }
    /*获取博table body 部分*/
    public static function getBody(&$data,$callback)
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
            $htmlArr[]='<tr class="'.$refer[$i++%2].' role="row">'.( is_array($v)?implode('',$v):$v ).'</tr>';
        }
        $htmlArr[]='</tbody>';
        
        return implode('',$htmlArr);
    }
    /*url 解析*/
    public static function parseUrl($url='')
    {
        (empty($url))&&($uri=$_SERVER['REQUEST_URI']);
        Sys::S('core.Url.Uri');
        Uri::setParam(array('m','a'),array('pg','order'));
        self::$urlTpl=Uri::toPageUriTpl($_SERVER['REQUEST_URI']);
    }
    /*获取分页数据*/
    public static function getPager($paging)
    {
        $htmlArr=array();
        $htmlArr[]='<div class="dataTables_paginate paging_full_numbers">';
        $link='';
        $s=$paging['page']-4;
        $e=$paging['page']+4;

        ($s<1)&&($s=1);
        ($e>$paging['numberOfPage'])&&($e=$paging['numberOfPage']);

        $order=implode('|',self::$order);

        if($s>1)
        {
            $htmlArr[]='<a class="paginate_button" href="'.sprintf(self::$urlTpl,$order,1).'">首页</a>';
        }

        for($s;$s<=$e;$s++)
        {
            if($s == $paging['page'])
            {
                $htmlArr[]='<a href="javascript:;" class="paginate_button current">'.$s.'</a>';
            }
            else
            {
                $link=sprintf(self::$urlTpl,implode('|',self::$order),$s);
                $htmlArr[]='<a href="'.$link.'" class="paginate_button ">'.$s.'</a>';
            }
        }

        if($e<$paging['numberOfPage'])
        {
            $htmlArr[]='<a class="paginate_button" href="'.sprintf(self::$urlTpl,$order,$paging['numberOfPage']).'">尾页</a>';
        }

        $htmlArr[]='</div>';

        return implode('',$htmlArr);
    }
    public static function getClass($colData)
    {
        $data=explode('|',strtolower($colData));

        if(self::$order && $data[0]==self::$order[0])
        {
            return isset(self::$sortRefer[ self::$order[1] ])?self::$sortRefer[ self::$order[1] ]:'';
        }
        else
        {
            return self::$sortRefer['sort'];
        }
    }

    /*加载依赖的脚本文件 以及 脚本*/
    static function loadRelyScript()
    {
        View::assign('styles',array('core.Bracket.jquery#datatables'));
    }
}