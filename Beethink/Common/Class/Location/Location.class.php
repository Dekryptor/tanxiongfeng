<?php
class Location
{
    /**
     * ����ĳ����γ�ȵ���Χĳ�ξ���������ε��ĸ���
     *
     * @param
     *        	lng float ����
     * @param
     *        	lat float γ��
     * @param
     *        	distance float �õ�����Բ�İ뾶����Բ������������У�Ĭ��ֵΪ0.5ǧ�� ����������� ��
     * @return array �����ε��ĸ���ľ�γ������
     */
    static function  returnSquarePoint($lng, $lat, $distance = 0.5) {
        $diqiubanjin = 6378; // ����뾶KM
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
        // ˵���ӿ��ĵ� ��ַ http://developer.baidu.com/map/geocoding-api.htm

        // ��ַ http://api.map.baidu.com/geocoder?address=���԰&output=json&key=51LNZnbvFdV8TC3GF4cz7Y9d&city=�ɶ�
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

    // ����m;
    static function  getDistance($lng1, $lat1, $lng2, $lat2) 	// ���ݾ�γ�ȼ������
    {
        // ���Ƕ�תΪ����
        $radLat1 = deg2rad ( $lat1 );
        $radLat2 = deg2rad ( $lat2 );
        $radLng1 = deg2rad ( $lng1 );
        $radLng2 = deg2rad ( $lng2 );
        $a = $radLat1 - $radLat2; // ��γ��֮��,γ��<90
        $b = $radLng1 - $radLng2; // ������֮��γ��<180
        $s = 2 * asin ( sqrt ( pow ( sin ( $a / 2 ), 2 ) + cos ( $radLat1 ) * cos ( $radLat2 ) * pow ( sin ( $b / 2 ), 2 ) ) ) * 6378.137;
        return round ( $s * 1000 );
    }

    // ð������
    static function  maopao($result_all, $name, $order = 'desc') {
        $len = count ( $result_all );
        for($i = 1; $i < $len; $i ++) 		// �����n-1������
        {
            $flag = false; // ��������ʼǰ��������־ӦΪ��
            for($j = $len - 1; $j >= $i; $j --) {
                if ($order == "desc") {
                    if ($result_all [$j] [$name] > $result_all [$j - 1] [$name]) 					// ������¼
                    { // ����ǴӴ�С�Ļ���ֻҪ��������жϸĳ�if($arr[$j]>$arr[$j-1])�Ϳ�����
                        $x = $result_all [$j];
                        $result_all [$j] = $result_all [$j - 1];
                        $result_all [$j - 1] = $x;
                        $flag = true; // �����˽������ʽ�������־��Ϊ��
                    }
                } else {
                    if ($result_all [$j] [$name] < $result_all [$j - 1] [$name]) 					// ������¼
                    { // ����ǴӴ�С�Ļ���ֻҪ��������жϸĳ�if($arr[$j]>$arr[$j-1])�Ϳ�����
                        $x = $result_all [$j];
                        $result_all [$j] = $result_all [$j - 1];
                        $result_all [$j - 1] = $x;
                        $flag = true; // �����˽������ʽ�������־��Ϊ��
                    }
                }
            }
            if (! $flag) // ��������δ������������ǰ��ֹ�㷨
                break;
        }
        return $result_all;
    }
}