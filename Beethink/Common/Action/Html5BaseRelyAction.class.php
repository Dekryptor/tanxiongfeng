<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2015/7/17 0017
 * Time: 10:33
 */
class Html5BaseRelyAction
{
    /*
     * 导入依赖的基本文件
     * */
    static function relyFile()
    {
        $styles_arr=array(
            'core.Bracket.style#default',
            'core.Bracket.bootstrap#min',
            'core.Bracket.bootstrap-override',
            'core.Bracket.weather-icons#min',
            'core.Bracket.animate#delay',
            'core.Bracket.select2',
            'core.Bracket.toggles',
            'core.Bracket.animate#min',
            'core.Bracket.lato',
            'core.Bracket.roboto',
            'core.Bracket.jquery-ui-1#10#3',
            'core.Bracket.font-awesome#min',
            'core.Bracket.search-slide',
        );
        //   core.Bracket.
        $scripts_arr=array(
            'core.Bracket.jquery-1#11#1#min',
            'core.Bracket.jquery-migrate-1#2#1#min',
            'core.Bracket.jquery-ui-1#10#3#min',
            'core.Bracket.jquery#cookies',
            'core.Bracket.bootstrap#min',
            'core.Bracket.modernizr#min',
            'core.Bracket.jquery#sparkline#min',
            'core.Bracket.toggles#min',
            'core.Bracket.retina#min',
            'core.Bracket.custom',
            'core.Bracket.search-slide',
            'core.Bracket.message',
        );

        View::assign('scriptTml','<script>jQuery(document).ready(function (){%s})</script>');

        View::l_assign('styles',$styles_arr,1);

        View::assign('scripts',$scripts_arr,1);

        return View::layout('core.Html5.baseRelyFile');
    }
}