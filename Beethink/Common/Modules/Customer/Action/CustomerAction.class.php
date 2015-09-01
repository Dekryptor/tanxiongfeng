<?php
class CustomerAction
{
    /**
     * 重置密码
     */
    public function resetPsd()
    {
        codecheck();
        
        $data=array(
            array('new_psd','string'),
            array('confirm_psd','string')
        );
        dataFilter($data,'post');
        
        //判断前后密码是否一致
        if($data['new_psd']!=$data['confirm_psd'])
        {
            returnJson(FAIL,'前后密码不一致',array('msgCode'=>301));
        }
        
        $upData=array(
            'psd'=>md5($data['new_psd'])
        );   
        
        $rs=D('Customer')->update($upData,'id=\''.$_SESSION['userinfo']['id'].'\'');   
         
        returnJson(SUCCESS,'','密码重置成功!');
    }
    /**
     * 修改密码
     */
    public function modifyPsd()
    {
        codecheck();
        
        $data=array(
            array('psd','string'),
            array('new_psd','string'),
            array('confirm_psd','string')
        );
        dataFilter($data,'post');
        
        //判断前后密码是否一致
        if($data['new_psd']!=$data['confirm_psd'])
        {
            returnJson(FAIL,'前后密码不一致',array('msgCode'=>301));
        }
        $oCust=D('Customer');
        //判断原密码是否正确
        if(!$oCust->checkPsd($_SESSION['userinfo']['id'],$data['psd']))
        {
            returnJson(FAIL,'原密码错误');
        }
        
        $upData=array(
            'psd'=>md5($data['new_psd'])
        );   
        
        $rs=$oCust->update($upData,'id=\''.$_SESSION['userinfo']['id'].'\'');    
        returnJson(SUCCESS,'','密码修改成功');
    }
    
    /**
     * 手机号注册
     * 
     * 手机号,密码
     */
    public function registPhone()
    {
        $data=array(
            array('phone','string','sj','手机号'),
            array('psd','string')
        );
        dataFilter($data,'post');
        
        $oCust=D('Customer');
        if($oCust->isRegistPhone($data['phone']))
        {
            returnJson(FAIL,'该手机号已经注册');
        }
        
        
        $cust_id=$oCust->registPhone($data['phone'],$data['psd']);
        
        $retData=$oCust->getUserinfo($cust_id);
        
        returnJson(SUCCESS,$retData);
    }
    
    /**
     * 保存用户编辑资料
     */   
     public function saveEdit()
     {
        codecheck();
        //参数对应
        $refData=array(
            'icon_id'=>array('icon_id','int'),
            'nicname'=>array('nicname','string'),
            'birth_time'=>array('birth_time','int'),
            'mail'=>array('mail',''),
            'sex'=>array('sex','ing'),
            'sign'=>array('sign','string')
        );   
        
        $upData=array();
        
        foreach($refData as $k=>$v)
        {
            if(isset($_POST[$k]))
            {
                $tmp=array(
                    array($k,$v[1])
                );
                dataFilter($tmp,'post');
                $upData[$v[0]]=$tmp[$k];    
            }
        }
        if($upData)
        {
            if(isset($upData['nicname']))
            {
                $upData['pinyin']=D('Language.Chn2pinyin')->Pinyin($upData['nicname']);
            }
            D('Customer')->update($upData,'id='.$_SESSION['userinfo']['id']);    
        }
       
        $retData=D('Customer')->getUserinfo($_SESSION['userinfo']['id']);
        
        returnJson(SUCCESS,$retData);
     }
     /**
      *  用户注销
      */
     public function logout()
     {
        codecheck();
        
        D('CustCode')->delete('0 AND cust_id='.$_SESSION['userinfo']['id']);
        
        returnJson(SUCCESS,'','注销成功!');
     }
}
?>