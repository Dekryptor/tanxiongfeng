<?php
class AdAction
{
    public function getAdList()
    {
        $rs=D('Ad')->getAdList();
        
        returnJson(SUCCESS,$rs);
    }   
}
?>