<?php
/**
 * 文件消息
 * 20图片，21语音，22.其他文件
 */

class FileMsgAction //extends Codecheck
{
    protected $msg_type=0,
            $user_id=0,
            $perOrGroup=0,
            $group_id=0;
    
    public function sendMsg()
    {
        $data=array(
            array('msg_type','int'),
            array('target_id','int'),
            array('perOrGroup','int')
        );
        dataFilter($data,'post');
        
        $provArr=array(
            '20'=>'imgStrategy',
            '21'=>'voiceStrategy',
            '22'=>'otherStrategy'
        );
        $this->msg_type=$data['msg_type'];
      
        $data['perOrGroup']==1?$this->user_id=$data['target_id']:$this->group_id=$data['target_id'];
        $this->perOrGroup=$data['perOrGroup'];
        
        if(isset($_FILES['fname']) && $_FILES['fname'])
        {
            $this->$provArr[$data['msg_type']]();
        }
        else
        {
            returnJson(FAIL,'unlawfule request');
        }
    }
    /**
     * 语音文件
     */
    private function voiceStrategy()
    {
        $path=date('Y-m',NOW);
       
        $basePath='Common/UpVoice/'.$path.'/';
        
        if(!isset($_POST['time']))
        {
            returnJson(FAIL,'unlawful request');
        }
        if(!is_dir(APP_PATH.$basePath))
        {
            mkdir(APP_PATH.$basePath);
        }
        
        $fname=time().mt_rand(0,10).mt_rand(0,10);
        $ftype=substr($_FILES['fname']['name'],strrpos($_FILES['fname']['name'],'.')+1);
        $fname.='.'.$ftype;
        $inData=array(
            'src'=>'Common/UpVoice/'.$path.'/'.$fname,
            'time'=>(int)$_POST['time']
        );
        move_uploaded_file($_FILES['fname']['tmp_name'],$basePath.$fname);
        $this->insertMsg($inData);
    }
    /**
     * 图片文件
     */
    private function imgStrategy()
    {
        $path=date('Y-m',NOW);
        $iniData=array(
          'markTitle'=>'',
          'dirname'=>APP_PATH,
          'fpath'=>'',
          'angle'=>0,
          'tw'=>0,
          'th'=>0,
          'srcSourcePath'=>'Common/Image/UpImg/'.$path.'/',
          'sw'=>0,
          'sh'=>0
        );
        
        if(!is_dir(APP_PATH.$iniData['srcSourcePath']))
        {
            mkdir(APP_PATH.$iniData['srcSourcePath']);
        }
        
      $oIm=D('Image');
      $oIm->setParam($iniData);
      $rs=$oIm->init($_FILES['fname']);
      
      list($w,$h)=getimagesize($_FILES['fname']['tmp_name']);
      
      if(isset($rs['200']))
      {
        //消息插入
        $inData=array(
            'w'=>$w,
            'h'=>$h,
            'src'=>'Common/Image/UpImg/'.$path.'/'.$rs['200']['name'][0]
        );
        
        $this->insertMsg($inData);
      } 
      else
      {
        returnJson(FAIL,'文件保存失败!');
      }
    }
    /**
     * 其它文件
     */
    private function otherStrategy()
    {
        $path=date('Y-m',NOW);
       
        $basePath='Common/Other/'.$path.'/';
        
        if(!is_dir(APP_PATH.$basePath))
        {
            mkdir(APP_PATH.$basePath);
        }
        
        $fname=time().mt_rand(0,10).mt_rand(0,10);
        $ftype=substr($_FILES['fname']['name'],strrpos($_FILES['fname']['name'],'.')+1);
        $fname.='.'.$ftype;
        $inData=array(
            'src'=>$basePath.$fname,
            'name'=>urlencode($_FILES['fname']['name'])
        );
        move_uploaded_file($_FILES['fname']['tmp_name'],$basePath.$fname);
        $this->insertMsg($inData);
   }
    /**
     * 插入消息
     */
    private function insertMsg($data)
    {
        $userData=array(
            'um_cust_id'=>1,//$_SESSION['userinfo']['cc_cust_id'],
            'um_receive_userid'=>$this->user_id,
            'um_perOrGroup'=>$this->perOrGroup,
            'um_group_id'=>$this->group_id
        );
        $conData=array(
            'cm_time'=>NOW,
            'cm_content'=>json_encode($data),
            'cm_type'=>$this->msg_type
        );
    
        $msg_id=D('Chatmsg')->sendMsg($userData,$conData);
        
        $msg_refer=array(
            '20'=>'img_msg',
            '21'=>'voice_msg',
            '22'=>'otherfile_msg'
        );
        
        $retData=array(
            'msg_id'=>$msg_id,
            $msg_refer[$this->msg_type]=>$data,
            'msg_type'=>$conData['cm_type'],
            'msg_time'=>$conData['cm_time'],
            'perOrGroup'=>$userData['um_perOrGroup'],
            'receive_userid'=>$this->user_id,
            'group_id'=>$this->group_id
        );
        
        returnJson(SUCCESS,$retData);
    }
}
?>