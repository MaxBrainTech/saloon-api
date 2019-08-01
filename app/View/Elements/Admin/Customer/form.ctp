<?php
echo $this->Html->script(array('jquery/jquery-ui.min'));
echo $this->Html->script(array('multiselect/jquery.multiselect'));
echo $this->Html->css(array('multiselect/jquery.multiselect'));
?>
<style>
    .ui-multiselect {
        width: 225px !important;
    }
</style>
<script>
    $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: "0",
            dateFormat: 'yy-mm-dd',
			yearRange : '1880:2014'
        });
    });
</script>

<fieldset class="column-left" style="width:75%;"> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
    <?php 
		echo ($this->Form->input('id')); ?>
    <p>
        <?php echo ($this->Form->input('first_name', array('div' => false, 'label' => 'First Name', "class" => "text-input medium-input","maxlength"=>30))); ?>

    </p>

    <p>
        <?php echo ($this->Form->input('last_name', array('div' => false, 'label' => 'Last Name', "class" => "text-input medium-input","maxlength"=>30))); ?>

    </p>
     <?php /*
    <p>
        <?php echo ($this->Form->input('username', array('div' => false, 'label' => 'Username*', "class" => "text-input medium-input"))); ?>
        <br><small>Minimum length: 5 characters</small>
    </p> */ ?>
	 <p>
        <?php echo ($this->Form->input('email', array('div' => false, 'label' => 'Email*', "class" => "text-input medium-input"))); ?>
    </p>
	<p>
		<?php  echo ($this->Form->input('alternate_email', array('div'=>false, 'label'=>"Alternate Email*", "class" => "text-input medium-input")));?>
	</p>
    <?php if ($this->params['action'] == 'admin_add') { ?>
        <p>
            <?php echo ($this->Form->input('password2', array('autocomplete' => 'off', "type" => "password", 'div' => false, 'label' => 'Password*','maxlength'=>20, "class" => "text-input medium-input"))); ?>
            <br><small>Minimum length: 6 characters</small>
        </p>

        <p>
            <?php echo ($this->Form->input('confirm_password', array('autocomplete' => 'off', "type" => "password", 'div' => false, 'label' => 'Confirm Password*','maxlength'=>20, "class" => "text-input medium-input"))); ?>
            <br><small>Re-Type Password here</small>
        </p>
    <?php } ?>
   
	
    <p>
    	<label>Subscription Plan*</label>
        <?php echo ($this->Form->input('subscription_plan_id', array('type' => 'select', 'options' => $subscription_plans, 'empty'=>"Select Subscription Plan", "class" => "text-input medium-input", 'label'=>false, 'div' => false, 'legend' => 'Status*'))); ?>
    </p>
	
    <p>
        <?php echo ($this->Form->input('status', array('type' => 'select', 'options' => array('0' => 'Inactive', '1' => 'Active'), 'default' => 2, "class" => "text-input medium-input", 'div' => false, 'legend' => 'Status*'))); ?>
    </p>
    <p>
        <?php echo ($this->Form->submit('Submit', array('class' => 'button', "div" => false, 'onclick' => "return customvalidation()"))); ?>
        <?php if (isset($this->request->data['User']['role_id'])) { ?>
            <?php
            echo $this->Html->link("Cancel", array('admin' => true, 'controller' => 'users', 'action' => 'index', ucfirst(Configure::read('App.Roles.' . $this->request->data['User']['role_id']))), array("class" => "button", "escape" => false));
        } else {

            echo $this->Html->link("Cancel", array('admin' => true, 'controller' => 'users', 'action' => 'index'), array("class" => "button", "escape" => false));
        }
        ?>

    </p>
	
</fieldset>
<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) {
    echo $this->Js->writeBuffer();
}
?>