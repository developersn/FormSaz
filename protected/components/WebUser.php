<?php
class WebUser extends CWebUser
{
	
	public function setFlash($key,$val,$default=null)
	{			
		$session=new CHttpSession;
		$session->open();
		$session['flash'.$key] = $val; 
	}
	
	public function hasFlash($key)
	{
		$session=new CHttpSession;
		$session->open();
		if( ! empty($session['flash'.$key]))
			return true;
		return false;
	}
	
	public function getFlash($key,$defaultValue=null,$delete=false) 
	{
		if($this->hasFlash($key))
		{
			$session=new CHttpSession;
			$session->open();
			
			$ret =$session['flash'.$key];
			$session['flash'.$key] = NULL;
			return $ret;
		}
		
		return '';
		
	}
	
	
}

class WebUser2 extends CWebUser
{
	
	static public function keyP($str='')
	{
		
		return Rh::user_ip().'flash_'.$str;
	}
	
	public function setFlash($key,$val,$default=null)
	{
		
		/*$all = Yii::app()->cache->get('_flash'.Rh::user_ip());
		$all[$key] = $val;
		Yii::app()->cache->delete('_flash'.Rh::user_ip());
		Yii::app()->cache->set('_flash'.Rh::user_ip(), $all ,200);*/
		ExtraCache::ins()->add(self::keyP($key),$val,2000);
	}
	
	public function hasFlash($key)
	{
		/*$all = Yii::app()->cache->get('_flash'.Rh::user_ip()) ;
		if( ! empty($all[$key]))
			return true;
		return false;*/
		$e = ExtraCache::ins()->get(self::keyP($key));
		return ! empty($e);
	}
	
	public function getFlash($key,$defaultValue=null,$delete=false) 
	{
		if($this->hasFlash($key))
		{
			$ret = ExtraCache::ins()->get(self::keyP($key));
			ExtraCache::ins()->delete(self::keyP($key));
			return $ret;
		}
		
		return '';
		
	}
	
	
}