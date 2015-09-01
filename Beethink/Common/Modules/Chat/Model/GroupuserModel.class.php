<?php
/**
 * 群组成员管理
 */
class GroupuserModel
{
    protected $trueTableName='tbl_group_user';
    //新增群成员
    public function addUser($g_id,$c_id)
    {
        //判断是否已经存在该成员
        if($this->isGroupMem($g_id,$c_id))
        {
            return true;
        }
        $inData=array(
            'gu_group_id'=>$g_id,
            'gu_cust_id'=>$c_id
        );        
        return M($this->trueTableName)->insert($inData);
    }
    
    //判断某个用户是否为群成员
    public function isGroupMem($g_id,$c_id)
    {
        return M($this->trueTableName)->select('gu_id','gu_group_id='.$g_id.' AND gu_cust_id='.$c_id,1);
    }
    //删除群众某个成员
    public function delGroupMem($g_id,$c_id)
    {
        return M($this->trueTableName)->delete('gu_group_id='.$g_id.' AND gu_cust_id='.$c_id);
    }
    //修改群成员备注
    public function alterMemRemark($g_id,$c_id,$remark)
    {
        $upData=array(
            'gu_mark'=>$remark
        );   
        return M($this->trueTableName)->update($upData,'gu_group_id='.$g_id.' AND gu_cust_id='.$c_id);
    }
    //获取用户群列表
    public function getGroupList($c_id)
    {
        $my=D('Chatgroup')->getUserOwnGroupInfo($c_id);
        $join=$this->getGroupInfo($c_id);
        
        $retData=array(
            'my'=>$my,
            'join'=>$join
        );
        return $retData;
    }
    //获取某群的成员列表
    public function getGroupMemList($g_id)
    {
        $rs=M($this->trueTableName)->query('SELECT a.gu_mark AS gu_mark,b.c_nicname AS c_nicname,c.i_src AS img,a.gu_cust_id AS gu_cust_id FROM tbl_group_user a
LEFT JOIN tbl_customer b ON a.gu_cust_id=b.c_id
LEFT JOIN tbl_image c ON c.i_id=b.c_img_id
WHERE a.gu_cust_id='.$g_id);
        return $rs;
    }
    //获取我加入的群信息
    public function getGroupInfo($c_id)
    {   
        $rs=M($this->trueTableName)->query('SELECT a.gu_group_id AS gu_group_id,b.cg_name AS cg_name,b.cg_cust_id AS owner_id FROM tbl_group_user a 
LEFT JOIN tbl_chat_group b ON b.cg_id=a.gu_group_id
WHERE a.gu_cust_id='.$c_id);
        
        return $rs;
    }
    //获取用户成员id
    public function getGroupMemIDS($g_id)
    {
        $rs=M($this->trueTableName)->select('GROUP_CONCAT(cust_id) AS cust_ids','group_id='.$g_id);
        return explode(',',$rs[0]['cust_ids']);
    } 
}
?>