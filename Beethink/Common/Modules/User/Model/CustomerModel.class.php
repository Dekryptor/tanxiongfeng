<?php
/**
 *     功能：tbl_customer表相关操作
 *   创建人：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
*/
class CustomerModel
{
    protected $trueTableName='tbl_customer';
    /**
    * 判断该手机是否已经注册
    */
    public function checkPhoneIsRegist($tel)
    {
        return M($this->trueTableName)->select('c_id','c_phone=\''.$tel.'\'',1);
    }
    /**
     * 添加用户
     */
     public function insert($arrData=array())
     {
        return M($this->trueTableName)->insert($arrData);
     }
     /**
      * 获取指定手机号记录
      */
     public function getRecByTel($tel)
     {
        return M($this->trueTableName)->select('c_id,c_nicname,c_psd,c_type,c_sex','c_phone=\''.$tel.'\'',1);
     }
     /**
      * 更新记录
      */
      public function update($data,$where)
      {
        return M($this->trueTableName)->update($data,$where);
      }
     /**
      * 根据用户id获取记录
      */
     public function getRecById($c_id)
     {
        return M($this->trueTableName)->select('c_nicname,c_phone','c_id='.$c_id,1);
     }
    /**
     * 获取用户信息
     */
    public function getUserInfo($c_id)
    {
        return M($this->trueTableName)->select('c_nicname AS nicname,c_sex AS sex,c_time AS time','c_id='.$c_id,1);
    }
    
}
?>