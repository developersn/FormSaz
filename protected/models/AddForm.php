<?php
class AddForm extends CFormModel
{
	public
			$title , $content , $msg='با تشکر از پرداخت شما' , $amount ;

	
	public 
			$key1_name ,
			$key1_is_req;
	
	
	public 
			$key2_name ,
			$key2_is_req;
	
	
	
	public 
			$key3_name ,
			$key3_is_req;
	
	function rules()
	{
		$this->amount = (int) $this->amount;
		return array(
			array('title,content,msg','required'),
			array('amount','numerical'),
			array('key1_is_req,key2_is_req,key3_is_req','boolean'),
			array('key1_name,key2_name,key3_name,amount','safe'),
			
		);
	}
	
	function attributeLabels()
	{
		return array(
			'title'=>'عنوان فرم',
			'content'=>'متن فرم',
			'msg'=>'پیغام بعد از خرید موفق',
			'amount'=>'مبلغ(تومان)',
			'key1_name'=>'عنوان فیلد1',
			'key1_is_req'=>'اجباری؟',
			'key2_name'=>'عنوان فیلد2',
			'key2_is_req'=>'اجباری؟',
			'key3_name'=>'عنوان فیلد 3',
			'key3_is_req'=>'اجباری؟',
		);
	}
	
	
	function save($id=0)
	{
		if(empty($id))
			$new = new Form;
		else
			$new = Form::model()->find('id=?',array($id));
		$new->title = $this->title;
		$new->content = $this->content;
		$new->msg = $this->msg;
		$new->status=1;
		$new->amount = (int) $this->amount;
		
		$new->key1 = serialize(array(
			'name'=>$this->key1_name ,
			'req'=> intval($this->key1_is_req)
		));
		
		$new->key2 = serialize(array(
			'name'=>$this->key2_name ,
			'req'=> intval($this->key2_is_req)
		));
		
		$new->key3 = serialize(array(
			'name'=>$this->key3_name ,
			'req'=> intval($this->key3_is_req)
		));
		
		return $new->save();
	}
	
	function setVals($form)
	{
		foreach(array('title','content','msg','amount') as $row)
			$this->{$row} = $form->{$row};
		
		
		if( ! empty($form->key1))
		{
			$key1 = unserialize($form->key1);
			$this->key1_name = ! empty($key1['name'])?$key1['name']:'';
			$this->key1_is_req = ! empty($key1['req'])?$key1['req']:'';
		}
		
		
		if( ! empty($form->key2))
		{
			$key2	 = unserialize($form->key2);
			$this->key2_name = ! empty($key2['name'])?$key2['name']:'';
			$this->key2_is_req = ! empty($key2['req'])?$key2['req']:'';
		}
		
		
		if( ! empty($form->key3))
		{
			$key3 = unserialize($form->key3);
			$this->key3_name = ! empty($key3['name'])?$key3['name']:'';
			$this->key3_is_req = ! empty($key3['req'])?$key3['req']:'';
		}
		
		
	}
	
	
}