<?php
/**
 * 点赞
 */
class PraiseModel
{
    protected $trueTableName='tbl_praise';
    //获取点赞数
    public function getPraiseCount($obj_id)
    {
        $rs=M($this->trueTableName)->select('COUNT(0) AS total','p_obj_id='.$obj_id);
        
        return $rs[0]['total'];
    }
    //获取点赞成员信息
    public function getPraiseMem($obj_id)
    {
        $rs=M($this->trueTableName)->query('SELECT b.c_id AS cust_id,b.c_nicname AS nicname FROM '.$this->trueTableName.' a 
LEFT JOIN tbl_customer b ON b.c_id=a.p_user_id WHERE a.p_obj_id='.$obj_id);
        return $rs;
    }
    //点赞
    public function addPraise($user_id,$obj_id)
    {
        //判断当前对象是否已经点赞
        $rs=M($this->trueTableName)->select('p_id','p_user_id='.$user_id.' AND p_obj_id='.$obj_id,1);
        if($rs)
        {
            //清除点赞记录
            M($this->trueTableName)->delete('p_obj_id='.$obj_id.' AND p_user_id='.$user_id);
            return -1;  //已经点过赞了
        }
        $inData=array(
            'p_user_id'=>$user_id,
            'p_obj_id'=>$obj_id,
            'p_time'=>NOW
        );
        return M($this->trueTableName)->insert($inData);
    }
}
?>