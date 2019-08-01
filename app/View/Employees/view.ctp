<?php //echo "<pre>";print_r($Employee); echo "</pre>";?>
<div class="row">
<ol class="breadcrumb">
  <li><a href="#">
    <em class="fa fa-home"></em>
  </a></li>
  <li class="active">Staff View</li>
</ol>
</div><!--/.row-->

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"></h1>
  </div>
  </div><!--/.row-->
  <div class="panel panel-default">
    <div class="panel-heading">Staff Detail</div>
    <div class="panel-body">
      <div class="col-sm-6">
        <div class="form-group">
          <label>名前:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['name']); ?></span>
          
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>メール:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['email']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>誕生日:</label>
          <span class="EditProfTxtRi"><?php echo $Employee['Employee']['dob']?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>電話:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['phone']); ?></span></li>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>参加日:</label>
          <span class="EditProfTxtRi"><a href="#"><?php echo ($Employee['Employee']['joining_date']); ?></a></span></li>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>指定:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['designation']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>給料:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['salary']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>住所:</label>
          <span class="EditProfTxtRi"><?php echo $Employee['Employee']['address']?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>Role Title:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['role_title']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>Lunch Time:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['lunch_time']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>Start Lunch Time:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['start_lunch_time']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>End Lunch Time:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['end_lunch_time']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>従業員コード:</label>
          <span class="EditProfTxtRi"><?php echo ($Employee['Employee']['emp_code']); ?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label>Technician Person:</label>
          <span class="EditProfTxtRi"><?php echo (($Employee['Employee']['emp_code']==1)?"Yes":"No");?></span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <?php echo $this->Html->image(SITE_URL . DS . CUSTOMER_NOTE_IMAGE . DS ."original". DS.$Employee['Employee']['image'], array('alt' => 'CakePHP', 'border' => '0', 'height'=>'200px', 'width'=>'200px')); ?>
        </div>
      </div>      
      <div class="col-md-12">
        <?php echo $this->Html->link("戻る", array('action' => 'employee_list'), array("class" => "button", "escape" => false)); ?>
      </div>
    </div>
  </div>
  </div><!-- /.panel-->