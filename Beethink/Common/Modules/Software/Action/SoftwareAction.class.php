<?php
class SoftwareAction
{
    public function getVersion()
    {
        $platform =D('Platform.Platform')->getPlatform();
        $rs=D('Software')->getVersion($platform);
        returnJson(SUCCESS,$rs);
    }    
}
?>