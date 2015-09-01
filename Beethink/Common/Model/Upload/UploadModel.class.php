<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/7/21 0021
 * Time: 14:28
 */
class UploadModel
{
    private $config=array(
            'mines'=>array(),   //allow file mine type
            'maxSize'=>0,       //limit the file maxsize that allowed
            'exts'=>'',         //允许上传文件的后缀
            'subName'=>'',      //子目录创建方式创建方式
            'rootPath'=>'',     //保存的根目录
            'saveName'=>'',     //保存文件的名字
            'replace'=>false,   //是否覆盖文件
            'hash'=>true,       //是否生成hash编码
            'driver'=>'',       //文件上传驱动
            'driverConfig'=>array() //驱动的配置信息
        ),
        $error='',          //上传的错误信息
        $uploader=null;     //上传驱动的实例
    
    public function save($file,$config=array())
    {
        $this->config=array_merge($this->config,$config);
        
        $this->check($file);
        
        if($this->error)
        {
            return $this->error;
        }
        $this->prepare($file,$config);
    
        $this->setDriver($driver,$this->config['driverConfig']);
        if(!$this->error)
        {
            $success_data = $this->uploader->save($file,$this->config['replace']);
            
            if(!$this->error)
            {
                return $success_data;    
            }    
        }
        
        return $this->error;
    }
    /**
     * auto fill path
     */
    private function fillPath($path)
    {
        (substr($path,-1)!='/')&&($path.='/');
        return $path;
    }
    /**
     * 
     * prepare data
     * 
     */
    private function prepare(&$file,&$config)
    {
        $file['ext']=$this->getExt($file['name']);
        $config['rootPath']=$this->fillPath($config['rootPath']);
        $config['savePath']=$this->fillPath($config['savePath']).$this->getSubname($config['subName']).'/';
        $config['saveName']=$this->getSaveName($config['saveName']).'.'.$file['ext'];
        
        if(Sys::D('Dir.Dir')->mk_dir($config['rootPath'].$config['savePath']))
        {
            return true;
        }
        
        $this->error(11,$config['rootPath'].$config['savePath']);
    } 
    /**
     *  实例化驱动
     */
    private function setDriver($driver,$driver_config)
    {
        $this->uploader=Sys::D($driver);
        if($this->uploader)
        {
            return $this->uploader->init($driver_config);
        }
        else
        {
            $this->error(10);
        }
    }
    /**
   * 获取文件的拓展名
   */ 
     public function getExt($filename)
     {
        return substr($filename,strrpos($filename,'.')+1);
     }
    /**
     * 获取subname
     */
     public function getSubname($subName)
     {
        return is_string($subName)?(empty($subName)?date('Ymd',NOW):$subName):call_user_func($subName);
     }
     /**
      * get the unique filename
      */
     private function getSaveName($saveName)
     {
        return is_string($saveName)?( empty($saveName)?time().mt_rand(100,1000):$saveName ):call_user_func($saveName);
     }
    /**
     * 获取唯一的文件名
     */
    private function getUniqueName()
    {
        return time().mt_rand(100,1000);
    }
    private function check($file)
    {
        if($file['error'])
        {
            $this->error($file['error']);
            return false;
        }
        if(empty($file['name']))
        {
            $this->error(4);
        }
        /*
         *  检查是否是合法上传
         * */
        if(!is_uploaded_file($file['tmp_name']))
        {
            $this->error(8);
        }

        if(!$this->checkSize($file['size']))
        {
            $this->error(1);
        }
        if(!$this->checkMime($file['type']))
        {
            $this->error(9);
        }

        return true;
    }


    /*
     * 文件大小检测
     * */
    private function checkSize($file_size)
    {
        return  (0!=$file_size)&&($file_size<$this->maxSize);
    }
    /*
     * 检查文件MIME类型是否合法
     * */
    private function checkMime($mime)
    {
        return in_array($mime,$this->mines);
    }
    /*
     * 检查文件后缀名是否合法
     * */
    private function checkExt($ext)
    {
        return in_array(strtolower($ext),$this->ext);
    }

    /*
     * 错误处理
     * */
    private function error($error_no,$msg='')
    {
        switch($error_no)
        {
            case 1:
                $this->error='文件超过 upload_max_filesize 限制';
                break;
            case 2:
                $this->error='文件超过 MAX_FILE_SIZE 限制';
                break;
            case 3:
                $this->error='文件只有部分被上传';
                break;
            case 4:
                $this->error='没有文件被上传';
                break;
            case 6:
                $this->error='找不到临时文件夹';
                break;
            case 7:
                $this->error='文件写入失败';
                break;
            case 9:
                $this->error='文件MIME类型不被允许';
                break;
            case 10:
                $this->error='实例化驱动过程中,发生错误:'.print_r($this->config['driverConfig']);
                break;
            case 11:
                $this->error='目录创建失败';
                break;
            default:
                $this->error='未知上传错误';
        }
    }
}