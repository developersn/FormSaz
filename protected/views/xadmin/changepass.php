 <?php
 $this->pageTitle = 'ویرایش پسورد';

 
 ?>


<!--content-->
		<div class=content>
			<div class=title style='border-bottom:0'>      تغییر پسورد مدیر</div>
			<div class=contentss style='padding:8px'><div class="form">

<?php $form2=$this->beginWidget('CActiveForm', array(
	'id'=>'user-change-pass-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	

	<table border=0 class=form width=''>
	<tr style='display:none'><td></td></tr>
	
	<tr class=row >
		<td valign=top><?php echo $form2->label($model2,'old'); ?></td>
		<td valign=top><?php echo $form2->passwordField($model2,'old',array('size'=>35,'dir'=>'ltr')) ,
			$form2->error($model2,'old');
		?></td>
		<td valign=top>
		</td>
	</tr>
	
		<tr class=row >
		<td valign=top><?php echo $form2->label($model2,'password'); ?></td>
		<td valign=top><?php echo $form2->passwordField($model2,'password',array('size'=>35,'dir'=>'ltr')) ,
			$form2->error($model2,'password');
		?></td>
		<td valign=top>
		</td>
	</tr>
	
		<tr class=row >
		<td valign=top><?php echo $form2->label($model2,'repass'); ?></td>
		<td valign=top><?php echo $form2->passwordField($model2,'repass',array('size'=>35,'dir'=>'ltr')) ,
			$form2->error($model2,'repass');
		?></td>
		<td valign=top>
		</td>
	</tr>
	
	
	
</table>

	

	<div class="row buttons">
		<?php echo 
		CHtml::hiddenField('token',$this->csrf) ,
		CHtml::submitButton('ویرایش',array('class'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
                </div>
		
			
		</div>
		
		<!--/content-->
		
		