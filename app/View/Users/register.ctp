
<!-- Begin Register -->

<div class="Container">
        <div class="SignupMidBox">
          <div class="SignupMidTp">
            <h2>Sign Up</h2>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam eu nulla.</p>
          </div>
          <div class="SignupMidBotm">
            <div class="SignupMidLeft">
              <div class="ChainBgLeft"></div>
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
					));
					?>	
              
              <ul class="SignupFrmLeft">
                <!-- li>
                  <label>Username<span class="Req">*</span></label>
                  <input name="" type="text" class="RegFild">
                </li-->
                
                
                <?php 
                 $msg = $this->Session->flash() . $this->Session->flash('auth');
                if($msg!='' && ($this->request->params['action']=="register")){
					?>
						<li><label><span class="Req">
							<?php 
								echo $msg;
							?>
						</span></label></li>
					<?php
					}?>
				<li>	
				  <label>First Name</label>
                  <?php echo $this->Form->input('User.first_name', array('div'=>false, 'label'=>false,  'class'=>'RegFild'));?>
                </li>
                <li>
                 <li>
                
                  <label>Last Name</label>
                  <?php echo $this->Form->input('User.last_name', array('div'=>false, 'label'=>false,  'class'=>'RegFild'));?>
                </li>	
				<li>
                  <label>Email Address<span class="Req">*</span></label>
                  <?php echo $this->Form->input('User.email', array('div'=>false, 'label'=>false,  'class'=>'RegFild'));?>
                  <!-- input name="" type="text" class="RegFild" -->
                </li>
                <li>
                  <label>Password<span class="Req">*</span></label>
                  <?php echo $this->Form->input('User.password2', array('div'=>false, 'label'=>false, 'minlength'=>'8','type'=>'password','autocomplete'=>'off', 'class'=>'RegFild'));?>
                  <!--  input name="" type="text" class="RegFild" -->
                </li>
                <li>
                  <label>Confirm Password<span class="Req">*</span></label>
                  <?php echo $this->Form->input('User.confirm_password', array('div'=>false, 'label'=>false, 'minlength'=>'8','type'=>'password','autocomplete'=>'off',  'class'=>'RegFild'));?>
                  <!--   input name="" type="text" class="RegFild" -->
                </li>
                <li>
                  <label>Select Subscription Plan:<span class="Req">*</span></label>
                  <?php echo $this->Form->input('User.subscription_plan_id', array('type'=>'select', 'options'=>$subscription_plans, 'empty'=>'Select Plan',  'div'=>false, 'label'=>false,  'class'=>'RegFild'));?>
                  <!--  select name="" class="RegFild">
                  </select-->
                </li>
                <li class="align-center">
                	<?php echo $this->Form->input('User.affiliate_status', array('type'=>'checkbox','div'=>false, 'label'=>false));?>
                   Become an Affiliate </li>
                <li class="align-center">
                <button type="submit" name="signup" class="BlueBtnFrm" tabindex="7" value="">SIGN UP</button>
                  <!-- input type="button" class="BlueBtnFrm" value="SIGN UP" name="" -->
                </li>
              </ul>
              <?php echo $this->Form->end();?> 
            </div>
            <div class="SignupMidRight">
              <h3>Already have an account?</h3>
              <p><?php echo $this->Html->image('bdr-log-ti.png', array('class'=>'bdr-log-ti', 'alt' => 'bg', 'title' => 'bg'))?>
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
					));
				?>	
              <ul class="SignupFrmLeft">
                <li>
                  <label>Email<span class="Req">*</span></label>
                  <?php echo $this->Form->input('User.login_email', array('div'=>false, 'label'=>false, 'class'=>'RegFild'));?>
                  <!--  input name="" type="text" class="RegFild" -->
                </li>
                <li>
                  <label>Password<span class="Req">*</span></label>
                  <?php echo $this->Form->input('User.password', array('div'=>false, 'label'=>false,  'class'=>'RegFild'));?>
                  <!--  input name="" type="text" class="RegFild"-->
                </li>
                <li class="align-center">
                  <input name="" type="checkbox" value="">
                  Remember me </li>
                <li class="align-center">
                 <!-- input type="button" class="BlueBtnFrm LogBtnBg" value="LOG IN" name="" -->
                  <button type="submit" name="login" class="BlueBtnFrm LogBtnBg"  value="">LOG IN</button>
                </li>
                
                <li class="align-center">
                <?php echo $this->Html->link('Forgot Password?',array('controller'=>'users','action'=>'forgot_password'), array('class'=>'Forgot'));?>
                <!-- a href="#" class="Forgot">Forgot your password?</a -->
                </li>
              </ul>
                <?php echo $this->Form->end();?> 
            </div>
          </div>
        </div>
        <div class="Clear"></div>
      </div>


<!-- end Register -->




<?php /*		<section class="mt106">
    <div class="login">
		<?php echo $this->Html->image('or.jpg', array('class'=>'or'));?>
                    <span class="noac">No account yet? 
					<?php echo $this->Html->link("Sign up", array('controller'=>'users', 'action'=>'register'), array('escape'=>false));?>
					</span>
                <h3>Registration to your account </h3>
        <div class="inner clearfix">
			
            <div class="sec2">
              
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
			));
		?>
			
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
							<div class="inputAction name req">							
							<?php echo $this->Form->input('User.first_name', array('div'=>false, 'label'=>"First Name", 'placeholder'=>'First Name','maxlength'=>'25'));?>
							<a href="#"></a>
							</div>
							<span class="hintText">Max 25 characters. Only letters, space and '-' are allowed.</span>
						</li>
						<li>
							<div class="inputAction name req">							
							<?php echo $this->Form->input('User.last_name', array('div'=>false, 'label'=>"Last Name", 'placeholder'=>'Last Name','maxlength'=>'25'));?>
							
							<a href="#"></a>
							</div>
							<span class="hintText">Max 25 characters. Only letters, space and '-' are allowed.</span>
						</li>
					
						<li>
						<div class="inputAction email req">
							<?php echo $this->Form->input('User.email', array('div'=>false, 'label'=>"Email", 'placeholder'=>'Email'));?>
							<a href="#"></a>
						</div>
						<span class="hintText">Min 6 and max 16 characters. Only numbers, letters, dots, '-' and '_' are allowed.</span>
						</li>
						<li>
							<div class="inputAction pss req">
							
							<?php echo $this->Form->input('User.password2', array('div'=>false, 'label'=>false, 'placeholder'=>'Password','minlength'=>'8','type'=>'password','autocomplete'=>'off'));?>
							<a href="#"></a>
							</div>
							<span class="hintText">Should not be less than 8 characters.</span>
						</li>
						<li>
							<div class="inputAction pss req">
							
							<?php echo $this->Form->input('User.confirm_password', array('div'=>false, 'label'=>false, 'placeholder'=>'Confirm Password','minlength'=>'8','type'=>'password','autocomplete'=>'off'));?>
							<a href="#"></a>
							</div>
							<span class="hintText">Should not be less than 8 characters.</span>
						</li>
						<li>
							<div class="inputAction subscription req">
							
							<?php echo $this->Form->input('User.subscription_plan_id', array('type'=>'select', 'options'=>$subscription_plans, 'empty'=>'Select Subscription Plan',  'div'=>false, 'label'=>false));?>
							<a href="#"></a>
							</div>
						</li>
						<li>
							<div class="inputAction subscription req">
							
							<?php echo $this->Form->input('User.affiliate_status', array('type'=>'checkbox','div'=>false, 'label'=>"Allow as affiliate"));?>
							<a href="#"></a>
							</div>
						</li>
						<li>
						<!-- <input type="hidden" name="redirect" value="https://www.girlforhire.com" />-->
						<button type="submit" name="submit" class="btn2" tabindex="7" value="">Signup</button>
						</li>
					</ul>
				<?php echo $this->Form->end();?>     	
		</div>
        </div>
    </div>

		*/?>
	