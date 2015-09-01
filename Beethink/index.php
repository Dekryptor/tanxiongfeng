<?php
function init()
{
    $data=array(
        APP_PATH.'Runtime/Define/define.php',
        APP_PATH.'Runtime/Conf/conf.php',
        APP_PATH.'Runtime/Class/class.php',
        APP_PATH.'Runtime/Custom/custom.php'
    );
    foreach($data as $v)
    {
        require $v;
    }
}
init();
App::_init();
?>