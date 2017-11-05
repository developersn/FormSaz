<?php

class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;


	public function rules()
	{
		return array(
			
			array('username, password', 'required','message'=>'اجباري است !'),
			array('username','email','message'=>'آدرس ایمیل معتبری بنویسید !') ,
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'مرا به خاطر بسپار',
			'username'=>'ایمیل ' ,
			'password'=>'پسورد ' ,
		);
	}


	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
			{
				$this->addError('password','نام کاربری یا کلمه عبور اشتباه است ، یا حساب شما غیرفعال است !');
				$this->addError('username','');
			}
		}
	}


	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*10 : 0; // 10 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
