<?php
class CUser implements ComponentInterface
{
	public $isGuest = true;
	public $timeout = 2000 ;
	public $id = NULL;
	
	function init()
	{
		$this->id = $this->getID();
		$this->isGuest = $this->isGuest();
	}
	
	function setLogin( int $id,$username)
	{
		$x = sha1($id.$username.$_SERVER['HTTP_USER_AGENT']);
		$save = array(
			'hash'=>$x ,
			'username'=>$username ,
			'id'=>$id ,
			'timeend'=> time() + $this->timeout
		);
		
		return A::pp()->session->set('user',$save);
		
	}
	
	function logout()
	{
		A::pp()->session->set('user',0);
		return A::pp()->session->get('user')==0;
	}
	
	function isGuest()
	{
		$sess = A::pp()->session->get('user');
		if(empty($sess) or empty($sess['hash']) or empty($sess['username']) or empty($sess['id']) or empty($sess['timeend']))
		{
			return true;
		}
		
		if($sess['hash']!=sha1($sess['id'].$sess['username'].$_SERVER['HTTP_USER_AGENT']))
			return true;
		
		if(time()> $sess['timeend'])
		{
			$this->logout();
			return true;
		}
		
		$sess['timeend'] = time() + $this->timeout;
		A::pp()->session->set('user',$sess);
		return false;
	}
	
	function getID()
	{
		if($this->isGuest())
			return NULL;
		
		$sess = A::pp()->session->get('user');
		return $sess['id'];
	}
	
	function createHash($str = '')
	{
		for($i=0;$i<4000;$i++)
			$str = hash('sha256',$str);
		return $str;
	}
	
}