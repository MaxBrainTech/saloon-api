<?php  //print_r($user);?>
<div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-home"></em>
        </a></li>
        <li class="active">マイショップ</li>
      </ol>
    </div><!--/.row-->
    
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">マイショップ</h1>
      </div>
    </div><!--/.row-->
    
      
    
      <div class="panel panel-default">
                    <div class="panel-heading">顧客 Detail</div>
                    <?php $this->Layout->sessionFlash(); ?>
                    <div class="panel-body">  
                      
                        <div class="col-sm-6">
    <div class="form-group">
      <label>名前:</label> 
      <span class="EditProfTxtRi"><?php echo $user['User']['name']?></span>
    
    </div>
</div>


 <div class="col-sm-6">
    <div class="form-group">
      <label>サロン名:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['salon_name']?></span></li>
    </div>
</div>
 <div class="col-sm-6">
    <div class="form-group">
      <label>メール :</label>
      <span class="EditProfTxtRi"><a href="#"><?php echo $user['User']['email']?></a></span></li>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>会社名:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['company_name']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>ホームページのURL:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['website']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
      <label>電話番号:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['tel']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
      <label>郵便番号:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['zip_code']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>市町村:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['city']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
      <label>住所:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['address1']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
      <label>アパート/マンション名:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['prefecture']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>従業員の数:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['employee_number']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>使用している広告:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['advertisement']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
      <label>従業員PINコード:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['employee_pin_number']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
      <label>顧客PINコード:</label>
      <span class="EditProfTxtRi"><?php echo $user['User']['customer_pin_number']?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>キャッシャー:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['cash_box']?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>Start Date:</label>
    <span class="EditProfTxtRi"><?php echo $user['User']['month_start_date']?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>週末:</label>
                      <span class="EditProfTxtRi"><?php echo $user['User']['weekend']?></span>
    </div>
</div>
                    

<div class="col-md-12">
  <?php echo $this->Html->link("Edit", array( 'controller'=>'users', 'action'=>'edit', $user['User']['id']), array("class"=>"btn btn-primary", "escape"=>false)); ?>
     <?php //echo ($this->Form->submit('Edit', array('class' => 'btn btn-primary', "div" => false))); ?>
    <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
</div>
    <?php // echo ($this->element('fornt/User/form'));?>
            <!-- <div class="form-group">
                <label>Text area</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>
             -->
           <?php // echo ($this->Form->end()); ?>  
                            
        </div>
    </div>
</div><!-- /.panel-->
