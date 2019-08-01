<?php	echo ($this->Html->script(array('ckeditor/ckeditor')));?>

<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Edit Template</h3>
		
		<ul class="content-box-tabs">
			<li>* required fields</li> <!-- href must be unique and match the id of target div -->
			
		</ul>
		
		<div class="clear"></div>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		
		<div style="display: block;" class="" id="tab2">
			<?php if(!empty($this->data['Template']['description'])):?>
				<div class="notification information png_bg">
					<a class="close" href="#"><?php echo($this->Html->image('admin/cross_grey_small.png', array('title'=>'Close this notification','alt'=>'close')));?></a>
					<div>
						<?php echo($this->data['Template']['description']);?>
					</div>
				</div>
			<?php endif ?>
			<?php
				$this->Layout->sessionFlash();			  
			?>
				
			<?php 
			echo $this->Form->create('Template', 
				array('url' => array('controller' => 'templates', 'action' => 'edit'),
			'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
				
				<?php echo ($this->element('Admin/Template/form'));?>
				
				<div class="clear"></div><!-- End .clear -->
				
			<?php
				echo ($this->Form->end());
			?>	
			
		</div> <!-- End #tab2 -->        
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->