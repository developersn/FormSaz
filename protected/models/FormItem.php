<?php

/**
 * This is the model class for table "form_item".
 *
 * The followings are the available columns in table 'form_item':
 * @property string $id
 * @property string $gateway_id
 * @property string $form_id
 * @property string $sn_au
 * @property string $amount
 * @property string $ip
 * @property string $time
 * @property integer $status
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property string $msg
 * @property string $values
 * @property string $extra
 */
class FormItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'form_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gateway_id, form_id, amount, ip, time, msg, values', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('gateway_id, form_id, time', 'length', 'max'=>10),
			array('sn_au, ip, email, name, extra', 'length', 'max'=>255),
			array('amount, phone', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, gateway_id, form_id, sn_au, amount, ip, time, status, email, phone, name, msg, values, extra', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'gateway_id' => 'Gateway',
			'form_id' => 'Form',
			'sn_au' => 'sn Au',
			'amount' => 'Amount',
			'ip' => 'Ip',
			'time' => 'Time',
			'status' => 'Status',
			'email' => 'Email',
			'phone' => 'Phone',
			'name' => 'Name',
			'msg' => 'Msg',
			'values' => 'Values',
			'extra' => 'Extra',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('gateway_id',$this->gateway_id,true);
		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('sn_au',$this->sn_au,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('msg',$this->msg,true);
		$criteria->compare('values',$this->values,true);
		$criteria->compare('extra',$this->extra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FormItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public $_min,$_max;
	static public function ShowAll()
	{
		$where = '1=1';
		$order = 'id DESC';
		$wherePager = '1=1';
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
		
		if( ! empty($_GET['form_id']))
		{
			$uid = (int) $_GET['form_id'];
			$where .=" and form_id={$uid} ";
			$wherePager .=" and form_id={$uid}";
		}
		
		if( ! empty($_GET['gateway_id']))
		{
			$uid = (int) $_GET['gateway_id'];
			$where .=" and gateway_id={$uid} ";
			$wherePager .=" and gateway_id={$uid}";
		}
		
		if( isset($_GET['status']))
		{
			$_uid = (int) $_GET['status'];
			$where .=" and status={$_uid} ";
			$wherePager .=" and status={$_uid}";
		}
		
	
		
		$data['items'] = self::model()->findAll(array(
												'order'=>$order ,
												'condition'=> $where ,
												'limit'=> 30 ,
											));
		if($rev)
			$data['items'] = array_reverse($data['items']);
		
		//max min for pager
		$tbl = self::model()->tableName();
		$dep = new CDbCacheDependency("select id from {$tbl} where {$wherePager} order by id desc limit 1");
		$pager = self::model()->cache(100,$dep)->find(array(
									'select'=>'MAX(id) as _max , MIN(id) as _min' ,
									'condition'=>$wherePager ,
									));
		$data['pager'] = $pager ;
		
		return $data ;
	}
}
