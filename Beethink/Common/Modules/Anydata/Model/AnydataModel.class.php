<?php
class AnydataModel
{
    protected $trueTableName='tbl_anydata';
    
    public function update($upData,$where)
    {
        return M($this->trueTableName)->update($upData,$where);
    }
    public function insert($inData)
    {
        return M($this->trueTableName)->insert($inData);
    }
    public function delete($where)
    {
        return M($this->trueTableName)->delete($where);
    }
    //统一入口
    public function anydata($k,$v=null)
    {
        if($v==null)
        {
            $rs=M($this->trueTableName)->select('val','key=\''.$k.'\'',1);
            return $rs?$rs['val']:'';
        }
        else
        {
            //检验当前可以是否存在
            $id=$this->keyExists($k);
            if($id)
            {
               $upData=array(
                'val'=>$v
               ); 
               return M($this->trueTableName)->update($upData,'key=\''.$k.'\'');
            }
            else
            {
                $inData=array(
                    'key'=>$k,
                    'val'=>$v
                );
                return M($this->trueTableName)->insert($inData);    
            }
        }
    }
    //判断key是否存在
    public function keyExists($key)
    {
        return M($this->trueTableName)->select('id','key=\''.$key.'\'',1);
    }
}
?>