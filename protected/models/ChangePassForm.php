<?php
class ChangePassForm extends CFormModel
{
	public $email,$verifyCode ;
	public $Rcaptcha;
	function rules()
	{
		return array(
			array('email','required'),
			array('email','email') ,
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() , 'message'=>'کد امنيتي نامعتبر است !'),
			array('Rcaptcha','checkRcaptcha'),
		);
	}
	
	function checkRcaptcha($att,$val)
	{
		if( ! RcaptchaWidget::check('register',$this->Rcaptcha))
			$this->addError('Rcaptcha','کد امنیتی اشتباه است ، لطفا دوباره وارد کنید !');
			
		
	}
	
	function attributeLabels()
	{
		return array('email'=>'ايميل','Rcaptcha'=>'');
	}
	
	function setToken()
	{
		$email = strtolower(strip_tags($this->email));
		$user = User::model()->find(array(
									'condition'=>'email=:m and status=1' ,
									'params'=>array(':m'=>$email),
									));
		if(empty($user))
			return ;
		
		//$user->token = Rh::randomChar(8,$this->email);
		//$user->save();
		
		Yii::app()->db->createCommand()
					->update('user',array(
						   'token'=>$token = Rh::randomChar(8,$user->id) ,
               ),'id=:id',array(':id'=>$user->id));
		
		$urlToken = Yii::app()->request->hostInfo.Yii::app()->createUrl("/xmain/tokenpass/{$user->last_activity}?token={$token}&u=" . Rh::numEncode($user->id).'&'.md5($token));
		//echo $urlToken;
		
		Rh::mail(Yii::app()->params['adminEmail'],$this->email,'تغيير کلمه',"براي تغيير کلمه عبور برروي لينک زير کليک کنيد ، در صورتيکه نيازي به تغيير رمز عبور نداريد اين ايميل را ناديده بگيريد.<br>" . CHtml::link($urlToken, $urlToken) );
		//Logs::model()->add('ss',$urlToken);
		return;
	}
	
	
	
}