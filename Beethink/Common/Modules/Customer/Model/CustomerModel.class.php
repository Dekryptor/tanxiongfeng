<?php
class CustomerModel
{
    protected $trueTableName='tbl_customer';
    
    public function insert($inData)
    {
        return M($this->trueTableName)->insert($inData);
    }
    public function update($upData)
    {
        return M($this->trueTableName)->update($upData);
    }
    
    /**
     *  注册手机号
     */
    public function registPhone($phone,$psd)
    {
        $nicname='x'.D('Identify')->getNumCode(9);
        $no=D('Identify')->getNumCode(9);
        
        $inData=array(
            'nicname'=>$nicname,
            'psd'=>md5($psd),
            'phone'=>$phone,
            'reg_time'=>NOW,
            'no'=>$no,
            'pinyin'=>D('Language.Chn2pinyin')->Pinyin($nicname)
            
        );
      
        $cust_id=M($this->trueTableName)->insert($inData);
        
        
        D('CustCode')->add($cust_id);
        
        return $cust_id;
    }
    
    public function getUserinfo($id)
    {
        $rs=M($this->trueTableName)->select('id,nicname,type,phone,sex,birth_time,icon_id,no,sign,pinyin,mail','id='.$id,1);
        
        if($rs)
        {
            $rs['code']=D('CustCode')->getCode($rs['id']);
        }
        
        return $rs?$rs:'::';          
    }
    /**
     * 判断该手机是否注册
     */
    public function isRegistPhone($phone)
    {
        return M($this->trueTableName)->select('id','phone=\''.$phone.'\'',1);
    }
    /**
     * 判断用户是否被禁用
     */
    public function isForbid($c_id)
    {
        $rs=M($this->trueTableName)->select('status','id='.$c_id,1);
        return $rs?$rs['status']:0;
    }
    /**
     *  判断密码是否正确
     */
    public function checkPsd($c_id,$psd)
    {
        $rs=M($this->trueTableName)->select('psd','id='.$c_id,1);
        
        if($rs)
        {
            return $rs['psd']==md5($psd);
        }
        
        return false;
    }
}

?>