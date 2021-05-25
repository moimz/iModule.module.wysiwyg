<?php
/**
 * 이 파일은 iModule 위지윅에디터모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 위지윅에디터 스타일시트를 생성한다.
 * 
 * @file /modules/wysiwyg/styles/wysiwyg.css.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2021. 5. 25.
 */
header("Content-Type: text/css; charset=utf-8");

readfile(__DIR__.'/froala_editor/froala_editor.min.css');

echo PHP_EOL;

if (isset($_GET['theme']) == true && is_file(__DIR__.'/themes/'.$_GET['theme'].'.css') == true) {
	readfile(__DIR__.'/froala_editor/themes/'.$_GET['theme'].'.css');
}

$plugins = array('align','code_view','colors','file','font_size','image','line_breaker','link','lists','paragraph_format','table','url','video');
foreach ($plugins as $plugin) {
	if (is_file(__DIR__.'/froala_editor/plugins/'.$plugin.'.min.css') == true) {
		echo PHP_EOL;
		readfile(__DIR__.'/froala_editor/plugins/'.$plugin.'.min.css');
	}
}

readfile(__DIR__.'/froala_editor/froala_style.min.css');
?>
.fr-box h1 {font-size:18px; font-weight:bold; font-family:inherit;}
.fr-box h2 {font-size:16px; font-weight:bold; font-family:inherit;}
.fr-box h3 {font-size:14px; font-weight:bold; font-family:inherit;}
.fr-box ol:not([data-role]), .fr-box ul:not([data-role]) {margin-left:25px; list-style-position:outside;}
.fr-box ul:not([data-role]) {list-style-type:disc;}
.fr-box label {display:inline !important;}
.fr-box table {border-collapse:collapse; border-spacing:0; border:1px; empty-cells:show; table-layout:auto; max-width:100%;}
.fr-box table thead tr {border-bottom:2px solid #252525}
.fr-box table thead th {background:#e6e6e6; border:1px solid #dddddd; padding:2px 5px;}
.fr-box table tbody td {border:1px solid #dddddd; padding:2px 5px; vertical-align:middle; }
.fr-box .fr-file {display:inline-block; color:#1976D2; text-decoration:none; vertical-align:middle; padding:0px 10px 0px 20px; background:url(../images/download.png) no-repeat 0px 50%; background-size:14px 14px;}
.fr-box .fr-file > span {display:inline-block; padding-left:5px; color:#666; font-style:normal;}
.fr-box .fr-file > span:before {content:"(";}
.fr-box .fr-file > span:after {content:")";}
.fr-box img {max-width:100%;}

.fr-wrapper {border-radius:0px !important;}
.fr-toolbar {border-top-width:2px !important; border-radius:0px !important; -moz-border-radius:0px !important; -webkit-border-radius:0px !important;}

.fr-view {line-height:1.6;}
.fr-dropdown-list li {font-size:13px !important;}
.fr-dropdown-list li a {padding:3px 15px !important;}
.fr-popup {border-top:2px solid #222 !important; margin-top:7px !important; border-radius:0px !important;}
.fr-popup .fr-arrow {top:-7px !important;}
.fr-above {border-top:0 !important; border-bottom:2px solid #222 !important; margin-top:0 !important; border-radius:0px !important;}
.fr-popup .fr-color-hex-layer {width:244px;}
.fr-popup .fr-color-set {margin:10px 10px 0px 10px; width:224px;}
.fr-inline .fr-arrow {top:-7px !important;}
.fr-above .fr-arrow {top:auto !important; bottom:-7px !important;}

div[data-module=wysiwyg].error .fr-toolbar.fr-top {border-top-color:#f44336 !important; box-shadow:0px 1px 3px rgba(244,67,54,.3), 0 1px 1px 1px rgba(244,67,54,.3);}
div[data-module=wysiwyg].error .fr-box.fr-basic.fr-top .fr-wrapper {box-shadow:0 1px 3px rgba(244,67,54,.3),0 1px 1px 1px rgba(244,67,54,.3)}
div[data-module=wysiwyg].success .fr-toolbar.fr-top {border-top-color:#4caf50 !important; box-shadow:0 1px 3px rgba(76,175,80,.3), 0 1px 1px 1px rgba(76,175,80,.3);}
div[data-module=wysiwyg].success .fr-box.fr-basic.fr-top .fr-wrapper {box-shadow:0 1px 3px rgba(76,175,80,.3),0 1px 1px 1px rgba(76,175,80,.3)}

div[data-role=input] > div[data-module=wysiwyg] {margin-top:-10px;}

@media only screen and (max-width:599px) {
	div[data-role=input] > div[data-module=wysiwyg] {margin-top:0px;}
}