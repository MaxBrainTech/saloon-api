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
    
    jQuery('#UserStartTime').datetimepicker();
    jQuery('#UserEndTime').datetimepicker();
    jQuery('#UserStartTime, #UserEndTime').datetimepicker({
    // format:'Y-m-d H:i'
    format:'H:i',
    datepicker:false,
    step:30,
    // allowTimes:['12:00','13:00','15:00','17:00','17:05','17:20','19:00','20:00'],    
    });
});   
</script>

 <div class="container-ipad container-fluid">
    <div class="text-right" style="margin: 10px 20px 0px 0px;">
        <a href="/users/payment_info/<?php echo $id; ?>" class="btn btn-primary mt-4">支払い情報</a>
    </div>
	<?php $this->Layout->sessionFlash(); ?>			
	<?php echo $this->Form->create('User',	array('url' => array('controller' => 'users', 'action' => 'app_edit'),'type'=>'file','inputDefaults' => array(	'error' => array('attributes' => array('wrap' => 'span',	'class' => 'input-notification error png_bg')))	));?>
        <div class="row">  
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('name', array('div' => false, 'label' => '名', "class" => "form-control","maxlength"=>30))); ?>
                </div>
            </div>
            


            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('email', array('div' => false, 'label' => 'Eメール', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('company_name', array('div' => false, 'label' => '会社名', "class" => "form-control","maxlength"=>30))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('image', array('type' => 'file', 'div' => false, 'label' => 'ユーザー画像', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('salon_name', array('div' => false, 'label' => 'サロン名', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('website', array('div' => false, 'label' => 'ウェブサイトのURL', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('tel', array('div' => false, 'label' => '電話番号', "class" => "form-control"))); ?>
                </div>
            </div>
           
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('zip_code', array('div' => false, 'label' => '郵便番号', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('city', array('div' => false, 'label' => 'シティ', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('address1', array('div' => false, 'label' => '道の名前', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                <?php echo ($this->Form->input('address2', array('div' => false, 'label' => 'アパート名', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('employee_number', array('div' => false, 'label' => '従業員番号', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('advertisement', array('div' => false, 'label' => '広告', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('avr_customer', array('div' => false, 'label' => '平均顧客', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>
            
            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('employee_pin_number', array('div' => false, 'label' => '社員のピン番号', "class" => "form-control"))); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                <?php echo ($this->Form->input('customer_pin_number', array('div' => false, 'label' => '顧客のピン番号', "class" => "form-control"))); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                <?php echo ($this->Form->input('cash_box', array('div' => false, 'label' => 'キャッシュボックス', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>

            <div class="col-sm-6 ">
                <div class="form-group">
                <?php echo ($this->Form->input('month_start_date', array('div' => false, 'label' => '日付', "class" => "form-control"))); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                	<label for="UserCashBox">週末</label>
                    <?php  echo ($this->Form->input('weekend', array('options'=>Configure::read('App.Weekday'),'div'=>false, 'label'=>false, "class" => "form-control")));?> 

                </div>
            </div>  
             <div class="col-sm-6">
                <div class="form-group">
                    <?php echo ($this->Form->input('over_time', array('div' => false, 'label' => '残業（分）', 'type' => 'number', "class" => "form-control", "maxlength"=> '3'))); ?>
                </div>
            </div>  
             <div class="col-sm-6">
                <div class="form-group">
                    <?php echo ($this->Form->input('start_time', array('div' => false, 'label' => '始業時間', 'type' => 'text','readonly'=>'true', "class" => "form-control"))); ?>
                </div>
            </div>  

             <div class="col-sm-6">
                <div class="form-group">
                    <?php echo ($this->Form->input('end_time', array('div' => false, 'label' => '終業時間', 'type' => 'text','readonly'=>'true', "class" => "form-control"))); ?>
                </div>
            </div>  
           
             <div class="col-sm-6">
                <div class="form-group">
                    <?php echo ($this->Form->input('sb_username', array('div' => false, 'label' => 'Salon Board Username', 'type' => 'text',"class" => "form-control"))); ?>
                </div>
            </div>  

             <div class="col-sm-6">
                <div class="form-group">
                    <?php echo ($this->Form->input('sb_password', array('div' => false, 'label' => 'Salon Board Password', 'type' => 'text', "class" => "form-control"))); ?>
                </div>
            </div>  


                           

            <div class="col-sm-12">
                    <?php  echo ($this->Form->submit('送信ボタン', array('class' => 'btn btn-primary', "div" => false))); ?>
            </div>
 </div>                           
		
<?php echo ($this->Form->end());	?>	

</div><!-- /.panel-->


