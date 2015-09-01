<?php
class DataModel
{
    //获取整形数据
    public static function getInt($name,$def=false)
    {
      return isset($_GET[$name])?(int)$_GET[$name]:$def;
    }
    //返回json
    function toJson($data)
    {
        $m=D('Return');
        $m::mybase_url($data);
        return urldecode(json_encode($data));    
    }
}
?>