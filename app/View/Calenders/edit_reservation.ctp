<?php 
    echo $this->Html->script(
                        array(
                            'datepicker/jquery.js',
                            'datepicker/jquery.datetimepicker.full.js'
                        ));
    echo $this->Html->css(array('datepicker/jquery.datetimepicker.css'));
        // echo "<pre>";
        //     print_r($this->request->data);
        // echo "</pre>";
    $reservation_id = $this->request->data['Reservation']['id'];
?>
 <link
     rel="stylesheet"
     href="http://code.jquery.com/ui/1.9.0/themes/smoothness/jquery-ui.css" />

<script>
 jQuery(document).ready(function () {
    'use strict';
    jQuery('#start_date').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false
    });
    jQuery('#end_date').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false
    });
    jQuery('#start_time').datetimepicker({
    format:'H:i',
    datepicker:false,
    step:15
    });
    jQuery('#end_time').datetimepicker({
    format:'H:i',
    datepicker:false,
    step:15
    });
});   
</script>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Reservation Form</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
        </div><!--/.row-->
        <?php if($this->request->params['pass'][0] == 1) {?>
            <div class="panel panel-default">
                <div class="panel-heading">Customer Detail</div>
                <div class="panel-body">
                    
                    <?php echo $this->Form->create('Reservation',  array('url' => array('controller' => 'calenders', 'action' => 'edit_reservation','1',$reservation_id),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                    <?php  echo ($this->Form->input('id'));?>
                    <?php  echo ($this->Form->input('reservation_type',array('type'=>'hidden','value'=>$this->request->params['pass'][0])));?>
                    <?php  echo ($this->Form->input('customer_id',array('type'=>'hidden','value'=>$this->request->data['Reservation']['customer_id'])));?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('customer_name', array('div' => false, 'label' => 'Name', 'type'=>'text','disabled'=>'true', 'value'=>$this->request->data['Customer']['name'], 'id'=>'customer_name', "class" => "form-control","maxlength"=>30))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('service_id', array('options'=>$service_list,'div'=>false,  'label' => 'Service',  "class" => "form-control")));?>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('employee_ids', array('multiple' => true,'options'=>$employee_list,'div'=>false,  'label' => 'Employee',  "class" => "form-control")));?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('channel', array('options'=>$channel_list,'div'=>false,  'label' => 'Channel',  "class" => "form-control")));?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('start_date', array('div' => false, 'label' => 'Start Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'start_date'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('end_date', array('div' => false, 'label' => 'End Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'end_date'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('start_time', array('div' => false, 'label' => 'Start Time', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'start_time'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('end_time', array('div' => false, 'label' => 'End Time', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'end_time'))); ?>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('price', array('div' => false, 'label' => 'Price', 'type' => 'text', "class" => "form-control"))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('note', array('div' => false, 'label' => 'Note', 'type'=>'text', "class" => "form-control"))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('all_day', array('type'=>'checkbox', 'label' => 'All Day') ); ?>
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12">
                        <?php echo ($this->Form->submit('Submit Button', array('class' => 'btn btn-primary', "div" => false))); ?>
                    </div>
                   <?php echo ($this->Form->end());    ?>
                </div>
            </div>
        <?php }elseif($this->request->params['pass'][0] == 2){?>
            <div class="panel panel-default">
                <div class="panel-heading">Event Detail</div>
                <div class="panel-body">
                    
                    <?php echo $this->Form->create('Reservation',  array('url' => array('controller' => 'calenders', 'action' => 'edit_reservation','2',$reservation_id),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                    <?php  echo ($this->Form->input('id'));?>
                    <?php  echo ($this->Form->input('reservation_type',array('type'=>'hidden','value'=>$this->request->params['pass'][0])));?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('event_name', array('div' => false, 'label' => 'Event Name', 'type'=>'text', 'id'=>'event_name', "class" => "form-control","maxlength"=>30))); ?>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('service_id', array('options'=>$service_list,'div'=>false,  'label' => 'Service',  "class" => "form-control")));?>
                        </div>
                    </div> -->

                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('employee_ids', array('multiple' => true,'options'=>$employee_list,'div'=>false,  'label' => 'Employee',  "class" => "form-control")));?>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('channel', array('options'=>$channel_list,'div'=>false,  'label' => 'Channel',  "class" => "form-control")));?>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('start_date', array('div' => false, 'label' => 'Start Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'start_date'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('end_date', array('div' => false, 'label' => 'End Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'end_date'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('start_time', array('div' => false, 'label' => 'Start Time', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'start_time'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('end_time', array('div' => false, 'label' => 'End Time', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'end_time'))); ?>
                        </div>
                    </div>
                    
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('price', array('div' => false, 'label' => 'Price', 'type' => 'text', "class" => "form-control"))); ?>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('note', array('div' => false, 'label' => 'Note', 'type'=>'text', "class" => "form-control"))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('all_day', array('type'=>'checkbox', 'label' => 'All Day') ); ?>
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12">
                        <?php echo ($this->Form->submit('Submit Button', array('class' => 'btn btn-primary', "div" => false))); ?>
                    </div>
                   <?php echo ($this->Form->end());    ?>
                </div>
            </div>
        <?php }elseif($this->request->params['pass'][0] == 3){?>
            <div class="panel panel-default">
                <div class="panel-heading">Staff Detail</div>
                <div class="panel-body">
                    
                    <?php echo $this->Form->create('Reservation',  array('url' => array('controller' => 'calenders', 'action' => 'edit_reservation','3',$reservation_id),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                    <?php  echo ($this->Form->input('id'));?>
                    <?php  echo ($this->Form->input('reservation_type',array('type'=>'hidden','value'=>$this->request->params['pass'][0])));?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('staff_name', array('div' => false, 'label' => 'Staff Name', 'type'=>'text', 'id'=>'staff_name', "class" => "form-control","maxlength"=>30))); ?>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('service_id', array('options'=>$service_list,'div'=>false,  'label' => 'Service',  "class" => "form-control")));?>
                        </div>
                    </div> -->

                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('employee_ids', array('multiple' => true,'options'=>$employee_list,'div'=>false,  'label' => 'Employee',  "class" => "form-control")));?>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php  echo ($this->Form->input('channel', array('options'=>$channel_list,'div'=>false,  'label' => 'Channel',  "class" => "form-control")));?>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('start_date', array('div' => false, 'label' => 'Start Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'start_date'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('end_date', array('div' => false, 'label' => 'End Date', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'end_date'))); ?>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('start_time', array('div' => false, 'label' => 'Start Time', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'start_time'))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('end_time', array('div' => false, 'label' => 'End Time', 'type' => 'text','readonly'=>'true', "class" => "form-control", 'id'=>'end_time'))); ?>
                        </div>
                    </div> -->
                    
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('price', array('div' => false, 'label' => 'Price', 'type' => 'text', "class" => "form-control"))); ?>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo ($this->Form->input('note', array('div' => false, 'label' => 'Note', 'type'=>'text', "class" => "form-control"))); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('all_day', array('type'=>'checkbox', 'label' => 'All Day') ); ?>
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12">
                        <?php echo ($this->Form->submit('Submit Button', array('class' => 'btn btn-primary', "div" => false))); ?>
                    </div>
                   <?php echo ($this->Form->end());    ?>
                </div>
            </div>
        <?php }?>
        </div><!-- /.panel-->
<script src="http://www.jquerycookbook.com/demos/scripts/jquery-ui.min.js"></script>

<script type="text/javascript">
// $(document).ready(function(){
//    $('#customer_name').on('keyup',function(){
//         console.log(this.value);
//         var keyword = this.value;
//         $.ajax({
//             url: "../customer_suggestion",
//             type: 'post',
//             data: {'keyword':keyword},
//             success: function(result){
//                 console.log(result);
//                 // var data = $.parseJSON(result )
//                 // var obj = JSON.parse(result);
//                 // $('#attendance_msg').text(obj.msg);
//                 // setTimeout(function() {
//                 //     location.reload();
//                 // }, 3000);
//                 // console.log(obj.msg );
//             }
//         });
//    });
// });


// $(function () {
//             var getData = function (request, response) {
//                 console.log(request);
//                 var site_url = '<?php //echo SITE_URL;?>/calenders/customer_suggestion/';
//                 $.getJSON(

//                     site_url + request.term,
//                     function (data) {
//                         response(data);
//                         console.log(response);
//                     });
//             };

//             var selectItem = function (event, ui) {
//                 console.log(ui);
//                 $("#customer_name").val(ui.Customer.value);
//             }

//             $("#customer_name").autocomplete({
//                 source: getData,
//                 select: selectItem,
//                 minLength: 2
//             });
//         });


</script>

        