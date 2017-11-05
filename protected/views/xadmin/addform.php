<script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/ck/ckeditor.js?ver=12"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/ck/config.js"></script>

<div class=content>
			<div class=title style='border-bottom:0'> <?php echo $this->pageTitle ; ?>  
			
			</div>
<div class=form>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	//'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
<table border=0 class=form width='100%'>
	
	<tr class=row >
		<td valign=top><?php echo $form->labelEx($model,'title'); ?></td>
		<td valign=top><?php echo $form->textField($model,'title',array('size'=>40,'dir'=>'rtl')) ,
			$form->error($model,'title');
		?></td>
		
	</tr>
	
	
	
	<tr class=row >
		<td valign=top><?php echo $form->labelEx($model,'content'); ?></td>
		<td valign=top><?php echo $form->textArea($model,'content',array('style'=>'height: 250px;width:500px;padding:2px !important; font-family:tahoma','class'=>'ckeditor')) ,
			$form->error($model,'content');
		?></td>
		
	</tr>
	
	<tr class=row >
		<td valign=top><?php echo $form->labelEx($model,'msg'); ?></td>
		<td valign=top><?php echo $form->textArea($model,'msg',array('style'=>'height: 250px;width:500px;padding:2px !important; font-family:tahoma','class'=>'ckeditor','id'=>'textarea_id')) ,
			$form->error($model,'msg');
		?></td>
		
	</tr>
	
	
	
	

	
		<tr class=row >
		<td valign=top><?php
		$_ = 'amount';
		echo $form->labelEx($model,$_); ?>
		<br> 
		</td>
		<td valign=top dir=ltr style='text-align:right'>
		<?php echo $form->textField($model,$_,array('size'=>20,'dir'=>'ltr')) ,
			$form->error($model,$_);
		?>
		<br>
		عدد صفر به معنای این هست که مبلغ توسط کاربر وارد شود
		</td>
		
	</tr>
	
	

	

	
	<tr class=row >
		<td valign=top><?php
		$_ = 'key1_name';
		echo $form->labelEx($model,$_); ?></td>
		<td valign=top >
		<?php echo $form->textField($model,$_,array('size'=>80,'dir'=>'rtl')) ,
			$form->error($model,$_);
		?></td>
		
	</tr>
	
	<tr>
			
			<td><?php echo $form->label($model,'key1_is_req'); ?></td>
			<td><?php echo $form->checkBox($model,'key1_is_req'); ?></td>
	</tr>
	
	
	<tr class=row >
		<td valign=top><?php
		$_ = 'key2_name';
		echo $form->labelEx($model,$_); ?></td>
		<td valign=top >
		<?php echo $form->textField($model,$_,array('size'=>80,'dir'=>'rtl')) ,
			$form->error($model,$_);
		?></td>
		
	</tr>
	
	<tr>
			
			<td><?php echo $form->label($model,'key2_is_req'); ?></td>
			<td><?php echo $form->checkBox($model,'key2_is_req'); ?></td>
	</tr>
	
	
	<tr class=row >
		<td valign=top><?php
		$_ = 'key3_name';
		echo $form->labelEx($model,$_); ?></td>
		<td valign=top >
		<?php echo $form->textField($model,$_,array('size'=>80,'dir'=>'rtl')) ,
			$form->error($model,$_);
		?></td>
		
	</tr>
	
	<tr>
			
			<td><?php echo $form->label($model,'key3_is_req'); ?></td>
			<td><?php echo $form->checkBox($model,'key3_is_req'); ?></td>
	</tr>
	
	
	
	
	
	
	
</table>

<div class="row buttons">
		<?php echo CHtml::hiddenField('token',$this->csrf)  ,
		CHtml::submitButton('اعمال' , array('class'=>'submit')); ?>
	</div>
<?php $this->endWidget(); ?>
	
</div>
			

</div>