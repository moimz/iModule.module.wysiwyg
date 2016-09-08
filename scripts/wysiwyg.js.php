<?php
header("Content-Type: application/x-javascript; charset=utf-8");

readfile(__DIR__.'/froala_editor.min.js');

$plugins = array('align','code_view','colors','file','font_size','image','line_breaker','link','insert_code','lists','paragraph_format','table','url','video');
foreach ($plugins as $plugin) {
	if (is_file(__DIR__.'/plugins/'.$plugin.'.min.js') == true) {
		readfile(__DIR__.'/plugins/'.$plugin.'.min.js');
	}
}
?>
var _WYSIWYGRESIZETIMEOUT = null;
(function($) {
	$.fn.wysiwyg = function(isReload) {
		var $textarea = this;
		var module = $textarea.attr("data-module");
		var allowUpload = $textarea.attr("data-uploader") == "true";
		
		if (allowUpload == true) {
			var plugins = ["align","codeView","colors","file","fontSize","image","lineBreaker","link","lists","paragraphFormat","insertCode","table","url","video"];
		} else {
			var plugins = ["align","codeView","colors","fontSize","lineBreaker","link","lists","paragraphFormat","table","url","video"];
		}
		
		//plugins = null;
		
		$textarea.on("froalaEditor.image.uploaded",function(e,editor,response) {
			var result = JSON.parse(response);
			if (result.success == true) {
				var id = editor.$oel.attr("id")+"-attachment";
				var file = result.fileInfo;
				file.status = "COMPLETE";
				Attachment.add(id,file);
			}
		});
		
		$textarea.on("froalaEditor.file.uploaded",function(e,editor,response) {
			var result = JSON.parse(response);
			if (result.success == true) {
				var id = editor.$oel.attr("id")+"-attachment";
				var file = result.fileInfo;
				file.status = "COMPLETE";
				Attachment.add(id,file);
			}
		});
		
		$textarea.on("froalaEditor.image.inserted",function(e,editor,$img,response) {
			if (response) {
				var result = typeof response == "object" ? response : JSON.parse(response);
				if (result.success == true) {
					$img.attr("data-success",null);
					$img.attr("data-fileinfo",null);
					$img.attr("data-idx",result.fileInfo.idx);
				}
			}
		});
		
		$textarea.on("froalaEditor.image.replaced",function(e,editor,$img,response) {
			if (response) {
				var result = typeof response == "object" ? response : JSON.parse(response);
				if (result.success == true) {
					$img.attr("data-success",null);
					$img.attr("data-fileinfo",null);
					$img.attr("data-idx",result.fileInfo.idx);
					editor.html.set(editor.html.get());
				}
			}
		});
		
		$textarea.on("froalaEditor.file.inserted",function(e,editor,$file,response) {
			if (response) {
				var result = typeof response == "object" ? response : JSON.parse(response);
				if (result.success == true) {
					editor.events.focus(true);
					editor.selection.restore();
					editor.undo.run();
					editor.undo.dropRedo();
					
					editor.html.insert('<a href="'+result.fileInfo.path+'" data-idx="'+result.fileInfo.idx+'" class="btn btnWhite btnDownload"><i class="fa fa-download"></i> '+result.fileInfo.name+' ('+Attachment.getFileSize(result.fileInfo.size)+')</a>');
					editor.html.insert('<p></p>');
					editor.undo.saveStep();
				}
			}
		});
		
		$textarea.on("froalaEditor.initialized",function(e,editor) {
			var $toolbar = $("div.fr-toolbar",editor.$box);
			var icon = $toolbar.children();
			
			var $lastSeparator = null;
			
			var i = position = 0;
			while (i < icon.length) {
				for (var loop=icon.length;i<loop;i++) {
					var $icon = $(icon[i]);
					if ($icon.is("div.fr-vs") == true) {
						$lastSeparator = $icon;
						position = i;
					}
					
					if ($lastSeparator != null && $lastSeparator.offset().top != $icon.offset().top && $lastSeparator.offset().top < $icon.offset().top + 2) {
						$lastSeparator.removeClass("fr-vs").addClass("fr-hs").attr("data-changed","true");
						i = position + 1;
						$lastSeparator = null;
						break;
					}
				}
			}
		});
		
		$(window).on("resize",function() {
			if (_WYSIWYGRESIZETIMEOUT != null) {
				clearTimeout(_WYSIWYGRESIZETIMEOUT);
				_WYSIWYGRESIZETIMEOUT = null;
			}
			
			_WYSIWYGRESIZETIMEOUT = setTimeout(function() {
				$("div.fr-toolbar").each(function() {
					var $toolbar = $(this);
					$("div.fr-separator[data-changed=true]").removeClass("fr-hs").removeClass("fr-vs");
					
					var icon = $toolbar.children();
					
					var $lastSeparator = null;
					
					var i = position = 0;
					while (i < icon.length) {
						for (var loop=icon.length;i<loop;i++) {
							var $icon = $(icon[i]);
							if ($icon.is("div.fr-vs") == true) {
								$lastSeparator = $icon;
								position = i;
							}
							
							if ($lastSeparator != null && $lastSeparator.offset().top != $icon.offset().top && $lastSeparator.offset().top < $icon.offset().top + 2) {
								$lastSeparator.removeClass("fr-vs").addClass("fr-hs").attr("data-changed","true");
								i = position + 1;
								$lastSeparator = null;
								break;
							}
						}
					}
				});
			},200);
		});
		
		$textarea.froalaEditor({
			key:"pFOFSAGLUd1AVKg1SN==", // Froala Wysiwyg OEM License Key For MoimzTools Only
			pluginsEnabled:plugins,
			heightMin:parseInt($textarea.attr("data-minHeight")),
			fontSize:["8","9","10","11","12","14","18","24"],
			paragraphFormat:{N:"Normal",H1:"Heading 1",H2:"Heading 2",H3:"Heading 3"},
			linkEditButtons:["linkOpen","linkEdit","linkRemove"],
			imageDefaultWidth:0,
			imageUploadURL:ENV.getProcessUrl("attachment","wysiwyg_upload"),
			imageUploadParams:{_module:module,_target:$textarea.attr("name")},
			fileUploadURL:ENV.getProcessUrl("attachment","wysiwyg_upload"),
			fileUploadParams:{_module:module,_target:$textarea.attr("name")},
			toolbarStickyOffset:$("#iModuleNavigation").outerHeight(true),
			placeholderText:$textarea.attr("placeholder") ? $textarea.attr("placeholder") : "Type something",
			toolbarButtons:["html","|","bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","outdent","indent","|","insertLink","insertTable","insertImage","insertFile","insertVideo","insertCode"],
			toolbarButtonsXS:["bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","outdent","indent","|","insertLink","insertImage","insertFile","insertVideo"],
			toolbarButtonsMD:["bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","outdent","indent","|","insertLink","insertImage","insertFile","insertVideo"],
			toolbarButtonsSM:["bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","outdent","indent","|","insertLink","insertImage","insertFile","insertVideo"],
			
			pasteDeniedTags:["a","abbr","address","area","article","aside","audio","base","bdi","bdo","blockquote","button","canvas","caption","cite","code","col","colgroup","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","header","hgroup","hr","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","menu","menuitem","meter","nav","noscript","object","ol","optgroup","option","output","param","pre","progress","queue","rp","rt","ruby","s","samp","script","style","section","select","small","source","span","strike","strong","sub","summary","sup","textarea","time","title","tr","track","ul","var","video","wbr"],
			pasteDeniedAttrs:["class","id","style"]
		});
		
		if (isReload === true && allowUpload === true) {
			Attachment.initEvent($textarea.attr("id")+"-attachment");
		}
	};
})(jQuery);