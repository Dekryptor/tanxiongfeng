<?php
class Exception
{
    public function index()
    {
        $msg=isset($_GET['msg'])?$_GET['msg']:'';
        $t=(isset($_GET['t']) && (int)$_GET['t']>0)?(int)$_GET['t']:8;
        
        $this->assign('msg',$msg);
        $this->assign('dely',$t);
        $this->assign('styles',array('common','core.common'));
        $this->display();
    }
}
?>