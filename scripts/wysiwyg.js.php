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

(function($) {
	$.fn.wysiwyg = function(isReload) {
		var $textarea = this;
		var module = $textarea.attr("data-wysiwyg-module");
		var allowUpload = $textarea.attr("data-wysiwyg-uploader") == "TRUE";
		
		if (allowUpload == true) {
			var plugins = ["align","codeView","colors","file","fontSize","image","lineBreaker","link","lists","paragraphFormat","insertCode","table","url","video"];
		} else {
			var plugins = ["align","codeView","colors","fontSize","lineBreaker","link","lists","paragraphFormat","table","url","video"];
		}
		
		/**
		 * 에디터와 연계된 업로더가 존재하지 않을 경우 기본 위지윅에디터 업로더를 사용한다.
		 */
		if ($("div[data-module=attachment][data-uploader-wysiwyg=TRUE][data-uploader-module="+module+"][data-uploader-target="+$textarea.attr("name")+"]").length == 0) {
			$textarea.data("uploader",false);
		} else {
			$textarea.data("uploader",$("div[data-module=attachment][data-uploader-wysiwyg=TRUE][data-uploader-module="+module+"][data-uploader-target="+$textarea.attr("name")+"]"));
			
			$textarea.on("froalaEditor.image.beforeUpload",function(e,editor,files) {
				var attachments = [];
				if (files.length > 0) {
					editor.edit.off();
					for (var i=0, loop=files.length;i<loop;i++) {
						files[i].wysiwyg = true;
						attachments.push(files[i]);
					}
					
					Attachment.add($(this).data("uploader").attr("id"),attachments);
					editor.popups.hideAll();
					editor.edit.on();
				}
				
				return false;
			});
			
			$textarea.on("froalaEditor.image.beforePasteUpload",function(e,editor,img) {
				if (img.src.indexOf('data:') !== 0) return;
				var type = img.src.match(/data:(.*?);/).length > 0 ? img.src.match(/data:(.*?);/).pop() : null;
				if (type == null) return;
				
				$current_image = $(img);
				$current_image.remove();
				editor.edit.off();

				var binary = atob($(img).attr('src').split(',')[1]);
				var array = [];

				for (var i=0;i<binary.length;i++) {
					array.push(binary.charCodeAt(i));
				}
				var upload_img = new Blob([new Uint8Array(array)],{type:type});
				upload_img.name = "clipboard."+type.split("/").pop();
				upload_img.wysiwyg = true;
				
				Attachment.add($(this).data("uploader").attr("id"),[upload_img]);
				editor.edit.on();
				
				console.log(upload_img);
				
				return false;
			});
			
			$textarea.on("froalaEditor.file.beforeUpload",function(e,editor,files) {
				var attachments = [];
				if (files.length > 0) {
					editor.edit.off();
					for (var i=0, loop=files.length;i<loop;i++) {
						files[i].wysiwyg = true;
						attachments.push(files[i]);
					}
					
					Attachment.add($(this).data("uploader").attr("id"),attachments);
					editor.popups.hideAll();
					editor.edit.on();
				}
				
				return false;
			});
		}
		/*
		$textarea.on("froalaEditor.image.uploaded",function(e,editor,response) {
			var result = JSON.parse(response);
			if (result.success == true) {
//				var id = editor.$oel.attr("id")+"-attachment";
				var file = result.file;
//				file.status = "COMPLETE";
//				Attachment.add(id,file);
			}
		});
		
		$textarea.on("froalaEditor.file.uploaded",function(e,editor,response) {
			var result = JSON.parse(response);
			if (result.success == true) {
				console.log("uploaded");
				var id = editor.$oel.attr("id")+"-attachment";
				var file = result.file;
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
					$img.attr("data-idx",result.file.idx);
				}
			}
		});
		
		$textarea.on("froalaEditor.file.inserted",function(e,editor,$file,response) {
			console.log("uploaded");
			
			if (response) {
				var result = typeof response == "object" ? response : JSON.parse(response);
				if (result.success == true) {
					$file.attr("href",result.file.download);
					$file.attr("data-idx",result.file.idx);
				}
			}
		});
		*/
		$textarea.froalaEditor({
			key:"pFOFSAGLUd1AVKg1SN==", // Froala Wysiwyg OEM License Key For MoimzTools Only
			pluginsEnabled:plugins,
			heightMin:parseInt($textarea.attr("data-wysiwyg-minHeight")),
			fontSize:["8","9","10","11","12","14","18","24"],
			paragraphFormat:{N:"Normal",H1:"Heading 1",H2:"Heading 2",H3:"Heading 3"},
			linkEditButtons:["linkOpen","linkEdit","linkRemove"],
			imageDefaultWidth:0,
			imageUploadURL:ENV.getProcessUrl("attachment","wysiwyg"),
			imageUploadParams:{module:module,target:$textarea.attr("name"),wyswiyg:"TRUE"},
			fileUploadURL:ENV.getProcessUrl("attachment","wysiwyg"),
			fileUploadParams:{module:module,target:$textarea.attr("name"),wysiwyg:"TRUE"},
			toolbarStickyOffset:$("#iModuleNavigation").outerHeight(true),
			placeholderText:$textarea.attr("placeholder") ? $textarea.attr("placeholder") : "Type something",
			imageEditButtons:["imageAlign","imageLink","linkOpen","linkEdit","linkRemove","imageDisplay","imageStyle","imageAlt","imageSize"],
			toolbarButtons:["html","|","bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertTable","insertImage","insertFile","insertVideo","insertCode"],
			toolbarButtonsXS:["bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertImage","insertFile","insertVideo"],
			toolbarButtonsMD:["bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertImage","insertFile","insertVideo"],
			toolbarButtonsSM:["bold","italic","underline","strikeThrough","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertImage","insertFile","insertVideo"],
			
			pasteDeniedTags:["a","abbr","address","area","article","aside","audio","base","bdi","bdo","blockquote","button","canvas","caption","cite","code","col","colgroup","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","header","hgroup","hr","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","menu","menuitem","meter","nav","noscript","object","ol","optgroup","option","output","param","pre","progress","queue","rp","rt","ruby","s","samp","script","style","section","select","small","source","span","strike","strong","sub","summary","sup","textarea","time","title","tr","track","ul","var","video","wbr"],
			pasteDeniedAttrs:["class","id","style"]
		});
	};
})(jQuery);