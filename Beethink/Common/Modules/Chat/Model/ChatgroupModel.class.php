<?php
/**
 * 群组操作
 */
class ChatgroupModel
{
    protected $trueTableName='tbl_chat_group';
    
    public function addGroup($c_id,$g_name)
    {
        $inData=array(
            'cg_name'=>$g_name,
            'cg_cust_id'=>$c_id,
            'cg_time'=>NOW
        );
        
        return M($this->trueTableName)->insert($inData);
    }
    /**
     * 获取用户自己创建的群
     */
    public function getUserOwnGroupInfo($c_id)
    {
        $rs=M($this->trueTableName)->select('cg_id,cg_time,cg_name','cg_cust_id='.$c_id);
        return $rs;
    }
}

?>