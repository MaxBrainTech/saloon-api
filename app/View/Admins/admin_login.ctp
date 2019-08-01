<div id="login-content">
	<?php echo $this->Form->create('User', array('url' => array('controller' => 'admins', 'action' => 'login')));?>
	<?php
	$msg= $this->Session->flash() . $this->Session->flash('auth');
	if($msg!=''){
	?>
	<div class="notification information png_bg">
		
			<?php 
			 echo $msg;
			?>
		
	</div>
	<?php
	}
	?>
	<p>
		<label>Username</label>
		<?php echo $this->Form->input("User.username", array("type" => "text", "div" => false, "label" => false, 'class'=>'text-input')); ?>
	</p>

	<div class="clear"></div>
    
	<p>
		<label>Password</label>
		<?php echo $this->Form->input("User.password", array("type" => "password", "div" => false, "label" => false, 'class'=>'text-input'));?>
	</p>

	<div class="clear"></div>
	
	<p>
		<?php echo $this->Form->submit("Login", array("class" => "button",'div'=>false)); ?>
	</p>
		
	<?php echo $this->Form->end(); ?>
</div>

	