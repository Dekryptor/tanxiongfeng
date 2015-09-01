<?php
class VerifyCode
{
    static function getAuthImage($text,$w=200,$h=40)
    {
        $im_x=$w;
        $im_y=$h;
        $im=imagecreatetruecolor($im_x,$im_y);
        $text_c=imagecolorallocate($im,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
        $button_c=imagecolorallocate($im,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
        imagefill($im,16,13,$button_c);
        $font=realpath(THINK_PATH.'Common/Class/VerifyCode/').'\\t1.ttf';
        $len=strlen($text);
        $mt_array=array(-1,1);
        $size=28;
        for($i=0;$i<$len;$i++)
        {
            $tmp=substr($text,$i,1);
            $p=array_rand($mt_array);
            $an=$mt_array[$p]*mt_rand(1,10);   //角度
            imagettftext($im,$size,$an,15+$i*$size,35,$text_c,$font,$tmp);
        }

        $distortion_im=imagecreatetruecolor($im_x,$im_y);
        imagefill($distortion_im,16,13,$button_c);
        for ( $i=0; $i<$im_x; $i++) {
            for ( $j=0; $j<$im_y; $j++) {
                $rgb = imagecolorat($im, $i , $j);
                if( (int)($i+20+sin($j/$im_y*2*M_PI)*10) <= imagesx($distortion_im)&& (int)($i+20+sin($j/$im_y*2*M_PI)*10) >=0 ) {
                    imagesetpixel ($distortion_im, (int)($i+10+sin($j/$im_y*2*M_PI-M_PI*0.1)*4) , $j , $rgb);
                }
            }
        }
        //加入干扰象素;
        $count = 160;//干扰像素的数量
        for($i=0; $i<$count; $i++){
            $randcolor = ImageColorallocate($distortion_im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagesetpixel($distortion_im, mt_rand()%$im_x , mt_rand()%$im_y , $randcolor);
        }

        $rand = mt_rand(5,30);
        $rand1 = mt_rand(15,25);
        $rand2 = mt_rand(5,10);
        for ($yy=$rand; $yy<=+$rand+2; $yy++){
            for ($px=-80;$px<=80;$px=$px+0.1)
            {
                $x=$px/$rand1;
                if ($x!=0)
                {
                    $y=sin($x);
                }
                $py=$y*$rand2;

                imagesetpixel($distortion_im, $px+80, $py+$yy, $text_c);
            }
        }
        //设置文件头;
        Header("Content-type: image/JPEG");

        //以PNG格式将图像输出到浏览器或文件;
        ImagePNG($distortion_im);

        //销毁一图像,释放与image关联的内存;
        ImageDestroy($distortion_im);
        ImageDestroy($im);
    }
    static function make_rand($length='32')
    {
        $str="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result="";
        for($i=0;$i<$length;$i++){
            $num[$i]=rand(0,25);
            $result.=$str[$num[$i]];
        }
        return $result;
    }
    public static function getCode($len=4,$w=200,$h=40)
    {
        $code=self::make_rand($len);
        $_SESSION['verify_code']=$code;
        self::getAuthImage($code,$w,$h);
    }
}