<?php  //print_r($user);?>
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
                      
                        <div class="col-sm-6">
    <div class="form-group">
      <label>Name:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['name']?></span>
    
    </div>
</div>


 <div class="col-sm-6">
    <div class="form-group">
    <label>Salon Name:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['salon_name']?></span></li>
    </div>
</div>
 <div class="col-sm-6">
    <div class="form-group">
    <label>Email Address:</label>
      <span class="EditProfTxtRi"><a href="#"><?php echo $user['User']['email']?></a></span></li>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Company Name:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['company_name']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Website URL:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['website']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Phone No:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['tel']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Zip Code:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['zip_code']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>City:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['city']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Street Name:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['address1']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Apartment Name:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['prefecture']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Employee No:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['employee_number']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Advertisement:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['advertisement']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Employee Pin Number:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['employee_pin_number']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Customer Pin Number:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['customer_pin_number']?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>Cash Box:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['cash_box']?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>Date:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['month_start_date']?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>Weekend:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['weekend']?></span>
    </div>
</div>
                    

<div class="col-md-12">
  <?php echo $this->Html->link("Edit", array('admin'=>true, 'controller'=>'users', 'action'=>'edit', $user['User']['id']), array("class"=>"btn btn-primary", "escape"=>false)); ?>
     <?php //echo ($this->Form->submit('Edit', array('class' => 'btn btn-primary', "div" => false))); ?>
    <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
</div>
    <?php // echo ($this->element('fornt/User/form'));?>
            <!-- <div class="form-group">
                <label>Text area</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>
             -->
           <?php // echo ($this->Form->end()); ?>  
                            
        </div>
    </div>
</div><!-- /.panel-->
