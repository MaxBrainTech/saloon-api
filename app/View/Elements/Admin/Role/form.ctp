<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
	<?php  echo ($this->Form->input('id'));?>
	
	<p>
		<label>Name*</label>
		<?php  echo ($this->Form->input('name', array('div'=>false, 'label'=>false, "class" => "text-input small-input")));?> 
		
	</p>
	<?php /* <p>
		<label>Description</label>
		<?php  echo ($this->Form->input('discription', array('div'=>false, 'label'=>false, "class" => "text-input small-input")));?> 
		
	</p> */?>
	
	<p>
		<label>Status</label>
		<?php  echo ($this->Form->input('status', array('options'=>Configure::read('Status'),'div'=>false, 'label'=>false, "class" => "small-input")));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));?>
		
		<?php echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'charities', 'action'=>'index'), array("class"=>"button", "escape"=>false)); ?>
		
	</p>
	
</fieldset>
<?php
	if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) 
	echo $this->Js->writeBuffer();
?>