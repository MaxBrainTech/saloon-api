
<style type="text/css">
.payemt_form{
  background-color: #e3e2dd;
}
.payemt_form_inner{
  max-width: 700px;
  margin: 0 auto;
  padding: 3% 0;
  text-align: center;
}
th{
    padding: 15px 0 15px 30px !important;
    text-align: center;
}
td{
    padding: 15px 0 15px 30px !important;
}
.model_text {
    font-size: 20px;
    font-weight: bold;
    color: #000000;
}
.thank_you {
    color: #83cb2c;
    font-size: 25px;
}
.text-center{
    text-align: center;
}
</style>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <div class="payemt_form">
                    <div class="payemt_form_inner">            
                            <h2>お支払い詳細</h2>
                            <table class="table" style="border: 2px solid #c08f33;">
                                <tbody>
                                    <tr>
                                        <th>プラン名</th>
                                        <td><?php echo $data['planData']['StripePlanDetails']['plan_nickname']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>支払いID</th>
                                        <td><?php echo $data['transactionData']['StripeTransactionDetails']['transaction_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い合計</th>
                                        <td><?php echo $data['transactionData']['StripeTransactionDetails']['transaction_amount'].'¥'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い日</th>
                                        <td><?php echo $data['transactionData']['StripeTransactionDetails']['transaction_created']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い状況</th>
                                        <td><?php 
                                echo (($data['userData']['User']['stripe_payment_status'] ==1)?$data['transactionData']['StripeTransactionDetails']['transaction_status']:(($data['userData']['User']['stripe_payment_status'] ==2)?"失敗しました":"未払い")); ?></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo (($data['userData']['User']['stripe_plan_status'] ==1)?"次回お支払日":(($data['userData']['User']['stripe_plan_status'] ==2)?"計画終了日":"未払い")); ?></th>
                                        <td><?php echo (($data['userData']['User']['stripe_plan_status'] ==1 && $data['userData']['User']['stripe_payment_status'] ==1)?$data['subscriptionData']['StripeSubscriptionDetails']['subscription_current_period_end']:(($data['userData']['User']['stripe_plan_status'] ==2)?$data['userData']['User']['subscription_current_period_end']:"未払い")); ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い額</th>
                                        <td><?php echo (($data['userData']['User']['stripe_plan_status'] ==1 && $data['userData']['User']['stripe_payment_status'] ==1)?"自動的に":(($data['userData']['User']['stripe_plan_status'] ==2)?"やめる":"未払い"));?></td>
                                    </tr>
                                    <tr>
                                        <th>現在のプラン</th>
                                        <td><?php echo (($data['userData']['User']['stripe_plan_status'] ==1 && $data['userData']['User']['stripe_payment_status'] ==1)?"持続する":(($data['userData']['User']['stripe_plan_status'] ==2)?"やめる":"未払い"));?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                                if(!$data['cancelData']['PlanCancelRequest']['status']){                                            
                                                if($data['userData']['User']['stripe_plan_status'] ==1 && $data['userData']['User']['stripe_payment_status'] ==1) { ?>
                                                <div class="col-lg-12"><a href="#" data-toggle="modal" data-target="#stopPlanModal" class="btn btn-danger">計画を取り消す</a></div>
                                            <?php } }?>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <!-- <a href="<?php echo "/users/my_shop"; ?>" /><button class="btn btn-primary">Go To Website</button></a> -->
                    </div>
                </div>
            </div>
        </div>


<!-- Modal Stop Plan -->



<!-- Modal Stop Plan -->
<div class="modal fade" id="stopPlanModal" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Cancel Plan</h4>
              <span id="attendance_msg" style="color: red;"></span>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="form-group">
<<<<<<< HEAD
                            <p class="model_text">To Cancel plan please put the reason why you want to cancel the plan. We want to know why you want to Cancel Plan.</p>
=======
>>>>>>> efc1dce70fd1c8ed77d49f9c4b402ea12acda7a4
                            <?php echo $this->Form->create('cancelPlan',  array('url' => array('controller' => 'users', 'action' => 'cancel_plan'),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                            <p class="model_text">Reasons for leaving our plan.</p>
                            <div class="col-lg-12">
                                <div class="form-group">
<<<<<<< HEAD
                                    <label>Plan is too expensive.</label><br>
                                    <input type="radio" name="expensive" required="required"> Yes
                                    <input type="radio" name="expensive" required="required"> No
=======
                                    <?php echo $this->Form->input('expensive', array('type'=>'checkbox', 'value'=>'1', 'label'=>' Plan is too expensive.', )); ?>
>>>>>>> efc1dce70fd1c8ed77d49f9c4b402ea12acda7a4
                                </div>                                
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
<<<<<<< HEAD
                                    <label>Does not have what I need.</label><br>
                                    <input type="radio" name="not_fil_my_need" required="required"> Yes
                                    <input type="radio" name="not_fil_my_need" required="required"> No
=======
                                    <?php echo $this->Form->input('not_fil_my_need', array('type'=>'checkbox', 'value'=>'1', 'label'=>' Does not have what I need.', )); ?>
>>>>>>> efc1dce70fd1c8ed77d49f9c4b402ea12acda7a4
                                </div>                                
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
<<<<<<< HEAD
                                    <label>Found a better system.</label><br>
                                    <input type="radio" name="found_better" required="required"> Yes
                                    <input type="radio" name="found_better" required="required"> No
=======
                                    <?php echo $this->Form->input('found_better', array('type'=>'checkbox', 'value'=>'1', 'label'=>' Found a better system.', )); ?>
>>>>>>> efc1dce70fd1c8ed77d49f9c4b402ea12acda7a4
                                </div>                                
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
<<<<<<< HEAD
                                    <label>I am closing my beauty salon.</label><br>
                                    <input type="radio" name="closin_salon" required="required"> Yes
                                    <input type="radio" name="closin_salon" required="required"> No
=======
                                    <?php echo $this->Form->input('closing_salon', array('type'=>'checkbox', 'value'=>'1', 'label'=>' I am closing my beauty salon.', )); ?>
>>>>>>> efc1dce70fd1c8ed77d49f9c4b402ea12acda7a4
                                </div>                                
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
<<<<<<< HEAD
                                    <label>It is hard to use.</label><br>
                                    <input type="radio" name="hard_to_use" required="required"> Yes
                                    <input type="radio" name="hard_to_use" required="required"> No
=======
                                    <?php echo $this->Form->input('hard_to_use', array('type'=>'checkbox', 'value'=>'1', 'label'=>' It is hard to use.', )); ?>
>>>>>>> efc1dce70fd1c8ed77d49f9c4b402ea12acda7a4
                                </div>                                
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <?php echo $this->Form->input('reason', array('type'=>'textarea','class'=>'form-control', 'label'=>false, 'placeholder'=>'Other reason please write Here...', 'required'=>true)); ?>
                                </div>                                
                            </div>
                            
                            <?php 
                                
                                echo $this->Form->input('user_id', array('type'=>'hidden','value'=>$data['userData']['User']['id'])); 
                                echo $this->Form->input('customer_id', array('type'=>'hidden','value'=>$data['subscriptionData']['StripeSubscriptionDetails']['customer_id'])); 
                            ?>
                            <div class="col-lg-12">
                                <div class="form-group">
                                 <?php echo ($this->Form->submit('Cencel Plan', array('class' => 'btn btn-danger', "div" => false))); ?>
                                </div>
                            </div>
                            <?php echo ($this->Form->end());    ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
</div>
<!-- <div class="modal fade" id="stopPlanModal" role="dialog">
    <div class="modal-dialog">    
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Stop Plan</h4>
              <span id="attendance_msg" style="color: red;"></span>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="form-group text-center">
                            <p class="model_text">If you click on stop plan then you can access the JTS Board Service on till Plan End Date (<?php echo $nextPaymentDate; ?>).</p>
                            <p class="model_text thank_you">Thank You</p>
                            <?php echo $this->Form->create('stopPayment',  array('url' => array('controller' => 'stripe_payments', 'action' => 'stop_plan'),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                            <?php 
                                echo $this->Form->input('user_id', array('type'=>'hidden','value'=>$transaction_data['user_id'])); 
                                echo $this->Form->input('customer_id', array('type'=>'hidden','value'=>$customer_id)); 
                            ?>
                            <?php echo ($this->Form->submit('Stop Plan', array('class' => 'btn btn-danger', "div" => false))); ?>
                            <?php echo ($this->Form->end());    ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
</div> -->
