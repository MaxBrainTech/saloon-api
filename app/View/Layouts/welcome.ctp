<?php echo $this->Html->docType('html5');?>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			<?php echo $title_for_layout;?> | 
			<?php echo Configure::read('Site.title');?>
		</title>
		<?php
			echo $this->Html->meta(
				'favicon.ico',
				'/favicon.ico',
				array('type' => 'icon')
			);
		?>
	<?php echo $this->Html->css(array('bootstrap', 'bootstrap.min','bootstrap-theme', 'font', 'grid', 'style', 'media'));?>
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
		<?php echo $this->Html->script(array('bootstrap','bootstrap.min', 'jquery-1.8.2.min', 'main'));?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
<body>


<div class="FullWraper">
  <?php echo $this->element("front/header");?>
  
  <div class="FullWraper InnerMidBg">
    <div class="FullWraper InnerMidUserBg">
      <div class="Container">
        <div class="MyAccMid">
          <div class="MyAccMidTp">
            <h2>My Account</h2>
            <div class="MyAccTpRight">
              <div class="AccEmailCon">
              <?php if(!empty($user['User']['paypal_email'])){ ?>
                <p><?php echo $user['User']['paypal_email'];?></p>
                <p><?php echo $user['User']['unique_no']?></p>
             <?php } ?>   
              </div>
              <?php if($user['User']['affiliate_status']){?>
              <div class="AccTpRiBtn"> <a href="#" class="BlueBtnRi">Become an Affiliate</a> </div>
              <?php } ?>
            </div>
          </div>
          <div class="AccMidBox">
            <?php echo $this->element("front/left");?>
            <?php  echo $content_for_layout;?>
          </div>
        </div>
        <div class="Clear"></div>
      
    </div>
    <div class="Clear"></div>
  </div>
  <?php echo $this->element("front/footer");?>
  <div class="Clear"></div>
</div>


<?php /*
<div class="FullWraper">
  <div class="FullWraper TopBgBan" id="home">
    <?php echo $this->element("front/header");?>
   </div>
   <div class="FullWraper InnerMidBg">
      <?php  echo $content_for_layout;?>
      <div class="Clear"></div>
    </div>
    <div class="Clear"></div>
    <?php echo $this->element("front/footer");?>
  <div class="Clear"></div>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php echo $this->Html->script(array('configuration'));?>
*/?>
</body>
</html>
