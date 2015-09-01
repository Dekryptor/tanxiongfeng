<?php
/**
 * form类
 *  实现便捷创建form元素标签
 *
 */
class FormModel
{
  protected $inputFormat='<input type="%s" id="%s" name="%s" value="%s"%s />',
            $resetFormat='<input type="reset" id="reset" class="reset"  value="%s" />',
            $checkboxFormat='<input type="%s" name="%s" id="%s" class="%s" value="%s"%s  /><span class="r-memo">%s</span>',
            $optionFormat='<option value="%s"%s>%s</option>';
  function text($name,$val,$attr=array())
  {
    if(!isset($attr['class'])) $attr['class']='text';
    return $this->getInputString('text',$name,$val,$attr);
  }
  function button($name,$val,$attr=array())
  {
    if(!isset($attr['class'])) $attr['class']='btn';
    return $this->getInputString('button',$name,$val,$attr);
  }
  function file($name,$attr=array())
  {
    if(!isset($attr['class'])) $attr['class']='file';
    return $this->getInputString('file',$name,' ',$attr);
  }
  function hidden($name,$val,$attr=array())
  {
    return $this->getInputString('hidden',$name,$val,$attr);
  }
  function submit($name,$val,$attr=array())
  {
    if(!isset($attr['class'])) $attr['class']='sub'; 
    return $this->getInputString('submit',$name,$val,$attr);
  }
  function reset($val,$attr=array())
  {
    return sprintf($this->resetFormat,$val);
  }
  //多个checkbox
  function checkbox($name,$sel,$checkData=array())
  {
    if(substr($name,-2,2)!=='[]') $name.='[]';
    return $this->getCheckData('checkbox',$name,$sel,$checkData);
  }
  //单个checkbox
  function singleCheckbox($name,$checked,$checkData=array())
  {
    $sel=$checked?key(current($checkData)):'';
    return $this->getCheckData('checkbox',$name,$sel,$checkData);
  }
  //多个radio
  function radio($name,$sel,$checkData=array())
  {
    if(substr($name,-2,2)!=='[]') $name.='[]';
    return $this->getCheckData('radio',$name,$sel,$checkData);
  }
  //select
  function select($name,$sel,$selData=array(),$ext='')
  {
    //$optionFormat='<option value="%s"%s>%s</option>';
    $str='<select name="'.$name.'" id="'.$name.'" '.$ext.'>';
    $checked='';
    foreach($selData as $k=>$v)
    {
      $checked=($k==$sel)?'selected':'';
      $str.=sprintf($this->optionFormat,$k,$checked,$v);
    }
    return $str.'</select>';
  }
  //textarea
  function textarea($name,$val,$attr=array())
  {
    return '<textarea class="textarea" id="'.$name.'" name="'.$name.'"'.$this->getAttr($attr).'>'.$val.'</textarea>';
  }
  //统一一般input格式
  private function getInputString($type,$name,$val,$attr=array())
  {//$inputFormat='<input type="%s" id="%s" name="%s" value="%s"%s />'
    return sprintf($this->inputFormat,$type,$name,$name,$val,$this->getAttr($attr));
  }
  //简单拼接下拉列表
  public function getSlidedown($html='',$title='编辑')
  {
    return ' <div class="slideDown" style="z-index: 0;">
        <a class="slideHandle" href="javascript:;">'.$title.'</a>
        <ul style="display: none;" class="slideList">'.$html.'</ul></div>';
  }
  //获取全选按钮
  public function getCheckAll($memo)
  {
    return '<input type="checkbox" id="checkAll" class="checkbox" /><span class="r-memo">'.$memo.'</span>';
  }
  //获取username
  public function username($name,$val,$attr=array())
  {
    if(!isset($attr['maxlength'])) $attr['maxlength']=30;
    return $this->getInputString('text',$name,$val,$attr);
  }
  //获取password
  public function password($name,$val,$attr=array())
  {
    if(!isset($attr['maxlength'])){
      $attr['maxlength']=32;
    } 
    if(!isset($attr['class'])){
      $attr['class']='psd';
    } 
    return $this->getInputString('password',$name,$val,$attr);
  }
  //date
  public function date($name,$val,$attr=array())
  {
    
  }
  //处理定义属性
  private function getAttr($attr=array())
  {
    $str='';
    foreach($attr as $k=>$v)
    {
      $str.=' '.$k.'="'.$v.'"';
    }
    return $str;
  }
  //生成复选框内容
  private function getCheckString($type,$name,$id,$value,$checked,$memo)
  {
    //$checkboxFormat='<input type="%s" name="%s" id="%s" value="%s"%s  />%s',
    return sprintf($this->checkboxFormat,$type,$name,$id,$type,$value,$checked,$memo);
  }
  //获取checkbox
  private function getCheckData($type,$name,$sel,$checkData=array())
  {
    $str='';$checked='';
    $cArr=is_array($sel)?$sel:array($sel);
    $i=0;
    foreach($checkData as $k=>$v)
    {
      $checked=in_array($k,$cArr)?'checked':'';
      $str.=$this->getCheckString($type,$name,$name.'_'.$i++,$k,$checked,$v);
    }
    return $str;
  }
}
?>