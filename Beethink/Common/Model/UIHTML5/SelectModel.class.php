<?php
/**
 * 下拉框
 *
 * 自动加载依赖的文件
 */
class SelectModel
{
	protected $selIndex='';
    static $flag=false;

	/**
	 * 带检索功能的下拉多选框
	 * @param  [type]  $id       [description]
	 * @param  [type]  $data     [
	 * $data=array(
	 * 		array('值','标题'),
	 * 		...
	 * )
	 * ]
	 * @param  [type]  $selData  [支持字符串,和数组]
	 * @param  boolean $disabled [description]
	 * @return [type]            [description]
	 */
	public function multiselectWithFilter($id,$data,$selData,$disabled=false)
	{
		$this->seIndex=$selData;
		$htmlArr=array();
		$htmlArr[]='<select id="'.$id.'" name="'.$id.'" multiple="multiple" style="display: none;">';

		foreach($data as $v)
		{
			$htmlArr[]='<option value="'.$v[0].'">'.$v[1].'</option>';
		}

		$htmlArr[]='</select>';
        
		$scripts='$(document).ready(function() {
        			$(\'#'.$id.'\').multiselect();
    			  });';

		View::assign('styles',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect'));
		View::assign('scripts',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect'));
		View::assign('liveScripts',array($scripts));

		return implode('',$htmlArr);
	}
    /**
     * 单选
     */
    public function select($id,$data,$selIndex,$disabled=false)
    {
        $this->selIndex=$selIndex;
        $htmlArr=array();
        $htmlArr[]='<select id="'.$id.'">';
        foreach($data as $v)
        {
            $htmlArr[]='<option'.$this->ifSelected($v[0]).' name="'.$id.'" value="'.$v[0].'">'.$v[1].'</option>';
        }
        $htmlArr[]='</select>';
        $liveScripts='$(document).ready(function() {
                $(\'#'.$id.'\').multiselect({
                    inheritClass: true,
                    checkboxName:\''.$id.'\'
                });
            });';
        
        if(!self::$flag)
        {
            View::assign('scripts',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect'));
            View::assign('styles',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect')); 
               
            self::$flag=true;
        }
        
        View::assign('liveScripts',array($liveScripts));
        
        return implode('',$htmlArr);
    }
    
	/**
     * 多选
     * $withFilter      是否带过滤功能,默认开启
	 * @return [type]           [description]
     * 
     * $data=array(
     *    array('值','描述'),
     *    array('值','描述'),
     *    array('label',array(
     *          array('值','描述'),
     *          ...    
     *      ))
     *    ...
     * );
	 */
	public function multiSelect($id,$data,$selData,$disabled=false,$withFilter=true)
	{
        //static $flag=false;
        static $groupFlag=false;
       
		$this->selIndex=$selData;
		$htmlArr=array();
        $isGroup=false;   //是否存在分组
        $withFilter=$withFilter?'true':'false';
        $htmlArr[]='<select id="'.$id.'"'.$this->ifDisabled($disabled).' multiple="multiple" style="display: none;">';
        
        foreach($data as $v)
        {
            if(is_array($v[1]))
            {
                $htmlArr[]=$this->groupStrategy($v[0],$v[1]);  
                $isGroup=true; 
            }
            else
            {
                $htmlArr[]=sprintf('<option name="%s" value="%s"%s>%s</option>',$id.'[]',$v[0],$this->ifSelected($v[0]),$v[1]);    
            }
        }
        
        $htmlArr[]='</select>';
        $liveScripts='$(document).ready(function() {
                $(\'#'.$id.'\').multiselect({
                    enableFiltering: '.$withFilter.',
                    inheritClass: true,
                    enableClickableOptGroups: true,
                                        
                    checkboxName:\''.$id.'[]\'
                });
            });';
        
        if(!self::$flag)
        {
            View::assign('scripts',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect'));
            View::assign('styles',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect')); 
               
            self::$flag=true;
        }
        if($isGroup && !$groupFlag)
        {
            View::assign('scripts',array('core.Bootstrap.Multiselect-master.bootstrap-multiselect-collapsible-groups'));
            $groupFlag=true;
        }
        View::assign('liveScripts',array($liveScripts));
        
        return implode('',$htmlArr);
	}
    
	/**
	 * [select description]
	 * @param  [type] id名
	 * @param  [type] 
	 * $data=array(
	 * 		array('值','标题'),
	 * 		array('label描述','data-subject描述',
	 * 			array(
	 * 				array('值','标题'),
	 * 				...
	 * 			)
	 * 		),
	 * );
	 * @param  boolean
	 * @return [type]
	 */
	public function getSelect($id,$data,$selIndex=0,$disabled=false)
	{
		$relyData=array(
				'js'=>array('core.Bootstrap.Select.Bootstrap-select'),
				'css'=>array('core.Bootstrap.Select.Bootstrap-select-min')
			);
		$htmlArr=array();
		$this->selIndex=$selIndex;

		$htmlArr[]='<select'.$this->ifDisabled($disabled).' id="'.$id.'" class="selectpicker bla bla bli" multiple data-live-search="true">';

		foreach($data as $v)
		{
			if(isset($v[2]) && is_array($v[2]))
			{
				$htmlArr[]=$this->groupStrategy($v[0],$v[1],$v[2]);
			}
			else
			{
			     $htmlArr[]='<option'.$this->ifSelected($v[0]).' value="'.$v[0].'">'.$v[1].'</option>';
			}
		}

		$htmlArr[]='</select>';

		$liveScripts='$(window).on(\'load\', function () {
			
            $(\'.selectpicker\').selectpicker({
                //\'selectedText\': \'cat\'
            });

        });';
		Sys::D('LoadRely')->loadFile($relyData['css'],$relyData['js']);
		View::assign('liveScripts',array($liveScripts));

		return implode('',$htmlArr);
	}

	public function groupStrategy($label,$data)
	{
		$htmlArr=array();

		$htmlArr[]='<optgroup label="'.$label.'">';

		foreach($data as $v)
		{
			$htmlArr[]='<option'.$this->ifSelected($v[0]).' value="'.$v[0].'">'.$v[1].'</option>';
		}

		$htmlArr[]='</optgroup>';

		return implode('',$htmlArr);
	}

	public function ifDisabled($disabled)
	{
		return $disabled?' DISABLED':'';
	}
	/*判断是否选中*/
	public function ifChecked($val)
	{
		if(is_array($this->selIndex))
		{
			return in_array($val,$this->selIndex)?' CHECKED':'';
		}
		else
		{
			return $val==$this->selIndex?' CHECKED':'';
		}
	}
	/*判断是选中*/
	public function ifSelected($val)
	{
	   if(is_array($this->selIndex))
       {
            return  in_array($val,$this->selIndex)?' SELECTED':'';
       }
       else
       {
            return $this->selIndex==$val?' SELECTED':''; 
       }
	}
}

?>