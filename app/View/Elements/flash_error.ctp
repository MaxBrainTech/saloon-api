<div class="alert bg-danger" style="margin-top: 15px;">
	<em class="fa fa-lg fa-warning">&nbsp;</em>
	<?php
		echo $message;			  
	?>
	<?php 
		echo $this->Html->link($this->Html->image('admin/cross_grey_small.png', array('title'=>'Close this notification','alt'=>'close')), array(), array('class'=>'close','escape'=>false));
	?>
</div>