<?php

/**
* options table

Options::ins()->add('reza','firest val');
echo Options::ins()->get('reza');
Options::ins()->update('reza','new val');

Options::ins()->delete('reza');
**/

/*
CREATE TABLE IF NOT EXISTS `opt` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `opt_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `opt_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opt_key` (`opt_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
*/

class Options
{	
	private static $ins = NULL ;
	
	static public function ins()
	{
		if(is_null(self::$ins))
			self::$ins = new self;
		return self::$ins;
	}
	
	public function add($key='' , $val = NULL)
	{
		//delete cache
		$this->delCache();
		
		if( ! is_null(Options::ins()->get($key)))
			return false;
			
		return Yii::app()->db->createCommand()
			->insert('opt',array(
				'opt_key'=>$key ,
				'opt_value'=>$val ,
			));
	}
	
	public function update($key='' , $val = NULL)
	{
		//delete cache
		$this->delCache();
		
		return Yii::app()->db->createCommand()
			->update('opt' , array(
				'opt_value'=>$val
				),
				'opt_key=:key'	,
				array(':key'=>$key)
				);
	}
	
	public function delete($key='')
	{
		//delete cache
		$this->delCache();
		
		return Yii::app()->db->createCommand()
			->delete('opt','opt_key=:key',array(':key'=>$key));
	}
	
	private $results = null;
	public function get($key = null)
	{
		if(empty($this->results))
			$this->setCache();
		if($key === null)
			return $this->results ;
		foreach($this->results as $row)
		{
			if($row['opt_key']==$key)
			{
				return $row['opt_value'];
				break;
			} 
		}
		return NULL;	
		
	}
	
	private function setCache()
	{
		$cache = Yii::app()->cache->get('Options');
		if(empty($this->results) and empty($cache))
			Yii::app()->cache->set('Options',Yii::app()->db->createCommand()->from('opt')->queryAll());
			
		$this->results = Yii::app()->cache->get('Options') ;
	}
	
	private function delCache()
	{
		Yii::app()->cache->delete('Options');
		$this->results = NULL;
	}
}
