یک ایمیل برای مدیریت ست کنید . این ایمیل غیرقابل تغییر است
<div style='color:red'>
<?php
if(isset($_POST['email']))
{
	echo "لطفا ایمیل صحیح وارد کنید !";
	$email = strip_tags($_POST['email']);
}
else
	$email = '';
 ?>
</div>

<?php

	echo CHtml::beginForm($this->createUrl('email'));
	echo CHtml::hiddenField('token',$this->csrf);
	echo CHtml::textField('email',$email);
	echo CHtml::submitButton('اعمال');
	echo CHtml::endForm();


 ?>