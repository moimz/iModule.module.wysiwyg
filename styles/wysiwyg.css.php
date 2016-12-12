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
.fr-box .btnDownload {margin:5px 0px;}

.fr-wrapper {border-radius:0px !important;}
.fr-toolbar {border-top:1px solid #222 !important; border-radius:0px !important; -moz-border-radius:0px !important; -webkit-border-radius:0px !important;}
.fr-command.fr-btn {margin:0px !important;}
.fr-command.fr-btn i {font-size:12px !important; margin:12px 10px !important;}
.fr-command.fr-btn.fr-dropdown i {margin-right:15px !important;}
.fr-command.fr-btn.fr-dropdown::after {right:5px !important;}
.fr-separator.fr-vs {height:32px !important;}
.fr-view {line-height:1.6;}
.fr-dropdown-list li {font-size:13px !important;}
.fr-dropdown-list li a {padding:3px 15px !important;}
.fr-popup {border-top:3px solid #222 !important; margin-top:7px !important; border-radius:0px !important;}
.fr-popup .fr-arrow {top:-7px !important;}