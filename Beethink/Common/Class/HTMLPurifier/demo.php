<?php
header('content-type:text/html;charset=utf-8');
/**
 * @author BEE
 * @copyright 2014
 * 过滤非AllowedElements的元素
 */
require_once 'HTMLPurifier.auto.php';
$config=HTMLPurifier_Config::createDefault();

//设置配置信息

$config->set('HTML.AllowedElements', array('div'=>true, 'table'=>true, 'tr'=>true, 'td'=>true, 'br'=>true));
$config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); //html文档类型（常设）
$config->set('Core.Encoding', 'UTF-8'); //字符编码（常设）

$purifier=new HTMLPurifier($config);


$html='<div class="test"><script>alert("this is a test");</script></div>';
$puri_html=$purifier->purify($html);

echo $puri_html;
?>