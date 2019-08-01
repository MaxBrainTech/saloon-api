<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo  $title_for_layout;?> | <?php echo Configure::read('Site.title');?></title>    
	<?php
	  if(isset($description_for_layout)){
	   echo $this->Html->meta('description', $description_for_layout);
	  } 
	  if(isset($keywords_for_layout)){
	   echo $this->Html->meta('keywords', $keywords_for_layout);	
	  }	 	 	
     echo $this->Html->meta('icon');     	 
	 echo $this->Html->css(array('reset','admin','invalid'));
     echo $scripts_for_layout;
    ?>
    <!--[if lte IE 6]><style>
        img { behavior: url("<?php echo Configure::read('App.SiteUrl');?>/css/iepngfix.htc") }
		</style><![endif]-->
	<style>	
	.error1 {
		font-size:15px;
		background-color:#FFCECE;
		border-color: #DF8F8F;
		color: #665252;
		text-align:center;
	}
	</style>	
</head>

<body id="login">
	
	<div id="login-wrapper" class="png_bg">
		<div id="login-top">
			<?php echo $this->Html->image('logo.png');?>
		</div>
		<p class="error1">
			
			The requested URL was not found on this server.
		</p>
	</div>
	<div class="clear"></div>
	<?php echo $this->element('sql_dump');?>
</body>
</html>