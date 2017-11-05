

<!--content-->
		<div class=content>
			<div class=title style='border-bottom:0'>      <?php echo $this->pageTitle; ?></div>
			<div class=contentss style='padding:8px'>
			
			<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


<div class="row">
		<?php $_='title';?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_); ?>
		<?php echo $form->error($model,$_); ?>
	</div>
	
	<div class="row">
		<?php $_='api';?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->textField($model,$_); ?>
		<?php echo $form->error($model,$_); ?>
	</div>
	
	<div class="row">
		<?php $_='type';?>
		<?php echo $form->labelEx($model,$_); ?><br>
		<?php echo $form->dropDownList($model,$_,array(2=>'اختصاصی مستقیم')); ?>
		<?php echo $form->error($model,$_); ?>
	</div>

	


	<div class="row buttons">
		<?php echo CHtml::submitButton('اعمال' , array('class'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>

       </div>
                </div>
				
				
				
		
			
		</div>
		
		<!--/content-->
		
		