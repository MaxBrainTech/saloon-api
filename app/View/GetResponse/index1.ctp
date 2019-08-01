<?php

// pr($transaction_data);
// pr($customer_decode->subscriptions->data[0]->current_period_start);
$paymentDate = gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->current_period_start);
$nextPaymentDate = gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->current_period_end);
$planName = $customer_decode->subscriptions->data[0]->items->data[0]->plan->nickname;
$customer_id = $customer_decode->id;
?>
<?php 

?>
<?php // echo Router::url( array('controller'=>'users','action'=>'my_shop'), true ); ?> 
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
                <?php // pr($data['userData']['User']['stripe_plan_status']); ?>
                <?php $this->Layout->sessionFlash(); ?>
                <div class="payemt_form">
                    <div class="payemt_form_inner">            
                            <h2>お支払い詳細</h2>
                            <table class="table" style="border: 2px solid #c08f33;">
                                <tbody>
                                    <tr>
                                        <th>プラン名</th>
                                        <td><?php echo $planName; ?></td>
                                    </tr>
                                    <tr>
                                        <th>支払いID</th>
                                        <td><?php echo $transaction_data['transaction_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い合計</th>
                                        <td><?php echo $transaction_data['transaction_amount'] .'¥'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い日</th>
                                        <td><?php echo $paymentDate; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い状況</th>
                                        <td><?php echo $transaction_data['transaction_status']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>次回お支払日</th>
                                        <td><?php echo $nextPaymentDate; ?></td>
                                    </tr>
                                    <tr>
                                        <th>お支払い額</th>
                                        <td>自動的に</td>
                                    </tr>
                                    <tr>
                                        <th>現在のプラン</th>
                                        <td>持続する</td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="2">
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#stopPlanModal" class="btn btn-danger" >計画を取り消す</a>
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
                        <div class="form-group text-center">
                            <p class="model_text">To Cancel plan please put the reason why you want to cancel the plan. We want to know why you want to Cancel Plan.</p>
                            <?php echo $this->Form->create('cancelPlan',  array('url' => array('controller' => 'users', 'action' => 'cancel_plan'),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Plan is too expensive.</label><br>
                                    <input type="radio" name="expensive" required="required"> Yes
                                    <input type="radio" name="expensive" required="required"> No
                                </div>                                
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Does not have what I need.</label><br>
                                    <input type="radio" name="not_fil_my_need" required="required"> Yes
                                    <input type="radio" name="not_fil_my_need" required="required"> No
                                </div>                                
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Found a better system.</label><br>
                                    <input type="radio" name="found_better" required="required"> Yes
                                    <input type="radio" name="found_better" required="required"> No
                                </div>                                
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>I am closing my beauty salon.</label><br>
                                    <input type="radio" name="closin_salon" required="required"> Yes
                                    <input type="radio" name="closin_salon" required="required"> No
                                </div>                                
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>It is hard to use.</label><br>
                                    <input type="radio" name="hard_to_use" required="required"> Yes
                                    <input type="radio" name="hard_to_use" required="required"> No
                                </div>                                
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <?php echo $this->Form->input('reason', array('type'=>'textarea','class'=>'form-control', 'label'=>false, 'placeholder'=>'Other reason please write Here...' , 'required'=>true)); ?>
                                </div>                                
                            </div>
                            <?php 
                                
                                echo $this->Form->input('user_id', array('type'=>'hidden','value'=>$data['userData']['User']['id'])); 
                                echo $this->Form->input('customer_id', array('type'=>'hidden','value'=>$customer_id));
                            ?>
                            <div class="col-lg-12">
                                <div class="form-group">
                            <?php echo ($this->Form->submit('Cencel Plan', array('class' => 'btn btn-danger', "div" => false))); ?>
                        </div></div>
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
