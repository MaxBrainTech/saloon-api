<ul class="shortcut-buttons-set">
	<li><a class="shortcut-button" href="<?php echo (Router::url(array('controller'=>'admins','action'=>'index')));?>" style="min-height:126px;"><span style="border:none;">
		<?php echo $this->Html->image('admin/icon-48-admin.png', array('alt'=>'icon','width'=>48,'height'=>48)); ?><br>
		Administrator
	</span></a></li>

	<li><a class="shortcut-button" href="<?php echo (Router::url(array('controller'=>'admins','action'=>'event_mail')));?>" style="min-height:126px;"><span style="border:none;">
		<?php echo $this->Html->image('admin/icon-48-email.png', array('alt'=>'icon','width'=>48,'height'=>48)); ?><br>
		Send Event Mail
	</span></a></li>
	 
	<li><a class="shortcut-button" href="<?php echo (Router::url(array('controller'=>'admins','action'=>'logout')));?>" style="min-height:126px;"><span style="border:none;">
		<?php echo $this->Html->image('admin/1379602599_on-off.png', array('alt'=>'icon')); ?><br>
		Logout
	</span></a></li>
	
	
</ul>
<div class="clear"></div>

</div>
