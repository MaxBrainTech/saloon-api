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
                            <h2>Thank You</h2>
                            <table class="table" style="border: 2px solid #c08f33;">
                                <tbody>
                                    <tr>
                                        <th>Your Payment was successfull.</th>
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>

