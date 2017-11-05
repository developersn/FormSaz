<?php

$mtime = explode( ' ', microtime() );
define('time_start',$mtime[1] + $mtime[0]);
define('memory_get_usage',memory_get_usage());

$_REQUEST = array_merge($_GET,$_POST);
if ( version_compare(PHP_VERSION, '5.3','<') )
	@set_magic_quotes_runtime(0); // Kill magic quotes

if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
	@set_time_limit(300);
	
set_error_handler('_exception_handler');

function _exception_handler($severity, $message, $filepath, $line)
{
	//header('Content-Type: text/html; charset=utf-8');
	$save = "# $severity: - ". date("Y-m-d H:i:s")." -> ". $_SERVER['REMOTE_ADDR']."\t-> ".$message."\n{$filepath} line {$line}\n\n";
	@A::pp()->profiler->addError(" $severity: $message\n{$filepath} line {$line}\n\n");
	$filename = BASEPATH.'protected/runtime/logs_'.date('Y-m-d').'.php';
	if(!file_exists($filename))
		$save2="<?php die ?>\n\n".$save;
	else
		$save2=$save;
	
		$f=fopen($filename,'a');
		fwrite($f,$save2);
		fclose($f);
		
}

class A
{
	private $_vars;
	private static $ins=null;
	
	private $config;
	
	protected $layout = 'site';
	
	
	function __get($key=1)
	{
		$x =  $this->_vars[$key];
		#if(empty($x))
		#	$x = new stdclass;
		
		return $x;
	}
	
	function __set($key,$val)
	{
		$this->_vars[$key]=$val;
	}
	
	
	
	function __construct()
	{
		
		$this->config = require_once dirname(__FILE__).'/../config.php';
		date_default_timezone_set($this->config['timeZone']) ;
		Jdate::init();
		$this->baseUrl = $this->config['baseUrl'];
		
		if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) 
		{ 
			if(isset($_GET)) 
				$_GET=$this->stripSlashes($_GET); 
			if(isset($_POST)) 
				$_POST=$this->stripSlashes($_POST); 
			if(isset($_REQUEST)) 
				$_REQUEST=$this->stripSlashes($_REQUEST); 
			if(isset($_COOKIE)) 
				$_COOKIE=$this->stripSlashes($_COOKIE); 
		} 

	
	
		if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"]=='on')
			$_ = "https://";
		else
			$_ = 'http://';
		if(isset($_SERVER['HTTP_HOST'])) 
            $_hostInfo=$_.$_SERVER['HTTP_HOST']; 
        else 
        { 
            $_hostInfo=$_.$_SERVER['SERVER_NAME']; 
        }
		
		//currentUrl
		$this->currentUrl = $_hostInfo.$_SERVER['REQUEST_URI'];
		$_ = explode('index.php',$_SERVER['SCRIPT_NAME']);
		
		
		
		if($this->baseUrl===null)
			$this->baseUrl = $_hostInfo .'/'. trim($_[0],'/');
	}
	
	
	
	static function pp()
	{
		
		if(self::$ins==NULL)
			self::$ins = new self;
		
		return self::$ins;
	}
	
	function run()
	{
		$conf = $this->config['components'];
		foreach((array) $conf as $key=>$val)
		{
			if( ! isset(A::pp()->{$key}))
			{
				A::pp()->{$key} = new $val['class'];
				
				foreach($val as $_k=>$_v)
				{
					if($_k !=='class')
						A::pp()->{$key}->$_k = $_v;
				}
				A::pp()->{$key}->init();
			}
		}
		
		//
		
		$controller = $this->router->controller;
		$action = $this->router->action;
		
		
		
		$cfile = BASEPATH."protected/controllers/{$controller}Controller.php";
		if( ! file_exists($cfile))
			die("controller $controller not found");
		include_once $cfile;
		$__ = "{$controller}Controller";
		
		$_con = new $__;
		
		if( ! method_exists($_con,"action{$action}"))
			die("action $action in $controller not found!");
		
		//$_con->{"action{$action}"}();
		call_user_func_array(array($_con,"action{$action}"),(array) $this->router->params);
	}
	
	function getPrivateKey()
	{
		$file = BASEPATH . 'protected/runtime/.privatecode';
		if( ! file_exists($file))
		{
			//A::pp()->profiler->addNotice('make new private key file');
			$f=fopen($file,'a');
			fwrite($f,sha1(mt_rand(1,9000).microtime(true)));
			fclose($f);
		}
		
		return file_get_contents($file);
	}
	
	
	
	function createUrl($str='')
	{
		return trim($this->baseUrl,'/') . "/?".$str;
	}
	
	
	
	function redirect($r = '',$c=302)
	{
		if(is_array($r))
		{
			$url = $this->createUrl($r[0]);
		}
		else
		{
			$url = $r;
		}
		//echo $url;
		if($c == 301)
			header("HTTP/1.1 301 Moved Permanently");
			
		header('Location: '.$url, true, $c); 
		die;
	}
	
	function refresh()
	{
		$this->redirect($this->currentUrl);
	}
}



function __autoload($class_name)
{
    $config = require dirname(__FILE__).'/../config.php';
	date_default_timezone_set($config['timeZone']) ;
	foreach($config['import'] as $row)
	{
		$f = BASEPATH . $row . "/{$class_name}.php";
		if(file_exists($f))
		{
			require_once $f;
			return ;
		}
	}
	
}

