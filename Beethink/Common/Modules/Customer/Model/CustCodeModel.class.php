<?php
class CustCodeModel
{
    protected $trueTableName='tbl_cust_code';
    
    public function add($cust_id)
    {
        //判断该用户id是否存在
        
        $code=D('Identify')->getIdentifyCode();
        
        $rs=$this->isExists($cust_id);
        
        $data=array(
            'code'=>$code,
            'expire_time'=>$this->getExipreTime(),
            'type'=>LOGIN_TYPE
        );
        
        
        if($rs)
        {
            return M($this->trueTableName)->update($data,'id='.$rs['id']);   
        }
        else
        {
            $data['cust_id']=$cust_id;
            return M($this->trueTableName)->insert($data);
        }
    }
    /**
     * 获取过期时间
     */
    public function getExipreTime()
    {
        return CODE_VALID_TIME==0?0:NOW+CODE_VALID_TIME;    
    }
    
    /**
     * 判断记录是否已经存在
     */
    public function isExists($cust_id)
    {
        $rs=M($this->trueTableName)->select('id','cust_id='.$cust_id.' AND type='.LOGIN_TYPE,1);
        
        return $rs?$rs['id']:0;
    }
    /**
     * 判断code是否有效
     */
    public function checkValid($code)
    {
        $rs=M($this->trueTableName)->select('cust_id,code,expire_time','code=\''.$code.'\' AND type='.LOGIN_TYPE,1);
        if($rs)
        {
            if($code==$rs['code'])
            {
                if($rs['expire_time']==0 || NOW<$rs['expire_time'])
                {
                    return $rs['cust_id'];
                }
                else
                {
                    return -1;
                }
            } 
        }
        return false;
    }
    /**
     * 获取当前用户code
     */
    public function getCode($cust_id)
    {
        $rs=M($this->trueTableName)->select('code','cust_id='.$cust_id,1);
        
        return $rs?$rs['code']:'';
    }
    public function delete($where)
    {
        return M($this->trueTableName)->delete($where);
    }
}

?>