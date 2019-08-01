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
    jQuery('#date').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false,
    });
});   
</script>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Add Holiday</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
    </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">Add Holiday</div>
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <?php echo $this->Form->create('Holiday',  array('url' => array('controller' => 'holidays', 'action' => 'add_holiday'),'type'=>'file','inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                <?php
                    echo $this->Form->input('id');
                    $user_id = $_SESSION['User']['id'];  
                    echo $this->Form->input('user_id', array('type'=>'hidden','value'=>$user_id));
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('title', array('div' => false, 'label' => '休暇名', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('date', array('div' => false, 'label' => '祝日', 'type'=>'text','id'=>'date', "class" => "form-control",'readonly'=>'readonly'))); ?>
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
