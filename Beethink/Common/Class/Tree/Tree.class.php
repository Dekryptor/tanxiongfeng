<?php
/**
 * 无限分级结构解析
 * 
 * $data=array(
 *  'id'=>'id',
 *  'pid'=>'父级id'
 *  '...'=>'其它'
 * );
 */

class Tree
{
    protected static $groupData=array(),   //分组数据
              $order=array(),       //顺序记录
              $k_id='id',           
              $k_pid='pid',
              $referData=array();   //参照数据
    /*入口,获取树状数据*/
    static function getTreeData(&$data,$k_id,$k_pid)
    {
        self::$k_id=$k_id;
        self::$k_pid=$k_pid;
        $rs=array();
        
        self::$groupData=self::getPidGroup($data);
        self::$referData=self::getReferData($data);
        
        self::getLevelData(0);
        unset(self::$order[0]);
        
        foreach(self::$order as $v)
        {
            $rs[$v]=self::$referData[$v];
        }
        return $rs;
    }
    
    static function getReferData($data)
    {
        $retData=array();
        foreach($data as $v)
        {
            $retData[$v[self::$k_id]]=$v;
        }
        return $retData;
    }
    /**
     * 递归获取层次关系
     * 
     * 
     * id=当前节点  pid=父级节点
     * 
     * 如果id=0 表示根节点,有且只有一个
     */
    static function getLevelData($id)
    {
         self::$referData[$id]['isLast']=$id==0?1:self::isLast($id,self::$referData[$id][self::$k_pid]);
         self::$referData[$id]['isParent']=1;

         self::$order[]=$id;
         
        foreach(self::$groupData[$id] as $k=>$v)
        {
            //判断是否为父级节点
            if(self::hasChild($k))
            {
                self::getLevelData($k);
            }                      
            else
            {
                self::$order[]=$k;
                self::$referData[$k]['isLast']=self::isLast($k,$id); //判断当前是否为尾节点
                self::$referData[$k]['isParent']=0;   //不是父级节点
            }                                                         
        }
        return ;
    }
    /**
     * 尾节点判断
     */
    static function isLast($id,$pid)
    {
        if(!isset(self::$groupData[$pid]))
        {
            return true;
        }
        $tmp=end(self::$groupData[$pid]);
        return $tmp[self::$k_id]==$id?1:0; 
    }
    /**
     * 判断是否含有子节点
     */
    static function hasChild($id)
    {
        return isset(self::$groupData[$id]);
    }
    /**
     * 根据id获取分组
     * 
     */
    static function getPidGroup(&$data)
    {
        $pid=0;
        $retArr=array();
        
        foreach($data AS $v)
        {
            $pid=$v[self::$k_pid];
            if(!isset($retArr[$pid]))
            {
                $retArr[$pid]=array();
            }
            $retArr[$pid][$v[self::$k_id]]=$v;
        }
        return $retArr;
    } 
}
/*
$treeData=array(
    array('id'=>1,'pid'=>0,'name'=>'bee2','age'=>'12'),
    array('id'=>2,'pid'=>1,'name'=>'bee3','age'=>'13'),
    array('id'=>3,'pid'=>2,'name'=>'bee4','age'=>'14'),
    array('id'=>4,'pid'=>1,'name'=>'bee5','age'=>'15'),
    array('id'=>5,'pid'=>2,'name'=>'bee6','age'=>'16'),
    array('id'=>6,'pid'=>4,'name'=>'bee7','age'=>'17'),
    array('id'=>7,'pid'=>1,'name'=>'bee8','age'=>'18')
);


$oTree=new TreeModel();
$rs=$oTree->getTreeData($treeData);

*/
?>