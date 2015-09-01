<?php
class AdModel
{
    protected $trueTableName='tbl_ad';
    
    public function getAdList($type=0)
    {
        $rs=M($this->trueTableName)->select('id,url,memo,type,img_id','type='.$type);
        return $rs;
    }   
}
?>