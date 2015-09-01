<?php
/**
 * 用户相关处理
 */
class CustomerAction
{
    //密码修改
    public function resetPsd()
    {
        $filterData=array(
            array('tel','string'),
            array('identifyCode','string'),
            array('psd','string'),
            array('confirm_psd','string')
        );
        $data=D('Basefilter')->postFilter($filterData);
        
        if(!$data){
            returnJson(FAIL,'unlawful request');
        }
        //判断前后密码是否一致
        if($data['psd']!=$data['confirm_psd'])
        {
            returnJson(FAIL,'前后密码不一致',array('msgCode'=>301));
        }
        //判断手机号是否已经注册
        if(!D('Customer')->checkPhoneIsRegist($data['tel']))
        {
            returnJson(FAIL,'手机号未注册',array('msgCode'=>'403'));
        }
        
        //判断验证码是否正确
        
        if(!D('Identify')->checkCorrent($data['tel'],$data['identifyCode']))
        {
            returnJson(FAIL,'验证码错误',array('msgCode'=>303));
        }
        
        $upData=array(
            'c_psd'=>md5($data['psd'])
        );   
        $oCust=D('Customer');
        $rs=$oCust->update($upData,'c_phone=\''.$data['tel'].'\'');    
        if($rs)
        {
            returnJson(SUCCESS,'密码修改成功');
        }
        else
        {
            returnJson(FAIL,' 操作失败');   
        }
    }
    
    /**
     * 获取当前注销的信息
     */
    public function getLogoutMsg()
    {
        new Codecheck();
        $rs=D('Customer')->getLogoutMsg($_SESSION['userinfo']['cc_cust_id']);
        
        returnJson(SUCCESS,$rs);
    }
    /**
     * 会员注销
     */
    public function logOut()
    {
        new Codecheck();
        $account=D('Customer')->delete('c_id='.$_SESSION['userinfo']['cc_cust_id']);
        returnJson(SUCCESS,'注销成功');
    }
    /**
     * 获取会员信息
     */
    public function getUserInfo()
    {
        new Codecheck();
        
        $rs=D('Customer')->getUserInfo($_SESSION['userinfo']['cc_cust_id']);
        
        returnJson(SUCCESS,$rs);
    }
    /**
     * 保存会员信息
     */
    public function saveUserInfo()
    {
        new Codecheck();
        
        $pData=array(
            array('name','string'),
            array('sex','int'),
            array('time','int')
        );
        
        dataFilter($pData,'post');
        
        $upData=array(
            'c_nicname'=>$pData['name'],
            'c_sex'=>$pData['sex'],
            'c_time'=>$pData['time']
        );
               
        D('Customer')->update($upData,'c_id='.$_SESSION['userinfo']['cc_cust_id']);
        
        returnJson(SUCCESS,'操作成功');
    }
    
    /**
     * 修改绑定手机号
     */
    public function modifyPhoneNum()
    {
        new Codecheck();
        
        $data=array(
            array('phone','string'),
            array('identifyCode','string'),
            array('new_phone','string')
        );
        
        dataFilter($data,'post');
       
        if(D('identify')->checkCorrent($data['phone'],$data['identifyCode']))
        {
            $upData=array(
                'c_phone'=>$data['new_phone']
            );
            D('Customer')->update($upData,'c_id='.$_SESSION['userinfo']['cc_cust_id']);
            
            returnJson(SUCCESS,'修改成功');
        }
        returnJson(FAIL,'验证码错误');
    }
}

?>