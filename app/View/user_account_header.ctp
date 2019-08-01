<?php //pr($userInfo);?>
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
				  <h2> <?php echo $userInfo['User']['first_name'];?> <?php echo $userInfo['User']['last_name'];?> <span> 
				  <?php 
				  if($userInfo['User']['gender']>0){
					echo ($userInfo['User']['gender']==1)?"Male":"Female"; 
				  }
				  ?>
				  </span>
				  <a href="#">
					<?php echo $this->Html->image('edit_icon.png', array());?>
				  </a> </h2>
				  <p> <?php echo $userInfo['User']['username'];?> </p>
				  <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
					<div class="row">
					<?php echo $this->Html->image('user_icon.png', array());?> 
					<?php echo date("M d, Y", strtotime($userInfo['User']['dob']));?> </p>
					<?php echo $this->Html->image('email_icon.png', array());?>
					<a href="mailto:<?php echo $userInfo['User']['email'];?>"> <?php echo $userInfo['User']['email'];?> </a> </p>
					</div>
				  </div>
				  <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
					<p class="address">
					<?php echo $this->Html->image('address_icon.png', array());?>
					<span> <?php echo $userInfo['User']['address'];?>, <?php echo $userInfo['User']['city'];?>, <?php echo $userInfo['User']['state'];?></span> </p>
				  </div>
				  <div class="clearfix"> </div>
				  <h3> My Story </h3>
				  <div class="col-xs-10 col-sm-12 col-md-12 col-lg-10">
					<div class="row"> 
						<p id="aboutMeShort">
							<?php if(strlen($userInfo['User']['about_me'])>200){
								echo nl2br(substr($userInfo['User']['about_me'], 0, 200));
								echo "..... ";
								echo $this->Html->link("read complete story", "#", array('id'=>'readComplete'));
							}else{
								echo nl2br($userInfo['User']['about_me']);						
							}
							?>
						</p>
						<p id="aboutMeDetail">
							<?php echo nl2br($userInfo['User']['about_me']);
								echo "&nbsp;&nbsp;&nbsp;".$this->Html->link("hide complete story", "#", array('id'=>'hideComplete'));
							?>
						</p>
						<script type="text/javascript">
						   jQuery(document).ready(function(){								
								jQuery('#aboutMeDetail').hide();
								jQuery('#readComplete').click(function(){
										jQuery('#aboutMeDetail').show(200);
										jQuery('#aboutMeShort').hide(200);
								});
								jQuery('#hideComplete').click(function(){
										jQuery('#aboutMeDetail').hide(200);
										jQuery('#aboutMeShort').show(200);
								});
							});
						</script>
				  </div>
				  </div>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-4 col-lg-3 col-xs-offset-0 col-sm-offset-3 col-md-offset-0 col-lg-offset-0 my-story user-details">
				  <div class="row">
					<div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
					  <h3> My Favorites </h3>
					  <?php if(!empty($userInfo['UserFavorite'])){?>
					  <?php foreach($userInfo['UserFavorite'] as $favoriteKey=>$favoriteValue){?>
					  <p> <?php echo $this->Html->image('star.png', array());?>
					  <span> <?php echo $favoriteValue['Charity']['name'];?> </span> </p>
					  <?php }?>
					  <?php }?>
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