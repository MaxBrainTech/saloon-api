<style type="text/css">
	form label {
		display: block;
		float: left;
		font-size: 17px;
		padding: 0 0 10px;
		width: 300px;
	}
</style>
<?php $classInput = 'small-input'; ?>
<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
    <?php echo ($this->Form->input('id')); ?>				


    <p>
        <label>Title*</label>
        <?php echo ($this->Form->input('title', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?> 

    </p>
	
    <p>
        <label>function name</label>
        <?php echo ($this->Form->input('name', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?> 

    </p>
	
    <p>
        <label>function Type(GET / POST)</label>
        <?php echo ($this->Form->input('type', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?> 

    </p>
	
    <p>
        <label>Url</label>
        <?php echo ($this->Form->input('url', array('div' => false, 'label' => false, 'type' => '', "class" => "text-input " . $classInput))); ?> 

    </p>
	
    <p>
        <label>Description</label>
        <?php echo ($this->Form->input('description', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?>
    </p>
	
    <p>
        <label>Request</label>
        <?php echo ($this->Form->input('request', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?> 

    </p>
	
    <p>
        <label>Header</label>
        <?php echo ($this->Form->input('header', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?> 

    </p>
	
    <p>
        <label>Response</label>
        <?php echo ($this->Form->input('response', array('div' => false, 'label' => false, "class" => "text-input " . $classInput))); ?> 

    </p>

<?php /* ?>
    <p>
        <label>iphone status</label>
        <?php echo ($this->Form->input('iphone_status', array('options' => Configure::read('Status'), 'div' => false, 'label' => false, "class" => "small-input"))); ?> 
    </p>

    <p>
        <label>iphone status</label>
        <?php echo ($this->Form->input('android_status', array('options' => Configure::read('Status'), 'div' => false, 'label' => false, "class" => "small-input"))); ?> 
    </p>
<?php  */?>

    <p>
        <label>Status</label>
        <?php echo ($this->Form->input('status', array('options' => Configure::read('Status'), 'div' => false, 'label' => false, "class" => "small-input"))); ?> 
    </p>

    <p>
        <?php echo $this->Form->submit('Submit', array('class' => 'button', "div" => false)); ?>

        <?php echo $this->Html->link("Cancel", array('admin' => true, 'controller' => 'test_web_services', 'action' => 'index'), array("class" => "button", "escape" => false)); ?>

    </p>

</fieldset>