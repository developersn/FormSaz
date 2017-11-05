<?php
class CView  extends CController implements ComponentInterface
{
	public $arr = array();
	function init()
	{
	
	}
	
	function setFile($file=NULL)
	{
		
		static $_;
		if($file!==NULL)
			$_ = $file;
		
		return $_;
	}
	
	function __toString()
	{
		extract($this->arr);
		include_once $this->setFile();
		return '';
	}
}