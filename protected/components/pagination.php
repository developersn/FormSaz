<?php


class pagination{

	private $all 	= 	0;		// select count(id) from post where status=publish
	private $range 	=	10;		// max post per page
	private $inpage	=	0;		// current page
	private $url=	'';			// url of page like http://mysite.com/page-
	private $after_url =	'';	// character for end of url like .html
	private $limit	   = null;	// make limit like 3 show 1 , 2 , 3 , ... , 5 , 6 , 7 , ... , 18 , 19 , 20
	
	// for example : http://mysite.com/page-1.html
	
			/**
		* construct func
		* get pagenumbers config
		*
		* @param $conf array 
		*/
	function __construct($conf=null)
	{
		if(!is_array($conf))
			return '';
			
			if(isset($conf['all']) && !empty($conf['all']))
				$this->all = $conf['all'];
			
			if(isset($conf['range']) && !empty($conf['range']))
				$this->range = $conf['range'];
			
			if(isset($conf['inpage']) && !empty($conf['inpage']))
				$this->inpage = $conf['inpage'];
			
			if(isset($conf['url']) && !empty($conf['url']))
				$this->url = $conf['url'];
			
			if(isset($conf['after_url']) && !empty($conf['after_url']))
				$this->after_url = $conf['after_url'];
				
			if(isset($conf['limit']) && !empty($conf['limit']))
				$this->limit = $conf['limit'];
	}
		/**
	* Return str class=inpage where page num is in page
	*
	* @access	private
	* @param $i int
	* @param $inpage string
	* @return string
	*/
	private function inpage($i,$inpage)
	{
		if($i==$inpage)
			return ' class="inpage"';
		return '';	
	}
	
		/**
	* check the number zoj or fard
	*
	* @access	private
	* @param $i int
	* @return bool
	*/
	private function is_zoj($int=1)
	{
		if($int%2 ==0)
			return true;
		return false;
	}
	
		/**
	* show page numbers
	*
	* @access	public
	* @return string - list of page numbers
	*/
	public function pagenumber()
	{
	$inpage = $this->inpage;
	$limit =$this->limit;
	$maxitem = ceil($this->all/$this->range);
	$last_end = $maxitem - $limit;
		$i = $fir_out = $sec_out = 0;
		
			// start of pagenumber
      $out="\n<ul class='pagenumber'>\n";
	  if(1<$inpage )	
		$out.='<li><a href="'.$this->url.'1'.$this->after_url.'" target="_self" rel="nofllow" >نخست</a></li>'."\n".'<li><a href="'.$this->url.($inpage-1).$this->after_url.'" target="_self" rel="nofllow" >قبلی</a></li>'."\n";
    
			//loop of pagenumber
	  while ($i<$maxitem)
	  {
		$i++;
			if($limit === null or ($i<=$limit) or ($i>$last_end) or ($i==$inpage) or ($i> $inpage-ceil(($limit)/2) and $i < $inpage+ceil(($limit)/2)) or ($this->is_zoj($limit) and $i>= $inpage-ceil(($limit)/2) and $i < $inpage+ceil(($limit)/2)) )
				$out.='<li'.$this->inpage($i,$inpage).'><a href="'.$this->url.$i.$this->after_url.'" target="_self" rel="nofllow" >'.$i.'</a></li>'."\n";
			elseif($i>$limit && ((!$this->is_zoj($limit) and $i<$inpage-(($limit)/2) ) or ($this->is_zoj($limit) and $i<$inpage-($limit/2)) ))
				$fir_out++;
			elseif($i<=$maxitem-$limit &&( (!$this->is_zoj($limit) and $i>$inpage-(($limit-1)/2)) or ($this->is_zoj($limit) and $i>$inpage+($limit/2)-1) ))
				$sec_out++;
			else
				continue;
							
				//show doted
				if($fir_out ===1 and ( (!$this->is_zoj($limit) and $i<$inpage-(($limit)/2)) or ($this->is_zoj($limit) and $i<$inpage-($limit/2)) ) )
					$out.='<li class="dotedli">...</li>';		
				elseif($sec_out===1 &&( (!$this->is_zoj($limit) and $i>$inpage-(($limit-1)/2) ) or ($this->is_zoj($limit) and $i>$inpage+($limit/2)-1) ) && $i<$maxitem-$limit+1)
					$out.='<li class="dotedli">...</li>';
				else
					continue;
						
			 }
	 		// end of pagenumber
	if($maxitem>$inpage )	
		$out.='<li><a href="'.$this->url.($inpage+1).$this->after_url.'"  >بعدی</a></li>'."\n".'<li><a href="'.$this->url.$i.$this->after_url.'" target="_self" rel="nofollow" >آخرین</a></li>'."\n"; 

		$out.="</ul>\n<div style='clear:both'></div>";		
			if($i===1)
				return '';
		return $out;
	}
	
	
	function __destruct()
	{
		// end
	}
}






//==== Usage ====\\
/*
?>
<style type='text/css'>
.pagenumber li{
  float:right;
  display:inline-block;
   margin:2px 1px 1px 2px;
  background:white;
  border:1px solid #D8CAAF
}
.pagenumber  li:hover{
  background:#FFE0A8;
}
.pagenumber a{
  display:inline-block;
    padding-top:2px;
  padding-bottom:2px;
  padding-right:6px;
  padding-left:6px;
  font-family:tahoma;
  text-decoration:none;
  font-size:8pt;
}

li.inpage{
  background:#E6DCCA;
}

.pagenumber .dotedli{
	border:none;
	background:white
}
.pagenumber .dotedli:hover{
	border:none;
	background:white 
}

</style>
<meta http-equiv='Content-type' content='text/html;charset=utf-8' />
<?php
$pageconf = array(
'all'=>200 ,	 // select count(*) from post where 
'range'=>10 , 	// select * from post limit inpage*range,range
'inpage'=>$_GET['page'],	// current page. example 4_GET[]
'limit'=>3 ,	// use number of li for minimize
'url'=>'http://localhost/workshop/pagination.php?page=' // url of page. the number showed in end of url
);

$pagenumber = new pagination($pageconf);

echo $pagenumber->pagenumber();
*/
