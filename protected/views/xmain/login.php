
	
	
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


<div class="row">
		<?php echo $form->labelEx($model,'username'); ?><br>
		<?php echo $form->textField($model,'username',array('size'=>21,'dir'=>'ltr','id'=>'userField')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?><br>
		<?php echo $form->passwordField($model,'password',array('size'=>21,'dir'=>'ltr','id'=>'passField')); ?>
		<?php echo $form->error($model,'password'); ?>
		
	</div>
<br>
	<div class="row rememberMe">
		<table border=0  style=''>
		<tr>
			<td><?php echo $form->checkBox($model,'rememberMe'); ?></td>
			<td><?php echo $form->label($model,'rememberMe'); ?></td>
		</tr>
		</table>
		
		
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('ورود به سایت' , array('class'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>
<?php echo CHtml::link('<b>[فراموشی رمز عبور]</b>',$this->createUrl('lostpassword')); ?>
       </div>
		   
<!--/write-->



