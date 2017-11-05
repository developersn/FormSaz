<?php
class CProfiler implements ComponentInterface
{
	function init()
	{
		
	}
	
	private $error = array();
	private $notice = array();
	private $query = array();
	
	function __construct()
	{
		//
	}
	function addQuery($add = '')
	{
		$this->query = $add;
	}
	function addError($str = null)
	{
		if( ! is_null($str))
			$this->error[] = $str ;
	}
	
	function addNotice($str = null)
	{
		if( ! is_null($str))
			$this->notice[] = $str ;
	}
	
	private static function make_table($name = '' , $array = array() , $count_id = true)
	{
		if($count_id)
			$count = ' - '.count($array);
		else
			$count = '';
		$out = <<<eof
		<meta charset='utf-8' >
		<table border=0 style='border:1px solid gray;font-family:tahoma , sans-serif;font-size:12px; maring:5px ;border-radius:4px;' width='99%'>
			<tr style='background:rgb(249,249,249)'>
				<td style='padding:3px;'>{$name}  {$count}</td>
			</tr>
eof;
			foreach( (array) $array as $item)
			$out .="<tr><td style='border-top:1px solid rgb(230,230,230);padding:3px;'>".htmlspecialchars($item,ENT_QUOTES,'UTF-8')."</td></tr>";
		$out .= "</table><div style='height:5px'> </div>";
		return $out;
	}
	
	function report()
	{
		$this->addQuery();
		echo '<br><br>';
		$mtime      = explode( ' ', microtime() );
		$time_end   = $mtime[1] + $mtime[0];
		$time_total = $time_end - time_start;
		echo self::make_table('time' , round($time_total,4),false);
		echo self::make_table('memory usage',number_format(memory_get_usage()-memory_get_usage).' bytes',false);
		echo self::make_table('error',$this->error);
		echo self::make_table('notice',$this->notice);
		echo self::make_table('query',$this->query);
		echo "Powered by <a href='http://rezaonline.net'>A::pp</a>";
	}
}