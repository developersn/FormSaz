<script>
function checkDB()
{
	$('#result').html('لطفا کمی صبر کنید ...');
	
	$.get('<?php echo $this->createUrl('checkdb&token='.$this->csrf); ?>',{'dbname':$('#dbname').val(),
	'dbuser':$('#dbuser').val() ,
	'dbpass':$('#dbpass').val() ,
	'dbhost':$('#dbhost').val()
	},function(data){
	
		if(data==200)
		{
			$('#result').html('اتصال به دیتابیس موفقیت آمیز ...');
			document.reza.submit();
			return true;
		}
	
		alert(data);
		$('#result').html(data);
		return false;
	});
	
	$('#result').html('خطایی رخ داد اطلاعات را بررسی کرده مجددا تلاش کنید !');
	return false;
}
</script>
<!--field-->
نام دیتابیس <br>
<?php echo CHtml::textField('','',array('id'=>'dbname')); ?> <br>
هاست نیم دیتابیس<br>
<?php echo CHtml::textField('','localhost',array('id'=>'dbhost')); ?> <br>
یوزر دیتابیس <br>
<?php echo CHtml::textField('','',array('id'=>'dbuser')); ?> <br>
پسورد دیتابیس <br>
<?php echo CHtml::textField('','',array('id'=>'dbpass')); ?> <br>



<?php
echo CHtml::beginForm($this->createUrl('doquery'),'post',array('name'=>'reza'));
echo CHtml::hiddenField('token',$this->csrf);
echo CHtml::submitButton('مرحله بعد',array('onclick'=>'return checkDB()'));
echo CHtml::endForm();
 ?>
 <div id=result>&nbsp;</div>