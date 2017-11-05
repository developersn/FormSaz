سطح دسترسی شاخه ها و فایلهای زیر برروی نوشتن (777) تنظیم شده باشد
<br>
<div dir=ltr>
<?php
foreach($out as $key=>$val)
{
	echo $key." (سطح دسترسی فعلی {$val}) <br>";
}
 ?>
</div>

<?php
echo CHtml::beginForm($this->createUrl('checkClass'));
echo CHtml::hiddenField('token',$this->csrf);
echo CHtml::submitButton('شروع عملیات نصب');
echo CHtml::endForm();
 ?>