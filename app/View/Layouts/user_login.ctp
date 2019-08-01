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
	 echo $this->Html->css(array('bootstrap.min','datepicker3','styles'));
     
    ?>
    <!--[if lte IE 6]><style>
        img { behavior: url("<?php echo Configure::read('App.SiteUrl');?>/css/iepngfix.htc") }
		</style><![endif]-->
</head>
<body>
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<?php echo $content_for_layout;?>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
	
 <?php echo $this->Html->script(array( 'jquery-1.11.1.min','bootstrap.min'));
        echo $scripts_for_layout;  ?>
        <?php // echo $this->element('sql_dump');?>
</body>
</html>
