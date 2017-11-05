<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="fa-IR" xml:lang="fa-IR">
<head>
  <title>
	<?php 
	$title = '' ;
	if( ! empty($this->pageTitle))
		$title .= " - {$this->pageTitle}";
	echo CHtml::encode($title); ?></title>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   
  <link rel="stylesheet" type="text/css" href="<?php echo A::pp()->baseUrl; ?>/../css/form.css" />
  <link rel='stylesheet' type='text/css' media='all' href='<?php echo A::pp()->baseUrl; ?>/../images/pay/reset.css' />
  <link rel='stylesheet' type='text/css' media='all' href='<?php echo A::pp()->baseUrl; ?>/../images/pay/css.css' />
  <script type="text/javascript" src="<?php echo A::pp()->baseUrl; ?>/assets/jquery.js"></script>


</head>

<body>
<div id=menu>
	<span class=mtitle>سيستم پرداخت اينترنتي</span>
	

	<div class=clear></div>
</div>


<div class=main>
	
		<?php 
		
		if ( ! empty($this->pageTitle))
		{
			echo CHtml::link("<div class=title>{$this->pageTitle}</div>",A::pp()->currentUrl).'<br>';
		} ?>
	
	<?php echo $content; ?>
</div>




</body>
</html>
