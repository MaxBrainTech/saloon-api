<style type="text/css">
  .error-message { display: none;}
</style>
<div id="login-content">
  
  <?php
  $msg= $this->Session->flash();
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
  <?php echo $this->Form->create('SaleUser', array('url' => array('controller' => 'sale_users', 'action' => 'login')));?>
  <p>
    <label>Username</label>
    <?php echo $this->Form->input("SaleUser.email", array("type" => "text", "div" => false, "label" => false, 'class'=>'text-input')); ?>
  </p>

  <div class="clear"></div>
    
  <p>
    <label>Password</label>
    <?php echo $this->Form->input("SaleUser.password", array("type" => "password", "div" => false, "label" => false, 'class'=>'text-input'));?>
  </p>

  <div class="clear"></div>
  
  <p>
    <?php echo $this->Form->submit("Login", array("class" => "button",'div'=>false)); ?>
  </p>
    
  <?php echo $this->Form->end(); ?>
</div>
