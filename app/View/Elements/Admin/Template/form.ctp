<script>
	$(document).ready(function(){
		$('#FooterTemplateFooterId').attr('name','data[FooterTemplate][][footer_id]');
	});
</script>
<style type="text/css">
.input-notification{
float: left;
position: relative;
width: 100%;
}
</style>
<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
	<?php  echo ($this->Form->input('id'));
	
	?>
	
	<p>
		<?php  echo ($this->Form->input('subject', array('div'=>false, 'label'=>'Email Subject*', "class" => "text-input large-input")));?> 
		
	</p>
	<p>&nbsp;</p>
	<p>
		<?php  echo ($this->Form->input('content', array('div'=>false, 'label'=>'Content*', "class" => "text-input text-area ckeditor", 'rows'=>'30')));?> 
		
	</p>
	
	<p>
		<?php  echo ($this->Form->hidden('description', array('div'=>false, 'label'=>'Content*', "class" => "text-input text-area ckeditor", 'rows'=>'30')));?> 
		
	</p>
	
	
	<p>
		<?php  echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));?>
		
		<?php echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'templates', 'action'=>'index'), array("class"=>"button", "escape"=>false)); ?>
			
	</p>
	
</fieldset>