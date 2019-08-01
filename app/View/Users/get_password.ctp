<div class="panel-heading">Reset Your Password</div>
        <div class="panel-body">

          <?php 
      echo $this->Form->create('User', 
        array('url' => array('controller' => 'users', 'action' => 'get_password',base64_encode($email), $verification_code),
        'inputDefaults' => array(
        'error' => array(
          'attributes' => array(
            'wrap' => 'span',
            'class' => 'input-notification error png_bg'
          )
        )
      )
      ));?>
        <?php // echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'get_password')));?>
          
            <fieldset>
              <div class="form-group" style="margin-bottom: 0;">
               <?php echo $this->Form->input('User.email', array('label'=>'Email', 'div'=>false, 'readonly'=>'readonly','error'=>false, 'class'=>'form-control'));?><br/>
              <?php
                if ($this->Form->isFieldError('email')){
                  echo $this->Form->error('email');
                }
              ?>
               
              </div>
              <div class="form-group">
              <?php echo $this->Form->input("User.password", array('label'=>'Password', "type" => "password", "value" => "", "div" => false, "placeholder" => "Password", 'class'=>'form-control')); ?>
               <?php
                if ($this->Form->isFieldError('password')){
                  echo $this->Form->error('password');
                }
              ?>
              </div>

              <div class="form-group">
              <?php echo $this->Form->input("User.password2", array('label'=>'Confirm Password', "type" => "password", "div" => false, "placeholder" => "Confirm Password", 'class'=>'form-control')); ?>
               <?php
                if ($this->Form->isFieldError('password2')){
                  echo $this->Form->error('password2');
                }
              ?>
              </div>
              
              <?php echo $this->Form->submit("Submit", array("class" => "btn btn-primary",'div'=>false)); ?>
              
            <?php echo $this->Form->end(); ?>
        </div>

