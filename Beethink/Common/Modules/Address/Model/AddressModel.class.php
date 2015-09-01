<?php
/**
 * 功能:获取用户地址信息
 */
class AddressModel
{
    protected $trueTableName='tbl_cust_address';
    /**
     * 获取指定用户地址信息
     */
    public function getAddr($c_id)
    {
        return M($this->trueTableName)->select('id,cust_id,addr,name,phone,recommend','cust_id='.$c_id);
    }
    
    /**
     * 地址添加
     */
    public function insert($data)
    {
        return M($this->trueTableName)->insert($data);
    }
    /**
     * 更新
     */
    public function update($data,$where)
    {
        return M($this->trueTableName)->update($data,$where);
    }
}
?>