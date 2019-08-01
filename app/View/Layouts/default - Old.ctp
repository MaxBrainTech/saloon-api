<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
   <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]--><head>
		<?php echo $this->Html->charset(); ?>
         <meta charset="utf-8">
         <!--[if IE]>
         <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
         <![endif]-->
<title><?php echo $title_for_layout;?> | <?php echo Configure::read('Site.title');?></title>
<?php	
	if(isset($description_for_layout)){
		echo $this->Html->meta('description', $description_for_layout);
	} 
	if(isset($keywords_for_layout)){
		echo $this->Html->meta('keywords', $keywords_for_layout);	
	}	 	 	
	echo $this->Html->meta('icon');
	  ?>	 
	 <meta name="description" content="Girl for Hire. Stunning App Showcase.">
	 <meta name="keywords" content="neue, app, ios, android, showcase, landing page, stunning">
	 <meta name="author" content="Aether Themes">
	 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	 <link rel="shortcut icon" href="images/favicon.png">
	 <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	 <link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITE_URL;?>/img/apple-touch-icon-72x72.png">
	 <link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITE_URL;?>/img/apple-touch-icon-114x114.png">
	 
	  <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	
	<?php	
	  echo $this->Html->script(array(
				'jquery-1',
				'jquery',
				'jquery-ui',
				'custom',
				'custom_cat'
		  ));
	  echo $scripts_for_layout;
	 ?>

	 <!--[if lt IE 9]>
	 <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	 <![endif]-->
		 
	<?php
	echo $this->Html->css(array(
			'css',
			'reset',
			'grid',
			'style',
			'newStyle',
			'form',
			'jquery-ui',
			'my-style',
		));
	?>
         <!--[if lt IE 9]>
         <link rel="stylesheet" type="text/css" href="https://www.girlforhire.com/frontend/stylesheets/ie.css" />
         <![endif]-->
      </head>
      <body>

	<!-- Begin Mobile Navigation -->
	<div id="mobile-nav">
	<div class="container clearfix">
	<div>
		<!-- Mobile Nav Button -->
		<div class="navigationButton sixteen columns clearfix">
			<?php echo $this->Html->image('mobile-nav.png', array('alt'=>'Navigation', 'height'=>'17', 'width'=>'29'));?>
		</div>
		<!-- Mobile Nav Links -->
		<div style="display: none;" class="navigationContent sixteen columns clearfix">
			<ul>
				<li><a href="#section1">Hello</a></li>
				<li><a href="#section2">Overview</a></li>
				<li><a href="#section3">Animations</a></li>
				<li><a href="#section4">Compatibility</a></li>
				<li><a href="#section5">Detail</a></li>
				<li><a href="#section6">Gallery</a></li>
			</ul>
		</div>
	</div>
	</div>
	</div>
	<!-- End Mobile Navigation -->   
	<?php echo $this->element("header");?>
	
	<?php echo $content_for_layout;?>
	
	
	<!-- Begin Footer -->
	<?php echo $this->element("footer");?>
	<!-- End Footer -->                                         
</body>
</html>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-48961393-1', 'girlforhire.com');
ga('send', 'pageview');

</script>