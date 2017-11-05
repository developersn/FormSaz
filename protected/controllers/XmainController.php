<?php
class XmainController extends Controller
{
	public $layout = '//layouts/site';
	
	public function actions()
	{
		$color = array(	0x83B41A ,0x608413  ,0xE4644B ,0x7A7A7A ,);
		return array(
			'captcha'=>array(
				'class'=>'CaptchaAction',
				'backColor'=>0xFAFAFA,
				'foreColor' => $color[mt_rand(0,count($color)-1)] ,
			),
		);
	}
	/* error */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->renderPartial('error', $error);
		}
	}
	
	function actionIndex()
	{
		$id = Options::ins()->get('def_form');
		$this->actionForm($id);
	}
	
	function actionList()
	{
		$this->pageTitle = 'لیست فرم های پرداخت';
		$items = Form::model()->findAll(array(
			'order'=>'id desc' ,
			'condition'=>'status=1'
		));
		
		$this->render('list',compact('items'));
	}
	
	
	public function actionLogin()
	{
		$this->pageTitle = 'ورود به سایت';
		if ( ! Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->homeUrl);
			
		$model=new LoginForm;
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			
			if($model->validate() && $model->login())
			{
				$this->redirect(array('xadmin/index'));
			}
		}
		
		$this->render('login',array('model'=>$model));
	}
	
	

	
	
	public function actionLostPassword()
	{
		if( ! Yii::app()->user->isGuest)
			$this->redirect('index');
		$model = new ChangePassForm ;
		if(isset($_POST['ChangePassForm']))
		{
		$model->attributes=$_POST['ChangePassForm'];
			if($model->validate())
			{
				$model->setToken();
				Yii::app()->user->setFlash('pass','لطفاً ایمیل خود را بررسی کنید .');
				//$this->refresh();
			}
		}
		$this->render('lostpass',array('model'=>$model));
	}
	
	/*token pass*/
	public function actionTokenPass($id=0)
	{
		if( ! Yii::app()->user->isGuest)
			$this->redirect(array('xmain/index'));
			
		$last_activity = (int) Rh::big_intval($id);
		if(empty($_GET['token']) or empty($_GET['u']) or empty($last_activity))
			throw new CHttpException(404,'آدرس مورد نظر یافت نشد !');
			
		$token = strip_tags($_GET['token']);
		$uid = (int) Rh::numDecode($_GET['u']);
		
		$user = User::model()->find(array(
									'condition'=>'last_activity=:l and id=:uid and token=:t and status=1 ',
									'params'=>array(
										':l'=>$last_activity ,
										':uid'=>$uid ,
										':t'=>$token ,
									)));
		if(empty($user))
			throw new CHttpException(404,'آدرس مورد نظر یافت نشد !');
		
		$pass = Rh::randomChar(12,$uid);
		$user->token = NULL;
		$user->password = UserIdentity::createHash($pass);
		
		Yii::app()->db->createCommand()->update('user',array(
			'token'=>'' ,
			'password'=>UserIdentity::createHash($pass)
		),'id='.$user->id);
		
		Rh::mail(Yii::app()->params['adminEmail'],$user->email,'پسورد شما با موفقیت تغییر کرد',"پسورد شما با موفقیت تغییر کرد . <br> پسورد جدید : <br>{$pass}" );
		
		$this->pageTitle = 'پسورد شما با موفقیت تغییر کرد ';
		Yii::app()->user->setFlash('msg','پسورد شما با موفقیت تغییر کرد ، لطفاً ایمیل خود را بررسی کنید .');
		$this->render('message');		
	}
	
	
	function actionForm($id=0)
	{
		$id = (int) $id;
		$form = Form::model()->find('status=1 and id=:id',compact('id'));
		if(empty($form))
			throw new CHttpException(404,'فرم مورد نظر یافت نشد !');
		$this->pageTitle = $form->title;
		
		
		$model = new FormPay;
		$model->_form = $form;
		if(isset($_POST['FormPay']))
		{
			$model->attributes = $_POST['FormPay'];
			$model->_form = $form;
			if($model->validate())
			{
				if($model->save())
					$model->go2bank();
			}
			
			
		}
		else
			$model->amount = $form->amount;
		$_form = $form;
		$this->render('form',compact('model','_form'));
	}
	
	//callback
	function actionCallback($id=0)
	{
		$id = (int) $id;
		$this->checkCsrf();
		if(empty($_GET['md5']) or $_GET['md5']!=md5($id.Yii::app()->basePath))
			throw new CHttpException(500,'request invalid!!1');
		
		$form_item = FormItem::model()->find('id=?',array($id));
		$this->pageTitle = "پرداخت شماره $form_item->id";
		if($form_item->status==0)
			throw new CHttpException(500,'تراکنش ناموفق');
		elseif($form_item->status==1)
			throw new CHttpException(500,'تراکنش موفق میباشد .');
		
		$model = new FormPay;
		if($model->verify($form_item))
		{
			$_form = Form::model()->find('id=?',array($form_item->form_id));
			$this->render('callback',compact('form_item','_form'));
		}
		else
			throw new CHttpException(500,'تراکنش ناموفق!');
	}
	
	
	
	
}