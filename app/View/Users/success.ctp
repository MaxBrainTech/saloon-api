<section class="mt106">    
    <div class="AccMid">
	<?php //echo $this->element("account_sidebar");?>
	<div class="AccMidRight" style="margin-right:150px;">
    <h2 style="border-bottom:none;font-size:20px;">Congratulations!!!</h2>
					<ul class="AccTabMenuTop" style="border-bottom:none; min-height: 395px;">
					<br />
					<br />
					<li>Your account has been verified.</li>
					<br />
					<br />
					<br />
					<br />
					<li class="FloatRight" style="float:left;">
					<?php echo $this->Html->link('Log In',array('controller' => 'users', 'action' => 'social_login'));
						?>
					</li>
					</ul>
     </div>
    <div class="clear"></div>
    </div>
</section>