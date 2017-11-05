<?php
class CCache extends stdclass implements ComponentInterface
{
	function init()
	{
		if( ! file_exists(BASEPATH.$this->path.'index.html'))
		{
			@mkdir(BASEPATH.$this->path, 0777);
			$fp = fopen(BASEPATH.$this->path.'index.html', "wb");
			if (!$fp) 
				die('eroor in make cache dir file in file ~ `'. __FILE__ .'` and line `'.__LINE__ .'`');
			fwrite($fp, 'cache its work :) <br> ');
			fclose($fp);
		}
	}
	
	
	public function hash($str='')
	{
		return $this->prefix.preg_replace('/[^a-zA-Z0-9]/','',$str).md5($str);
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
		$fp = fopen(BASEPATH.$this->path.$fileName, "wb");
		if (!$fp) 
			die('eroor in make cache dir file in file ~ `'. __FILE__ .'` and line `'.__LINE__ .'`');
		fwrite($fp, '<?php die; ?>'.serialize($data));
		fclose($fp);
		
		return file_exists(BASEPATH.$this->path.$fileName);
	}
	
	public function get($key=1)
	{
		$fileName = self::hash($key).'.php';
		$f = BASEPATH.$this->path.$fileName;
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
		if( ! file_exists(BASEPATH.$this->path.$fileName))
			return true;
			
		@unlink(BASEPATH.$this->path.$fileName);
		if(file_exists(BASEPATH.$this->path.$fileName))
			return false;
		return true;
	}
	
	public function flush()
	{
		$path = BASEPATH.$this->path;

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