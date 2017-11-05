<?php foreach($items as $item): ?>
	<div class=form>
		<?php $am =empty($item->amount)?'مبلغ دلخواه':$item->amount.' تومان'; ?>
		<?php echo CHtml::link(CHtml::encode($item->title).' » '.$am,$this->createUrl('form',array('id'=>$item->id))); ?> 
	</div>
<?php endforeach; ?>