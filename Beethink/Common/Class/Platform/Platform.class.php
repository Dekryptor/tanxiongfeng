<?php
class Platform
{
    static function getPlatform()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod"))
        {
           return 1;
        }
        else if(strpos($userAgent,"Android"))
        {
           return 2;
        } 
        else
        {
            return 0;
        }
    }   
}
?>