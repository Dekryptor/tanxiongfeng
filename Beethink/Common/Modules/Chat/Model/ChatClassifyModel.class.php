<?php
class ChatClassifyModel
{
    protected $trueTableName='tbl_chat_classify';
    
    public function getClassifyNameById($cc_id)
    {
        return M($this->trueTableName)->select('cc_id,cc_name','cc_id='.$cc_id,1);
    }
    public function getClassifyNameByIds($cc_ids)
    {
        return M($this->trueTableName)->select('cc_id,cc_name','FIND_IN_SET(cc_id,\''.$cc_ids.'\')');
    }
}
?>