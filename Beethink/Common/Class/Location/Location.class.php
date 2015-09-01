<?php
class Location
{
    /**
     * 计算某个经纬度的周围某段距离的正方形的四个点
     *
     * @param
     *        	lng float 经度
     * @param
     *        	lat float 纬度
     * @param
     *        	distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米 （计算有误差 ）
     * @return array 正方形的四个点的经纬度坐标
     */
    static function  returnSquarePoint($lng, $lat, $distance = 0.5) {
        $diqiubanjin = 6378; // 地球半径KM
        $dlng = 2 * asin ( sin ( $distance / (2 * $diqiubanjin) ) / cos ( deg2rad ( $lat ) ) );
        $dlng = rad2deg($dlng);
        $dlat = $distance / $diqiubanjin;
        $dlat = rad2deg ( $dlat );
        $squares = array (
            'left-top' => array (
                'lat' => $lat + $dlat,
                'lng' => $lng - $dlng
            ),
            'right-top' => array (
                'lat' => $lat + $dlat,
                'lng' => $lng + $dlng
            ),
            'left-bottom' => array (
                'lat' => $lat - $dlat,
                'lng' => $lng - $dlng
            ),
            'right-bottom' => array (
                'lat' => $lat - $dlat,
                'lng' => $lng + $dlng
            )

        );
        return $squares;
    }

    static function  getGps($city, $ds) {
        // 说明接口文档 地址 http://developer.baidu.com/map/geocoding-api.htm

        // 地址 http://api.map.baidu.com/geocoder?address=软件园&output=json&key=51LNZnbvFdV8TC3GF4cz7Y9d&city=成都
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, "http://api.map.baidu.com/geocoder?address=$ds&output=json&key=51LNZnbvFdV8TC3GF4cz7Y9d&city=$city" );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        $output = curl_exec ( $ch );
        curl_close ( $ch );
        $ac = json_decode ( $output );
        if (isset ( $ac->result->location->lng )) {
            return array (
                $ac->result->location->lng,
                $ac->result->location->lat
            );
        } else {
            return false;
        }
    }

    // 返回m;
    static function  getDistance($lng1, $lat1, $lng2, $lat2) 	// 根据经纬度计算距离
    {
        // 将角度转为狐度
        $radLat1 = deg2rad ( $lat1 );
        $radLat2 = deg2rad ( $lat2 );
        $radLng1 = deg2rad ( $lng1 );
        $radLng2 = deg2rad ( $lng2 );
        $a = $radLat1 - $radLat2; // 两纬度之差,纬度<90
        $b = $radLng1 - $radLng2; // 两经度之差纬度<180
        $s = 2 * asin ( sqrt ( pow ( sin ( $a / 2 ), 2 ) + cos ( $radLat1 ) * cos ( $radLat2 ) * pow ( sin ( $b / 2 ), 2 ) ) ) * 6378.137;
        return round ( $s * 1000 );
    }

    // 冒泡排序
    static function  maopao($result_all, $name, $order = 'desc') {
        $len = count ( $result_all );
        for($i = 1; $i < $len; $i ++) 		// 最多做n-1趟排序
        {
            $flag = false; // 本趟排序开始前，交换标志应为假
            for($j = $len - 1; $j >= $i; $j --) {
                if ($order == "desc") {
                    if ($result_all [$j] [$name] > $result_all [$j - 1] [$name]) 					// 交换记录
                    { // 如果是从大到小的话，只要在这里的判断改成if($arr[$j]>$arr[$j-1])就可以了
                        $x = $result_all [$j];
                        $result_all [$j] = $result_all [$j - 1];
                        $result_all [$j - 1] = $x;
                        $flag = true; // 发生了交换，故将交换标志置为真
                    }
                } else {
                    if ($result_all [$j] [$name] < $result_all [$j - 1] [$name]) 					// 交换记录
                    { // 如果是从大到小的话，只要在这里的判断改成if($arr[$j]>$arr[$j-1])就可以了
                        $x = $result_all [$j];
                        $result_all [$j] = $result_all [$j - 1];
                        $result_all [$j - 1] = $x;
                        $flag = true; // 发生了交换，故将交换标志置为真
                    }
                }
            }
            if (! $flag) // 本趟排序未发生交换，提前终止算法
                break;
        }
        return $result_all;
    }
}