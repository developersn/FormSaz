<?php
class Registry extends stdclass
{
	static private $ins = NULL ;
	
	static public function ins()
	{
		if(self::$ins === NULL)
			self::$ins = new self;
		return self::$ins ;
	}
}
