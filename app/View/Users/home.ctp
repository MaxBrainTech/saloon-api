<!--<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>
-->
<!-- Modal -->
<script>
       jQuery(document).ready(function(){
		//twitter bootstrap script
        jQuery(".like-icon").click(function(){
					var rateVal = jQuery(this).attr('id');
					var rat = rateVal.replace("rate_", "");
					//alert(rat);
					jQuery.ajax({
						type: "POST",
						url: "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'ratethis'));?>",
						//data: jQuery('form.test').serialize(),
						data: {feed_id:"1", rate:rat},
						success: function(msg){ //alert(msg);
								if(msg=='AR'){
									alert("You have already vote this video.");
								}else{
									var respon = msg.split("_");
									jQuery("#votecount_1").html(respon[0]);
									jQuery("#votecount_0").html(respon[1]);
								}
								//id="votecount_1"
								 jQuery("#modal-results").html(msg);
								jQuery("#myModal").modal('hide');    
							 },
					error: function(){
						alert("failure");
						}
					});
				});
				
		<?php	/* jQuery('#BtnSignUp').click(function(){
				//alert("test");
					//jQuery('#UserUsername').popover('show');
					var user_name = jQuery('#UserUsername').val();
					var email = jQuery('#UserEmail').val();
					jQuery.ajax({
						type: "POST",
						url: "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'checkunique'));?>",
						data: jQuery('#UserHomeForm').serialize(),
						//data: {userName:user_name, emailId:email},
						success: function(response){
								alert(response);
								var errorMsgs = "";
								var errLength = "";
								errorMsgs = response.split('__');
								errLength = response.split('__').length;
								//alert(errLength);
								jQuery('#UserUsername').popover('destroy');
								jQuery('#UserFirstName').popover('destroy');
								jQuery('#UserEmail').popover('destroy');
								jQuery('#UserPassword2').popover('destroy');
								jQuery('#UserConfirmPassword').popover('destroy');
							
						if(errLength>0){
							for(i=0;i<errLength;i++){
								var singleValidMsg = "";
								var error = "";
								singleValidMsg = errorMsgs[i];
								console.log(i+errorMsgs[i]);
								error = singleValidMsg.split("=");
								jQuery('#'+error[0]).popover('destroy');
								jQuery('#'+error[0]).popover({
										content: "<font color='black'>"+error[1]+"</font>",
										//title: 'Dynamic response!',
										html: true,
										//delay: {show: 500, hide: 100}
								}).popover('show');
							}
							return false;
							
						}else{ //alert("hgfh");							
							window.location.replace("<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'my_account'));?>");
							return true;
						}
								
								//jQuery('#UserUsername').popover({
							//		content: "<font color='black'>Username already exist</font>",
									//title: 'Dynamic response!',
							//		html: true,
									//delay: {show: 500, hide: 100}
					//			}).popover('show'); 
								
							 },
					error: function(){
						alert("failure");
						return false;
						}
					});	
					return false;
				});
				*/?>
			jQuery('#LoginFail').hide();
			jQuery('#BtnLogin').click(function(){
					//jQuery('#UserUsername').popover('show');
					var user_name = jQuery('#UserUsername').val();
					jQuery.ajax({
						type: "POST",
						url: "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'checklogin'));?>",
						data: jQuery('#UserLoginForm').serialize(),
						success: function(response){
								if(response==1){
									jQuery('#LoginFail').hide();
									window.location.replace("<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'my_account'));?>");
								}else{
									jQuery('#LoginFail').show(100);							
								}
							 },
					error: function(){
						alert("failure");
						return false;
						}
					});	
					return false;
				});
					
				jQuery('#loginFailClose').click(function(){
					jQuery('#LoginFail').hide();				
				});
			
			});
    </script>


<!-- REGISTER START -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	
		
 <div class="col-xs-10 col-sm-8 col-md-8 col-lg-6 white-bg col-xs-offset-1 col-sm-offset-2 col-md-offset-2 col-lg-offset-3 bs-example-modal-lg" style="width: 98%;margin-left:0;" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="cancel_icon">
			<a href="#" data-dismiss="modal">
					<?php echo $this->Html->image("cancel_icon.png");?>
		  </a></div>
          
		  <?php
			echo $this->Form->create('User',
				array('url' =>array('controller'=>"users", 'action'=>"register"),
				'class'=>'register-form col-xs-12 col-sm-10 col-md-10 col-xs-offset-0 col-sm-offset-1 col-md-offset-1', 
				'role'=>'form',
				
					'inputDefaults' => array(
					'error' => array(
						'attributes' => array(
							'wrap' => 'span',
							'class' => 'input-notification error png_bg'
						)
					)
				)
			));
		?>
            <div class="row">
              <h2 class="text-center"> Create an Account </h2>
            
              <div class="form-group">
                <label  >First Name </label>
				<?php echo $this->Form->input('User.first_name', array('div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'First Name', "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <label  > Last Name </label>
               <?php echo $this->Form->input('User.last_name', array('div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Last Name', "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <label  > Email Address </label>
               <?php echo $this->Form->input('User.email', array('div'=>false, 'type'=>'email', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Email Address', "data-error"=>"Bruh, that email address is invalid", "data-placement"=>"bottom"));?>
              </div>
                <div class="form-group">
                <label  >Alternate Email Address </label>
               <?php echo $this->Form->input('User.alternate_email', array('div'=>false, 'type'=>'alternate_email', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Alternate Email Address', "data-error"=>"Bruh, that alternate email address is invalid", "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
               <?php echo $this->Form->input('User.password2', array('type'=>'password', 'data-minlength'=>'8', 'div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Password', "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Confirm Password</label>
               <?php echo $this->Form->input('User.confirm_password', array('div'=>false, 'class'=>'form-control','type'=>'password', 'data-minlength'=>'8', 'label'=>false, 'placeholder'=>'Confirm Password', 'required', "data-validation-matches-match"=>"password2", "data-match"=>"#UserPassword2", "data-validation-matches-message"=>"Must match password entered above", "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Subscription Plan</label>
               <?php echo $this->Form->input('User.subscription_plan_id', array('options'=> $SubscriptionPlanList, 'empty'=>"Select Subscription Plan", 'div'=>false, 'class'=>'form-control','type'=>'select', 'data-minlength'=>'8', 'label'=>false, 'placeholder'=>'Subscription Plan', 'required', "data-validation-matches-match"=>"password2", "data-match"=>"#UserPassword2", "data-validation-matches-message"=>"Must match password entered above", "data-placement"=>"bottom"));?>
              </div>promo_code
              <div class="form-group">
                <label  > Promo Code (use if)</label>
               <?php echo $this->Form->input('User.promo_code', array('div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Promo Code', "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-submit transition" id="BtnSignUp">REGISTER</button>
              </div>
              <div class="form-group">
                <p> Already have an account - <a href="#"> LOG IN </a></p>
              </div>
            </div>
          <?php echo $this->Form->end();?>
        </div>
     
	
    </div>
  </div>
</div>
<!-- REGISTER END -->



<!-- LOGIN START -->
<div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	
		
 <div class="col-xs-10 col-sm-8 col-md-8 col-lg-6 white-bg col-xs-offset-1 col-sm-offset-2 col-md-offset-2 col-lg-offset-3 bs-example-modal-lg" style="width: 98%;margin-left:0;" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="cancel_icon">
			<a href="#" data-dismiss="modal">
					<?php echo $this->Html->image("cancel_icon.png");?>
		  </a></div>
          
		  <?php
			echo $this->Form->create('User',
				array('url' =>'#',
				'class'=>'register-form col-xs-12 col-sm-10 col-md-10 col-xs-offset-0 col-sm-offset-1 col-md-offset-1', 
				'id'=>'UserLoginForm', 
				'role'=>'form',
					'inputDefaults' => array(
					'error' => array(
						'attributes' => array(
							'wrap' => 'span',
							'class' => 'input-notification error png_bg'
						)
					)
				)
			));
		?>
            <div class="row">
              <h2 class="text-center"> Login </h2>
			  
				<div class="alert alert-danger alert-dismissible" role="alert" id="LoginFail">
				  <button type="button" class="close" id="loginFailClose"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				  <strong>Error! </strong> Incorrect username or password, please check.
				</div>
	
              <div class="form-group">

                <label> User Name
					<a href="#" class="pull-right">
						<?php echo $this->Html->image("question_icon.png");?>
					</a>
				</label>


                <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 user-url"> <div class="row" > injii.com </div> </div>
                
                <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
               	  <div class="row"> 
                  	<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1"> 
						<?php echo $this->Form->input('User.username', array('div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Username', "data-toggle"=>"popover", "data-placement"=>"bottom"));?>
						<div id="ErrorUserUsername"></div>
						<?php //7echo $this->Form->error('User.username');?>
                  </div>
                  </div>
                </div>
                
                <div class="clearfix"> </div>

              </div>
			  <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
               <?php echo $this->Form->input('User.password2', array('type'=>'password', 'data-minlength'=>'8', 'div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Password', "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-submit transition" id="BtnLogin">LOGIN</button>
              </div>
              <div class="form-group">
                <p><a href="#" data-dismiss="modal" data-toggle="modal" data-target="#forgetPasswordModel"> Forgot your password? </a></p>
              </div>
            </div>
          <?php echo $this->Form->end();?>
        </div>
    </div>
  </div>
</div>
<!-- LOGIN START -->



<!-- FORGET PASSWORD -->
<div class="modal fade" id="forgetPasswordModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	
		
 <div class="col-xs-10 col-sm-8 col-md-8 col-lg-6 white-bg col-xs-offset-1 col-sm-offset-2 col-md-offset-2 col-lg-offset-3 bs-example-modal-lg" style="width: 98%;margin-left:0;" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="cancel_icon">
			<a href="#" data-dismiss="modal">
					<?php echo $this->Html->image("cancel_icon.png");?>
		  </a></div>
          
		  <?php
			echo $this->Form->create('User',
				array('url' =>'#',
				'class'=>'register-form col-xs-12 col-sm-10 col-md-10 col-xs-offset-0 col-sm-offset-1 col-md-offset-1', 
				'id'=>'UserForgetForm', 
				'role'=>'form',
					'inputDefaults' => array(
					'error' => array(
						'attributes' => array(
							'wrap' => 'span',
							'class' => 'input-notification error png_bg'
						)
					)
				)
			));
		?>
            <div class="row">
              <h2 class="text-center"> Forgot your password? </h2>
				<div class="alert alert-danger alert-dismissible" role="alert" id="LoginFail">
				  <button type="button" class="close" id="loginFailClose"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				  <strong>Error! </strong> Incorrect username or password, please check.
				</div>
				
			  <div class="form-group">
                <label for="exampleInputPassword1">Enter your email</label>
               <?php echo $this->Form->input('User.email', array('div'=>false, 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Email', "data-placement"=>"bottom"));?>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-submit transition" id="BtnForget">SEND</button>
              </div>
              <div class="form-group">
                <p> Already have an account - <a href="#"> REGISTER </a></p>
              </div>
            </div>
          <?php echo $this->Form->end();?>
        </div>
     
	
    </div>
  </div>
</div>
<!-- LOGIN START -->

<div class="contribute"> 
	
	<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 grey-transparent login-register"> 
		<div class="container"> 
			<div class="row"> 
				<h2 class="text-center"> are you <span> <strong>Voicemail?</strong> </span> </h2> 
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
					<div class="col-xs-5 col-sm-4 col-md-3 col-lg-2 col-xs-offset-1 col-sm-offset-2 col-md-offset-3 col-lg-offset-4"> 
						<a href="<?php ?>" class="login-btn transition" data-toggle="modal" data-target="#LoginModal"> LOGIN </a>
					</div>
					<div class="col-xs-5 col-sm-4 col-md-3 col-lg-2"> 
						<a href="#" class="login-btn transition" data-toggle="modal" data-target="#myModal"> REGISTER </a>
					</div>
				</div>
			</div>
		</div>
    
    <div class="clearfix"> </div> 
</section>

<div class="clearfix"> </div>

</div>
