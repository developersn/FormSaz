

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<head>
   <title>
	<?php 
	$title = Yii::app()->name ;
	if( ! empty($this->pageTitle))
		$title .= " - {$this->pageTitle}";
	echo CHtml::encode($title); ?></title>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/favicon.png?123" type="image/x-icon">
   
  <link rel="stylesheet" type="text/css" href='<?php echo Yii::app()->request->baseUrl; ?>/images/admin/style.css' media='all' />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  </head>

<body>
<!--top-->
<div id=top>
	<div style='padding:16px;'>
		<a class=ttitle href='<?php echo Yii::app()->createUrl('xmain/index'); ?>'>نمایش سایت</a>
		
		
		<div style='float:left;padding-left:20px'>
		مدیر گرامی , 
		<?php echo $this->currentUser()->name ; ?>
		 خوش آمدید .
		&nbsp;&nbsp;&nbsp;
			
			
			<a href='<?php echo $this->createUrl('logout?token='.$this->csrf) ?>' class='atop out' style='padding-right:5px'><span class=outicon>&nbsp;&nbsp;&nbsp;</span> خروج</a>
			
			
			

		</div>
		<div class=clear>&nbsp;</div> 
		<a id=ahome href='<?php echo $this->createUrl('index') ?>'>&nbsp;</a> / &nbsp;&nbsp; <a class=ahome2 href='<?php echo Yii::app()->createUrl('xadmin/'.$this->action->id); ?>'><?php echo $this->pageTitle ?></a>
		
		
		<div style='float:left;'>
		 &nbsp;&nbsp;&nbsp;
			 <?php 
		$last = (int) Yii::app()->session['last_activity'];
		$ip = Yii::app()->session['last_ip'];
		if( ! empty($last))
		{
			echo " آخرین ورود شما به سیستم در " ,
				Rh::time_left($last);
			if( ! empty($ip))
				echo " با آی پی $ip";

		}
		?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		</div>
		<div class=clear>&nbsp;</div>
		
	</div>

</div>

<!--/top-->
<?php 
	

	
	class dLinkClass
	{
		static public $this_action_id;
		function link($action,$name,$url = NULL)
		{
			$this_action_id=self::$this_action_id;
			$this_action_id = strtolower($this_action_id);
			$action = strtolower($action);
			if(empty($url))
				$u =Yii::app()->createUrl("xadmin/{$action}");
			else
				$u = Yii::app()->createUrl("xadmin/{$url}");
				
			if($action == $this_action_id)
				echo "<a href='".$u."'><span class='ssidebar ssidebarhover' >&nbsp; {$name}</span></a>";
			else
				echo "<a href='".$u."'><span class='ssidebar ssidebarhovernone' >&nbsp; {$name}</span></a>";
		}
	}
	
	dLinkClass::$this_action_id = $this->action->id;
	$do = new dLinkClass;
?>
<!--main-->
<div style='height:100% !important;padding:10px 30px;'>
	<div style='float:right;width:17%;margin-left:20px;' id=sidebar>
		<!--sidebar-->
		
		<?php $do->link('index','پیشخوان'); ?>
		<?php $do->link('formitems','پرداختی ها'); ?>
		
		
		<div style='height:14px;width:1px'></div>
		<?php $do->link('addgateway','افزودن درگاه'); ?>
		<?php $do->link('gateways','همه درگاهها'); ?>
		
		<?php $do->link('addform','افزودن فرم'); ?>
		<?php $do->link('allforms','همه فرمها'); ?>
		
		
	
		
		<div style='height:14px;width:1px'></div>
		
		
		
		
		
		<?php $do->link('logs','گزارشات لاگ'); ?>
		
		<?php $do->link('changepass','تغییر پسورد'); ?>
		
		
	
		<!--/sidebar-->
	</div>
	
	<div style='width:79%; float:right'>
	<?php
foreach(array('err','ok','war','info','msg') as $err_var)
if(Yii::app()->user->hasFlash($err_var))
	echo "<div class={$err_var}>".Yii::app()->user->getFlash($err_var)."</div>";


?>
		<?php echo $content ; ?>
	</div>
	<div class=clear>&nbsp;</div>
	
	
</div>


<!--/main-->
<!--footer-->

<br>

</body>

</html>
<div dir=ltr>

