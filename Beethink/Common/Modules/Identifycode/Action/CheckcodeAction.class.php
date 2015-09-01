<?php
/**
 * 验证码操作
 */
class CheckcodeAction
{
    /**
     * 获取验证码
     */
    public function getIdentify()
    {
        $data=array(
            array('phone','string','sj','手机'),
        );
        dataFilter($data,'post');
        
        //生成随机码并通过短信的形式通知手机端
        $code=D('Identify')->getNumCode(6);
        
        D('Checkcode')->add($data['phone'],$code);
        
        returnJson(SUCCESS,'','验证码已成功发送！');   
    }
    /**
    * 判断验证码是否相同
    */
   public function isEqualCode()
   {
        $data=array(
            array('phone','string','sj','手机'),
            array('identifyCode','string')
        );
        dataFilter($data,'post');
        
        if(D('Checkcode')->checkCorrent($data['phone'],$data['identifyCode']))
        {
            returnJson(SUCCESS,'','验证成功');
        }
        else
        {
            returnJson(FAIL,'验证码错误');
        }
        
        $data=D('Basefilter')->postFilter($filterData);
        
        if($data)
        {
            if(D('Code')->checkCorrent($data['phone'],$data['code']))
            {
                returnJson(SUCCESS,'','验证成功');
            }
            else
            {
                returnJson(FAIL,'验证码错误');
            }
        }
        else
        {
            returnJson(FAIL,'unlawful request');
        }
   }
}
?>