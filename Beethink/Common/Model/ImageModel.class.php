<?php
header('content-type:text/html;charset=utf-8');
/*
功能:
  1.文件获取
  2.文件类型检测
  3.文件大小检测
  4.文件合法性检测
  5.文件缩放
  6.水印处理
  7.文件存储
  8.采用简单策略模式进行针对性处理
  9.支持原图保存
 10.目录大小限制

param:
  array(
    'markTitle'=>'bee',
    'fpath'=>'',
    'srcSourcePath'=>'',
    'angle'=>0,
    'tw'=>40,
    'th'=>40,
    'maxFileSize'=2000000,
    'diskVolume'=125000000,      
    'avaiFileType'=>array('image/jpeg','image/jpg','image/png','image/gif')
  );
  
注:
1.$w,$h
  1).文件宽,高宽任意一个为零 将按照等比例缩放的方式处理,
  2).同时设置了高和宽非零则按自定义大小处理
  3).如果高宽都设置为零 则不处理
数据参考:

101:文件大小超出允许范围
200:文件处理成功
301:磁盘空间不足
401:文件类型不合法
501:文件保存失败,目录不存在或无权限访问
  
*/
class ImageModel
{
  private $markTitle='bee',        
          $fpath='',                //新图保存位置
          $srcSourcePath='',        //原图保存位置
          $angle=0,                 //旋转角度
          $tw=40,        
          $th=40,
          $sw=0,
          $sh=0,
          $filename='',             //保存的文件名 默认是时间戳加随机数
          $dirname='',              //父级目录路径
          $diskVolume=0,            //设置目录最大容量,零为不限制   非零为限制大小
          $maxFileSize=2000000,      //最大上传文件大小
          $avaiFileType=array('jpeg','jpg','png','gif'),    //合法文件类型
          $data=array();            //array('name'=>array(),'type'=>array(),'fpath'=>array())
   
  //初始化参数设置
  /**
   * ImageModel::setParam()
   * 
   * @param mixed $data
   * @return
   */
  public function setParam($data)
  {
    foreach($data as $k=>$v)
    {
      if(isset($this->$k)) $this->$k=$v;
    }
    $this->dirname=$this->fillPath($this->dirname);
    
    if($this->fpath){
      $this->fpath=$this->fillPath($this->dirname.$this->fpath);
    }
    if($this->srcSourcePath){
      $this->srcSourcePath=$this->fillPath($this->dirname.$this->srcSourcePath);
    }
  }
  //路径自动补全
  /**
   * ImageModel::fillPath()
   * 
   * @param mixed $dir
   * @return
   */
  private function fillPath($dir)
  {
    return substr($dir,-1)=='/'?$dir:$dir.'/';
  }        
  //入口
  /**
   * ImageModel::init()
   * 
   * @param mixed $data
   * @return
   */
  public function init($data)
  {
    //磁盘剩余空间检查
    if(!$this->checkDiskVolumn($this->dirname)) return $this->error(301,'指定位置存储空间不足');
    if($data['size']<$this->maxFileSize)
    {
      $ftype=substr($data['name'],strrpos($data['name'],'.')+1);
    
      if(in_array($ftype,$this->avaiFileType))
      {         
        if(!$this->filename)
        {
          $this->filename=time().mt_rand(0,100).'.'.$ftype;
        } 
        $im=$this->imageCreate($ftype,$data['tmp_name']);
         
        if($this->srcSourcePath && $this->fpath)
        {
          
          if(!$this->strategy($data['tmp_name'],$this->srcSourcePath,$ftype,$this->sw,$this->sh))
          {
            return $this->error(501,'文件保存失败,目录不存在或无权限访问');
          }
          
          if(!$this->strategy($data['tmp_name'],$this->fpath,$ftype,$this->tw,$this->th))
          {
            return $this->error(501,'文件保存失败,目录不存在或无权限访问');
          }
        }
        else if($this->fpath)
        {
          if(!$this->strategy($data['tmp_name'],$this->fpath,$ftype))
          {
            return $this->error(501,'文件保存失败,目录不存在或无权限访问');
          }
        }
        else
        {
          if(!$this->strategy($data['tmp_name'],$this->srcSourcePath,$ftype,$this->sw,$this->sh))
          {
            return $this->error(501,'文件保存失败,目录不存在或无权限访问');
          }
        }
        return $this->error(200,$this->data);
      }
      else
      {
        return $this->error(401,'文件类型:.'.$ftype.' 不被允许');
      }
    }
    else
    {
      return $this->error(101,$data['name'].'文件大小超出允许范围');
    }
  }
  //目录大小检测
  /**
   * ImageModel::checkDiskVolumn()
   * 
   * @param mixed $dirname
   * @return
   */
  private function checkDiskVolumn($dirname)
  {
    //获取目录大小
    if($this->diskVolume==0){
      return true;
    } 
    else 
    {
      return true;  
    }
  }
  //jpg/jpeg策略
  /**
   * ImageModel::strategy()
   * 
   * @param mixed $srcPath
   * @param mixed $tarDir
   * @param mixed $type
   * @param integer $tw
   * @param integer $th
   * @return
   */
  private function strategy($srcPath,$tarDir,$type,$tw=0,$th=0)
  {
    $type=strtolower($type);
    $im=$this->imageCreate($type,$srcPath);
    //尺寸
    list($w,$h)=getimagesize($srcPath);
    list($tw,$th)= $this->adjustSize($tw,$th,$w,$h);
    $newImg=imagecreatetruecolor($tw,$th);
   
    imagecopyresized($newImg,$im,0,0,0,0,$tw,$th,$w,$h);
    //水印           
    if($this->markTitle)
    {
      $x=$tw-strlen($this->markTitle)*12;
      $y=$th-20;
      $this->watermark($newImg,$this->markTitle,$x,$y);
    }
    //旋转?
    if($this->angle>0)
    {
      $this->imgRotate($newImg,$this->angle);
    }
    //保存图片
    return $this->saveFile($newImg,$type,$tarDir);
  }
  //imagecreate
  /**
   * ImageModel::imageCreate()
   * 
   * @param mixed $type
   * @param mixed $path
   * @return
   */
  private function imageCreate($type,$path)
  {
    switch($type)
    {
    case 'jpg':
    case 'jpeg':
      return imagecreatefromjpeg($path);
    case 'png':
      return imagecreatefrompng($path);
    case 'gif':
      return imagecreatefromgif($path);
    default:die('暂不支持该类型');
    }
    return false;
  }
  //destroy image
  /**
   * ImageModel::imageSave()
   * 
   * @param mixed $im
   * @param mixed $type
   * @param mixed $path
   * @return
   */
  private function imageSave(&$im,$type,$path)
  {
    switch($type)
    {
    case  'jpg':
    case 'jpeg':
      return imagejpeg($im,$path);
    case 'png':
      return imagepng($im,$path);
    case 'gif':
      return imagegif($im,$path);
    default:;
    }
    return false;
  }
  //水印
  /**
   * ImageModel::watermark()
   * 
   * @param mixed $im
   * @param mixed $markTitle
   * @param mixed $x
   * @param mixed $y
   * @return
   */
  private function watermark(&$im,$markTitle,$x,$y)
  {
    $color=imagecolorallocate($im,255,255,255);
    imagestring($im,40,$x,$y,$markTitle,$color);
  }
  //旋转处理
  /**
   * ImageModel::imgRotate()
   * 
   * @param mixed $im
   * @param mixed $rotate
   * @return
   */
  private function imgRotate(&$im,$rotate)
  {
    $im=imagerotate($im,$rotate,0);
  }
  //保存
  /**
   * ImageModel::saveFile()
   * 
   * @param mixed $im
   * @param mixed $type
   * @param mixed $path
   * @return
   */
  private function saveFile(&$im,$type,$path)
  {
    if(!is_dir($path)) return false;
    $path=$this->fillPath($path);
    $fpath=$path.''.$this->filename;
    $this->imageSave($im,$type,$fpath);
    $this->data['name'][]=$this->filename;
    $this->data['fpath'][]=$fpath;
    //释放内存
    imagedestroy($im);
    return true;
  }
  //获取文件适配大小
  /**
   * ImageModel::adjustSize()
   * 
   * @param mixed $w
   * @param mixed $h
   * @param mixed $sw
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
  //记录处理
  /**
   * ImageModel::error()
   * 
   * @param mixed $type
   * @param mixed $con
   * @return
   */
  private function error($type,$con)
  {
    return array($type=>$con);    
  }
}
/*
$iniData=array(
  'markTitle'=>'',
  'dirname'=>APP_PATH,
  'fpath'=>'Common/Img/file/thumbnail',
  'angle'=>0,
  'tw'=>0,
  'th'=>160,
  'srcSourcePath'=>'Common/Img/file/srcImg',
  'sw'=>0,
  'sh'=>400,
    );
if(isset($_FILES['fname']) && $_FILES['fname'])
{
  $oIm=new ImageModel();
  $oIm->setParam($iniData);
  $rs=$oIm->init($_FILES['fname']);
  $k=key($rs);
  if($k==200)
  {
    echo '{"r":200,"m":"'.implode(',',$rs[200]['fpath']).'"}';
  }
  else
  {
    echo '{"r":'.$k.',"m":"'.$rs[$k].'"}';
  }
}
*/
?>