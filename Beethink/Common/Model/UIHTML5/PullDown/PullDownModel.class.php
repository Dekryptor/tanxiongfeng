<?php
class PullDownModel
{
	/*下拉框向下*/
	public function Dropdown($id,$memo,$data)
	{
    	$htmlArr=array();
    	$htmlArr[]='<div class="dropdown clearfix">';	
    	$htmlArr[]='<button class="btn btn-default dropdown-toggle" type="button" id="'.$id.'" data-toggle="dropdown" aria-expanded="true">'.$memo.'<span class="caret"></span></button>';
    	$htmlArr[]='<ul class="dropdown-menu" role="menu" aria-labelledby="'.$id.'">';

    	foreach($data as  $v)
		{
			$htmlArr[]='<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">'.$v.'</a></li>';	
		}

		$htmlArr[]='</ul></div>';

		return implode('', $htmlArr);
	}
	/*下拉框  向上*/
	public function DropUp($id,$memo,$data)
	{
    	$htmlArr=array();
    	$htmlArr[]='<div class="dropup clearfix">';	
    	$htmlArr[]='<button class="btn btn-default dropdown-toggle" type="button" id="'.$id.'" data-toggle="dropdown" aria-expanded="true">'.$memo.'<span class="caret"></span></button>';
    	$htmlArr[]='<ul class="dropdown-menu" role="menu" aria-labelledby="'.$id.'">';

    	foreach($data as  $v)
		{
			$htmlArr[]='<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">'.$v.'</a></li>';	
		}

		$htmlArr[]='</ul></div>';

		return implode('', $htmlArr);

	}
}
?>