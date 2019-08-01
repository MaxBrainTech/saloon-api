<?php
// echo "<pre>";
//     print_r($data);
// echo "</pre>";
?>


<?php 
    echo $this->Html->script(
                        array(
                            'datepicker/jquery.js',
                            'datepicker/jquery.datetimepicker.full.js'
                        ));
    echo $this->Html->css(array('datepicker/jquery.datetimepicker.css'));
?>
 <link
     rel="stylesheet"
     href="http://code.jquery.com/ui/1.9.0/themes/smoothness/jquery-ui.css" />

<script>
 jQuery(document).ready(function () {
    'use strict';
    jQuery('#date').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false
    });
    
    $("#mydate").datepicker().datepicker("setDate", new Date());
});   
</script>

<div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Customer List</li>
            </ol>
        </div><!--/.row-->
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
        </div><!--/.row-->
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Customer List        
                <div class="col-md-4" style="float: right;">
                    <?php //echo $this->Html->link("Add", array('controller'=>'customers', 'action'=>'add'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
                    <!-- <a href="" class="btn btn-primary" style="float: right;">Add</a> -->
                </div>
            </div>
<?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">

                <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact NO</th>
                            <th>Service Name</th>
                            <th>Last Visited Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $record){?>
                            <tr>

                                <td><?php echo $record['Customer']['name'];?></td>
                                <td><?php echo $record['Customer']['email'];?></td>
                                <td><?php echo $record['Customer']['tel'];?></td>
                                <td><?php echo $record['Service']['name'];?></td>
                                <td><?php echo date('Y-m-d',strtotime($record['Service']['modified']));?></td>
                                <td>
                                    <a id="#openServiceForm" href="javascript:void(0);" data-log='<?php //echo $record['Attendance']['status'];?>' data-nm='<?php //echo $record['Employee']['name'];?>' data-id="<?php echo $record['Customer']['user_id'];?>" data-cid="<?php echo $record['Customer']['id'];?>" title="Attendance" alt="Attendance" class="att_test"><span class="icon-attendance" data-toggle="modal" data-target="#addServiceInfo">&nbsp;</span></a>
                                    <?php echo $this->Html->link("Note", array('controller'=>'customerhistories', 'action'=>'get_customer_analysis_dates',$record['Customer']['id']), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>

                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                         <th>Name</th>
                         <th>Email</th>
                         <th>Contact NO</th>
                         <th>Service Name</th>
                         <th>Last Visited Date</th>
                         <th>Action</th>
                    </tfoot>
                </table>
            </div>
        </div>


        <!-- Modal -->
  <div class="modal fade" id="addServiceInfo" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Service Form</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
       
        <div class="modal-body">
         <?php  echo ($this->Form->create('CustomerForm', array('name' => 'CustomerForm', 'url' => array('controller' => 'customers', 'action' => 'get_service_form'))));?>
          <?php if(isset($serviceList)) { ?>
            <?php  echo ($this->Form->input('service_id', array('options'=>$serviceList,'div'=>false,  'label' => 'Service',  "class" => "form-control")));?>

            <!-- <div class="col-sm-6"> -->
                <div class="form-group">
                    <?php echo ($this->Form->input('date', array('div' => false, 'label' => 'Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'date'))); ?>
                </div>
            <!-- </div> -->

            <?php  echo ($this->Form->input('user_id', array("type"=>'hidden')));?>
            <?php  echo ($this->Form->input('customer_id', array("type"=>'hidden')));?>
            <div class="form-group" style="margin-top: 30px;">
              <?php echo ($this->Form->submit('Add Detail', array('class' => 'btn btn-primary', "div" => false))); ?>
            </div>
          <?php }else{ echo 'No Service Form has created!!!';} ?>

        <?php echo ($this->Form->end());    ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>
      </div>
      
    </div>
  </div>

  <script>
$(document).ready(function(){
    var CustomerServiceId;
    var id;
    $('.att_test').on('click',function(){
        id = $(this).attr("data-id");
        cid = $(this).attr("data-cid");
        $('#CustomerFormUserId').val(id);
        $('#CustomerFormCustomerId').val(cid);
        console.log(id);
    });
    $('#serviceForm').click(function(){
        CustomerServiceId = $('#CustomerServiceId').val();
        // console.log("CustomerServiceId: "+CustomerServiceId);
        
        // console.log(id);
        $.ajax({
            url: "../users/get_service_form",
            type: 'post',
            data: {'CustomerServiceId':CustomerServiceId, 'id':id},
            success: function(result){
                console.log(result);
                var delay = 1000; 
                setTimeout(function(){ window.location = '../users/service_form'; }, delay);
            }
        });
    });
});
</script>