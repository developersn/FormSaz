<?php
class FormPay extends CFormModel
{
	public
			$name , $email , $phone , $amount,$msg ;
			
	public	$key1,$key2,$key3;
	
	public	$gateway_id;
	public $verifyCode;
	
	public $_form;
	function rules()
	{
		$_form = $this->_form;
		if( ! empty($_form->amount))
			$this->amount = $_form->amount;
		$this->amount = (int) $this->amount;
		
		$range = array();
		$gt = Gateway::model()->findAll(array(
			'select'=>'id',
			'condition'=>'status=1'
		));
		foreach($gt as $row)
			$range[] = $row->id;
		
		$out = array(
			array('_form','unsafe'),
			array('name,email,amount','required'),
			array('email','email'),
			array('phone,amount','numerical'),
			array('phone','match','pattern'=>'/^[0-9]{10,12}$/i','message'=>'لطفا شماره را بصورت صحیح وارد کنید'),
			array('msg','safe'),
			array('gateway_id','in','range'=>$range),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() , 'message'=>'کد امنیتی نامعتبر است !'),
			
		);
		
		
		if(empty($_form->amount))
			$out[] =	array('amount','numerical','min'=>100,'max'=>1000000);
		else
			$out[] =	array('amount','in','range'=>array($_form->amount));
			
			
		//key
		$key1 = array();
		if( ! empty($_form->key1))
			$key1 = unserialize($_form->key1);
			
		if( ! empty($key1['name']))
			$out[] = array('key1',empty($key1['req']) ?'safe':'required');
		else
			$out[] = array('key1','unsafe');
			
			$key2 = array();
		if( ! empty($_form->key2))
			$key2 = unserialize($_form->key2);
			
		if( ! empty($key2['name']))
			$out[] = array('key2',empty($key2['req']) ?'safe':'required');
		else
			$out[] = array('key2','unsafe');
		
		$key3 = array();
		if( ! empty($_form->key3))
			$key3 = unserialize($_form->key3);
			
		if( ! empty($key3['name']))
			$out[] = array('key3',empty($key3['req']) ?'safe':'required');
		else
			$out[] = array('key3','unsafe');
			
		return $out;
	}
	
	function attributeLabels()
	{
		$_form = $this->_form;
		$out =  array(
			'name'=>'نام و نام خانوادگی',
			'email'=>'پست الکترونیک',
			'phone'=>'شماره تلفن',
			'amount'=>'مبلغ (تومان)',
			'msg'=>'پیغام شما',
			'gateway_id'=>'درگاه',
			'verifyCode'=>'',
		);
		
		$key1 = array();
		if( ! empty($_form->key1))
			$key1 = unserialize($_form->key1);
			
		if( ! empty($key1['name']))
			$out['key1'] = $key1['name'];
			
			$key2 = array();
		if( ! empty($_form->key2))
			$key2 = unserialize($_form->key2);
			
		if( ! empty($key2['name']))
			$out['key2'] = $key2['name'];
		
		$key3 = array();
		if( ! empty($_form->key3))
			$key3 = unserialize($_form->key3);
			
		if( ! empty($key3['name']))
			$out['key3'] = $key3['name'];
			
		return $out;
	}
	
	
	function save()
	{
		$_form = $this->_form;
		$new  = new FormItem;
		$new->gateway_id = $this->gateway_id;
		$new->form_id = $this->_form->id;
		$new->sn_au = 'none';
		$new->amount = (int) $this->amount;
		$new->ip = Rh::user_ip();
		$new->time = time();
		$new->status = 2;
		$new->email = strtolower($this->email);
		$new->phone = strip_tags($this->phone);
		$new->name = strip_tags($this->name);
		$new->msg = ! empty($this->msg)? $this->msg:'--';
		
		$att = $this->attributeLabels();
		$values = "--\n";
		if( ! empty($this->key1))
			$values .= $att['key1'] ." : {$this->key1} \n";
			
		if( ! empty($this->key2))
			$values .= $att['key2'] ." : {$this->key2} \n";

		if( ! empty($this->key3))
			$values .= $att['key3'] ." : {$this->key3} \n";			
		$new->values = $values;
		$new->extra = NULL;
		
		
		$n= $new->save();
		return $n;
	}
	
	
	
	
	function go2bank()
	{
		$last = (int) Yii::app()->db->lastInsertID;
		
		$gateway = Gateway::model()->find('id=? and status=1',array($this->gateway_id));
		if(empty($gateway))
			throw new CHttpException(500,'درگاه موجود نیست یا غیرفعال شده است !');
		
		if($gateway->type==1)
			$bank = new JP1; //عادی
		else
			$bank = new sn;
			
		$bank->API = $gateway->api;
			
		$callback = Yii::app()->createAbsoluteUrl('xmain/callback',array(
			'id'=>$last ,
			'token'=>Yii::app()->request->getCsrfToken() ,
			'md5'=> md5($last.Yii::app()->basePath),
		));
		
		$au = $bank->request((int) $this->amount , $last,$callback);
		if(strlen($au)>4)
		{
			$af = Yii::app()->db->createCommand()->update('form_item',array('sn_au'=>$au),'id='.$last);
			if( ! $af)
				throw new CHttpException(500,'خطا در ذخیره سازی دیتابیس');
		}
		else
			throw new CHttpException('500','خطا در اتصال به بانک کد برگشتی '.$au);
		
		$bank->go2bank($au);
		die;
		
	}
	
	
	function verify($form_item)
	{
		$gateway = Gateway::model()->findByPk($form_item->gateway_id);
		if($gateway->type==1)
			$bank = new JP1; //عادی
		else
			$bank = new sn;
			
		$bank->API = $gateway->api;
		
		if($bank->verify($form_item->amount,$form_item->id,$form_item->sn_au))
		{
			Yii::app()->db->createCommand()->update('form_item',array('status'=>1),'status=2 and id='.$form_item->id);
			return true;
		}
		else
		{
			Yii::app()->db->createCommand()->update('form_item',array('status'=>0),'status=2 and id='.$form_item->id);
			return false;
		}
	}
	
	
}