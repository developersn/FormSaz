<?php

/**
 * This is the model class for table "form".
 *
 * The followings are the available columns in table 'form':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $msg
 * @property integer $status
 * @property string $amount
 * @property string $key1
 * @property string $key2
 * @property string $key3
 * @property string $key4
 * @property string $extra
 */
class Form extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'form';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, msg', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('title, key1, key2, key3, key4, extra', 'length', 'max'=>255),
			array('amount', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, content, msg, status, amount, key1, key2, key3, key4, extra', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'content' => 'Content',
			'msg' => 'Msg',
			'status' => 'Status',
			'amount' => 'Amount',
			'key1' => 'Key1',
			'key2' => 'Key2',
			'key3' => 'Key3',
			'key4' => 'Key4',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('msg',$this->msg,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('key1',$this->key1,true);
		$criteria->compare('key2',$this->key2,true);
		$criteria->compare('key3',$this->key3,true);
		$criteria->compare('key4',$this->key4,true);
		$criteria->compare('extra',$this->extra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Form the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
