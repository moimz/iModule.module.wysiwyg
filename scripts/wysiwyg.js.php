<?php
/**
 * 이 파일은 iModule 위지윅에디터모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 위지윅에디터 스크립트를 생성한다.
 * 위지윅에디터모듈에 포함된 Froala Wysiwyg Editor (https://froala.com/wysiwyg-editor) 는 iModule 내에서 자유롭게 사용할 수 있도록 라이센싱되어 있습니다.
 * iModule 외부에서 Froala Wysiwyg Editor 사용시 라이센스 위반이므로 주의하시기 바랍니다.
 * 
 * @file /modules/wysiwyg/scripts/wysiwyg.js.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 3. 23.
 */
header("Content-Type: application/x-javascript; charset=utf-8");

echo PHP_EOL.'/* froala_editor.min.js */'.PHP_EOL;
readfile(__DIR__.'/froala_editor/froala_editor.min.js');

echo PHP_EOL.'/* codemirror.js */'.PHP_EOL;
readfile(__DIR__.'/codemirror/codemirror.js');

echo PHP_EOL.'/* xml.js */'.PHP_EOL;
readfile(__DIR__.'/codemirror/mode/xml.js');

$plugins = array('align','code_view','colors','file','font_size','image','line_breaker','link','insert_code','lists','paragraph_format','table','url','video');
foreach ($plugins as $plugin) {
	if (is_file(__DIR__.'/froala_editor/plugins/'.$plugin.'.min.js') == true) {
		echo PHP_EOL.'/* '.$plugin.'.min.js */'.PHP_EOL;
		readfile(__DIR__.'/froala_editor/plugins/'.$plugin.'.min.js');
	}
}
?>
(function($) {
	$.fn.wysiwyg = function(isReload) {
		var $textarea = this;
		var module = $textarea.attr("data-wysiwyg-module");
		var fileUpload = $textarea.attr("data-wysiwyg-file-upload") == "TRUE";
		var imageUpload = $textarea.attr("data-wysiwyg-image-upload") == "TRUE";
		
		var plugins = ["align","codeView","colors","fontSize","lineBreaker","link","lists","paragraphFormat","table","url","video"];
		
		if (fileUpload == true) plugins.push("file");
		if (imageUpload == true) plugins.push("image");
		
		/**
		 * 에디터와 연계된 업로더가 존재하지 않을 경우 기본 위지윅에디터 업로더를 사용한다.
		 */
		if ($("div[data-module=attachment][data-uploader-wysiwyg=TRUE][data-uploader-module="+module+"][data-uploader-target="+$textarea.attr("name")+"]").length == 0) {
			$textarea.data("uploader",false);
			
			$textarea.on("froalaEditor.image.uploaded",function(e,editor,image) {
				image = JSON.parse(image);
				var $form = $(this).parents("form");
				$form.append($("<input>").attr("type","hidden").attr("name",$(this).attr("name")+"_files[]").val(image.code));
			});
			
			$textarea.on("froalaEditor.file.uploaded",function(e,editor,file) {
				file = JSON.parse(file);
				var $form = $(this).parents("form");
				$form.append($("<input>").attr("type","hidden").attr("name",$(this).attr("name")+"_files[]").val(file.code));
			});
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
		
		$textarea.on("froalaEditor.image.inserted",function(e,editor,$image,response) {
			if (response) {
				$image.attr("data-code",null);
			}
		});
		
		$textarea.on("froalaEditor.file.inserted",function(e,editor,$file,response) {
			if (response) {
				var result = typeof response == "object" ? response : JSON.parse(response);
				if (result.idx) {
					$file.attr("data-idx",result.idx);
				}
				$file.attr("data-code",null);
			}
		});
		
		if ($textarea.is("div") == true) {
			var toolbarInline = true;
			var toolbarButtons = ["html","bold","italic","underline","strikeThrough","color","emoticons","-","paragraphFormat","align","formatOL","formatUL","indent","outdent","-","insertImage","insertLink","insertFile","insertVideo","undo","redo"];
			var toolbarButtonsXS = toolbarButtons;
			var toolbarButtonsMD = toolbarButtons;
			var toolbarButtonsSM = toolbarButtons;
			var pasteDeniedTags = [];
			var pasteDeniedAttrs = [];
		} else {
			var toolbarInline = false;
			var toolbarButtons = ["html","|","bold","italic","underline","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertTable","insertImage","insertFile","insertVideo","insertCode"];
			var toolbarButtonsXS = ["bold","italic","underline","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertImage","insertFile","insertVideo"];
			var toolbarButtonsMD = ["bold","italic","underline","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertImage","insertFile","insertVideo"];
			var toolbarButtonsSM = ["bold","italic","underline","|","paragraphFormat","fontSize","color","|","align","formatOL","formatUL","|","insertLink","insertImage","insertFile","insertVideo"];
			var pasteDeniedTags = ["abbr","address","article","aside","audio","base","bdi","bdo","blockquote","button","canvas","caption","cite","code","col","colgroup","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","header","hgroup","iframe","input","ins","kbd","keygen","label","legend","link","main","mark","menu","menuitem","meter","nav","noscript","object","optgroup","option","output","param","pre","progress","queue","rp","rt","ruby","s","samp","script","style","section","select","small","source","span","strike","strong","summary","textarea","time","title","tr","track","var","video","wbr"];
			var pasteDeniedAttrs = ["class","id","style"];
		}
		
		$textarea.froalaEditor({
			key:"pFOFSAGLUd1AVKg1SN==", // Froala Wysiwyg OEM License Key For MoimzTools Only
			codeMirrorOptions:{
				indentWithTabs:true,
				lineNumbers:true,
				lineWrapping:true,
				mode:"text/html",
				tabMode:"indent",
				tabSize:4
			},
			toolbarInline:toolbarInline,
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
			videoUpload:false, // @todo Youtube API
			toolbarStickyOffset:$("#iModuleNavigation").outerHeight(true),
			placeholderText:$textarea.attr("placeholder") ? $textarea.attr("placeholder") : "Type something",
			imageEditButtons:["imageAlign","imageLink","linkOpen","linkEdit","linkRemove","imageDisplay","imageStyle","imageAlt","imageSize"],
			toolbarButtons:toolbarButtons,
			toolbarButtonsXS:toolbarButtonsXS,
			toolbarButtonsMD:toolbarButtonsMD,
			toolbarButtonsSM:toolbarButtonsSM,
			pasteDeniedTags:pasteDeniedTags,
			pasteDeniedAttrs:pasteDeniedAttrs
		});
	};
})(jQuery);