<?php if(!$this->Session->check('Facebook1.User')){
	?>
	<a href="<?php echo $login_url;?>" onclick = "window.open('<?php echo $login_url;?>','Window1'); return false;">LOGIN</a>
	
	<?php
 }?>