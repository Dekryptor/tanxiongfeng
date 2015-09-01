<?php
/**
 *  $oImg=D('Image.Image');
        
        $iniData=array(
            'dirPath'=>COMMON_PATH.'Image'
        );
        
        $oImg->setParam($iniData);
        $im=$oImg->init($_FILES['fname']);
        $im=$oImg->compress($im,400,0);        
        $im=$oImg->rotate($im,30);
        $im=$oImg->textWater($im,'this is a test');
        $im=$oImg->imageWater($im,COMMON_PATH.'Image/test.jpg');
        $rs=$oImg->saveImage($im);
 */
class ImageModel
{
    protected $dirPath='',  //文件存储目录
              $filename='',  //文件名
              $filetype='',  //文件类型
              $fData=null,   //文件数据
              $retData=array(),  //数据返回
              $maxFileSize=3000000, //最大文件大小 
              $availType=array('image/jpg','image/jpeg','image/gif','image/png');   //默认允许文件类型
    
    //参数设置
    public function setParam($data)
    {
        foreach($data as $k=>$v)
        {
            if(isset($this->$k))
            {
                $this->$k=$v;
            }
        }
        $this->dirPath=$this->checkDir($this->dirPath);
    }
    //路径自动补全
    public function checkDir($path)
    {   
        return substr($path,-1)=='/'?$path:$path.'/';
    }
    
    //入口
    public function init($fData)
    {
        $this->imageFilter($fData);
        //目录自动生成
        $this->builtDir();
        $this->fData=$fData;
        $this->filetype=substr($fData['name'],strrpos($fData['name'],'.')+1);
        //生成文件名
        $this->filename=time().mt_rand(0,100).mt_rand(0,100).'.'.$this->filetype;
       
        $this->retData['size']=$this->fData['size'];
        $this->retData['name']=$this->filename;    
        list($this->retData['w'],$this->retData['h'])=getimagesize($this->fData['tmp_name']);
        return $this->getImageObj($fData['tmp_name'],$this->filetype);
    }
    //目录创建
    private function builtDir()
    {
       return D('Dir')->mk_dir($this->dirPath);
    }
    //获取图片对象
    public function getImageObj($filePath,$filetype='')
    {   
        ($filetype)||($filetype=substr($filePath,strrpos($filePath,'.')+1));
        switch($filetype)
        {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filePath);
            case 'png':
                return imagecreatefrompng($filePath);
            case 'gif':
                return imagecreatefromgif($filePath);
            default:
                return;
        }
    }
    //原图保存
    public function saveSrcImage(&$im)
    {
        //$im=imagecreatefromjpeg($this->fData['tmp_name']);
        return $this->saveImage($im);
    }
    //图片保存
    public function saveImage(&$im)
    {
        $fpath=$this->dirPath.''.$this->filename;
        
        switch($this->filetype)
        {
            case 'jpeg':
            case 'jpg':
                imagejpeg($im,$fpath);
                break;
            case 'png':
                imagepng($im,$fpath);
                break;
            case 'gif':
                imagegif($im,$fpath);
                break;
            default:;
        }
        $this->freeImage($im);
        return $this->retData;
    }
    //销毁图片
    private function freeImage(&$im)
    {
       return imagedestroy($im);
    }
    //文件基本数据验证
    private function imageFilter($fData)
    {
        if($fData['error']>0)
        {
            return 'file error:'.$fData['error'];
        }
        //判断文件类型
        if(!in_array($fData['type'],$this->availType))
        {
            return 'file type error:'.$fData['type'];
        }
        if($fData['size']>$this->maxFileSize)
        {
            return 'filesize error:'.$fData['size'];
        }
        return true;
    }
    //图片压缩
    public function compress($im,$w=0,$h=0)
    {
        $size=$this->adjustSize($w,$h,$this->retData['w'],$this->retData['h']);
        $newImg=imagecreatetruecolor($size[0],$size[1]);
        imagecopyresized($newImg,$im,0,0,0,0,$size[0],$size[1],$this->retData['w'],$this->retData['h']);
        //update image size
        list($this->retData['w'],$this->retData['h'])=$size;
        return $newImg;
    }
    //旋转
    public function rotate(&$im,$angle,$color=0)
    {
        return imagerotate($im,$angle,$color);
    }
    //文本水印
    public function textWater(&$im,$txt,$x=null,$y=null,$color=0)
    {
        if($x==null && $y==null)
        {
            $distance=strlen($txt)*12;
            
            $x=($this->retData['w']-$distance);
            $y=$this->retData['h']-25;    
        }
        ($color)||($color=imagecolorallocate($im,255,255,255));
        imagestring($im,40,$x,$y,$txt,$color);    
        return $im;
    }
    //图片水印
    public function imageWater(&$im,$srcImgPath,$x=null,$y=null)
    {
        $srcImg=$this->getImageObj($srcImgPath);
        $size=getimagesize($srcImgPath);
        
        if($x==null && $y==null)
        {
            $x1=($this->retData['w']-$size[0])/2;
            $y1=($this->retData['h']-$size[1])/2;    
        }
        else
        {
            $x1=$x;
            $y1=$y;
        }
        imagecopy($im,$srcImg,$x1,$y1,0,0,$this->retData['w'],$this->retData['h']);
        return $im;
    }
    /**
   * 图片自适应
   * 
   * ImageModel::adjustSize()
   * 
   * @param mixed $w   自定义高宽
   * @param mixed $h
   * @param mixed $sw    图片原始尺寸
   * @param mixed $sh
   * @return
   */
  private function adjustSize($w,$h,$sw,$sh)
  {
    if($w!=0)
    {
      $tw=$w;
      if($h==0)
      {
        $th=ceil($sh*$w/$sw);
      } 
      else
      {
        $th=$h;
      }
      
    }else
    {
      if($h==0)
      {
        $th=$sh;
        $tw=$sw;  
      }
      else
      {
        $th=$h;
        $tw=ceil($sw*$h/$sh);
      }
      
    }
    return array($tw,$th);
  }
}
?>