<?php
class ChatmsgModel
{
    protected $trueTableName='tbl_chat_message';
    
    public function getMsg($c_id)
    {
        $msg_refer=array(
            '20'=>'img_msg',
            '21'=>'voice_msg',
            '22'=>'otherfile_msg'
        );
        
        $rs=M($this->trueTableName)->query('CALL pro_getMsg('.$c_id.')');
        $retData=array();
            
        foreach($rs as $v)
        {
            $msg_name=isset($msg_refer[$v['msg_type']])?$msg_refer[$v['msg_type']]:'msg';
            
            $retData[]=array(
                'msg_id'=>$v['um_id'],
                $msg_name=>$msgContent,
                'msg_type'=>$v['msg_type'],
                'msg_time'=>$v['msg_time'],
                'perOrGroup'=>$v['perOrGroup'],
                'receive_userid'=>$v['receive_id'],
                'group_id'=>$v['group_id']
            );
               
        }
        return $retData;
    }
    public function sendMsg($userData,$conData)
    {
        //保存消息内容
        $msg_id=M($this->trueTableName)->insert($conData);
       
        $userData['um_msg_id']=$msg_id;
        $oHandle=M('tbl_user_msg');
        
        if($userData['um_perOrGroup']==1)
        {
            //判断是否为黑名单成员
            if(D('ChatBlacklist')->isBlacklistMem($userData['um_receive_userid'],$userData['um_cust_id']))
            {
                returnJson(FAIL,'请先加为好友!');
            }
            $oHandle->insert($userData);
        }
        else
        {
            $mem_ids=D('Groupuser')->getGroupMemIDS($userData['um_group_id']);
            foreach($mem_ids as $v)
            {
                $v['um_receive_userid']=$v;
                $oHandle->insert($userData);
            }
        }
        return $msg_id;
    }   
}
?>