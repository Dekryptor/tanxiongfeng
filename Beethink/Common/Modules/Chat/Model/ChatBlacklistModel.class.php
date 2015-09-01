<?php

class ChatBlacklistModel
{
    protected $trueTableName='tbl_chat_blacklist';
    public function add($c_id,$target_id)
    {
        //判断是否已经被黑名单了
        $rs=M($this->trueTableName)->select('id','cust_id='.$c_id.' AND target_id='.$target_id,1);
        
        if(!$rs)
        {
            $inData=array(
                'cust_id'=>$c_id,
                'target_id'=>$target_id
            );
            return M($this->trueTableName)->insert($inData);    
        }
        return true;        
    }
    public function del($c_id,$target_id)
    {
        return M($this->trueTableName)->delete('cust_id='.$c_id.' AND target_id='.$target_id);
    }
    //判断target_id是否是c_id黑名单用户
    public function isBlacklistMem($c_id,$target_id)
    {
        return M($this->trueTableName)->select('id','cust_id='.$c_id.' AND target_id='.$target_id,1);
    }
}
?>