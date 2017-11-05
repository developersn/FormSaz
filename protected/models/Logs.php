<?php


class Logs extends CActiveRecord
{
	public $_max , $_min;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'logs';
	}

	public function rules()
	{
		return array(
			array('title, time, ip, msg', 'required'),
			array('title, ip', 'length', 'max'=>255),
			array('time', 'length', 'max'=>10),
		);
	}

	function attributeLabels()
	{
		return array(
			'id'=>'شناسه',
			'title'=>'عنوان',
			'time'=>'تاریخ' ,
			'ip'=>'آی پی',
			'msg'=>'پیغام',
			"authorName"=>"اسم نویسنده",
		);
	}
	
	public function add($title='',$msg='')
	{
		for($i=0;$i<10;$i++)
		{
			$r = $this->_add($title,$msg);
			if($r)
			{
				break;
				return true;
			}
		}
		return false;
	}
	
	public function _add($title , $msg)
	{
		$log = new self ;
		$log->title = Rh::safe_html($title);
		$log->msg = Rh::safe_html($msg);
		$log->time = time();
		$log->ip = Rh::user_ip();
		return $log->save();
	}
	
	static public function showAll()
	{
		$where = '1=1';
		$order = 'id DESC';
		$rev = false ;
		if( ! empty($_GET['next']))
		{
			$id = (int) Rh::big_intval($_GET['next']);
			$where = " id<{$id} ";
			$order = 'id DESC';
			$rev = false;
		}
		
		if( ! empty($_GET['back']))
		{
			$id = (int) Rh::big_intval($_GET['back']);
			$where = " id>{$id} ";
			$order = 'id ASC';
			$rev = true;
		}
		$data['items'] = self::model()->findAll(array(
												'order'=>$order ,
												'condition'=> $where ,
												'limit'=> 30 ,
											));
		if($rev)
			$data['items'] = array_reverse($data['items']);
			
		//max min for pager
		$dep = new CDbCacheDependency("select id from logs order by id desc limit 1");
		$pager = self::model()->cache(10000,$dep)->find(array(
									'select'=>'MAX(id) as _max , MIN(id) as _min' ,
									));
		$data['pager'] = $pager ;
		
		return $data ;
	}
	
	
}