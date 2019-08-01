<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
	<?php  echo ($this->Form->input('id'));?>				
	
	<p>
		<label>First Name</label>
		<?php  echo ($this->Form->input('first_name', array('div'=>false, 'label'=>false,"autocomplete"=>"off", "class" => "text-input small-input","maxlength"=>30)));?> 
		
	</p>
	
	<p>
		<label>Last Name</label>
		<?php  echo ($this->Form->input('last_name', array('div'=>false, 'label'=>false,"autocomplete"=>"off", "class" => "text-input small-input","maxlength"=>30)));?> 
		
	</p>
	 <?php if ($this->params['action'] == 'admin_add') { ?>
	<p>
		<label>Username*</label>
		<?php  echo ($this->Form->input('username', array('div'=>false, 'label'=>false,"autocomplete"=>"off", "class" => "text-input small-input")));?> 
		<br><small>Minimum length:5 characters</small>
	</p>
	<?php
	}
	?>
	 <?php if ($this->params['action'] == 'admin_add') { ?>
	<p>
		<label>Password*</label>
		<?php  echo ($this->Form->input('password2', array("type" => "password", 'div'=>false,"autocomplete"=>"off", 'label'=>false, "class" => "text-input small-input")));?> 
		<br><small>Minimum length: 8 characters</small>
	</p>
	
	 <p>
            <?php echo ($this->Form->input('confirm_password', array('autocomplete' => 'off', "type" => "password", 'div' => false, 'label' => 'Confirm Password*', "class" => "text-input small-input"))); ?>
            <br><small>Re-Type Password here</small>
        </p>
	 <?php } ?>
	<p>
		<label>Email*</label>
		<?php  echo ($this->Form->input('email', array('div'=>false, 'label'=>false, "class" => "text-input small-input")));?> 
		<?php  echo ($this->Form->input('role_id', array("type" => "hidden"  , 'div'=>false, 'label'=>false, )));?> 
	</p>
	<?php
	if(isset($user_data) && !empty($user_data) && $user_data['User']['role_id'] != Configure::read('App.Admin.role'))
	{
	?>
	<p>
		<label>Status</label>
		<?php  echo ($this->Form->input('status', array('options'=>Configure::read('Status'),'div'=>false, 'label'=>false, "class" => "small-input")));?> 
	</p>
	<?php
	}
	?>
	
	<p>
		<?php  echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));?>
		<?php echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'admins', 'action'=>'index','Admin'), array("class"=>"button", "escape"=>false)); ?>
	</p>
	
</fieldset>