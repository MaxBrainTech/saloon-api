	<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
	<?php  echo ($this->Form->input('id'));?>				
	
	<p>
		<label>Name*</label>
		<?php  echo ($this->Form->input('name', array('div'=>false, 'label'=>false, "class" => "text-input small-input","maxlength"=>30)));?> 
		
	</p>
	
	<p>
		<label>Company Name*</label>
		<?php  echo ($this->Form->input('company_name', array('div'=>false, 'label'=>false, "class" => "text-input small-input","maxlength"=>30)));?> 
		
	</p>
	
	<p>
		<label>Email*</label>
		<?php  echo ($this->Form->input('email', array('div'=>false, 'label'=>false, "class" => "text-input medium-input")));?> 
		
	</p>

	<p>
		<label>Username*</label>
		<?php  echo ($this->Form->input('username', array('div'=>false, 'label'=>false, "class" => "text-input small-input")));?> 
		<br><small>Minimum length: 5 characters</small>
	</p>

	
	
	<p>
		<label>Status</label>
		<?php  echo ($this->Form->input('userStatus', array('options'=>Configure::read('Status'),'div'=>false, 'label'=>false, "class" => "small-input")));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));?>
		
		<?php echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'admins', 'action'=>'index'), array("class"=>"button", "escape"=>false)); ?>
		
	</p>
	
</fieldset>