<?php
class CRouter implements ComponentInterface
{
	public $defaultAction , $defaultController;
	
	//return
	public $controller,  $action , $params;
	function init()
	{
		$this->controller = $this->defaultController;
		$this->action = $this->defaultAction;
		
		$arr = array_keys($_GET);
		if(isset($arr[0]))
		{
			$url = $arr[0];
			$url = explode('/',trim($url));
			if(isset($url[0]))
			{
				$this->controller = $url[0];
				unset($url[0]);
			}
			
			if(isset($url[1]))
			{
				$this->action = $url[1];
				unset($url[1]);
			}
			
			$this->params = $url;
			
		}
	}
	
	function createUrl($in=NULL)
	{
		//return 
	}
}