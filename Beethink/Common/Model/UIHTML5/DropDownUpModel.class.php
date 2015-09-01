<?php
class DropDownUpModel
{
	/**
	 * 
	 	@1	$title=下拉框标题			//必填
	 	@2	$data=array(				//必填
				array(
					'href'=>'',			//必填
					'class'=>'',		//可选
					'title'=>''			//必填
				)
		 	)
		@3 	$id=命名ID					//可选
	 */
	public function dropdrow($title,$data,$id='')
	{
		$htmlArr=array();
		$id=$this->getID($id);

		$htmlArr[]='<li class="dropdown dropdown-user"'.$id.'>';
		$htmlArr[]='<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" class="img-circle" src="../../assets/admin/layout/img/avatar3_small.jpg">
					<span class="username username-hide-on-mobile">
					'.$title.' </span>
					<i class="fa fa-angle-down"></i>
					</a>';
						
		$htmlArr[]='<ul class="dropdown-menu dropdown-menu-default">';
		foreach($data as $v)
		{
			$v['class']=isset($v['class'])?'<i class="'.$v['class'].'"></i>':'';
			$htmlArr[]='<li><a href="'.$v['href'].'">'.$v['class'].$v['title'].' </a></li>';
		}
		$htmlArr[]='</ul></li>';

		return implode('',$htmlArr);
	}
	/**
	 * 
	 	@1	$title=下拉框标题			//必填
	 	@2	$data=array(				//必填
				array(
					'href'=>'',			//必填
					'attr'=>'',		    //可选 拓展样式
					'title'=>''			//必填
				)
		 	)
		@3 	$id=命名ID					//可选
	 */
	public function toolbar($title,$data,$id='')
	{
		$htmlArr=array();
		$id=$this->getID($id);
		$htmlArr[]='<div class="page-toolbar"'.$id.'><div class="btn-group">';
		$htmlArr[]='<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					'.$title.' <i class="fa fa-angle-down"></i>
						</button>';
		$htmlArr[]='<ul class="dropdown-menu pull-right" role="menu">';
		$ext='';

		foreach($data as $v)
		{
			$ext=isset($v['attr'])?$this->extAttr($v['attr']):'';
			$htmlArr[]='<li>
							<a'.$ext.' href="'.$v['href'].'">'.$v['title'].'</a>
						</li>';
		}

		$htmlArr[]='</ul></div></div>';


		return implode('',$htmlArr);
	}
	/**
	 * 格式化id
	 */
	public function getID($id)
	{
		return $id?' id="'.$id.'"':'';
	}
	/**
	 * 拓展样式
	 */
	public function extAttr($data)
	{
		$htmlArr=array();

		foreach ($data as $k => $v) {
			$htmlArr[]=$k.'="'.$v.'"';	
		}

		return ' '.implode(' ',$htmlArr);
	}

}
?>