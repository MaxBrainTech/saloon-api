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
    <style type="text/css">
      .login_logo{width:30%;}
      .button { padding: 10px !important; font-size: 18px !important; border: none !important;}
    </style>
</head>
<body id="login">
    <div id="login-wrapper" class="png_bg">
        <div id="login-top">
            <?php echo $this->Html->image('home-logo.png',array('title'=>'JTS Board','alt'=>'JTS Board','class'=>'login_logo'));?>
        </div>
		<?php echo $content_for_layout;?>
 <?php echo $this->Html->script(array( 'jquery-1.11.1.min','bootstrap.min'));
        echo $scripts_for_layout;  ?>
        <?php // echo $this->element('sql_dump');?>
</div>
    <div class="clear"></div>
    <?php // echo $this->element('sql_dump');?>
</body>
</html>
