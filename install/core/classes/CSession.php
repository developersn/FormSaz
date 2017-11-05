<?php
class CSession  extends stdclass implements ComponentInterface
{
	function __construct()
	{
		ini_set('session.use_only_cookies', 1); 

		session_name(get_class($this) . md5($_SERVER['HTTP_USER_AGENT'])); 
		session_start();
		
	}
	
	function init()
	{
		
		$this->privateKey = A::pp()->getPrivateKey();
		if( ! empty($this->savePath))
		{
			$_ = BASEPATH.$this->savePath;
			
			session_save_path($_);
		}
		
		if( ! empty($this->reGenerate))
		{
			$reGenerate = $this->reGenerate;
			$old = $this->get('reGenerateTime');
			if(empty($old) or intval($old + $this->reGenerate)<time())
			{
			
				if ( ! session_regenerate_id(true))
					A::pp()->profiler->addNotice('Session_regenerated_id not work');
				$this->add('reGenerateTime',time());
			}
			
		}
		
	}
	
	function add($key,$val)
	{
		$_SESSION[$this->privateKey][$key] = $val;
		
		return $this->get($key)==$val;
	}
	function set($key,$val)
	{
		return $this->add($key,$val);
	}
	
	function get($key)
	{
		return isset($_SESSION[$this->privateKey][$key])?$_SESSION[$this->privateKey][$key]:NULL;
	}
	
	function destroy()
	{
		$_SESSION[$this->privateKey] = NULL;
		return  session_destroy();
	}
	

}