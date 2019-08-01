<?php	echo ($this->Html->script(array('ckeditor/ckeditor')));?>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Edit Settings</h3>
		
		<ul class="content-box-tabs">
			<li>* required fields</li> <!-- href must be unique and match the id of target div -->
			
		</ul>
		
		<div class="clear"></div>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div style="display: block;" class="" id="tab2">
			
			<?php
				$this->Layout->sessionFlash();			  
			?>
				
			<?php 
			echo $this->Form->create('Setting', 
				array('url' => array('controller' => 'settings', 'action' => 'index'),
			'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
				
				<?php echo ($this->element('Admin/Setting/form'));?>
				
				<div class="clear"></div><!-- End .clear -->
				
			<?php
				echo ($this->Form->end());
			?>	
			
		</div> <!-- End #tab2 -->        
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<script>
$(document).ready(function(){
	var count =0;
	$("#SettingAdminIndexForm").submit(function(){
	
		
		if($("#1SettingValue").val() != "")
		{
			var email = $("#1SettingValue").val();
			
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			if (!filter.test(email)) {
			if(count <1)	{
				$("#1SettingValue").after('<span class="input-notification error png_bg">Please provide a valid email address.</span>');
				count++;
				return false;
			}else{
				return false;
			}
			}
			
		}
		
		return true;
	});

});
</script>