
<script>
 function changestatus(id)
 {
	$("#ajax"+id).html('<img src="<?php echo Yii::app()->baseUrl .'/images/loading.gif' ?>" />');
	$.post('<?php echo $this->createUrl('changeAjax') ?>',{'id':id,'YII_CSRF_TOKEN':'<?php echo $this->csrf; ?>','token':'<?php echo $this->csrf; ?>','db':'form'},
	function(data)
	{
		
		if(data <0)
		{
			alert('خطا در تغییر وضعیت !') ;
			$("#ajax"+id).html('Error!');
		}
		else if(data==1 || data==0)
		{
			$("#ajax"+id).html("<span class='status"+data+"'></span>");
		}
		else
		{
			alert('خطایی رخ داد!');
		}
	});
 }
 
 

 </script>
<!--content-->
		<div class=content>
			<div class=title style='border-bottom:0'> <?php echo $this->pageTitle ; ?>  
			
			<a style='float:left' href='<?php echo $this->createUrl('addform') ?>'><span class=submit>ساخت فرم جدید</span></a>
			</div>
			<table class=grid border=0>
				<tr class=gridtop >
					 <td width='15%' >شناسه</td>
                     <td  >عنوان</td>
					<td >مبلغ (تومان)</td>
					
					<td >وضعیت</td>
					<td >عملیات</td>
				</tr>
			
<?php
 foreach($items as $item ) : ?>
                       <tr id='tid<?php echo $item->id; ?>'>
                            <td ><?php echo $item->id ?></td>
							<td ><?php echo CHtml::link(CHtml::encode($item->title),Yii::app()->createUrl('xmain/form?id='.$item->id)); ?></td>
							<td ><?php echo empty($item->amount)?'اختیاری':$item->amount; ?></td>
							
							
							<td>
								<a href='javascript:void(0)' onclick='changestatus(<?php echo $item->id ?>)' >
									<span id='ajax<?php echo $item->id ?>' class='status'>
										<span class='status<?php echo $item->status ?>'></span>
									</span>
								</a>
							</td>
							
							<td>
							<?php echo CHtml::link('<span class=view></span>',Yii::app()->createUrl('xmain/form?id='.$item->id));?>
							<?php echo CHtml::link('<span class=update></span>',Yii::app()->createUrl('xadmin/editform?id='.$item->id));?>
							- 
							<?php echo CHtml::link('[ آیتمهای پرداختی این فرم ]',Yii::app()->createUrl('xadmin/formitems?form_id='.$item->id));?>
							
							<?php 
							$def = Options::ins()->get('def_form');
							if($def!=$item->id)
								echo CHtml::link('[ ست کردن بعنوان فرم پیشفرض ]',Yii::app()->createUrl("xadmin/allforms?token={$this->csrf}&set_def_form_id=".$item->id));
							?>
							
							</td>
                        </tr>
<?php  endforeach ; ?>
                    </table>

					
		
			
		</div>
		
		<!--/content-->
		
		