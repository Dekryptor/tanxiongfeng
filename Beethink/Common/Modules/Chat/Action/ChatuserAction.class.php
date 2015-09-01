<?php
/**
 * 好友管理
 */
class ChatuserAction
{
    /**
     * 获取好友列表
     */
    public function getFriendList()
    {     
        $c_id=1;
        
        $rs=D('Chatuser')->getFriendList($c_id);
        returnJson(SUCCESS,$rs);
    }
    /**
     * 删除好友
     */
    public function delFriend()
    {
        $c_id=1;
        $target_id=2;
        
        D('Chatuser')->delFriend($c_id,$target_id);
        
        returnJson(SUCCESS,'删除好友成功!');
    }
    /**
     * 添加好友
     */
    public function addFriend()
    {
           $c_id=1;
           $target_id=4;
           
           $rs=D('Chatuser')->addFriend($c_id,$target_id);
          
           $msg='好友请求已经发送!';
           if($rs===-1)
           {
                $msg='添加成功!';
           }else if($rs===true)
           {
                $msg='你们已经是好友了!';
           }           
           
           returnJson(SUCCESS,$msg);
    }
    /**
     * 获取新好友列表
     */
    public function getNewFriendList()
    {
        $c_id=1;
        
        $rs=D('Chatuser')->getNewFriendList($c_id);
        
        returnJson(SUCCESS,$rs);
    }
}
?>