<?php
/**
 *  功能:定位处理
 * 
 */
class LocationModel
{
    /**
     * 获取 省名,城市名,区名对应id
     */
    public function getRefer($province_name,$city_name,$area_name)
    {
        $retData=array(
            'province_id'=>0,
            'city_id'=>0,
            'area_id'=>0
        );
        //省
        if($province_name)
        {
            $province=M('s_province')->select('ProvinceID','ProvinceName LIKE \''.$province_name.'%\'',1);
            if($province)
            {
                $retData['province_id']=$province['ProvinceID'];
            }
        }
        //市
        if($city_name)
        {
            $where='CityName LIKE \''.$city_name.'%\'';
            if($retData['province_id'])
            {
                $where.=' AND ProvinceID='.$retData['province_id'];
            } 
            $rs=M('s_city')->select('CityID',$where,1);
            if($rs)
            {
                $retData['city_id']=$rs['CityID'];
            }
        }
        //区
        if($area_name)
        {
            $where='DistrictName LIKE \''.$area_name.'%\'';
            if($retData['city_id'])
            {
                $where.=' AND CityId='.$retData['city_id'];
            }
            $rs=M('s_district')->select('DistrictID',$where,1);
            if($rs)
            {
                $retData['area_id']=$rs['DistrictID'];
            }
        }
        return $retData;
    }
    /**
     * 地址拼接
     */
     public function getAddress($province_id,$city_id,$area_id,$ext)
     {
        $addrArr=array();
        
        if($province_id)
        {
            $rs=M('s_province')->select('ProvinceName','ProvinceID='.$province_id,1);
            if($rs)
            {
                $addrArr[]=$rs['ProvinceName'];
            }
        }
        if($city_id)
        {
            $rs=M('s_city')->select('CityName','CityID='.$city_id,1);
            if($rs)
            {
                $addrArr[]=$rs['CityName'];
            }
        }
        if($area_id)
        {
            $rs=M('s_district')->select('DistrictName','DistrictID='.$area_id,1);
            if($rs)
            {
                $addrArr[]=$rs['DistrictName'];
            }
        }
        $addrArr[]=$ext;
      
        return implode('',$addrArr);
     }
    
}
?>