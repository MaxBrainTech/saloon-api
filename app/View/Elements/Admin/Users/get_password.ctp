<section class="mt106">    
    <div class="AccMid">
	<?php //echo $this->element("account_sidebar");?>
	
  <?php 
			echo $this->Form->create('User', 
				array('url' => array('controller' => 'users', 'action' => 'get_password',base64_encode($email), $verification_code),
				'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
  <div class="AccMidRight" style="margin-right:150px;">
    <h2 style="border-bottom:none;font-size:20px;">Reset Your Password</h2>
    <ul class="AccSetFrm">
	
	<li>
	  <label>Email Address :</label>
	  <?php echo $this->Form->input('User.email', array('label'=>false, 'div'=>false, 'placeholder'=>'Email','error'=>false));?><br/>
			<?php
				if ($this->Form->isFieldError('email')){
					echo $this->Form->error('email');
				}
			?>
	  
	   <?php //e($form->error("User.users_email"));?>
	</li>
	<li>
	  <label><?php echo 'New password';?></label>
	  <?php echo $this->Form->input('User.password2', array('type'=>'password','label'=>false, 'div'=>false, 'placeholder'=>'Password','error'=>false));?><br/>
			<?php
				if ($this->Form->isFieldError('password2')){
					echo $this->Form->error('password2');
				}
			?>
	  
	   <?php //e($form->error("User.users_email"));?>
	</li>
	</ul>
	<div class="AccSetBtnRow">
        <?php echo ($this->Form->submit('Submit', array('class' => 'OrangeBtn', "div" => false))); ?>
		
	</div>
     </div>
		<?php echo ($this->Form->end()); ?>
    <div class="clear"></div>
    </div>
</section>