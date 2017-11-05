<?php
class CController extends stdclass
{
	public $csrf ,$pageTitle='';
	function __construct()
	{
		$this->csrf = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].dirname(__FILE__));
	}
	
	
	public $layout = 'site';
	function createUrl($str='')
	{
		return A::pp()->createUrl(A::pp()->router->controller."/".$str);
	}
	
	function render($file='', array $arr = array())
	{
		$v = new CView;
		
		if(is_array($file))
		{
			//extract($file);
			$v->arr = $file;
			$v->setFile(BASEPATH . "/protected/views/".strtolower(A::pp()->router->controller)."/".strtolower(A::pp()->router->action).".php");
		}
		elseif($file[0]=='/' and $file[1]=='/')
		{
			//extract($arr);
			$v->arr = $arr;
			$v->setFile(BASEPATH . "/protected/views/".strtolower($file).".php");
		}
		else
		{
			//extract($arr);
			$v->arr = $arr;
			$v->setFile(BASEPATH . "/protected/views/".strtolower(A::pp()->router->controller)."/".strtolower($file).".php");
		}
		
		
		$content = $v;
		include_once BASEPATH . "/protected/views/layouts/".$this->layout.'.php';
	}
	
	function renderPartial($file,array $arr = array())
	{
		if(is_array($file))
		{
			extract($file);
			include BASEPATH . "/protected/views/".strtolower(A::pp()->router->controller)."/".strtolower(A::pp()->router->action).".php";
		}
		elseif($file[0]=='/' and $file[1]=='/')
		{
			extract($arr);
			include BASEPATH . "/protected/views/".strtolower($file).".php";
		}
		else
		{
			extract($arr);
			include BASEPATH . "/protected/views/".strtolower(A::pp()->router->controller)."/".strtolower($file).".php";
		}
			
	}
	
	function redirect($r = '')
	{
		return A::pp()->redirect($r);
	}
	
	function refresh()
	{
		A::pp()->refresh();
	}
}