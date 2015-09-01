<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/11 0011
 * Time: 20:29
 */
class GlobalRuleModel
{
    protected static $trueTableName='tbl_global_rule';

    /*根据action和model获取id*/
    public static function getIdByActionAndModule($action,$module)
    {
        $action=strtolower($action);
        $module=strtolower($module);

        $data=Sys::M(self::$trueTableName)->select('`id`','`action`=\''.$action.'\' AND `module`=\''.$module.'\'',1);

        return $data?$data['id']:0;
    }
}