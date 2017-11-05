<?php

class Rh
{
	
	static private $ins = null ;
	
	
	
	static public function getInstance()
	{
		if(is_null(self::$ins))
			self::$ins = new self;
			
		return self::$ins;
	}
	
	static public function is_numeric($int = ' ')
	{
		if($int<0) return false;
		return ctype_digit($int);
	}
	
	static public function reset($x='')
	{
		return '';
	}
	
	static public function big_intval($str ='')
	{
		$str = str_replace('-','',$str);
		$str = preg_replace('@([^0-9])@','',$str);
		$str = ltrim($str,0);
		if(empty($str))
			$str = 0;
		return $str;
	}
	
	static public function is_email($x=null)
	{
		return filter_var($x, FILTER_VALIDATE_EMAIL);
	}
	
		
	/**
* Safe_html function
* Convert safe html code to insert to database
*
* @param $html string 
* @return string
*/
	static public function safe_html($html=null)
	{
		return CHtml::encode(self::str_fix($html));	
	}
	
	

	/** 
* Time Left function
*
*	Example
*		$x = 1332140945 ;
*		echo time_left($x);
* @param $ts int timestamp's post 
* @return string time left like 3mahe ghabl
*/
	static public function time_left($ts = null)
	{
		if(!$ts)
			return '';
			
		$time = time();
			$t = $time-$ts;
			
		if(intval($t) < 0)
			return 'آینده';
			
		if(floor($t/31536000) >= 1 )
			$out = floor($t/31536000).' سال قبل';
		elseif(floor($t/2592000) >= 1)
			$out = floor($t/2592000).' ماه قبل';
		elseif(floor($t/604800) >= 1)
			$out = floor($t/604800).' هفته قبل';
		elseif(floor($t/86400) >= 1)
			$out = floor($t/86400).' روز قبل';
		elseif(floor($t/3600) >=1)
			$out = floor($t/3600).' ساعت پیش';
		elseif(floor($t/60) >= 1)
			$out = floor($t/60).' دقیقه پیش';
		else
			$out = $t.' ثانیه قبل';
	return $out;
	}
	/**
* str_fix function
* change arabic char to farsi and change farsi number to standard number
*
* @param $input string 
* @param $strim bool  use trim function or not!
* @return string
*/
	static public function str_fix($input = '',$trim=true)
	{
		$arabic = array("ي", "ك", "٤", "٥", "٦");
		$farsi = array("ی", "ک", "4", "5", "6");
		$out = str_replace($arabic,$farsi,$input);	
		
		$farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
		$english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
		$out = str_replace($farsi_array, $english_array, $out);
			if($trim)
				$out = trim($out);
		return $out;
	}
	
	static public function url_fix($str='')
	{
		$str = urldecode($str);
		$str = trim($str);
		$str = strip_tags($str);
		$str = str_replace(' ','-',$str);
		$str = str_replace('_','-',$str);
		
		$str = self::str_fix($str);
		
		/*$pattern = '/[^a-zA-Z0-9آالبیسشپگکمنتچجحخهعغفقثصضوئءدذرزژطظ]+/';*/
		$pattern = '/[^a-zA-Z0-9]+/';
		$str = preg_replace($pattern,'-',$str);
		
		$str = str_replace('---------','-',$str);
		$str = str_replace('--------','-',$str);
		$str = str_replace('-------','-',$str);
		$str = str_replace('------','-',$str);
		$str = str_replace('-----','-',$str);
		$str = str_replace('----','-',$str);
		$str = str_replace('---','-',$str);
		$str = str_replace('--','-',$str);
		$str = trim($str,'-');

		$str = htmlspecialchars($str, ENT_QUOTES);
		
		return $str;
	}

/**
* user_ip
*
* @return string - user ip address
*/	
	static public function user_ip()
	{
		$out = '0.0.0.0';
		/*if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			$out =$_SERVER['HTTP_CLIENT_IP'];
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			$out = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else*/	
			$out = $_SERVER['REMOTE_ADDR'];
			
		$out = strip_tags($out);
		return self::safe_html($out);
	}
	
	static public function mail($from='',$to = '' , $subj ='email' , $body = 'test')
	{
		$sitetitle = Yii::app()->name;
		$rand = self::randomChar(40,$to.$body);
		$name='=?UTF-8?B?'.base64_encode($from).'?=';
		$subject='=?UTF-8?B?'.base64_encode($subj).'?=';
		$headers="From: $name <{$from}>\r\n".
					"Reply-To: {$from}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/html; charset=UTF-8";
		$now = self::date();
		$send = @mail($to,$subject,"<center><div style='font-family:tahoma;font-size:11px;width:700px;background:#F3F3F3;padding:4px;direction:rtl;border:5px solid #7D3E94;
		text-align:right;border-radius:2px;color:#272727;text-shadow:1px 1px 0 white'>
		<div style='font-weight:bold;border-radius:2px;border-bottom:1px solid white;margin:4px;padding:4px;text-align:center'>{$subj}
		
		</div> 

		{$body}
		<br> {$now}
		
		</div>
		<span style='font-size:11px;font-family:tahoma'>{$sitetitle}</span> 
		</center>
		<!--{$rand}-->
		",$headers);
		return ! empty($send) ? TRUE : FALSE;
	}
	
	
	
	static public function randomChar($max=8 , $salt='')
	{
		$max = (int) $max ;
		$str = $salt . time() . mt_rand(1,80). $max . $_SERVER['REMOTE_ADDR'] .microtime(true);
		$random = md5($str);
		for(;;)
		{
			$random .= md5($random);
			$random = str_replace(array(1,2,3,4,5,6,7,8,9,0),array('z','x','c','v','b','n','m','l','k','j'),$random);
			if(strlen($random) >= $max)
				break;
		}
		return substr($random , 0 , $max);
	}
	
	static public function randomNum($max = 8,$salt=1)
	{
		$max = (int) $max;
		
		$num = preg_replace('@([^1-9])@','',md5($salt).self::randomChar($max*3).time());
		do
		{
			$num .= preg_replace('@([^0-9])@','',md5($num)) ;
			if(strlen($num) >= $max)
				break;
		}while(true);
		
		return substr($num , 0 , $max);
	}
	
	static public function date($time = NULL)
	{
		Jdate::init();
		if(empty($time))
			$time = (int) time();
		return jdate('Y-m-d H:i',$time);
	}
	
	static public function numEncode($int=0)
	{
		$num = array(1,2,3,4,5,6,7,8,9,0);
		$char = array('q','a','z','x','s','w','e','d','c','v');
		return str_replace($num,$char,$int);
	}
	
	static public function numDecode($str='')
	{
		$num = array(1,2,3,4,5,6,7,8,9,0);
		$char = array('q','a','z','x','s','w','e','d','c','v');
		return str_replace($char,$num,$str);
	}
	
	static public function file_get_contents($url,$timeout=5)
	{
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);

		// display file
		return $file_contents;
	}
	
	
	static function mktime($date ='16/04/1392',$time='10:25')
	{
		$_ = explode('/',$date);
		$_ = array_map('intval',$_);
		
		$__ = explode(':',$time);
		$__ = array_map('intval',$__);
		
		Jdate::init();
		return jmktime($__[0],$__[1],date('s'),$_[1],$_[0],$_[2]) ;
	}
}