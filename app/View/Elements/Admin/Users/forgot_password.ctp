<section class="mt106">    
    <div class="AccMid">
	<?php //echo $this->element("account_sidebar");?>	
  <?php 
			echo $this->Form->create('User', 
				array('url' => array('controller' => 'users', 'action' => 'forgot_password'),
							'inputDefaults' => array(
							'error' => array(
								'attributes' => array(
									'wrap' => 'span',
									'class' => 'input-notification error png_bg'
								)
							)
						)
					)
				);?>
  <div class="AccMidRight" style="margin-right:150px;">
    <h2 style="border-bottom:none;font-size:20px;">Forgot your password?</h2>
	<?php  $this->Layout->sessionFlash(); ?>   
    <ul class="AccSetFrm">
	<li>
		<span>Enter your e-mail address in the field below and we will send your password.</span>
	</li>
	<li>
	  <label>Email Address :</label>
	  <?php echo $this->Form->input('User.email', array('div'=>false, 'label'=>false, 'placeholder'=>'Email','error'=>false));?><br/>
			<?php
				if($this->Form->isFieldError('email')){
					echo $this->Form->error('email');
				}
			?>	  
	   <?php //e($form->error("User.users_email"));?>
	</li>
	</ul>
	<div class="AccSetBtnRow">
		<?php
            echo $this->Html->link("Back", array('controller' => 'users', 'action' => 'register'), array("class" => "GrayBtn", "escape" => false));?>
        <?php echo ($this->Form->submit('Send', array('class' => 'OrangeBtn', "div" => false))); ?>
	</div>
     </div>
		<?php echo ($this->Form->end()); ?>
    <div class="clear"></div>
    </div>
</section>