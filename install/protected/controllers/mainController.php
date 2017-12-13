<?php

class mainController extends Controller
{
	public $layout = 'main';
	
	function actionIndex()
	{
		$this->pageTitle = 'نصب اسکريپت';
		$out = $this->checkChmod();
		$this->render('index',compact('out'));
	}
	
	function checkChmod()
	{
		$index_dir  = pathinfo(BASEPATH.'../index.php');
		$index_dir = $index_dir['dirname'];
		$dir = array();
		
		$dir[] = $index_dir.'/assets';
		$dir[] = $index_dir.'/protected/runtime';
		$dir[] = $index_dir.'/protected/config';
		$dir[] = $dir[2].DIRECTORY_SEPARATOR.'lock';
		$dir[] = $dir[2].DIRECTORY_SEPARATOR.'database.php';
		
		$out = array();
		foreach($dir as $row)
		{
			$row = str_replace(array('install/../','install\../'),'',$row);
			$out[$row] = substr(sprintf('%o', fileperms($row)), -4);
		}
		
		return $out;

	}
	
	
	function actioncheckClass()
	{
		$this->pageTitle= 'بررسی نیازمندی ها';
		$this->checkCsrf();
		$out = $this->checkClass();
		$this->render('checkClass',compact('out'));
	}
	
	function checkClass()
	{
	
		
		$out['php version > 5.1'] = false;
		if(version_compare(PHP_VERSION,"5.1.0",">="))
			$out['php version > 5.1'] = true;
			
		$out['Reflection_extension'] = false;
		if(class_exists('Reflection',false))
			$out['Reflection_extension'] = true;
			
			
		$out['PCRE extension'] = false;
		if(extension_loaded("pcre"))
			$out['PCRE extension']=true;
			
		$out['SPL extension'] = false;
		if(extension_loaded("SPL"))
			$out['SPL extension']=true;
			
			
		$out['DOM extension'] = false;
		if(class_exists("DOMDocument",false))
			$out['DOM extension']=true;
			
			
		$out['PDO extension'] = false;
		if(extension_loaded('pdo'))
			$out['PDO extension']=true;
			
		$out['PDO MySQL extension'] = false;
		if(extension_loaded('pdo_mysql'))
			$out['PDO MySQL extension']=true;
			
	
			
		$out['GD extension'] = false;
		if(extension_loaded('gd'))
			$out['GD extension']=true;
		
		
		
		
		return $out;
		
	}
	
	
	//db
	
	function actionGetDb()
	{
		$this->pageTitle= 'اطلاعات دیتابیس';
		$this->checkCsrf();
		
		$this->render('getdb');
	}
	
	function actionCheckDb()
	{
	
		$this->checkCsrf();
		$get = array_map('strip_tags',$_GET);
		
		try 
		{
			$dbh = new PDO("mysql:host={$get['dbhost']};dbname={$get['dbname']}", $get['dbuser'], $get['dbpass']);
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() ;
			die();
		}
		
		if( ! $dbh) die('error in db connection');
		
		$write = "<?php return array(
			'connectionString' => 'mysql:host={$get['dbhost']};dbname={$get['dbname']}',
			'username' => '{$get['dbuser']}',
			'password' => '{$get['dbpass']}',
			
			'charset' => 'utf8',
			'emulatePrepare' => true,
			'schemaCachingDuration'=>86000,
			
			'enableProfiling'=>true ,
			'enableParamLogging'=>true ,
		);";
		
		$af = file_put_contents(BASEPATH.'../protected/config/database.php',$write);
		if( ! $af)
			die('error in make file '.BASEPATH.'../protected/config/database.php');
		die('200');

	}
	
	
	function actionDoQuery()
	{
		$this->pageTitle = 'کوئری دیتابیس';
		$this->checkCsrf();
		$dbconfig = require_once BASEPATH.'../protected/config/database.php';
		
		try
		{
			$pdo = new PDO($dbconfig['connectionString'], $dbconfig['username'], $dbconfig['password']);
			$pdo->query("set names utf8")->execute();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() ;
			die();
		}
		
		$query = array();
		$query[] = "CREATE TABLE IF NOT EXISTS `form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `msg` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `amount` bigint(20) unsigned NOT NULL DEFAULT '0',
  `key1` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `key2` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `key3` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `key4` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;";

$query[] = <<<eot
INSERT INTO `form` (`id`, `title`, `content`, `msg`, `status`, `amount`, `key1`, `key2`, `key3`, `key4`, `extra`) VALUES
(1, 'کمک به سایت', '<p>با سلام<br />\r\nجهت کمک به سایت مبلغ دلخواهی واریز کنید .</p>\r\n', '<p>با تشکر از پرداخت شما<br />\r\nاسم شما در لیست افرادی که به سایت کمک کردند درج خواهد شد .</p>\r\n', 1, 0, 'a:2:{s:4:"name";s:28:"آدرس وبسایت شما";s:3:"req";i:1;}', 'a:2:{s:4:"name";s:29:"دلیل کمک به سایت";s:3:"req";i:0;}', 'a:2:{s:4:"name";s:0:"";s:3:"req";i:0;}', NULL, NULL),
(2, 'دانلود فایل', '<p>جهت دانلود فایل باید مبلغ 5000 تومان پرداخت کنید . بعد از پرداخت لینک دانلود به شما ارائه میشود .</p>\r\n', '<p>با تشکر از پرداخت شما لینک دانلود</p>\r\n\r\n<p>http://example.ir/get.zip</p>\r\n\r\n<p>&nbsp;</p>\r\n', 1, 5000, 'a:2:{s:4:"name";s:35:"نحوه آشنایی با سایت";s:3:"req";i:1;}', 'a:2:{s:4:"name";s:0:"";s:3:"req";i:0;}', 'a:2:{s:4:"name";s:0:"";s:3:"req";i:0;}', NULL, NULL);
eot;

$query[] = "CREATE TABLE IF NOT EXISTS `form_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_id` int(10) unsigned NOT NULL,
  `form_id` int(10) unsigned NOT NULL,
  `sn_au` varchar(255) DEFAULT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  `msg` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `values` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_id` (`gateway_id`,`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

$query[] = "INSERT INTO `form_item` (`id`, `gateway_id`, `form_id`, `sn_au`, `amount`, `ip`, `time`, `status`, `email`, `phone`, `name`, `msg`, `values`, `extra`) VALUES
(1, 1, 2, 'none', 5000, '::1', 1413916259, 2, 'programmer@developerapi.net', '', 'رضا', 'ممنون از شما', '--\nنحوه آشنایی با سایت : گوگل \n', NULL);
";

$query[] = "CREATE TABLE IF NOT EXISTS `gateway` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'پرداخت آنلاین',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `api` varchar(255) NOT NULL,
  `webservice` tinyint(1) NOT NULL DEFAULT '1',
  `param1` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `param2` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";


$query[] = "INSERT INTO `gateway` (`id`, `title`, `type`, `api`, `webservice`,`param1`, `param2`, `status`) VALUES
(1, 'بانک سامان', 2, 'gtd12123u7', 1,NULL, NULL, 1);";

$query[] = "CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(255) NOT NULL,
  `msg` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";


$query[] = "CREATE TABLE IF NOT EXISTS `logslogin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ip` varchar(100) NOT NULL,
  `useragent` varchar(255) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";


$query[] = "CREATE TABLE IF NOT EXISTS `opt` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `opt_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `opt_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opt_key` (`opt_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";



$query[] = "INSERT INTO `opt` (`id`, `opt_key`, `opt_value`) VALUES
(1, 'tryLogin', '10'),
(2, 'minuteBanLogin', '60'),
(3, 'ipBanLogin', '127.0.0.2\r\n'),
(4, 'def_form', '2');";


$query[] = "CREATE TABLE IF NOT EXISTS `trylogin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `try` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_ban` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `end_ban` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;";


$query[] = "CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` char(64) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `access_level` varchar(7) NOT NULL DEFAULT 'buyer',
  `mobile` varchar(20) NOT NULL DEFAULT '0918',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '1372446725',
  `start_time` int(10) unsigned NOT NULL DEFAULT '1372446725',
  `ip` varchar(100) NOT NULL DEFAULT '127.0.0.1',
  `token` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `extra` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";



$query[] = "INSERT INTO `user` (`id`, `email`, `password`, `name`, `access_level`, `mobile`, `last_activity`, `start_time`, `ip`, `token`, `status`, `extra`) VALUES
(1, 'info@example.com', '2dd511e1be0a7cffa52b76e04e116953bd992f887a7934b16bb53e7024bdb5c8', 'admin (مدیریت کل)', 'admin', '0918', 1413664205, 1372446725, '::1', '', 1, 'none');
";
		
		$out = array();
		
		foreach($query as $row)
		{
			$stm = $pdo->prepare($row);
			if($stm->execute())
				$out[$row] = true;
			else
				$out[$row] = false;
		}
		
		$this->render('doquery',compact('out'));
	}
	
	function actionEmail()
	{
		$this->pageTitle= 'ایمیل مدیریت';
		$this->checkCsrf();
		$dbconfig = require_once BASEPATH.'../protected/config/database.php';
		
		try
		{
			$pdo = new PDO($dbconfig['connectionString'], $dbconfig['username'], $dbconfig['password']);
			$pdo->query("set names utf8")->execute();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() ;
			die();
		}
		
		
		$this->pageTitle= 'ایمیل مدیریت را وارد کنید';
		$this->checkCsrf();
		
		if( ! empty($_POST['email']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$email = strip_tags($_POST['email']);
			$stm = $pdo->prepare("update user set email=:email where id=1");
			$stm->bindValue(':email',$email);
			if($stm->execute())
				$this->render('finish',compact('email'));
		}
		else
			$this->render('email');
	}
	
	
	
	
}