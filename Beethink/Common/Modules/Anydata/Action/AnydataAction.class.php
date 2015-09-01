<?php
class AnydataAction
{
    /**
     * 获取平台介绍
     */
     public function getSysIntroduce()
     {
        $rs=D('Anydata')->anydata('sys_introduce');
        returnJson(SUCCESS,$rs);
     }
     /**
      * 获取订座的提示信息
      */
     public function getBookingTips()
     {
        $rs=D('Anydata')->anydata('sys_booking_tips');
        returnJson(SUCCESS,$rs);  
     }
     /**
      * 获取用户注册协议
      */
     public function getCustRegistProtocol()
     {
        $rs=D('Anydata')->anydata('sys_cust_regist_protocol');
        
        returnJson(SUCCESS,$rs);
     }
}
?>