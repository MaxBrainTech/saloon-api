<div class="panel-heading">Employee Log in</div>
<?php $this->Layout->sessionFlash(); ?>
        <div class="panel-body">
        <?php echo $this->Form->create('Employee', array('url' => array('controller' => 'employees', 'action' => 'login')));?>
          
            <fieldset>
              <div class="form-group">
               <?php echo $this->Form->input("Employee.emp_code", array("type" => "text", "div" => false, "placeholder" => "Employee Code", "label" => false, 'class'=>'form-control')); ?>
               
              </div>
              <!-- <div class="form-group">
              <?php //echo $this->Form->input("Employee.password", array("type" => "password", "div" => false, "placeholder" => "Password", "label" => false, 'class'=>'form-control')); ?>
              </div>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me">Remember Me
                </label>
              </div> -->
              <?php echo $this->Form->submit("Login", array("class" => "btn btn-primary",'div'=>false)); ?>
              
            <?php echo $this->Form->end(); ?>
        </div>

