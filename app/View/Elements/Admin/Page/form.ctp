<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
	<?php  echo ($this->Form->input('id'));?>				
	
	<p>
		<?php  echo ($this->Form->input('title', array('div'=>false, 'label'=>'Title*', "class" => "text-input medium-input")));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->input('heading', array('type'=>'text','div'=>false, 'label'=>'Heading*', "class" => "text-input medium-input")));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->input('content', array('div'=>false, 'label'=>'Content*', "class" => "text-input text-area ckeditor", 'rows'=>'30')));?> 
		
	</p>
	
	<p style="margin-top:5px;">
		<?php  echo ($this->Form->input('meta_title', array('div'=>false, 'label'=>'Meta Title*', "class" => "text-input small-input")));?> 
		<br><small>Meta title displays on Titlebar of Browser</small>
	</p>
	
	<p>
		<?php  echo ($this->Form->input('meta_keywords', array('div'=>false, 'label'=>'Meta Keywords*', "class" => "text-input small-input")));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->input('meta_description', array('type'=>'textarea', 'div'=>false, 'label'=>'Meta Description*', "class" => "text-input small-input", 'rows'=>'5', 'cols'=>'10')));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));?>
		
		<?php echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'pages', 'action'=>'index'), array("class"=>"button", "escape"=>false)); ?>
		
	</p>
	
</fieldset>