<?php
class FilterModel
{
    /**
     * dataFilter()
     * 
     * @param mixed $data
     * @param mixed $ReqType
     * @return
     */
    public static function dataFilter(&$data,$ReqType)
    {
        if($ReqType=='get')
        {
            $data=D('Basefilter')->getFilter($data);
        }
        else if($ReqType=='post')
        {
            $data=D('Basefilter')->postFilter($data);
        }
        if(!$data)
        {
            $oR=D('Return');
            $oR::returnJson(FAIL,'unlawful request');
        }
    }
    public static function anyFilter(&$dataRefer)
    {
        $retData=array();
        foreach($dataRefer as $k=>$v)
        {
            if(isset($_POST[$k]))
            {
                $tmpData=array();
                $index=array_shift($v);
                
                $tmpData[]=array_merge(array($k),$v);
                
                self::dataFilter($tmpData,'post');
               
                $retData[$index]=$tmpData[$k];       
            }
        }
        return $retData;
    }
}
?>