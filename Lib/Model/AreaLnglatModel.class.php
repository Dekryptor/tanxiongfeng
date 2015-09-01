<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1 0001
 * Time: 17:14
 */
class AreaLnglatModel
{
    protected static $trueTableName = 'tbl_area_lnglat';

    /*根据区域名称获取位置信息*/
    static function getLngLatByName($name)
    {
        $data = Sys::M(self::$trueTableName)->select('`lat`,`lng`', '`name` LIKE \'' . $name . '%\'', 1);

        return $data ? $data : array('lat' => 0, 'lng' => 0);
    }

    /*根据code获取位置信息*/
    static function getLngLatByCode($code)
    {
        $data = Sys::M(self::$trueTableName)->select('`lat`,`lng`', '`code`=\'' . $code . '\'', 1);

        return $data ? $data : array('lat' => 0, 'lng' => 0);
    }

    /*获取城市列表 */
    static function getCityList()
    {
        $data = Sys::M(self::$trueTableName)->select('`id`,`code`,`name`,`lat`,`lng`', 'status=1');

        return $data;
    }

    /*获取父节点下的子节点 */
    static function getChildList($code)
    {
        $data = Sys::M(self::$trueTableName)->select('`id`,`code`,`name`,`lat`,`lng`', 'status=1 AND p_code=\'' . $code . '\'');

        return $data;
    }

    /*获取区域信息*/
    static function getInfoByCode($code)
    {
        $data = Sys::M(self::$trueTableName)->select('`id`,`code`,`name`,`lat`,`lng`', 'code=\'' . $code . '\'', 1);

        return $data?$data:array('id'=>0,'code'=>'','name'=>'未知','lat'=>0,'lng'=>0);
    }

    /*地址拼接*/
    static function decorateAddress($cityCode,$areaCode,$ext='')
    {
        $cityData=self::getInfoByCode($cityCode);
        $areaData=self::getInfoByCode($areaCode);

        return $cityData['name'].$areaData['name'].$ext;
    }
}