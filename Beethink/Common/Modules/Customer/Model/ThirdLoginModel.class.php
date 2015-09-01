<?php
/**
 * type : 1=>qq 2=>微信 3=>新浪
 */
class ThirdLoginModel
{
    protected $trueTableName='tbl_third_login';
    
    public function isRegisted($openId,$type)
    {
        return M($this->trueTableName)->select('cust_id','type='.$type.' AND openid=\''.$openId.'\'',1);
    }
    public function regist($data,$type)
    {
        $identifyCode=D('Identify')->getIdentifyCode();
            
        $inData=array(
            'i_src'=>$data['icon']
        );
        
        $img_id=D('Img')->insert($inData);
        
        $inData=array(
            'c_nicname'=>$data['nicname'],
            'c_psd'=>' ',
            'c_phone'=>' ',
            'c_time'=>NOW,
            'c_sex'=>$data['sex'],
            'c_cust_code'=>$identifyCode,
            'c_icon_id'=>$img_id,
            'c_no'=>D('Identify')->getSerialNum(),
            'pinyin'=>D('Language.Chn2pinyin')->Pinyin($data['nicname'])
        );
        
        $cust_id=D('Customer')->insert($inData);
        
        $inData=array(
            'openid'=>$data['openid'],
            'type'=>$type,
            'cust_id'=>$cust_id
        );
        
        $insId=M($this->trueTableName)->insert($inData);
        
        return $cust_id;
    }   
}
?>