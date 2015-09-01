<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/17 0017
 * Time: 21:52
 */
class ImageModel
{
    protected static $trueTableName='tbl_image';

    /*获取图片信息*/
    static function getImageInfo($id)
    {
        $data=Sys::M(self::$trueTableName)->select('`id`,`url`,`w`,`h`','`id`='.$id,1);

        if(empty($data))
        {
            $data=array(
                'id'=>0,
                'url'=>'',
                'w'=>0,
                'h'=>0
            );
        }

        return $data;
    }
    /*保存图片信息*/
    static function save($url,$w,$h,$hash)
    {
        $data=array(
            'url'=>array($url,'string'),
            'w'=>array($w,'float'),
            'h'=>array($h,'float'),
            'hash'=>$hash
        );

        return Sys::M(self::$trueTableName)->save($data);
    }
    /*判断图片是否存在*/
    static function ifExists($fileName)
    {
        $hash=md5_file($fileName);

        $data=Sys::M(self::$trueTableName)->select('`id`','`hash`=\''.$hash.'\'',1);

        return $data?$data['id']:0;
    }
}