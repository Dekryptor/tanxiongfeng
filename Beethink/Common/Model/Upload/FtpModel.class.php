<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/7/21 0021
 * Time: 18:42
 */
class FtpModel
{
    private $rootPath='',       //上传文件根目录
            $error='',           //错误信息
            $link='',             //FTP连接
            $config=array(	
                'host'=>'', 	    //服务器
                'port'=>21,     	//端口
                'timeout'=>90, 		//超时时间
                'username'=>'',  	//用户名
                'password'=>'',  	//密码,
                'savePath'=>'',		//文件存储路径
                'subName'=>'',		//目录名
                'fileName'=>'',		//文件存储全路径
                'repalce'=>'true',	//是否覆盖文件
    );
    public function __construct($config=array())
    {
    	$this->setParam($config);
    }
    /**
     * 入口
     */
    public function init($file,$config)
    {
        $this->setParam($config);
        
        if($this->login())
        {
            return $this->save($file);
        }   
        else
        {
        	return $this->error('login_fail',$config['username']);
        }
    }
    /**
     * 参数设置
     */
    public function setParam($config)
    {
        (!empty($config) && is_array($config))?$this->config=array_merge($this->config,$config):'';
    }
    /**
     * 参数处理
     */
    private function prepare()
    {
    	$this->config['rootPath']=$this->fillPath($this->config['rootPath']);
    	$this->config['subName']=$this->getSubName($this->config['subName']);
    	$this->config['savePath']=$this->fillPath($this->config['savePath']);
    	$this->config['saveName']=$this->getSaveName($this->config['saveName']);
    }
    /**
     * get sub name
     */
    private function getSubName($subName)
    {
    	return is_string($subName)?(empty($subName)?date('Ymd',NOW):$subName):call_user_func($subName);
    }
    /**
     * getSaveName
     */
    private function getSaveName($saveName)
    {
    	return is_string($subName)?(empty($subName)?NOW.mt_rand(100,1000):$subName):call_user_func($subName);
    }
    /**
     * get file ext
     */
    private function getExt($name)
    {
    	return substr($file,strrpos($name,'.')+1);
    }
    /**
     * 路径自动补全
     */
    private function fillPath($path)
    {
    	if(empty($path))
		{
    		return '';
    	}
    	
		if(substr($path,-1)!='/')
		{
			$path=$path.'/';
		}
		
		return $path;
    }
    /*
     * 登录到ftp服务器
     * */
    private function login()
    {
        $this->link=ftp_connect($this->config['host'],$this->config['port'],$this->config['timeout']);
        if($this->link)
        {
            if(ftp_login($this->link,$this->config['username'],$this->config['password']))
            {
                return true;
            }
            else
            {
                $this->error='无法登录到FTP服务器:username=>'.$this->config['username'];
            }
        }
        else
        {
            $this->error='无法连接到FTP服务器:'.$this->config['host'];
        }
        return false;
    }
    /*
     * 保存文件
     * */
    private function save($file)
    {
    	$file['ext']=$this->getExt($file['name']);
        $filename=$this->config['rootPath'].$this->config['savepath'].$this->config['subName'].'/'.$this->config['savename'].'.'.$file['ext'];

		//移动文件
		if(!$this->config['repalce'])
		{
			if(is_file($filename))
			{
				$this->error('file_already_exists',$filename);
			}
		}
		
        if(!ftp_put($this->link,$filename,$file['tmp_name'],FTP_BINARY))
        {
            return $this->error('file_save_fail',$filename);
        }
        
        $this->config['rootPath']=$this->fillPath($this->config['rootPath']);
    	$this->config['subName']=$this->getSubName($this->config['subName']);
    	$this->config['savePath']=$this->fillPath($this->config['savePath']);
    	$this->config['saveName']=$this->getSaveName($this->config['saveName']);
        $file['filePath']=$filename;
        $file['relative_path']='/'.$this->config['savePath'].$this->config['subName'].'/'.$this->config['savename'].'.'.$file['ext'];
        
        return $file;
    }
    /**
     * 错误处理
     */
	private function error($k,$msg=null)
	{
		$k=strtolower($k);
		$error_refer=array(
			'login_fail'=>array(1,'登录FTP失败'),
			'connect_ftp_server_fail'=>array(2,'连接FTP服务器失败'),
			'file_save_fail'=>array(3,'保存文件失败'),
			'file_already_exists'=>array(4,'文件已经存在'),
		);
		$code=0;
		$msg='';
		
		if(isset($error_refer[$k]))
		{
			$code=$error_refer[$k][0];
			$msg=$error_refer[$k][1].(is_null($msg)?'':$msg);
		}
		else
		{
			$code=999;
			$msg='发生未知错误';
		}
		
		return array('code'=>$code,'msg'=>$msg);
	}
}