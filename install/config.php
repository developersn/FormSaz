<?php

return array(
	'timeZone' => 'Asia/Tehran' ,
	'baseUrl'=>NULL , // if is null set automatic alse use http://example.com
	'import'=>array(
		'core/classes',
		'protected/models',
		'protected/components',
	),
	
	'components'=>array(
		'router'=>array(
			'class'=>'CRouter',
			'defaultAction'=>'index',
			'defaultController'=>'main',
		),
		
		'profiler'=>array(
			'class'=>'CProfiler'
		),
		
		
		'cache'=>array(
			'class'=>'CCache',
			'path'=>'protected/runtime/cache/',
			'prefix'=>'c_'
		),
		

		
		
	),
);