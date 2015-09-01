<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/31 0031
 * Time: 9:54
 */
class Uri
{
    static private $ignoreList = array('m', 'a'),  //忽略的参数列表
        $replaceList = array('pg'),                 //需要替换的参数列表
        $connect_symbol='&',                        //参数与参数隔离符号
        $equal_symbol='=';                          //赋值

    /*参数定义*/
    static function setParam($ignoreList = array(), $replaceList = array(),$connect_symbol=null,$equal_symbol=null)
    {
        (is_array($ignoreList) && $ignoreList) && (self::$ignoreList = array_merge(self::$ignoreList, $ignoreList));
        (is_array($replaceList) && $replaceList) && (self::$replaceList = array_merge(self::$replaceList, $replaceList));
        (!empty($connect_symbol))&&(self::$connect_symbol=$connect_symbol);
        (!empty($equal_symbol))&&(self::$equal_symbol=$equal_symbol);
    }

    /*解析指定的uri*/
    static function parseUri($uri)
    {
        $parseData = array();
        $parseUri = parse_url($uri);

        $tmp = array();
        $data = array();

        $parseData['query'] = array();
        $parseData['path'] = $parseUri['path'];

        if ($parseUri['query']) {
            $data = explode(self::$connect_symbol, $parseUri['query']);

            foreach ($data as $v) {
                $tmp = explode(self::$equal_symbol, $v);
                $parseData['query'][strtolower($tmp[0])] = $tmp[1];
            }
        }

        foreach(self::$replaceList as $v)
        {
            (!isset($parseData['query'][$v]))&&($parseData['query'][$v]='');
        }
        ksort($parseData['query']);
        return $parseData;
    }

    /*把uri准换成page需要的格式*/
    static function toPageUriTpl($uri = '')
    {
        (empty($uri)) && ($uri = $_SERVER['REQUEST_URI']);
        $parseData = self::parseUri($uri);
        $query_arr = array();
        $tmp = array();

        foreach ($parseData['query'] as $k => $v)
        {
            if (in_array($k, self::$ignoreList)) {
                continue;
            } else if (in_array($k, self::$replaceList)) {
                $query_arr[] = $k . self::$equal_symbol.'%s';
            } else {
                $query_arr[] = $k . self::$equal_symbol . $v;
            }
        }

        return $parseData['path'] . '?' . implode(self::$connect_symbol, $query_arr);
    }
}