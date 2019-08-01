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
    jQuery('#CustomerDob').datetimepicker();
    jQuery('#CustomerDob').datetimepicker({
    // format:'Y-m-d H:i'
    format:'Y-m-d',
    timepicker:false
    });
});   
</script>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Customer Edit</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
        </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">Customer Detail</div>
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <?php echo $this->Form->create('Customer',  array('url' => array('controller' => 'customers', 'action' => 'edit'),'type'=>'file','inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                <?php  echo ($this->Form->input('id'));?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('name', array('div' => false, 'label' => '名前', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php  echo ($this->Form->input('service_id', array('options'=>$service_list,'div'=>false,  'label' => 'サービス',  "class" => "form-control")));?> 
                   
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('email', array('div' => false, 'label' => 'メール', "class" => "form-control"))); ?>
                    </div>
                </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('tel', array('div' => false, 'label' => '電話番号', 'type'=>'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('city', array('div' => false, 'label' => '市町村','type'=>'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('address1', array('div' => false, 'label' => '住所', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('address2', array('div' => false, 'label' => 'アパート/マンション名', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('zip_code', array('div' => false, 'label' => '郵便番号', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('kana', array('div' => false, 'label' => 'カナ', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>                
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php $gender_option = array('male'=>'Male', 'female'=>'Female')?>
                        <?php $attributes = array('value' => $this->request->data['User']['gender'], 'legend' => '性別');?>
                        <?php echo ($this->Form->radio('gender',$gender_option, $attributes)); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('dob', array('div' => false, 'label' => '誕生日', 'type' => 'text','readonly'=>'true', "class" => "form-control"))); ?>
                    </div>
                </div>
               
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('age', array('div' => false, 'label' => '年齢', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('job', array('div' => false, 'label' => '職業', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('subscription_of_news', array('div' => false, 'label' => 'お得な情報を受取る', 'type' => 'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('know_about_company', array('div' => false, 'label' => 'どうしてこのサロンを知りましたか？','type'=>'text', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('how_did_you_come', array('div' => false, 'label' => '何でサロンまでお越しになられましたか？', "class" => "form-control"))); ?>
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



        