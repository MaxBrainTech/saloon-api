<?php //echo "<pre>";print_r($Customer); echo "</pre>";?>
<div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Customer View</li>
      </ol>
    </div><!--/.row-->
    
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Customer View</h1>
      </div>
    </div><!--/.row-->
      <div class="panel panel-default">
        <div class="panel-heading">Customer Detail</div>
        <div class="panel-body"> 
            <div class="col-sm-6">
    <div class="form-group">
      <label>名前:</label>
      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['name']); ?></span>
    
    </div>
</div>


 <div class="col-sm-6">
    <div class="form-group">
    <label>カナ:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['kana']); ?></span></li>
    </div>
</div>
 <div class="col-sm-6">
    <div class="form-group">
    <label>サービス 名前:</label>
      <span class="EditProfTxtRi"><?php echo ($Customer['Service']['name']); ?></span></li>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>メール:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['email']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>性別:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['gender']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>Date of Birth:</label>
                      <span class="EditProfTxtRi"><?php echo $Customer['Customer']['dob']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>電話 Number:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['tel']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>郵便番号:</label>
                      <span class="EditProfTxtRi"><?php echo $Customer['Customer']['zip_code']?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>住所:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['address1']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>お得な情報を受取る:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['subscription_of_news']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>職業:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['job']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>どうしてこのサロンを知りましたか？:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['know_about_company']); ?></span>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
    <label>何でサロンまでお越しになられましたか？:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Customer']['how_did_you_come']); ?></span>
    </div>
</div>
<!-- <div class="col-sm-6">
    <div class="form-group">
    <label>Service Name:</label>
                      <span class="EditProfTxtRi"><?php echo ($Customer['Service']['name']); ?></span>
    </div>
</div> -->

<div class="col-sm-6">
    <div class="form-group">
    <label>Profile Created:</label>
                      <span class="EditProfTxtRi"><?php echo ($this->Time->niceShort(strtotime($Customer['Customer']['created']))); ?></span>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
    <label>Updated on:</label>
                      <span class="EditProfTxtRi"><?php echo ($this->Time->niceShort(strtotime($Customer['Customer']['modified']))); ?></span>
    </div>
</div>

<!-- <div class="col-sm-6">
    <div class="form-group">
    <label>Weekend:</label>
                      <span class="EditProfTxtRi"><?php //echo ($Customer['Customer']['name']); ?></span>
    </div>
</div> -->
                    

<div class="col-md-12">
    <?php echo $this->Html->link("Back", array('action' => 'customer_list'), array("class" => "button", "escape" => false)); ?>
  <?php //echo $this->Html->link("Edit", array('controller'=>'users', 'action'=>'edit', $user['User']['id']), array("class"=>"btn btn-primary", "escape"=>false)); ?>
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



