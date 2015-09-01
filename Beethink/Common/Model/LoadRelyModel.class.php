<?php
class LoadRelyModel
{
	public function loadFile($cssData,$jsData)
	{
		View::assign('styles',$cssData);
		View::assign('scripts',$jsData);
	}
}
?>