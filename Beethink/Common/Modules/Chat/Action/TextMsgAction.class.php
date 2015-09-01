<?php
/**
 * 消息相关
 */
class TextMsgAction //extends Codecheck
{
    /**
     * 发送消息
     */
    public function sendMsg()
    {
        $cust_id=2;
        
        $data=array(
            array('target_id','int'),
            array('msg_type','int'),
            array('msg','string'),
            array('perOrGroup','int')     //群消息 和 用户消息
        );
        dataFilter($data,'post');
        
        $user_id=$group_id=0;
        
        $data['perOrGroup']==1?$user_id=$data['target_id']:$group_id=$data['target_id'];
        
        $userData=array(
            'um_cust_id'=>$cust_id,
            'um_receive_userid'=>$user_id,
            'um_perOrGroup'=>$data['perOrGroup'],
            'um_group_id'=>$group_id
        );
        $conData=array(
            'cm_time'=>NOW,
            'cm_content'=>$data['msg'],
            'cm_type'=>$data['msg_type']
        );
        $msg_id=D('Chatmsg')->sendMsg($userData,$conData);
        
        $retData=array(
            'msg_id'=>$msg_id,
            'msg'=>$data['msg'],
            'msg_type'=>$data['msg_type'],
            'msg_time'=>$data['msg'],
            'perOrGroup'=>$data['perOrGroup'],
            'receive_userid'=>$user_id,
            'group_id'=>$group_id
        );
        returnJson(SUCCESS,$retData);
    }
}
?>