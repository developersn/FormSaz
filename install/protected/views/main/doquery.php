
<div dir=ltr>
<?php
$ok = true;
foreach($out as $key=>$val)
{
	if($val==false)
	{
		$ok = false;
		echo "<br><font color=red>{$key} خطا !</font>";
	}
	else
		echo "<br><font color=green>{$key} انجام شد .</font>";
}
 ?>
</div>

<?php
if($ok)
{
	echo CHtml::beginForm($this->createUrl('email'));
	echo CHtml::hiddenField('token',$this->csrf);
	echo CHtml::submitButton('مرحله بعد');
	echo CHtml::endForm();
}
else
{
	echo CHtml::beginForm($this->createUrl('doquery'));
	echo CHtml::hiddenField('token',$this->csrf);
	echo CHtml::submitButton('بررسی مجدد');
	echo CHtml::endForm();
}
 ?>