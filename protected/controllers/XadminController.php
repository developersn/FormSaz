<?php
class XadminController extends Controller
{
	public $layout = '//layouts/xadmin';
	
	
	
	function accessRules()
	{
		
		
		return array(
			
			array('allow', 
				'users'=>array('@'),
				'expression'=> ($this->currentUser()->access_level!='user' && $this->currentUser()->access_level!='guest' and $this->currentUser()->access_level!='shoppper') ? '1==1':'1==2',
			),
			array('deny',  
				'users'=>array('*'),
			),
		);
	}
	
	
	
	
	/*filter */
	function filters()
	{
		return array(
			'accessControl', 
		);
	}
	function actionIndex()
	{
		$this->pageTitle = 'پیشخوان';
		$data['logsLogin']= Logslogin::model()->findAll('user_id='.$this->userId." order by id desc limit 50");
		
		$last = (int) Yii::app()->session['last_activity'];
		$data['last'] = $last;
		$this->render('index',$data);
	}
	
	
	function actionLogs()
	{
		$this->pageTitle='گزارشات لاگ';
			if( ! empty($_GET['del']))
		{
			$this->checkCsrf();
			if(Logs::model()->deleteAll())
				Yii::app()->user->setFlash('ok','لاگها به خوبی پاک شدند .');
			else
				Yii::app()->user->setFlash('err','تغییری انجام نشد !');
			
			$this->redirect(array('xadmin/logs'));
		}
		$this->render('logs',Logs::showAll());
	}
	
	
	
	
	
/************************/
	function actionchangeAjax()
	{
		
		// db and change status
		$db = array(
			'gateway'=>array(
				1=>0 ,
				0=>1 ,
			),
			
			'form'=>array(
				1=>0 ,
				0=>1 ,
			),
			
			
		);
		
		
		if( ! Yii::app()->request->isAjaxRequest)
			die('-403');
		
		if(isset($_POST['token']) and ! empty($_POST['db']) and ! empty($_POST['id']))
		{
			if($_POST['token'] != $this->csrf)
				die('-500');
				
			$_db = strip_tags($_POST['db']);
			
			if( ! in_array($_db,array_keys($db)))
				die('-402');
				
			//do it
			$id = intval($_POST['id']);
			
			$_ = (int) $this->db->createCommand("select status from $_db where id=$id")->queryScalar();
			$status = $db[$_db][$_];
			
			$af = (bool) $this->db->createCommand("update $_db set status=$status where id=$id ")->execute();
			if( ! $af)
				die('-403');
				
			die( (string) $status);
		}
		die('-404');
	}
	
	
	
	

	function actionChangePass()
	{
		//change pass
		$model2 = new UserChangePassForm;
		if(isset($_POST['UserChangePassForm']))
		{
			$model2->attributes = $_POST['UserChangePassForm'];
			//$this->checkCsrf();
			if($model2->validate())
			{
				$model2->save();
				Yii::app()->user->setFlash('ok','کلمه عبور با موفقیت تغییر کرد .');
				$this->refresh();
			}
		}
		$this->render('changepass',array('model2'=>$model2));
	}
	
	
	function actionaddgateway()
	{
		$this->pageTitle = 'افزودن درگاه';
		
		$model  = new Gateway;
		if(isset($_POST['Gateway']))
		{
			$model->attributes = $_POST['Gateway'];
			$model->api = strip_tags($model->api);
			$model->title = strip_tags($model->title);
			$model->type = (int) $model->type;
			$model->webservice = (int) $model->webservice;
			if($model->save())
			{
				Yii::app()->user->setFlash('ok','درگاه جدید ساخته شد');
				$this->redirect(array('xadmin/gateways'));
			}
		}
		
		$this->render('addgateway',array('model'=>$model));
	}
	
	function actiongateways()
	{
		$this->pageTitle = 'لیست درگاهها';
		
		$this->render('gateways',array(
			'items'=>Gateway::model()->findAll()
		));
	}
	
	
	function actioneditgateway($id=0)
	{
		$this->pageTitle = 'ویرایش درگاه '.$id;
		
		$model  = Gateway::model()->findByPk($id);
		if(empty($model))
			throw new CHttpException(404,'gateway not found');
		if(isset($_POST['Gateway']))
		{
			$model->attributes = $_POST['Gateway'];
			$model->api = strip_tags($model->api);
			$model->title = strip_tags($model->title);
			$model->type = (int) $model->type;
			$model->webservice = (int) $model->webservice;
			if($model->save())
			{
				Yii::app()->user->setFlash('ok','درگاه به خوبی ویرایش شد ');
				$this->redirect(array('xadmin/gateways'));
			}
		}
		
		$this->render('editgateway',array('model'=>$model));
	}
	
	
	function actionAddForm()
	{
		$this->pageTitle = 'ساخت فرم پرداخت';
		
		$model = new AddForm;
		if(isset($_POST['AddForm']))
		{
			$model->attributes = $_POST['AddForm'];
			$this->checkCsrf();
			if($model->validate())
			{
				if($model->save())
					Yii::app()->user->setFlash('ok','فرم جدید اضافه شد ');
				else
					Yii::app()->user->setFlash('err','خطا در ثبت فرم');
				$this->redirect('allforms');
			}
		}
		
		$this->render('addform',compact('model'));
	}
	
	
	function actionEditForm($id=0)
	{
		$id = (int) $id;
		$form = Form::model()->find('id=?',array($id));
		if(empty($form))
			throw new CHttpException(404,'فرمی موجود نیست !');
			
		$model = new AddForm;
		
		if(isset($_POST['AddForm']))
		{
			$model->attributes = $_POST['AddForm'];
			$this->checkCsrf();
			if($model->validate())
			{
				if($model->save($form->id))
					Yii::app()->user->setFlash('ok','فرم ویرایش شد .');
				else
					Yii::app()->user->setFlash('err','خطا در ثبت فرم');
				$this->redirect('allforms');
			}
		}
		else
			$model->setVals($form);
		
		$this->pageTitle = "ویرایش فرم $id";
		$this->render('addform',compact('model'));
	}
	
	function actionAllForms()
	{
		$this->pageTitle = 'تمامی فرمها';
		$items = Form::model()->findAll(array('order'=>'id desc'));
		
		if( ! empty($_GET['set_def_form_id']))
		{
			
			$this->checkCsrf();
			 Options::ins()->update('def_form',intval($_GET['set_def_form_id']));
			 $this->redirect(array('xadmin/allforms'));
		}
		
		$this->render('allform',compact('items'));
	}
	
	function actionFormItems()
	{
		$this->pageTitle = 'پرداختی ها';
		$this->render('formitems',FormItem::ShowAll());
	}
	
	function actionshowFormItem($id=0)
	{
		$id = (int) $id;
		$item = FormItem::model()->find('id=?',array($id));
		if(empty($item))
			throw new CHttpException(404,'پیدا نشد');
		$this->pageTitle = 'مشاهده آیتم شماره '.$id;
		$this->render('item',compact('item'));
	}
	
}