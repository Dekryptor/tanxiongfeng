<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/6 0006
 * Time: 9:04
 */
class LeftMenu
{
    protected static $k_id='',
                        $k_href='',
                        $k_html='',
                        $k_name='',
                        $k_icon='',
                        $k_pid='';
    /*
     * ���
     * $data=array(
     *  array($k_id=>'id',$k_pid=>'pid',$k_href=>self::$k_href,$k_html=>'html����','ext'=array('��չ����',,,)),
     * );
     *
     * $k_id=>��Ӧ ������id����
     * $k_pid=>��Ӧ ��Ӧ����pid����
     * $k_href=>��Ӧ ������href����
     * $k_html=>��Ӧ ��ʾ����name����
     * */
    static function getMenu($data,$k_id,$k_pid,$k_href,$k_html,$k_icon='k_icon')
    {
        Sys::S('Tree.Tree');
        $data=Tree::getTreeData($data,$k_id,$k_pid);
        self::$k_id=$k_id;
        self::$k_pid=$k_pid;
        self::$k_href=$k_href;
        self::$k_html=$k_html;
        self::$k_icon=$k_icon;

        return self::decorateData($data);
    }
    /*����װ��*/
    static function decorateData($data)
    {
        $htmlArr=array();

        $tmpData=current($data);
        (isset($tmpData['ext']))||($tmpData['ext']=array());
       
        $htmlArr[]='<ul class="nav nav-pills nav-stacked nav-bracket"><li class="active"><a '.self::parseExtAttr($tmpData['ext']).' href="'.$tmpData[self::$k_href].'"><i class="'.$tmpData[self::$k_icon].'"></i> '.$tmpData[self::$k_html].'</a></li>';

        foreach($data as $k=>$v)
        {
            (!isset($v['ext']))&&($v['ext']=array());
            (!isset($v[self::$k_icon]))&&($v[self::$k_icon]='');
            if($v[self::$k_pid]==0) {
                continue;
            }
            if($v['isParent'])
            {
                $htmlArr[]='<li class="nav-parent"><a '.self::parseExtAttr($v['ext']).' href="'.$v[self::$k_href].'"><i class="'.$v[self::$k_icon].'"></i> '.$v[self::$k_html].'</a><ul class="children">';
            }
            else
            {
                $htmlArr[]='<li><a '.self::parseExtAttr($v['ext']).' href="'.$v[self::$k_href].'"><i class="'.$v[self::$k_icon].'"></i> '.$v[self::$k_html].'</a></li>';
                if($v['isLast'])
                {
                    $htmlArr[]=self::getSuffix($data,$k);
                }
            }
        }
        $htmlArr[]='</ul>';
        return implode('',$htmlArr);
    }
    /*��ȡ��׺*/
    static function getSuffix(&$data,$k)
    {
        $tagArr=array();
        $pid=$data[$k][self::$k_pid];

        while($pid>1)
        {
            $tagArr[]='</ul></li>';
            $pid=$data[$pid][self::$k_pid];
        }

        return implode('',$tagArr);
    }
    /*�������Խ���*/
    static function parseExtAttr($data)
    {
        if(!is_array($data))
        {
            return $data;
        }else
        {
            $attrArr=array();

            foreach($data as $k=>$v)
            {
                $attrArr[]=$k.'="'.$v.'"';
            }

            return implode(' ',$attrArr);
        }
    }
}