<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">チケットを追加</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
    </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">チケットを追加</div>
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <?php echo $this->Form->create('Ticket',  array('url' => array('controller' => 'tickets', 'action' => 'add_ticket'),'type'=>'file','inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                <?php
                    echo $this->Form->input('id');
                    $user_id = $_SESSION['User']['id'];  
                    echo $this->Form->input('user_id', array('type'=>'hidden','value'=>$user_id));
                ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_name', array('div' => false, 'label' => 'チケット名', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_price', array('div' => false, 'label' => 'チケット金額', 'type'=>'text','id'=>'ticket_price', "class" => "form-control"))); ?>
                    </div>
                </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_amount', array('div' => false, 'label' => 'チケット合計','type'=>'text','id'=>'ticket_amount', "class" => "form-control"))); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('ticket_num_time', array('div' => false, 'label' => 'Ticket Number','type'=>'text','id'=>'ticket_num_time', "class" => "form-control"))); ?>
                    </div>
                </div>
                
                
                <div class="col-sm-12">
                    <?php echo ($this->Form->submit('送信', array('class' => 'btn btn-primary','id'=>'add_ticket', "div" => false))); ?>
                    <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                </div>
               <?php echo ($this->Form->end());    ?>
            </div>
        </div>
        </div><!-- /.panel-->
<script type="text/javascript">
    document.getElementById('ticket_price').addEventListener('input', event =>event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
    document.getElementById('ticket_amount').addEventListener('input', event =>event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
    // document.getElementById('ticket_num_time').addEventListener('input', event =>event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
    // $(document).ready(function(){
    //     var val;
    //     $('#ticket_price').on('keyup',function(){
    //         // console.log($('#product_price').val());
    //             val = $('#ticket_price').val() + '円';
    //         // console.log(val);
    //         $('#ticket_price').val(val);
    //     });
    //     $('#ticket_amount').on('keyup',function(){
    //         // console.log($('#product_price').val());
    //             val = $('#ticket_amount').val() + '円';
    //         // console.log(val);
    //         $('#ticket_amount').val(val);
    //     });
    // });
    $(document).ready(function(){
        $('#add_ticket').on('click',function(){
            ticket_amount = $('#ticket_amount').val();
            ticket_number = $('#ticket_num_time').val();
            if((ticket_amount == '') && (ticket_number == '')){
                alert("Please Enter One Field Ticket Amount and Ticket Number");
                event.preventDefault();
            }else if((ticket_amount != 0) && (ticket_number != 0)){
                alert("You can't enter both Ticket Amount and Ticket Number");
                event.preventDefault();
            }
            
        });
    });
</script>