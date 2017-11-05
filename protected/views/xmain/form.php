
	
<?php echo $_form->content; ?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


<div class="row">
		<?php $_='name'; ?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_); ?>
		<?php echo $form->error($model,$_); ?>
</div>


<div class="row">
		<?php $_='email'; ?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_,array('dir'=>'ltr')); ?>
		<?php echo $form->error($model,$_); ?>
</div>


<div class="row">
		<?php $_='phone'; ?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_,array('dir'=>'ltr')); ?>
		<?php echo $form->error($model,$_); ?>
</div>

<div class="row">
		<?php $_='msg'; ?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textArea($model,$_,array('rows'=>'5','cols'=>40)); ?>
		<?php echo $form->error($model,$_); ?>
</div>



<!--key-->
<?php 
	$key1 = array();
	if( ! empty($_form->key1))
		$key1 = unserialize($_form->key1);
	
	if( ! empty($key1['name'])):
	$_ = 'key1';
?>
	<div class="row">
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_); ?>
		<?php echo $form->error($model,$_); ?>
	</div>
	<?php endif;?>
<!--/key-->	


<!--key-->
<?php 
	$key2 = array();
	if( ! empty($_form->key2))
		$key2 = unserialize($_form->key2);
	
	if( ! empty($key2['name'])):
	$_ = 'key2';
?>
	<div class="row">
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_); ?>
		<?php echo $form->error($model,$_); ?>
	</div>
	<?php endif;?>
<!--/key-->	


<!--key-->
<?php 
	$key3 = array();
	if( ! empty($_form->key3))
		$key3 = unserialize($_form->key3);
	
	if( ! empty($key3['name'])):
	$_ = 'key3';
?>
	<div class="row">
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_); ?>
		<?php echo $form->error($model,$_); ?>
	</div>
	<?php endif;?>
<!--/key-->	



<div class="row">
		<?php $c = array('dir'=>'ltr');
		
		if( ! empty($_form->amount))
			$c = array('readonly'=>'readonly','style'=>'direction:ltr');
		?>
		<?php $_='amount'; ?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_, $c); ?>
		<?php echo $form->error($model,$_); ?>
</div>


<div class="row">
		<?php
			$_='gateway_id';
			$data = array();
			$gt = Gateway::model()->findAll(array(
			'select'=>'id,title',
			'condition'=>'status=1',
			'order'=>'id desc'
			));
			foreach($gt as $row)
				$data[$row->id] = $row->title;
		?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->dropDownList($model,$_,$data); ?>
		<?php echo $form->error($model,$_); ?>
</div>

<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<br>
		<?php echo $form->textField($model,'verifyCode',array('size'=>15,'dir'=>'ltr')); ?>
		</div>
		
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('پرداخت' ); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
		   
<!--/write-->



