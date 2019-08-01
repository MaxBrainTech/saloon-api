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
<div class="panel-heading">Log in</div>
        <div class="panel-body">
          <?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));?>
          
            <fieldset>
              <div class="form-group">
               <?php echo $this->Form->input("User.email", array("type" => "text", "div" => false, "placeholder" => "E-mail", "label" => false, 'class'=>'form-control')); ?>
               
              </div>
              <div class="form-group">
              <?php echo $this->Form->input("User.password", array("type" => "password", "div" => false, "placeholder" => "Password", "label" => false, 'class'=>'form-control')); ?>
              </div>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me">Remember Me
                </label>
              </div>
              <div class="col-md-4"><?php echo $this->Form->submit("Login", array("class" => "btn btn-primary",'div'=>false)); ?></div>
              <div class="col-md-8 text-right">
              <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-logout')) . "Registration", array('plugin' => null, 'controller' => 'users', 'action' => 'registration'),  array( 'escape' => false,'class'=>'btn btn-primary')));
              ?></div>
              
            <?php echo $this->Form->end(); ?>
        </div>

