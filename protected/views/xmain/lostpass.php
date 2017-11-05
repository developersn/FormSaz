<?php
$this->pageTitle = 'فراموشی رمز عبور';
 ?>
<?php if(Yii::app()->user->hasFlash('pass')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('pass'); ?>
</div>

<?php else: ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div class=row>
	<?php echo $form->label($model,'email'); ?> &nbsp;&nbsp;&nbsp;<?php echo $form->textField($model,'email',array('size'=>35,'dir'=>'ltr')) ,
			$form->error($model,'email');
		?>
</div>	

	<?php if(CCaptcha::checkRequirements()): ?>
	<!--<div class=form><div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<br>
		<?php echo $form->textField($model,'verifyCode',array('size'=>15,'dir'=>'ltr')); ?>
		</div>
		
		<?php echo $form->error($model,'verifyCode'); ?>
	</div></div>-->
	<?php endif; ?>
	<div class=row>
	<?php $this->widget('RcaptchaWidget',array('key'=>'register')); ?>
	<?php 
	$_ = 'Rcaptcha';
	echo $form->labelEx($model,$_) ;
	echo $form->textField($model,$_) ;
	echo $form->error($model,$_);
	?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('تائید',array('class'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
		   

