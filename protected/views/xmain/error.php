<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>خطایی رخ داده - Error <?php echo $code; ?></title>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <style>
   *{text-decoration:none}
   body{background:#E3E3E3;font-family:tahoma,sans-serif;font-size:11px;line-height:1.6em;direction:rtl}
   .e{margin:0px auto; width:580px;background:#EAEAEA;border:1px solid #D2D2D2;padding:4px;border-radius:4px;}
   .toptitle{font-size:20px;color:#313131;text-shadow:1px 1px 1px white;font-family:times,arial,tahoma,sans-serif}
   </style>
   </head><body>
   <br><br><br><br><br><br>
   <center><span class='toptitle'>Error <?php echo $code; ?></span></center>
 <div class='e'>
	<div style='padding:4px;background:white;border-radius:4px;color:#4B4B4B'>
                 
                         
			<?php // echo $code!=500?CHtml::encode($message):'Error 500'; ?>
			<?php print CHtml::encode($message) ; ?>
			<br>
			<?php
			$url = Yii::app()->request->hostInfo;
			
			$h = '';
			if( ! empty(Yii::app()->params['siteUrl']))
				$h = 'http://'.str_replace('http://','',Yii::app()->params['siteUrl']);
			else
				$h = $url;
				
			echo "<br>".CHtml::link('{  بازگشت به صفحه اصلی  }',$h);	
			?>
			</div>
 </div>
</body></html>

