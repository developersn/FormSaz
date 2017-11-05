<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $access_level
 * @property string $mobile
 * @property string $last_activity
 * @property string $start_time
 * @property string $ip
 * @property string $token
 * @property integer $status
 * @property string $extra
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password, name, token, extra', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('email, name', 'length', 'max'=>255),
			array('password', 'length', 'max'=>64),
			array('access_level', 'length', 'max'=>7),
			array('mobile', 'length', 'max'=>20),
			array('last_activity, start_time', 'length', 'max'=>10),
			array('ip', 'length', 'max'=>100),
			array('token', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, name, access_level, mobile, last_activity, start_time, ip, token, status, extra', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'password' => 'Password',
			'name' => 'Name',
			'access_level' => 'Access Level',
			'mobile' => 'Mobile',
			'last_activity' => 'Last Activity',
			'start_time' => 'Start Time',
			'ip' => 'Ip',
			'token' => 'Token',
			'status' => 'Status',
			'extra' => 'Extra',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('access_level',$this->access_level,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('last_activity',$this->last_activity,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('extra',$this->extra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public $_max , $_min;
	static public function showAll()
	{
		$where = '1=1';
		$order = 'user.id DESC';
		$wherePager = '1=1';
		$rev = false ;
		if( ! empty($_GET['next']))
		{
			$id = (int) Rh::big_intval($_GET['next']);
			$where = " user.id<{$id} ";
			$order = 'user.id DESC';
			$rev = false;
		}
		
		if( ! empty($_GET['back']))
		{
			$id = (int) Rh::big_intval($_GET['back']);
			$where = " user.id>{$id} ";
			$order = 'user.id ASC';
			$rev = true;
		}
		
		//setstatus
		if(isset($_GET['status']))
		{
			$where .= " and user.status=" . Rh::big_intval($_GET['status']);
			$wherePager .=" and status=" . Rh::big_intval($_GET['status']);
			
		}
		
		if(isset($_GET['shop_status']))
		{
			$where .= " and shop.status=" . Rh::big_intval($_GET['shop_status']);
			
			
		}
		
		$where .=" and `access_level`='shopper' ";
		$wherePager .=" and `access_level`='shopper' ";
		
		/*$data['items'] = self::model()->findAll(array(
												//'select'=>'id,username,name,email,status,last_activity,site' ,
												'order'=>$order ,
												'condition'=> $where ,
												'limit'=> 30 ,
											));*/
		$data['items'] = Yii::app()->db->createCommand("SELECT user.* , shop.shop_sub , shop.shop_url, shop.status as shop_status FROM `user` left join shop on user.id = shop.user_id where  $where order by $order limit 30")->queryAll();
		if($rev)
			$data['items'] = array_reverse($data['items']);
		
		//max min for pager
		$pager = self::model()->find(array(
									'select'=>'MAX(id) as _max , MIN(id) as _min' ,
									'condition'=>$wherePager ,
									));
		$data['pager'] = $pager ;
		
		return $data ;
	}
}