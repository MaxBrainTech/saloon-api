<?php 
    // echo "<pre>";
    // print_r($reservationData['Reservation']);
    // echo "</pre>";
?>

<div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Reservations</li>
            </ol>
        </div><!--/.row-->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"></h1>
            </div>
            <?php $this->Layout->sessionFlash(); ?>
        </div><!--/.row-->


        <!-- Service Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Customer Reservation
                <div class="col-md-4" style="float: right;">
                    <?php echo $this->Html->link("Add Customer Reservation", array('controller'=>'calenders', 'action'=>'add_reservation','1'), array("class"=>"btn btn-primary", "escape"=>false, "style"=>"float:right")); ?>
                    
                </div>
            </div>
            
            <div class="panel-body">
                <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
                <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Start Date & Time</th>
                    <th>Last Visit</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reservationData['Reservation'] as $record) {
                    if($record['reservation_type'] == 1){
                 ?>
                    <tr>
                    <td><?php echo $record['customer_name'] ?></td>
                    <td><?php echo $record['start_date'] ?>
                        <?php if($record['all_day'] == 0){ ?>
                            <span><?php echo $record['start_time'] ?></span>
                        <?php }?>
                    </td>
                    <td><?php echo $record['last_visit'] ?></td>
                    <td><?php echo $record['service_name'] ?></td>
                    <td><?php echo $record['price'] ?></td>
                    <td>
                        <?php if($record['ongoing'] == 1 ){ ?>
                        <a class="btn btn-primary az_test" data-ongoing-value='<?php echo $record['ongoing'];?>' data-id='<?php echo $record['id'];?>' title="Update Status" data-toggle="modal" data-target="#startModal">Update</a>
                    <?php }elseif($record['ongoing'] == 2 ){?>
                        <a class="btn btn-primary az_test" data-ongoing-value='<?php echo $record['ongoing'];?>' data-id='<?php echo $record['id'];?>' title="Update Status" data-toggle="modal" data-target="#ongoingModal">Update</a>
                    <?php }elseif($record['ongoing'] == 3 ){?>
                        <div><p style="background-color : green;color: white;">Finished</p></div>
                    <?php }elseif($record['ongoing'] == 4 ){?>
                        <div><p style="background-color: red;color: white;">Canceled</p></div>
                    <?php }?>
                    </td>
                    <td>
                        <a href="/calenders/delete_reservation/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                         <a href="/calenders/edit_reservation/1/<?php echo $record['id'];?>" title='Edit Reservation'><img src="/img/admin/pencil.png" alt="Edit"></a>
                    </td>
                </tr>
                <?php }}?>

                </tbody>
                <tfoot>
                 <tr>
                    <th>Customer Name</th>
                    <th>Start Date & Time</th>
                    <th>Last Visit</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </tfoot>
                </table>

                </div>
        </div>

<!-- Modal -->
  <div class="modal fade" id="startModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Status</h4>
          <span id="msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
          <p id="staff_name"></p>          
          <a id="start" action="javascript:void(0);" class="btn btn-primary">Start</a>
          <a id="cancel" action="javascript:void(0);" class="btn btn-danger">Cancel</a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!-- Modal -->
  <div class="modal fade" id="ongoingModal" role="dialog">
    <div class="modal-dialog"> 
  <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Status</h4>
          <span id="msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
          <p id="staff_name"></p>          
          <a id="finish" action="javascript:void(0);" class="btn btn-primary">Finish</a>
          <a id="cancel1" action="javascript:void(0);" class="btn btn-danger">Cancel</a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!-- Events Table -->
<div class="panel panel-default">
    <div class="panel-heading">
        Events
        <div class="col-md-4" style="float: right;">
            <?php echo $this->Html->link("Add Event Reservation", array('controller'=>'calenders', 'action'=>'add_reservation', '2'), array("class"=>"btn btn-primary", "escape"=>false, "style"=>"float:right")); ?>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Start Date & Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservationData['Reservation'] as $record) {
                    if($record['reservation_type'] == 2){
                 ?>
                    <tr>
                    <td><?php echo $record['event_name'] ?></td>
                    <td><?php echo $record['start_date'] ?>
                        <?php if($record['all_day'] == 0){ ?>
                            <span><?php echo $record['start_time'] ?></span>
                        <?php }?>
                    </td>
                    <td>
                        <a href="/calenders/delete_reservation/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                         <a href="/calenders/edit_reservation/2/<?php echo $record['id'];?>" title='Edit Reservation'><img src="/img/admin/pencil.png" alt="Edit"></a>
                    </td>
                </tr>
                <?php }}?>
                
            </tbody>
            <tfoot>
                 <tr>
                    <th>Event Name</th>
                    <th>Start Date & Time</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>


<!-- Staff Table -->
<div class="panel panel-default">
    <div class="panel-heading">
        Staff
        <div class="col-md-4" style="float: right;">
            <?php echo $this->Html->link("Add Staff Holiday", array('controller'=>'calenders', 'action'=>'add_reservation', '3'), array("class"=>"btn btn-primary", "escape"=>false, "style"=>"float:right")); ?>
        </div>
    </div>
    <div class="panel-body">
        <table id="dataTables-example1" style="width:100%" class="table table-bordered data-table-custom Calendar-table jts_table">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Start Date & Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservationData['Reservation'] as $record) {
                    if($record['reservation_type'] == 3){
                 ?>
                    <tr>
                    <td><?php echo $record['staff_name'] ?></td>
                    <td><?php echo $record['start_date'] ?>
                        <?php if($record['all_day'] == 0){ ?>
                            <span><?php echo $record['start_time'] ?></span>
                        <?php }?>
                    </td>
                    <td>
                        <a href="/calenders/delete_reservation/<?php echo $record['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                         <a href="/calenders/edit_reservation/3/<?php echo $record['id'];?>" title='Edit Reservation'><img src="/img/admin/pencil.png" alt="Edit"></a>
                    </td>
                </tr>
                <?php }}?>
                
            </tbody>
            <tfoot>
                 <tr>
                    <th>Event Name</th>
                    <th>Start Date & Time</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>

<script>
$(document).ready(function(){
    var status;
    var reservation_id;
    $('.az_test').on('click',function(){
        reservation_id = $(this).attr("data-id");
        status = $(this).attr("data-ongoing-value");
        console.log(reservation_id);
        console.log(status);
    });
    
    $('#start').click(function(){
        
        // console.log(emp_code);
        $.ajax({
            url: "add_reservation_status",
            type: 'post',
            data: {'id':reservation_id,'status':'2'},
            success: function(result){
                console.log(result);
                // var data = $.parseJSON(result )
                // var obj = JSON.parse(result);
                $('#msg').text('Status Updated.');
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        });
    });

    $('#finish').click(function(){
        
        // console.log(emp_code);
        $.ajax({
            url: "add_reservation_status",
            type: 'post',
            data: {'id':reservation_id,'status':'3'},
            success: function(result){
                console.log(result);
                // var data = $.parseJSON(result )
                // var obj = JSON.parse(result);
                $('#msg').text('Status Updated.');
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        });
    });

    $('#cancel').click(function(){
        
        // console.log(emp_code);
        $.ajax({
            url: "add_reservation_status",
            type: 'post',
            data: {'id':reservation_id,'status':'4'},
            success: function(result){
                console.log(result);
                // var data = $.parseJSON(result )
                // var obj = JSON.parse(result);
                $('#msg').text('Status Updated.');
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        });
    });
    $('#cancel1').click(function(){
        
        // console.log(emp_code);
        $.ajax({
            url: "add_reservation_status",
            type: 'post',
            data: {'id':reservation_id,'status':'4'},
            success: function(result){
                console.log(result);
                // var data = $.parseJSON(result )
                // var obj = JSON.parse(result);
                $('#msg').text('Status Updated.');
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        });
    });
});
</script>