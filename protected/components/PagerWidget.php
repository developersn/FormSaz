<?php

class PagerWidget extends CWidget
{
	public  $items = array() ,
			$params ='' ,
			$action ='' ,
			$max = NULL ,
			$min = NULL ,
			$pk = 'id';
	
	
	public function init()
	{	
		if( ! strpos('?',$this->action))
			$start = '?';
		else
			$start = '&';
			
		$params = $this->params .',';
		$params = explode(',',$params);
		$params = array_map('trim',$params);
		$_params = array();
		foreach($params as $param)
		{
			if(isset($_GET[$param]))
				$_params[]= "{$param}=" . strip_tags($_GET[$param]);
		}	
		if( ! empty($_params))
			$this->action =  $this->action .$start. implode('&',$_params);	
		else
			$this->action = $this->action .$start .'';
	}
	
	public function run()
	{
		$out = '';
		if(empty($this->items))
		{
			echo '<br>هیچ آیتمی یافت نشد !';
			if( ! empty(Yii::app()->request->urlReferrer))
				echo '<br>'.CHtml::link('بازگشت',Yii::app()->request->urlReferrer).'<br><br>';
			return;
		}
		$get = $this->getMinMax();
		if($get['max']<$this->max)
			$out = CHtml::link('« صفحه قبل',"{$this->action}&back={$get['max']}");
			
		if($get['min']>$this->min)
		{
			if( ! empty($out))
				$out .=' - ';
			$out .=CHtml::link('صفحه بعد »',"{$this->action}&next={$get['min']}");
		}
		echo $out;
	}
	
	 private function getMinMax()
	{
		if(empty($this->items))
			return NULL;
		if( ! is_array($this->items))
			$this->items = (array) $this->items;
		$ids = array();
		foreach($this->items as $item)
			$ids[] = $item[$this->pk];
		
		return array(
			'max'=>max($ids) ,
			'min'=>min($ids) ,
		);
		
	}

}