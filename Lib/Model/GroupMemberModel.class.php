<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2015/7/9 0009
 * Time: 9:21
 *
 * 用户规则继承于群组
 */
class GroupMemberModel
{
    protected static $trueTableName='tbl_group_member';

    public static function getGroupId($user_id)
    {
        $group_info=Sys::M(self::$trueTableName)->select('group_id','`mem_id`='.$user_id.' AND `status`=1',1);

        return $group_info?$group_info['group_id']:0;
    }
}