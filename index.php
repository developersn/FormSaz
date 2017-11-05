<?php
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors','On');

$config=dirname(__FILE__).'/protected/config/main.php';

//defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once dirname(__FILE__).'/yii/yii.php';
Yii::createWebApplication($config)->run();
