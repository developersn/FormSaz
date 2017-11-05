<?php
class Controller extends CController
{
	
	
	public function checkCsrf()
	{
		$_REQUEST = array_merge($_POST,$_GET);
		if(empty($_REQUEST['token']) or $_REQUEST['token']!=$this->csrf)
			throw new CException('CSRF Security!');
	}
}