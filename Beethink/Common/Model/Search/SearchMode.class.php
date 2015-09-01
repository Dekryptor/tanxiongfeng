<?php
/**
 *     功能：生成搜索栏
 *   创建人：BEE
 *     日期：
 *   修改人：
 * 修改日期：
 *     备注：
*/
class SearchModel
{
    //创建搜索框
    function builtSearch($searchData,$action='')
    {
        if(!is_array($searchData))
        {
            die('searchData 格式错误');
        }
        $oF=D('Form');
        $html='<div class="searchSlide" id="searchSlide">
        <li class="shead">
        <span id="sCondition" class="label"></span>
        <a id="sTxt" href="javascript:;" class="slideTxt sDown">搜索</a>
        </li><form id="searchCon" method="get" action="'.$action.'" class="searchCon">
        <input type="hidden" name="do" value="query" />';
        foreach($searchData as $v)
        {
        if(method_exists($oF,$v[0]))
        {
          if(!isset($v[4])) $v[4]=array();
          $html.='<li><span class="smemo">'.$v[1].':</span>'.$oF->$v[0]($v[2],$v[3],$v[4]).'</li>';
        }
        } 
        $html.='<li><span class="smemo"></span><input type="submit" class="sub" name="sub" value="确定" /><input id="sclose" class="sclose" type="button" value="关闭" onclick="" /></li></form></div>';
        return $html;
    }
}
?>