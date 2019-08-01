<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Admin Edit</h3>
		
		<ul class="content-box-tabs" style="">
			<li>* required fields</li>
			
		</ul>
		
		<div class="clear"></div>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content" style="">
		
		<div >
		<?php 
			echo $this->Form->create('SaleUser', 
				array('url' => array('controller' => 'sale_users', 'action' => 'sales_admin_edit'),
			'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
		<p>
			<?php echo $this->Form->input("name", array("type" => "text", "div" => false, "label" => "Name*", 'class'=>'text-input small-input', 'required'=>'required')); ?>
		</p>
		
		<p>
			<?php echo $this->Form->submit("Submit", array("class" => "button",'div'=>false)); ?>
			<?php 
	            echo ($this->Html->link("Cancel", array('controller' => 'sale_users', 'action' => 'sales_admin_list'), array('escape' => false,'class'=>'button')));
		
            ?>
		</p>
			
				<div class="clear"></div><!-- End .clear -->
		<?php echo ($this->Form->end()); ?>	
			
		</div> <!-- End #tab2 -->        
		
	</div> <!-- End .content-box-content -->
	
</div>