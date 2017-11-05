
		
		<!-- box-->
<div class=content>
			<div class=title style='border-bottom:0'> جزئیات لاگین به پنل 
			
			</div>
				<table width=100% class=grid>
				<tr class=gridtop>
					
					<td>شناسه</td>
					<td>آی پی </td>
					<td>یوزر ایجنت</td>
					<td>تاریخ</td>
					<td>وضعیت لاگین</td>
				</tr>
			<?php 
			unset($item);
			foreach( (array) $logsLogin as $item) : ?>
				<tr >
					
					<td><?php echo $item->id; ?></td>
					<td><?php echo CHtml::encode($item->ip); ?></td>
					<td><?php echo CHtml::encode($item->useragent ); ?></td>
					<td><?php echo Rh::date($item->time).'  ['.Rh::time_left($item->time).']'; ?></td>
					<td><span class=status<?php echo $item->status;?>></span></td>
				</tr>
			<?php endforeach;?>
			</table>
			
</div>