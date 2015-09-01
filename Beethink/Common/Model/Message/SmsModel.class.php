<?php
/**
 *     功能：短信发送
 *       by：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
		1.发送的内容会被正则匹配，所以内容需要满足一定格式，如 验证码:22323
		2.该接口仅供测试使用,每天的发送量不超过3条
	   
 */
class SmsModel {
	/**
	 *     功能：
     *       by：
     *     参数：
     *     返回：
     *     日期：
     *   修改人：
     * 修改时间：
	 */
	function sendMessage($Content, $phonenumber) 
    {
        //暂时关闭
        return true;
       
		$spnumber = time();
		$Content = $Content . "【酬诚网络】";
		
		$Content=iconv("utf-8","gbk",$Content);//这里需要转换成gbk
		$Content=urlencode($Content);
				
		//$Content = mb_convert_encoding ( $Content, "utf-8", "gbk" );
		$file = file_get_contents( "http://221.122.112.136:8080/sms/mt.jsp?cpName=choucheng&cpPwd=123456&phones=$phonenumber&spCode=$spnumber&msg=$Content&extNum=0");
		//print_r(iconv("gbk","utf-8",$file));exit;
		if($file==0){
			return TRUE;
		}else{
			return false;
		}		
	}
}

?>