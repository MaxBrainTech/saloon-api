<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">My Shop</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">My Shop</h1>
			</div>
		</div><!--/.row-->
		
			
		
		  <div class="panel panel-default">
                    <div class="panel-heading">Customer Detail</div>
                    <div class="panel-body">  
                    	<?php $this->Layout->sessionFlash(); ?>			
                    		<?php echo $this->Form->create('User',	array('url' => array('controller' => 'users', 'action' => 'edit'),'type'=>'file','inputDefaults' => array(	'error' => array('attributes' => array('wrap' => 'span',	'class' => 'input-notification error png_bg')))	));?>
                    		<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('name', array('div' => false, 'label' => 'Name', "class" => "form-control","maxlength"=>30))); ?>
    </div>
</div>


 <div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('email', array('div' => false, 'label' => 'Email', "class" => "form-control"))); ?>
    </div>
</div>
 <div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('company_name', array('div' => false, 'label' => 'Company Name', "class" => "form-control","maxlength"=>30))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('salon_name', array('div' => false, 'label' => 'Salon Name', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('website', array('div' => false, 'label' => 'Website URL', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('tel', array('div' => false, 'label' => 'Phone No', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('zip_code', array('div' => false, 'label' => 'Zip Code', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('city', array('div' => false, 'label' => 'City', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('address1', array('div' => false, 'label' => 'Street Name', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('address2', array('div' => false, 'label' => 'Apartment Name', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('employee_number', array('div' => false, 'label' => 'Employee No', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('advertisement', array('div' => false, 'label' => 'Advertisement', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('avr_customer', array('div' => false, 'label' => 'Average Customer', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('employee_pin_number', array('div' => false, 'label' => 'Employee Pin Number', "class" => "form-control"))); ?>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('customer_pin_number', array('div' => false, 'label' => 'Customer Pin Number', "class" => "form-control"))); ?>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('cash_box', array('div' => false, 'label' => 'Cash Box', 'type' => 'text', "class" => "form-control"))); ?>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <?php echo ($this->Form->input('month_start_date', array('div' => false, 'label' => 'Date', "class" => "form-control"))); ?>
    </div>
</div>
<div class="col-sm-6">
	<label for="UserCashBox">Weekend</label>
    <select class = "form-control" name="weekend" id="UserAction<?php echo ($defaultTab); ?>">

        <?php if(isset($this->request->data['User']['weekend'])){ ?>
            <option value="<?php echo $this->request->data['User']['weekend'];?>"><?php echo $this->request->data['User']['weekend'];?></option>
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
</div>                    

<div class="col-sm-12">
     <?php echo ($this->Form->submit('Submit Button', array('class' => 'btn btn-primary', "div" => false))); ?>
    <button type="reset" class="btn btn-default">Reset Button</button>
</div>
		<?php // echo ($this->element('fornt/User/form'));?>
            <!-- <div class="form-group">
                <label>Text area</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>
             -->
           <?php echo ($this->Form->end());	?>	
                            
        </div>
    </div>
</div><!-- /.panel-->


