<!--content-->
		<div class=content>
			<div class=title> <?php echo $this->pageTitle ; ?> - <a href="<?php echo $this->createUrl('logs?del=1&token=' . $this->csrf); ?>">حذف همه گزارشات</a> </div>
			<table class=grid border=0>
				<tr class=gridtop>
					 <td width='7%' >شناسه</td>
                            <td width='12%' >آی پی</td>
                            <td width='18%' >عنوان</td>
							<td>پیغام</td>
							<td width='15%'>زمان</td>
				</tr>
			
<?php foreach($items as $item ) : ?>
                        <tr>
                            <td ><?php echo $item->id ?></td>
							<td ><?php echo $item->ip ?></td>
							<td ><?php echo $item->title ?></td>
							<td ><?php echo $item->msg ?></td>
							<td ><?php echo jdate('d M Y (H:i)',$item->time) ?></td>
                        </tr>
<?php endforeach ; ?>
                    </table>

					<center>
					<?php $this->widget('PagerWidget',array('min'=>$pager->_min,'max'=>$pager->_max , 'items'=>$items,'action'=>$this->createUrl('logs'))); ?>
					</center>
		
			
		</div>
		
		<!--/content-->
		
		