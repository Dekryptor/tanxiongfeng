<?php
/**
 * form��
 *  ʵ�ֱ�ݴ���formԪ�ر�ǩ
 *
 */
class Form
{
    protected static  $inputFormat='<input type="%s" id="%s" name="%s" value="%s"%s placeholder="%s" />',
        $textareaFormat='<textarea id="%s" name="%s" placeholder="%s"%s>%s</textarea>',
        $resetFormat='<input type="reset" id="reset" class="reset"  value="%s" />',
        $scriptsFormat='<script type="text/javascript">%s</script>',
        $checkboxFormat='<input type="%s" name="%s" id="%s" class="%s" value="%s"%s  /><span class="r-memo">%s</span>',
        $optionFormat='<option value="%s"%s>%s</option>',
        $radioFormat='<div class="%s">
                        <input type="radio" name="%s" id="%s" value="%s"%s >
                        <label for="%s">%s</label>
                      </div>',
        $checkFormat='<div class="%s">
                        <input type="checkbox" name="%s" id="%s" value="%s"%s>
                        <label for="%s">%s</label>
                      </div>';

    static function text($name,$val,$attr=array(),$placeholer='')
    {
        self::getClass($attr,'form-control');
        return self::getInputString('text',$name,$val,$attr,$placeholer);
    }
    static function hidden($name,$val,$attr=array())
    {
        return self::getInputString('hidden',$name,$val,$attr);
    }
    /*
     * inputTags
     * */
    static function inputTag($name,$val,$attr=array(),$placeholder='')
    {
        self::getClass($attr,'form-control');
        self::getScript('styles',array('core.Bracket.jquery#tagsinput'));
        self::getScript('scripts',array('core.Bracket.jquery#tagsinput#min'));
        self::getScript('livescripts','jQuery(\'#'.$name.'\').tagsInput({width:\'auto\'});');
        return self::getInputString('text',$name,$val,$attr,$placeholder);
    }
    /*
     * spinner
     * */
    static function spinner($name,$val,$attr=array(),$placeholder='')
    {
        $attr['autocomplete']='off';
        $attr['role']='spinbutton';
        $attr['aria-valuenow']=$val;
        self::getClass($attr,'ui-spinner-input');

        self::getScript('livescripts','
        // Spinner
        var spinner = jQuery(\'#spinner\').spinner();
        spinner.spinner(\'value\', 0);
    ');
        return self::getInputString('text',$name,$val,$attr,$placeholder);
    }

    /*
     * textarea
     * */
    static function textarea($name,$val,$attr=array(),$placeholder='')
    {
        self::getClass($attr,'form-control');
        return sprintf(self::textareaFormat,$name,$name,$placeholder,self::getAttr($attr),$val);
    }

    /*
     *  autogrow textarea
     *  自动增长文本域
     * */
    static function autogrowTextarea($name,$val,$attr=array(),$placeholer='')
    {
        $label='';
        self::getClass($attr,'form-control');

        $label= sprintf(self::textareaFormat,$name,$name,$placeholer,self::getAttr($attr),$val);
        self::getScript('livescripts','$(\'#'.$name.'\').autogrow();');
        self::getScript('scripts',array('core.Bracket.jquery#autogrow-textarea'));
        return $label;
    }
    /*
     * select2 field
     * */
    static function select2($name,$sel='',$data=array(),$attr=array(),$placeholder='')
    {
        $data_arr=array();

        self::getClass($attr,'select2-container select2');
        $attr['data-placeholder']=$placeholder;
        $selected='';
        $data_arr[]='<select name="'.$name.'" id="'.$name.'" class="'.$attr['class'].'" data-placeholder="'.$attr['data-placeholder'].'">';
        foreach($data as $k=>$v)
        {
            $selected=$k==$sel?' SELECTED':'';
            $data_arr[]='<option'.$selected.' value="'.$k.'">'.$v.'</option>';

        }
        $data_arr[]='</select>';
        self::getScript('livescripts','// Select2
  jQuery(".select2").select2({
    width:\'100%\'
  });
');
        self::getScript('scripts',array('core.Bracket.select2#min'));
        return implode('',$data_arr);
    }
    /*
     * Select2 Multiple
     * */
    static function select2Multiple($name,$sel_arr=array(),$data=array(),$attr=array(),$placeholder='')
    {
        $data_arr=array();

        self::getClass($attr,'select2-container select2 select2-multiple');
        $attr['data-placeholder']=$placeholder;
        $selected='';
        $data_arr[]='<select multiple name="'.$name.'" id="'.$name.'" class="'.$attr['class'].'" data-placeholder="'.$attr['data-placeholder'].'">';
        foreach($data as $k=>$v)
        {
            $selected=in_array($k,$sel_arr)?' SELECTED':'';
            $data_arr[]='<option'.$selected.' value="'.$k.'">'.$v.'</option>';

        }
        $data_arr[]='</select>';
        self::getScript('livescripts','// Select2
  jQuery(".select2-multiple").select2({
    width:\'100%\'
  });
');
        self::getScript('scripts',array('core.Bracket.select2#min'));
        return implode('',$data_arr);
    }
    /*
     * time
     * */
    static function timePicker($name,$val,$attr=array(),$config='')
    {
        self::getClass($attr,'form-control');
        ($config)||($config='defaultTime: false');
        self::getScript('styles',array('core.Bracket.bootstrap-timepicker#min'));
        self::getScript('scripts',array('core.Bracket.bootstrap-timepicker#min'));
        self::getScript('livescripts','
  // Time Picker
  jQuery(\'#'.$name.'\').timepicker({'.$config.'});');

        return '<div class="bootstrap-timepicker">'.self::getInputString('text',$name,$val,$attr).'</div>';
    }
    /*
     * 手机号码
     * */
    static function phoneNum($name,$val,$attr=array(),$placeholder='',$mask='999-9999-9999')
    {
        self::getClass($attr,'form-control');
        self::getScript('livescripts','jQuery("#'.$name.'").mask("'.$mask.'");');
        ($placeholder)||($placeholder='�ֻ��');
        self::getScript('scripts',array('core.Bracket.jquery#maskedinput#min'));

        return self::getInputString('text',$name,$val,$attr,$placeholder);
    }
    /*
     * 时间获取
     * */
    static function datePicker($name,$val,$attr=array(),$config='')
    {
        self::getClass($attr,'form-control');
        ($config)||($config='defaultTime: false');
        self::getScript('styles',array('core.Bracket.bootstrap-timepicker#min'));
        self::getScript('scripts',array('core.Bracket.bootstrap-timepicker#min'));
        self::getScript('livescripts','
  // Date Picker
  jQuery(\'#'.$name.'\').datepicker();
');

        return '<div class="input-group">'.self::getInputString('text',$name,$val,$attr,'dd/mm/yyyyy').'<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>';
    }
    /*
     * 文件上传
     * $data=array(
     *      array(
     *          'name'=>'test.jpg',
     *          'size'=>'30M',
     *          'id'=>'123',
     *          'url'=>'上传到地址ַ',
     *      ),
     * );
     * */
    static function fileUpload($name,$url,$data=array(),$attr=array(),$config=array())
    {
        $tmp=self::getTpl('dropzone');
        $tpl=self::getTpl('fileUpload');
        $node_arr=array();

        foreach($data as $v)
        {
            (isset($v['name']))||($v['name']='unknown');
            (isset($v['size']))||($v['size']='M');
            (isset($v['url']))||($v['url']='javascript:;');
            (isset($v['id']))||($v['id']=0);
            $node_arr[]=sprintf($tpl,$v['name'],$v['size'],$v['url']);
        }

        $tmp=sprintf($tmp,implode('',$node_arr));
        ($config)||($config=array('url'=>$url));
        $config=json_encode($config);
        self::getScript('styles',array('core.Bracket.dropzone'));
        self::getScript('scripts',array('core.Bracket.dropzone#min'));
        //self::getScript('livescripts','$("div#myId").dropzone('.$config.');');
        return $tmp;
    }
    /*多个checkbox*/
    static function checkboxs($name,$sel,$data=array())
    {
        if(substr($name,-2,2)!=='[]') $name.='[]';
        $checkData=is_string($sel)?explode(',',$sel):$sel;
        $htmlArr=array();
        $check='';
        $i=1;

        foreach($data as $v)
        {
            $check=in_array($v['val'],$checkData);
            self::getClass($v,'ckbox ckbox-default');
            $id=$name.'_'.$i++;

            $htmlArr[]=sprintf(self::$checkFormat,$v['class'],$name,$id,$v['val'],$check,$id,$v['memo']);
        }

        return implode('',$htmlArr);
    }
    /*单个checkbox*/
    static function checkbox($name,$val,$memo='',$checked=false,$ext=array())
    {
        /*<div class="%s">
                        <input type="checkbox" name="%s" id="%s" value="%s"%s>
                        <label for="%s">%s</label>
                      </div>*/
        $check=$checked?' CHECKED ':'';
        self::getClass($ext,'ckbox ckbox-default');

        return sprintf(self::$checkFormat,$ext['class'],$name,$name,$val,$check,$name,$memo);
    }

    /*单个radio*/
    static function radio($name,$val,$sel,$memo='',$checked=false,$ext=array())
    {
        self::getClass($ext,'rdio rdio-default');
        $check=$checked?' CHECKED ':'';
        return sprintf(self::$radioFormat,$ext['class'],$name,$name,$val,$check,$name,$memo);
    }
    /*多个radio*/
    static function radios($name,$sel,$data)
    {
        $htmlArr=array();
        $checkData=is_string($sel)?explode(',',$sel):$sel;
        $check='';
        $i=1;$id='';

        foreach($data as $v)
        {
            $check=in_array($v['val'],$checkData)?' CHECKED ':'';
            self::getClass($v,'rdio rdio-default');
            $id=$name.'_'.$i++;
            $htmlArr[]=sprintf(self::$radioFormat,$v['class'],$name,$id,$v['val'],$check,$id,$v['memo']);
        }

        return implode('',$htmlArr);
    }
    /*
     * 模板获取
     * */
    static function getTpl($tplName)
    {
        return flie_get_contents(THINK_PATH.'Common/Class/Html5/Tpl/'.$tplName);
    }
    /*
     * 获取类定义
     * */
    static function getClass(&$param,$default='form-control')
    {
        if(!isset($param['class']))
        {
            $param['class']=$default;
        }
    }

    /*获取输入文本*/
    private static function getInputString($type,$name,$val,$attr=array(),$placeholer='')
    {
        return sprintf(self::inputFormat,$type,$name,$name,$val,self::getAttr($attr),$placeholer);
    }

    /*属性获取*/
    private static function getAttr($attr=array())
    {
        $css_arr=array();
        foreach($attr as $k=>$v)
        {
            $css_arr[]=$k.'="'.$v.'"';
        }
        return implode(' ',$css_arr);
    }

    /*
     * 设置相关脚本,并进行去重处理,避免重复加载同一文件
     * */
    static function getScript($type,$data='')
    {
        static $loaded_file=array(
            'styles'=>array(),
            'scripts'=>array()
        );
        $type=strtolower($type);
        if($type=='livescripts')
        {
            return View::assign('livescripts',$data."\r\n");
        }
        else
        {
            //ȥ�ش���
            foreach((array)$data as $k=>$v)
            {
                if(in_array($v,$loaded_file[$type]))
                {
                    unset($data[$k]);
                }
                else
                {
                    $loaded_file[$type][]=$v;
                }
            }
            if($type=='scripts')
            {
                return View::assign('scripts',$data);
            }else if($type=='styles')
            {
                return View::assign('styles',$data);
            }
        }
    }
}

/*
 * static static function test()
    {
        self::assign('text',self::text('text','',array('class'=>'form-control'),'come on'));
        self::assign('textarea',self::textarea('textarea','textarea',array('class'=>'form-control','row'=>5),'textarea'));
        self::assign('autogrowTextarea',self::autogrowTextarea('autogrowTextarea','autogrowTextarea',array('styles'=>'height:60px;','row'=>5),'autogrowTextarea'));
        self::assign('inputTag',self::inputTag('inputTag','hehe',array()));
        self::assign('spinner',self::spinner('spinner',0));
        $select2_data=array(
            '1'=>"bee",
            '2'=>'jiangjing',
            '3'=>'tancong',
            '4'=>'adelababy'
        );
        self::assign('select2',self::select2('select2','2',$select2_data));
        self::assign('select2Multiple',self::select2Multiple('select2Multiple',array('2','3'),$select2_data));
        self::assign('timePicker',self::timePicker('timePicker','',array()));
        self::assign('phoneNum',self::phoneNum('phoneNum',''));
        self::assign('datePicker',self::datePicker('datePicker',''));
        self::assign('file',self::fileUpload('file','/Form_test.jsp'));
        self::display();
    }
 * */