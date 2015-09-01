<?php
/**
 * 图片处理驱动类，可配置图片处理库
 * 目前支持GD库和imagick
 */
class Image{
    /* 驱动相关常量定义 */
    const IMAGE_GD              =   1; //常量，标识GD库类型
    const IMAGE_IMAGICK         =   2; //常量，标识imagick库类型

    /* 缩略图相关常量定义 */
    const IMAGE_THUMB_SCALE     =   1 ; //常量，标识缩略图等比例缩放类型
    const IMAGE_THUMB_FILLED    =   2 ; //常量，标识缩略图缩放后填充类型
    const IMAGE_THUMB_CENTER    =   3 ; //常量，标识缩略图居中裁剪类型
    const IMAGE_THUMB_NORTHWEST =   4 ; //常量，标识缩略图左上角裁剪类型
    const IMAGE_THUMB_SOUTHEAST =   5 ; //常量，标识缩略图右下角裁剪类型
    const IMAGE_THUMB_FIXED     =   6 ; //常量，标识缩略图固定尺寸缩放类型

    /* 水印相关常量定义 */
    const IMAGE_WATER_NORTHWEST =   1 ; //常量，标识左上角水印
    const IMAGE_WATER_NORTH     =   2 ; //常量，标识上居中水印
    const IMAGE_WATER_NORTHEAST =   3 ; //常量，标识右上角水印
    const IMAGE_WATER_WEST      =   4 ; //常量，标识左居中水印
    const IMAGE_WATER_CENTER    =   5 ; //常量，标识居中水印
    const IMAGE_WATER_EAST      =   6 ; //常量，标识右居中水印
    const IMAGE_WATER_SOUTHWEST =   7 ; //常量，标识左下角水印
    const IMAGE_WATER_SOUTH     =   8 ; //常量，标识下居中水印
    const IMAGE_WATER_SOUTHEAST =   9 ; //常量，标识右下角水印

    /**
     * 图片资源
     * @var resource
     */
    protected static $img;

    /*
     * 初始化信息
     * */
    public static function init($type=self::IMAGE_GD,$imgname = null)
    {
        /* 判断调用库的类型 */
        switch ($type) {
            case self::IMAGE_GD:
                $class = 'Gd';
                break;
            case self::IMAGE_IMAGICK:
                $class = 'Imagick';
                break;
            default:;

        }

        /* 引入处理库，实例化图片处理对象 */
        require_once THINK_PATH.'Common/Class/Upload/Driver/'.$class.'.class.php';
        self::$img = new $class($imgname);
    }
    /**
     * 打开一幅图像
     * @param  string $imgname 图片路径
     * @return Object          当前图片处理库对象
     */
    public static function open($imgname){
        self::$img->open($imgname);
    }

    /**
     * 保存图片
     * @param  string  $imgname   图片保存名称
     * @param  string  $type      图片类型
     * @param  integer $quality   图像质量
     * @param  boolean $interlace 是否对JPEG类型图片设置隔行扫描
     * @return Object             当前图片处理库对象
     */
    public static function save($imgname, $type = null, $quality=80,$interlace = true){
        self::$img->save($imgname, $type, $quality,$interlace);
    }

    /**
     * 返回图片宽度
     * @return integer 图片宽度
     */
    public static function width(){
        return self::$img->width();
    }

    /**
     * 返回图片高度
     * @return integer 图片高度
     */
    public static function height(){
        return self::$img->height();
    }

    /**
     * 返回图像类型
     * @return string 图片类型
     */
    public static function type(){
        return self::$img->type();
    }

    /**
     * 返回图像MIME类型
     * @return string 图像MIME类型
     */
    public static function mime(){
        return self::$img->mime();
    }

    /**
     * 返回图像尺寸数组 0 - 图片宽度，1 - 图片高度
     * @return array 图片尺寸
     */
    public static function size(){
        return self::$img->size();
    }

    /**
     * 裁剪图片
     * @param  integer $w      裁剪区域宽度
     * @param  integer $h      裁剪区域高度
     * @param  integer $x      裁剪区域x坐标
     * @param  integer $y      裁剪区域y坐标
     * @param  integer $width  图片保存宽度
     * @param  integer $height 图片保存高度
     * @return Object          当前图片处理库对象
     */
    public static function crop($w, $h, $x = 0, $y = 0, $width = null, $height = null){
        self::$img->crop($w, $h, $x, $y, $width, $height);
    }

    /**
     * 生成缩略图
     * @param  integer $width  缩略图最大宽度
     * @param  integer $height 缩略图最大高度
     * @param  integer $type   缩略图裁剪类型
     * @return Object          当前图片处理库对象
     */
    public static function thumb($width, $height, $type = self::IMAGE_THUMB_SCALE){
        self::$img->thumb($width, $height, $type);
    }

    /**
     * 添加水印
     * @param  string  $source 水印图片路径
     * @param  integer $locate 水印位置
     * @param  integer $alpha  水印透明度
     * @return Object          当前图片处理库对象
     */
    public static function water($source, $locate = self::IMAGE_WATER_SOUTHEAST,$alpha=80){
        self::$img->water($source, $locate,$alpha);
    }

    /**
     * 图像添加文字
     * @param  string  $text   添加的文字
     * @param  string  $font   字体路径
     * @param  integer $size   字号
     * @param  string  $color  文字颜色
     * @param  integer $locate 文字写入位置
     * @param  integer $offset 文字相对当前位置的偏移量
     * @param  integer $angle  文字倾斜角度
     * @return Object          当前图片处理库对象
     */
    public static function text($text, $font, $size, $color = '#00000000',
                         $locate = self::IMAGE_WATER_SOUTHEAST, $offset = 0, $angle = 0){
        self::$img->text($text, realpath($font), $size, $color, $locate, $offset, $angle);
    }
    /*图片旋转*/
    public static function rotate($angle,$color='#00000000',$transparent=100)
    {
        self::$img->rotate($angle,$color,$transparent);
    }
}