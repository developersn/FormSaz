<?php

class ExtraCache
{
	static private $ins=null;
	
	static private $perfix = '';
	static private $cacheDir = null;
	
	
	static public function ins()
	{
		if(self::$cacheDir===null)
			self::$cacheDir = dirname(__FILE__).'/../runtime/extracache/';
			
		if( ! file_exists(self::$cacheDir))
		{
			@mkdir(self::$cacheDir, 0777);
			$fp = fopen(self::$cacheDir.'index.html', "wb");
			if (!$fp) 
				die('eroor in make cache dir file in file ~ `'. __FILE__ .'` and line `'.__LINE__ .'`');
			fwrite($fp, 'cache its work :) <br> ');
			fclose($fp);
		}
			
		if(self::$ins===null)
			self::$ins = new self;
		return self::$ins;
	}
	
	public function setPerfix($str = '')
	{
		self::$perfix = strip_tags($str);
		return $this;
	}
	
	public function hash($str='')
	{
		return self::$perfix.''.preg_replace('/[^a-zA-Z0-9]/','',$str).md5($str);
	}
	
	public function set($_1=1 , $_2=1 , $_3=3600)
	{
		return $this->add($_1,$_2,$_3);
	}
	
	public function add($key=1,$val=1 ,$expire=3600)
	{
		self::delete($key);
		$data = array(
			'key'=>$key ,
			'val'=>$val ,
			'expire'=>time() + $expire ,
		);
		
		$fileName = self::hash($key).'.php';
		$fp = fopen(self::$cacheDir.$fileName, "wb");
		if (!$fp) 
			die('eroor in make cache dir file in file ~ `'. __FILE__ .'` and line `'.__LINE__ .'`');
		fwrite($fp, '<?php die; ?>'.serialize($data));
		fclose($fp);
		
		return file_exists(self::$cacheDir.$fileName);
	}
	
	public function get($key=1)
	{
		$fileName = self::hash($key).'.php';
		$f = self::$cacheDir.$fileName;
		if( ! file_exists($f))
			return null;
		
		$data = file_get_contents($f);
		if(empty($data))
			return NULL;
		
		$data = str_replace('<?php die; ?>','',$data);
		$data = unserialize($data);
		
		if(empty($data['expire']) or $data['expire']<time())
		{
			self::delete($key);
			return NULL;
		}
		
		if(empty($data['key']) or $data['key']!=$key)
		{
			self::delete($key);
			return NULL;
		}
		
		return $data['val'];

	}
	
	public function delete($key=1)
	{
		$fileName = self::hash($key).'.php';
		if( ! file_exists(self::$cacheDir.$fileName))
			return true;
			
		@unlink(self::$cacheDir.$fileName);
		if(file_exists(self::$cacheDir.$fileName))
			return false;
		return true;
	}
	
	public function flush()
	{
		$path = self::$cacheDir;

		if(($handle=opendir($path))===false) 
			return; 
		while(($file=readdir($handle))!==false) 
		{ 
			if($file[0]==='.') 
				continue; 
			$fullPath=$path.DIRECTORY_SEPARATOR.$file; 
			@unlink($fullPath); 
		} 
		closedir($handle); 

	}
	
	
}