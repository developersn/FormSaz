<?php

class Controller extends CController
{
	public $layout='//layouts/site';
	public $menu=array();

	public $breadcrumbs=array();
	
	public $csrf ;
	
	public $db , $userId = 0;
	public function init()
	{
		$this->csrf = Yii::app()->request->getCsrfToken() ;
		Jdate::init();
		$this->db = Yii::app()->db ;
		if( ! Yii::app()->user->isGuest)
			$this->userId = (int) Yii::app()->user->id ;
			
			Yii::app()->clientScript->registerScript('jahanpay', "
		// powered by jahanpay_form version 1.0
		");
		
		
	}
	
	public function checkCsrf()
	{
		$_REQUEST = array_merge($_POST,$_GET);
		if(empty($_REQUEST['token']) or $_REQUEST['token']!=$this->csrf)
			throw new CHttpException(500,'CSRF Security!');
	}
	
	protected function currentUser()
	{
		static $user ;
		if (Yii::app()->user->isGuest)
			return NULL;
		else
		{
			if( ! empty($user))
				return $user;
		}
		 $user = User::model()->findByPk(Yii::app()->user->id);
		return $user;
	}
	
	
	public function actionLogout()
	{
		$this->checkCsrf();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	
}