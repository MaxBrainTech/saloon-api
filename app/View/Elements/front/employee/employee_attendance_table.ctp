<?php 
    // echo "<pre>";
    // echo ($status);
    // echo "</pre>";
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Employee Attendance</li>
    </ol>
    </div><!--/.row-->
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
        </div><!--/.row-->
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                My Attandance
                <div class="col-md-4" style="float: right;">
                    <!-- <a href="javascript:void(0)" class="btn btn-primary" style="float: right;"></a> -->
                    <a href="javascript:void(0);" data-log='<?php echo ($status);?>' data-nm='<?php echo $this->Session->read('employee.Employee.name');?>' data-id='<?php echo $this->Session->read('employee.Employee.emp_code');?>' title="Attendance" alt="Attendance" class="att_test btn btn-primary"  data-toggle="modal" data-target="#attendanceModal" style="float: right;">Submit Attendance</a>
                </div>
            </div>
            <?php $this->Layout->sessionFlash(); ?>
            <div class="panel-body">
                <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Date</th>
                            <th>Check In Time</th>
                            <th>Check Out Time</th>
                            <!-- <th>Others</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i =1; ?>
                        <?php foreach($data as $record){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $record['Attendance']['date'];?></td>
                            <td><?php echo date('H:i:s',strtotime($record['Attendance']['checkin_time']));?></td>
                            <td><?php echo date('H:i:s',strtotime($record['Attendance']['checkout_time']));?></td>
                            <!-- <td><?php //echo date('Y-m-d',strtotime($record['Service']['modified']));?></td> -->
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <th>Sr. No</th>
                        <th>Date</th>
                        <th>Check In Time</th>
                        <th>Check Out Time</th>
                        <!-- <th>Others</th> -->
                    </tfoot>
                </table>
            </div>
        </div>

<!-- Modal -->
  <div class="modal fade" id="attendanceModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Attendance Submition</h4>
          <span id="attendance_msg" style="color: red;"></span>
        </div>
        <div class="modal-body">
          <p id="staff_name"></p>          
          <a id="staff_check_in" action="javascript:void(0);" class="btn btn-primary">Check In</a>
          <a id="staff_check_out" action="javascript:void(0);" class="btn btn-danger">Check Out</a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<script>


$(document).ready(function(){
    var emp_code;
    var status;
    $('.att_test').on('click',function(){
        $('#staff_name').html('Staff Name: '+ $(this).attr("data-nm"));
        emp_code = $(this).attr("data-id");
        status = $(this).attr("data-log");
        if(status == ''){
            $('#staff_check_out').hide();
        }else if(status == 0){
            $('#staff_check_in').hide();
            $('#staff_check_out').show();
        }else{
            $('#staff_check_in').hide();
            $('#staff_check_out').hide();
        }
        // console.log('status:'+status);
    });
    
    $('#staff_check_in').click(function(){
        
        console.log(emp_code);
        $.ajax({
            url: "add_attendance",
            type: 'get',
            data: {'emp_code':emp_code},
            success: function(result){
                // var data = $.parseJSON(result )
                var obj = JSON.parse(result);
                $('#attendance_msg').text(obj.msg);
                setTimeout(function() {
                    location.reload();
                }, 3000); 
                // console.log(obj.msg );
            }
        });
    });

    $('#staff_check_out').click(function(){
        
        console.log(emp_code);
        $.ajax({
            url: "add_attendance",
            type: 'get',
            data: {'emp_code':emp_code},
            success: function(result){
                // var data = $.parseJSON(result )
                var obj = JSON.parse(result);
                $('#attendance_msg').text(obj.msg);
                setTimeout(function() {
                    location.reload();
                }, 3000);
                // console.log(obj.msg );
            }
        });
    });
});
</script>        