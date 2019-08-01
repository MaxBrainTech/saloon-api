<div class="FullWraper TopBgBan" id="home">
    <div class="FullWraper">
      <div class="Container HeadBotmBdr">
        <div class="Logo f-left margin-top-40 margin-bottom-25">
        <?php echo $this->Html->link($this->Html->image('home-logo.png', array('alt' => 'JTSBoard', 'title' => 'JTSBoard', 'style' => 'height: 32px;')), array('controller' => '/'), array('escape' => false)); ?>
        </div>
        <div class="Navigation">
          <ul class="Navi">
          	<li class="active"><?php echo $this->Html->link("Home", array('controller' => '/'), array('escape' => false, 'class'=>'active')); ?></li>
            <li class="active"><?php echo $this->Html->link("Login", array('controller' => 'users', 'action'=> 'login'), array('escape' => false, 'class'=>'active')); ?></li>
            <li class="active"><?php echo $this->Html->link("Employee Login", array('controller' => 'employees', 'action'=> 'login'), array('escape' => false, 'class'=>'active')); ?></li>
           </ul>
          
          
          <div class="NaviMobile">
          <div class="NaviMobileIcon"><a href="#"><span class="glyphicon glyphicon-align-justify"></span></a></div>
          
          <ul class="Navi NaviMobile">
          <li class="active"><?php echo $this->Html->link("Home", array('controller' => '/'), array('escape' => false, 'class'=>'active')); ?></li>
           </ul>
          </div>
<?php /* ?>
          <ul class="LogSignBtn">
            <?php if($this->request->params['action']=="register" ||  $this->request->params['action']=="my_profile"){
          	$active ="SignupBtn Active";
          }else{
          	$active ="SignupBtn";
          }
          ?>
          <?php if($this->Session->read('Auth.User.id')){ ?>
		<li><?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span><label>My Profile</label>', array('controller'=>'users', 'action'=>'my_profile'), array('escape' => FALSE, 'class'=>$active )); ?></li>	
           <li><?php  echo $this->Html->link('<span>Logout</span>', array('controller'=>'users', 'action'=>'logout'), array('escape' => FALSE,'class'=>'LoginBtn'));?></li>
           
         <?php } else { ?>
           <li><?php  echo $this->Html->link('<span>Login</span>', array('controller'=>'users', 'action'=>'login'), array('escape' => FALSE,'class'=>'LoginBtn'));?></li>
            <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span><label>Sign up</label>', array('controller'=>'users', 'action'=>'register'), array('escape' => FALSE, 'class'=>$active )); ?></li>
         
         <?php } ?>
         </ul>
        </div>
        <div class="Clear"></div>
         <?php */ ?>
      </div>
      <div class="Clear"></div>
    </div>
    <div class="FullWraper p-relative z-index-10">
      <div class="ChainTopBg p-absolute"></div>
      <div class="Container">
        <div class="BanMidBox">
          <p style='color:#228B22'><?php echo $msg;?></p>
          <h1 class="margin-top-30 text-center">Manage <span class="RedColor">Customer</span> Details</h1>
          <p>Customer service is important for us.</p>
        </div>
       
        <div class="Clear"></div>
      </div>
      <div class="Clear"></div>
    </div>
    <div class="Clear"></div>
  </div>
  