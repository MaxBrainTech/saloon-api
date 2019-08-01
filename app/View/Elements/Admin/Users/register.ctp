<script type="text/javascript">
    jQuery(document).ready(function()
    {
		jQuery(function() {
			jQuery(".datepicker").datepicker({
				changeMonth: true,
				changeYear: true,
				maxDate: "0",
				dateFormat: 'yy-mm-dd',
				yearRange : '1880:2014'
			});
		});
	});
</script>
<!-- Begin Register -->
<section class="mt106">
    <div class="login">
		<?php echo $this->Html->image("or.jpg", array('class'=>'or'));?>
        <span class="noac">Connected with Social Media?
		<?php echo $this->Html->link("Join", array('controller'=>'users', 'action'=>'social_login'));?>
		</span>
        <h3>Create an account  </h3>
				<?php
				$msg= $this->Session->flash() . $this->Session->flash('auth');
					if($msg!=''){
					?>
						<div class="notification information png_bg">
							<?php 
								echo $msg;
							?>
						</div>
					<?php
					}
				?>
        <div class="inner clearfix">
			<?php echo $this->element("login_element");?>
                <!--
            <div class="sec1">
				<h4>Login with a social network</h4>
                <ul class="s-list">
                    <li><a href="#" class="facebook"></a></li>
                    <li><a href="https://www.girlforhire.com/users/twitter" class="twitter"></a></li>
                    <li><a href="https://www.girlforhire.com/users/linkedIn?lType=initiate" class="in"></a></li>
                    <li><a href="https://www.girlforhire.com/users/google" class="g"></a></li>
                </ul>
            </div>
				-->
			<div class="sec2">
                <h4>Sign up with your email address</h4>
                <?php
					$this->Layout->sessionFlash();			  
				?>

				<?php 
			echo $this->Form->create('User', 
				array('url' => array('controller' => 'users', 'action' => 'register'),
				'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
				
					<ul>
						<li>
						<div class="inputAction username req">
							<?php echo $this->Form->input('User.username1', array('div'=>false, 'label'=>false, 'placeholder'=>'Username'));?>
							<a href="#"></a>
						</div>
						<span class="hintText">Min 6 and max 16 characters. Only numbers, letters, dots, '-' and '_' are allowed.</span>
						</li>
						<li>
							<div class="inputAction name req">							
							<?php echo $this->Form->input('User.display_name', array('div'=>false, 'label'=>false, 'placeholder'=>'Full Name','maxlength'=>'25'));?>
							
							<a href="#"></a>
							</div>
							<span class="hintText">Max 25 characters. Only letters, space and '-' are allowed.</span>
						</li>
						<li>
							<div class="inputAction email req">
								<a href="#"></a>								
								<?php echo $this->Form->input('User.email', array('div'=>false, 'label'=>false, 'placeholder'=>'Email','autocomplete'=>'off'));?>
								
							</div>
						</li>
						<li>
							<div class="inputAction pss req">
							
							<?php echo $this->Form->input('User.password1', array('div'=>false, 'label'=>false, 'placeholder'=>'Password','minlength'=>'8','type'=>'password','autocomplete'=>'off'));?>
							<a href="#"></a>
							</div>
							<span class="hintText">Should not be less than 8 characters.</span>
						</li>

						<li>
							<div class="req">
								<!--<div id="uniform-undefined" class="selector"></div>-->
								<div class="form_select" style="width:248px;background:#EEEEEE;border:0px;">
								<?php									
									$Gender = Configure::read('App.FrontSex');
									echo $this->Form->input('User.gender', array('div'=>false, 'label'=>false, 'placeholder'=>'Gender', 'style'=>'width:230px;background:#EEEEEE;','options'=>$Gender));
								?>
								</div>
							</div>
						</li>
						<li>
							<div class="inputAction birth req">							
							<?php echo ($this->Form->input('dob', array('div' => false,'label'=>false,'type'=>'text','placeholder' => 'Birthday', "class" => "datepicker", 'id'=>'datepicker'))); ?>
							
							<a href="#"></a>
							</div>
						</li>
						<!--<li class="selectpic"><input type="file"></li>-->
						<li>
	<!--                        <input type="hidden" name="redirect" value="https://www.girlforhire.com" />-->
							<button type="submit" name="submit" class="btn2" tabindex="7" value="">DONE</button></li>
						<li class="f14">By registering, you agree to the <br> <a href="#" class="f14"> Terms of Service.</a></li>
					</ul>
                                                
				</form>           	
			</div>
        </div>
    </div>
	
    <script>
         jQuery(document).ready(function()
        {
            jQuery(".form_select select").change(function()
            {	
                if (jQuery(this)[0].selectedIndex != 0)
                {
                    jQuery(this).parent('div').removeClass("rbordertext");

                }
            });

            jQuery(".inputAction input").blur(function()
            {
                parentdiv = jQuery(this).parent('div');
                ahrefObj = parentdiv.find('a');
                textObj = jQuery(this);
                if (textObj.val() === "")
                {
                    ahrefObj.removeClass('right');
                    ahrefObj.removeClass('wrong');
                }
                else
                {
                    textObj.removeClass("rbordertext");
                    var regxUserName = /^[A-Za-z0-9-_.]+$/;
                    var regxName = /^[A-Za-z- ]+$/;
                    var regxEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;

                    if (parentdiv.hasClass("username"))
                    {
                        if (textObj.val().length >= 6 && textObj.val().length <= 16 && (textObj.val()).match(regxUserName))
                        {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else
                        {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }

                    else if (parentdiv.hasClass("name")) {
                        if (textObj.val().match(regxName))
                        {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }

                    else if (parentdiv.hasClass("email")) {
                        if ((textObj.val()).match(regxEmail))
                        {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }
                    else if (parentdiv.hasClass("pss")) {
                        if (textObj.val().length >= 8) {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }

                }
            });
            
            jQuery(".inputAction input").change(function()
            {
                parentdiv = jQuery(this).parent('div');
                ahrefObj = parentdiv.find('a');
                textObj = jQuery(this);
                if (textObj.val() === "")
                {
                    ahrefObj.removeClass('right');
                    ahrefObj.removeClass('wrong');
                }
                else
                {
                    textObj.removeClass("rbordertext");
                    var regxUserName = /^[A-Za-z0-9-_.]+$/;
                    var regxName = /^[A-Za-z- ]+$/;
                    var regxEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;

                    if (parentdiv.hasClass("birth")) {
                        if (textObj.val().length == 10 && jQuery('#datepicker').datepicker('getDate') < new Date) {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }

                }
            });
            
            jQuery(".inputAction input").keyup(function()
            {
                parentdiv = jQuery(this).parent('div');
                ahrefObj = parentdiv.find('a');
                textObj = jQuery(this);
                if (textObj.val() === "")
                {
                    ahrefObj.removeClass('right');
                    ahrefObj.removeClass('wrong');
                }
                else
                {
                    textObj.removeClass("rbordertext");
                    var regxUserName = /^[A-Za-z0-9-_.]+$/;
                    var regxName = /^[A-Za-z- ]+$/;
                    var regxEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;

                    if (parentdiv.hasClass("username"))
                    {
                        if ((textObj.val()).match(regxUserName))
                        {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else
                        {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }

                    else if (parentdiv.hasClass("name")) {
                        if ((textObj.val()).match(regxName))
                        {
                            ahrefObj.addClass('right');
                            ahrefObj.removeClass('wrong');
                        }
                        else {
                            ahrefObj.addClass('wrong');
                            ahrefObj.removeClass('right');
                        }
                    }

                    else if (parentdiv.hasClass("email")) 
					{
                       if ((textObj.val()).match(regxEmail))
                       {
                           ahrefObj.addClass('right');
                           ahrefObj.removeClass('wrong');
                       }
                       else {
                           ahrefObj.addClass('wrong');
                           ahrefObj.removeClass('right');
                       }
                    }
                    else if (parentdiv.hasClass("pss")) 
					{
                       if (textObj.val().length >= 8) {
                           ahrefObj.addClass('right');
                           ahrefObj.removeClass('wrong');
                       }
                       else {
                           ahrefObj.addClass('wrong');
                           ahrefObj.removeClass('right');
                       }
                        ahrefObj.addClass('right');
                    }
                }

            });

            jQuery(".btn2").click(function(event) {
                jQuery(".req input").each(function()
                {
                    if (jQuery(this).val() === "")
                    {
                        jQuery(this).addClass("rbordertext");
                    }
                    else
                        jQuery(this).removeClass("rbordertext");
                });
                jQuery(".req select").each(function()
                {
                    if (jQuery(this)[0].selectedIndex === 0)
                    {
                        jQuery(this).parent('div').addClass("rbordertext")
                    }
                });
                if (jQuery(".wrong").length > 0 || jQuery(".rbordertext").length > 0)
                {
                    event.preventDefault();
                }

            });

        });
        window.fbAsyncInit = function() {
            FB.init({
                appId: '658056574235722',
                status: true, // check login status
                cookie: true, // enable cookies to allow the server to access the session
                xfbml: true  // parse XFBML
            });
            // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
            // for any authentication related change, such as login, logout or session refresh. This means that
            // whenever someone who was previously logged out tries to log in again, the correct case below 
            // will be handled. 
            jQuery(".s-list .facebook").click(function() {
                FB.login(function(response) {
                    if (response.authResponse) {
                        console.log(response);
                        console.log('Welcome!  Fetching your information.... ');
                        jQuery.post("https://www.girlforhire.com/users/iajax", {
                            action: "facebook",
                            params: {
                                fb_access_token: response.authResponse.accessToken
                            }
                        },
                        function(data) {
                            if (data.status == "success") {
                                window.location = "https://www.girlforhire.com/my-ads";
                            } else {
                                alert(data.result);
                            }
                        }, "json");
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                    }
                }, {scope: 'email, user_about_me, user_birthday, user_likes, user_website,user_friends, user_education_history, user_work_history, user_photos'});
            });
        };
        // Load the SDK asynchronously
        (function(d) {
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = "//connect.facebook.net/en_US/all.js";
            ref.parentNode.insertBefore(js, ref);
        }(document));</script>
</section>
<!-- end Register -->