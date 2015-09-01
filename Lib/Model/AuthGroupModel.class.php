<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2015/7/9 0009
 * Time: 9:25
 */
class AuthGroupModel
{
    protected static $trueTableName='tbl_auth_group';

    public static function getRuleByGroupId($group_id)
    {
        $group_info=Sys::M(self::$trueTableName)->select('`rules`,`g_rules`','`group_id`='.$group_id,1);

        return $group_info?$group_info:array('rules'=>'','g_rules'=>'');
    }
    /*获取组的全局规则*/
    public static function getGlobalRule($group_id)
    {
        $ruleData=Sys::M(self::$trueTableName)->select('g_rules','id='.$group_id,1);

        return $ruleData?$ruleData['g_rules']:'';
    }
}
