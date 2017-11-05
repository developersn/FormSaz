<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="fa-IR" xml:lang="fa-IR">
<head>
  <title>
	<?php 
	$title = Yii::app()->name ;
	if( ! empty($this->pageTitle))
		$title .= " - {$this->pageTitle}";
	echo CHtml::encode($title); ?></title>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel='stylesheet' type='text/css' media='all' href='<?php echo Yii::app()->request->baseUrl; ?>/images/pay/reset.css' />
  <link rel='stylesheet' type='text/css' media='all' href='<?php echo Yii::app()->request->baseUrl; ?>/images/pay/css.css' />
  


</head>

<body>
<div id=menu>
	<span class=mtitle><?php echo Yii::app()->name; ?></span>
	
	<ul>
		<li><a href='<?php echo Yii::app()->createUrl('xmain/index'); ?>'>صفحه اول<br> HOME </a></li>
		<li><a href='<?php echo Yii::app()->createUrl('xmain/list'); ?>'>لیست آیتم ها<br> ITEMS </a></li>
		<li><a href='<?php echo Yii::app()->createUrl('xadmin/index'); ?>'>پنل مدیریت<br> ADMIN </a></li>
		<?php 
			if( ! Yii::app()->user->isGuest)
				echo "<li><a href='".$this->createUrl('logout?token='.$this->csrf)."' style='color:orange'>خروج <br> LOGOUT</a></li>";
		?>
	</ul>
	<div class=clear></div>
</div>


<div class=main>
	
		<?php 
		
		if ( ! empty($this->pageTitle))
		{
			echo CHtml::link("<div class=title>{$this->pageTitle}</div>",Yii::app()->request->requestUri).'<br>';
		} ?>
	
	<?php echo $content; ?>
</div>



</body>
</html>
