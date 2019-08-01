<div class="FullWraper TopBgBan" id="home">
    <div class="FullWraper">
      <div class="Container HeadBotmBdr">
        <div class="Logo f-left margin-top-40 margin-bottom-25">
        <?php echo $this->Html->link($this->Html->image('/img/home-logo.png', array('alt' => 'vocalist','class'=>'default_header_logo', 'title' => 'vocalist')), array('controller' => '/'), array('escape' => false)); ?>
        </div>
        <div class="Navigation">
          <ul class="Navi">
          	<li class="active"><?php echo $this->Html->link("Home", array('controller' => '/'), array('escape' => false, 'class'=>'active')); ?></li>
          	<!-- <li><a href="#">About us</a></li>
            <li><a href="#">Features</a></li>
            <li><a href="#">Contact us</a></li> -->
          </ul>
          
          
          <div class="NaviMobile">
          <div class="NaviMobileIcon"><a href="#"><span class="glyphicon glyphicon-align-justify"></span></a></div>
          
          <ul class="Navi NaviMobile">
          <li class="active"><?php echo $this->Html->link("Home", array('controller' => '/'), array('escape' => false, 'class'=>'active')); ?></li>
           <?php /* li class="active"><a href="#">Home</a></li*/?>
            <!-- <li><a href="#">About us</a></li>
            <li><a href="#">Features</a></li>
            <li><a href="#">Contact us</a></li> -->
          </ul>
          </div>
          <ul class="LogSignBtn">
            <?php if($this->request->params['action']=="registration" ||  $this->request->params['action']=="my_profile"){
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
            <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span><label>Sign up</label>', array('controller'=>'users', 'action'=>'registration'), array('escape' => FALSE, 'class'=>$active )); ?></li>
         
         <?php } ?>
         </ul>
        </div>
        <div class="Clear"></div>
      </div>
      <div class="Clear"></div>
    </div>
    <div class="Clear"></div>
  </div>
  