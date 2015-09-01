<?php
class SoftwareModel
{
    protected $trueTableName='tbl_software';
    
    public function getVersion($platform)
    {
        $rs=M($this->trueTableName)->select('version,memo,time','platform='.$platform.' ORDER BY id DESC',1);
        
        return $rs?$rs:'::';
    }
}
?>