<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
-->
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery(".req input").blur(function()
        {

            if (jQuery(".rbordertext").length > 0) {

                jQuery(this).removeClass('rbordertext');
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
            if (jQuery(".rbordertext").length > 0)
            {
                event.preventDefault();
            }

        });

    });
</script>

<!-- Begin login -->
<section class="mt106">
    <div class="login">
		<?php echo $this->Html->image('or.jpg', array('class'=>'or'));?>
                    <span class="noac">No account yet? 
					<?php echo $this->Html->link("Sign up", array('controller'=>'users', 'action'=>'register'), array('escape'=>false));?>
					</span>
                <h3>Login to your account </h3>
        <div class="inner clearfix">
			<div class="sec1">				
				<?php echo $this->element("social_connect");?>
			</div>
            <div class="sec2">
                <h4>Login with your username</h4>
			<?php 
			echo $this->Form->create('User', 
				array('url' => array('controller' => 'users', 'action' => 'login'),'type'=>'file',
			'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
			
				<?php
					$msg = $this->Session->flash() . $this->Session->flash('auth');
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
			<p>
				<ul>
                    <li>
						<div class="req">
							<?php echo $this->Form->input('User.username', array('div'=>false, 'label'=>false, 'placeholder'=>'Username', 'tabindex'=>'1'));?>
						</div>
					</li>
                    <li>
						<div class="req">
							<?php echo $this->Form->input('User.password', array('div'=>false, 'label'=>false, 'placeholder'=>'Password', 'tabindex'=>'2'));?>
						</div>
					</li>
                    <li class="forgot">
							<!--<label for="remember1">Remember me</label>-->
                        <!--<a href="https://www.girlforhire.com/user/forgot-password">Forgot Password?</a>-->
						<?php echo $this->Html->link('Forgot Password?',array('controller'=>'users','action'=>'forgot_password'));?>
						
<!--                        <a href="javascript:void(0);">Forgot Password?</a>-->
                    </li>
                    <li>
                        <!--<input type="hidden" name="redirect" value="https://www.girlforhire.com" />-->
                        <button type="submit" name="submit" class="btn2" tabindex="3" value="">Sign In</button><!--                        <input type="submit" value="Login" class="btn2">-->
                    </li>
                    <li><?php echo $this->Html->link("Can't access your account?", array('controller'=>'users', 'action'=>'register'), array('escape'=>false));?></li>
                </ul>
				<?php echo $this->Form->end();?>
		</div>
        </div>
    </div>   	
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery(".req input").blur(function()
        {

            if (jQuery(".rbordertext").length > 0) {

                jQuery(this).removeClass('rbordertext');
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
            if (jQuery(".rbordertext").length > 0)
            {
                event.preventDefault();
            }

        });

    });
</script>
</section>
<!-- end login -->