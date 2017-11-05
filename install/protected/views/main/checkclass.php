بررسی نیازمندی های نصب
<br>
<div dir=ltr>
<?php
$ok = true;
foreach($out as $key=>$val)
{
	if($val==false)
	{
		$ok = false;
		echo "<br><font color=red>{$key} فعال نیست !</font>";
	}
	else
		echo "<br><font color=green>{$key} فعال است .</font>";
}
 ?>
</div>

<?php
if($ok)
{
	echo CHtml::beginForm($this->createUrl('getDb'));
	echo CHtml::hiddenField('token',$this->csrf);
	echo CHtml::submitButton('مرحله بعد');
	echo CHtml::endForm();
}
else
{
	echo CHtml::beginForm($this->createUrl('checkClass'));
	echo CHtml::hiddenField('token',$this->csrf);
	echo CHtml::submitButton('بررسی مجدد');
	echo CHtml::endForm();
}
 ?>