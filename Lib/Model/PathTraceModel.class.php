<?php
/**
 * 菜单路径跟踪
 */
class PathTraceModel
{
    protected static $trueTableName='tbl_menu';
    /**
     * 路径跟踪
     */
    static function getTraceByUrl($m,$a)
    {
        $m=strtolower($m);
        $a=strtolower($a);

        $pathData=array();
        /*current url information*/
        $rs=Sys::M(self::$trueTableName)->select('`id`','`module`=\''.$m.'\' AND `action`=\''.$a.'\'',1);
        /*follow the path*/
        if($rs)
        {
            return self::getTraceById($rs['id']);
        }
        else
        {
            return self::getDecorate(array(),0);
        }

    }
    static function getTraceById($id)
    {
        $pathData=array();
        $pathData[]=$data=self::getBaseInfoById($id);
        
        while($data['pid']!=0)
        {
            $data=self::getBaseInfoById($data['pid']);
            array_unshift($pathData,$data);
        }
        
        return self::getDecorate($pathData,$id);
    }
    static function getBaseInfoById($id)
    {
        return Sys::M(self::$trueTableName)->select('id,pid,title,url','id='.$id,1);
    }
   
    /**
     * 数据加工成html代码
     */
    static function getDecorate($data,$id)
    {
        $htmlArr=array();
      
        $htmlArr[]='<div class="breadcrumb-wrapper">
        <span class="label">当前位置:</span>
        <ol class="breadcrumb">'; 
        foreach($data as $v)
        {
            if($v['id']!=$id)
            {
               $htmlArr[]='<li><a href="'.$v['url'].'">'.$v['title'].'</a></li>'; 
            }
            else
            {
                $htmlArr[]='<li class="active">'.$v['title'].'</li>';
            }
        }
        $htmlArr[]='</ol></div>';
        return implode('',$htmlArr);
    }
}