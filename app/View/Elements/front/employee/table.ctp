<?php
// echo "<pre>"; 
// print_r($data);
// die;
?>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
            <li class="active">スタッフリスト</li>
        </ol>
    </div><!--/.row-->   
    <?php $this->Layout->sessionFlash(); ?>     
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
    </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">
                スタッフリスト        
                <div class="col-md-4" style="float: right;">
                    <?php echo $this->Html->link("従業員を追加する", array('controller'=>'employees', 'action'=>'add'), array("class"=>"btn btn-primary",'style'=>'float: right;', "escape"=>false)); ?>
                    <!-- <a href="" class="btn btn-primary" style="float: right;">Add</a> -->
                </div>
            </div>
            
            <div class="panel-body">

                <table id="dataTables-example" style="width:100%" class="table table-bordered data-table-custom Calendar-table">
                    <thead>
                        <tr>
                            <th>名前</th>
                            <th>メール</th>
                            <th>電話番号</th>
                            <th>指定</th>
                            <th>参加日</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $record){?>
                            <tr>
                                <td><?php echo $record['Employee']['name'];?></td>
                                <td><?php echo $record['Employee']['email'];?></td>
                                <td><?php echo $record['Employee']['phone'];?></td>
                                <td><?php echo $record['Employee']['designation'];?></td>
                                <td><?php echo date('Y-m-d',strtotime($record['Employee']['joining_date']));?></td>
                                <td>
                                        <!-- Icons -->
                                        <a href="/employees/delete/<?php echo $record['Employee']['id'];?>" onclick="return confirm('Are you sure to delete this record?')"><img src="/img/admin/cross.png" title="Delete" alt="Delete"></a>
                                         <a href="/employees/view/<?php echo $record['Employee']['id'];?>"><img src="/img/admin/view.jpg" title="View" alt="View"></a>
                                         <a href="/employees/edit/<?php echo $record['Employee']['id'];?>"><img src="/img/admin/pencil.png" title="Edit" alt="Edit"></a>
                                         <a href="javascript:void(0);" data-log='<?php echo $record['Attendance']['status'];?>' data-nm='<?php echo $record['Employee']['name'];?>' data-id='<?php echo $record['Employee']['emp_code'];?>' title="Attendance" alt="Attendance" class="att_test"><span class="icon-attendance" data-toggle="modal" data-target="#attendanceModal">&nbsp;</span></a>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                        <th>名前</th>
                        <th>メール</th>
                        <th>電話番号</th>
                        <th>指定</th>
                        <th>参加日</th>
                        <th>Action</th>
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
          <a id="staff_check_in" action="javascript:void(0);" class="btn btn-primary">出勤</a>
          <a id="staff_check_out" action="javascript:void(0);" class="btn btn-danger">退勤</a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        </div>
      </div>
      
    </div>
  </div>

<script>
$(document).ready(function(){
    var emp_code;
    var status;
    $('.att_test').on('click',function(){
        $('#staff_name').html('名前: '+ $(this).attr("data-nm"));
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
        
        // console.log(emp_code);
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
                }, 5000);
                // setTimeout("location.href = 'http://localhost/jtsboard/employees/list'",3000); 
                // console.log(obj.msg );
            }
        });
    });

    $('#staff_check_out').click(function(){
        
        // console.log(emp_code);
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
                }, 5000);
                // setTimeout("location.href = 'http://localhost/jtsboard/employees/list'",3000); 
                // console.log(obj.msg );
            }
        });
    });
});
</script>