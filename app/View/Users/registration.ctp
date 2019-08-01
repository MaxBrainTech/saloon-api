<style type="text/css">
  .session_msg{
    text-align: center;
    padding: 0 0 20px 0;
    font-size: 16px;
    font-weight: 600;
  }
  .success{
    color: #00ab66;
  }
  .error{
    color: #cc0000;
  }
  .error-message{
    color: #cc0000;
    margin: 5px;
  }
</style>
<div class="panel-heading">
  <div class="col-md-4">Registration</div>
  <div class="col-md-8 text-right"><?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-logout')) . "Login", array('plugin' => null, 'controller' => 'users', 'action' => 'login'),  array( 'escape' => false,'class'=>'btn btn-primary')));
              ?></div>
  </div>
  <div class="panel-body">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'registration')));?>
    
    <fieldset>
      <div class="form-group">
       <?php echo $this->Form->input("User.name", array("type" => "text", "div" => false, "placeholder" => "Name", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.salon_name", array("type" => "text", "div" => false, "placeholder" => "Salon Name", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.email", array("type" => "email", "div" => false, "placeholder" => "E-mail", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.password", array("type" => "password", "div" => false, "placeholder" => "Password", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.confirm_password", array("type" => "password", "div" => false, "placeholder" => "Confirm Password", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.employee_pin_number", array("type" => "text", "div" => false, "placeholder" => "Employee Pin Number", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.customer_pin_number", array("type" => "text", "div" => false, "placeholder" => "Customer Pin Number", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.zip_code", array("type" => "text", "div" => false, "placeholder" => "Zip Code", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.prefecture", array("type" => "text", "div" => false, "placeholder" => "Perfecture", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.city", array("type" => "text", "div" => false, "placeholder" => "City", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.address1", array("type" => "text", "div" => false, "placeholder" => "Address 1", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.address2", array("type" => "text", "div" => false, "placeholder" => "Address 2", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.tel", array("type" => "text", "div" => false, "placeholder" => "Phone", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.website", array("type" => "text", "div" => false, "placeholder" => "Website", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.employee_number", array("type" => "text", "div" => false, "placeholder" => "Employee Number", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.advertisement", array("type" => "text", "div" => false, "placeholder" => "Advertisement", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <div class="form-group">
       <?php echo $this->Form->input("User.avr_customer", array("type" => "text", "div" => false, "placeholder" => "AVR Customer", "label" => false, 'class'=>'form-control')); ?>
       
      </div>
      <?php echo $this->Form->submit("Submit", array("class" => "btn btn-primary",'div'=>false)); ?>
        
      <?php echo $this->Form->end(); ?>
    </div>

