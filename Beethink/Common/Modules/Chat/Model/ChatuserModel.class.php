<?php
class ChatuserModel
{
    protected $trueTableName='tbl_chat_user';
    
    //包含用户头像,名称,说说,备注
    public function getFriendList($c_id)
    {
        $classify_rs=$this->getClassifyInfo($c_id);
        foreach($classify_rs as &$v)
        {
            $v['member']=$this->getMemByClassifyId($c_id,$v['classify_id']);        
        }  
        return $classify_rs;
    }
    public function getMemByClassifyId($c_id,$classify_id)
    { 
       $rs=M($this->trueTableName)->query('SELECT a.cu_friend_id AS friend_id,a.cu_remark AS remark,c.i_src AS img,b.c_nicname AS nicname,a.cu_classify_id AS classify_id FROM tbl_chat_user a
LEFT JOIN tbl_customer b ON b.c_id=a.cu_friend_id
LEFT JOIN tbl_image c ON b.c_img_id
WHERE a.cu_cust_id='.$c_id.' AND a.cu_classify_id='.$classify_id.' AND a.cu_status=1');

        return $rs;
    }
    public function delFriend($c_id,$fri_id)
    {
        return M($this->trueTableName)->delete('cu_cust_id='.$c_id.' AND cu_friend_id='.$fri_id);
    }
    //返回 -1 则为对象 黑名单中,拒绝添加
    public function addFriend($c_id,$fri_id)
    {
        //判断该用户是否在黑名单中,如果是则拒绝
        if(D('ChatBlacklist')->isBlacklistMem($fri_id,$c_id))
        {
            return -1;
        }
        //判断当前好友是否已经存在
        if($this->isFriend($c_id,$fri_id))
        {
            return true;
        }
        $inData=array(
            'cu_cust_id'=>$c_id,
            'cu_friend_id'=>$fri_id
        );
        return M($this->trueTableName)->insert($inData);
    }
    //获取 添加好友 的列表
    public function getNewFriendList($c_id)
    {
        $rs=M($this->trueTableName)->query('SELECT a.cu_friend_id AS friend_id,c.i_src AS img,b.c_nicname AS nicname FROM tbl_chat_user a
LEFT JOIN tbl_customer b ON b.c_id=a.cu_friend_id
LEFT JOIN tbl_image c ON b.c_img_id
WHERE a.cu_target_id='.$c_id.' AND a.cu_status=0');
        return $rs;
    }
    //判断是否为好友
    public function isFriend($c_id,$target_id)
    {
        return M($this->trueTableName)->select('cu_id','cu_cust_id='.$c_id.' AND cu_friend_id='.$target_id,1);
    }
    //获取分组情况
    public function getClassifyInfo($c_id)
    {
        $rs=M($this->trueTableName)->query('SELECT DISTINCT cu_classify_id AS classify_id FROM '.$this->trueTableName.' WHERE cu_cust_id='.$c_id);
        $oClassify=D('ChatClassify');
        foreach($rs as &$v)
        {
            $tmp=$oClassify->getClassifyNameById($v['classify_id']);
            $v['classify_name']=$tmp['cc_name'];
        }
        return $rs; 
    }
}
?>