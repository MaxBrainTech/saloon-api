<?php 
	echo $this->Html->css(array(
		'jquery/jquery.fileupload-ui',
		'bootstrap.min'
		
	));
	
	/* echo $this->Html->script(array(
		'jquery/jquery.iframe-transport',
		'jquery/jquery.fileupload',
		'jquery/jquery.fileupload-fp',
		'jquery/jquery.fileupload-ui',
		'locale',
		'main'
	)); */
?>  
  <div class="LeftAccMid">
		<div class="large_img">
		<?php echo $this->General->user_picture($USER['id'], $USER['image'], 'LARGE');?>
      </div>
		<div class="clear"></div>
		<div class="pictureForm">
      <?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'picture'),
			'type'=>'file',
			'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				))));
		
			
		?>
      
      <span class="btn btn-success fileinput-button">
			  <i class="icon-plus icon-white"></i>
			  <span>Browse</span>
			  <?php echo $this->Form->input("User.image", array('type'=>'file', "div" => false, "label" => false)); ?>
		 </span>
		 <button type="submit" class="btn btn-primary start">
			  <i class="icon-upload icon-white"></i>
			  <span>Upload</span>
		 </button>
      
      
      <?php echo $this->Form->end(); ?>
		</div>
  </div>