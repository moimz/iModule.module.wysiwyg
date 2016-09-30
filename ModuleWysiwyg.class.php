<?php
class ModuleWysiwyg {
	private $IM;
	private $Module;
	
	private $lang = null;
	
	private $_attachment = null;
	private $_id = null;
	private $_name = null;
	private $_module = null;
	private $_placeholderText = null;
	private $_content = null;
	private $_required = false;
	private $_theme = 'default';
	private $_height = 300;
	private $_hideButtons = array();
	private $_toolBarFixed = true;
	private $_uploader = true;
	
	function __construct($IM,$Module) {
		$this->IM = $IM;
		$this->Module = $Module;
		
		$this->_attachment = $this->IM->getModule('attachment');
	}
	
	function db() {
		return $this->IM->db($this->Module->getInstalled()->database);
	}
	
	/**
	 * Get language string from language code
	 *
	 * @param string $code language code (json key)
	 * @return string language string
	 */
	function getLanguage($code) {
		if ($this->lang == null) {
			if (file_exists($this->Module->getPath().'/languages/'.$this->IM->language.'.json') == true) {
				$this->lang = json_decode(file_get_contents($this->Module->getPath().'/languages/'.$this->IM->language.'.json'));
				if ($this->IM->language != $this->Module->getPackage()->language) {
					$this->oLang = json_decode(file_get_contents($this->Module->getPath().'/languages/'.$this->Module->getPackage()->language.'.json'));
				}
			} else {
				$this->lang = json_decode(file_get_contents($this->Module->getPath().'/languages/'.$this->Module->getPackage()->language.'.json'));
				$this->oLang = null;
			}
		}
		
		$temp = explode('/',$code);
		if (count($temp) == 1) {
			return isset($this->lang->$code) == true ? $this->lang->$code : ($this->oLang != null && isset($this->oLang->$code) == true ? $this->oLang->$code : '');
		} else {
			$string = $this->lang;
			for ($i=0, $loop=count($temp);$i<$loop;$i++) {
				if (isset($string->{$temp[$i]}) == true) $string = $string->{$temp[$i]};
				else $string = null;
			}
			
			if ($string == null && $this->oLang != null) {
				$string = $this->oLang;
				for ($i=0, $loop=count($temp);$i<$loop;$i++) {
					if (isset($string->{$temp[$i]}) == true) $string = $string->{$temp[$i]};
					else $string = null;
				}
			}
			return $string == null ? '' : $string;
		}
	}
	
	function reset() {
		$this->_id = null;
		$this->_name = null;
		$this->_placeholderText = null;
		$this->_module = null;
		$this->_content = null;
		$this->_required = false;
		$this->_theme = 'white';
		$this->_hideButtons = array();
		$this->_uploader = true;
	}
	
	function setId($id) {
		$this->_id = $id;
		
		return $this;
	}
	
	function setName($name) {
		$this->_name = $name;
		$this->_attachment->setWysiwyg($name);
		
		return $this;
	}
	
	function setModule($module) {
		$this->_module = $module;
		$this->_attachment->setModule($module);
		
		return $this;
	}
	
	function setPlaceholder($text) {
		$this->_placeholderText = $text;
		
		return $this;
	}
	
	function loadFile($files=array()) {
		$this->_attachment->loadFile($files);
		
		return $this;
	}
	
	function setContent($content) {
		$this->_content = $content;
		
		return $this;
	}
	
	function setRequired($required) {
		$this->_required = $required;
		
		return $this;
	}
	
	function setTheme($theme) {
		$this->_theme = $theme;
		
		return $this;
	}
	
	function getAttachment() {
		return $this->_attachment;
	}
	
	function setHeight($height) {
		$this->_height = $height;
		
		return $this;
	}
	
	function setUploader($uploader) {
		$this->_uploader = $uploader;
		
		return $this;
	}
	
	function setHideButtons($hideButtons=array()) {
		$this->_hideButtons = $hideButtons;
		
		return $this;
	}
	
	function setToolBarFixed($toolbarFixed) {
		$this->_toolBarFixed = $toolbarFixed;
		
		return $this;
	}
	
	/**
	 * 위지윅에디터를 사용하기 위한 필수요소를 미리 불러온다.
	 */
	function preload() {
		$this->IM->addHeadResource('script',$this->Module->getDir().'/scripts/wysiwyg.js.php');
		$this->IM->addHeadResource('style',$this->Module->getDir().'/styles/wysiwyg.css.php?theme='.$this->_theme);
	}
	
	function doLayout() {
		$this->_id = $this->_id == null ? uniqid('wysiwyg-') : $this->_id;
		$this->_name = $this->_name == null ? 'content' : $this->_name;
		$this->IM->addSiteHeader('script',$this->Module->getDir().'/scripts/wysiwyg.js.php');
		$this->IM->addSiteHeader('style',$this->Module->getDir().'/styles/wysiwyg.css.php?theme='.$this->_theme);
		
		$wysiwyg = '<textarea id="'.$this->_id.'" name="'.$this->_name.'" data-wysiwyg="true" data-module="'.$this->_module.'" data-uploader="'.($this->_uploader == true ? 'true' : 'false').'" data-minHeight="'.$this->_height.'"'.($this->_required == true ? ' data-required="required"' : '').''.($this->_placeholderText != null ? ' placeholder="'.$this->_placeholderText.'"' : '').'>'.($this->_content !== null ? $this->_content : '').'</textarea>'.PHP_EOL;
		$wysiwyg.= $this->_buildScript();
		
		echo $wysiwyg;
		
		if ($this->_uploader == true) {
			$this->_attachment->setId($this->_id.'-attachment');
			$this->_attachment->doLayout();
		}
		
		$this->reset();
	}
	
	function _buildScript() {
		$script = '<script>$(document).ready(function() { $("#'.$this->_id.'").wysiwyg(); });</script>'.PHP_EOL;
		
		return $script;
	}
}
?>