<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/7/9 0009
 * Time: 18:09
 */
class GroupModel
{
    protected static $trueTableName='tbl_group';

    /*
     * 获取当前组的规则,并获取父级规则
     * */
    public static function getInheritRule($group_id)
    {
        $rule_arr=array(
            'rules'=>array(),
            'g_rules'=>array()
        );
        $group_info=array();
        $tmpRule=array();

        $group_info=self::getGroupInfo($group_id);

        $oAuthGroup=Sys::D('AuthGroup');

        do
        {
            $tmpRule=$oAuthGroup::getRuleByGroupId($group_id);

            $rule_arr['rules'][]=$tmpRule['rules'];
            $rule_arr['g_rules'][]=$tmpRule['g_rules'];

            $group_info=self::getGroupInfo($group_info['pid']);

        }while($group_info && $group_id=$group_info['id'] && $group_info['pid']!=0 && $group_info['inherit']);

        //去除重复项
        $rule_arr['rules']=array_unique(explode(',',implode(',',$rule_arr['rules'])));
        $rule_arr['g_rules']=array_unique(explode(',',implode(',',$rule_arr['g_rules'])));

        return $rule_arr;
    }
    /*
     * 获取组信息
     * */
    public static function getGroupInfo($group_id)
    {
        $group_info=Sys::M(self::$trueTableName)->select('id,type,name,description,pid,inherit','id='.$group_id,1);

        return $group_info?$group_info:array();
    }
    /*
     * 删除部门
     *
     * 同时删除其子级部门
     * */
    public static function delGroup($group_id)
    {
        $group_id_arr=array();
        $childs='';

        while($childs)
        {
            $childs=self::getChild($group_id);
            
        }

        return Sys::M(self::$trueTableName)->delete('id='.$group_id);
    }
    /*
     * 获取子级
     * */
    public static function getChild($group_id)
    {
        $group_info=Sys::M(self::$trueTableName)->select('GROUP_CONCAT(`id`) AS ids','pid='.$group_id);

        return $group_info[0]['ids'];
    }
    /*
     * 判断
     * */
    public static function ifHasChild($group_id)
    {
        $rs=Sys::M(self::$trueTableName)->select('id','pid='.$group_id,1);
        return $rs?1:0;
    }
}