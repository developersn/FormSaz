
<!--content-->
		<div class=content>
			<div class=title style='border-bottom:0'> <?php echo $this->pageTitle ; ?>  
			</div>
			<table class=grid border=0>
				<tr class=gridtop >
					 <td >شناسه</td>
					 <td >نام پرداخت کننده</td>
					<td >ایمیل پرداخت کننده</td>
                     <td  >کد پیگیری</td>
					<td >مبلغ (تومان)</td>
					
					<td >فرم</td>
					<td >آی پی</td>
					<td >تاریخ</td>
					
					<td >وضعیت</td>
					<td >عملیات</td>
				</tr>
			
<?php
 foreach($items as $item ) : ?>
                       <tr id='tid<?php echo $item->id; ?>'>
                            <td ><?php echo $item->id; ?></td>
							<td ><?php echo CHtml::encode($item->name) ?></td>
							<td ><?php echo CHtml::encode($item->email) ?></td>
							
							<td ><?php echo $item->sn_au; ?></td>
							<td ><?php echo $item->amount; ?></td>
							<td ><?php echo CHtml::link("Form-{$item->form_id}",$this->createUrl('form?id='.$item->form_id)); ?></td>
							<td ><?php echo CHtml::encode($item->ip); ?></td>
							<td ><?php echo jdate('l,d M Y (H:i)',$item->time); ?></td>
							
							
							<td ><span class=status<?php echo $item->status; ?>></span></td>
							<td ><?php echo CHtml::link('[مشاهده]',$this->createUrl('showFormItem?id='.$item->id)); ?></td>
							
                        </tr>
<?php  endforeach ; ?>
                    </table>
					<center>
					<?php $this->widget('PagerWidget',array('min'=>$pager->_min,'max'=>$pager->_max ,'params'=>'status,form_id,gateway_id', 'items'=>$items,'action'=>$this->createUrl($this->action->id))); ?>
					</center>
					
		
			
		</div>
		
		<!--/content-->
		
		