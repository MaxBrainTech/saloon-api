<?php //echo "<pre>";print_r($this->request->data); echo "</pre>";?>
<?php 
    echo $this->Html->script(
                        array(
                            'datepicker/jquery.js',
                            'datepicker/jquery.datetimepicker.full.js'
                        ));
    echo $this->Html->css(array('datepicker/jquery.datetimepicker.css'));
?>

<script>
 jQuery(document).ready(function () {
    'use strict';
    jQuery('#EmployeeDob, #EmployeeJoiningDate').datetimepicker();
    jQuery('#EmployeeDob, #EmployeeJoiningDate').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false,
    });
    jQuery('#EmployeeStartLunchTime').datetimepicker();
    jQuery('#EmployeeEndLunchTime').datetimepicker();
    jQuery('#EmployeeStartLunchTime, #EmployeeEndLunchTime').datetimepicker({
    // format:'Y-m-d H:i'
    format:'H:i',
    datepicker:false,
    step:30,
    // allowTimes:['12:00','13:00','15:00','17:00','17:05','17:20','19:00','20:00'],    
    });
});   
</script>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">スタッフ 編集</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
        </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">スタッフ Detail</div>
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <?php echo $this->Form->create('Employee',  array('url' => array('controller' => 'employees', 'action' => 'edit'),'type'=>'file','inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                <?php  echo ($this->Form->input('id'));?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('name', array('div' => false, 'label' => '名前', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('email', array('div' => false, 'label' => 'メール', "class" => "form-control"))); ?>
                    </div>
                </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('phone', array('div' => false, 'label' => '電話番号', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('designation', array('div' => false, 'label' => '指定','type'=>'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('salary', array('div' => false, 'label' => '給料', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('address', array('div' => false, 'label' => '住所', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('role_title', array('div' => false, 'label' => '役割', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('joining_date', array('div' => false, 'label' => '参加日', 'type' => 'text','readonly'=>'true', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('dob', array('div' => false, 'label' => '誕生日', 'type' => 'text','readonly'=>'true', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('emp_code', array('div' => false, 'label' => '従業員コード', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="UserCashBox">この従業員は施術者ですか？</label>
                    <?php  echo ($this->Form->input('is_technician', array('options'=>Configure::read('App.boolean'),'div'=>false, 'label'=>false, "class" => "form-control")));?> 
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('lunch_time', array('div' => false, 'label' => 'ランチタイム', 'type' => 'text', "class" => "form-control", 'readonly'=>'readonly'))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('start_lunch_time', array('div' => false, 'label' => 'ランチタイム開始時間', 'type' => 'text', "class" => "form-control", 'readonly'=>'readonly'))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('end_lunch_time', array('div' => false, 'label' => 'ランチタイム終了時間','type'=>'text', "class" => "form-control", 'readonly'=>'readonly'))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $this->Html->image(SITE_URL . DS . CUSTOMER_NOTE_IMAGE . DS ."original". DS.$this->request->data['Employee']['image'], array('alt' => 'No Image', 'border' => '0', 'height'=>'50px', 'width'=>'50px')); ?>
                        <?php 
                        echo $this->Form->input('image',array('type' => 'file','label'=>'スタッフの画像'));
                        ?>
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <?php echo ($this->Form->submit('送信', array('class' => 'btn btn-primary', "div" => false))); ?>
                    <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                </div>
               <?php echo ($this->Form->end());    ?>
            </div>
        </div>
        </div><!-- /.panel-->



        