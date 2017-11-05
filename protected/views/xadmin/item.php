<style>
.value
{
	padding:2px;
	margin:2px;
	background:rgb(248,248,248);
	border:2px solid white;
}
</style>

<!--content-->
		<div class=content>
			<div class=title style='border-bottom:0'>      <?php echo $this->pageTitle; ?></div>
			<div class=contentss style='padding:8px'>
			
				<table >
					<tr>
						<td >شناسه - وضعیت - شناسه پیگیری</td>
						<td class=value width='50%'><?php echo $item->id; ?> | <span class=status<?php echo $item->status; ?>></span> | <?php echo $item->sn_au; ?></td>
					</tr>
					
					<tr>
						<td >نام</td>
						<td class=value><?php echo CHtml::encode($item->name) ?></td>
					</tr>
					
					<tr>
						<td >ایمیل</td>
						<td class=value><?php echo CHtml::encode($item->email) ?></td>
					</tr>
					
					<tr>
						<td >شماره تلفن</td>
						<td class=value><?php echo CHtml::encode($item->phone) ?></td>
					</tr>
					
					<tr>
						<td >مبلغ به تومان</td>
						<td class=value><?php echo CHtml::encode($item->amount) ?></td>
					</tr>
					
					<tr>
						<td >فرم</td>
						<td class=value><?php echo CHtml::link("Form-{$item->form_id}",$this->createUrl('form?id='.$item->form_id)); ?></td>
					</tr>
					
					<tr>
						<td >درگاه</td>
						<td class=value><?php echo CHtml::link("Gateway-{$item->gateway_id}",$this->createUrl('editgateway?id='.$item->gateway_id)); ?></td>
					</tr>
					
					<tr>
						<td >آی پی</td>
						<td class=value><?php echo CHtml::encode($item->ip) ?></td>
					</tr>
					
					<tr>
						<td >تاریخ</td>
						<td class=value><?php echo jdate('l,d M Y (H:i)',$item->time); ?></td>
					</tr>
					
					
					
					
					
					<tr>
						<td >فیلدهای اضافی</td>
						<td class=value><?php $x = CHtml::encode($item->values); 
						echo nl2br($x);
						?></td>
					</tr>
					
					<tr>
						<td >پیغام کاربر</td>
						<td class=value><?php echo CHtml::encode($item->msg) ?></td>
					</tr>
					
					
				</table>
			
			
            </div>
				
				
				
		
			
		</div>
		
		<!--/content-->
		
		