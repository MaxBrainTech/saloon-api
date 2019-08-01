<div class="notification information png_bg">
	<?php 
		echo $this->Html->link($this->Html->image('admin/cross_grey_small.png', array('title'=>'Close this notification','alt'=>'close')), array(), array('class'=>'close','escape'=>false));
	?>
	
	<div>
	<?php
		echo $message;
	?>
	</div>
</div>