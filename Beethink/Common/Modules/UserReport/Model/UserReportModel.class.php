<?php
class UserReportModel
{
    protected $trueTableName='tbl_user_report';
    
    public function insert($inData)
    {
        return M($this->trueTableName)->insert($inData);
    }
    
}

?>