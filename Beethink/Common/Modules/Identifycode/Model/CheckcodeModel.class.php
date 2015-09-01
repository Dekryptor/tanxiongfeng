<?php
/**
 * 验证码错误
 */
class CheckcodeModel
{
    protected $trueTableName='tbl_identify',
            $timeout=60;
    
    public function insert($data)
    {
        return M($this->trueTableName)->insert($data);
    }
    public function update($data)
    {
        return M($this->trueTableName)->update($data);
    }
    /**
    * 添加记录 
    */   
    public function add($tel,$code)
    {
        $oM=M($this->trueTableName);
        /**
         * 判断tel值是否存在,如果存在则直接更新,否则插入
         */
        $rs=$oM->select('i_id','i_phone_num=\''.$tel.'\'',1);
        
        if($rs)
        {
            $upData=array(
                'i_code'=>$code,
                'i_time'=>NOW+$this->timeout
            );
            return $oM->update($upData,'i_phone_num=\''.$tel.'\'');
        }
        else
        {
            return $oM->insert(array('i_code'=>$code,'i_phone_num'=>$tel,'i_time'=>NOW+$this->timeout));
        }
    }
    /**
     * 检验验证码是否合法
     */
    public function checkCorrent($tel,$code)
    {     
        $rs=M($this->trueTableName)->select('i_id,i_code','i_phone_num=\''.$tel.'\'',1);
        if($rs && $rs['i_code']==$code)
        {
            return true;
        }
        return false;
    }
}
?>