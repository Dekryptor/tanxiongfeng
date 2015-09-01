<?php
class FileHandleModel
{
    /* 检查当前文件是否有效 */
    public static function checkCache($tmpFile)
    {
      if(!Conf::param('tpl_cache_on') || APP_DEBUG) return false;
      if(!is_file($tmpFile)) 
      {
          return false;
      }
      else if(filectime($tmpFile)>filemtime($tmpFile)) //如果文件被修改
      {
          return false;
      }
      else if(time()>filemtime($tmpFile)+Conf::param('tpl_cache_time'))  //检测文件是否在有效期内
      {
          return false;
      }
      return true;        //缓存有效
    }
}
?>