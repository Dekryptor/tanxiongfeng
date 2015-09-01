<?php
class UserReportAction
{
    public function report()
    {
        $data=array();
        
        if(isset($_POST['content']) && $_POST['content'])
        {
            $data['content']=addslashes($_POST['content']);
        }
        if(isset($_POST['img_ids']) && $_POST['img_ids'])
        {
            $imgArr=explode(',',$_POST['img_ids']);
            $len=count($imgArr);
            ($len>2)&&($len=2);
            
            for($i=1;$i<=$len;$i++)
            {
                $data['img_id'.$i]=$imgArr[$i-1];
            }
            
        }
        
        if($data)
        {
            if(isset($_POST['qqmail']) && $_POST['qqmail'])
            {
                $data['qqmail']=$_POST['qqmail'];
            }
            
            $oIp=D('ip');
            $ip=$oIp->get_client_ip();
            $iIp=$oIp->getInt($ip);
          
            $data['ip']=$iIp;
            $data['time']=NOW;
            
            D('UserReport')->insert($data);
        }
        
        returnJson(SUCCESS,'','操作成功!');
    }
}
?>