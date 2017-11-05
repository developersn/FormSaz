<?php

class Trylogin extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'trylogin';
	}


	public function rules()
	{

		return array(
			array('ip, time, end_ban', 'required'),
			array('try, is_ban', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>255),
			array('time, end_ban', 'length', 'max'=>10),
			
		);
	}

}