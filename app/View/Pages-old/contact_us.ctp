<style type="text/css">
.login input[type="text"], .login input[type="password"] {
    background: none repeat scroll 0 0 #EEEEEE;
    border: 0 none;
    color: #141414;
    font-size: 16px;
    padding: 12px 10px;
    width: 352px;
}
</style>
<section class="mt106">
    <div class="login">		
        <h3>Conatct Us</h3>
		<?php
			$this->Layout->sessionFlash();			  
		?>		
		<div class="inner clearfix">		
            <?php
            echo $this->Form->create('Page', array('url' => array('controller' => 'pages', 'action' => 'contact_us'),
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
				<ul>
						<li style="margin-bottom:10px;">
							<div class="form_select" style="width:349px;background:#EEEEEE;border:0px;">
								<a href="#"></a>								
								<?php echo $this->Form->input('sendto', array('div'=>false, 'label'=>false, 'default'=>'1','style'=>'width:335px;background:#EEEEEE;',"options" => array('1'=>"Admins", '2'=>"Sales", '3'=>"Support")));?>
								
							</div>
						</li>
						<li style="margin-bottom:10px;">
							<div class="inputAction name req">
								<a href="#"></a>								
								<?php echo $this->Form->input('name', array('div'=>false, 'label'=>false, 'placeholder'=>'Name','autocomplete'=>'off', 'error'=>false));?>
								<br/>
								<?php
									if ($this->Form->isFieldError('name')) {
										echo $this->Form->error('name');
									}
								?>
							</div>
						</li>
						<li style="margin-bottom:10px;">
							<div class="inputAction subject req">
								<a href="#"></a>								
								<?php echo $this->Form->input('subject', array('div'=>false, 'label'=>false, 'placeholder'=>'Subject','autocomplete'=>'off', 'error'=>false));?>
								<br/>
								<?php
									if ($this->Form->isFieldError('subject')) {
										echo $this->Form->error('subject');
									}
								?>
							</div>
						</li>
						<li style="margin-bottom:10px;">
							<div class="inputAction email req">
								<a href="#"></a>								
								<?php echo $this->Form->input('email', array('div'=>false, 'label'=>false, 'placeholder'=>'Email','autocomplete'=>'off', 'error'=>false));?>
								<br/>
								<?php
									if ($this->Form->isFieldError('email')) {
										echo $this->Form->error('email');
									}
								?>
							</div>
						</li>						
						<li style="margin-bottom:10px;">
							<div class="inputAction message req">
							<a href="#"></a>
							<?php  echo ($this->Form->input('message', array('type'=>'textarea', 'div'=>false, 'label'=>false,'placeholder'=>'Message', "class" => "text-input small-input", 'rows'=>'5', 'cols'=>'13','style'=>'width:351px', 'error'=>false)));?> 
<br/>
								<?php
									if ($this->Form->isFieldError('message')) {
										echo $this->Form->error('message');
									}
								?>
							</div>
						</li>
						<li>
	<!--                        <input type="hidden" name="redirect" value="https://www.girlforhire.com" />-->
							<button type="submit" name="submit" class="btn2" tabindex="7" value="">Send</button>
							
						</li>						
				</ul>	
            <?php
            echo ($this->Form->end());
            ?>
		</div>		
	</div> <!-- End #tab2 -->        
</section>
   
