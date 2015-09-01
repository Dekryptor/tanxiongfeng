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

class TreeModel
{
    protected $groupData=array(),   //分组数据
              $order=array(),       //顺序记录
              $k_id='id',           
              $k_pid='pid',
              $referData=array();   //参照数据
            
    function getTreeData(&$data,$k_id,$k_pid)
    {
        $this->k_id=$k_id;
        $this->k_pid=$k_pid;
        $rs=array();
        
        $this->groupData=$this->getPidGroup($data);
        $this->referData=$this->getReferData($data);
        
        $this->getLevelData(0);
        unset($this->order[0]);
        
        foreach($this->order as $v)
        {
            $rs[]=$this->referData[$v];
        }
        return $rs;
    }
    
    function getReferData($data)
    {
        $retData=array();
        foreach($data as $v)
        {
            $retData[$v[$this->k_id]]=$v;
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
    function getLevelData($id)
    {
         $this->referData[$id]['isLast']=$id==0?1:$this->isLast($id,$this->referData[$id][$this->k_pid]);
         $this->referData[$id]['isParent']=1;

         $this->order[]=$id;
         
        foreach($this->groupData[$id] as $k=>$v)
        {
            //判断是否为父级节点
            if($this->hasChild($k))
            {
                $this->getLevelData($k);
            }                      
            else
            {
                $this->order[]=$k;
                $this->referData[$k]['isLast']=$this->isLast($k,$id); //判断当前是否为尾节点
                $this->referData[$k]['isParent']=0;   //不是父级节点
            }                                                         
        }
        return ;
    }
    /**
     * 尾节点判断
     */
    function isLast($id,$pid)
    {
        if(!isset($this->groupData[$pid]))
        {
            return true;
        }
        $tmp=end($this->groupData[$pid]);
        return $tmp[$this->k_id]==$id?1:0; 
    }
    /**
     * 判断是否含有子节点
     */
    function hasChild($id)
    {
        return isset($this->groupData[$id]);
    }
    /**
     * 根据id获取分组
     * 
     */
    function getPidGroup(&$data)
    {
        $pid=0;
        $retArr=array();
        
        foreach($data AS $v)
        {
            $pid=$v['pid'];
            if(!isset($retArr[$pid]))
            {
                $retArr[$pid]=array();
            }
            $retArr[$pid][$v[$this->k_id]]=$v;    
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