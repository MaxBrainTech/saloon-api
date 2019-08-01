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
			echo $this->Html->meta('icon');
		?>
		<?php echo $this->Html->css(array('bootstrap', 'bootstrap.min','bootstrap-theme', 'bootstrap-theme.min', 'font', 'grid', 'style', 'media'));?>
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
		<?php echo $this->Html->script(array('jquery-1.8.2.min', 'bootstrap','bootstrap.min'));?>
	<?php /*	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		*/?>
	</head>
	<script>
	$(document).ready(function(){
		$(".Navi.NaviMobile").hide();
		$(".NaviMobileIcon").click(function(){
			$(".Navi.NaviMobile").toggle("MenuShow");

		});
			
		$("#home .RightMobile").fadeIn('slow').animate({
		            'left': '660px'
		            }, {duration: 'slow', queue: false}, function() {
		            // Animation complete.
		        });
				$("#home .LeftMobile").fadeIn('slow').animate({
		            'left': '111px'
		            }, {duration: 'slow', queue: false}, function() {
		            // Animation complete.
		        });
				$("#home .iphoneMobile").fadeIn('slow').animate({
		            'top': '0px'
		            }, {duration: 'slow', queue: false}, function() {
		            // Animation complete.
		        });
		});
</script>
<body>
<div class="FullWraper">
	<?php echo $this->element("front/home_header");?>
	<?php  echo $content_for_layout;?>
    <?php echo $this->element("front/footer");?>
  
  <div class="Clear"></div>
</div>











<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>
   $(document).ready(function(){
	   $(window).bind('scroll', function() {
	   var navHeight = $( window ).height()  +970;
			 if ($(window).scrollTop() > navHeight) {
				 $('.FeaListWrp').addClass('fixed');
			 }
			 else {
				 $('.FeaListWrp').removeClass('fixed');
			 }
		});
	});
</script>

</body>
	
</html>
