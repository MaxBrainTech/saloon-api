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
        <?php echo ($this->Form->input('name', array('div' => false, 'label' => 'Name', "class" => "text-input medium-input","maxlength"=>30))); ?>

    </p>
     
	 <p>
        <?php echo ($this->Form->input('email', array('div' => false, 'label' => 'Email*', "class" => "text-input medium-input"))); ?>
    </p>
	
    <p>
        <?php echo ($this->Form->input('company_name', array('div' => false, 'label' => 'company_name', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('salon_name', array('type' => 'text','div' => false, 'label' => 'Salon Name', "class" => "text-input medium-input"))); ?>
    </p>

     <p>
        <?php echo ($this->Form->input('tel', array('div' => false, 'label' => 'tel', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('zip_code', array('type' => 'text','div' => false, 'label' => 'zip_code', "class" => "text-input medium-input"))); ?>
    </p>


    <p>
        <?php echo ($this->Form->input('city', array('type' => 'text', 'div' => false, 'label' => 'city', "class" => "text-input medium-input"))); ?>
    </p>
     
    <p>
        <?php echo ($this->Form->input('website', array('type' => 'text','div' => false, 'label' => 'Website', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('address1', array('div' => false, 'label' => 'address1', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('address2', array('div' => false, 'label' => 'address2', "class" => "text-input medium-input"))); ?>
    </p>


    <p>
        <?php echo ($this->Form->input('employee_number', array('type' => 'text','div' => false, 'label' => 'employee_number', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('advertisement', array('type' => 'text','div' => false, 'label' => 'advertisement', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('avr_customer', array('type' => 'text','type' => 'text','div' => false, 'label' => 'avr_customer', "class" => "text-input medium-input"))); ?>
    </p>
    
     <p>
        <?php echo ($this->Form->input('employee_pin_number', array('type' => 'text','type' => 'text','div' => false, 'label' => 'employee_pin_number', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('customer_pin_number', array('type' => 'text','div' => false, 'label' => 'customer_pin_number', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <?php echo ($this->Form->input('month_start_date', array('div' => false, 'label' => 'month_start_date', "class" => "text-input medium-input"))); ?>
    </p>

    <p>
        <div class="bulk-actions align-full">
                        <?php // print_r($this->request->data['HelpQuestion']['weekend']);?>
                        <select class = "medium-input" name="weekend" id="HelpQuestionAction<?php echo ($defaultTab); ?>">

                            <?php if(isset($this->request->data['HelpQuestion']['weekend'])){ ?>
                                <option value="<?php echo $this->request->data['HelpQuestion']['weekend'];?>"><?php echo $this->request->data['HelpQuestion']['weekend'];?></option>
                            <?php }else { ?>
                                <option selected="selected" value="">Select Day</option>
                            <?php } ?>    
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Wednesday">Thursaday</option>
                            <option value="Wednesday">Friday</option>
                            <option value="Wednesday">Saturday</option>
                            <option value="Wednesday">Sunday</option>

                        </select>
    
                    </div>  <br> 
    </p>

    <br>

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
        <?php echo ($this->Form->submit('Submit', array('class' => 'button', "div" => false, 'onclick' => "return customvalidation()"))); ?>
        <?php if (isset($this->request->data['HelpQuestion']['role_id'])) { ?>
            <?php
            echo $this->Html->link("Cancel", array('admin' => true, 'controller' => 'helps', 'action' => 'index', ucfirst(Configure::read('App.Roles.' . $this->request->data['HelpQuestion']['role_id']))), array("class" => "button", "escape" => false));
        } else {

            echo $this->Html->link("Cancel", array('admin' => true, 'controller' => 'helps', 'action' => 'index'), array("class" => "button", "escape" => false));
        }
        ?>

    </p>
	
</fieldset>
<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) {
    echo $this->Js->writeBuffer();
}
?>