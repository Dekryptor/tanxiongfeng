<?php
/**
 * 功能：图片表操作
 * 
 */
class ImgModel
{
    protected $trueTableName='tbl_image';
    /**
     * 添加操作
     */
    public function insert($data)
    {
        return M($this->trueTableName)->insert($data);
    }
    /**
     * 更新操作
     */
    public function update($data,$where)
    {
        return M($this->trueTableName)->update($data,$where);
    }
    /**
     * 删除操作
     * 
     */
    public function delete($where)
    {
        return M($this->trueTableName)->delete($where);
    }
    /**
     * 根据ids获取图片信息
     */
    public function getImageByIds($ids)
    {
        $rs=M($this->trueTableName)->select('id,src,w,h','FIND_IN_SET(id,\''.$ids.'\')');
        return $rs;
    }
    /**
     * 根据id获取图片信息
     */
    public function getImageById($id)
    {
        $retData=array();
        $rs=M($this->trueTableName)->select('id,src,w,h','id='.$id,1);
        return $rs?$rs['src']:'::';
    }
}
?>