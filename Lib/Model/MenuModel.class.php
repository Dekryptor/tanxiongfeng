<?php
/**
 * 功能:
 * 1.菜单路径跟踪
 * 2.生成左侧菜单栏
 */
class MenuModel
{
    protected static $trueTableName='tbl_menu',
              $menuHtml=array();

    /*获取数据图数据*/
    public static function getTreeData()
    {
        $data=Sys::M(self::$trueTableName)->select('`id`,`title`,`pid`,`url`,`tip`,`class`,``','hide=0 AND status=0 AND is_dev=0');
    }
    /**
     * 路径跟踪
     */
    public static function getTraceByUrl($url)
    {
        $pathData=array();
        //当前url信息
        $rs=Sys::M(self::$trueTableName)->select('id','url=\''.$url.'\'',1);
        //递归向上
        $data=$rs?self::getTraceById($rs['id']):array();
        
        return self::getTraceById($rs['id']);
    }
    public static function getTraceById($id)
    {
        $pathData=array();
        $pathData[]=$data=self::getBaseInfoById($id);
        
        while($data['pid']!=0)
        {
            $data=self::getBaseInfoById($data['pid']);
            array_unshift($pathData,$data);
        }
        
        return self::getPathDecorate($pathData,$id);
    }
    /**
     * 获取基本信息
     */
    public static function getBaseInfoById($id)
    {
        return Sys::M(self::$trueTableName)->select('id,pid,title,url','id='.$id,1);
    }
    /**
     * 获取详细信息
     */
    public static function getInfo($id)
    {
        return Sys::M(self::$trueTableName)->select('id,pid,title,url,tip','id='.$id,1);
    }
    /**
     * 数据加工成html代码
     */
    public static function getPathDecorate($data,$id)
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
    /**
     * 获取菜单信息
     */
    public static function getMenu($listIds)
    {
        $menuData=array();
        $list=explode(',',$listIds);
        
        foreach($list AS $v)
        {
            $menuData[]=self::getInfo($v);
        }
        $treeData=Sys::D('Tree.tree')->getTreeData($menuData,'id','pid');
        
        return self::getMenuDecorate($treeData);
    }
    /**
     * 获取左侧导航栏内容
     */
    public static function getMenuDecorate($data)
    {
        static $htmlArr=array(),
               $i=0;
        
        if(empty($data))
        {
            return implode('',$htmlArr);
        }       
               
        $curData=array_shift($data);
        
        if($curData['isParent'])
        {
            $htmlArr[$i]='<li class="nav-parent">
            <i class="fa">::before</i>
            <a href="javascript:;" title="'.$curData['tip'].'">'.$curData['title'].'</a>
            <ul class="children" style="display: block;">';
            ++$i;   
            self::getMenuDecorate($data);
        }
        else
        {
            $htmlArr[]='<li><a title="'.$curData['tip'].'" href="'.$curData['url'].'"><i class="fa fa-caret-right"></i> '.$curData['title'].'</a></li>';
        }
        if($curData['isParent'])
        {
            $htmlArr[$i]='</ul></li>';
        }
        return implode('',$htmlArr);
    }
    /*根据action和module获取对应id*/
    public static function getIdByActionAndModule($action,$module)
    {
        $action=strtolower($action);
        $model=strtolower($module);

        $data=Sys::M(self::$trueTableName)->select('`id`','`action`=\''.$action.'\' AND `module`=\''.$module.'\'',1);

        return $data?$data['id']:0;
    }
}