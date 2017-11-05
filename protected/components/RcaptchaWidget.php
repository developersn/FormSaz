<?php

class RcaptchaWidget extends CWidget
{
	public  $key='';
	public 	$url = NULL;
	
	
	public function init()
	{	
		parent::init();
		if($this->url ===null)
			$this->url = Yii::app()->baseUrl.'/captcha/index.php';
		
		if( ! strpos('?',$this->url))
			$start = '?';
		else
			$start = '&';
			
		$this->url .=$start.'key='.$this->key.'&rand='.time();
	}
	
	public function run()
	{
		$id = 'codecaptcha0000000rezaworkshop_ir00000000'.md5($_SERVER['REMOTE_ADDR']);
		$js = "
		
 function reload()
 {
	var rand = Math.floor(Math.random()*300) ;
	
	document.getElementById('{$id}').innerHTML = \"<img width=90 height=30 onclick='reload()' style='cursor:pointer' title='برای بارگذاری مجدد کلیک کنید' alt='plz wait ...' src='{$this->url}\" + rand + \"' />\";
 }

		";
		//Yii::app()->clientScript->registerScript('rcaptcha'.$id, $js);
		$out = "<div id='{$id}'>
		  <img width=90 height=30 alt='plz wait ...' src='{$this->url}' onclick='reload()' style='cursor:pointer' title='برای بارگذاری مجدد کلیک کنید' />
		 </div>
 
<!--<a  onclick='reload()' style='cursor:pointer'>بارگذاري مجدد</a>-->";

		echo "<script>{$js}</script> {$out}";
	}
	
	static public function check($key='',$val='')
	{
		 $key .= $_SERVER['REMOTE_ADDR'];
		 $val2=ExtraCache::ins()->setPerfix('captcha')->get($key);
		 ExtraCache::ins()->setPerfix('captcha')->delete($key);
		 
		return $val==$val2;
	}
	
	 

}