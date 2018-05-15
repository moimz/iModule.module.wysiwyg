<?php
/**
 * 이 파일은 iModule 위지윅에디터모듈 일부입니다. (https://www.imodule.kr)
 *
 * 위지윅에디터 설정을 위한 설정폼을 생성한다.
 * 
 * @file /modules/wysiwyg/admin/configs.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0
 * @modified 2018. 3. 18.
 */
if (defined('__IM__') == false) exit;
?>
<script>
var config = new Ext.form.Panel({
	id:"ModuleConfigForm",
	border:false,
	bodyPadding:10,
	width:600,
	fieldDefaults:{labelAlign:"right",labelWidth:100,anchor:"100%",allowBlank:true},
	items:[
		new Ext.form.FieldSet({
			title:Wysiwyg.getText("admin/configs/form/default_setting"),
			items:[
				new Ext.form.TextArea({
					fieldLabel:Wysiwyg.getText("admin/configs/form/iframe"),
					name:"iframe",
					afterBodyEl:'<div class="x-form-help">'+Wysiwyg.getText("admin/configs/form/iframe_help")+'</div>'
				})
			]
		})
	]
});
</script>