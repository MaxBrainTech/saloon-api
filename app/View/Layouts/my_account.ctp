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
		<?php echo $this->Html->css(array('bootstrap.min', 'custom','custom-dev'));?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<header id="hrgroup">
		  <header id="header-inner">
			<div class="container">
			  <div class="row">
				<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 logo-inner">
					<?php echo $this->Html->image('logo.png');?>
				</div>
				<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
				  <ul class="nav-top">
					<li class="user-login-col"> Jennifer4you 
						<?php echo $this->Html->image('pro_pic.png');?>
					</li>
					<li><a href="#" class="transition"> HOME </a></li>
					<li><a href="#" class="transition"> MY ACCOUNT </a></li>
					<li><a href="#" class="transition"> LOGOUT </a></li>
				  </ul>
				</div>
			  </div>
			</div>
		  </header>
		</header>
		<div class="clearfix"> </div>
		<div class="contribute">
		  <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 grey-transparent ">
			<div class="container pTop-bott-50">
			  <div class="row">
				<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xs-offset-4 col-sm-offset-0 col-md-offset-0 col-lg-offset-0">
				  <div class="row">
					<div class="user-pic-col">
					<?php echo $this->Html->image('user_pic.png', array('class'=>'image-responsive'));?>
					</div>
				  </div>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-6 col-lg-7 user-details">
				  <h2> Jennifer Hunter <span> Female </span>
				  <a href="#">
					<?php echo $this->Html->image('edit_icon.png', array());?>
				  </a> </h2>
				  <p> Jennifer4you </p>
				  <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
					<div class="row">
					<?php echo $this->Html->image('user_icon.png', array());?> May 24, 1992 </p>
					<?php echo $this->Html->image('email_icon.png', array());?>
					<a href="#"> jemh@gmail.com </a> </p>
					</div>
				  </div>
				  <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
					<p class="address">
					<?php echo $this->Html->image('address_icon.png', array());?>
					<span> 350 Fifth Avenue, 34th floor. New York, NY 10118-3299 USA </span> </p>
				  </div>
				  <div class="clearfix"> </div>
				  <h3> My Story </h3>
				  <div class="col-xs-10 col-sm-12 col-md-12 col-lg-10">
					<div class="row"> 
						<p> Fusce interdum. Maecenas eu elit sed nulla dignissim interdum. Sed laoreet. Aenean pede. Phasellus porta... <a href="#"> read complete story </a> </p>
				  </div>
				  </div>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-4 col-lg-3 col-xs-offset-0 col-sm-offset-3 col-md-offset-0 col-lg-offset-0 my-story user-details">
				  <div class="row">
					<div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
					  <h3> My Story </h3>
					  <p> <?php echo $this->Html->image('star.png', array());?>
					  <span> Save The Children </span> </p>
					  <p> <?php echo $this->Html->image('star.png', array());?>
					  <span> Maecenas eu elitsed nulla interdum </span> </p>
					  <p> <?php echo $this->Html->image('star.png', array());?><span> Phasellus pretium ornare lorem </span> </p>
					  <p> <?php echo $this->Html->image('star.png', array());?> <span> Ut dictum nonummy dia </span> </p>
					  <p> <?php echo $this->Html->image('star.png', array());?> <span> Integer vel magna </span> </p>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			<div class="row">
			  <div class="nav-tabs-grid grey-transparent">
				<div class="container">
				  <div class="row">
					<ul class="nav nav-tabs">
					  <li class="active"><a href="#"> my content </a></li>
					  <li><a href="#">my broadcasts</a></li>
					  <li><a href="#">My Account</a></li>
					  <li><a href="#">edit profile</a></li>
					  <li><a href="#"> logout </a></li>
					</ul>
				  </div>
				</div>
			  </div>
			</div>
			<div class="clearfix"> </div>
		  </section>
		  <div class="clearfix"> </div>
		</div>
		<section id="collaborative-col">
		  <div class="pad0 gradient-transparent">
			<div class="container">
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				  
					<ul class="tab-sub-nav">
					  <li><a href="#"> Add Content </a></li>
					  <li><a href="#"> More Links </a></li>
					</ul>
					<ul class="tab-sort-by">
					  <li> Sort by : <a href="#" class="transition active"> Name </a></li>
					  <li> | </li>
					  <li><a href="#" class="transition"> Date </a></li>
					</ul>
				  
				</div>
			  </div>
			  
			  <div class="row">  
			  
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"><?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
				<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
					<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
						<div class="pic-hover">   
							<div class="one-fourth-icon transition"> 
								<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
								<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
							 </div>   
						</div>
					</div>
					<p> Rachel Beckwith's Mom Visits... </p>
					<p> <span> Posted on : 10 July 2014 </span> </p>
				</div>
				
			  
				
				
				
			  </div>
			  
			  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pagination-col text-center">  
				<ul class="pagination">
					<li><a href="#"> <i class="glyphicon glyphicon-chevron-left"> </i></a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#"> <i class="glyphicon glyphicon-chevron-right"> </i> </a></li>
				</ul>
			 </div>
			  
			  
			  
			  
			  
			</div>
		  </div>
		</section>

		<?php //echo $content_for_layout;?>
		<?php echo $this->element("footer");?>
		<?php echo $this->Html->script(array('jquery.min','bootstrap.min'));?>
		<?php echo $this->Html->script(array('custom'));?>
	</body>
</html>
