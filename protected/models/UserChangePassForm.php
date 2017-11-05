<?php
class UserChangePassForm extends CFormModel
{
	public $old ,$password ,$repass ;
	
	function rules()
	{
		return array(
			array('old,password,repass','required'),
			array('old','checkPass'),
			array('password','length','min'=>7) ,
			array('repass','compare','compareAttribute'=>'password') ,
		);
	}
	
	function attributeLabels()
	{
		return array(
			'old'=>'کلمه عبور قبلی',
			'password'=>'کلمه عبور جدید',
			'repass'=>'تکرار کلمه عبور',
		);
	}
	
	function checkPass($att,$val)
	{
		$pass = UserIdentity::createHash($this->old);
		$user = User::model()->findByPk(Yii::app()->user->id);
		if($pass !=$user->password)
			$this->addError('old','کلمه عبور قبلی نادرست است !');
	}
	
	function save()
	{
		$pass = UserIdentity::createHash($this->password);
		/*$user = User::model()->findByPk(Yii::app()->user->id);
		$user->password = $pass;*/
		$af = 0;
		$af += Yii::app()->db->createCommand()->update('user' ,array('password'=>$pass) ,'id='. intval(Yii::app()->user->id));
		return (boolean) $af;
	}
}