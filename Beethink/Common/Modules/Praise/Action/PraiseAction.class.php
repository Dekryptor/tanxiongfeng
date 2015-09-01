<?php
/**
 * 点赞相关
 */
class PraiseAction
{
    //获取点赞数
    public function getPraiseCount()
    {
        $data=array(
            array('obj_id','int')
        );
        dataFilter($data,'post');
        
        $rs=D('Praise')->getPraiseCount($data['obj_id']);
        returnJson(SUCCESS,$rs);
    }
    //获取点赞成员信息
    public function getPraiseMem()
    {
        $data=array(
            array('obj_id','int')
        );
        dataFilter($data,'post');
        
        $rs=D('Praise')->getPraiseMem($data['obj_id']);
        returnJson(SUCCESS,$rs);
    }
    //点赞
    public function addPraise()
    {
        $data=array(
            array('obj_id','int')
        );
        dataFilter($data,'post');
        $user_id=1;
        
        $rs=D('Praise')->addPraise($user_id,$data['obj_id']);
        $msg=$rs==-1?'取消点赞成功!':'点赞成功!';
        
        returnJson(SUCCESS,$msg);
    }
}
?>