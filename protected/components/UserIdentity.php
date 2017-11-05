<?php
class UserIdentity extends CUserIdentity
{
	private $_id = NULL;
	public $accessLevel = NULL;
	public function authenticate()
	{
		self::accessLogin();
		
		$user = User::model()->find('email=:user and status=1',array(':user'=>strip_tags($this->username)));
		$hash = self::createHash($this->password) ;
		if(empty($user))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
			self::tryLogin();
		}
		elseif($user->password != $hash)
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
			self::tryLogin();
		//لاگین ناموفق
			Logslogin::model()->add($user->id,0);
		}
		else
		{
			//لاگین موفق
			Logslogin::model()->add($user->id,1);
			$this->_id = $user->id;
			$this->username = $user->email;
			$this->errorCode=self::ERROR_NONE;
			Yii::app()->session['last_activity'] = $user->last_activity ;
			Yii::app()->session['last_ip'] = $user->ip ;
			
			//update last activity
			Yii::app()->db->createCommand()->update('user',array('last_activity'=>intval(time()) , 'ip'=>Rh::user_ip()),"id={$user->id}");
			
		}
		return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	
	static public function createHash($str = '')
	{
		for($i=0;$i<4000;$i++)
			$str = hash('sha256',$str);
		return $str;
	}
	
	static private function accessLogin()
	{
		//bans
		$ipBanLogin = Options::ins()->get('ipBanLogin');
		if( ! empty($ipBanLogin))
		{
			$ipBanLogin .= ',';
			$ipBan = explode(',',$ipBanLogin);
			$ipBan = array_map('trim',$ipBan);
			if(in_array(Rh::user_ip() , $ipBan))
			{
				Logs::model()->add('لاگین با آی پی مسدود','جلوگیری از لاگین کاربر به جهت تطابق با آی پی های مسدود ثبت شده .');
				throw new CHttpException(401,'آی پی شما در بلاک لیست قرار دارد ، شما مجاز به لاگین نیستید !');
			}
		}
		
		// ban try login
		Yii::app()->db->createCommand()
			->delete('trylogin','end_ban <= ?',array(time()));
		
		$try = Trylogin::model()->find('is_ban=1 and ip=?',array(Rh::user_ip()));
		if( ! empty($try))
		{
			$min = Options::ins()->get('minuteBanLogin') ;
			Logs::model()->add('لاگین ناموفق' , "آی پی کاربر به جهت تعدد لاگینهای ناموفق به مدت $min دقیقه مسدود شد ." );
			throw new CHttpException(401,'آی پی شما مسدود شد . شما مجاز به لاگین نیستید !');
		}

	}
	
	static private function tryLogin()
	{
		$minBan = (int) 60 * Options::ins()->get('minuteBanLogin');
		$try = Trylogin::model()->findByAttributes(array('ip'=>Rh::user_ip()));
		if(empty($try))
		{
			$try = new Trylogin;
			$try->try = 1;
			$try->is_ban=0;
		}
		else
		{
			$try->try++ ;
			$try->is_ban= ($try->try >= Options::ins()->get('tryLogin')) ? 1 : 0;
		}
		
		$try->ip = Rh::user_ip();
		$try->time = time();
		$try->end_ban = time() + $minBan;
		$try->save();
	}
}