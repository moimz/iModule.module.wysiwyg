<?php
header("Content-Type: text/css; charset=utf-8");

readfile(__DIR__.'/froala_editor.min.css');
if (isset($_GET['theme']) == true && is_file(__DIR__.'/themes/'.$_GET['theme'].'.css') == true) {
	readfile(__DIR__.'/themes/'.$_GET['theme'].'.css');
}

$plugins = array('align','code_view','colors','file','font_size','image','line_breaker','link','lists','paragraph_format','table','url','video');
foreach ($plugins as $plugin) {
	if (is_file(__DIR__.'/plugins/'.$plugin.'.min.css') == true) {
		readfile(__DIR__.'/plugins/'.$plugin.'.min.css');
	}
}
?>
.fr-box h1 {font-size:17px; font-weight:bold; font-family:inherit;}
.fr-box h2 {font-size:15px; font-weight:bold; font-family:inherit;}
.fr-box h3 {font-size:13px; font-weight:bold; font-family:inherit;}
.fr-box ol, .fr-box ul {margin-left:25px;}
.fr-box label {display:inline !important;}
.fr-box table {border-collapse:collapse; border-spacing:0; border:1px; empty-cells:show; table-layout:auto; max-width:100%;}
.fr-box table thead tr {border-bottom:2px solid #252525}
.fr-box table thead th {background:#e6e6e6; border:1px solid #dddddd; padding:2px 5px;}
.fr-box table tbody td {border:1px solid #dddddd; padding:2px 5px; vertical-align:middle; }
.fr-box pre {background:#f4f4f4; border:1px solid #ddd; border-radius:5px; font-family:Menlo, Monaco, monospace, sans-serif; padding:10px; margin:10px 0px;}
.fr-box .fr-file {display:inline-block; border:1px solid #ddd; border-radius:5px; color:#000; text-decoration:none; vertial-align:middle; padding:5px 10px 5px 30px; background:url(../images/download.png) no-repeat 10px 50%; background-size:14px 14px;}
.fr-box .fr-file:hover {background-color:rgba(0,0,0,0.1);}
.fr-box .fr-file > i.size {display:inline-block; padding-left:5px; color:#666; font-style:normal;}
.fr-box img {max-width:100%;}

.fr-wrapper {border-radius:0px !important;}
.fr-toolbar {border-top-width:2px !important; border-radius:0px !important; -moz-border-radius:0px !important; -webkit-border-radius:0px !important;}

.fr-view {line-height:1.6;}
.fr-dropdown-list li {font-size:13px !important;}
.fr-dropdown-list li a {padding:3px 15px !important;}
.fr-popup {border-top:3px solid #222 !important; margin-top:7px !important; border-radius:0px !important;}
.fr-popup .fr-arrow {top:-7px !important;}

div[data-module=wysiwyg].error .fr-toolbar.fr-top {border-top-color:#f44336 !important; box-shadow:0px 1px 3px rgba(244,67,54,.3), 0 1px 1px 1px rgba(244,67,54,.3);}
div[data-module=wysiwyg].error .fr-box.fr-basic.fr-top .fr-wrapper {box-shadow:0 1px 3px rgba(244,67,54,.3),0 1px 1px 1px rgba(244,67,54,.3)}
div[data-module=wysiwyg].success .fr-toolbar.fr-top {border-top-color:#4caf50 !important; box-shadow:0 1px 3px rgba(76,175,80,.3), 0 1px 1px 1px rgba(76,175,80,.3);}
div[data-module=wysiwyg].success .fr-box.fr-basic.fr-top .fr-wrapper {box-shadow:0 1px 3px rgba(76,175,80,.3),0 1px 1px 1px rgba(76,175,80,.3)}

div[data-role=input] > div[data-module=wysiwyg] {margin-top:-10px;}