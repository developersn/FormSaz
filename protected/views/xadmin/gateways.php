<?php 
$type = array(
	 2=>'مستقیم اختصاصي'
);
?>
<script>
 function changestatus(id)
 {
	$("#ajax"+id).html('<img src="<?php echo Yii::app()->baseUrl .'/images/loading.gif' ?>" />');
	$.post('<?php echo $this->createUrl('changeAjax') ?>',{'id':id,'YII_CSRF_TOKEN':'<?php echo $this->csrf; ?>','token':'<?php echo $this->csrf; ?>','db':'gateway'},
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
			
			<a style='float:left' href='<?php echo $this->createUrl('addgateway') ?>'><span class=submit>افزودن درگاه</span></a>
			</div>
			<table class=grid border=0>
				<tr class=gridtop >
					 <td width='15%' >شناسه</td>
                     <td  >عنوان</td>
					<td >نوع</td>
					<td >Api</td>
					<td >وضعیت</td>
					<td >عملیات</td>
				</tr>
			
<?php
 foreach($items as $item ) : ?>
                       <tr id='tid<?php echo $item->id; ?>'>
                            <td ><?php echo $item->id ?></td>
							<td ><?php echo CHtml::encode($item->title) ?></td>
							<td ><?php echo $type[$item->type] ?></td>
							
							<td ><?php echo strip_tags($item->api); ?></td>
							<td>
								<a href='javascript:void(0)' onclick='changestatus(<?php echo $item->id ?>)' >
									<span id='ajax<?php echo $item->id ?>' class='status'>
										<span class='status<?php echo $item->status ?>'></span>
									</span>
								</a>
							</td>
							
							<td>
							<?php echo CHtml::link('<span class=update></span>',Yii::app()->createUrl('xadmin/editgateway?id='.$item->id));?>
							
							
							</td>
                        </tr>
<?php  endforeach ; ?>
                    </table>

					
		
			
		</div>
		
		<!--/content-->
		
		