<?php
class ImgAction 
{
    public function save()
    {   
        $path=date('Y-m',NOW);
        
        $oImg=D('Image.Image');
        
        $iniData=array(
            'dirPath'=>COMMON_PATH.'Image/'.$path.'/'
        );
        
        $oImg->setParam($iniData);
        $im=$oImg->init($_FILES['fname']);
        //$im=$oImg->compress($im,400,0);        
        //$im=$oImg->rotate($im,30);
        //$im=$oImg->textWater($im,'this is a test');
        //$im=$oImg->imageWater($im,COMMON_PATH.'Image/test.jpg');
        $rs=$oImg->saveImage($im);
        
        $inData=array(
            'src'=>DOMAIN.'Common/Image/'.$path.'/'.$rs['name'],
            'w'=>$rs['w'],
            'h'=>$rs['h'],
            'time'=>NOW
        );
        $rs=D('Img')->insert($inData);
        
        
        unset($inData['time']);
        $inData['id']=$rs;
        
        returnJson(SUCCESS,$inData); 
    }
}
?>