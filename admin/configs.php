<?php
/**
 * 이 파일은 iModule 포트폴리오모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 위지윅에디터 설정을 위한 설정폼을 생성한다.
 * 
 * @file /modules/wysiwyg/admin/configs.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0.160910
 */

if (defined('__IM__') == false) exit;
?>
<script>
var config = new Ext.form.Panel({
	id:"ModuleConfigForm",
	border:false,
	bodyPadding:10,
	fieldDefaults:{labelAlign:"right",labelWidth:100,anchor:"100%",allowBlank:true},
	items:[
		new Ext.form.FieldSet({
			title:Wysiwyg.getText("admin/configs/form/default_setting"),
			items:[
				new Ext.form.TextArea({
					fieldLabel:Wysiwyg.getText("admin/configs/form/iframe"),
					name:"iframe",
					height:200
				}),
				new Ext.form.DisplayField({
					value:'<div class="x-form-help" style="padding-left:105px;">'+Wysiwyg.getText("admin/configs/form/iframe_help")+'</div>'
				})
			]
		})
	]
});
</script>