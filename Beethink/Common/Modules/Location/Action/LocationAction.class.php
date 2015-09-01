<?php
/**
 *     功能：定位相关
 *   创建人：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
*/
class LocationAction
{
    /**
	 *     功能：根据文字地址信息获取相应的id
     *     参数：省名，市名，区名
     *     返回：
     *     备注：
	 */
    public function getIdRefer()
    {
        $data=array(
            array('province','string'),
            array('city','string'),
            array('district','string')
        );
        
        dataFilter($data,'post');
        
        $provinceRs=$data['province']?M('s_province')->select('ProvinceID','ProvinceName=\''.$data['province'].'\'',1):'';
        $cityRs=$data['city']?M('s_city')->select('CityID','CityName=\''.$data['city'].'\'',1):'';
        $districtRs=$data['district']?M('s_district')->select('DistrictID','DistrictName=\''.$data['district'].'\'',1):'';
        
         $rs = array(
            'province'=>($provinceRs?$provinceRs['ProvinceID']:''),
            'city'=>($cityRs?$cityRs['CityID']:''),
            'district'=>($districtRs?$districtRs['DistrictID']:'')
        );
        returnJson(SUCCESS,$rs);
    }
    /**
	 *     功能：根据省id获取对应市结点内容
     *     参数：省id
     *     返回：
     *     备注：
	 */
     public function getCityByProId()
     {
        
        $data=array(
            array('province_id','int')
        );
       
        dataFilter($data,'post');
        
       
        $rs= M('s_city')->select('CityID,CityName','ProvinceID='.$data['province_id']);
        returnJson(SUCCESS,$rs);
     }
     /**
	 *     功能：根据市id获取对应区结点内容
     *     参数：市id
     *     返回：
     *     备注：
	 */
     public function getDistrictByCityId()
     {
        $filterData=array(
            array('city_id','int')
        );
        $data=D('Basefilter')->postFilter($filterData);
        
        if(!$data){
            returnJson(FAIL,'unlawful request');
        }
        
        $rs=M('s_district')->select('DistrictID,DistrictName','CityID='.$data['city_id']);
        returnJson(SUCCESS,$rs);
     }
     /**
	 *     功能：获取省列表
     *     参数：
     *     返回：
     *     备注：
	 */
     public function getProvinceList()
     {
         $rs=M('s_province')->select('ProvinceID,ProvinceName');
         returnJson(SUCCESS,$rs); 
     }  
     /**
	 *     功能：获取市列表
     *     参数：
     *     返回：
     *     备注：
	 */
     public function getCityList()
     {
        $rs= M('s_city')->select('CityID,CityName');
        returnJson(SUCCESS,$rs);
     }
     /**
	 *     功能：获取区列表
     *     参数：
     *     返回：
     *     备注：
	 */
     public function getDistrictList()
     {
        $rs= M('s_district')->select('DistrictID,DistrictName');
        returnJson(SUCCESS,$rs);
     }
     /**
      * 获取推荐城市
      */
      public function getRecommendCity()
      {
        $rs=M('s_city')->select('CityID,CityName','Recommend=1');
        returnJson(SUCCESS,$rs);
      }
      /**
       * 获取推荐省份
       */
      public function getRecommendProvince()
      {
        $rs=M('s_province')->select('ProvinceID,ProvinceName','Recommend=1');
        returnJson(SUCCESS,$rs);
      }
}
?>