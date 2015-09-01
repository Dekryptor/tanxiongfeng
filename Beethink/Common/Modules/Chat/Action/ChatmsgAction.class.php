<?php
/**
 * 消息
 */
class ChatmsgAction
{   //获取消息
    public function getMsg()
    {
        $c_id=1;
        
        $rs=D('Chatmsg')->getMsg($c_id);
        
        returnJson(SUCCESS,$rs);
    }
} 
?>