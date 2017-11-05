<?php


return array(
	'timeZone' => 'Asia/Tehran' ,
	'basePath'=> dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'سيستم پرداخت اينترنتي آنلاين',
	'sourceLanguage' => 'en_us',
 'language'=>'fa_ir',
	
	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
		'application.models.model2.*',
		'application.models.forms.*',
		'application.models.bank.*',
		'application.components.*',
		'application.extensions.*' ,
		
		
	),

	/*'modules'=>array(
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
		
		
	),*/
	
	'defaultController'=>'xmain',
	
	// application components
	'components'=>array(
		'user'=>array(
			'class'=>'WebUser' ,
			'allowAutoLogin'=>true,
			'loginUrl'=>array('xmain/login'),
		),
		'request'=>array(
			'enableCookieValidation'=>true,
			'enableCsrfValidation' => false,
			'csrfTokenName'=>'__csrf__',
			
        ),

		'urlManager'=>array(
			'urlFormat'=>'path',
			//'showScriptName'=>false ,
			'rules'=>array(
							
				"manage24/<action:\w+>/<id:\d+>"=>"xadmin/<action>",
				"manage24/<action:\w+>"=>"xadmin/<action>",
				"manage24"=>"xadmin/index",
				
				"admin/<action:\w+>/<id:\d+>"=>"xadmin/<action>",
				"admin/<action:\w+>"=>"xadmin/<action>",
				"admin"=>"xadmin/index",
				
				
				
				'xmain/<action:\w+>/<id:\d+>'=>'xmain/<action>',
				'xmain/<action:\w+>'=>'xmain/<action>',
				'xadmin/<action:\w+>/<id:\d+>'=>'xadmin/<action>',
				'xadmin/<action:\w+>'=>'xadmin/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			),
		),
		
		
		'db'=>require(dirname(__FILE__).'/database.php'),
		
		
		
		'cache'=>array(
			'class'=>'system.caching.CFileCache',
			'directoryLevel'=> 0 ,
			//'connectionID'=>'db',
		),
		
		'sescache'=>array(
			'class'=>'system.caching.CFileCache',
			'cacheFileSuffix'=>'__sess__.bin',
			'keyPrefix'=>'sessi_' ,
			//'cachePath'=>'protected/runtime/sess' ,
		),	
		
		
		'session' => array (
			//'class' => 'system.web.CDbHttpSession',
			//'connectionID' => 'db',
			//'sessionTableName' => 'session',
			'timeout'=> 3600 ,
			
			//'class'=>'system.web.CCacheHttpSession' ,
			//'cacheID'=>'sescache' ,
		) ,
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'xmain/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					//'class'=>'CProfileLogRoute',
					'levels'=>'error, warning',
					
				),
				// uncomment the following to show log messages on web pages
				
			
				
			),
		),
		
		
	),

	'params'=>require_once(dirname(__FILE__).'/params.php'),
);