<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/28 0028
 * Time: 17:51
 */
class AdxminOrderModel
{
    CONST SECRET='a35ojjglgvqjjor0';

    protected static $trueTableName='adxmi_order';
    /*save order*/
    static function save($data)
    {
        $saveData=array(
            'order_id'=>array($data['order'],'string'),
            'app_id'=>array($data['app'],'string'),
            'ad_name'=>array($data['ad'],'string'),
            'adid'=>array($data['adid'],'int'),
            'user_id'=>array($data['user'],'string'),
            'device_id'=>array($data['device'],'string'),
            'channel'=>array($data['chn'],'int'),
            'revenue'=>array($data['revenue'],'float'),
            'order_create_time'=>array($data['time'],'int'),
            'store_id'=>array($data['storeid'],'string'),
            'packagename'=>array($data['pkg'],'string'),
            'ad_type'=>array($data['ad_type'],'string'),
            'points'=>array($data['points'],'int'),
        );

        $strArr=array();
        foreach($saveData as $k=>$v)
        {
            $strArr[]=$k.'='.$v;
        }

        return DBUtil::save(self::$trueTableName,$saveData);
    }
    /*save order info */
    static function saveOrder($url)
    {
        $status=self::checkUrl($url,self::SECRET);

        if(!$status)
        {
            return false;
        }

        $urlData=parse_url($url);
        parse_str($urlData['query'],$queryData);

        return self::save($queryData);
    }
    /*check url*/
    static function checkUrl($url,$secret)
    {
        /*剥离出sign*/
        $sepIndex=strrpos($url,'&');
        $signStr=substr($url,$sepIndex+1);
        $urlWithoutSign=substr($url,0,$sepIndex);

        $signParam=explode('=',$signStr);
        if($signParam[0]!='sign')
        {
            return false;
        }

        (isset($signParam[1]))||($signParam[1]='');
        $curSign=self::getUrlSignature($urlWithoutSign,$secret);
        file_put_contents('./test.txt',$signParam[1].'\n'.$curSign.'\n'.(strcmp($curSign,$signParam[1])==0?'YES':'NO'));

        return strcmp($curSign,$signParam[1])==0;
    }
    /*get cur sign*/
    static function getCurrentSign($host,$queryData)
    {
        $strArr=array();

        $strArr[]='http://'.$host.'?';

        $strArr[]='order='.$queryData['order'];
        $strArr[]='app='.$queryData['app'];
        $strArr[]='ad='.$queryData['ad'];


    }
    static function getUrlSignature($url, $secret){
        $params = array();
        $url_parse = parse_url($url);
        if (isset($url_parse['query'])){
            $query_arr = explode('&', $url_parse['query']);
            if (!empty($query_arr)){
                foreach($query_arr as $p){
                    if (strpos($p, '=') !== false){
                        list($k, $v) = explode('=', $p);
                        $params[$k] = urldecode($v);
                    }
                }
            }
        }
        return self::getSignature($params, $secret);
    }

    static function getSignature($params, $secret){
        $str = '';
        ksort($params);
        foreach ($params as $k => $v) {
            $str .= "{$k}={$v}";
        }
        $str .= $secret;
        return md5($str);
    }
}